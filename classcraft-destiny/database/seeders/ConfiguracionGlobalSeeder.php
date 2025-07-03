<?php

// ===== database/seeders/ConfiguracionGlobalSeeder.php =====
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfiguracionGlobalSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('⚙️ Configurando parámetros globales del sistema...');
        
        $configuraciones = [
            // CONFIGURACIONES DE EXPERIENCIA
            [
                'clave' => 'xp_base_participacion',
                'valor' => '20',
                'tipo' => 'integer',
                'descripcion' => 'Experiencia base por participación en clase',
                'categoria' => 'experiencia',
                'editable' => true,
            ],
            [
                'clave' => 'xp_base_tarea_completada',
                'valor' => '100',
                'tipo' => 'integer',
                'descripcion' => 'Experiencia base por completar una tarea',
                'categoria' => 'experiencia',
                'editable' => true,
            ],
            [
                'clave' => 'multiplicador_primera_participacion',
                'valor' => '2.0',
                'tipo' => 'string',
                'descripcion' => 'Multiplicador para la primera participación del día',
                'categoria' => 'experiencia',
                'editable' => true,
            ],
            [
                'clave' => 'xp_bonus_trabajo_equipo',
                'valor' => '25',
                'tipo' => 'integer',
                'descripcion' => 'XP extra por trabajar en equipo exitosamente',
                'categoria' => 'experiencia',
                'editable' => true,
            ],
            [
                'clave' => 'xp_penalty_tarea_tarde',
                'valor' => '-50',
                'tipo' => 'integer',
                'descripcion' => 'Penalización de XP por entregar tarde',
                'categoria' => 'experiencia',
                'editable' => true,
            ],
            [
                'clave' => 'xp_bonus_racha_participacion',
                'valor' => '5',
                'tipo' => 'integer',
                'descripcion' => 'XP extra por cada día consecutivo de participación',
                'categoria' => 'experiencia',
                'editable' => true,
            ],
            
            // CONFIGURACIONES DE RECURSOS
            [
                'clave' => 'regeneracion_salud_diaria',
                'valor' => '10',
                'tipo' => 'integer',
                'descripcion' => 'Puntos de salud regenerados por día',
                'categoria' => 'recursos',
                'editable' => true,
            ],
            [
                'clave' => 'regeneracion_energia_hora',
                'valor' => '1',
                'tipo' => 'integer',
                'descripcion' => 'Puntos de energía regenerados por hora',
                'categoria' => 'recursos',
                'editable' => true,
            ],
            [
                'clave' => 'regeneracion_luz_ayuda',
                'valor' => '1',
                'tipo' => 'integer',
                'descripcion' => 'Puntos de luz ganados por ayudar a compañeros',
                'categoria' => 'recursos',
                'editable' => true,
            ],
            [
                'clave' => 'penalizacion_tardanza',
                'valor' => '10',
                'tipo' => 'integer',
                'descripcion' => 'Puntos de salud perdidos por tardanza',
                'categoria' => 'recursos',
                'editable' => true,
            ],
            [
                'clave' => 'salud_minima_supervivencia',
                'valor' => '1',
                'tipo' => 'integer',
                'descripcion' => 'Salud mínima antes de quedar "caído"',
                'categoria' => 'recursos',
                'editable' => true,
            ],
            [
                'clave' => 'penalizacion_abandono_equipo',
                'valor' => '50',
                'tipo' => 'integer',
                'descripcion' => 'Penalización en salud por abandonar equipo',
                'categoria' => 'recursos',
                'editable' => true,
            ],
            
            // CONFIGURACIONES DE ECONOMÍA
            [
                'clave' => 'glimmer_base_actividad',
                'valor' => '50',
                'tipo' => 'integer',
                'descripcion' => 'Glimmer base obtenido por actividad completada',
                'categoria' => 'economia',
                'editable' => true,
            ],
            [
                'clave' => 'costo_revive_glimmer',
                'valor' => '100',
                'tipo' => 'integer',
                'descripcion' => 'Costo en glimmer para revivir a un compañero',
                'categoria' => 'economia',
                'editable' => true,
            ],
            [
                'clave' => 'glimmer_participacion_diaria',
                'valor' => '25',
                'tipo' => 'integer',
                'descripcion' => 'Glimmer ganado por primera participación del día',
                'categoria' => 'economia',
                'editable' => true,
            ],
            [
                'clave' => 'descuento_clan_tienda',
                'valor' => '0.1',
                'tipo' => 'string',
                'descripcion' => 'Descuento en tienda para miembros de clan (10%)',
                'categoria' => 'economia',
                'editable' => true,
            ],
            [
                'clave' => 'limite_glimmer_diario',
                'valor' => '500',
                'tipo' => 'integer',
                'descripcion' => 'Máximo glimmer que se puede ganar por día',
                'categoria' => 'economia',
                'editable' => true,
            ],
            [
                'clave' => 'limite_glimmer_personal',
                'valor' => '10000',
                'tipo' => 'integer',
                'descripcion' => 'Máximo glimmer que puede tener un personaje',
                'categoria' => 'economia',
                'editable' => true,
            ],
            
            // CONFIGURACIONES DE TIEMPO
            [
                'clave' => 'duracion_muerte_academica',
                'valor' => '86400',
                'tipo' => 'integer',
                'descripcion' => 'Duración en segundos de la muerte académica (24 horas)',
                'categoria' => 'tiempo',
                'editable' => true,
            ],
            [
                'clave' => 'cooldown_poder_base',
                'valor' => '3600',
                'tipo' => 'integer',
                'descripcion' => 'Cooldown base en segundos para poderes (1 hora)',
                'categoria' => 'tiempo',
                'editable' => true,
            ],
            [
                'clave' => 'tiempo_gracia_tardanza',
                'valor' => '300',
                'tipo' => 'integer',
                'descripcion' => 'Segundos de gracia antes de marcar tardanza (5 minutos)',
                'categoria' => 'tiempo',
                'editable' => true,
            ],
            [
                'clave' => 'duracion_sesion_clase',
                'valor' => '2700',
                'tipo' => 'integer',
                'descripcion' => 'Duración estándar de una sesión de clase (45 minutos)',
                'categoria' => 'tiempo',
                'editable' => true,
            ],
            [
                'clave' => 'tiempo_inactividad_logout',
                'valor' => '7200',
                'tipo' => 'integer',
                'descripcion' => 'Tiempo de inactividad antes de logout automático (2 horas)',
                'categoria' => 'tiempo',
                'editable' => true,
            ],
            [
                'clave' => 'cooldown_cambio_clan',
                'valor' => '604800',
                'tipo' => 'integer',
                'descripcion' => 'Tiempo de espera para cambiar de clan (7 días)',
                'categoria' => 'tiempo',
                'editable' => true,
            ],
            
            // CONFIGURACIONES DE SISTEMA
            [
                'clave' => 'max_poder_equipados',
                'valor' => '4',
                'tipo' => 'integer',
                'descripcion' => 'Máximo número de poderes que se pueden equipar simultáneamente',
                'categoria' => 'sistema',
                'editable' => true,
            ],
            [
                'clave' => 'poder_level_inicial',
                'valor' => '750',
                'tipo' => 'integer',
                'descripcion' => 'Nivel de poder inicial para nuevos personajes',
                'categoria' => 'sistema',
                'editable' => true,
            ],
            [
                'clave' => 'max_miembros_clan',
                'valor' => '6',
                'tipo' => 'integer',
                'descripcion' => 'Máximo número de miembros por clan',
                'categoria' => 'sistema',
                'editable' => true,
            ],
            [
                'clave' => 'habilitar_modo_prestige',
                'valor' => 'true',
                'tipo' => 'boolean',
                'descripcion' => 'Habilitar sistema de prestige post-nivel 100',
                'categoria' => 'sistema',
                'editable' => true,
            ],
            [
                'clave' => 'nivel_max_normal',
                'valor' => '100',
                'tipo' => 'integer',
                'descripcion' => 'Nivel máximo antes del prestige',
                'categoria' => 'sistema',
                'editable' => false,
            ],
            [
                'clave' => 'max_emotes_equipados',
                'valor' => '4',
                'tipo' => 'integer',
                'descripcion' => 'Máximo número de emotes equipados',
                'categoria' => 'sistema',
                'editable' => true,
            ],
            [
                'clave' => 'permitir_cambio_clase_rpg',
                'valor' => 'false',
                'tipo' => 'boolean',
                'descripcion' => 'Permitir que estudiantes cambien de clase RPG',
                'categoria' => 'sistema',
                'editable' => true,
            ],
            [
                'clave' => 'max_clases_por_profesor',
                'valor' => '10',
                'tipo' => 'integer',
                'descripcion' => 'Máximo número de clases que puede gestionar un profesor',
                'categoria' => 'sistema',
                'editable' => true,
            ],
            
            // CONFIGURACIONES DE NOTIFICACIONES
            [
                'clave' => 'notificar_subida_nivel',
                'valor' => 'true',
                'tipo' => 'boolean',
                'descripcion' => 'Enviar notificación cuando un estudiante sube de nivel',
                'categoria' => 'notificaciones',
                'editable' => true,
            ],
            [
                'clave' => 'notificar_poder_disponible',
                'valor' => 'true',
                'tipo' => 'boolean',
                'descripcion' => 'Notificar cuando un poder sale de cooldown',
                'categoria' => 'notificaciones',
                'editable' => true,
            ],
            [
                'clave' => 'notificar_actividad_nueva',
                'valor' => 'true',
                'tipo' => 'boolean',
                'descripcion' => 'Notificar cuando se crea una nueva actividad',
                'categoria' => 'notificaciones',
                'editable' => true,
            ],
            [
                'clave' => 'notificar_compañero_caido',
                'valor' => 'true',
                'tipo' => 'boolean',
                'descripcion' => 'Notificar a clan cuando un miembro está caído',
                'categoria' => 'notificaciones',
                'editable' => true,
            ],
            [
                'clave' => 'frecuencia_notificaciones_resumen',
                'valor' => 'diaria',
                'tipo' => 'string',
                'descripcion' => 'Frecuencia de notificaciones resumen (diaria, semanal)',
                'categoria' => 'notificaciones',
                'editable' => true,
            ],
            [
                'clave' => 'notificar_evento_especial',
                'valor' => 'true',
                'tipo' => 'boolean',
                'descripcion' => 'Notificar cuando inicia un evento especial',
                'categoria' => 'notificaciones',
                'editable' => true,
            ],
            
            // CONFIGURACIONES DE UI
            [
                'clave' => 'tema_visual_default',
                'valor' => 'destiny_dark',
                'tipo' => 'string',
                'descripcion' => 'Tema visual por defecto para nuevas clases',
                'categoria' => 'ui',
                'editable' => true,
            ],
            [
                'clave' => 'mostrar_animaciones',
                'valor' => 'true',
                'tipo' => 'boolean',
                'descripcion' => 'Habilitar animaciones en la interfaz',
                'categoria' => 'ui',
                'editable' => true,
            ],
            [
                'clave' => 'mostrar_particulas',
                'valor' => 'true',
                'tipo' => 'boolean',
                'descripcion' => 'Mostrar efectos de partículas en el fondo',
                'categoria' => 'ui',
                'editable' => true,
            ],
            [
                'clave' => 'volumen_efectos',
                'valor' => '0.7',
                'tipo' => 'string',
                'descripcion' => 'Volumen por defecto para efectos de sonido',
                'categoria' => 'ui',
                'editable' => true,
            ],
            [
                'clave' => 'idioma_default',
                'valor' => 'es',
                'tipo' => 'string',
                'descripcion' => 'Idioma por defecto del sistema',
                'categoria' => 'ui',
                'editable' => true,
            ],
            [
                'clave' => 'ocultar_stats_otros_personajes',
                'valor' => 'false',
                'tipo' => 'boolean',
                'descripcion' => 'Ocultar estadísticas detalladas de otros personajes',
                'categoria' => 'ui',
                'editable' => true,
            ],
            
            // CONFIGURACIONES DE BALANCE
            [
                'clave' => 'multiplicador_clan_xp',
                'valor' => '1.1',
                'tipo' => 'string',
                'descripcion' => 'Multiplicador de XP para miembros de clan activo',
                'categoria' => 'balance',
                'editable' => true,
            ],
            [
                'clave' => 'bonus_lider_clan',
                'valor' => '5',
                'tipo' => 'integer',
                'descripcion' => 'Bonus de XP adicional para líderes de clan',
                'categoria' => 'balance',
                'editable' => true,
            ],
            [
                'clave' => 'factor_dificultad_adaptativa',
                'valor' => '1.0',
                'tipo' => 'string',
                'descripcion' => 'Factor para ajustar dificultad según rendimiento',
                'categoria' => 'balance',
                'editable' => true,
            ],
            [
                'clave' => 'bonus_primera_clase_dia',
                'valor' => '1.2',
                'tipo' => 'string',
                'descripcion' => 'Multiplicador XP para primera clase del día',
                'categoria' => 'balance',
                'editable' => true,
            ],
            [
                'clave' => 'penalizacion_ausencia_injustificada',
                'valor' => '30',
                'tipo' => 'integer',
                'descripcion' => 'Puntos de salud perdidos por ausencia sin justificación',
                'categoria' => 'balance',
                'editable' => true,
            ],
            
            // CONFIGURACIONES DE SEGURIDAD
            [
                'clave' => 'max_intentos_login',
                'valor' => '5',
                'tipo' => 'integer',
                'descripcion' => 'Máximo intentos de login antes de bloquear',
                'categoria' => 'seguridad',
                'editable' => true,
            ],
            [
                'clave' => 'tiempo_bloqueo_login',
                'valor' => '900',
                'tipo' => 'integer',
                'descripcion' => 'Tiempo de bloqueo tras exceder intentos (15 min)',
                'categoria' => 'seguridad',
                'editable' => true,
            ],
            [
                'clave' => 'registrar_acciones_admin',
                'valor' => 'true',
                'tipo' => 'boolean',
                'descripcion' => 'Registrar todas las acciones administrativas',
                'categoria' => 'seguridad',
                'editable' => false,
            ],
            [
                'clave' => 'verificacion_dos_factores',
                'valor' => 'false',
                'tipo' => 'boolean',
                'descripcion' => 'Habilitar verificación en dos pasos',
                'categoria' => 'seguridad',
                'editable' => true,
            ],
            [
                'clave' => 'caducidad_sesion_profesor',
                'valor' => '28800',
                'tipo' => 'integer',
                'descripcion' => 'Duración máxima de sesión profesor (8 horas)',
                'categoria' => 'seguridad',
                'editable' => true,
            ],
            [
                'clave' => 'caducidad_sesion_estudiante',
                'valor' => '14400',
                'tipo' => 'integer',
                'descripcion' => 'Duración máxima de sesión estudiante (4 horas)',
                'categoria' => 'seguridad',
                'editable' => true,
            ],
            
            // CONFIGURACIONES DE EVENTOS
            [
                'clave' => 'habilitar_eventos_automaticos',
                'valor' => 'true',
                'tipo' => 'boolean',
                'descripcion' => 'Permitir que el sistema genere eventos automáticamente',
                'categoria' => 'eventos',
                'editable' => true,
            ],
            [
                'clave' => 'frecuencia_eventos_especiales',
                'valor' => '14',
                'tipo' => 'integer',
                'descripcion' => 'Días entre eventos especiales automáticos',
                'categoria' => 'eventos',
                'editable' => true,
            ],
            [
                'clave' => 'multiplicador_xp_eventos',
                'valor' => '2.0',
                'tipo' => 'string',
                'descripcion' => 'Multiplicador de XP durante eventos especiales',
                'categoria' => 'eventos',
                'editable' => true,
            ]
        ];

        foreach ($configuraciones as $config) {
            $config['created_at'] = now();
            $config['updated_at'] = now();
            DB::table('configuracion_global')->insert($config);
        }
        
        $this->command->info('✅ 52 configuraciones globales creadas exitosamente');
        $this->command->line('');
        $this->command->line('📋 Categorías configuradas:');
        $this->command->line('   🔸 Experiencia (6 parámetros)');
        $this->command->line('   🔸 Recursos (6 parámetros)');
        $this->command->line('   🔸 Economía (6 parámetros)');
        $this->command->line('   🔸 Tiempo (6 parámetros)');
        $this->command->line('   🔸 Sistema (8 parámetros)');
        $this->command->line('   🔸 Notificaciones (6 parámetros)');
        $this->command->line('   🔸 UI (6 parámetros)');
        $this->command->line('   🔸 Balance (5 parámetros)');
        $this->command->line('   🔸 Seguridad (6 parámetros)');
        $this->command->line('   🔸 Eventos (3 parámetros)');
        $this->command->line('');
        $this->command->warn('💡 Estas configuraciones se pueden modificar desde el panel de administración');
        $this->command->warn('💡 Los parámetros marcados como no editables son críticos del sistema');
    }
}