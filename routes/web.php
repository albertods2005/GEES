<?php

use App\Http\Controllers\CocheController;
use App\Http\Controllers\ConvocadoController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\MultaController;
use App\Http\Controllers\OcupanteCocheController;
use App\Http\Controllers\PartidoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\UsuarioEquipoController;
use App\Models\Equipo;
use App\Models\Usuario;
use Illuminate\Support\Facades\Route;

$publicSections = [
    [
        'uri' => 'mis-equipos',
        'route' => 'public.my-teams',
        'title' => 'Mis equipos',
        'eyebrow' => 'Tus accesos',
        'description' => 'Aqui veras todos los equipos a los que perteneces y el rol con el que participas en cada uno.',
        'hero' => 'Accede rapido a todos tus equipos',
        'audiences' => ['Jugadores', 'Entrenadores', 'Familiares'],
        'highlights' => [
            'Listado de equipos asociados al usuario.',
            'Rol visible en cada acceso.',
            'Entrada directa a horarios y siguientes apartados.',
        ],
        'panels' => [
            ['title' => 'Vista personal', 'text' => 'Cada usuario vera sus propios equipos segun las relaciones guardadas en la base de datos.'],
            ['title' => 'Rol destacado', 'text' => 'Mostramos claramente si perteneces como entrenador, jugador o familiar.'],
            ['title' => 'Accion directa', 'text' => 'Cada equipo sera un acceso rapido hacia sus horarios y su informacion asociada.'],
        ],
    ],
    [
        'uri' => 'equipos/unirse',
        'route' => 'public.join-team',
        'title' => 'Unete a tu equipo',
        'eyebrow' => 'Acceso principal',
        'description' => 'La puerta de entrada para jugadores, entrenadores y familiares. Desde aqui podras aceptar invitaciones, vincularte a un equipo y empezar a ver su informacion.',
        'hero' => 'Encuentra tu vestuario en segundos',
        'audiences' => ['Jugadores', 'Entrenadores', 'Familiares'],
        'highlights' => [
            'Busqueda por codigo o invitacion del equipo.',
            'Estado de acceso pendiente para nuevas incorporaciones.',
            'Resumen inicial del rol asociado al usuario.',
        ],
        'panels' => [
            ['title' => 'Jugadores', 'text' => 'Entraran para consultar convocatorias, entrenamientos y su actividad dentro del equipo.'],
            ['title' => 'Entrenadores', 'text' => 'Tendran acceso rapido a gestion deportiva, convocatorias y pizarra tactica.'],
            ['title' => 'Familiares', 'text' => 'Veran la informacion compartida del equipo, avisos y disponibilidad de traslados.'],
        ],
    ],
    [
        'uri' => 'equipos/crear',
        'route' => 'public.create-team',
        'title' => 'Crea un equipo',
        'eyebrow' => 'Configuracion inicial',
        'description' => 'Pantalla pensada para responsables deportivos y entrenadores. Aqui se iniciara el alta de un nuevo equipo con sus datos, categoria y estructura basica.',
        'hero' => 'Empieza un proyecto deportivo con una base clara',
        'audiences' => ['Entrenadores', 'Coordinacion', 'Administracion'],
        'highlights' => [
            'Formulario inicial de nombre, categoria y temporada.',
            'Asignacion futura de cuerpo tecnico y jugadores.',
            'Punto de partida para reglas, convocatorias y sesiones.',
        ],
        'panels' => [
            ['title' => 'Alta guiada', 'text' => 'La idea es que el proceso sea muy simple y que luego podamos ampliar los detalles equipo por equipo.'],
            ['title' => 'Roles preparados', 'text' => 'Mas adelante mostraremos acciones distintas segun el rol, pero la vista ya queda lista para esa evolucion.'],
            ['title' => 'Conexion con el admin', 'text' => 'El alta publica convivira con la gestion avanzada del panel de administracion que ya existe.'],
        ],
    ],
    [
        'uri' => 'entrenamientos',
        'route' => 'public.trainings',
        'title' => 'Entrenamientos',
        'eyebrow' => 'Calendario deportivo',
        'description' => 'Vista para consultar sesiones, horarios y trabajo semanal del equipo. Servira tanto para ver planificacion como para confirmar asistencia.',
        'hero' => 'Toda la semana de trabajo en un solo lugar',
        'audiences' => ['Jugadores', 'Entrenadores', 'Familiares'],
        'highlights' => [
            'Calendario con proximos entrenamientos.',
            'Indicaciones de hora, lugar y notas del cuerpo tecnico.',
            'Espacio preparado para control de asistencia.',
        ],
        'panels' => [
            ['title' => 'Semana visible', 'text' => 'Queremos que con un vistazo sepas cuando entrena cada equipo y que informacion tienes que revisar.'],
            ['title' => 'Preparado para detalle', 'text' => 'Mas adelante podremos añadir ejercicios, tareas y observaciones por sesion.'],
            ['title' => 'Vista segun rol', 'text' => 'La informacion se filtrara segun seas entrenador, jugador o familiar autorizado.'],
        ],
    ],
    [
        'uri' => 'convocatorias',
        'route' => 'public.callups',
        'title' => 'Convocatorias',
        'eyebrow' => 'Partidos y citaciones',
        'description' => 'Aqui se consultaran listas de convocados, horarios de citacion y respuestas de disponibilidad para cada partido o evento.',
        'hero' => 'Responde a cada convocatoria sin perder contexto',
        'audiences' => ['Jugadores', 'Entrenadores', 'Familiares'],
        'highlights' => [
            'Listado de convocatorias abiertas y pasadas.',
            'Confirmacion de asistencia o ausencia.',
            'Detalle de partido, punto de encuentro y notas importantes.',
        ],
        'panels' => [
            ['title' => 'Respuesta rapida', 'text' => 'El objetivo es que cualquier usuario pueda confirmar asistencia desde una pantalla muy clara.'],
            ['title' => 'Control tecnico', 'text' => 'Los entrenadores veran la lista de respuestas y podran ajustar la convocatoria.'],
            ['title' => 'Apoyo familiar', 'text' => 'Los familiares tendran visibilidad cuando ese rol deba confirmar o consultar informacion.'],
        ],
    ],
    [
        'uri' => 'equipo/multas',
        'route' => 'public.fines',
        'title' => 'Multas',
        'eyebrow' => 'Disciplina de equipo',
        'description' => 'Pantalla inicial para consultar normas internas, incidencias y seguimiento economico asociado al equipo cuando corresponda.',
        'hero' => 'Mantén claras las normas y su seguimiento',
        'audiences' => ['Jugadores', 'Entrenadores', 'Administracion'],
        'highlights' => [
            'Resumen de normas y tipos de sancion.',
            'Espacio para seguimiento de importes o incidencias.',
            'Preparado para visibilidad parcial segun rol.',
        ],
        'panels' => [
            ['title' => 'Normas visibles', 'text' => 'La vista puede servir tanto para recordar reglas como para seguir sanciones concretas.'],
            ['title' => 'Roles distintos', 'text' => 'No todos veran lo mismo: mas adelante afinaremos permisos y detalle segun perfil.'],
            ['title' => 'Integracion futura', 'text' => 'Encajara con el modulo de multas del admin, pero con una experiencia mas centrada en el usuario final.'],
        ],
    ],
    [
        'uri' => 'pizarra',
        'route' => 'public.board',
        'title' => 'Pizarra tactica',
        'eyebrow' => 'Trabajo tactico',
        'description' => 'Espacio donde se prepararan esquemas, movimientos y tareas visuales del equipo. De momento sera una vista base con la estructura de navegacion.',
        'hero' => 'Diseña ideas de juego antes de bajarlas al campo',
        'audiences' => ['Entrenadores', 'Asistentes'],
        'highlights' => [
            'Zona reservada para crear tacticas y movimientos.',
            'Preparada para futuras herramientas visuales.',
            'Relacionada con entrenamientos y convocatorias.',
        ],
        'panels' => [
            ['title' => 'Base visual', 'text' => 'Dejamos una pantalla con caracter para que la pizarra ya exista en la navegacion general.'],
            ['title' => 'Escalable', 'text' => 'Luego podremos decidir si usar fichas, tableros por fases o ejercicios enlazados.'],
            ['title' => 'Acceso restringido', 'text' => 'Esta seccion quedara especialmente orientada a perfiles tecnicos.'],
        ],
    ],
];

