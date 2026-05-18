<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo legado mantenido temporalmente para evitar errores en referencias antiguas.
 * La estructura activa usa Horario en lugar de Entrenamiento.
 */
class Entrenamiento extends Model
{
    /**
     * Tabla de respaldo que permite mantener compatibilidad temporal.
     *
     * @var string
     */
    protected $table = 'horarios';
}
