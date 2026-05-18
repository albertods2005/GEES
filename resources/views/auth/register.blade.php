<x-guest-layout>
    <section class="space-y-6">
        <div class="public-panel-header lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-3xl">
                <h2 class="text-3xl font-semibold text-white">Panel de registro</h2>
                <p class="mt-3 text-base leading-8 text-slate-300">
                    Usa este bloque para crear tu acceso a GEES con tu nombre, tu correo y la contraseña que utilizaras despues para entrar.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('login') }}" class="rounded-2xl bg-white px-6 py-4 text-sm font-semibold text-slate-900 shadow-[0_14px_32px_rgba(255,255,255,0.16)] transition hover:-translate-y-0.5">
                    Iniciar sesión
                </a>
                <a href="{{ route('home') }}" class="public-cta-secondary rounded-2xl px-6 py-4">
                    Cerrar panel
                </a>
            </div>
        </div>

        <div class="public-form-panel rounded-[2rem] p-6 lg:p-8">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="public-card-kicker">Nuevo bloque</p>
                    <h1 class="mt-3 text-4xl font-semibold tracking-tight text-white">Crear cuenta</h1>
                </div>

                <a href="{{ route('login') }}" class="public-cta-secondary self-start rounded-2xl px-6 py-3">
                    Cancelar
                </a>
            </div>

            <form method="POST" action="{{ route('register') }}" class="mt-10 space-y-6">
                @csrf

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="nombre_usuario" class="public-label">Nombre</label>
                        <input id="nombre_usuario" class="public-input rounded-[1.1rem] py-4 text-base" type="text" name="nombre_usuario" value="{{ old('nombre_usuario') }}" required autofocus autocomplete="name" placeholder="Tu nombre completo" />
                        <x-input-error :messages="$errors->get('nombre_usuario')" class="auth-error" />
                    </div>

                    <div>
                        <label for="correo" class="public-label">Correo</label>
                        <input id="correo" class="public-input rounded-[1.1rem] py-4 text-base" type="email" name="correo" value="{{ old('correo') }}" required autocomplete="username" placeholder="tu@equipo.com" />
                        <x-input-error :messages="$errors->get('correo')" class="auth-error" />
                    </div>

                    <div>
                        <label for="contrasena" class="public-label">Contraseña</label>
                        <x-password-input id="contrasena" class="public-input rounded-[1.1rem] py-4 text-base" name="contrasena" required autocomplete="new-password" placeholder="Crea una contraseña segura" />
                        <x-input-error :messages="$errors->get('contrasena')" class="auth-error" />
                    </div>

                    <div>
                        <label for="contrasena_confirmation" class="public-label">Confirmar contraseña</label>
                        <x-password-input id="contrasena_confirmation" class="public-input rounded-[1.1rem] py-4 text-base" name="contrasena_confirmation" required autocomplete="new-password" placeholder="Repite la contraseña" />
                        <x-input-error :messages="$errors->get('contrasena_confirmation')" class="auth-error" />
                    </div>
                </div>

                <div class="rounded-[1.75rem] border border-blue-300/15 bg-[linear-gradient(180deg,rgba(37,99,235,0.14),rgba(15,23,42,0.2))] px-5 py-5">
                    <p class="text-lg font-semibold text-white">Acceso preparado para crecer</p>
                    <p class="mt-2 text-sm leading-7 text-slate-300">
                        El alta usa los datos reales del proyecto y mantiene la misma estructura visual de paneles que ya aparece en otras zonas de la web.
                    </p>
                </div>

                <div class="flex flex-col gap-4 pt-2 sm:flex-row sm:items-center sm:justify-between">
                    <a class="auth-link text-base" href="{{ route('login') }}">Ya tengo cuenta</a>

                    <button type="submit" class="rounded-2xl bg-white px-6 py-4 text-sm font-semibold text-slate-900 shadow-[0_14px_32px_rgba(255,255,255,0.16)] transition hover:-translate-y-0.5">
                        Registrarse
                    </button>
                </div>
            </form>
        </div>
    </section>
</x-guest-layout>
