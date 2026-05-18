<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     * Crea la tabla partidos según gees.sql.
     */
    public function up(): void
    {
        // Si la tabla ya existe (por ejemplo al importar gees.sql), no la recreamos.
        if (Schema::hasTable('partidos')) {
            return;
        }

        Schema::create('partidos', function (Blueprint $table) {
            // Identificador del partido.
            $table->id('id_partido');

            // Equipo que organiza o disputa el partido.
            $table->foreignId('id_equipo')
                ->nullable()
                ->constrained('equipos', 'id_equipo');

            // Nombre del equipo rival.
            $table->string('equipo_rival')->nullable();

            // Fecha del partido.
            $table->date('fecha')->nullable();

            // Hora de quedada previa.
            $table->time('hora_quedada')->nullable();

            // Hora oficial del partido.
            $table->time('hora_partido')->nullable();

            // Lugar del encuentro.
            $table->string('lugar')->nullable();
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('partidos');
    }
};
