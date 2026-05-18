<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     * Crea la tabla multas según gees.sql.
     */
    public function up(): void
    {
        // Si la tabla ya existe (por ejemplo al importar gees.sql), no la recreamos.
        if (Schema::hasTable('multas')) {
            return;
        }

        Schema::create('multas', function (Blueprint $table) {
            // Identificador de la multa.
            $table->id('id_multa');

            // Equipo sobre el que aplica la normativa de multa.
            $table->foreignId('id_equipo')
                ->nullable()
                ->constrained('equipos', 'id_equipo');

            // Motivo de la sanción.
            $table->string('motivo')->nullable();

            // Importe monetario de la multa.
            $table->double('monto');

            // Indica si la multa ya fue abonada.
            $table->boolean('pagada')->default(false);

            // Fecha de asignación de la multa.
            $table->date('fecha_asignacion')->nullable();

            // Nombre del jugador al que se asocia la multa.
            $table->string('nombre_jugador')->nullable();
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('multas');
    }
};
