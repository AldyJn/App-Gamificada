<?php

// ===== database/seeders/UsuarioTestSeeder.php =====
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuarioTestSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('👥 Creando usuarios de prueba completos...');
        
        // CREAR PROFESOR DE PRUEBA
        $profesorId = DB::table('usuario')->insertGetId([
            'nombre' => 'Carlos',
            'apellido' => 'Mendoza',
            'correo' => 'profesor@test.com',
            'avatar' => '/images/avatars/teacher_default.jpg',
            'contraseña_hash' => Hash::make('password123'),
            'salt' => Str::random(32),
            'id_tipo_usuario' => 1, // profesor
            'id_estado' => 1, // activo
            'ultimo_acceso' => now()->subHours(2),
            'timezone' => 'America/Lima',
            'notificaciones_push' => true,
            'configuracion_ui' => json_encode([
                'tema' => 'destiny_dark',
                'animaciones' => true,
                'sonidos' => true,
                'idioma' => 'es',
                'mostrar_tutoriales' => false
            ]),
            'estadisticas_globales' => json_encode([
                'clases_creadas' => 1,
                'estudiantes_gestionados' => 6,
                'actividades_creadas' => 5,
                'tiempo_total_plataforma' => 7200 // 2 horas
            ]),
            'created_at' => now()->subDays(45),
            'updated_at' => now(),
        ]);

        $docenteId = DB::table('docente')->insertGetId([
            'id_usuario' => $profesorId,
            'especialidad' => 'Matemáticas y Ciencias',
            'created_at' => now()->subDays(45),
            'updated_at' => now(),
        ]);

        $this->command->info('✅ Profesor creado: Carlos Mendoza');

        // CREAR ESTUDIANTES DE PRUEBA CON VARIEDAD
        $estudiantes = [
            ['nombre' => 'Ana', 'apellido' => 'García', 'codigo' => 'EST001', 'clase_rpg' => 1, 'nivel' => 18, 'perfil' => 'líder'],
            ['nombre' => 'Luis', 'apellido' => 'Rodríguez', 'codigo' => 'EST002', 'clase_rpg' => 2, 'nivel' => 25, 'perfil' => 'velocista'],
            ['nombre' => 'María', 'apellido' => 'López', 'codigo' => 'EST003', 'clase_rpg' => 3, 'nivel' => 22, 'perfil' => 'colaboradora'],
            ['nombre' => 'Diego', 'apellido' => 'Martín', 'codigo' => 'EST004', 'clase_rpg' => 1, 'nivel' => 15, 'perfil' => 'constante'],
            ['nombre' => 'Sofia', 'apellido' => 'Herrera', 'codigo' => 'EST005', 'clase_rpg' => 2, 'nivel' => 28, 'perfil' => 'competitiva'],
            ['nombre' => 'Gabriel', 'apellido' => 'Torres', 'codigo' => 'EST006', 'clase_rpg' => 3, 'nivel' => 20, 'perfil' => 'mentor'],
        ];

        $estudiantesIds = [];
        foreach ($estudiantes as $estudiante) {
            $usuarioId = DB::table('usuario')->insertGetId([
                'nombre' => $estudiante['nombre'],
                'apellido' => $estudiante['apellido'],
                'correo' => strtolower($estudiante['nombre']) . '@estudiante.test.com',
                'avatar' => '/images/avatars/' . strtolower($estudiante['nombre']) . '_avatar.jpg',
                'contraseña_hash' => Hash::make('password123'),
                'salt' => Str::random(32),
                'id_tipo_usuario' => 2, // estudiante
                'id_estado' => 1, // activo
                'ultimo_acceso' => now()->subHours(rand(1, 24)),
                'timezone' => 'America/Lima',
                'notificaciones_push' => true,
                'configuracion_ui' => json_encode([
                    'tema' => 'destiny_dark',
                    'animaciones' => true,
                    'sonidos' => true,
                    'idioma' => 'es',
                    'mostrar_tutoriales' => $estudiante['nivel'] < 20
                ]),
                'estadisticas_globales' => json_encode([
                    'personajes_creados' => 1,
                    'total_xp_ganada' => rand(5000, 25000),
                    'actividades_completadas' => rand(5, 20),
                    'tiempo_total_plataforma' => rand(3600, 14400)
                ]),
                'created_at' => now()->subDays(rand(30, 40)),
                'updated_at' => now(),
            ]);

            $estudianteId = DB::table('estudiante')->insertGetId([
                'id_usuario' => $usuarioId,
                'codigo_estudiante' => $estudiante['codigo'],
                'grado' => '5to',
                'seccion' => 'A',
                'created_at' => now()->subDays(rand(30, 40)),
                'updated_at' => now(),
            ]);

            $estudiantesIds[] = [
                'id' => $estudianteId,
                'clase_rpg' => $estudiante['clase_rpg'],
                'nombre' => $estudiante['nombre'],
                'apellido' => $estudiante['apellido'],
                'nivel' => $estudiante['nivel'],
                'perfil' => $estudiante['perfil']
            ];
        }

        $this->command->info('✅ 6 estudiantes creados con perfiles únicos');

        // CREAR CLASE DE PRUEBA COMPLETA
        $claseId = DB::table('clase')->insertGetId([
            'nombre' => 'Matemáticas 5to A - 2025',
            'descripcion' => 'Clase de matemáticas gamificada con mecánicas Destiny para el año académico 2025. Los estudiantes explorarán conceptos matemáticos como verdaderos Guardianes del Conocimiento, enfrentando desafíos que pondrán a prueba tanto su intelecto como su capacidad de trabajo en equipo.',
            'id_docente' => $docenteId,
            'grado' => '5to',
            'seccion' => 'A',
            'año_academico' => 2025,
            'activo' => true,
            'codigo_invitacion' => 'MATH5A25',
            'qr_url' => '/qr/MATH5A25.png',
            'fecha_inicio' => now()->startOfMonth(),
            'fecha_fin' => now()->addMonths(6),
            'tema_visual' => 'destiny_default',
            'modo_competitivo' => true,
            'configuracion_gamificacion' => json_encode([
                'multiplicador_xp_base' => 1.0,
                'habilitar_clanes' => true,
                'eventos_especiales' => true,
                'sistema_prestigio' => true,
                'dificultad_adaptativa' => true,
                'permitir_pvp' => true
            ]),
            'horarios_clase' => json_encode([
                'lunes' => '08:00-09:30',
                'miercoles' => '10:00-11:30',
                'viernes' => '14:00-15:30'
            ]),
            'created_at' => now()->subDays(35),
            'updated_at' => now(),
        ]);

        // INSCRIBIR ESTUDIANTES EN LA CLASE
        foreach ($estudiantesIds as $estudiante) {
            DB::table('inscripcion_clase')->insert([
                'id_clase' => $claseId,
                'id_estudiante' => $estudiante['id'],
                'fecha_ingreso' => now()->subDays(rand(25, 35)),
                'activo' => true,
                'created_at' => now()->subDays(rand(25, 35)),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ Clase creada: Matemáticas 5to A - 2025 (Código: MATH5A25)');

        // CREAR PERSONAJES DETALLADOS
        $personajesCreados = [];
        foreach ($estudiantesIds as $estudiante) {
            $nivel = $estudiante['nivel'];
            $experiencia = DB::table('nivel_experiencia')->where('nivel', $nivel)->value('experiencia_requerida');
            $experiencia += rand(100, 800); // Progreso hacia siguiente nivel

            $statsBase = DB::table('clase_rpg')->where('id', $estudiante['clase_rpg'])->value('stats_base');
            $statsBase = json_decode($statsBase, true);
            $claseNombre = DB::table('clase_rpg')->where('id', $estudiante['clase_rpg'])->value('nombre');

            // Recursos actuales basados en perfil
            $saludMultiplier = match($estudiante['perfil']) {
                'líder' => 0.9, // Toma riesgos
                'constante' => 1.0, // Siempre estable
                'mentor' => 0.8, // Se sacrifica por otros
                default => 0.85
            };
            
            $saludActual = max(10, round($statsBase['salud'] * $saludMultiplier));
            $energiaActual = max(1, $statsBase['energia'] - rand(0, 2));
            $luzActual = max(1, $statsBase['luz'] - rand(0, 1));

            $personajeId = DB::table('personaje')->insertGetId([
                'id_estudiante' => $estudiante['id'],
                'id_clase' => $claseId,
                'id_clase_rpg' => $estudiante['clase_rpg'],
                'nombre' => $estudiante['nombre'] . ' el Guardián',
                'nivel' => $nivel,
                'experiencia' => $experiencia,
                'power_level' => 750 + ($nivel * 8) + rand(-15, 25),
                
                // Recursos actuales
                'salud_actual' => $saludActual,
                'salud_maxima' => $statsBase['salud'],
                'energia_actual' => $energiaActual,
                'energia_maxima' => $statsBase['energia'],
                'luz_actual' => $luzActual,
                'luz_maxima' => $statsBase['luz'],
                
                'avatar_base' => 'guardian_' . strtolower($claseNombre),
                'imagen_personalizada' => '/images/avatars/' . strtolower($estudiante['nombre']) . '_guardian.jpg',
                'gear_equipado' => json_encode([
                    'shader' => $nivel > 20 ? 'shader_crepusculo' : 'shader_vanguardia',
                    'emblem' => $nivel > 30 ? 'maestro_conocimiento' : 'primeros_pasos'
                ]),
                'poderes_desbloqueados' => json_encode(range(1, min(5, floor($nivel / 10) + 3))),
                'poderes_equipados' => json_encode([1, 2, 3]),
                'stats_actuales' => json_encode($statsBase),
                'puntos_prestigio' => $nivel > 50 ? rand(50, 200) : 0,
                'veces_reset' => 0,
                'streak_participacion' => match($estudiante['perfil']) {
                    'constante' => rand(10, 20),
                    'líder' => rand(8, 15),
                    'velocista' => rand(5, 12),
                    default => rand(2, 10)
                },
                'fecha_ultimo_nivel' => now()->subDays(rand(1, 20)),
                'ultimo_uso_poder' => now()->subHours(rand(2, 24)),
                'ultima_actividad' => now()->subHours(rand(1, 12)),
                'logros_personales' => json_encode([
                    'primera_actividad_completada' => true,
                    'primer_nivel_alcanzado' => true,
                    'primer_poder_usado' => true,
                    'primera_ayuda_realizada' => true,
                    'primer_clan_unido' => $estudiante['perfil'] !== 'individual',
                    'primera_muerte_academica' => rand(0, 1) == 1,
                    'primer_revival_realizado' => $estudiante['clase_rpg'] == 3, // Hechiceros
                ]),
                'created_at' => now()->subDays(rand(25, 35)),
                'updated_at' => now(),
            ]);

            $personajesCreados[] = [
                'id' => $personajeId,
                'estudiante_id' => $estudiante['id'],
                'nivel' => $nivel,
                'clase_rpg' => $estudiante['clase_rpg'],
                'perfil' => $estudiante['perfil']
            ];

            // ASIGNAR PODERES SEGÚN NIVEL
            $poderesClase = DB::table('poder_habilidad')
                ->where('id_clase_rpg', $estudiante['clase_rpg'])
                ->where('nivel_requerido', '<=', $nivel)
                ->get();

            foreach ($poderesClase as $index => $poder) {
                DB::table('personaje_poder')->insert([
                    'id_personaje' => $personajeId,
                    'id_poder' => $poder->id,
                    'nivel_poder' => min(3, floor($nivel / 15) + 1),
                    'fecha_desbloqueado' => now()->subDays(rand(5, 25)),
                    'usos_disponibles' => 3,
                    'usos_totales' => rand(5, 50),
                    'equipado' => $index < 3, // Primeros 3 poderes equipados
                    'slot_equipado' => $index < 3 ? $index + 1 : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // ASIGNAR INVENTARIO VARIADO
            $itemsBasicos = [1, 2, 5]; // Vanguardia, Crepúsculo, Primeros Pasos
            if ($nivel > 20) $itemsBasicos[] = 3; // Obsidiana
            if ($nivel > 30) $itemsBasicos[] = 6; // Maestro Conocimiento
            if ($nivel > 40) $itemsBasicos[] = 4; // Luz Dorada

            foreach ($itemsBasicos as $itemId) {
                DB::table('inventario_personaje')->insert([
                    'id_personaje' => $personajeId,
                    'id_item' => $itemId,
                    'cantidad' => 1,
                    'equipado' => $itemId == ($nivel > 20 ? 2 : 1),
                    'fecha_obtenido' => now()->subDays(rand(5, 25)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('✅ 6 personajes creados con progresión realista');

        // CREAR CLAN FUNCIONAL
        $clanId = DB::table('clan_clase')->insertGetId([
            'id_clase' => $claseId,
            'nombre' => 'Guardianes de la Sabiduría',
            'descripcion' => 'Un clan élite dedicado a la búsqueda del conocimiento supremo y la excelencia académica. Unidos por el deseo de proteger y compartir la sabiduría, estos Guardianes han demostrado que el verdadero poder reside en el aprendizaje colaborativo.',
            'lider_id' => $estudiantesIds[0]['id'], // Ana como líder
            'max_miembros' => 6,
            'emblema_url' => '/images/clans/wisdom_guardians.jpg',
            'experiencia_clan' => 4750,
            'nivel_clan' => 5,
            'stats_clan' => json_encode([
                'actividades_completadas' => 58,
                'raids_completados' => 4,
                'strikes_completados' => 15,
                'miembros_revividos' => 18,
                'promedio_nivel' => 21.4,
                'dias_activo' => 35,
                'racha_actividad_maxima' => 12
            ]),
            'lema' => 'El conocimiento es nuestro destino',
            'created_at' => now()->subDays(30),
            'updated_at' => now(),
        ]);

        // MIEMBROS DEL CLAN CON ROLES
        $miembrosClan = [
            ['estudiante' => 0, 'rol' => 'lider', 'contribucion' => 1200],     // Ana
            ['estudiante' => 2, 'rol' => 'oficial', 'contribucion' => 950],   // María
            ['estudiante' => 5, 'rol' => 'oficial', 'contribucion' => 800],   // Gabriel
            ['estudiante' => 1, 'rol' => 'miembro', 'contribucion' => 750],   // Luis
            ['estudiante' => 3, 'rol' => 'miembro', 'contribucion' => 600],   // Diego
        ];

        foreach ($miembrosClan as $miembro) {
            DB::table('miembro_clan')->insert([
                'id_clan' => $clanId,
                'id_estudiante' => $estudiantesIds[$miembro['estudiante']]['id'],
                'rol_clan' => $miembro['rol'],
                'fecha_ingreso' => now()->subDays(rand(20, 30)),
                'contribucion_xp' => $miembro['contribucion'],
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ Clan creado: Guardianes de la Sabiduría (5 miembros)');

        // CREAR ACTIVIDADES VARIADAS
        $actividades = [
            [
                'titulo' => 'Strike: Álgebra en las Sombras',
                'descripcion' => 'Los antiguos secretos del álgebra han sido corrompidos por fuerzas del caos matemático. Reúne tu fireteam de 3 Guardianes y adéntrate en las Ecuaciones Sombría para restaurar el equilibrio algebraico. Solo trabajando en perfecta sincronía podrán descifrar las ecuaciones de segundo grado que protegen el núcleo del conocimiento.',
                'tipo' => 1, // strike
                'dificultad' => 'normal',
                'power_requerido' => 750,
                'xp' => 200,
                'glimmer' => 100,
                'activa' => true,
                'inicio' => 1,
                'entrega' => 7,
            ],
            [
                'titulo' => 'Raid: La Bóveda de la Geometría Infinita',
                'descripcion' => 'En lo más profundo del cosmos matemático yace la legendaria Bóveda de la Geometría Infinita. Este desafío épico requiere un fireteam completo de 6 Guardianes para enfrentar las pruebas más complejas de geometría espacial. Cada miembro debe dominar su rol: algunos resolverán, otros verificarán, y todos deberán comunicarse perfectamente para superar los enigmas tridimensionales que protegen los tesoros geométricos.',
                'tipo' => 2, // raid
                'dificultad' => 'heroico',
                'power_requerido' => 800,
                'xp' => 500,
                'glimmer' => 300,
                'activa' => true,
                'inicio' => 3,
                'entrega' => 14,
            ],
            [
                'titulo' => 'Crucible: Batalla de Fracciones',
                'descripcion' => 'El Crisol matemático arde con la competencia más feroz. Demuestra tu supremacía en el manejo de fracciones enfrentándote a otros Guardianes en combate directo. Solo los más rápidos y precisos en simplificar, sumar y multiplicar fracciones saldrán victoriosos de esta arena de conocimiento puro.',
                'tipo' => 3, // crucible
                'dificultad' => 'normal',
                'power_requerido' => 760,
                'xp' => 150,
                'glimmer' => 75,
                'activa' => false,
                'inicio' => -5,
                'entrega' => -1,
            ],
            [
                'titulo' => 'Nightfall: Trigonometría del Vacío',
                'descripcion' => 'Un Nightfall de dificultad extrema donde las fuerzas del Vacío han corrompido las funciones trigonométricas. Con modificadores que cambian constantemente las reglas del seno, coseno y tangente, solo los Guardianes más experimentados pueden esperar sobrevivir a esta prueba letal. La recompensa vale el riesgo: conocimiento trigonométrico de nivel legendario.',
                'tipo' => 5, // nightfall
                'dificultad' => 'legendario',
                'power_requerido' => 850,
                'xp' => 400,
                'glimmer' => 250,
                'activa' => true,
                'inicio' => 5,
                'entrega' => 12,
            ],
            [
                'titulo' => 'Patrol: Exploración Estadística',
                'descripcion' => 'Las vastas llanuras de la Estadística aguardan tu exploración. En esta misión de patrullaje libre, podrás practicar a tu ritmo mientras recolectas datos, analizas distribuciones y enfrentas encuentros aleatorios con problemas de probabilidad. Perfecto para Guardianes que prefieren aprender mediante la exploración autónoma.',
                'tipo' => 4, // patrol
                'dificultad' => 'facil',
                'power_requerido' => 750,
                'xp' => 80,
                'glimmer' => 40,
                'activa' => true,
                'inicio' => 0,
                'entrega' => 21,
            ],
            [
                'titulo' => 'Gambit: Límites y Derivadas',
                'descripcion' => 'Un modo híbrido único donde dos equipos compiten resolviendo problemas de cálculo mientras enfrentan "invasiones" de preguntas difíciles del equipo rival. Demuestra tu velocidad en límites y precisión en derivadas mientras proteges a tu equipo de las distracciones matemáticas enemigas.',
                'tipo' => 6, // gambit
                'dificultad' => 'heroico',
                'power_requerido' => 820,
                'xp' => 300,
                'glimmer' => 180,
                'activa' => true,
                'inicio' => 2,
                'entrega' => 9,
            ]
        ];

        foreach ($actividades as $actividad) {
            DB::table('actividad')->insert([
                'id_clase' => $claseId,
                'id_tipo_actividad' => $actividad['tipo'],
                'titulo' => $actividad['titulo'],
                'descripcion' => $actividad['descripcion'],
                'fecha_inicio' => now()->addDays($actividad['inicio']),
                'fecha_entrega' => now()->addDays($actividad['entrega']),
                'puntos_experiencia' => $actividad['xp'],
                'puntos_glimmer' => $actividad['glimmer'],
                'dificultad' => $actividad['dificultad'],
                'power_level_requerido' => $actividad['power_requerido'],
                'recompensas_especiales' => json_encode([
                    'gear_chance' => 0.3,
                    'shader_chance' => $actividad['dificultad'] === 'legendario' ? 0.4 : 0.1,
                    'emblem_especial' => 'activity_completion_' . $actividad['tipo'],
                    'badge_progress' => true,
                    'clan_xp_bonus' => 50
                ]),
                'requisitos_fireteam' => json_encode([
                    'min_participantes' => DB::table('tipo_actividad')->where('id', $actividad['tipo'])->value('min_participantes'),
                    'composicion_sugerida' => $actividad['tipo'] == 2 ? 
                        ['titan' => 2, 'cazador' => 2, 'hechicero' => 2] : 
                        ['tank' => 1, 'dps' => 1, 'support' => 1]
                ]),
                'activa' => $actividad['activa'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ 6 actividades creadas con lore completo');

        // CREAR MISIÓN PRINCIPAL ÉPICA
        $misionId = DB::table('mision')->insertGetId([
            'id_clase' => $claseId,
            'titulo' => 'La Búsqueda del Conocimiento Perdido',
            'descripcion' => 'Una misión épica que llevará a los Guardianes a través de todas las dimensiones del conocimiento matemático para recuperar los Códices Perdidos de la Sabiduría Ancestral.',
            'lore_historia' => 'Hace milenios, cuando las primeras civilizaciones dominaban las matemáticas, los Grandes Maestros del Conocimiento sellaron sus descubrimientos más profundos en los Códices de la Sabiduría Ancestral. Estos artefactos contienen secretos que podrían revolucionar nuestra comprensión del universo matemático. Pero las fuerzas del Caos Numérico han dispersado los códices por todo el cosmos. Como Guardianes del Conocimiento, vuestra misión es recuperar estos artefactos perdidos antes de que caigan en manos de la Ignorancia Primordial.',
            'fecha_inicio' => now()->subDays(5),
            'fecha_fin' => now()->addDays(25),
            'tipo_mision' => 'principal',
            'dificultad' => 'heroico',
            'poder_requerido' => 780,
            'experiencia_bonus' => 1000,
            'glimmer_bonus' => 500,
            'recompensas_especiales' => json_encode([
                'titulo_especial' => 'Recuperador del Conocimiento Ancestral',
                'emblem_legendario' => 'ancient_wisdom_seeker',
                'shader_exclusivo' => 'golden_knowledge_aura',
                'gear_exotico' => 'tome_of_infinite_wisdom',
                'badge_legendario' => 'master_of_ancient_lore'
            ]),
            'requiere_fireteam' => true,
            'min_participantes' => 3,
            'max_participantes' => 6,
            'activa' => true,
            'orden' => 1,
            'created_at' => now()->subDays(5),
            'updated_at' => now(),
        ]);

        // CREAR CONFIGURACIÓN DE CLASE
        DB::table('configuracion_clase')->insert([
            'id_clase' => $claseId,
            'permitir_personajes' => true,
            'permitir_tienda' => true,
            'permitir_clanes' => true,
            'sistema_poderes_activo' => true,
            'sistema_badges_activo' => true,
            'economia_virtual_activa' => true,
            'eventos_especiales_activos' => true,
            'multiplicadores_xp' => json_encode([
                'base' => 1.0,
                'primera_participacion' => 2.0,
                'trabajo_equipo' => 1.25,
                'evento_especial' => 1.5,
                'clan_bonus' => 1.1,
                'racha_participacion' => 1.05,
                'fin_de_semana' => 1.15
            ]),
            'limites_recursos' => json_encode([
                'salud_minima' => 0,
                'energia_minima' => 0,
                'luz_minima' => 0,
                'cooldown_minimo' => 300,
                'max_usar_poderes_dia' => 10,
                'max_glimmer_personal' => 10000,
                'max_nivel_sin_prestige' => 100
            ]),
            'cooldowns_personalizados' => json_encode([
                'revive_compañero' => 7200,
                'uso_poder_super' => 86400,
                'cambio_clan' => 604800,
                'reset_personaje' => 2592000,
                'cambio_clase_rpg' => 1209600 // 2 semanas si se permite
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // CREAR EVENTO ESPECIAL ACTIVO
        DB::table('evento_especial')->insert([
            'id_clase' => $claseId,
            'nombre' => 'Estandarte de Hierro: Trigonometría Suprema',
            'descripcion' => 'El evento más desafiante del semestre ha llegado. Durante esta semana, solo los Guardianes más dedicados y hábiles en trigonometría podrán reclamar las recompensas legendarias del Estandarte de Hierro. Las reglas son simples: el nivel de poder importa, cada error cuenta, y solo los mejores de los mejores obtendrán acceso al prestigioso título de Señor del Hierro Académico.',
            'tipo_evento' => 'iron_banner',
            'fecha_inicio' => now()->addDays(3),
            'fecha_fin' => now()->addDays(10),
            'recompensas_especiales' => json_encode([
                'shader_exclusivo' => 'iron_banner_scholar_supreme',
                'emblem_exclusivo' => 'iron_lord_mathematician',
                'titulo_especial' => 'Señor del Hierro Académico',
                'gear_legendario' => [
                    'casco_iron_scholar',
                    'guantes_iron_wisdom',
                    'botas_iron_velocity',
                    'peto_iron_guardian'
                ],
                'badge_exclusivo' => 'iron_banner_champion_2025',
                'glimmer_bonus' => 1000
            ]),
            'requisitos_participacion' => json_encode([
                'power_level_min' => 780,
                'nivel_min' => 15,
                'actividades_completadas_min' => 3,
                'estado_no_caido' => true,
                'clan_membership_bonus' => true
            ]),
            'imagen_banner' => '/images/events/iron_banner_trigonometry.jpg',
            'max_participantes' => 30,
            'reglas_especiales' => json_encode([
                'power_level_enabled' => true,
                'bonus_xp_multiplier' => 2.5,
                'elimination_style' => true,
                'respawn_delayed' => true,
                'team_damage_shared' => 0.1,
                'special_modifiers' => [
                    'precision_boost',
                    'teamwork_required',
                    'time_pressure',
                    'advanced_trigonometry'
                ],
                'daily_challenges' => true
            ]),
            'activo' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // CREAR ALGUNOS REGISTROS DE COMPORTAMIENTO REALISTAS
        $comportamientosEjemplo = [
            ['personaje_idx' => 0, 'tipo' => 1, 'desc' => 'Demostró excelencia excepcional resolviendo sistemas de ecuaciones lineales en tiempo récord'],
            ['personaje_idx' => 1, 'tipo' => 2, 'desc' => 'Ayudó pacientemente a María con problemas complejos de factorización'],
            ['personaje_idx' => 2, 'tipo' => 4, 'desc' => 'Propuso una metodología innovadora para abordar integrales por partes'],
            ['personaje_idx' => 0, 'tipo' => 7, 'desc' => 'Llegó 12 minutos tarde debido a problemas de transporte público'],
            ['personaje_idx' => 3, 'tipo' => 3, 'desc' => 'Lideró brillantemente su equipo durante el proyecto de geometría analítica'],
            ['personaje_idx' => 4, 'tipo' => 1, 'desc' => 'Mostró comprensión sobresaliente de funciones trigonométricas inversas'],
            ['personaje_idx' => 5, 'tipo' => 2, 'desc' => 'Utilizó su poder de "Revivir" para ayudar a Gabriel tras una evaluación difícil'],
            ['personaje_idx' => 1, 'tipo' => 4, 'desc' => 'Desarrolló una técnica única para memorizar fórmulas de derivadas'],
        ];

        foreach ($comportamientosEjemplo as $comp) {
            $personaje = $personajesCreados[$comp['personaje_idx']];
            $tipo = DB::table('tipo_comportamiento')->find($comp['tipo']);
            
            if ($tipo) {
                $saludAnterior = DB::table('personaje')->where('id', $personaje['id'])->value('salud_actual');
                $saludPosterior = max(0, min(100, $saludAnterior + $tipo->puntos_salud));
                
                DB::table('registro_comportamiento')->insert([
                    'id_personaje' => $personaje['id'],
                    'id_tipo_comportamiento' => $tipo->id,
                    'descripcion' => $comp['desc'],
                    'observacion' => 'Registro automático del sistema de seguimiento académico',
                    'salud_anterior' => $saludAnterior,
                    'salud_posterior' => $saludPosterior,
                    'fecha' => now()->subDays(rand(1, 10)),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Actualizar salud del personaje
                DB::table('personaje')->where('id', $personaje['id'])->update([
                    'salud_actual' => $saludPosterior,
                    'energia_actual' => max(0, DB::table('personaje')->where('id', $personaje['id'])->value('energia_actual') + $tipo->puntos_energia),
                    'luz_actual' => max(0, DB::table('personaje')->where('id', $personaje['id'])->value('luz_actual') + $tipo->puntos_luz),
                ]);
            }
        }

        // CREAR RANKINGS REALISTAS
        $personajesOrdenados = collect($personajesCreados)
            ->sortByDesc('nivel')
            ->values()
            ->toArray();

        foreach ($personajesOrdenados as $index => $personaje) {
            $puntuacion = (1000 - ($index * 80)) + rand(-50, 100);
            
            DB::table('ranking_clase')->insert([
                'id_clase' => $claseId,
                'id_personaje' => $personaje['id'],
                'tipo_ranking' => 'general',
                'posicion' => $index + 1,
                'puntuacion' => $puntuacion,
                'periodo_inicio' => now()->startOfMonth(),
                'periodo_fin' => now()->endOfMonth(),
                'metricas_detalladas' => json_encode([
                    'xp_total' => DB::table('personaje')->where('id', $personaje['id'])->value('experiencia'),
                    'actividades_completadas' => rand(8, 18),
                    'participaciones_totales' => rand(25, 60),
                    'ayudas_realizadas' => $personaje['clase_rpg'] == 3 ? rand(5, 15) : rand(0, 8),
                    'poderes_usados' => rand(10, 40),
                    'racha_maxima' => rand(3, 12),
                ]),
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // CREAR ESTADÍSTICAS DE CLASE
        DB::table('estadistica_clase')->insert([
            'id_clase' => $claseId,
            'promedio_nivel' => round(collect($personajesCreados)->avg('nivel'), 1),
            'promedio_participacion' => 82.3,
            'promedio_power_level' => 847.2,
            'distribucion_niveles' => json_encode([
                '1-10' => 0,
                '11-20' => 3,
                '21-30' => 3,
                '31+' => 0
            ]),
            'distribucion_clases_rpg' => json_encode([
                'Titán' => 2,
                'Cazador' => 2,
                'Hechicero' => 2
            ]),
            'total_personajes_activos' => 6,
            'fecha_calculo' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // CREAR ESTADÍSTICAS INDIVIDUALES DE PERSONAJES
        foreach ($personajesCreados as $personaje) {
            DB::table('estadistica_personaje')->insert([
                'id_personaje' => $personaje['id'],
                'actividades_completadas' => rand(8, 20),
                'eventos_participados' => rand(1, 3),
                'poderes_usados' => rand(15, 60),
                'interacciones_sociales' => $personaje['clase_rpg'] == 3 ? rand(20, 40) : rand(5, 25),
                'experiencia_total_ganada' => DB::table('personaje')->where('id', $personaje['id'])->value('experiencia') + rand(1000, 5000),
                'glimmer_total_ganado' => rand(2000, 8000),
                'dias_consecutivos_activo' => match($personaje['perfil']) {
                    'constante' => rand(15, 25),
                    'líder' => rand(12, 20),
                    'velocista' => rand(8, 15),
                    default => rand(5, 15)
                },
                'promedio_notas' => rand(14, 20), // Escala 0-20
                'veces_revivido' => $personaje['nivel'] < 20 ? rand(0, 3) : rand(0, 1),
                'veces_revivio_otros' => $personaje['clase_rpg'] == 3 ? rand(3, 10) : rand(0, 3),
                'fecha_calculo' => now()->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // CREAR NOTIFICACIONES DE EJEMPLO
        $notificacionesEjemplo = [
            [
                'usuario_id' => $profesorId,
                'titulo' => 'Nuevo Estudiante Inscrito',
                'mensaje' => 'Sofia Herrera se ha unido exitosamente a tu clase Matemáticas 5to A',
                'tipo' => 'info',
                'categoria' => 'academico',
                'icono' => '/icons/notifications/new_student.svg'
            ],
            [
                'usuario_id' => DB::table('estudiante')->where('codigo_estudiante', 'EST001')->value('id_usuario'),
                'titulo' => '¡Felicidades! Subiste de Nivel',
                'mensaje' => 'Has alcanzado el nivel 18. Nuevo poder desbloqueado: "Intimidación"',
                'tipo' => 'success',
                'categoria' => 'sistema',
                'icono' => '/icons/notifications/level_up.svg'
            ],
            [
                'usuario_id' => DB::table('estudiante')->where('codigo_estudiante', 'EST002')->value('id_usuario'),
                'titulo' => 'Poder Disponible',
                'mensaje' => 'Tu poder "Tiro de Precisión" ha salido de cooldown y está listo para usar',
                'tipo' => 'info',
                'categoria' => 'sistema',
                'icono' => '/icons/notifications/power_ready.svg'
            ],
            [
                'usuario_id' => DB::table('estudiante')->where('codigo_estudiante', 'EST003')->value('id_usuario'),
                'titulo' => 'Compañero Necesita Ayuda',
                'mensaje' => 'Diego está en estado crítico de salud. Como Hechicero, puedes usar "Revivir"',
                'tipo' => 'warning',
                'categoria' => 'social',
                'icono' => '/icons/notifications/revive_needed.svg'
            ],
            [
                'usuario_id' => $profesorId,
                'titulo' => 'Evento Especial Próximo',
                'mensaje' => 'El Estandarte de Hierro: Trigonometría comenzará en 3 días',
                'tipo' => 'info',
                'categoria' => 'evento',
                'icono' => '/icons/notifications/event_coming.svg'
            ]
        ];

        foreach ($notificacionesEjemplo as $notif) {
            DB::table('notificacion')->insert([
                'id_usuario' => $notif['usuario_id'],
                'titulo' => $notif['titulo'],
                'mensaje' => $notif['mensaje'],
                'tipo' => $notif['tipo'],
                'categoria' => $notif['categoria'],
                'leida' => rand(0, 1) == 1,
                'datos' => json_encode([
                    'timestamp' => now()->timestamp,
                    'auto_generated' => true
                ]),
                'icono_url' => $notif['icono'],
                'created_at' => now()->subHours(rand(1, 72)),
                'updated_at' => now(),
            ]);
        }

        // CREAR CÓDIGO DE ACCESO TEMPORAL
        DB::table('codigo_acceso_temporal')->insert([
            'id_clase' => $claseId,
            'codigo' => 'DEMO2025',
            'fecha_creacion' => now(),
            'fecha_expiracion' => now()->addDays(30),
            'usos_maximos' => 20,
            'usos_actuales' => 3,
            'restricciones' => json_encode([
                'solo_nuevos_estudiantes' => true,
                'grado_requerido' => '5to',
                'edad_minima' => 15,
                'edad_maxima' => 18
            ]),
            'activo' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('');
        $this->command->info('🎉 SISTEMA COMPLETO CREADO EXITOSAMENTE');
        $this->command->line('');
        $this->command->info('📊 RESUMEN DE DATOS CREADOS:');
        $this->command->line('');
        
        $this->command->line('👨‍🏫 USUARIOS:');
        $this->command->line('   • 1 Profesor: Carlos Mendoza');
        $this->command->line('   • 6 Estudiantes con perfiles únicos');
        $this->command->line('');
        
        $this->command->line('🎮 PERSONAJES:');
        $this->command->line('   • 6 Guardianes con niveles 15-28');
        $this->command->line('   • 2 Titanes, 2 Cazadores, 2 Hechiceros');
        $this->command->line('   • Poderes desbloqueados según nivel');
        $this->command->line('');
        
        $this->command->line('🏫 CLASE FUNCIONAL:');
        $this->command->line('   • Matemáticas 5to A - 2025');
        $this->command->line('   • Código de acceso: MATH5A25');
        $this->command->line('   • QR disponible para unirse');
        $this->command->line('');
        
        $this->command->line('⚔️ CLAN ACTIVO:');
        $this->command->line('   • Guardianes de la Sabiduría');
        $this->command->line('   • 5 miembros con roles definidos');
        $this->command->line('   • Nivel 5 con 4750 XP');
        $this->command->line('');
        
        $this->command->line('🎯 CONTENIDO:');
        $this->command->line('   • 6 actividades con lore completo');
        $this->command->line('   • 1 misión principal épica');
        $this->command->line('   • 1 evento Iron Banner activo');
        $this->command->line('   • Rankings y estadísticas generadas');
        $this->command->line('');
        
        $this->command->info('🔑 CREDENCIALES DE ACCESO:');
        $this->command->line('');
        $this->command->warn('👨‍🏫 PROFESOR:');
        $this->command->line('   Email: profesor@test.com');
        $this->command->line('   Password: password123');
        $this->command->line('');
        $this->command->warn('👥 ESTUDIANTES (todos con password: password123):');
        $this->command->line('   • ana@estudiante.test.com - Ana García (Titán Líder)');
        $this->command->line('   • luis@estudiante.test.com - Luis Rodríguez (Cazador Velocista)');
        $this->command->line('   • maria@estudiante.test.com - María López (Hechicero Colaborador)');
        $this->command->line('   • diego@estudiante.test.com - Diego Martín (Titán Constante)');
        $this->command->line('   • sofia@estudiante.test.com - Sofia Herrera (Cazador Competitivo)');
        $this->command->line('   • gabriel@estudiante.test.com - Gabriel Torres (Hechicero Mentor)');
        $this->command->line('');
        
        $this->command->info('✅ El sistema está 100% listo para desarrollo!');
        $this->command->warn('💡 Próximo paso: Crear modelos Laravel y controladores API');
    }
}