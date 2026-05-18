<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     * Renombra la tabla base de Laravel de users a usuarios y adapta columnas al esquema de gees.sql.
     */
    public function up(): void
    {
        if (Schema::hasTable('users') && ! Schema::hasTable('usuarios')) {
            Schema::rename('users', 'usuarios');
        }

        Schema::table('usuarios', function (Blueprint $table) {
            // Renombra el identificador principal para usar nomenclatura del dominio GEES.
            if (Schema::hasColumn('usuarios', 'id') && ! Schema::hasColumn('usuarios', 'id_usuario')) {
                $table->renameColumn('id', 'id_usuario');
            }

            // Renombra campos base de autenticación para mantener consistencia con la base legacy.
            if (Schema::hasColumn('usuarios', 'name') && ! Schema::hasColumn('usuarios', 'nombre_usuario')) {
                $table->renameColumn('name', 'nombre_usuario');
            }

            if (Schema::hasColumn('usuarios', 'email') && ! Schema::hasColumn('usuarios', 'correo')) {
                $table->renameColumn('email', 'correo');
            }

            if (Schema::hasColumn('usuarios', 'password') && ! Schema::hasColumn('usuarios', 'contrasena')) {
                $table->renameColumn('password', 'contrasena');
            }
        });
    }

    /**
     * Revierte la migración.
     * Devuelve los nombres estándar de Laravel en rollback.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            if (Schema::hasColumn('usuarios', 'contrasena') && ! Schema::hasColumn('usuarios', 'password')) {
                $table->renameColumn('contrasena', 'password');
            }

            if (Schema::hasColumn('usuarios', 'correo') && ! Schema::hasColumn('usuarios', 'email')) {
                $table->renameColumn('correo', 'email');
            }

            if (Schema::hasColumn('usuarios', 'nombre_usuario') && ! Schema::hasColumn('usuarios', 'name')) {
                $table->renameColumn('nombre_usuario', 'name');
            }

            if (Schema::hasColumn('usuarios', 'id_usuario') && ! Schema::hasColumn('usuarios', 'id')) {
                $table->renameColumn('id_usuario', 'id');
            }
        });

        if (Schema::hasTable('usuarios') && ! Schema::hasTable('users')) {
            Schema::rename('usuarios', 'users');
        }
    }
};
