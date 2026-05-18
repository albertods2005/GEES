<?php

namespace App\Http\Controllers;

use App\Models\Convocado;
use App\Models\Equipo;
use App\Models\Horario;
use App\Models\Multa;
use App\Models\Partido;
use App\Models\UsuarioEquipo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EquipoController extends Controller
{
    private const DEPORTES = [
        'Futbol' => 'Futbol',
        'Futbol sala' => 'Futbol sala',
        'Baloncesto' => 'Baloncesto',
    ];

    private const CATEGORIAS = [
        'Prebenjamin' => 'Prebenjamin',
        'Benjamin' => 'Benjamin',
        'Alevin' => 'Alevin',
        'Infantil' => 'Infantil',
        'Cadete' => 'Cadete',
        'Juvenil' => 'Juvenil',
        'Senior' => 'Senior',
    ];

    /**
     * Lista todos los equipos creados en la aplicacion.
     */
    public function index(Request $request): View
    {
        $busqueda = trim((string) $request->query('q', ''));
        $equipos = Equipo::query()
            ->withCount('usuarios')
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(function ($query) use ($busqueda) {
                    if (ctype_digit($busqueda)) {
                        $query->orWhere('id_equipo', (int) $busqueda);
                    }

                    $query->orWhere('nombre_equipo', 'like', "%{$busqueda}%")
                        ->orWhere('categoria', 'like', "%{$busqueda}%")
                        ->orWhere('deporte', 'like', "%{$busqueda}%")
                        ->orWhere('codigo_grupo_entrenador', 'like', "%{$busqueda}%")
                        ->orWhere('codigo_grupo_jugador', 'like', "%{$busqueda}%")
                        ->orWhere('codigo_grupo_familiar', 'like', "%{$busqueda}%");
                });
            })
            ->orderByDesc('id_equipo')
            ->get();

        return view('gees.equipos.index', compact('equipos'));
    }

    /**
     * Muestra formulario de creación.
     */
    public function create(): View
    {
        return view('gees.equipos.create', [
            'deportes' => self::DEPORTES,
            'categorias' => self::CATEGORIAS,
        ]);
    }

    /**
     * Guarda un equipo nuevo.
     */
    public function store(Request $request): RedirectResponse
    {
        $datosValidados = $this->validarDatosEquipo($request);

        Equipo::create($this->prepararDatosEquipo($datosValidados));

        return redirect()->route('equipos.index')->with('exito', 'Equipo creado correctamente.');
    }

    /**
     * Muestra formulario de edición.
     */
    public function edit(Equipo $equipo): View
    {
        return view('gees.equipos.edit', [
            'equipo' => $equipo,
            'deportes' => self::DEPORTES,
            'categorias' => self::CATEGORIAS,
        ]);
    }

    /**
     * Actualiza un equipo.
     */
    public function update(Request $request, Equipo $equipo): RedirectResponse
    {
        $datosValidados = $request->validate([
            'nombre_equipo' => ['required', 'string', 'max:255'],
            'categoria' => ['required', 'string', 'in:'.implode(',', array_keys(self::CATEGORIAS))],
            'deporte' => ['required', 'string', 'in:'.implode(',', array_keys(self::DEPORTES))],
            'tiene_multas' => ['required', 'boolean'],
        ]);

        if (empty($equipo->codigo_grupo_entrenador)) {
            $datosValidados['codigo_grupo_entrenador'] = $this->generarCodigoGrupo($datosValidados['nombre_equipo'], 'ENT');
        }

        if (empty($equipo->codigo_grupo_jugador)) {
            $datosValidados['codigo_grupo_jugador'] = $this->generarCodigoGrupo($datosValidados['nombre_equipo'], 'JUG');
        }

        if (empty($equipo->codigo_grupo_familiar)) {
            $datosValidados['codigo_grupo_familiar'] = $this->generarCodigoGrupo($datosValidados['nombre_equipo'], 'FAM');
        }

        $equipo->update($datosValidados);

        return redirect()->route('equipos.index')->with('exito', 'Equipo actualizado correctamente.');
    }

    /**
     * Elimina un equipo.
     */
    public function destroy(Equipo $equipo): RedirectResponse
    {
        $equipo->delete();

        return redirect()->route('equipos.index')->with('exito', 'Equipo eliminado correctamente.');
    }

    /**
     * Guarda un equipo desde la zona de usuario y vincula al creador como entrenador.
     */
    public function storePublic(Request $request): RedirectResponse
    {
        $datosValidados = $this->validarDatosEquipo($request);
        $usuario = $request->user();

        $equipo = DB::transaction(function () use ($datosValidados, $usuario) {
            $equipo = Equipo::create($this->prepararDatosEquipo($datosValidados));

            UsuarioEquipo::firstOrCreate([
                'id_usuario' => $usuario->id_usuario,
                'id_equipo' => $equipo->id_equipo,
            ], [
                'rol' => 'entrenador',
            ]);

            return $equipo;
        });

        return redirect()
            ->route('public.join-team')
            ->with('exito', 'Equipo creado correctamente. Ahora eres el entrenador de '.$equipo->nombre_equipo.'.');
    }

    /**
     * Une al usuario autenticado a un equipo segun el codigo introducido.
     */
    public function joinByCode(Request $request): RedirectResponse
    {
        $datosValidados = $request->validate([
            'codigo_equipo' => ['required', 'string', 'max:255'],
        ]);

        $codigo = trim($datosValidados['codigo_equipo']);

        $equipo = Equipo::query()
            ->where('codigo_grupo_entrenador', $codigo)
            ->orWhere('codigo_grupo_jugador', $codigo)
            ->orWhere('codigo_grupo_familiar', $codigo)
            ->first();

        if (! $equipo) {
            return back()
                ->withErrors(['codigo_equipo' => 'El codigo introducido no corresponde a ningun equipo.'])
                ->withInput();
        }

        $rol = match ($codigo) {
            $equipo->codigo_grupo_entrenador => 'entrenador',
            $equipo->codigo_grupo_jugador => 'jugador',
            $equipo->codigo_grupo_familiar => 'familiar',
            default => null,
        };

        if (! $rol) {
            return back()
                ->withErrors(['codigo_equipo' => 'No se ha podido determinar el rol asociado a ese codigo.'])
                ->withInput();
        }

        $relacionExistente = UsuarioEquipo::query()
            ->where('id_usuario', $request->user()->id_usuario)
            ->where('id_equipo', $equipo->id_equipo)
            ->first();

        if ($relacionExistente) {
            return back()
                ->withErrors(['codigo_equipo' => 'Ya perteneces a este equipo como '.$relacionExistente->rol.'.'])
                ->withInput();
        }

        UsuarioEquipo::create([
            'id_usuario' => $request->user()->id_usuario,
            'id_equipo' => $equipo->id_equipo,
            'rol' => $rol,
        ]);

        return redirect()
            ->route('public.join-team')
            ->with('exito', 'Te has unido correctamente a '.$equipo->nombre_equipo.' como '.$rol.'.');
    }

    /**
     * Muestra los equipos del usuario autenticado en la zona publica.
     */
    public function myTeams(Request $request): View
    {
        $sections = $this->publicSections();
        $section = collect($sections)->firstWhere('route', 'public.my-teams');
        $equipos = collect();
        $equipoActivoId = $request->session()->get('equipo_activo_id');

        if ($request->user()) {
            $equipos = $request->user()
                ->equipos()
                ->orderBy('nombre_equipo')
                ->get();
        }

        return view('public.my-teams', [
            'sections' => $sections,
            'section' => $section,
            'equipos' => $equipos,
            'equipoActivoId' => $equipoActivoId,
        ]);
    }

    /**
     * Guarda el equipo activo del usuario y redirige a la zona del equipo.
     */
    public function selectPublicTeam(Request $request, Equipo $equipo): RedirectResponse
    {
        $equipoUsuario = $this->equipoDelUsuario($request, $equipo->id_equipo);

        if (! $equipoUsuario) {
            return redirect()
                ->route('public.my-teams')
                ->withErrors(['equipo' => 'No puedes acceder a ese equipo.']);
        }

        $request->session()->put('equipo_activo_id', $equipoUsuario->id_equipo);

        return redirect()
            ->route('public.trainings')
            ->with('exito', 'Has entrado en '.$equipoUsuario->nombre_equipo.' como '.$equipoUsuario->pivot->rol.'.');
    }

    /**
     * Muestra la vista de entrenamientos del equipo activo.
     */
    public function publicTrainings(Request $request): View
    {
        [$sections, $section, $equiposUsuario, $equipoSeleccionado] = $this->resolverContextoEquipo($request, 'public.trainings');
        $horarios = collect();

        if ($equipoSeleccionado) {
            $horarios = Horario::query()
                ->where('id_equipo', $equipoSeleccionado->id_equipo)
                ->orderBy('dia')
                ->orderBy('hora_entreno')
                ->get();
        }

        return view('public.trainings', [
            'sections' => $sections,
            'section' => $section,
            'equiposUsuario' => $equiposUsuario,
            'equipoSeleccionado' => $equipoSeleccionado,
            'puedeGestionarEquipo' => $equipoSeleccionado?->pivot?->rol === 'entrenador',
            'horarios' => $horarios,
        ]);
    }

    /**
     * Muestra las convocatorias del equipo activo.
     */
    public function publicCallups(Request $request): View
    {
        [$sections, $section, $equiposUsuario, $equipoSeleccionado] = $this->resolverContextoEquipo($request, 'public.callups');
        $partidos = collect();

        if ($equipoSeleccionado) {
            $partidos = Partido::query()
                ->with('convocados')
                ->where('id_equipo', $equipoSeleccionado->id_equipo)
                ->orderByDesc('fecha')
                ->orderByDesc('hora_partido')
                ->get();
        }

        return view('public.callups', [
            'sections' => $sections,
            'section' => $section,
            'equiposUsuario' => $equiposUsuario,
            'equipoSeleccionado' => $equipoSeleccionado,
            'puedeGestionarEquipo' => $equipoSeleccionado?->pivot?->rol === 'entrenador',
            'partidos' => $partidos,
        ]);
    }

    /**
     * Muestra la pizarra tactica del equipo activo.
     */
    public function publicBoard(Request $request): View
    {
        [$sections, $section, $equiposUsuario, $equipoSeleccionado] = $this->resolverContextoEquipo($request, 'public.board');

        return view('public.board', [
            'sections' => $sections,
            'section' => $section,
            'equiposUsuario' => $equiposUsuario,
            'equipoSeleccionado' => $equipoSeleccionado,
            'puedeGestionarEquipo' => $equipoSeleccionado?->pivot?->rol === 'entrenador',
        ]);
    }

    /**
     * Muestra las multas del equipo activo.
     */
    public function publicFines(Request $request): View
    {
        [$sections, $section, $equiposUsuario, $equipoSeleccionado] = $this->resolverContextoEquipo($request, 'public.fines');
        $multas = collect();

        if ($equipoSeleccionado) {
            $multas = Multa::query()
                ->where('id_equipo', $equipoSeleccionado->id_equipo)
                ->orderByDesc('fecha_asignacion')
                ->get();
        }

        return view('public.fines', [
            'sections' => $sections,
            'section' => $section,
            'equiposUsuario' => $equiposUsuario,
            'equipoSeleccionado' => $equipoSeleccionado,
            'puedeGestionarEquipo' => $equipoSeleccionado?->pivot?->rol === 'entrenador',
            'multas' => $multas,
        ]);
    }

    /**
     * Devuelve las opciones disponibles del formulario publico de equipos.
     *
     * @return array{deportes: array<string, string>, categorias: array<string, string>}
     */
    public static function opcionesFormulario(): array
    {
        return [
            'deportes' => self::DEPORTES,
            'categorias' => self::CATEGORIAS,
        ];
    }

    /**
     * Devuelve la definicion base de apartados publicos.
     *
     * @return array<int, array<string, mixed>>
     */
    public function publicSections(): array
    {
        return [
            [
                'uri' => 'mis-equipos',
                'route' => 'public.my-teams',
                'title' => 'Mis equipos',
                'eyebrow' => 'Tus accesos',
                'description' => 'Aqui veras todos los equipos a los que perteneces y el rol con el que participas en cada uno.',
                'hero' => 'Accede rapido a todos tus equipos',
            ],
            [
                'uri' => 'equipos/unirse',
                'route' => 'public.join-team',
                'title' => 'Unete a tu equipo',
                'eyebrow' => 'Acceso principal',
                'description' => 'La puerta de entrada para jugadores, entrenadores y familiares. Desde aqui podras aceptar invitaciones, vincularte a un equipo y empezar a ver su informacion.',
                'hero' => 'Encuentra tu vestuario en segundos',
            ],
            [
                'uri' => 'equipos/crear',
                'route' => 'public.create-team',
                'title' => 'Crea un equipo',
                'eyebrow' => 'Configuracion inicial',
                'description' => 'Pantalla pensada para responsables deportivos y entrenadores. Aqui se iniciara el alta de un nuevo equipo con sus datos, categoria y estructura basica.',
                'hero' => 'Empieza un proyecto deportivo con una base clara',
            ],
            [
                'uri' => 'entrenamientos',
                'route' => 'public.trainings',
                'title' => 'Entrenamientos',
                'eyebrow' => 'Calendario deportivo',
                'description' => 'Vista para consultar sesiones, horarios y trabajo semanal del equipo. Servira tanto para ver planificacion como para confirmar asistencia.',
                'hero' => 'Toda la semana de trabajo en un solo lugar',
            ],
            [
                'uri' => 'convocatorias',
                'route' => 'public.callups',
                'title' => 'Convocatorias',
                'eyebrow' => 'Partidos y citaciones',
                'description' => 'Aqui se consultaran listas de convocados, horarios de citacion y respuestas de disponibilidad para cada partido o evento.',
                'hero' => 'Responde a cada convocatoria sin perder contexto',
            ],
            [
                'uri' => 'equipo/multas',
                'route' => 'public.fines',
                'title' => 'Multas',
                'eyebrow' => 'Disciplina de equipo',
                'description' => 'Pantalla inicial para consultar normas internas, incidencias y seguimiento economico asociado al equipo cuando corresponda.',
                'hero' => 'Mantén claras las normas y su seguimiento',
            ],
            [
                'uri' => 'pizarra',
                'route' => 'public.board',
                'title' => 'Pizarra tactica',
                'eyebrow' => 'Trabajo tactico',
                'description' => 'Espacio donde se prepararan esquemas, movimientos y tareas visuales del equipo. De momento sera una vista base con la estructura de navegacion.',
                'hero' => 'Diseña ideas de juego antes de bajarlas al campo',
            ],
        ];
    }

    /**
     * Genera un codigo simple y legible para cada rol del equipo.
     */
    private function generarCodigoGrupo(string $nombreEquipo, string $prefijo): string
    {
        $base = Str::upper(Str::slug(Str::limit($nombreEquipo, 18, ''), ''));

        if ($base === '') {
            $base = 'EQUIPO';
        }

        do {
            $codigo = $prefijo.'-'.$base.'-'.Str::upper(Str::random(6));
        } while (Equipo::query()->where('codigo_grupo_entrenador', $codigo)
            ->orWhere('codigo_grupo_jugador', $codigo)
            ->orWhere('codigo_grupo_familiar', $codigo)
            ->exists());

        return $codigo;
    }

    /**
     * Reglas comunes de validacion para alta de equipos.
     *
     * @return array<string, mixed>
     */
    private function validarDatosEquipo(Request $request): array
    {
        return $request->validate([
            'nombre_equipo' => ['required', 'string', 'max:255'],
            'categoria' => ['required', 'string', 'in:'.implode(',', array_keys(self::CATEGORIAS))],
            'deporte' => ['required', 'string', 'in:'.implode(',', array_keys(self::DEPORTES))],
            'tiene_multas' => ['required', 'boolean'],
        ]);
    }

    /**
     * Completa los datos persistibles del equipo antes de guardarlo.
     *
     * @param array<string, mixed> $datosValidados
     * @return array<string, mixed>
     */
    private function prepararDatosEquipo(array $datosValidados): array
    {
        $datosValidados['codigo_grupo_entrenador'] = $this->generarCodigoGrupo($datosValidados['nombre_equipo'], 'ENT');
        $datosValidados['codigo_grupo_jugador'] = $this->generarCodigoGrupo($datosValidados['nombre_equipo'], 'JUG');
        $datosValidados['codigo_grupo_familiar'] = $this->generarCodigoGrupo($datosValidados['nombre_equipo'], 'FAM');

        return $datosValidados;
    }

    /**
     * Devuelve el contexto comun de equipo activo para la zona publica.
     *
     * @return array{0: array<int, array<string, mixed>>, 1: array<string, mixed>|null, 2: \Illuminate\Support\Collection<int, Equipo>, 3: Equipo|null}
     */
    private function resolverContextoEquipo(Request $request, string $routeName): array
    {
        $sections = $this->publicSections();
        $section = collect($sections)->firstWhere('route', $routeName);
        $equiposUsuario = collect();
        $equipoSeleccionado = null;

        if ($request->user()) {
            $equiposUsuario = $request->user()
                ->equipos()
                ->orderBy('nombre_equipo')
                ->get();

            $equipoSeleccionado = $equiposUsuario->firstWhere(
                'id_equipo',
                $request->session()->get('equipo_activo_id')
            );
        }

        return [$sections, $section, $equiposUsuario, $equipoSeleccionado];
    }

    /**
     * Busca un equipo del usuario autenticado conservando el rol del pivote.
     */
    private function equipoDelUsuario(Request $request, int $equipoId): ?Equipo
    {
        if (! $request->user()) {
            return null;
        }

        return $request->user()
            ->equipos()
            ->where('equipos.id_equipo', $equipoId)
            ->first();
    }
}
