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
                        <h2 class="mt-3 text-2xl font-semibold text-white">Registrate o inicia sesion para preparar la pizarra de tus equipos.</h2>
                        <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-300">
                            La pizarra se activa cuando tienes un equipo seleccionado y rol dentro de el.
                        </p>
                        <div class="mt-5 flex flex-wrap gap-3">
                            <a href="{{ route('register') }}" class="public-cta-primary">Registrate</a>
                            <a href="{{ route('login') }}" class="public-cta-secondary">Inicia sesion</a>
                        </div>
                    </div>
                @endguest

                @auth
                    <div class="public-overlay-panel p-6 lg:p-8">
                        @if (session('exito'))
                            <div class="public-alert-success">
                                {{ session('exito') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="public-alert-error">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        @if ($equiposUsuario->isEmpty())
                            <div class="public-empty-card">
                                <p class="text-lg font-semibold text-white">Todavia no tienes equipos asignados</p>
                                <p class="mt-3 text-sm leading-7 text-slate-300">
                                    Para usar la pizarra primero necesitas crear un equipo o unirte a uno.
                                </p>
                            </div>
                        @elseif (! $equipoSeleccionado)
                            <div class="public-empty-card">
                                <p class="text-lg font-semibold text-white">Selecciona uno de tus equipos</p>
                                <p class="mt-3 text-sm leading-7 text-slate-300">
                                    Elige el equipo activo desde Mis equipos para cargar aqui la pista y sus fichas.
                                </p>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('public.my-teams') }}" class="public-cta-primary">Ir a mis equipos</a>
                            </div>
                        @else
                            @php
                                $deporte = $equipoSeleccionado->deporte ?? 'Futbol';
                            @endphp

                            <div class="public-panel-header">
                                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-200">Equipo seleccionado</p>
                                <h2 class="mt-3 text-3xl font-semibold text-white">{{ $equipoSeleccionado->nombre_equipo }}</h2>
                                <div class="public-pill-list">
                                    <span class="public-role-badge">{{ ucfirst($equipoSeleccionado->pivot->rol) }}</span>
                                    <span class="public-pill">{{ $deporte }}</span>
                                    <span class="public-pill">Pizarra interactiva</span>
                                </div>
                                <p class="text-sm leading-7 text-slate-300">
                                    La pista cambia segun el deporte del equipo. Arrastra las fichas para ordenar la idea tactica y dejala guardada en este navegador.
                                </p>
                            </div>

                            <div
                                x-data="createTacticalBoard({
                                    storageKey: @js('gees-tactical-board-'.$equipoSeleccionado->id_equipo),
                                    sport: @js($deporte),
                                    canEdit: @js($puedeGestionarEquipo),
                                    teamName: @js($equipoSeleccionado->nombre_equipo),
                                })"
                                x-init="init()"
                                class="space-y-6"
                            >
                                <div class="public-toolbar">
                                    <div>
                                        <p class="text-lg font-semibold text-white">Panel tactico</p>
                                        <p class="public-toolbar-copy" x-show="canEdit">
                                            Añade fichas de tu equipo, del rival o el balon. Luego arrastralas libremente sobre la pista.
                                        </p>
                                        <p class="public-toolbar-copy" x-show="!canEdit">
                                            Esta pizarra esta en modo consulta. Solo el entrenador puede mover y añadir fichas.
                                        </p>
                                    </div>

                                    <div class="public-board-actions-grid" x-show="canEdit">
                                        <button type="button" class="public-action-add" @click="addPiece('team')">Añadir mia</button>
                                        <button type="button" class="public-action-edit" @click="addPiece('opponent')">Añadir rival</button>
                                        <button type="button" class="public-panel-submit" @click="addPiece('ball')">Añadir balon</button>
                                        <button type="button" class="public-panel-cancel" @click="resetBoard()">Reiniciar</button>
                                    </div>
                                </div>

                                <div class="public-board-layout">
                                    <div class="public-board-panel" x-ref="boardPanel" :class="{ 'public-board-panel-fullscreen': isFullscreen }">
                                        <div class="flex flex-wrap items-center justify-between gap-3">
                                            <div>
                                                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-200">Superficie</p>
                                                <h3 class="mt-2 text-2xl font-semibold text-white" x-text="boardTitle"></h3>
                                            </div>
                                            <div class="flex flex-wrap items-center gap-3">
                                                <div class="public-pill-list">
                                                    <span class="public-pill" x-text="pieces.length + ' fichas'"></span>
                                                    <span class="public-pill" x-text="teamName"></span>
                                                </div>
                                                <button
                                                    type="button"
                                                    class="public-board-expand-button"
                                                    @click="toggleFullscreen()"
                                                    x-show="fullscreenSupported"
                                                    :aria-label="isFullscreen ? 'Salir de pantalla completa' : 'Pantalla completa'"
                                                    :title="isFullscreen ? 'Salir de pantalla completa' : 'Pantalla completa'"
                                                >
                                                    <svg x-show="!isFullscreen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                        <path d="M8 3H3v5"></path>
                                                        <path d="M16 3h5v5"></path>
                                                        <path d="M21 16v5h-5"></path>
                                                        <path d="M3 16v5h5"></path>
                                                    </svg>
                                                    <svg x-show="isFullscreen" x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                        <path d="M9 3H3v6"></path>
                                                        <path d="M15 3h6v6"></path>
                                                        <path d="M21 15v6h-6"></path>
                                                        <path d="M3 15v6h6"></path>
                                                        <path d="M8 8L3 3"></path>
                                                        <path d="M16 8l5-5"></path>
                                                        <path d="M8 16l-5 5"></path>
                                                        <path d="M16 16l5 5"></path>
                                                    </svg>
                                                    <span class="sr-only" x-text="isFullscreen ? 'Salir de pantalla completa' : 'Pantalla completa'"></span>
                                                </button>
                                            </div>
                                        </div>

                                        <div
                                            class="public-board-surface mt-6"
                                            :class="[fieldClass, isFullscreen ? 'public-board-surface-fullscreen' : '']"
                                            x-ref="surface"
                                            @pointermove.window="onPointerMove($event)"
                                            @pointerup.window="endDrag()"
                                            @pointercancel.window="endDrag()"
                                        >
                                            <template x-if="sportKey === 'football'">
                                                <svg viewBox="0 0 100 60" class="public-board-lines" aria-hidden="true">
                                                    <rect x="1" y="1" width="98" height="58" rx="1.5"></rect>
                                                    <line x1="50" y1="1" x2="50" y2="59"></line>
                                                    <circle cx="50" cy="30" r="9"></circle>
                                                    <circle cx="50" cy="30" r="0.8" fill="currentColor"></circle>
                                                    <rect x="1" y="18" width="16" height="24"></rect>
                                                    <rect x="1" y="24" width="5" height="12"></rect>
                                                    <rect x="83" y="18" width="16" height="24"></rect>
                                                    <rect x="94" y="24" width="5" height="12"></rect>
                                                </svg>
                                            </template>

                                            <template x-if="sportKey === 'futsal'">
                                                <svg viewBox="0 0 100 60" class="public-board-lines" aria-hidden="true">
                                                    <rect x="1" y="1" width="98" height="58" rx="1.5"></rect>
                                                    <line x1="50" y1="1" x2="50" y2="59"></line>
                                                    <circle cx="50" cy="30" r="8"></circle>
                                                    <circle cx="50" cy="30" r="0.8" fill="currentColor"></circle>
                                                    <path d="M1 18 Q18 30 1 42"></path>
                                                    <path d="M99 18 Q82 30 99 42"></path>
                                                    <rect x="1" y="25" width="3" height="10"></rect>
                                                    <rect x="96" y="25" width="3" height="10"></rect>
                                                </svg>
                                            </template>

                                            <template x-if="sportKey === 'basketball'">
                                                <svg viewBox="0 0 100 60" class="public-board-lines" aria-hidden="true">
                                                    <rect x="1" y="1" width="98" height="58" rx="1.5"></rect>
                                                    <line x1="50" y1="1" x2="50" y2="59"></line>
                                                    <circle cx="50" cy="30" r="8"></circle>
                                                    <circle cx="50" cy="30" r="0.8" fill="currentColor"></circle>
                                                    <rect x="1" y="19" width="19" height="22"></rect>
                                                    <rect x="80" y="19" width="19" height="22"></rect>
                                                    <path d="M20 11 Q37 30 20 49"></path>
                                                    <path d="M80 11 Q63 30 80 49"></path>
                                                    <line x1="5" y1="24" x2="5" y2="36"></line>
                                                    <line x1="95" y1="24" x2="95" y2="36"></line>
                                                </svg>
                                            </template>

                                            <template x-for="piece in pieces" :key="piece.id">
                                                <button
                                                    type="button"
                                                    class="public-board-piece"
                                                    :class="piece.type === 'team' ? 'public-board-piece-team' : (piece.type === 'opponent' ? 'public-board-piece-opponent' : 'public-board-piece-ball')"
                                                    :style="`left:${piece.x}%; top:${piece.y}%`"
                                                    @pointerdown.prevent="startDrag(piece.id, $event)"
                                                    @dblclick.prevent="removePiece(piece.id)"
                                                    :title="canEdit ? 'Arrastra para mover. Doble clic para borrar.' : piece.label"
                                                >
                                                    <span x-text="piece.label"></span>
                                                </button>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="public-board-sidebar">
                                        <div class="public-board-panel">
                                            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-200">Instrucciones</p>
                                            <div class="mt-4 grid gap-3 text-sm leading-7 text-slate-300">
                                                <p>La pista cambia automaticamente segun el deporte del equipo.</p>
                                                <p x-show="canEdit">Puedes arrastrar fichas, borrar con doble clic y reiniciar toda la disposicion cuando quieras.</p>
                                                <p x-show="!canEdit">En este rol puedes consultar la pizarra, pero no modificarla.</p>
                                                <p>La disposicion se guarda en este navegador para el equipo actual.</p>
                                            </div>
                                        </div>

                                        <div class="public-board-panel">
                                            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-200">Leyenda</p>
                                            <div class="mt-4 grid gap-3">
                                                <div class="flex items-center gap-3 text-sm text-slate-200">
                                                    <span class="public-board-legend public-board-piece-team"></span>
                                                    Tu equipo
                                                </div>
                                                <div class="flex items-center gap-3 text-sm text-slate-200">
                                                    <span class="public-board-legend public-board-piece-opponent"></span>
                                                    Rival
                                                </div>
                                                <div class="flex items-center gap-3 text-sm text-slate-200">
                                                    <span class="public-board-legend public-board-piece-ball"></span>
                                                    Balon
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('public.my-teams') }}" class="public-cta-secondary">Cambiar de equipo</a>
                            </div>
                        @endif
                    </div>
                @endauth
            </div>
        </div>
    </section>
</x-public-layout>
