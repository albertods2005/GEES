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
                            mode: @js(old('fine_ui_mode', '')),
                            openCreate() {
                                this.mode = 'create';
                            },
                            closePanels() {
                                this.mode = '';
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
                                    <span class="public-pill">{{ $multas->count() }} {{ $multas->count() === 1 ? 'multa' : 'multas' }}</span>
                                </div>
                                <p class="text-sm leading-7 text-slate-300">
                                    Consulta las multas del equipo de un vistazo y marca el pago en cuanto quede resuelto.
                                </p>
                            </div>

                            @if ($puedeGestionarEquipo)
                                <div class="public-toolbar">
                                    <div>
                                        <p class="text-lg font-semibold text-white">Panel de entrenador</p>
                                        <p class="public-toolbar-copy">
                                            Añade multas nuevas desde un solo panel y luego gestionalas desde cada tarjeta con las acciones justas.
                                        </p>
                                    </div>
                                    <div class="flex flex-wrap gap-3">
                                        <button type="button" class="public-action-add" @click="openCreate()">Añadir multa</button>
                                        <button type="button" class="public-panel-cancel" @click="closePanels()" x-show="mode" x-cloak>Cerrar panel</button>
                                    </div>
                                </div>

                                <div x-show="mode === 'create'" x-transition.opacity.duration.200ms x-cloak class="public-modal-panel">
                                    <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                        <div>
                                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-400">Nueva multa</p>
                                            <h3 class="mt-2 text-2xl font-semibold text-white">Añadir sancion</h3>
                                        </div>
                                        <button type="button" class="public-panel-cancel" @click="closePanels()">Cancelar</button>
                                    </div>

                                    <form method="POST" action="{{ route('public.fines.store') }}" class="mt-6 space-y-5">
                                        @csrf
                                        <input type="hidden" name="fine_ui_mode" value="create" />
                                        <input type="hidden" name="pagada" value="0" />
                                        <div class="public-form-grid">
                                            <label>
                                                <span class="public-label">Jugador</span>
                                                <input name="nombre_jugador" value="{{ old('nombre_jugador') }}" class="public-input" placeholder="Nombre del jugador" />
                                                <x-input-error :messages="$errors->get('nombre_jugador')" class="mt-2" />
                                            </label>
                                            <label>
                                                <span class="public-label">Motivo</span>
                                                <input name="motivo" value="{{ old('motivo') }}" class="public-input" placeholder="Retraso, equipacion..." />
                                                <x-input-error :messages="$errors->get('motivo')" class="mt-2" />
                                            </label>
                                            <label>
                                                <span class="public-label">Importe</span>
                                                <input type="number" step="0.01" min="0" name="monto" value="{{ old('monto') }}" class="public-input" placeholder="5.00" />
                                                <x-input-error :messages="$errors->get('monto')" class="mt-2" />
                                            </label>
                                            <label>
                                                <span class="public-label">Fecha</span>
                                                <input type="date" name="fecha_asignacion" value="{{ old('fecha_asignacion') }}" class="public-input" />
                                                <x-input-error :messages="$errors->get('fecha_asignacion')" class="mt-2" />
                                            </label>
                                        </div>
                                        <div class="public-inline-actions">
                                            <button class="public-panel-submit">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            @endif

                            <div class="public-data-grid">
                                @forelse ($multas as $multa)
                                    <article class="public-training-card">
                                        <div class="public-card-stack">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                            <div class="flex min-h-[4.5rem] items-center">
                                                <p class="text-2xl font-semibold leading-none text-white lg:text-3xl">{{ $multa->motivo ?: 'Motivo pendiente' }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-lg font-semibold text-white">{{ number_format((float) $multa->monto, 2, ',', '.') }} EUR</p>
                                                <span class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] {{ $multa->pagada ? 'border border-emerald-300/20 bg-emerald-400/15 text-emerald-200' : 'border border-rose-300/20 bg-rose-400/15 text-rose-200' }}">
                                                    {{ $multa->pagada ? 'Pagada' : 'No pagada' }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="public-meta-grid public-card-divider">
                                            <p class="public-meta-item">Fecha: <span class="font-semibold text-white">{{ $multa->fecha_asignacion?->format('d/m/Y') ?? 'Pendiente' }}</span></p>
                                            <p class="public-meta-item">Jugador: <span class="font-semibold text-white">{{ $multa->nombre_jugador ?: 'No indicado' }}</span></p>
                                            <p class="public-meta-item">Estado: <span class="{{ $multa->pagada ? 'text-emerald-200' : 'text-rose-200' }} font-semibold">{{ $multa->pagada ? 'Pagada' : 'No pagada' }}</span></p>
                                        </div>

                                        @if ($puedeGestionarEquipo)
                                            <div class="public-inline-actions public-card-divider">
                                                @unless ($multa->pagada)
                                                    <form method="POST" action="{{ route('public.fines.mark-paid', $multa) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="public-panel-submit" onclick="return confirm('¿Marcar esta multa como pagada?')">Pagada</button>
                                                    </form>
                                                @endunless

                                                <form method="POST" action="{{ route('public.fines.destroy', $multa) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="public-action-delete" onclick="return confirm('¿Eliminar multa?')">Borrar</button>
                                                </form>
                                            </div>
                                        @endif
                                        </div>
                                    </article>
                                @empty
                                    <div class="public-empty-card text-sm">
                                        No hay multas cargadas para este equipo todavia.
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
