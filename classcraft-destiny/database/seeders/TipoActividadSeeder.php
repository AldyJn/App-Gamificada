<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoActividadSeeder extends Seeder
{
    public function run()
    {
        DB::table('tipo_actividad')->insert([
            [
                'nombre' => 'strike',
                'descripcion' => 'Misión cooperativa para 3 Guardianes',
                'min_participantes' => 3,
                'max_participantes' => 3,
                'duracion_estimada' => 45,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'raid',
                'descripcion' => 'Desafío épico para 6 Guardianes con mecánicas complejas',
                'min_participantes' => 6,
                'max_participantes' => 6,
                'duracion_estimada' => 120,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'crucible',
                'descripcion' => 'Actividad competitiva entre equipos',
                'min_participantes' => 2,
                'max_participantes' => 12,
                'duracion_estimada' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'patrol',
                'descripcion' => 'Exploración individual o en pequeños grupos',
                'min_participantes' => 1,
                'max_participantes' => 3,
                'duracion_estimada' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'nightfall',
                'descripcion' => 'Strike de alta dificultad con modificadores',
                'min_participantes' => 3,
                'max_participantes' => 3,
                'duracion_estimada' => 60,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'gambit',
                'descripcion' => 'Modo híbrido PvEvP',
                'min_participantes' => 8,
                'max_participantes' => 8,
                'duracion_estimada' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
        
        $this->command->info('✅ Tipos de actividad creados');
    }
}