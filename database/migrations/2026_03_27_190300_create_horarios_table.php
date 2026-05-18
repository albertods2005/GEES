<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     * Crea la tabla horarios según gees.sql.
     */
    public function up(): void
    {
        // Si la tabla ya existe (por ejemplo al importar gees.sql), no la recreamos.
        if (Schema::hasTable('horarios')) {
            return;
        }

        Schema::create('horarios', function (Blueprint $table) {
            // Identificador principal del horario.
            $table->id();

            // Día del entrenamiento o actividad.
            $table->string('dia')->nullable();

            // Hora de quedada del equipo.
            $table->time('hora_quedada', 6)->nullable();

            // Hora efectiva de inicio del entreno.
            $table->time('hora_entreno', 6)->nullable();

            // Lugar donde se realiza la actividad.
            $table->string('lugar')->nullable();

            // Equipo asociado al horario.
            $table->foreignId('id_equipo')
                ->nullable()
                ->constrained('equipos', 'id_equipo');
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
