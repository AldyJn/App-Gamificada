<?php
// ===== database/seeders/ClaseRpgSeeder.php =====
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClaseRpgSeeder extends Seeder
{
    public function run()
    {
        DB::table('clase_rpg')->insert([
            [
                'id' => 1,
                'nombre' => 'Titán',
                'descripcion' => 'Guardianes resistentes que protegen a sus aliados',
                'lore_descripcion' => 'Los Titanes son los defensores de la humanidad, construyendo muros donde no los hay y manteniéndose firmes ante la adversidad. Su fuerza no radica solo en su resistencia física, sino en su capacidad de inspirar y proteger a quienes los rodean.',
                'stats_base' => json_encode([
                    'salud' => 100,
                    'energia' => 6,
                    'luz' => 4,
                    'fuerza' => 9,
                    'inteligencia' => 6,
                    'agilidad' => 4
                ]),
                'arbol_habilidades' => json_encode([
                    'defensivas' => ['Escudo Guardián', 'Barrera Protectora', 'Rally the Troops'],
                    'liderazgo' => ['Intimidación', 'Burbuja de Santidad'],
                    'pasivas' => ['Resistencia Natural', 'Aura de Liderazgo']
                ]),
                'energia_maxima' => 100,
                'bonificaciones_pasivas' => json_encode([
                    'reduccion_daño_comportamiento' => 0.5,
                    'bonus_xp_liderazgo' => 0.1,
                    'regeneracion_salud_extra' => 2
                ]),
                'imagen_url' => '/images/classes/titan.png',
                'color_primario' => '#D4AF37', // Dorado
                'color_secundario' => '#8B4513', // Marrón
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'nombre' => 'Cazador',
                'descripcion' => 'Exploradores ágiles especializados en precisión y velocidad',
                'lore_descripcion' => 'Los Cazadores son los ojos y oídos de los Guardianes. Rápidos, precisos y siempre alerta, se especializan en golpear primero y golpear fuerte. Su agilidad mental y física los convierte en los primeros en actuar y los últimos en rendirse.',
                'stats_base' => json_encode([
                    'salud' => 80,
                    'energia' => 10,
                    'luz' => 6,
                    'fuerza' => 6,
                    'inteligencia' => 7,
                    'agilidad' => 9
                ]),
                'arbol_habilidades' => json_encode([
                    'precision' => ['Tiro de Precisión', 'Marked for Death'],
                    'velocidad' => ['Cadena de Eliminaciones', 'Sigilo'],
                    'ultimate' => ['Tormenta de Balas Doradas']
                ]),
                'energia_maxima' => 120,
                'bonificaciones_pasivas' => json_encode([
                    'bonus_primera_participacion' => 2.0,
                    'multiplicador_combo' => 0.25,
                    'probabilidad_critico' => 0.15
                ]),
                'imagen_url' => '/images/classes/hunter.png',
                'color_primario' => '#FF8C00', // Naranja
                'color_secundario' => '#2F4F2F', // Verde oscuro
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'nombre' => 'Hechicero',
                'descripcion' => 'Eruditos místicos que apoyan y potencian a su equipo',
                'lore_descripcion' => 'Los Hechiceros son los estudiosos de los misterios de la Luz. Su conocimiento profundo les permite manipular las fuerzas fundamentales del universo para sanar, proteger y potenciar a sus aliados. Su verdadero poder radica en hacer que otros brillen.',
                'stats_base' => json_encode([
                    'salud' => 60,
                    'energia' => 4,
                    'luz' => 10,
                    'fuerza' => 4,
                    'inteligencia' => 9,
                    'agilidad' => 5
                ]),
                'arbol_habilidades' => json_encode([
                    'sanacion' => ['Sanación Menor', 'Revivir'],
                    'soporte' => ['Aura de Sabiduría', 'Portal de Conocimiento'],
                    'ultimate' => ['Pozo de Radiancia']
                ]),
                'energia_maxima' => 80,
                'bonificaciones_pasivas' => json_encode([
                    'bonus_xp_global' => 0.25,
                    'regeneracion_luz_extra' => 1,
                    'alcance_habilidades_aumento' => 1.5
                ]),
                'imagen_url' => '/images/classes/warlock.png',
                'color_primario' => '#9370DB', // Púrpura
                'color_secundario' => '#4169E1', // Azul real
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
        
        $this->command->info('✅ Clases RPG creadas: Titán, Cazador, Hechicero');
    }
}