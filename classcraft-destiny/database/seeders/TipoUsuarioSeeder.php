<?php
// ===== database/seeders/TipoUsuarioSeeder.php =====
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoUsuarioSeeder extends Seeder
{
    public function run()
    {
        DB::table('tipo_usuario')->insert([
            [
                'id' => 1,
                'nombre' => 'profesor',
                'descripcion' => 'Docente con acceso completo a gestión de clases',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nombre' => 'estudiante',
                'descripcion' => 'Estudiante con acceso a actividades y personalización',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        
        $this->command->info('✅ Tipos de usuario creados');
    }
}

// ===== database/seeders/EstadoUsuarioSeeder.php =====
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoUsuarioSeeder extends Seeder
{
    public function run()
    {
        DB::table('estado_usuario')->insert([
            [
                'id' => 1,
                'nombre' => 'activo',
                'descripcion' => 'Usuario activo en el sistema',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nombre' => 'inactivo',
                'descripcion' => 'Usuario temporalmente inactivo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nombre' => 'suspendido',
                'descripcion' => 'Usuario suspendido por mal comportamiento',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'nombre' => 'graduado',
                'descripcion' => 'Estudiante que ya terminó el curso',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        
        $this->command->info('✅ Estados de usuario creados');
    }
}