Route::view('/', 'public.home', ['sections' => $publicSections])->name('home');

foreach ($publicSections as $section) {
    if (in_array($section['route'], ['public.create-team', 'public.join-team', 'public.my-teams', 'public.trainings', 'public.callups', 'public.board', 'public.fines'], true)) {
        continue;
    }

    Route::view($section['uri'], 'public.section', [
        'sections' => $publicSections,
        'section' => $section,
    ])->name($section['route']);
}

Route::get('/equipos/crear', function () use ($publicSections) {
    return view('public.create-team', [
        'sections' => $publicSections,
        'section' => collect($publicSections)->firstWhere('route', 'public.create-team'),
        ...\App\Http\Controllers\EquipoController::opcionesFormulario(),
    ]);
})->name('public.create-team');

Route::get('/mis-equipos', [EquipoController::class, 'myTeams'])->name('public.my-teams');
Route::get('/mis-equipos/{equipo}', [EquipoController::class, 'selectPublicTeam'])->name('public.my-teams.select');

Route::get('/equipos/unirse', function () use ($publicSections) {
    return view('public.join-team', [
        'sections' => $publicSections,
        'section' => collect($publicSections)->firstWhere('route', 'public.join-team'),
    ]);
})->name('public.join-team');

Route::get('/entrenamientos', [EquipoController::class, 'publicTrainings'])->name('public.trainings');
Route::get('/convocatorias', [EquipoController::class, 'publicCallups'])->name('public.callups');
Route::get('/pizarra', [EquipoController::class, 'publicBoard'])->name('public.board');
Route::get('/equipo/multas', [EquipoController::class, 'publicFines'])->name('public.fines');

