<?php
// ===== database/seeders/TipoComportamientoSeeder.php =====
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoComportamientoSeeder extends Seeder
{
    public function run()
    {
        $comportamientos = [
            // COMPORTAMIENTOS POSITIVOS
            [
                'nombre' => 'Participación Excepcional',
                'descripcion' => 'Participación sobresaliente en clase',
                'puntos_salud' => 10,
                'puntos_energia' => 2,
                'puntos_luz' => 1,
                'tipo' => 'positivo',
                'icono' => '⭐',
                'afecta_equipo' => false,
            ],
            [
                'nombre' => 'Ayuda a Compañero',
                'descripcion' => 'Ayuda significativa a un compañero en dificultades',
                'puntos_salud' => 5,
                'puntos_energia' => 1,
                'puntos_luz' => 2,
                'tipo' => 'positivo',
                'icono' => '🤝',
                'afecta_equipo' => true,
            ],
            [
                'nombre' => 'Liderazgo',
                'descripcion' => 'Demostró liderazgo positivo en actividad grupal',
                'puntos_salud' => 8,
                'puntos_energia' => 3,
                'puntos_luz' => 1,
                'tipo' => 'positivo',
                'icono' => '👑',
                'afecta_equipo' => false,
            ],
            [
                'nombre' => 'Creatividad',
                'descripcion' => 'Solución creativa e innovadora a un problema',
                'puntos_salud' => 6,
                'puntos_energia' => 1,
                'puntos_luz' => 2,
                'tipo' => 'positivo',
                'icono' => '💡',
                'afecta_equipo' => false,
            ],
            [
                'nombre' => 'Perseverancia',
                'descripcion' => 'No se rindió ante un desafío difícil',
                'puntos_salud' => 12,
                'puntos_energia' => 2,
                'puntos_luz' => 1,
                'tipo' => 'positivo',
                'icono' => '💪',
                'afecta_equipo' => false,
            ],
            
            // COMPORTAMIENTOS NEGATIVOS
            [
                'nombre' => 'Interrupción Menor',
                'descripcion' => 'Interrupción leve de la clase',
                'puntos_salud' => -5,
                'puntos_energia' => -1,
                'puntos_luz' => 0,
                'tipo' => 'negativo',
                'icono' => '⚠️',
                'afecta_equipo' => false,
            ],
            [
                'nombre' => 'Tardanza',
                'descripcion' => 'Llegada tarde a clase sin justificación',
                'puntos_salud' => -10,
                'puntos_energia' => -2,
                'puntos_luz' => 0,
                'tipo' => 'negativo',
                'icono' => '⏰',
                'afecta_equipo' => false,
            ],
            [
                'nombre' => 'No Entrega de Tarea',
                'descripcion' => 'No entregó tarea en la fecha establecida',
                'puntos_salud' => -25,
                'puntos_energia' => -3,
                'puntos_luz' => -1,
                'tipo' => 'negativo',
                'icono' => '📝',
                'afecta_equipo' => false,
            ],
            [
                'nombre' => 'Falta de Respeto',
                'descripcion' => 'Comportamiento irrespetuoso hacia compañeros o profesor',
                'puntos_salud' => -30,
                'puntos_energia' => -5,
                'puntos_luz' => -2,
                'tipo' => 'negativo',
                'icono' => '❌',
                'afecta_equipo' => true,
            ],
            [
                'nombre' => 'Abandono de Equipo',
                'descripcion' => 'Abandonó a su equipo durante una actividad grupal',
                'puntos_salud' => -20,
                'puntos_energia' => -4,
                'puntos_luz' => -3,
                'tipo' => 'negativo',
                'icono' => '🏃',
                'afecta_equipo' => true,
            ]
        ];

        foreach ($comportamientos as $comportamiento) {
            $comportamiento['created_at'] = now();
            $comportamiento['updated_at'] = now();
            DB::table('tipo_comportamiento')->insert($comportamiento);
        }
        
        $this->command->info('✅ Tipos de comportamiento creados (10 registros)');
    }
}