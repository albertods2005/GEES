<x-guest-layout>
    <section class="space-y-6">
        <div class="public-panel-header lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-3xl">
                <h2 class="text-3xl font-semibold text-white">Panel de acceso</h2>
                <p class="mt-3 text-base leading-8 text-slate-300">
                    Entra con tu correo y tu contraseña para recuperar el control de tu equipo, tus convocatorias y tu informacion compartida.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="rounded-2xl bg-white px-6 py-4 text-sm font-semibold text-slate-900 shadow-[0_14px_32px_rgba(255,255,255,0.16)] transition hover:-translate-y-0.5">
                    Crear cuenta
                </a>
                <a href="{{ route('home') }}" class="public-cta-secondary rounded-2xl px-6 py-4">
                    Volver
                </a>
            </div>
        </div>

        <div class="public-form-panel rounded-[2rem] p-6 lg:p-8">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="public-card-kicker">Inicia sesion</p>
                    <h1 class="mt-3 text-4xl font-semibold tracking-tight text-white">Accede a tu cuenta</h1>
                </div>

                <a href="{{ route('home') }}" class="public-cta-secondary self-start rounded-2xl px-6 py-3">
                    Cancelar
                </a>
            </div>

            <x-auth-session-status class="mt-6 rounded-2xl border border-emerald-300/20 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-100" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="mt-10 space-y-6">
                @csrf

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="correo" class="public-label">Correo</label>
                        <input id="correo" class="public-input rounded-[1.1rem] py-4 text-base" type="email" name="correo" value="{{ old('correo') }}" required autofocus autocomplete="username" placeholder="tu@equipo.com" />
                        <x-input-error :messages="$errors->get('correo')" class="auth-error" />
                    </div>

                    <div>
                        <label for="contrasena" class="public-label">Contraseña</label>
                        <x-password-input id="contrasena" class="public-input rounded-[1.1rem] py-4 text-base" name="contrasena" required autocomplete="current-password" placeholder="Escribe tu contraseña" />
                        <x-input-error :messages="$errors->get('contrasena')" class="auth-error" />
                    </div>
                </div>

                <div class="flex flex-col gap-4 text-sm text-slate-300 md:flex-row md:items-center md:justify-between">
                    <label for="remember_me" class="inline-flex items-center gap-3">
                        <input id="remember_me" type="checkbox" class="auth-check" name="remember">
                        <span>Recordarme</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="auth-link" href="{{ route('password.request') }}">¿Olvidaste la contraseña?</a>
                    @endif
                </div>

                <div class="rounded-[1.75rem] border border-white/10 bg-white/5 px-5 py-5">
                    <p class="text-lg font-semibold text-white">¿Todavia no tienes acceso?</p>
                    <p class="mt-2 text-sm leading-7 text-slate-300">
                        Crea tu cuenta para entrar en la plataforma con los datos reales de tu perfil y seguir el mismo flujo visual del resto de GEES.
                    </p>
                </div>

                <div class="flex flex-col gap-4 pt-2 sm:flex-row sm:items-center sm:justify-between">
                    <a class="auth-link text-base" href="{{ route('register') }}">Crear cuenta</a>
                    <button type="submit" class="rounded-2xl bg-white px-6 py-4 text-sm font-semibold text-slate-900 shadow-[0_14px_32px_rgba(255,255,255,0.16)] transition hover:-translate-y-0.5">
                        Iniciar sesión
                    </button>
                </div>
            </form>
        </div>
    </section>
</x-guest-layout>
