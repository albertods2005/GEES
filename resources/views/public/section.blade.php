<x-public-layout :sections="$sections" :title="'GEES | '.$section['title']">
    <section class="mx-auto max-w-7xl px-6 pb-14 pt-14 lg:px-8 lg:pb-20 lg:pt-18">
        <div class="grid gap-8 lg:grid-cols-[0.32fr_0.68fr] lg:items-start">
            <aside class="public-feature-panel lg:sticky lg:top-28">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-slate-300">Menu</p>
                <div class="mt-6 grid gap-3">
                    @foreach ($sections as $navSection)
                        <a href="{{ route($navSection['route']) }}" class="rounded-2xl border px-4 py-4 text-sm transition {{ $navSection['route'] === $section['route'] ? 'border-blue-300/35 bg-blue-400/10 text-white' : 'border-white/10 bg-white/5 text-slate-300 hover:border-white/20 hover:text-white' }}">
                            {{ $navSection['title'] }}
                        </a>
                    @endforeach
                </div>
            </aside>

            <div class="space-y-6">
                <p class="text-sm font-semibold uppercase tracking-[0.28em] text-blue-200">{{ $section['eyebrow'] }}</p>
                <div class="space-y-4">
                    <h1 class="text-4xl font-semibold tracking-tight text-white sm:text-5xl">{{ $section['hero'] }}</h1>
                    <p class="max-w-2xl text-lg leading-8 text-slate-300">{{ $section['description'] }}</p>
                </div>

                <div class="flex flex-wrap gap-3">
                    @foreach ($section['audiences'] as $audience)
                        <span class="rounded-full border border-white/12 bg-white/6 px-4 py-2 text-sm text-slate-200">{{ $audience }}</span>
                    @endforeach
                </div>

                <div class="public-overlay-panel p-6">
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-200">Acceso</p>
                    <h2 class="mt-3 text-2xl font-semibold text-white">Registrate o inicia sesion para poder crear o ver tus equipos.</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-300">
                        Esta pagina ya forma parte de la navegacion publica, pero su contenido real se activara cuando el usuario tenga acceso y podamos distinguir bien los permisos por rol.
                    </p>

                    <div class="mt-5 flex flex-wrap gap-3">
                        @auth
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('dashboard') }}" class="public-cta-primary">Ir al panel</a>
                            @else
                                <a href="{{ route('home') }}" class="public-cta-primary">Volver a mi zona</a>
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="public-cta-primary">Registrate</a>
                            <a href="{{ route('login') }}" class="public-cta-secondary">Inicia sesion</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 pb-14 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-3">
            @foreach ($section['panels'] as $panel)
                <article class="public-info-card">
                    <p class="public-card-kicker">{{ $panel['title'] }}</p>
                    <p class="mt-4 text-sm leading-7 text-slate-300">{{ $panel['text'] }}</p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-6 pb-20 lg:px-8">
        <div class="public-overlay-panel p-6 lg:p-8">
            <div class="flex flex-col gap-8 lg:flex-row lg:items-start lg:justify-between">
                <div class="max-w-2xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-200">Lo que mostrara esta vista</p>
                    <h2 class="mt-3 text-3xl font-semibold text-white">{{ $section['title'] }}</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-300">
                        Esta version es una maqueta navegable. Su objetivo es que ya podamos movernos por la web, entender la arquitectura y decidir despues el detalle funcional de cada pantalla.
                    </p>
                </div>

                <div class="grid min-w-[18rem] gap-3">
                    @foreach ($section['highlights'] as $highlight)
                        <div class="rounded-2xl border border-white/8 bg-white/5 px-4 py-3 text-sm leading-6 text-slate-200">
                            {{ $highlight }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</x-public-layout>
