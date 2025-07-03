<?php
// ===== database/seeders/PoderHabilidadSeeder.php =====
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoderHabilidadSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🔄 Creando poderes para las 3 clases...');
        
        $poderesTitan = [
            [
                'id_clase_rpg' => 1,
                'nombre' => 'Escudo Guardián',
                'descripcion' => 'Absorbe completamente el próximo daño dirigido hacia un compañero de equipo',
                'nivel_requerido' => 1,
                'costo_recursos' => json_encode(['energia' => 3, 'luz' => 0]),
                'tiempo_cooldown' => 3600, // 1 hora
                'efecto_mecanico' => json_encode([
                    'tipo' => 'absorber_daño',
                    'objetivo' => 'aliado',
                    'valor' => 100,
                    'duracion' => 300
                ]),
                'tipo_poder' => 'activo',
                'categoria' => 'defensivo',
                'icono_url' => '/icons/powers/titan_shield.svg',
                'animacion_css' => 'titan-shield-cast',
                'sonido_url' => '/sounds/titan_shield.mp3',
                'rareza' => 'comun',
                'orden' => 1,
            ],
            [
                'id_clase_rpg' => 1,
                'nombre' => 'Barrera Protectora',
                'descripcion' => 'Crea una zona donde el equipo no puede recibir daño por comportamiento durante 20 minutos',
                'nivel_requerido' => 10,
                'costo_recursos' => json_encode(['energia' => 2, 'luz' => 2]),
                'tiempo_cooldown' => 7200, // 2 horas
                'efecto_mecanico' => json_encode([
                    'tipo' => 'inmunidad_temporal',
                    'objetivo' => 'equipo',
                    'duracion' => 1200,
                    'radio' => 5
                ]),
                'tipo_poder' => 'activo',
                'categoria' => 'defensivo',
                'rareza' => 'raro',
                'orden' => 2,
            ],
            [
                'id_clase_rpg' => 1,
                'nombre' => 'Rally the Troops',
                'descripcion' => 'Restaura 15 puntos de salud a todos los miembros del equipo cercanos',
                'nivel_requerido' => 20,
                'costo_recursos' => json_encode(['energia' => 4, 'luz' => 1]),
                'tiempo_cooldown' => 1800, // 30 minutos
                'efecto_mecanico' => json_encode([
                    'tipo' => 'sanacion_grupal',
                    'objetivo' => 'equipo',
                    'valor' => 15,
                    'radio' => 3
                ]),
                'tipo_poder' => 'activo',
                'categoria' => 'soporte',
                'rareza' => 'raro',
                'orden' => 3,
            ],
            [
                'id_clase_rpg' => 1,
                'nombre' => 'Intimidación',
                'descripcion' => 'Cancela una interrupción de clase y restaura el orden',
                'nivel_requerido' => 30,
                'costo_recursos' => json_encode(['energia' => 1, 'luz' => 0]),
                'tiempo_cooldown' => 5400, // 1.5 horas
                'efecto_mecanico' => json_encode([
                    'tipo' => 'cancelar_interrupcion',
                    'objetivo' => 'clase',
                    'restaurar_orden' => true
                ]),
                'tipo_poder' => 'activo',
                'categoria' => 'defensivo',
                'rareza' => 'raro',
                'orden' => 4,
            ],
            [
                'id_clase_rpg' => 1,
                'nombre' => 'Burbuja de Santidad',
                'descripcion' => '[SÚPER] Crea una zona de inmunidad total durante una actividad completa',
                'nivel_requerido' => 50,
                'costo_recursos' => json_encode(['energia' => 0, 'luz' => 8]),
                'tiempo_cooldown' => 86400, // 24 horas
                'efecto_mecanico' => json_encode([
                    'tipo' => 'inmunidad_total',
                    'objetivo' => 'equipo',
                    'duracion' => 3600,
                    'radio' => 10
                ]),
                'tipo_poder' => 'super',
                'categoria' => 'defensivo',
                'rareza' => 'exotico',
                'orden' => 5,
            ]
        ];

        $poderesCazador = [
            [
                'id_clase_rpg' => 2,
                'nombre' => 'Tiro de Precisión',
                'descripcion' => 'Garantiza que la próxima respuesta sea perfecta, otorgando triple experiencia',
                'nivel_requerido' => 1,
                'costo_recursos' => json_encode(['energia' => 2, 'luz' => 0]),
                'tiempo_cooldown' => 1800, // 30 minutos
                'efecto_mecanico' => json_encode([
                    'tipo' => 'garantizar_exito',
                    'objetivo' => 'self',
                    'multiplicador_xp' => 3.0,
                    'duracion' => 600
                ]),
                'tipo_poder' => 'activo',
                'categoria' => 'ofensivo',
                'rareza' => 'comun',
                'orden' => 1,
            ],
            [
                'id_clase_rpg' => 2,
                'nombre' => 'Cadena de Eliminaciones',
                'descripcion' => 'Cada respuesta correcta consecutiva otorga +1 energía (máximo 5)',
                'nivel_requerido' => 10,
                'costo_recursos' => json_encode(['energia' => 3, 'luz' => 0]),
                'tiempo_cooldown' => 3600, // 1 hora
                'efecto_mecanico' => json_encode([
                    'tipo' => 'combo_multiplicador',
                    'objetivo' => 'self',
                    'energia_por_combo' => 1,
                    'max_combo' => 5,
                    'duracion' => 1800
                ]),
                'tipo_poder' => 'activo',
                'categoria' => 'ofensivo',
                'rareza' => 'raro',
                'orden' => 2,
            ],
            [
                'id_clase_rpg' => 2,
                'nombre' => 'Sigilo',
                'descripcion' => 'Evita completamente la próxima penalización menor (tardanza, tarea menor)',
                'nivel_requerido' => 15,
                'costo_recursos' => json_encode(['energia' => 1, 'luz' => 1]),
                'tiempo_cooldown' => 7200, // 2 horas
                'efecto_mecanico' => json_encode([
                    'tipo' => 'evitar_penalizacion',
                    'objetivo' => 'self',
                    'tipo_penalizacion' => 'menor',
                    'duracion' => 3600
                ]),
                'tipo_poder' => 'activo',
                'categoria' => 'defensivo',
                'rareza' => 'raro',
                'orden' => 3,
            ],
            [
                'id_clase_rpg' => 2,
                'nombre' => 'Marked for Death',
                'descripcion' => 'Marca una pregunta difícil; si la responde correctamente obtiene XP masivo',
                'nivel_requerido' => 25,
                'costo_recursos' => json_encode(['energia' => 4, 'luz' => 1]),
                'tiempo_cooldown' => 5400, // 1.5 horas
                'efecto_mecanico' => json_encode([
                    'tipo' => 'marcar_objetivo',
                    'objetivo' => 'pregunta_dificil',
                    'multiplicador_xp' => 5.0,
                    'duracion' => 1800
                ]),
                'tipo_poder' => 'activo',
                'categoria' => 'ofensivo',
                'rareza' => 'epico',
                'orden' => 4,
            ],
            [
                'id_clase_rpg' => 2,
                'nombre' => 'Tormenta de Balas Doradas',
                'descripcion' => '[SÚPER] Las próximas 3 respuestas son automáticamente perfectas',
                'nivel_requerido' => 50,
                'costo_recursos' => json_encode(['energia' => 0, 'luz' => 10]),
                'tiempo_cooldown' => 86400, // 24 horas
                'efecto_mecanico' => json_encode([
                    'tipo' => 'respuestas_perfectas',
                    'objetivo' => 'self',
                    'cantidad' => 3,
                    'multiplicador_xp' => 4.0,
                    'duracion' => 7200
                ]),
                'tipo_poder' => 'super',
                'categoria' => 'ofensivo',
                'rareza' => 'exotico',
                'orden' => 5,
            ]
        ];

        $poderesHechicero = [
            [
                'id_clase_rpg' => 3,
                'nombre' => 'Sanación Menor',
                'descripcion' => 'Restaura 30 puntos de salud a un compañero seleccionado',
                'nivel_requerido' => 1,
                'costo_recursos' => json_encode(['energia' => 0, 'luz' => 2]),
                'tiempo_cooldown' => 1800, // 30 minutos
                'efecto_mecanico' => json_encode([
                    'tipo' => 'sanacion_individual',
                    'objetivo' => 'aliado',
                    'valor' => 30
                ]),
                'tipo_poder' => 'activo',
                'categoria' => 'soporte',
                'rareza' => 'comun',
                'orden' => 1,
            ],
            [
                'id_clase_rpg' => 3,
                'nombre' => 'Aura de Sabiduría',
                'descripcion' => 'Otorga +25% experiencia a todo el equipo durante 1 hora',
                'nivel_requerido' => 10,
                'costo_recursos' => json_encode(['energia' => 1, 'luz' => 3]),
                'tiempo_cooldown' => 7200, // 2 horas
                'efecto_mecanico' => json_encode([
                    'tipo' => 'buff_xp',
                    'objetivo' => 'equipo',
                    'multiplicador' => 1.25,
                    'duracion' => 3600,
                    'radio' => 5
                ]),
                'tipo_poder' => 'activo',
                'categoria' => 'soporte',
                'rareza' => 'raro',
                'orden' => 2,
            ],
            [
                'id_clase_rpg' => 3,
                'nombre' => 'Revivir',
                'descripcion' => 'Restaura un compañero "caído" (0 salud) a 50% de su salud máxima',
                'nivel_requerido' => 20,
                'costo_recursos' => json_encode(['energia' => 2, 'luz' => 4]),
                'tiempo_cooldown' => 10800, // 3 horas
                'efecto_mecanico' => json_encode([
                    'tipo' => 'revivir',
                    'objetivo' => 'aliado_caido',
                    'porcentaje_salud' => 0.5
                ]),
                'tipo_poder' => 'activo',
                'categoria' => 'soporte',
                'rareza' => 'epico',
                'orden' => 3,
            ],
            [
                'id_clase_rpg' => 3,
                'nombre' => 'Portal de Conocimiento',
                'descripcion' => 'Proporciona acceso a una pista adicional en la próxima actividad',
                'nivel_requerido' => 25,
                'costo_recursos' => json_encode(['energia' => 1, 'luz' => 2]),
                'tiempo_cooldown' => 5400, // 1.5 horas
                'efecto_mecanico' => json_encode([
                    'tipo' => 'pista_extra',
                    'objetivo' => 'self',
                    'duracion' => 86400
                ]),
                'tipo_poder' => 'activo',
                'categoria' => 'soporte',
                'rareza' => 'raro',
                'orden' => 4,
            ],
            [
                'id_clase_rpg' => 3,
                'nombre' => 'Pozo de Radiancia',
                'descripcion' => '[SÚPER] Crea una zona donde el equipo no puede "morir" y obtiene doble experiencia',
                'nivel_requerido' => 50,
                'costo_recursos' => json_encode(['energia' => 0, 'luz' => 10]),
                'tiempo_cooldown' => 86400, // 24 horas
                'efecto_mecanico' => json_encode([
                    'tipo' => 'zona_suprema',
                    'objetivo' => 'equipo',
                    'inmunidad_muerte' => true,
                    'multiplicador_xp' => 2.0,
                    'duracion' => 1800,
                    'radio' => 8
                ]),
                'tipo_poder' => 'super',
                'categoria' => 'soporte',
                'rareza' => 'exotico',
                'orden' => 5,
            ]
        ];

        // Insertar todos los poderes
        $todosPoderes = array_merge($poderesTitan, $poderesCazador, $poderesHechicero);
        
        foreach ($todosPoderes as $poder) {
            $poder['activo'] = true;
            $poder['created_at'] = now();
            $poder['updated_at'] = now();
            DB::table('poder_habilidad')->insert($poder);
        }
        
        $this->command->info('✅ 15 poderes creados exitosamente');
        $this->command->line('   - 5 poderes para Titán (defensivos/liderazgo)');
        $this->command->line('   - 5 poderes para Cazador (ofensivos/agilidad)');
        $this->command->line('   - 5 poderes para Hechicero (soporte/sanación)');
    }
}