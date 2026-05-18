<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     * Crea la tabla coches según gees.sql.
     */
    public function up(): void
    {
        // Si la tabla ya existe (por ejemplo al importar gees.sql), no la recreamos.
        if (Schema::hasTable('coches')) {
            return;
        }

        Schema::create('coches', function (Blueprint $table) {
            // Identificador del coche.
            $table->id('id_coche');

            // Nombre del conductor principal del coche.
            $table->string('nombre_conductor');

            // Número total de plazas disponibles.
            $table->integer('numero_plazas');

            // Equipo al que está vinculado el coche.
            $table->foreignId('id_equipo')
                ->constrained('equipos', 'id_equipo')
                ->cascadeOnDelete();

            // Índice explícito para mantener nombre del esquema legacy.
            $table->index('id_equipo', 'fk_coches_equipos');
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('coches');
    }
};
