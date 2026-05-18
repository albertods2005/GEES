<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;

abstract class Controller
{
    /**
     * Devuelve el equipo activo del usuario autenticado conservando el pivote con el rol.
     */
    protected function equipoActivoDelUsuario(Request $request): ?Equipo
    {
        $usuario = $request->user();
        $equipoActivoId = $request->session()->get('equipo_activo_id');

        if (! $usuario || ! $equipoActivoId) {
            return null;
        }

        return $usuario->equipos()
            ->where('equipos.id_equipo', $equipoActivoId)
            ->first();
    }

    /**
     * Devuelve el equipo activo solo si el usuario pertenece a él como entrenador.
     */
    protected function equipoActivoComoEntrenador(Request $request): ?Equipo
    {
        $equipo = $this->equipoActivoDelUsuario($request);

        if (! $equipo || $equipo->pivot?->rol !== 'entrenador') {
            return null;
        }

        return $equipo;
    }
}
