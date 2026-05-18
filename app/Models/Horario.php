<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo Horario.
     *
     * @var string
     */
    protected $table = 'horarios';

    /**
     * El esquema legacy no usa columnas created_at / updated_at.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Atributos asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'dia',
        'hora_quedada',
        'hora_entreno',
        'lugar',
        'id_equipo',
    ];

    /**
     * Relación inversa con el equipo dueño del horario.
     */
    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo', 'id_equipo');
    }
}
