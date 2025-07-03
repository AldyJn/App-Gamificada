<?php
// ===== database/seeders/TituloRangoSeeder.php =====
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TituloRangoSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('👑 Creando títulos especiales...');
        
        $titulos = [
            [
                'nombre' => 'Conquistador',
                'descripcion' => 'Completó todos los raids del semestre',
                'requisitos' => json_encode(['raids_completados' => 'todos_del_semestre']),
                'color_titulo' => '#FFD700',
                'efecto_visual' => 'dorado',
                'icono_url' => '/icons/titles/conqueror.svg',
                'rareza' => 'epico',
            ],
            [
                'nombre' => 'Infalible',
                'descripcion' => 'Mantuvo el primer lugar durante 4 semanas consecutivas',
                'requisitos' => json_encode(['posicion_max' => 1, 'semanas_consecutivas' => 4]),
                'color_titulo' => '#FF4500',
                'efecto_visual' => 'brillante',
                'icono_url' => '/icons/titles/flawless.svg',
                'rareza' => 'legendario',
            ],
            [
                'nombre' => 'Protector',
                'descripcion' => 'Salvó a más de 15 compañeros de la "muerte académica"',
                'requisitos' => json_encode(['revives_realizados' => 15]),
                'color_titulo' => '#4169E1',
                'efecto_visual' => 'animado',
                'icono_url' => '/icons/titles/savior.svg',
                'rareza' => 'raro',
            ],
            [
                'nombre' => 'Velocista',
                'descripcion' => 'Record de entregas tempranas en la clase',
                'requisitos' => json_encode(['record_velocidad' => true]),
                'color_titulo' => '#00FF00',
                'efecto_visual' => 'normal',
                'icono_url' => '/icons/titles/speedrunner.svg',
                'rareza' => 'raro',
            ],
            [
                'nombre' => 'Ascendente',
                'descripcion' => 'Completó el prestige una vez',
                'requisitos' => json_encode(['prestige_completado' => 1]),
                'color_titulo' => '#9400D3',
                'efecto_visual' => 'animado',
                'icono_url' => '/icons/titles/ascendant.svg',
                'rareza' => 'legendario',
            ],
            [
                'nombre' => 'Señor del Hierro',
                'descripcion' => 'Ganó 3 eventos Iron Banner consecutivos',
                'requisitos' => json_encode(['iron_banner_wins' => 3, 'consecutivos' => true]),
                'color_titulo' => '#8B4513',
                'efecto_visual' => 'brillante',
                'icono_url' => '/icons/titles/iron_lord.svg',
                'rareza' => 'legendario',
            ],
            [
                'nombre' => 'Guardián Supremo',
                'descripcion' => 'Alcanzó el máximo nivel en todas las estadísticas',
                'requisitos' => json_encode(['stats_maximas' => true]),
                'color_titulo' => '#FFFFFF',
                'efecto_visual' => 'animado',
                'icono_url' => '/icons/titles/supreme_guardian.svg',
                'rareza' => 'legendario',
            ],
            [
                'nombre' => 'Mente Brillante',
                'descripcion' => 'Resolvió 100 problemas complejos sin ayuda',
                'requisitos' => json_encode(['problemas_complejos_resueltos' => 100]),
                'color_titulo' => '#00BFFF',
                'efecto_visual' => 'brillante',
                'icono_url' => '/icons/titles/brilliant_mind.svg',
                'rareza' => 'epico',
            ]
        ];

        foreach ($titulos as $titulo) {
            $titulo['activo'] = true;
            $titulo['created_at'] = now();
            $titulo['updated_at'] = now();
            DB::table('titulo_rango')->insert($titulo);
        }
        
        $this->command->info('✅ 8 títulos especiales creados');
    }
}