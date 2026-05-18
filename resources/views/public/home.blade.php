<x-public-layout :sections="$sections" title="GEES | Zona publica">
    <section class="mx-auto max-w-7xl px-6 pb-16 pt-14 lg:px-8 lg:pb-24 lg:pt-20">
        <div class="grid gap-10 lg:grid-cols-[1.15fr_0.85fr] lg:items-center">
            <div class="space-y-8">
                <div class="inline-flex rounded-full border border-blue-400/25 bg-blue-400/10 px-4 py-1 text-sm text-blue-100">
                    Primera capa publica para entrenadores, jugadores y familiares
                </div>

                <div class="space-y-5">
                    <h1 class="max-w-4xl text-4xl font-semibold tracking-tight text-white sm:text-5xl lg:text-6xl">
                        Una entrada clara a cada equipo, incluso antes de iniciar sesion.
                    </h1>
                    <p class="max-w-2xl text-lg leading-8 text-slate-300">
                        Esta primera version ya permite recorrer la web y entender cada modulo. En cada apartado se recuerda que debes registrarte o iniciar sesion para crear equipos, unirte al tuyo o consultar tu informacion.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('public.join-team') }}" class="public-cta-primary">Ver acceso a equipos</a>
                    <a href="{{ route('public.create-team') }}" class="public-cta-secondary">Crear equipo</a>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <article class="public-stat-card">
                        <p class="text-3xl font-semibold text-white">3</p>
                        <p class="mt-2 text-sm text-slate-300">Perfiles previstos: entrenador, jugador y familiar.</p>
                    </article>
                    <article class="public-stat-card">
                        <p class="text-3xl font-semibold text-white">{{ count($sections) }}</p>
                        <p class="mt-2 text-sm text-slate-300">Pantallas base ya enlazadas para navegar por la experiencia.</p>
                    </article>
                    <article class="public-stat-card">
                        <p class="text-3xl font-semibold text-white">1</p>
                        <p class="mt-2 text-sm text-slate-300">Mensaje comun de acceso para invitar a registrarse o iniciar sesion.</p>
                    </article>
                </div>
            </div>

            <div class="public-overlay-panel p-6 lg:p-8">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-200">Acceso a la plataforma</p>
                <h2 class="mt-4 text-3xl font-semibold text-white">Registrate o inicia sesion para poder crear o ver tus equipos.</h2>
                <p class="mt-4 text-sm leading-7 text-slate-300">
                    El menu superior te lleva a cada modulo. Esta portada solo presenta la estructura inicial mientras definimos el contenido real y los permisos por rol.
                </p>

                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-3xl border border-white/10 bg-white/5 px-5 py-4">
                        <p class="text-sm font-semibold text-white">Jugadores</p>
                        <p class="mt-2 text-sm leading-6 text-slate-300">Accederan a su equipo, entrenamientos, convocatorias y apartados compartidos.</p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/5 px-5 py-4">
                        <p class="text-sm font-semibold text-white">Entrenadores</p>
                        <p class="mt-2 text-sm leading-6 text-slate-300">Gestionaran equipos, tacticas, convocatorias y planificacion deportiva.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 pb-16 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-3">
            <article class="public-info-card">
                <p class="public-card-kicker">Entrenadores</p>
                <h2 class="mt-4 text-2xl font-semibold text-white">Planifica y organiza</h2>
                <p class="mt-3 text-sm leading-7 text-slate-300">
                    Accesos previstos para crear equipos, preparar convocatorias, revisar entrenamientos y trabajar sobre la pizarra tactica.
                </p>
            </article>
            <article class="public-info-card">
                <p class="public-card-kicker">Jugadores</p>
                <h2 class="mt-4 text-2xl font-semibold text-white">Consulta y responde</h2>
                <p class="mt-3 text-sm leading-7 text-slate-300">
                    Podran unirse a su equipo, confirmar convocatorias, revisar entrenos y acceder a la informacion que les corresponda.
                </p>
            </article>
            <article class="public-info-card">
                <p class="public-card-kicker">Familiares</p>
                <h2 class="mt-4 text-2xl font-semibold text-white">Acompana el dia a dia</h2>
                <p class="mt-3 text-sm leading-7 text-slate-300">
                    Veran avisos, horarios y apartados compartidos cuando su rol tenga acceso a la informacion del equipo.
                </p>
            </article>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 pb-20 lg:px-8">
        <div class="public-overlay-panel px-6 py-8 lg:px-10">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-3xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-200">Acceso necesario</p>
                    <h2 class="mt-3 text-3xl font-semibold text-white">Registrate o inicia sesion para poder crear o ver tus equipos.</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-200">
                        De momento esta zona funciona como escaparate navegable. En la siguiente fase conectaremos cada vista con permisos reales y contenido segun el rol del usuario.
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('dashboard') }}" class="public-cta-primary">Entrar al panel</a>
                        @else
                            <a href="{{ route('public.join-team') }}" class="public-cta-primary">Ir a mi zona</a>
                        @endif
                    @else
                        <a href="{{ route('register') }}" class="public-cta-primary">Registrate</a>
                        <a href="{{ route('login') }}" class="public-cta-secondary border-white/20 bg-slate-950/30 text-white hover:border-white/30">Inicia sesion</a>
                    @endauth
                </div>
            </div>
        </div>
    </section>
</x-public-layout>
