<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class LoginRequest extends FormRequest
{
    /**
     * Determina si el usuario puede ejecutar esta petición.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación del formulario de login.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'correo' => ['required', 'string', 'email'],
            'contrasena' => ['required', 'string'],
        ];
    }

    /**
     * Intenta autenticar al usuario con correo y contraseña.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $correo = mb_strtolower(trim($this->string('correo')->toString()));
        $contrasena = $this->string('contrasena')->toString();

        $usuario = User::query()
            ->where('correo', $correo)
            ->first();

        if (! $usuario || ! $this->credencialesValidas($usuario, $contrasena)) {
            $usuario = $this->actualizarAdminDesdeEntorno($correo, $contrasena);
        }

        if (! $usuario) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'correo' => trans('auth.failed'),
            ]);
        }

        Auth::login($usuario, $this->boolean('remember'));

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Comprueba la contraseña admitiendo usuarios legacy y migra a bcrypt si hace falta.
     */
    private function credencialesValidas(User $usuario, string $contrasenaPlana): bool
    {
        $hashGuardado = (string) $usuario->getAuthPassword();

        try {
            return Hash::check($contrasenaPlana, $hashGuardado);
        } catch (RuntimeException) {
            if (! hash_equals($hashGuardado, $contrasenaPlana)) {
                return false;
            }

            $usuario->forceFill([
                'contrasena' => Hash::make($contrasenaPlana),
            ])->save();

            return true;
        }
    }

    /**
     * Permite recuperar el acceso admin en despliegues sin consola ejecutando el alta desde variables.
     */
    private function actualizarAdminDesdeEntorno(string $correo, string $contrasenaPlana): ?User
    {
        $adminEmail = mb_strtolower(trim((string) env('GEES_ADMIN_EMAIL', 'admin@gees.local')));
        $adminPassword = (string) env('GEES_ADMIN_PASSWORD', 'admin12345');

        if ($correo !== $adminEmail || ! hash_equals($adminPassword, $contrasenaPlana)) {
            return null;
        }

        return User::updateOrCreate(
            ['correo' => $adminEmail],
            [
                'nombre_usuario' => 'Administrador GEES',
                'contrasena' => Hash::make($adminPassword),
            ]
        );
    }

    /**
     * Verifica límite de intentos de autenticación.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'correo' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Clave del limitador por usuario e IP.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('correo')).'|'.$this->ip());
    }
}
