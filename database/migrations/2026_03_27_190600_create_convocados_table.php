<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     * Crea la tabla convocados según gees.sql.
     */
    public function up(): void
    {
        // Si la tabla ya existe (por ejemplo al importar gees.sql), no la recreamos.
        if (Schema::hasTable('convocados')) {
            return;
        }

        Schema::create('convocados', function (Blueprint $table) {
            // Identificador principal de la convocatoria.
            $table->id();

            // Partido al que corresponde la convocatoria.
            $table->foreignId('id_partido')
                ->constrained('partidos', 'id_partido')
                ->cascadeOnDelete();

            // Nombre del jugador convocado.
            $table->string('nombre_jugador')->nullable();

            // Dorsal del jugador para el partido.
            $table->integer('dorsal');
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('convocados');
    }
};
