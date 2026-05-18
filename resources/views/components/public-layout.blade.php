<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'GEES') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="public-shell bg-slate-950 text-slate-100 antialiased">
        @php
            $navigationSections = collect($sections ?? [])->reject(fn ($navSection) => in_array($navSection['route'] ?? '', ['public.create-team', 'public.join-team'], true));
        @endphp
        <div class="relative isolate overflow-hidden">
            <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[44rem] bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.4),_transparent_32%),radial-gradient(circle_at_15%_12%,_rgba(125,211,252,0.16),_transparent_22%),linear-gradient(180deg,_rgba(15,23,42,1),_rgba(3,7,18,0.98))]"></div>
            <div class="pointer-events-none absolute inset-y-0 right-0 -z-10 w-1/2 bg-[radial-gradient(circle_at_center,_rgba(37,99,235,0.18),_transparent_46%)]"></div>
            <div class="pointer-events-none absolute inset-0 -z-10 opacity-30 [background-image:linear-gradient(rgba(148,163,184,0.08)_1px,transparent_1px),linear-gradient(90deg,rgba(148,163,184,0.08)_1px,transparent_1px)] [background-size:4rem_4rem] [mask-image:radial-gradient(circle_at_center,black,transparent_78%)]"></div>

            <header class="border-b border-white/10 bg-slate-950/65 backdrop-blur-xl">
                <div class="mx-auto grid max-w-7xl items-center gap-6 px-6 py-4 lg:grid-cols-[auto_minmax(0,1fr)_auto] lg:px-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl border border-blue-400/30 bg-gradient-to-br from-blue-400/20 to-cyan-300/10 text-sm font-semibold tracking-[0.24em] text-blue-100 shadow-[0_10px_30px_rgba(59,130,246,0.2)]">
                            GE
                        </span>
                        <span class="block text-sm font-semibold uppercase tracking-[0.28em] text-slate-200">GEES</span>
                    </a>

                    <nav class="hidden min-w-0 items-center justify-center gap-1 xl:gap-2 lg:flex">
                        <a href="{{ route('home') }}" class="public-nav-link {{ request()->routeIs('home') ? 'public-nav-link-active' : '' }}">Inicio</a>
                        @foreach ($navigationSections as $navSection)
                            <a href="{{ route($navSection['route']) }}" class="public-nav-link {{ request()->routeIs($navSection['route']) ? 'public-nav-link-active' : '' }}">
                                {{ $navSection['title'] }}
                            </a>
                        @endforeach
                    </nav>

                    <div class="flex items-center justify-end gap-3">
                        @auth
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('dashboard') }}" class="public-cta-secondary">Ir al panel</a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="public-cta-secondary">Cerrar sesion</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="public-cta-secondary">Inicia sesion</a>
                            <a href="{{ route('register') }}" class="public-cta-primary">Registrate</a>
                        @endauth
                    </div>
                </div>

            </header>

            <main>
                {{ $slot }}
            </main>

            <footer class="border-t border-white/10 bg-slate-950/75 backdrop-blur-xl">
                <div class="mx-auto grid max-w-7xl gap-10 px-6 py-10 lg:grid-cols-[1.15fr_0.85fr_0.8fr] lg:px-8">
                    <div class="max-w-md">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                            <span class="flex h-11 w-11 items-center justify-center rounded-2xl border border-blue-400/30 bg-gradient-to-br from-blue-400/20 to-cyan-300/10 text-sm font-semibold tracking-[0.24em] text-blue-100 shadow-[0_10px_30px_rgba(59,130,246,0.2)]">
                                GE
                            </span>
                            <span class="block text-sm font-semibold uppercase tracking-[0.28em] text-slate-200">GEES</span>
                        </a>
                        <p class="mt-5 text-sm leading-7 text-slate-300">
                            Gestion deportiva para conectar entrenadores, jugadores y familiares alrededor de cada equipo.
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-blue-200">Secciones</p>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                            <a href="{{ route('home') }}" class="public-footer-link">Inicio</a>
                            @foreach ($navigationSections as $navSection)
                                <a href="{{ route($navSection['route']) }}" class="public-footer-link">
                                    {{ $navSection['title'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-blue-200">Acceso</p>
                        <div class="mt-4 flex flex-col gap-3">
                            @auth
                                <a href="{{ route('public.my-teams') }}" class="public-footer-link">Mis equipos</a>
                                <a href="{{ route('public.create-team') }}" class="public-footer-link">Crear equipo</a>
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('dashboard') }}" class="public-footer-link">Panel de administracion</a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="public-footer-link">Inicia sesion</a>
                                <a href="{{ route('register') }}" class="public-footer-link">Registrate</a>
                                <a href="{{ route('public.join-team') }}" class="public-footer-link">Unete a tu equipo</a>
                            @endauth
                        </div>
                    </div>
                </div>

                <div class="border-t border-white/10">
                    <div class="mx-auto flex max-w-7xl flex-col gap-3 px-6 py-5 text-sm text-slate-400 sm:flex-row sm:items-center sm:justify-between lg:px-8">
                        <p>&copy; {{ now()->year }} GEES. Todos los derechos reservados.</p>
                        <p>Organizacion, tactica y comunicacion de equipo en un mismo lugar.</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
