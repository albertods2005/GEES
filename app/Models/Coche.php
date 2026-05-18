<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coche extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo Coche.
     *
     * @var string
     */
    protected $table = 'coches';

    /**
     * Clave primaria personalizada.
     *
     * @var string
     */
    protected $primaryKey = 'id_coche';

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
        'nombre_conductor',
        'numero_plazas',
        'id_equipo',
    ];

    /**
     * Relación inversa con el equipo propietario del coche.
     */
    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo', 'id_equipo');
    }

    /**
     * Relación uno a muchos con ocupantes asociados al coche.
     */
    public function ocupantes()
    {
        return $this->hasMany(OcupanteCoche::class, 'id_coche', 'id_coche');
    }
}
