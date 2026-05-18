<x-public-layout :sections="$sections" :title="'GEES | '.$section['title']">
    <section class="mx-auto max-w-4xl px-6 pb-14 pt-14 lg:px-8 lg:pb-20 lg:pt-18">
        <div class="space-y-8">
            <div class="space-y-4 text-center">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-blue-200">{{ $section['eyebrow'] }}</p>
                <h1 class="text-4xl font-semibold tracking-tight text-white sm:text-5xl">{{ $section['hero'] }}</h1>
                <p class="mx-auto max-w-2xl text-lg leading-8 text-slate-300">
                    Crea el equipo con sus datos principales y el sistema generara automaticamente los codigos de acceso para entrenador, jugador y familiar.
                </p>
            </div>

            @guest
                <div class="public-overlay-panel p-6 text-center lg:p-8">
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-200">Acceso</p>
                    <h2 class="mt-3 text-2xl font-semibold text-white">Inicia sesion o registrate para crear un equipo.</h2>
                    <p class="mx-auto mt-3 max-w-2xl text-sm leading-7 text-slate-300">
                        Cuando accedas podras indicar nombre, deporte, categoria y si el equipo tendra multas. Los codigos de acceso se generaran automaticamente al guardar.
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

                    <form method="POST" action="{{ route('public.create-team.store') }}" class="space-y-6">
                        @csrf

                        <div class="space-y-3">
                            <label for="nombre_equipo" class="block text-sm font-medium text-slate-200">Nombre del equipo</label>
                            <input
                                id="nombre_equipo"
                                name="nombre_equipo"
                                value="{{ old('nombre_equipo') }}"
                                class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-4 text-white placeholder:text-slate-500 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400/30"
                                placeholder="Ejemplo: Infantil A"
                                required
                            >
                            <x-input-error :messages="$errors->get('nombre_equipo')" class="mt-2" />
                        </div>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div class="space-y-3">
                                <label for="deporte" class="block text-sm font-medium text-slate-200">Deporte</label>
                                <select
                                    id="deporte"
                                    name="deporte"
                                    class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-4 text-white focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400/30"
                                    required
                                >
                                    <option value="" class="text-slate-900">Selecciona un deporte</option>
                                    @foreach ($deportes as $deporte)
                                        <option value="{{ $deporte }}" @selected(old('deporte') === $deporte) class="text-slate-900">{{ $deporte }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('deporte')" class="mt-2" />
                            </div>

                            <div class="space-y-3">
                                <label for="categoria" class="block text-sm font-medium text-slate-200">Categoria</label>
                                <select
                                    id="categoria"
                                    name="categoria"
                                    class="w-full rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-4 text-white focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400/30"
                                    required
                                >
                                    <option value="" class="text-slate-900">Selecciona una categoria</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria }}" @selected(old('categoria') === $categoria) class="text-slate-900">{{ $categoria }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('categoria')" class="mt-2" />
                            </div>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                            <input type="hidden" name="tiene_multas" value="0">
                            <label class="flex items-start gap-3">
                                <input
                                    type="checkbox"
                                    name="tiene_multas"
                                    value="1"
                                    @checked(old('tiene_multas') == '1')
                                    class="mt-1 h-5 w-5 rounded border-white/20 bg-slate-950/60 text-blue-500 focus:ring-blue-400"
                                >
                                <span>
                                    <span class="block text-sm font-semibold text-white">Este equipo tendra multas</span>
                                    <span class="mt-1 block text-sm text-slate-300">Activa esta opcion si quieres llevar control de multas y normas desde el equipo.</span>
                                </span>
                            </label>
                            <x-input-error :messages="$errors->get('tiene_multas')" class="mt-2" />
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-slate-950/30 p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-blue-200">Codigos de acceso</p>
                            <p class="mt-3 text-sm leading-7 text-slate-300">
                                Al crear el equipo se generaran automaticamente los codigos para entrenador, jugador y familiar. Despues podras compartir cada uno segun el rol que corresponda.
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <button type="submit" class="public-cta-primary">Crear equipo</button>
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('equipos.index') }}" class="public-cta-secondary">Ver listado de equipos</a>
                            @else
                                <a href="{{ route('public.my-teams') }}" class="public-cta-secondary">Ver mis equipos</a>
                            @endif
                        </div>
                    </form>
                </div>
            @endauth
        </div>
    </section>
</x-public-layout>
