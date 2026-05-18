<nav x-data="{ open: false }" class="border-b border-white/10 bg-slate-950/65 backdrop-blur-xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex min-h-16 items-center justify-between gap-4 py-2">
            <div class="flex min-w-0 flex-1 items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl border border-blue-400/30 bg-gradient-to-br from-blue-400/20 to-cyan-300/10 text-sm font-semibold tracking-[0.24em] text-blue-100 shadow-[0_10px_30px_rgba(59,130,246,0.2)]">
                            GE
                        </span>
                        <span class="block text-sm font-semibold uppercase tracking-[0.28em] text-slate-200">GEES</span>
                    </a>
                </div>

                <div class="hidden min-w-0 flex-1 sm:-my-px sm:ms-8 sm:flex sm:flex-nowrap sm:items-center sm:justify-center sm:gap-2 lg:gap-3">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Panel</x-nav-link>
                    <x-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')">Usuarios</x-nav-link>
                    <x-nav-link :href="route('equipos.index')" :active="request()->routeIs('equipos.*')">Equipos</x-nav-link>
                    <x-nav-link :href="route('usuarios-equipos.index')" :active="request()->routeIs('usuarios-equipos.*')">Usuarios / Equipos</x-nav-link>
                    <x-nav-link :href="route('horarios.index')" :active="request()->routeIs('horarios.*')">Horarios</x-nav-link>
                    <x-nav-link :href="route('partidos.index')" :active="request()->routeIs('partidos.*')">Partidos</x-nav-link>
                    <x-nav-link :href="route('convocados.index')" :active="request()->routeIs('convocados.*')">Convocados</x-nav-link>
                    <x-nav-link :href="route('multas.index')" :active="request()->routeIs('multas.*')">Multas</x-nav-link>
                </div>
            </div>

            <div class="hidden shrink-0 sm:flex sm:items-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="public-cta-secondary px-4 py-2">
                        Cerrar sesión
                    </button>
                </form>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="space-y-1 border-t border-white/10 px-4 py-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Panel</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')">Usuarios</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('equipos.index')" :active="request()->routeIs('equipos.*')">Equipos</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('usuarios-equipos.index')" :active="request()->routeIs('usuarios-equipos.*')">Usuarios / Equipos</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('horarios.index')" :active="request()->routeIs('horarios.*')">Horarios</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('partidos.index')" :active="request()->routeIs('partidos.*')">Partidos</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('convocados.index')" :active="request()->routeIs('convocados.*')">Convocados</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('multas.index')" :active="request()->routeIs('multas.*')">Multas</x-responsive-nav-link>
            <form method="POST" action="{{ route('logout') }}" class="pt-2">
                @csrf
                <button type="submit" class="public-cta-secondary w-full">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </div>
</nav>
