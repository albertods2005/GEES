<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convocado extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo Convocado.
     *
     * @var string
     */
    protected $table = 'convocados';

    /**
     * El esquema legacy no usa created_at / updated_at.
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
        'id_partido',
        'nombre_jugador',
        'dorsal',
    ];

    /**
     * Relación inversa con el partido al que pertenece la convocatoria.
     */
    public function partido()
    {
        return $this->belongsTo(Partido::class, 'id_partido', 'id_partido');
    }
}
