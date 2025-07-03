<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemEquipamientoSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🎨 Creando sistema de equipamiento...');
        
        $items = [
            // SHADERS (Esquemas de Color)
            [
                'nombre' => 'Shader Vanguardia',
                'descripcion' => 'Colores oficiales de la Vanguardia: azul y blanco',
                'tipo_slot' => 'shader',
                'rareza' => 'comun',
                'nivel_poder' => 1,
                'precio_tienda' => 50,
                'color_hex' => '#1E90FF',
                'imagen_url' => '/images/shaders/vanguard.jpg',
                'requisitos' => null,
            ],
            [
                'nombre' => 'Shader Crepúsculo',
                'descripcion' => 'Gradiente púrpura y dorado del atardecer',
                'tipo_slot' => 'shader',
                'rareza' => 'raro',
                'nivel_poder' => 1,
                'precio_tienda' => 150,
                'color_hex' => '#9370DB',
                'imagen_url' => '/images/shaders/twilight.jpg',
                'requisitos' => json_encode(['nivel_min' => 10]),
            ],
            [
                'nombre' => 'Shader Obsidiana',
                'descripcion' => 'Negro profundo con detalles rojos carmesí',
                'tipo_slot' => 'shader',
                'rareza' => 'epico',
                'nivel_poder' => 1,
                'precio_tienda' => 300,
                'color_hex' => '#000000',
                'imagen_url' => '/images/shaders/obsidian.jpg',
                'requisitos' => json_encode(['nivel_min' => 25]),
            ],
            [
                'nombre' => 'Shader Luz Dorada',
                'descripcion' => 'Resplandor dorado de la Luz pura',
                'tipo_slot' => 'shader',
                'rareza' => 'exotico',
                'nivel_poder' => 1,
                'precio_tienda' => 500,
                'color_hex' => '#FFD700',
                'imagen_url' => '/images/shaders/golden_light.jpg',
                'requisitos' => json_encode(['nivel_min' => 50, 'raid_completado' => true]),
            ],
            
            // EMBLEMAS
            [
                'nombre' => 'Primeros Pasos',
                'descripcion' => 'Emblema conmemorativo del primer día',
                'tipo_slot' => 'emblem',
                'rareza' => 'comun',
                'nivel_poder' => 1,
                'precio_tienda' => null, // Gratuito
                'imagen_url' => '/images/emblems/first_steps.jpg',
                'requisitos' => null,
            ],
            [
                'nombre' => 'Maestro del Conocimiento',
                'descripcion' => 'Para aquellos que han dominado su clase',
                'tipo_slot' => 'emblem',
                'rareza' => 'epico',
                'nivel_poder' => 1,
                'precio_tienda' => null,
                'imagen_url' => '/images/emblems/knowledge_master.jpg',
                'requisitos' => json_encode(['nivel_min' => 50, 'notas_perfectas' => 10]),
            ],
            [
                'nombre' => 'Leyenda Inmortal',
                'descripcion' => 'Solo para quienes alcanzaron el nivel 100',
                'tipo_slot' => 'emblem',
                'rareza' => 'legendario',
                'nivel_poder' => 1,
                'precio_tienda' => null,
                'imagen_url' => '/images/emblems/immortal_legend.jpg',
                'requisitos' => json_encode(['nivel_min' => 100]),
            ],
            [
                'nombre' => 'Conquistador de Raids',
                'descripcion' => 'Completó todos los raids disponibles',
                'tipo_slot' => 'emblem',
                'rareza' => 'exotico',
                'nivel_poder' => 1,
                'precio_tienda' => null,
                'imagen_url' => '/images/emblems/raid_conqueror.jpg',
                'requisitos' => json_encode(['raids_completados' => 'todos']),
            ],
            
            // GEAR ESPECÍFICO POR CLASE - TITÁN
            [
                'nombre' => 'Casco del Conquistador',
                'descripcion' => 'Casco ceremonial para Titanes victoriosos',
                'tipo_slot' => 'casco',
                'rareza' => 'epico',
                'nivel_poder' => 10,
                'stats_bonus' => json_encode(['fuerza' => 5, 'inteligencia' => 2]),
                'precio_tienda' => 500,
                'imagen_url' => '/images/gear/titan_helmet_conqueror.jpg',
                'requisitos' => json_encode(['clase_rpg' => 'Titán', 'nivel_min' => 30]),
            ],
            [
                'nombre' => 'Peto del Guardián Supremo',
                'descripcion' => 'Armadura que ha protegido a incontables aliados',
                'tipo_slot' => 'pecho',
                'rareza' => 'exotico',
                'nivel_poder' => 15,
                'stats_bonus' => json_encode(['fuerza' => 8, 'salud_extra' => 20]),
                'efecto_especial' => json_encode(['reduccion_daño_equipo' => 0.1]),
                'precio_tienda' => 800,
                'imagen_url' => '/images/gear/titan_chest_supreme.jpg',
                'requisitos' => json_encode(['clase_rpg' => 'Titán', 'nivel_min' => 60, 'revives_realizados' => 20]),
            ],
            
            // GEAR ESPECÍFICO POR CLASE - CAZADOR
            [
                'nombre' => 'Capa del Cazador Nocturno',
                'descripcion' => 'Capa que ondea con el viento del conocimiento',
                'tipo_slot' => 'clase_item',
                'rareza' => 'raro',
                'nivel_poder' => 8,
                'stats_bonus' => json_encode(['agilidad' => 4, 'inteligencia' => 3]),
                'precio_tienda' => 350,
                'imagen_url' => '/images/gear/hunter_cloak_nightstalker.jpg',
                'requisitos' => json_encode(['clase_rpg' => 'Cazador', 'nivel_min' => 20]),
            ],
            [
                'nombre' => 'Botas del Velocista',
                'descripcion' => 'Permiten movimientos más rápidos y precisos',
                'tipo_slot' => 'botas',
                'rareza' => 'epico',
                'nivel_poder' => 12,
                'stats_bonus' => json_encode(['agilidad' => 6, 'energia_extra' => 2]),
                'efecto_especial' => json_encode(['velocidad_entrega' => 1.2]),
                'precio_tienda' => 600,
                'imagen_url' => '/images/gear/hunter_boots_speedster.jpg',
                'requisitos' => json_encode(['clase_rpg' => 'Cazador', 'nivel_min' => 40, 'primeras_entregas' => 15]),
            ],
            
            // GEAR ESPECÍFICO POR CLASE - HECHICERO
            [
                'nombre' => 'Vínculo del Hechicero Supremo',
                'descripcion' => 'Amuleto que canaliza la Luz del conocimiento',
                'tipo_slot' => 'clase_item',
                'rareza' => 'epico',
                'nivel_poder' => 12,
                'stats_bonus' => json_encode(['inteligencia' => 6, 'luz_extra' => 2]),
                'efecto_especial' => json_encode(['regeneracion_luz_bonus' => 1]),
                'precio_tienda' => 600,
                'imagen_url' => '/images/gear/warlock_bond_supreme.jpg',
                'requisitos' => json_encode(['clase_rpg' => 'Hechicero', 'nivel_min' => 35]),
            ],
            [
                'nombre' => 'Guantes del Sanador',
                'descripcion' => 'Amplifican el poder de sanación',
                'tipo_slot' => 'guantes',
                'rareza' => 'exotico',
                'nivel_poder' => 18,
                'stats_bonus' => json_encode(['inteligencia' => 7, 'luz_extra' => 3]),
                'efecto_especial' => json_encode(['eficiencia_sanacion' => 1.5]),
                'precio_tienda' => 900,
                'imagen_url' => '/images/gear/warlock_gloves_healer.jpg',
                'requisitos' => json_encode(['clase_rpg' => 'Hechicero', 'nivel_min' => 50, 'sanaciones_realizadas' => 50]),
            ]
        ];

        foreach ($items as $item) {
            $item['activo'] = true;
            $item['created_at'] = now();
            $item['updated_at'] = now();
            DB::table('item_equipamiento')->insert($item);
        }
        
        $this->command->info('✅ 16 items de equipamiento creados');
        $this->command->line('   - 4 shaders, 4 emblemas, 8 gear específico por clase');
    }
}