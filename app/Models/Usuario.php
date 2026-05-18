<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * La tabla usuarios del esquema legacy no incluye created_at ni updated_at.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Define el nombre de la tabla asociada al modelo.
     * Se usa "usuarios" para mantener compatibilidad con el esquema GEES.
     *
     * @var string
     */
    protected $table = 'usuarios';

    /**
     * Define la clave primaria personalizada del modelo.
     *
     * @var string
     */
    protected $primaryKey = 'id_usuario';

    /**
     * Atributos asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre_usuario',
        'correo',
        'contrasena',
    ];

    /**
     * Atributos ocultos al serializar el modelo.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    /**
     * Conversiones de tipos de atributos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    /**
     * Indica a Laravel qué columna contiene la contraseña autenticable.
     */
    public function getAuthPassword(): string
    {
        return (string) $this->contrasena;
    }

    /**
     * Relación muchos a muchos con equipos, usando la tabla pivote usuarios_equipos.
     */
    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'usuarios_equipos', 'id_usuario', 'id_equipo')
            ->withPivot('rol');
    }

    /**
     * Relación uno a muchos con la tabla pivote como entidad explícita.
     */
    public function usuariosEquipos()
    {
        return $this->hasMany(UsuarioEquipo::class, 'id_usuario');
    }

    /**
     * Indica si el usuario pertenece a la zona de administracion.
     */
    public function isAdmin(): bool
    {
        $correo = mb_strtolower(trim((string) $this->correo));

        return in_array($correo, config('gees.admin_emails', []), true);
    }
}
