<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioEquipo extends Model
{
    use HasFactory;

    /**
     * Tabla pivote de relación entre usuarios y equipos.
     *
     * @var string
     */
    protected $table = 'usuarios_equipos';

    /**
     * En este esquema no existen marcas de tiempo en la tabla pivote.
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
        'id_usuario',
        'id_equipo',
        'rol',
    ];

    /**
     * Relación inversa hacia el usuario de la vinculación.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Relación inversa hacia el equipo de la vinculación.
     */
    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo', 'id_equipo');
    }
}
