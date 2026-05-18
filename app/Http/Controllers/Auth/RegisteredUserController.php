<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Muestra la vista de registro.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Procesa la petición de registro y crea un usuario GEES.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre_usuario' => ['required', 'string', 'max:255'],
            'correo' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:usuarios,correo'],
            'contrasena' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $usuario = User::create([
            'nombre_usuario' => $request->nombre_usuario,
            'correo' => $request->correo,
            'contrasena' => Hash::make($request->contrasena),
        ]);

        event(new Registered($usuario));

        Auth::login($usuario);

        return redirect(route($usuario->isAdmin() ? 'dashboard' : 'home', absolute: false));
    }
}
