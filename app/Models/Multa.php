<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Multa extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo Multa.
     *
     * @var string
     */
    protected $table = 'multas';

    /**
     * Clave primaria personalizada.
     *
     * @var string
     */
    protected $primaryKey = 'id_multa';

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
        'motivo',
        'monto',
        'pagada',
        'fecha_asignacion',
        'nombre_jugador',
    ];

    /**
     * Conversiones de tipos para facilitar uso en lógica de aplicación.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'pagada' => 'boolean',
            'fecha_asignacion' => 'date',
            'monto' => 'float',
        ];
    }

    /**
     * Relación inversa con el equipo al que pertenece la multa.
     */
    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo', 'id_equipo');
    }
}