Route::middleware(['auth'])->group(function () {
    Route::post('/equipos/crear', [EquipoController::class, 'storePublic'])->name('public.create-team.store');
    Route::post('/equipos/unirse', [EquipoController::class, 'joinByCode'])->name('public.join-team.store');

    Route::post('/entrenamientos', [HorarioController::class, 'storePublic'])->name('public.trainings.store');
    Route::put('/entrenamientos/{horario}', [HorarioController::class, 'updatePublic'])->name('public.trainings.update');
    Route::delete('/entrenamientos/{horario}', [HorarioController::class, 'destroyPublic'])->name('public.trainings.destroy');

    Route::post('/convocatorias', [PartidoController::class, 'storePublic'])->name('public.callups.matches.store');
    Route::put('/convocatorias/{partido}', [PartidoController::class, 'updatePublic'])->name('public.callups.matches.update');
    Route::delete('/convocatorias/{partido}', [PartidoController::class, 'destroyPublic'])->name('public.callups.matches.destroy');

    Route::post('/convocatorias/{partido}/convocados', [ConvocadoController::class, 'storePublic'])->name('public.callups.players.store');
    Route::put('/convocatorias/convocados/{convocado}', [ConvocadoController::class, 'updatePublic'])->name('public.callups.players.update');
    Route::delete('/convocatorias/convocados/{convocado}', [ConvocadoController::class, 'destroyPublic'])->name('public.callups.players.destroy');

    Route::post('/equipo/multas', [MultaController::class, 'storePublic'])->name('public.fines.store');
    Route::put('/equipo/multas/{multa}', [MultaController::class, 'updatePublic'])->name('public.fines.update');
    Route::patch('/equipo/multas/{multa}/pagada', [MultaController::class, 'markAsPaidPublic'])->name('public.fines.mark-paid');
    Route::delete('/equipo/multas/{multa}', [MultaController::class, 'destroyPublic'])->name('public.fines.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard', [
            'totalEquipos' => Equipo::query()->count(),
            'totalUsuarios' => Usuario::query()->count(),
            'ultimosEquipos' => Equipo::query()
                ->withCount('usuarios')
                ->orderByDesc('id_equipo')
                ->limit(8)
                ->get(),
        ]);
    })->name('dashboard');

    Route::resource('usuarios', UsuarioController::class)->except(['show']);
    Route::resource('equipos', EquipoController::class)->except(['show']);
    Route::resource('usuarios-equipos', UsuarioEquipoController::class)->except(['show']);
    Route::resource('horarios', HorarioController::class)->except(['show']);
    Route::resource('partidos', PartidoController::class)->except(['show']);
    Route::resource('convocados', ConvocadoController::class)->except(['show']);
    Route::resource('coches', CocheController::class)
        ->parameters(['coches' => 'coche'])
        ->except(['show']);
    Route::resource('ocupantes-coche', OcupanteCocheController::class)
        ->parameters(['ocupantes-coche' => 'ocupante_coche'])
        ->except(['show']);
    Route::resource('multas', MultaController::class)->except(['show']);
});

require __DIR__.'/auth.php';
