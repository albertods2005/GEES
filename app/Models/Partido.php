<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partido extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo Partido.
     *
     * @var string
     */
    protected $table = 'partidos';

    /**
     * Clave primaria personalizada.
     *
     * @var string
     */
    protected $primaryKey = 'id_partido';

    /**
     * La clave primaria es autoincremental y numerica.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Tipo de la clave primaria.
     *
     * @var string
     */
    protected $keyType = 'int';

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
        'id_equipo',
        'equipo_rival',
        'fecha',
        'hora_quedada',
        'hora_partido',
        'lugar',
    ];

    /**
     * Fuerza el uso de la clave primaria legacy en el binding de rutas.
     */
    public function getRouteKeyName(): string
    {
        return 'id_partido';
    }

    /**
     * Relación inversa con el equipo que disputa el partido.
     */
    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo', 'id_equipo');
    }

    /**
     * Relación uno a muchos con la lista de convocados del partido.
     */
    public function convocados()
    {
        return $this->hasMany(Convocado::class, 'id_partido', 'id_partido');
    }
}
