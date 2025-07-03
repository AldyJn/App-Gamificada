<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoTareaSeeder extends Seeder
{
    public function run()
    {
        DB::table('estado_tarea')->insert([
            [
                'nombre' => 'pendiente',
                'descripcion' => 'Tarea asignada pero no entregada',
                'color_hex' => '#FFA500',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'entregada',
                'descripcion' => 'Tarea entregada, pendiente de evaluación',
                'color_hex' => '#1E90FF',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'evaluada',
                'descripcion' => 'Tarea evaluada con nota asignada',
                'color_hex' => '#32CD32',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'tarde',
                'descripcion' => 'Tarea entregada fuera de plazo',
                'color_hex' => '#DC143C',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'incompleta',
                'descripcion' => 'Tarea entregada pero incompleta',
                'color_hex' => '#FFD700',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        
        $this->command->info('✅ Estados de tarea creados');
    }
}
