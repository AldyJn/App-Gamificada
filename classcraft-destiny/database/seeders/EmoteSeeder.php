<?php
// ===== database/seeders/EmoteSeeder.php =====
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmoteSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('😄 Creando sistema de emotes...');
        
        $emotes = [
            [
                'nombre' => 'Saludo Guardián',
                'descripcion' => 'Saludo formal entre Guardianes',
                'animacion_css' => 'guardian-salute',
                'icono_url' => '/icons/emotes/salute.svg',
                'nivel_requerido' => 1,
                'precio_tienda' => null, // Gratuito
                'rareza' => 'comun',
            ],
            [
                'nombre' => 'Danza de la Victoria',
                'descripcion' => 'Celebración tras completar una actividad difícil',
                'animacion_css' => 'victory-dance',
                'icono_url' => '/icons/emotes/victory_dance.svg',
                'nivel_requerido' => 10,
                'precio_tienda' => 100,
                'rareza' => 'raro',
            ],
            [
                'nombre' => 'Meditación de la Luz',
                'descripcion' => 'Pose contemplativa de conexión con el conocimiento',
                'animacion_css' => 'light-meditation',
                'icono_url' => '/icons/emotes/meditation.svg',
                'nivel_requerido' => 25,
                'precio_tienda' => 250,
                'rareza' => 'epico',
            ],
            [
                'nombre' => 'Invocación del Vacío',
                'descripcion' => 'Emote místico que muestra dominio de fuerzas arcanas',
                'animacion_css' => 'void-summon',
                'icono_url' => '/icons/emotes/void_summon.svg',
                'nivel_requerido' => 50,
                'precio_tienda' => 500,
                'rareza' => 'exotico',
            ],
            [
                'nombre' => 'Aplauso Entusiasta',
                'descripcion' => 'Para reconocer el buen trabajo de un compañero',
                'animacion_css' => 'enthusiastic-clap',
                'icono_url' => '/icons/emotes/clap.svg',
                'nivel_requerido' => 5,
                'precio_tienda' => 75,
                'rareza' => 'comun',
            ],
            [
                'nombre' => 'Concentración Profunda',
                'descripcion' => 'Muestra enfoque total en una tarea',
                'animacion_css' => 'deep-focus',
                'icono_url' => '/icons/emotes/focus.svg',
                'nivel_requerido' => 15,
                'precio_tienda' => 150,
                'rareza' => 'raro',
            ],
            [
                'nombre' => 'Eureka',
                'descripcion' => 'Momento de inspiración y descubrimiento',
                'animacion_css' => 'eureka-moment',
                'icono_url' => '/icons/emotes/eureka.svg',
                'nivel_requerido' => 30,
                'precio_tienda' => 300,
                'rareza' => 'epico',
            ],
            [
                'nombre' => 'Respeto Guardián',
                'descripcion' => 'Muestra respeto profundo hacia un compañero',
                'animacion_css' => 'guardian-respect',
                'icono_url' => '/icons/emotes/respect.svg',
                'nivel_requerido' => 40,
                'precio_tienda' => 400,
                'rareza' => 'epico',
            ]
        ];

        foreach ($emotes as $emote) {
            $emote['activo'] = true;
            $emote['created_at'] = now();
            $emote['updated_at'] = now();
            DB::table('emote')->insert($emote);
        }
        
        $this->command->info('✅ 8 emotes creados exitosamente');
    }
}