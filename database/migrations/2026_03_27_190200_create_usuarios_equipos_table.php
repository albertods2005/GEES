<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     * Crea la tabla pivote usuarios_equipos según gees.sql.
     */
    public function up(): void
    {
        // Si la tabla ya existe (por ejemplo al importar gees.sql), no la recreamos.
        if (Schema::hasTable('usuarios_equipos')) {
            return;
        }

        Schema::create('usuarios_equipos', function (Blueprint $table) {
            // Identificador de la relación usuario-equipo.
            $table->id();

            // Usuario relacionado con el equipo.
            $table->foreignId('id_usuario')
                ->constrained('usuarios', 'id_usuario');

            // Equipo relacionado con el usuario.
            $table->foreignId('id_equipo')
                ->constrained('equipos', 'id_equipo');

            // Rol funcional del usuario dentro del equipo.
            $table->string('rol')->nullable();

            // Índices explícitos para mantener nomenclatura de la base actual.
            $table->index('id_usuario', 'fk_usuario');
            $table->index('id_equipo', 'fk_equipo');
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios_equipos');
    }
};
