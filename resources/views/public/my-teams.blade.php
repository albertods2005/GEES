<x-public-layout :sections="$sections" :title="'GEES | '.$section['title']">
    <section class="public-page-shell">
        <div class="public-page-grid">
            <div class="public-content-stack">
                <p class="public-eyebrow">{{ $section['eyebrow'] }}</p>
                <div class="space-y-4">
                    <h1 class="public-hero-title">{{ $section['hero'] }}</h1>
                    <p class="public-hero-copy">{{ $section['description'] }}</p>
                </div>

                @guest
                    <div class="public-overlay-panel public-block-stack p-6 lg:p-8">
                        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-200">Acceso</p>
                        <h2 class="mt-3 text-2xl font-semibold text-white">Registrate o inicia sesion para poder crear o ver tus equipos.</h2>
                        <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-300">
                            Cuando entres con tu cuenta, aqui cargaremos todos los equipos en los que estas, mostrando el nombre y el rol que tienes dentro de cada uno.
                        </p>

                        <div class="mt-5 flex flex-wrap gap-3">
                            <a href="{{ route('register') }}" class="public-cta-primary">Registrate</a>
                            <a href="{{ route('login') }}" class="public-cta-secondary">Inicia sesion</a>
                        </div>
                    </div>
                @endguest

                @auth
                    <div class="public-overlay-panel p-6 lg:p-8">
                        @include('gees.partials.mensajes')

                        @if ($equipos->isEmpty())
                            <div class="public-empty-card">
                                <p class="text-lg font-semibold text-white">Aun no estas en ningun equipo</p>
                                <p class="mt-3 text-sm leading-7 text-slate-300">
                                    Puedes crear uno nuevo o unirte con un codigo. En cuanto tengas equipos asignados apareceran aqui como accesos directos.
                                </p>
                                <div class="mt-5 flex flex-wrap gap-3">
                                    <a href="{{ route('public.create-team') }}" class="public-cta-primary">Crear equipo</a>
                                    <a href="{{ route('public.join-team') }}" class="public-cta-secondary">Unirme con codigo</a>
                                </div>
                            </div>
                        @else
                            <div class="public-panel-header">
                                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-200">Tus accesos activos</p>
                                <h2 class="text-2xl font-semibold text-white">Selecciona el equipo al que quieres entrar</h2>
                                <p class="text-sm leading-7 text-slate-300">
                                    Al entrar en un equipo dejaremos ese contexto activo para Horarios, Convocatorias y Multas.
                                </p>
                                <div class="flex flex-wrap gap-3">
                                    <a href="{{ route('public.create-team') }}" class="public-cta-primary">Crear equipo</a>
                                    <a href="{{ route('public.join-team') }}" class="public-cta-secondary">Unirme con codigo</a>
                                </div>
                            </div>

                            <div class="public-data-grid">
                                @foreach ($equipos as $equipo)
                                    <a href="{{ route('public.my-teams.select', $equipo) }}" class="group public-team-card {{ $equipoActivoId === $equipo->id_equipo ? 'public-team-card-active' : '' }}">
                                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <p class="text-xl font-semibold text-white">{{ $equipo->nombre_equipo }}</p>
                                                <div class="public-pill-list">
                                                    <span class="public-role-badge">{{ ucfirst($equipo->pivot->rol) }}</span>
                                                    @if ($equipoActivoId === $equipo->id_equipo)
                                                        <span class="public-pill">Equipo activo</span>
                                                    @endif
                                                </div>
                                                <p class="mt-4 text-sm leading-7 text-slate-300">
                                                    Nombre del equipo: <span class="font-semibold text-white">{{ $equipo->nombre_equipo }}</span>
                                                </p>
                                            </div>
                                            <span class="public-team-action">
                                                Entrar en {{ $equipo->nombre_equipo }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endauth
            </div>
        </div>
    </section>
</x-public-layout>
