<?php

namespace Database\Seeders;

use App\Models\Coche;
use App\Models\Convocado;
use App\Models\Equipo;
use App\Models\Horario;
use App\Models\Multa;
use App\Models\OcupanteCoche;
use App\Models\Partido;
use App\Models\Usuario;
use App\Models\UsuarioEquipo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GeesSeeder extends Seeder
{
    /**
     * Ejecuta la carga de datos de prueba del proyecto GEES.
     */
    public function run(): void
    {
        $adminEmail = mb_strtolower(trim((string) env('GEES_ADMIN_EMAIL', 'admin@gees.local')));
        $adminPassword = (string) env('GEES_ADMIN_PASSWORD', 'admin12345');

        Usuario::updateOrCreate(
            ['correo' => $adminEmail],
            [
                'nombre_usuario' => 'Administrador GEES',
                'contrasena' => Hash::make($adminPassword),
            ]
        );

        $usuarioEntrenador = Usuario::firstOrCreate([
            'correo' => 'entrenador@gees.local',
        ], [
            'nombre_usuario' => 'Entrenador Principal',
            'contrasena' => Hash::make('password123'),
        ]);

        $usuarioJugador = Usuario::firstOrCreate([
            'correo' => 'jugador@gees.local',
        ], [
            'nombre_usuario' => 'Jugador Uno',
            'contrasena' => Hash::make('password123'),
        ]);

        $usuarioFamiliar = Usuario::firstOrCreate([
            'correo' => 'familiar@gees.local',
        ], [
            'nombre_usuario' => 'Familiar Uno',
            'contrasena' => Hash::make('password123'),
        ]);

        $equipo = Equipo::create([
            'nombre_equipo' => 'Infantil Alzira',
            'categoria' => 'Infantil (13-14 años)',
            'deporte' => 'Futbol sala',
            'tiene_multas' => true,
            'codigo_grupo_entrenador' => 'GRP-32875',
            'codigo_grupo_jugador' => 'GRP-43706',
            'codigo_grupo_familiar' => 'GRP-7283',
        ]);

        UsuarioEquipo::create([
            'id_usuario' => $usuarioEntrenador->id_usuario,
            'id_equipo' => $equipo->id_equipo,
            'rol' => 'entrenador',
        ]);

        UsuarioEquipo::create([
            'id_usuario' => $usuarioJugador->id_usuario,
            'id_equipo' => $equipo->id_equipo,
            'rol' => 'jugador',
        ]);

        UsuarioEquipo::create([
            'id_usuario' => $usuarioFamiliar->id_usuario,
            'id_equipo' => $equipo->id_equipo,
            'rol' => 'familiar',
        ]);

        Horario::create([
            'dia' => '01/04/2026',
            'hora_quedada' => '18:00',
            'hora_entreno' => '18:30',
            'lugar' => 'Pabellón Municipal',
            'id_equipo' => $equipo->id_equipo,
        ]);

        $partido = Partido::create([
            'id_equipo' => $equipo->id_equipo,
            'equipo_rival' => 'Algemesi',
            'fecha' => '2026-04-10',
            'hora_quedada' => '17:00',
            'hora_partido' => '18:00',
            'lugar' => 'Alzira',
        ]);

        Convocado::create([
            'id_partido' => $partido->id_partido,
            'nombre_jugador' => 'Jugador Uno',
            'dorsal' => 7,
        ]);

        $coche = Coche::create([
            'nombre_conductor' => 'Entrenador Principal',
            'numero_plazas' => 4,
            'id_equipo' => $equipo->id_equipo,
        ]);

        OcupanteCoche::create([
            'id_coche' => $coche->id_coche,
            'nombre_ocupante' => 'Jugador Uno',
        ]);

        Multa::create([
            'id_equipo' => $equipo->id_equipo,
            'motivo' => 'Llegar tarde',
            'monto' => 1.50,
            'pagada' => false,
            'fecha_asignacion' => '2026-04-02',
            'nombre_jugador' => 'Jugador Uno',
        ]);
    }
}
