<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OcupanteCoche extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo OcupanteCoche.
     *
     * @var string
     */
    protected $table = 'ocupantes_coche';

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
        'id_coche',
        'nombre_ocupante',
    ];

    /**
     * Relación inversa con el coche asociado.
     */
    public function coche()
    {
        return $this->belongsTo(Coche::class, 'id_coche', 'id_coche');
    }
}
