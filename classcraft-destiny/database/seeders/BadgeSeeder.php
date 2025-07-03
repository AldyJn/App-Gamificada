<?php
// ===== database/seeders/BadgeSeeder.php =====
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadgeSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🏅 Creando sistema de badges...');
        
        $badges = [
            // BADGES ACADÉMICOS
            [
                'nombre' => 'Primera Luz',
                'descripcion' => 'Completó su primera actividad',
                'imagen_url' => '/images/badges/first_light.jpg',
                'tipo' => 'academico',
                'criterios' => json_encode(['actividades_completadas' => 1]),
                'rareza' => 'comun',
                'puntos_prestigio' => 10,
                'oculto' => false,
            ],
            [
                'nombre' => 'Erudito',
                'descripcion' => 'Obtuvo la nota máxima en 5 actividades',
                'imagen_url' => '/images/badges/scholar.jpg',
                'tipo' => 'academico',
                'criterios' => json_encode(['notas_perfectas' => 5]),
                'rareza' => 'raro',
                'puntos_prestigio' => 50,
                'oculto' => false,
            ],
            [
                'nombre' => 'Velocista',
                'descripcion' => 'Fue el primero en entregar 10 actividades',
                'imagen_url' => '/images/badges/speedster.jpg',
                'tipo' => 'academico',
                'criterios' => json_encode(['primeras_entregas' => 10]),
                'rareza' => 'epico',
                'puntos_prestigio' => 100,
                'oculto' => false,
            ],
            [
                'nombre' => 'Perfeccionista',
                'descripcion' => 'Mantuvo promedio perfecto durante un mes',
                'imagen_url' => '/images/badges/perfectionist.jpg',
                'tipo' => 'academico',
                'criterios' => json_encode(['promedio_perfecto_dias' => 30]),
                'rareza' => 'epico',
                'puntos_prestigio' => 200,
                'oculto' => false,
            ],
            [
                'nombre' => 'Maestro del Saber',
                'descripcion' => 'Alcanzó el nivel máximo en una materia',
                'imagen_url' => '/images/badges/knowledge_master.jpg',
                'tipo' => 'academico',
                'criterios' => json_encode(['nivel_maximo_materia' => true]),
                'rareza' => 'legendario',
                'puntos_prestigio' => 500,
                'oculto' => false,
            ],
            
            // BADGES SOCIALES
            [
                'nombre' => 'Guardián del Equipo',
                'descripcion' => 'Revivió a 5 compañeros caídos',
                'imagen_url' => '/images/badges/team_guardian.jpg',
                'tipo' => 'social',
                'criterios' => json_encode(['revives_realizados' => 5]),
                'rareza' => 'raro',
                'puntos_prestigio' => 75,
                'oculto' => false,
            ],
            [
                'nombre' => 'Mentor',
                'descripcion' => 'Ayudó a 20 compañeros diferentes',
                'imagen_url' => '/images/badges/mentor.jpg',
                'tipo' => 'social',
                'criterios' => json_encode(['ayudas_realizadas' => 20]),
                'rareza' => 'epico',
                'puntos_prestigio' => 150,
                'oculto' => false,
            ],
            [
                'nombre' => 'Corazón de León',
                'descripcion' => 'Nunca abandonó a un compañero en dificultades',
                'imagen_url' => '/images/badges/lion_heart.jpg',
                'tipo' => 'social',
                'criterios' => json_encode(['abandonos' => 0, 'ayudas_minimas' => 15]),
                'rareza' => 'epico',
                'puntos_prestigio' => 120,
                'oculto' => false,
            ],
            [
                'nombre' => 'Alma del Clan',
                'descripcion' => 'Lideró un clan exitosamente durante un semestre',
                'imagen_url' => '/images/badges/clan_soul.jpg',
                'tipo' => 'social',
                'criterios' => json_encode(['lider_clan_dias' => 180, 'clan_exitoso' => true]),
                'rareza' => 'legendario',
                'puntos_prestigio' => 300,
                'oculto' => false,
            ],
            
            // BADGES DE ASISTENCIA
            [
                'nombre' => 'Nunca Falla',
                'descripcion' => 'Asistencia perfecta durante un mes',
                'imagen_url' => '/images/badges/perfect_attendance.jpg',
                'tipo' => 'asistencia',
                'criterios' => json_encode(['dias_consecutivos_presente' => 30]),
                'rareza' => 'raro',
                'puntos_prestigio' => 80,
                'oculto' => false,
            ],
            [
                'nombre' => 'Puntualidad Absoluta',
                'descripcion' => 'Sin tardanzas durante todo el semestre',
                'imagen_url' => '/images/badges/punctuality.jpg',
                'tipo' => 'asistencia',
                'criterios' => json_encode(['tardanzas' => 0, 'dias_minimos' => 60]),
                'rareza' => 'epico',
                'puntos_prestigio' => 100,
                'oculto' => false,
            ],
            
            // BADGES ESPECIALES
            [
                'nombre' => 'Leyenda Inmortal',
                'descripcion' => 'Alcanzó el nivel 100',
                'imagen_url' => '/images/badges/immortal_legend.jpg',
                'tipo' => 'especial',
                'criterios' => json_encode(['nivel_alcanzado' => 100]),
                'rareza' => 'legendario',
                'puntos_prestigio' => 1000,
                'oculto' => false,
            ],
            [
                'nombre' => 'El Elegido',
                'descripcion' => 'Badge secreto por logro extraordinario',
                'imagen_url' => '/images/badges/chosen_one.jpg',
                'tipo' => 'especial',
                'criterios' => json_encode(['secreto' => true]),
                'rareza' => 'legendario',
                'puntos_prestigio' => 2000,
                'oculto' => true,
            ],
            [
                'nombre' => 'Conquistador de Raids',
                'descripcion' => 'Completó todos los raids disponibles',
                'imagen_url' => '/images/badges/raid_conqueror.jpg',
                'tipo' => 'especial',
                'criterios' => json_encode(['raids_completados' => 'todos']),
                'rareza' => 'legendario',
                'puntos_prestigio' => 500,
                'oculto' => false,
            ]
        ];

        foreach ($badges as $badge) {
            $badge['activo'] = true;
            $badge['created_at'] = now();
            $badge['updated_at'] = now();
            DB::table('badge')->insert($badge);
        }
        
        $this->command->info('✅ 14 badges creados exitosamente');
        $this->command->line('   - 5 académicos, 4 sociales, 2 asistencia, 3 especiales');
    }
}
