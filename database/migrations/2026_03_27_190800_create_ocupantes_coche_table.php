<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     * Crea la tabla ocupantes_coche según gees.sql.
     */
    public function up(): void
    {
        // Si la tabla ya existe (por ejemplo al importar gees.sql), no la recreamos.
        if (Schema::hasTable('ocupantes_coche')) {
            return;
        }

        Schema::create('ocupantes_coche', function (Blueprint $table) {
            // Identificador del registro de ocupante.
            $table->id();

            // Coche al que se asocia el ocupante.
            $table->foreignId('id_coche')
                ->constrained('coches', 'id_coche')
                ->cascadeOnDelete();

            // Nombre del ocupante que viaja en el coche.
            $table->string('nombre_ocupante');
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('ocupantes_coche');
    }
};
