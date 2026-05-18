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
                    <div class="public-overlay-panel p-6 lg:p-8">
                        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-200">Acceso</p>
                        <h2 class="mt-3 text-2xl font-semibold text-white">Registrate o inicia sesion para poder crear o ver tus equipos.</h2>
                    </div>
                @endguest

                @auth
                    <div
                        class="public-overlay-panel public-block-stack p-6 lg:p-8"
                        x-data="{
                            mode: @js(old('callup_ui_mode', '')),
                            activeId: @js((string) old('callup_ui_target', '')),
                            activePlayerId: @js((string) old('callup_player_id', '')),
                            openCreate() {
                                this.mode = 'create';
                                this.activeId = '';
                                this.activePlayerId = '';
                            },
                            openEdit(id) {
                                this.mode = 'edit';
                                this.activeId = String(id);
                                this.activePlayerId = '';
                            },
                            openPlayers(id) {
                                this.mode = 'players';
                                this.activeId = String(id);
                                this.activePlayerId = '';
                            },
                            openPlayerEditor(id) {
                                this.activePlayerId = String(id);
                            },
                            closePlayerEditor() {
                                this.activePlayerId = '';
                            },
                            closePanels() {
                                this.mode = '';
                                this.activeId = '';
                                this.activePlayerId = '';
                            },
                            isEditing(id) {
                                return this.mode === 'edit' && this.activeId === String(id);
                            },
                            isManagingPlayers(id) {
                                return this.mode === 'players' && this.activeId === String(id);
                            },
                            isEditingPlayer(id) {
                                return this.activePlayerId === String(id);
                            }
                        }"
                    >
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
                            </div>
                        @elseif (! $equipoSeleccionado)
                            <div class="public-empty-card">
                                <p class="text-lg font-semibold text-white">Selecciona uno de tus equipos</p>
                            </div>
                            <div class="mt-5 flex flex-wrap gap-3">
                                <a href="{{ route('public.my-teams') }}" class="public-cta-primary">Ir a mis equipos</a>
                            </div>
                        @else
                            <div class="public-panel-header">
                                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-200">Equipo seleccionado</p>
                                <h2 class="mt-3 text-3xl font-semibold text-white">{{ $equipoSeleccionado->nombre_equipo }}</h2>
                                <div class="public-pill-list">
                                    <span class="public-role-badge">{{ ucfirst($equipoSeleccionado->pivot->rol) }}</span>
                                    <span class="public-pill">{{ $partidos->count() }} {{ $partidos->count() === 1 ? 'convocatoria activa' : 'convocatorias cargadas' }}</span>
                                </div>
                                <p class="text-sm leading-7 text-slate-300">
                                    Mantiene una sola convocatoria clara por equipo. Si necesitas cambiarla, edita la actual o borra la existente antes de crear otra nueva.
                                </p>
                            </div>

                            @if ($puedeGestionarEquipo)
                                <div class="public-toolbar">
                                    <div>
                                        <p class="text-lg font-semibold text-white">Panel de entrenador</p>
                                        <p class="public-toolbar-copy">
                                            La convocatoria no enseña todos los formularios de golpe. Abre solo la accion que necesites para crear, editar la actual o gestionar convocados.
                                        </p>
                                    </div>
                                    <div class="flex flex-wrap gap-3">
                                        @if ($partidos->isEmpty())
                                            <button type="button" class="public-action-add" @click="openCreate()">Crear convocatoria</button>
                                        @endif
                                        <button type="button" class="public-panel-cancel" @click="closePanels()" x-show="mode" x-cloak>Cerrar panel</button>
                                    </div>
                                </div>

                                @if ($partidos->isEmpty())
                                    <div x-show="mode === 'create'" x-transition.opacity.duration.200ms x-cloak class="public-modal-panel">
                                        <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                            <div>
                                                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-400">Nueva convocatoria</p>
                                                <h3 class="mt-2 text-2xl font-semibold text-white">Crear partido</h3>
                                            </div>
                                            <button type="button" class="public-panel-cancel" @click="closePanels()">Cancelar</button>
                                        </div>

                                        <form method="POST" action="{{ route('public.callups.matches.store') }}" class="mt-6 space-y-5">
                                            @csrf
                                            <input type="hidden" name="callup_ui_mode" value="create" />
                                            <div class="public-form-grid">
                                                <label>
                                                    <span class="public-label">Rival</span>
                                                    <input name="equipo_rival" value="{{ old('equipo_rival') }}" class="public-input" placeholder="Equipo rival" />
                                                    <x-input-error :messages="$errors->get('equipo_rival')" class="mt-2" />
                                                </label>
                                                <label>
                                                    <span class="public-label">Lugar</span>
                                                    <input name="lugar" value="{{ old('lugar') }}" class="public-input" placeholder="Campo o pabellon" />
                                                    <x-input-error :messages="$errors->get('lugar')" class="mt-2" />
                                                </label>
                                                <label>
                                                    <span class="public-label">Fecha</span>
                                                    <input type="date" name="fecha" value="{{ old('fecha') }}" class="public-input" />
                                                    <x-input-error :messages="$errors->get('fecha')" class="mt-2" />
                                                </label>
                                                <label>
                                                    <span class="public-label">Hora de quedada</span>
                                                    <input type="time" name="hora_quedada" value="{{ old('hora_quedada') }}" class="public-input" />
                                                    <x-input-error :messages="$errors->get('hora_quedada')" class="mt-2" />
                                                </label>
                                                <label>
                                                    <span class="public-label">Hora de partido</span>
                                                    <input type="time" name="hora_partido" value="{{ old('hora_partido') }}" class="public-input" />
                                                    <x-input-error :messages="$errors->get('hora_partido')" class="mt-2" />
                                                </label>
                                            </div>
                                            <div class="public-inline-actions">
                                                <button class="public-panel-submit">Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            @endif

                            <div class="public-data-grid">
                                @forelse ($partidos as $partido)
                                    @php
                                        $fechaMostrada = $partido->fecha;
                                        $horaQuedadaInput = $partido->hora_quedada ? substr((string) $partido->hora_quedada, 0, 5) : '';
                                        $horaPartidoInput = $partido->hora_partido ? substr((string) $partido->hora_partido, 0, 5) : '';
                                        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $partido->fecha)) {
                                            $fechaMostrada = \Illuminate\Support\Carbon::parse($partido->fecha)->format('d/m/Y');
                                        }
                                    @endphp
                                    <article class="public-training-card" x-data="{ confirmDelete: false }">
                                        <div class="relative public-card-stack">
                                            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                                <div>
                                                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-200">Convocatoria</p>
                                                    <h3 class="mt-2 public-training-day">{{ $partido->equipo_rival ?: 'Rival pendiente' }}</h3>
                                                </div>

                                                @if ($puedeGestionarEquipo)
                                                    <div class="public-training-actions mt-0">
                                                        <button type="button" class="public-action-edit" @click="openEdit('{{ $partido->id_partido }}')">Editar</button>
                                                        <button type="button" class="public-action-edit" @click="openPlayers('{{ $partido->id_partido }}')">Convocados</button>
                                                        <button type="button" class="public-action-delete" @click="confirmDelete = !confirmDelete">Borrar</button>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="public-meta-grid public-card-divider">
                                                <p class="public-meta-item">Fecha: <span class="font-semibold text-white">{{ $fechaMostrada ?: 'Pendiente' }}</span></p>
                                                <p class="public-meta-item">Hora de quedada: <span class="font-semibold text-white">{{ $partido->hora_quedada ?: 'Pendiente' }}</span></p>
                                                <p class="public-meta-item">Hora de partido: <span class="font-semibold text-white">{{ $partido->hora_partido ?: 'Pendiente' }}</span></p>
                                                <p class="public-meta-item">Lugar: <span class="font-semibold text-white">{{ $partido->lugar ?: 'Pendiente' }}</span></p>
                                                <p class="public-meta-item sm:col-span-2 xl:col-span-2">Convocados: <span class="font-semibold text-white">{{ $partido->convocados->count() }}</span></p>
                                            </div>

                                            <div class="public-card-divider">
                                                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-200">Lista actual</p>
                                                <div class="public-pill-list">
                                                    @forelse ($partido->convocados as $convocado)
                                                        <span class="public-pill">
                                                            {{ $convocado->nombre_jugador ?: 'Jugador sin nombre' }}@if ($convocado->dorsal) (#{{ $convocado->dorsal }}) @endif
                                                        </span>
                                                    @empty
                                                        <span class="text-sm text-slate-300">No hay convocados cargados.</span>
                                                    @endforelse
                                                </div>
                                            </div>

                                            @if ($puedeGestionarEquipo)
                                                <div x-show="isEditing('{{ $partido->id_partido }}')" x-transition.opacity.duration.200ms x-cloak class="public-modal-panel mt-6">
                                                    <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                                        <div>
                                                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-400">Editar convocatoria</p>
                                                            <h4 class="mt-2 text-xl font-semibold text-white">{{ $partido->equipo_rival ?: 'Partido sin rival' }}</h4>
                                                        </div>
                                                        <button type="button" class="public-panel-cancel" @click="closePanels()">Cerrar</button>
                                                    </div>

                                                    <form id="callup-edit-form-{{ $partido->id_partido }}" method="POST" action="{{ route('public.callups.matches.update', $partido) }}" class="mt-6 space-y-5" novalidate>
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="id_partido" value="{{ $partido->id_partido }}" />
                                                        <input type="hidden" name="callup_ui_mode" value="edit" />
                                                        <input type="hidden" name="callup_ui_target" value="{{ $partido->id_partido }}" />
                                                        <div class="public-form-grid">
                                                            <label>
                                                                <span class="public-label">Rival</span>
                                                                <input name="equipo_rival" value="{{ old('callup_ui_target') == $partido->id_partido ? old('equipo_rival', $partido->equipo_rival) : $partido->equipo_rival }}" class="public-input" />
                                                                @if (old('callup_ui_target') == $partido->id_partido)
                                                                    <x-input-error :messages="$errors->get('equipo_rival')" class="mt-2" />
                                                                @endif
                                                            </label>
                                                            <label>
                                                                <span class="public-label">Lugar</span>
                                                                <input name="lugar" value="{{ old('callup_ui_target') == $partido->id_partido ? old('lugar', $partido->lugar) : $partido->lugar }}" class="public-input" />
                                                                @if (old('callup_ui_target') == $partido->id_partido)
                                                                    <x-input-error :messages="$errors->get('lugar')" class="mt-2" />
                                                                @endif
                                                            </label>
                                                            <label>
                                                                <span class="public-label">Fecha</span>
                                                                <input type="date" name="fecha" value="{{ old('callup_ui_target') == $partido->id_partido ? old('fecha', $partido->fecha) : $partido->fecha }}" class="public-input" />
                                                                @if (old('callup_ui_target') == $partido->id_partido)
                                                                    <x-input-error :messages="$errors->get('fecha')" class="mt-2" />
                                                                @endif
                                                            </label>
                                                            <label>
                                                                <span class="public-label">Hora de quedada</span>
                                                                <input type="time" name="hora_quedada" value="{{ old('callup_ui_target') == $partido->id_partido ? old('hora_quedada', $horaQuedadaInput) : $horaQuedadaInput }}" class="public-input" />
                                                                @if (old('callup_ui_target') == $partido->id_partido)
                                                                    <x-input-error :messages="$errors->get('hora_quedada')" class="mt-2" />
                                                                @endif
                                                            </label>
                                                            <label>
                                                                <span class="public-label">Hora de partido</span>
                                                                <input type="time" name="hora_partido" value="{{ old('callup_ui_target') == $partido->id_partido ? old('hora_partido', $horaPartidoInput) : $horaPartidoInput }}" class="public-input" />
                                                                @if (old('callup_ui_target') == $partido->id_partido)
                                                                    <x-input-error :messages="$errors->get('hora_partido')" class="mt-2" />
                                                                @endif
                                                            </label>
                                                        </div>
                                                        <div class="public-inline-actions">
                                                            <button type="submit" form="callup-edit-form-{{ $partido->id_partido }}" class="public-panel-submit">Guardar</button>
                                                        </div>
                                                    </form>
                                                </div>

                                                <div x-show="isManagingPlayers('{{ $partido->id_partido }}')" x-transition.opacity.duration.200ms x-cloak class="public-modal-panel mt-6">
                                                    <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                                        <div>
                                                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-400">Gestion de convocados</p>
                                                            <h4 class="mt-2 text-xl font-semibold text-white">{{ $partido->equipo_rival ?: 'Convocatoria sin rival' }}</h4>
                                                        </div>
                                                        <button type="button" class="public-panel-cancel" @click="closePanels()">Cerrar</button>
                                                    </div>

                                                    <div class="mt-6 grid gap-3">
                                                        @forelse ($partido->convocados as $convocado)
                                                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                                                <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                                                    <div>
                                                                        <p class="text-lg font-semibold text-white">{{ $convocado->nombre_jugador ?: 'Jugador sin nombre' }}</p>
                                                                        <p class="mt-2 text-sm text-slate-300">Dorsal: <span class="font-semibold text-white">{{ $convocado->dorsal ?: 'Pendiente' }}</span></p>
                                                                    </div>
                                                                    <div class="flex flex-wrap gap-3">
                                                                        <button type="button" class="public-action-edit" @click="openPlayerEditor('{{ $convocado->id }}')">Editar</button>
                                                                        <form method="POST" action="{{ route('public.callups.players.destroy', $convocado) }}">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button class="public-action-delete" onclick="return confirm('¿Eliminar convocado?')">Borrar</button>
                                                                        </form>
                                                                    </div>
                                                                </div>

                                                                <div x-show="isEditingPlayer('{{ $convocado->id }}')" x-transition.opacity.duration.150ms x-cloak class="public-modal-panel mt-4">
                                                                    <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                                                        <div>
                                                                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-400">Editar jugador</p>
                                                                            <h5 class="mt-2 text-lg font-semibold text-white">{{ $convocado->nombre_jugador ?: 'Convocado' }}</h5>
                                                                        </div>
                                                                        <button type="button" class="public-panel-cancel" @click="closePlayerEditor()">Cerrar</button>
                                                                    </div>

                                                                    <form method="POST" action="{{ route('public.callups.players.update', $convocado) }}" class="mt-6 space-y-5">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <input type="hidden" name="callup_ui_mode" value="players" />
                                                                        <input type="hidden" name="callup_ui_target" value="{{ $partido->id_partido }}" />
                                                                        <input type="hidden" name="callup_player_id" value="{{ $convocado->id }}" />
                                                                        <div class="public-form-grid">
                                                                            <label>
                                                                                <span class="public-label">Jugador</span>
                                                                                <input name="nombre_jugador" value="{{ old('callup_player_id') == $convocado->id ? old('nombre_jugador', $convocado->nombre_jugador) : $convocado->nombre_jugador }}" class="public-input" />
                                                                                @if (old('callup_player_id') == $convocado->id)
                                                                                    <x-input-error :messages="$errors->get('nombre_jugador')" class="mt-2" />
                                                                                @endif
                                                                            </label>
                                                                            <label>
                                                                                <span class="public-label">Dorsal</span>
                                                                                <input type="number" name="dorsal" min="1" value="{{ old('callup_player_id') == $convocado->id ? old('dorsal', $convocado->dorsal) : $convocado->dorsal }}" class="public-input" />
                                                                                @if (old('callup_player_id') == $convocado->id)
                                                                                    <x-input-error :messages="$errors->get('dorsal')" class="mt-2" />
                                                                                @endif
                                                                            </label>
                                                                        </div>
                                                                        <div class="public-inline-actions">
                                                                            <button class="public-panel-submit">Guardar</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <div class="public-empty-card text-sm">
                                                                Todavia no has añadido jugadores a esta convocatoria.
                                                            </div>
                                                        @endforelse
                                                    </div>

                                                    <div class="public-modal-panel mt-4">
                                                        <div>
                                                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-400">Nuevo convocado</p>
                                                            <h5 class="mt-2 text-lg font-semibold text-white">Añadir jugador a la lista</h5>
                                                        </div>

                                                        <form method="POST" action="{{ route('public.callups.players.store', $partido) }}" class="mt-6 space-y-5">
                                                            @csrf
                                                            <input type="hidden" name="callup_ui_mode" value="players" />
                                                            <input type="hidden" name="callup_ui_target" value="{{ $partido->id_partido }}" />
                                                            <div class="public-form-grid">
                                                                <label>
                                                                    <span class="public-label">Jugador</span>
                                                                    <input name="nombre_jugador" value="{{ old('callup_ui_mode') === 'players' && old('callup_ui_target') == $partido->id_partido && ! old('callup_player_id') ? old('nombre_jugador') : '' }}" class="public-input" placeholder="Nombre del jugador" />
                                                                    @if (old('callup_ui_mode') === 'players' && old('callup_ui_target') == $partido->id_partido && ! old('callup_player_id'))
                                                                        <x-input-error :messages="$errors->get('nombre_jugador')" class="mt-2" />
                                                                    @endif
                                                                </label>
                                                                <label>
                                                                    <span class="public-label">Dorsal</span>
                                                                    <input type="number" name="dorsal" min="1" value="{{ old('callup_ui_mode') === 'players' && old('callup_ui_target') == $partido->id_partido && ! old('callup_player_id') ? old('dorsal') : '' }}" class="public-input" placeholder="8" />
                                                                    @if (old('callup_ui_mode') === 'players' && old('callup_ui_target') == $partido->id_partido && ! old('callup_player_id'))
                                                                        <x-input-error :messages="$errors->get('dorsal')" class="mt-2" />
                                                                    @endif
                                                                </label>
                                                            </div>
                                                            <div class="public-inline-actions">
                                                                <button class="public-panel-submit">Añadir</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                                <div x-show="confirmDelete" x-transition.opacity.duration.150ms x-cloak class="public-confirm-panel">
                                                    <p class="font-semibold text-white">Vas a borrar esta convocatoria.</p>
                                                    <p class="mt-2">Si la eliminas, desapareceran el partido y todos los convocados asociados.</p>
                                                    <div class="public-inline-actions">
                                                        <form method="POST" action="{{ route('public.callups.matches.destroy', $partido) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="public-action-delete" onclick="return confirm('¿Eliminar partido y su convocatoria?')">Confirmar borrado</button>
                                                        </form>
                                                        <button type="button" class="public-panel-cancel" @click="confirmDelete = false">Cancelar</button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </article>
                                @empty
                                    <div class="public-empty-card text-sm">
                                        No hay convocatorias cargadas para este equipo todavia.
                                    </div>
                                @endforelse
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
