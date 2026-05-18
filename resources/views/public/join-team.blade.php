<x-public-layout :sections="$sections" :title="'GEES | '.$section['title']">
    <section class="mx-auto max-w-4xl px-6 pb-14 pt-14 lg:px-8 lg:pb-20 lg:pt-18">
        <div class="space-y-8">
            <div class="space-y-4 text-center">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-blue-200">{{ $section['eyebrow'] }}</p>
                <h1 class="text-4xl font-semibold tracking-tight text-white sm:text-5xl">{{ $section['hero'] }}</h1>
                <p class="mx-auto max-w-2xl text-lg leading-8 text-slate-300">
                    Introduce el codigo del equipo y, si es valido, entraras automaticamente con el rol asociado a ese codigo.
                </p>
            </div>

            @guest
                <div class="public-overlay-panel p-6 text-center lg:p-8">
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-200">Acceso</p>
                    <h2 class="mt-3 text-2xl font-semibold text-white">Inicia sesion o registrate para unirte a un equipo.</h2>
                    <p class="mx-auto mt-3 max-w-2xl text-sm leading-7 text-slate-300">
                        Cuando accedas, solo tendras que pegar el codigo. El sistema detectara si corresponde a entrenador, jugador o familiar.
                    </p>

                    <div class="mt-5 flex flex-wrap justify-center gap-3">
                        <a href="{{ route('register') }}" class="public-cta-primary">Registrate</a>
                        <a href="{{ route('login') }}" class="public-cta-secondary">Inicia sesion</a>
                    </div>
                </div>
            @endguest

            @auth
                <div class="public-overlay-panel p-6 lg:p-8">
                    @include('gees.partials.mensajes')

                    <form method="POST" action="{{ route('public.join-team.store') }}" class="space-y-6">
                        @csrf

                        <div class="space-y-3">
                            <label for="codigo_equipo" class="block text-sm font-medium text-slate-200">Codigo de equipo</label>
                            <input
                                id="codigo_equipo"
                                name="codigo_equipo"
                                value="{{ old('codigo_equipo') }}"
                                class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-4 text-white placeholder:text-slate-500 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400/30"
                                placeholder="Ejemplo: JUG-INFANTILA-AB12CD"
                                required
                            >
                            <x-input-error :messages="$errors->get('codigo_equipo')" class="mt-2" />
                            <p class="text-sm leading-6 text-slate-400">
                                No hace falta elegir rol. El codigo define automaticamente si entraras como entrenador, jugador o familiar.
                            </p>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-slate-950/30 p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-blue-200">Como funciona</p>
                            <p class="mt-3 text-sm leading-7 text-slate-300">
                                Si el codigo es correcto, te añadiremos directamente al equipo correspondiente con los permisos que tenga asignados ese codigo.
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <button type="submit" class="public-cta-primary">Unirme al equipo</button>
                            <a href="{{ route('public.my-teams') }}" class="public-cta-secondary">Ver mis equipos</a>
                        </div>
                    </form>
                </div>
            @endauth
        </div>
    </section>
</x-public-layout>
