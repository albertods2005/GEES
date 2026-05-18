<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     * Crea la tabla equipos según estructura de gees.sql.
     */
    public function up(): void
    {
        // Si la tabla ya existe (por ejemplo al importar gees.sql), no la recreamos.
        if (Schema::hasTable('equipos')) {
            return;
        }

        Schema::create('equipos', function (Blueprint $table) {
            // Clave primaria con nombre explícito para mantener compatibilidad legacy.
            $table->id('id_equipo');

            // Nombre del equipo deportivo.
            $table->string('nombre_equipo')->nullable();

            // Categoría deportiva del equipo.
            $table->string('categoria')->nullable();

            // Tipo de deporte del equipo.
            $table->string('deporte')->nullable();

            // Indica si el equipo gestiona multas internas.
            $table->boolean('tiene_multas')->default(false);

            // Código de grupo para entrenadores en comunicaciones.
            $table->string('codigo_grupo_entrenador')->nullable();

            // Código de grupo para jugadores en comunicaciones.
            $table->string('codigo_grupo_jugador')->nullable();

            // Código de grupo para familiares en comunicaciones.
            $table->string('codigo_grupo_familiar')->nullable();
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};
