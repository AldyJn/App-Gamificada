<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoUsuarioSeeder extends Seeder
{
    public function run()
    {
        // Limpiar tabla primero
        DB::table('tipo_usuario')->delete();
        
        DB::table('tipo_usuario')->insert([
            [
                'id' => 1,
                'nombre' => 'docente', // ✅ CAMBIADO DE 'profesor' A 'docente'
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
        
        $this->command->info('✅ Tipos de usuario creados correctamente');
    }
}