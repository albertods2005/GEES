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
                        <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-300">
                            Esta pantalla mostrara los horarios del equipo seleccionado cuando el usuario tenga acceso a uno o varios equipos.
                        </p>
                        <div class="mt-5 flex flex-wrap gap-3">
                            <a href="{{ route('register') }}" class="public-cta-primary">Registrate</a>
                            <a href="{{ route('login') }}" class="public-cta-secondary">Inicia sesion</a>
                        </div>
                    </div>
                @endguest

                @auth
                    <div
                        class="public-overlay-panel public-block-stack p-6 lg:p-8"
                        x-data="{
                            mode: @js(old('training_ui_mode', '')),
                            activeId: @js((string) old('training_ui_id', '')),
                            openCreate() {
                                this.mode = 'create';
                                this.activeId = '';
                            },
                            openEdit(id) {
                                this.mode = 'edit';
                                this.activeId = String(id);
                            },
                            closePanels() {
                                this.mode = '';
                                this.activeId = '';
                            },
                            isEditing(id) {
                                return this.mode === 'edit' && this.activeId === String(id);
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
                                <p class="mt-3 text-sm leading-7 text-slate-300">
                                    Para ver horarios primero necesitas crear un equipo o unirte a uno con codigo.
                                </p>
                            </div>
                        @elseif (! $equipoSeleccionado)
                            <div class="public-empty-card">
                                <p class="text-lg font-semibold text-white">Selecciona uno de tus equipos</p>
                                <p class="mt-3 text-sm leading-7 text-slate-300">
                                    Elige un equipo desde Mis equipos para dejarlo activo y mostrar aqui sus horarios.
                                </p>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('public.my-teams') }}" class="public-cta-primary">Ir a mis equipos</a>
                            </div>
                        @else
                            <div class="public-panel-header">
                                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-200">Equipo seleccionado</p>
                                <h2 class="mt-3 text-3xl font-semibold text-white">{{ $equipoSeleccionado->nombre_equipo }}</h2>
                                <div class="public-pill-list">
                                    <span class="public-role-badge">{{ ucfirst($equipoSeleccionado->pivot->rol) }}</span>
                                    <span class="public-pill">{{ $horarios->count() }} entrenes activos</span>
                                </div>
                                <p class="text-sm leading-7 text-slate-300">
                                    Organiza cada dia de entrenamiento por separado y abre la accion que necesites solo cuando toque: crear, editar o borrar.
                                </p>
                            </div>

                            @if ($puedeGestionarEquipo)
                                <div class="public-toolbar">
                                    <div>
                                        <p class="text-lg font-semibold text-white">Panel de entrenador</p>
                                        <p class="public-toolbar-copy">
                                            Usa el boton de alta para sumar un nuevo entreno. Cada tarjeta tiene sus propias acciones para editar o borrar sin llenar la pantalla de formularios.
                                        </p>
                                    </div>
                                    <div class="flex flex-wrap gap-3">
                                        <button type="button" class="public-action-add" @click="openCreate()">Añadir</button>
                                        <button type="button" class="public-panel-cancel" @click="closePanels()" x-show="mode" x-cloak>Cerrar panel</button>
                                    </div>
                                </div>

                                <div x-show="mode === 'create'" x-transition.opacity.duration.200ms x-cloak class="public-modal-panel">
                                    <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                        <div>
                                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-400">Nuevo bloque</p>
                                            <h3 class="mt-2 text-2xl font-semibold text-white">Añadir entrenamiento</h3>
                                        </div>
                                        <button type="button" class="public-panel-cancel" @click="closePanels()">Cancelar</button>
                                    </div>

                                    <form method="POST" action="{{ route('public.trainings.store') }}" class="mt-6 space-y-5">
                                        @csrf
                                        <input type="hidden" name="training_ui_mode" value="create" />
                                        <div class="public-form-grid">
                                            <label>
                                                <span class="public-label">Dia</span>
                                                <input type="date" name="dia" value="{{ old('dia') }}" class="public-input" />
                                                <x-input-error :messages="$errors->get('dia')" class="mt-2" />
                                            </label>
                                            <label>
                                                <span class="public-label">Lugar</span>
                                                <input name="lugar" value="{{ old('lugar') }}" class="public-input" placeholder="Pabellon principal" />
                                                <x-input-error :messages="$errors->get('lugar')" class="mt-2" />
                                            </label>
                                            <label>
                                                <span class="public-label">Hora de quedada</span>
                                                <input type="time" name="hora_quedada" value="{{ old('hora_quedada') }}" class="public-input" />
                                                <x-input-error :messages="$errors->get('hora_quedada')" class="mt-2" />
                                            </label>
                                            <label>
                                                <span class="public-label">Hora de entreno</span>
                                                <input type="time" name="hora_entreno" value="{{ old('hora_entreno') }}" class="public-input" />
                                                <x-input-error :messages="$errors->get('hora_entreno')" class="mt-2" />
                                            </label>
                                        </div>
                                        <div class="public-inline-actions">
                                            <button class="public-panel-submit">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            @endif

                            <div class="public-training-grid">
                                @forelse ($horarios as $horario)
                                    @php
                                        $fechaMostrada = $horario->dia;
                                        $formatearHora = static function ($hora) {
                                            if (!$hora) {
                                                return 'Pendiente';
                                            }

                                            try {
                                                return \Illuminate\Support\Carbon::createFromFormat('H:i:s', $hora)->format('H:i');
                                            } catch (\Throwable $exception) {
                                                try {
                                                    return \Illuminate\Support\Carbon::parse($hora)->format('H:i');
                                                } catch (\Throwable $exception) {
                                                    return $hora;
                                                }
                                            }
                                        };

                                        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $horario->dia)) {
                                            $fechaMostrada = \Illuminate\Support\Carbon::parse($horario->dia)->format('d/m/Y');
                                        }
                                    @endphp
                                    <article class="public-training-card" x-data="{ confirmDelete: false }">
                                        <div class="relative public-card-stack">
                                            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                                <div>
                                                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-200">Sesion</p>
                                                    <h3 class="mt-2 public-training-day">{{ $fechaMostrada ?: 'Dia sin definir' }}</h3>
                                                </div>

                                                @if ($puedeGestionarEquipo)
                                                    <div class="public-training-actions mt-0">
                                                        <button type="button" class="public-action-edit" @click="openEdit('{{ $horario->id }}')">Editar</button>
                                                        <button type="button" class="public-action-delete" @click="confirmDelete = !confirmDelete">Borrar</button>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="public-training-detail-grid public-card-divider">
                                                <div class="public-training-detail-card">
                                                    <p class="public-training-detail-label">Hora de quedada</p>
                                                    <p class="public-training-detail-value">{{ $formatearHora($horario->hora_quedada) }}</p>
                                                </div>
                                                <div class="public-training-detail-card">
                                                    <p class="public-training-detail-label">Hora de entreno</p>
                                                    <p class="public-training-detail-value">{{ $formatearHora($horario->hora_entreno) }}</p>
                                                </div>
                                                <div class="public-training-detail-card">
                                                    <p class="public-training-detail-label">Lugar</p>
                                                    <p class="public-training-detail-value public-training-detail-value-accent">{{ $horario->lugar ?: 'Pendiente' }}</p>
                                                </div>
                                            </div>

                                            @if ($puedeGestionarEquipo)
                                                <div x-show="isEditing('{{ $horario->id }}')" x-transition.opacity.duration.200ms x-cloak class="public-modal-panel mt-6">
                                                    <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                                        <div>
                                                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-400">Ajuste de sesion</p>
                                                            <h4 class="mt-2 text-xl font-semibold text-white">{{ $fechaMostrada ?: 'Sesion sin dia' }}</h4>
                                                        </div>
                                                        <button type="button" class="public-panel-cancel" @click="closePanels()">Cerrar</button>
                                                    </div>

                                                    <form method="POST" action="{{ route('public.trainings.update', $horario) }}" class="mt-6 space-y-5">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="training_ui_mode" value="edit" />
                                                        <input type="hidden" name="training_ui_id" value="{{ $horario->id }}" />
                                                        <div class="public-form-grid">
                                                            <label>
                                                                <span class="public-label">Dia</span>
                                                                <input type="date" name="dia" value="{{ old('training_ui_id') == $horario->id ? old('dia', $horario->dia) : $horario->dia }}" class="public-input" />
                                                                @if (old('training_ui_id') == $horario->id)
                                                                    <x-input-error :messages="$errors->get('dia')" class="mt-2" />
                                                                @endif
                                                            </label>
                                                            <label>
                                                                <span class="public-label">Lugar</span>
                                                                <input name="lugar" value="{{ old('training_ui_id') == $horario->id ? old('lugar', $horario->lugar) : $horario->lugar }}" class="public-input" />
                                                                @if (old('training_ui_id') == $horario->id)
                                                                    <x-input-error :messages="$errors->get('lugar')" class="mt-2" />
                                                                @endif
                                                            </label>
                                                            <label>
                                                                <span class="public-label">Hora de quedada</span>
                                                                <input type="time" name="hora_quedada" value="{{ old('training_ui_id') == $horario->id ? old('hora_quedada', $formatearHora($horario->hora_quedada)) : $formatearHora($horario->hora_quedada) }}" class="public-input" />
                                                                @if (old('training_ui_id') == $horario->id)
                                                                    <x-input-error :messages="$errors->get('hora_quedada')" class="mt-2" />
                                                                @endif
                                                            </label>
                                                            <label>
                                                                <span class="public-label">Hora de entreno</span>
                                                                <input type="time" name="hora_entreno" value="{{ old('training_ui_id') == $horario->id ? old('hora_entreno', $formatearHora($horario->hora_entreno)) : $formatearHora($horario->hora_entreno) }}" class="public-input" />
                                                                @if (old('training_ui_id') == $horario->id)
                                                                    <x-input-error :messages="$errors->get('hora_entreno')" class="mt-2" />
                                                                @endif
                                                            </label>
                                                        </div>
                                                        <div class="public-inline-actions">
                                                            <button class="public-panel-submit">Guardar</button>
                                                        </div>
                                                    </form>
                                                </div>

                                                <div x-show="confirmDelete" x-transition.opacity.duration.150ms x-cloak class="public-confirm-panel">
                                                    <p class="font-semibold text-white">Vas a borrar este entreno.</p>
                                                    <p class="mt-2">Si lo eliminas, desapareceran este dia, su hora de quedada, su hora de entreno y el lugar asociado.</p>
                                                    <div class="public-inline-actions">
                                                        <form method="POST" action="{{ route('public.trainings.destroy', $horario) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="public-action-delete" onclick="return confirm('¿Eliminar entrenamiento?')">Confirmar borrado</button>
                                                        </form>
                                                        <button type="button" class="public-panel-cancel" @click="confirmDelete = false">Cancelar</button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </article>
                                @empty
                                    <div class="public-empty-card text-sm xl:col-span-2">
                                        No hay horarios cargados para este equipo todavia.
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
