<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Ensures the production admin account exists without duplicating demo data.
     */
    public function run(): void
    {
        $adminEmail = mb_strtolower(trim((string) env('GEES_ADMIN_EMAIL', 'admin@gees.local')));
        $adminPassword = (string) env('GEES_ADMIN_PASSWORD', 'admin12345');

        Usuario::updateOrCreate(
            ['correo' => $adminEmail],
            [
                'nombre_usuario' => 'Administrador GEES',
                'contrasena' => Hash::make($adminPassword),
            ]
        );
    }
}
