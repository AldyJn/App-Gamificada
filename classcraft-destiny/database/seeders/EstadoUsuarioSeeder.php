<?php

// ===== database/seeders/EstadoUsuarioSeeder.php =====
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstadoUsuario;

class EstadoUsuarioSeeder extends Seeder
{
    public function run()
    {
        $estados = [
            [
                'nombre' => 'activo',
                'descripcion' => 'Usuario activo en el sistema',
            ],
            [
                'nombre' => 'inactivo',
                'descripcion' => 'Usuario temporalmente inactivo',
            ],
            [
                'nombre' => 'suspendido',
                'descripcion' => 'Usuario suspendido por comportamiento',
            ],
            [
                'nombre' => 'graduado',
                'descripcion' => 'Estudiante que terminó el curso',
            ],
        ];

        foreach ($estados as $estado) {
            EstadoUsuario::firstOrCreate(
                ['nombre' => $estado['nombre']],
                $estado
            );
        }
        
        $this->command->info('✅ Estados de usuario creados');
    }
}