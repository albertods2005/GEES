<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo Equipo.
     *
     * @var string
     */
    protected $table = 'equipos';

    /**
     * Clave primaria personalizada.
     *
     * @var string
     */
    protected $primaryKey = 'id_equipo';

    /**
     * El esquema legacy de equipos no usa columnas created_at / updated_at.
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
        'nombre_equipo',
        'categoria',
        'deporte',
        'tiene_multas',
        'codigo_grupo_entrenador',
        'codigo_grupo_jugador',
        'codigo_grupo_familiar',
    ];

    /**
     * Relación muchos a muchos con usuarios mediante la tabla pivote usuarios_equipos.
     */
    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'usuarios_equipos', 'id_equipo', 'id_usuario')
            ->withPivot('rol');
    }

    /**
     * Relación uno a muchos con la entidad pivote usuarios_equipos.
     */
    public function usuariosEquipos()
    {
        return $this->hasMany(UsuarioEquipo::class, 'id_equipo');
    }

    /**
     * Relación uno a muchos con horarios del equipo.
     */
    public function horarios()
    {
        return $this->hasMany(Horario::class, 'id_equipo');
    }

    /**
     * Relación uno a muchos con partidos del equipo.
     */
    public function partidos()
    {
        return $this->hasMany(Partido::class, 'id_equipo');
    }

    /**
     * Relación uno a muchos con coches asociados al equipo.
     */
    public function coches()
    {
        return $this->hasMany(Coche::class, 'id_equipo');
    }

    /**
     * Relación uno a muchos con multas del equipo.
     */
    public function multas()
    {
        return $this->hasMany(Multa::class, 'id_equipo');
    }
}
