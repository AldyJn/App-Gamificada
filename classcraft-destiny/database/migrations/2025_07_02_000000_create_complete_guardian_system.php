<?php
// database/migrations/2025_07_02_000000_create_complete_guardian_system.php
// MIGRACIÓN COMPLETA CON MECÁNICAS DESTINY + CLASSCRAFT

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // ===== TABLAS DE AUTENTICACIÓN =====
        Schema::create('tipo_usuario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique(); // 'profesor', 'estudiante'
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('estado_usuario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 20)->unique(); // 'activo', 'inactivo', 'suspendido'
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        Schema::create('usuario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('apellido', 100); // AGREGADO
            $table->string('correo', 100)->unique();
            $table->string('avatar')->nullable();
            $table->string('contraseña_hash', 255);
            $table->string('salt', 128);
            $table->foreignId('id_tipo_usuario')->constrained('tipo_usuario');
            $table->foreignId('id_estado')->default(1)->constrained('estado_usuario');
            $table->timestamp('ultimo_acceso')->nullable();
            $table->json('configuracion_ui')->nullable(); // Preferencias UI
            $table->string('timezone')->default('America/Lima');
            $table->boolean('notificaciones_push')->default(true);
            $table->json('estadisticas_globales')->nullable(); // Stats cross-clase
            $table->boolean('eliminado')->default(false);
            $table->timestamps();
        });

        // ===== TABLAS DE PERFILES =====
        Schema::create('estudiante', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuario')->cascadeOnDelete();
            $table->string('codigo_estudiante', 20)->unique();
            $table->string('grado', 50)->nullable();
            $table->string('seccion', 10)->nullable();
            $table->timestamps();
        });

        Schema::create('docente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuario')->cascadeOnDelete();
            $table->string('especialidad', 100)->nullable();
            $table->timestamps();
        });

        // ===== SISTEMA DE CLASES RPG (DESTINY STYLE) =====
        Schema::create('clase_rpg', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique(); // 'Titán', 'Cazador', 'Hechicero'
            $table->text('descripcion')->nullable();
            $table->text('lore_descripcion')->nullable(); // Historia/trasfondo
            $table->json('stats_base')->nullable(); // {salud: 100, energia: 6, luz: 4}
            $table->json('arbol_habilidades')->nullable(); // Árbol de progresión
            $table->integer('energia_maxima')->default(100);
            $table->json('bonificaciones_pasivas')->nullable(); // Bonos automáticos
            $table->string('imagen_url')->nullable();
            $table->string('color_primario', 7)->default('#FFFFFF'); // Color hex
            $table->string('color_secundario', 7)->default('#000000');
            $table->timestamps();
        });

        // ===== SISTEMA DE PODERES/HABILIDADES =====
        Schema::create('poder_habilidad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_clase_rpg')->constrained('clase_rpg');
            $table->string('nombre', 100);
            $table->text('descripcion');
            $table->integer('nivel_requerido')->default(1);
            $table->json('costo_recursos')->nullable(); // {energia: 3, luz: 0}
            $table->integer('tiempo_cooldown')->default(0); // en segundos
            $table->json('efecto_mecanico')->nullable(); // {tipo: "absorber_daño", valor: 100}
            $table->string('tipo_poder')->default('activo'); // 'activo', 'pasivo', 'super'
            $table->string('categoria')->nullable(); // 'ofensivo', 'defensivo', 'soporte'
            $table->string('icono_url')->nullable();
            $table->string('animacion_css')->nullable(); // Clase CSS para animación
            $table->string('sonido_url')->nullable();
            $table->enum('rareza', ['comun', 'raro', 'epico', 'exotico'])->default('comun');
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0); // Para ordenar en UI
            $table->timestamps();
        });

        // ===== SISTEMA DE NIVELES DE EXPERIENCIA =====
        Schema::create('nivel_experiencia', function (Blueprint $table) {
            $table->integer('nivel')->primary(); // 1-100
            $table->bigInteger('experiencia_requerida');
            $table->json('recompensas')->nullable();
            $table->string('titulo_desbloqueado')->nullable();
            $table->string('poder_desbloqueado')->nullable(); // ID del poder que se desbloquea
            $table->timestamps();
        });

        // ===== GESTIÓN DE CLASES =====
        Schema::create('clase', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->foreignId('id_docente')->constrained('docente');
            $table->string('grado', 50)->nullable();
            $table->string('seccion', 10)->nullable();
            $table->integer('año_academico');
            $table->boolean('activo')->default(true);
            $table->string('codigo_invitacion', 10)->unique()->nullable();
            $table->string('qr_url')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->string('tema_visual')->default('destiny_default');
            $table->json('configuracion_gamificacion')->nullable(); // Multiplicadores, reglas
            $table->boolean('modo_competitivo')->default(false);
            $table->json('horarios_clase')->nullable(); // Horarios regulares
            $table->timestamps();
        });

        Schema::create('inscripcion_clase', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_clase')->constrained('clase');
            $table->foreignId('id_estudiante')->constrained('estudiante');
            $table->timestamp('fecha_ingreso')->useCurrent();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->unique(['id_clase', 'id_estudiante']);
        });

        // ===== PERSONAJES CON MECÁNICAS DESTINY =====
        Schema::create('personaje', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_estudiante')->constrained('estudiante');
            $table->foreignId('id_clase')->constrained('clase');
            $table->foreignId('id_clase_rpg')->constrained('clase_rpg');
            $table->string('nombre', 100)->nullable();
            $table->integer('nivel')->default(1);
            $table->bigInteger('experiencia')->default(0);
            $table->integer('power_level')->default(750); // Separado del nivel normal
            
            // RECURSOS DESTINY STYLE
            $table->integer('salud_actual')->default(100);
            $table->integer('salud_maxima')->default(100);
            $table->integer('energia_actual')->default(10);
            $table->integer('energia_maxima')->default(10);
            $table->integer('luz_actual')->default(10);
            $table->integer('luz_maxima')->default(10);
            
            // PERSONALIZACIÓN
            $table->string('avatar_base', 50)->default('guardian_basic');
            $table->string('imagen_personalizada')->nullable();
            $table->json('gear_equipado')->nullable(); // {shader: 'vanguard_blue', emblem: 'first_steps'}
            $table->json('poderes_desbloqueados')->nullable(); // [1,3,5,7] IDs de poderes
            $table->json('poderes_equipados')->nullable(); // [1,3] IDs activos (máx 4)
            
            // STATS Y PROGRESIÓN
            $table->json('stats_actuales')->nullable(); // {fuerza: 10, inteligencia: 15, agilidad: 8}
            $table->integer('puntos_prestigio')->default(0); // Para post-100
            $table->integer('veces_reset')->default(0); // Cuántas veces ha hecho prestige
            $table->integer('streak_participacion')->default(0);
            $table->timestamp('fecha_ultimo_nivel')->nullable();
            $table->timestamp('ultimo_uso_poder')->nullable();
            $table->timestamp('ultima_actividad')->nullable();
            $table->json('logros_personales')->nullable();
            
            $table->timestamps();
            
            // Relaciones
            $table->foreign('nivel')->references('nivel')->on('nivel_experiencia');
            $table->unique(['id_estudiante', 'id_clase']); // Un personaje por clase
        });

        // ===== PODERES EQUIPADOS POR PERSONAJE =====
        Schema::create('personaje_poder', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_personaje')->constrained('personaje')->cascadeOnDelete();
            $table->foreignId('id_poder')->constrained('poder_habilidad');
            $table->integer('nivel_poder')->default(1); // Evolución del poder
            $table->timestamp('fecha_desbloqueado');
            $table->integer('usos_disponibles')->default(3); // Límite diario
            $table->timestamp('ultimo_uso')->nullable();
            $table->integer('usos_totales')->default(0); // Estadística
            $table->boolean('equipado')->default(false);
            $table->integer('slot_equipado')->nullable(); // 1,2,3,4 para ordenar poderes
            $table->timestamps();
            
            $table->unique(['id_personaje', 'id_poder']);
        });

        // ===== SISTEMA DE EQUIPAMIENTO/GEAR =====
        Schema::create('item_equipamiento', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion');
            $table->enum('tipo_slot', ['casco', 'guantes', 'pecho', 'botas', 'clase_item', 'arma', 'shader', 'emblem']);
            $table->enum('rareza', ['comun', 'raro', 'epico', 'exotico'])->default('comun');
            $table->integer('nivel_poder')->default(1);
            $table->json('stats_bonus')->nullable(); // {inteligencia: 5, fuerza: 2}
            $table->json('efecto_especial')->nullable(); // Efectos únicos del item
            $table->string('imagen_url')->nullable();
            $table->string('icono_url')->nullable();
            $table->integer('precio_tienda')->nullable();
            $table->json('requisitos')->nullable(); // {nivel_min: 10, clase_rpg: "titan"}
            $table->string('color_hex', 7)->nullable(); // Para shaders
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // ===== INVENTARIO DEL PERSONAJE =====
        Schema::create('inventario_personaje', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_personaje')->constrained('personaje')->cascadeOnDelete();
            $table->foreignId('id_item')->constrained('item_equipamiento');
            $table->integer('cantidad')->default(1);
            $table->boolean('equipado')->default(false);
            $table->timestamp('fecha_obtenido')->useCurrent();
            $table->json('propiedades_aleatorias')->nullable(); // Stats variables
            $table->timestamps();
        });

        // ===== SISTEMA DE CLANES/FIRETEAMS =====
        Schema::create('clan_clase', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_clase')->constrained('clase');
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->foreignId('lider_id')->constrained('estudiante');
            $table->integer('max_miembros')->default(6);
            $table->string('emblema_url')->nullable();
            $table->bigInteger('experiencia_clan')->default(0);
            $table->integer('nivel_clan')->default(1);
            $table->json('stats_clan')->nullable(); // Estadísticas del clan
            $table->string('lema')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('miembro_clan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_clan')->constrained('clan_clase');
            $table->foreignId('id_estudiante')->constrained('estudiante');
            $table->enum('rol_clan', ['lider', 'oficial', 'miembro'])->default('miembro');
            $table->timestamp('fecha_ingreso')->useCurrent();
            $table->bigInteger('contribucion_xp')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->unique(['id_clan', 'id_estudiante']);
        });

        // ===== SISTEMA DE ACTIVIDADES Y CONTENIDO =====
        Schema::create('tipo_actividad', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique(); // 'strike', 'raid', 'crucible', 'patrol'
            $table->text('descripcion')->nullable();
            $table->integer('min_participantes')->default(1);
            $table->integer('max_participantes')->default(6);
            $table->integer('duracion_estimada')->nullable(); // en minutos
            $table->timestamps();
        });

        Schema::create('actividad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_clase')->constrained('clase');
            $table->foreignId('id_tipo_actividad')->constrained('tipo_actividad');
            $table->string('titulo', 100);
            $table->text('descripcion')->nullable();
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_entrega')->nullable();
            $table->integer('puntos_experiencia')->default(0);
            $table->integer('puntos_glimmer')->default(0); // Moneda Destiny
            $table->json('recompensas_especiales')->nullable(); // Gear, emblemas, etc.
            $table->enum('dificultad', ['facil', 'normal', 'heroico', 'legendario'])->default('normal');
            $table->integer('power_level_requerido')->default(750);
            $table->json('requisitos_fireteam')->nullable(); // {tank: 1, dps: 1, support: 1}
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });

        // ===== PARTICIPACIÓN EN ACTIVIDADES =====
        Schema::create('participacion_actividad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_actividad')->constrained('actividad');
            $table->foreignId('id_personaje')->constrained('personaje');
            $table->enum('rol_fireteam', ['tank', 'dps', 'support', 'flex'])->nullable();
            $table->timestamp('fecha_inicio')->useCurrent();
            $table->timestamp('fecha_completada')->nullable();
            $table->boolean('completada')->default(false);
            $table->decimal('porcentaje_completado', 5, 2)->default(0);
            $table->decimal('nota', 5, 2)->nullable();
            $table->bigInteger('experiencia_obtenida')->default(0);
            $table->integer('glimmer_obtenido')->default(0);
            $table->json('recompensas_obtenidas')->nullable();
            $table->text('comentario_docente')->nullable();
            $table->timestamps();
        });

        // ===== SISTEMA DE ENTREGA DE ACTIVIDADES =====
        Schema::create('entrega_actividad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_participacion')->constrained('participacion_actividad');
            $table->string('archivo')->nullable();
            $table->text('descripcion_entrega')->nullable();
            $table->text('reflexion_estudiante')->nullable(); // Para actividades de reflection
            $table->timestamp('fecha_entrega')->useCurrent();
            $table->boolean('entrega_tardia')->default(false);
            $table->timestamps();
        });

        // ===== EVENTOS ESPECIALES (DESTINY STYLE) =====
        Schema::create('evento_especial', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_clase')->constrained('clase');
            $table->string('nombre', 100);
            $table->text('descripcion');
            $table->enum('tipo_evento', ['iron_banner', 'trials', 'raid', 'festival', 'competencia'])->default('festival');
            $table->timestamp('fecha_inicio');
            $table->timestamp('fecha_fin');
            $table->json('recompensas_especiales')->nullable();
            $table->json('requisitos_participacion')->nullable();
            $table->string('imagen_banner')->nullable();
            $table->integer('max_participantes')->nullable();
            $table->json('reglas_especiales')->nullable(); // Modificadores del evento
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('participacion_evento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_evento')->constrained('evento_especial');
            $table->foreignId('id_personaje')->constrained('personaje');
            $table->integer('puntos_evento')->default(0);
            $table->integer('rango_evento')->nullable();
            $table->json('recompensas_obtenidas')->nullable();
            $table->timestamp('fecha_inscripcion')->useCurrent();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->unique(['id_evento', 'id_personaje']);
        });

        // ===== SISTEMA DE RANKINGS =====
        Schema::create('ranking_clase', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_clase')->constrained('clase');
            $table->foreignId('id_personaje')->constrained('personaje');
            $table->enum('tipo_ranking', ['general', 'semanal', 'mensual', 'evento'])->default('general');
            $table->integer('posicion');
            $table->bigInteger('puntuacion');
            $table->date('periodo_inicio');
            $table->date('periodo_fin');
            $table->json('metricas_detalladas')->nullable(); // Breakdown de puntuación
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->unique(['id_clase', 'id_personaje', 'tipo_ranking', 'periodo_inicio']);
        });

        // ===== TÍTULOS Y LOGROS =====
        Schema::create('titulo_rango', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion');
            $table->json('requisitos')->nullable(); // {posicion_max: 3, semanas_consecutivas: 4}
            $table->string('color_titulo', 7)->default('#FFFFFF'); // Hex color
            $table->enum('efecto_visual', ['normal', 'dorado', 'brillante', 'animado'])->default('normal');
            $table->string('icono_url')->nullable();
            $table->enum('rareza', ['comun', 'raro', 'epico', 'legendario'])->default('comun');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('personaje_titulo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_personaje')->constrained('personaje');
            $table->foreignId('id_titulo')->constrained('titulo_rango');
            $table->boolean('titulo_equipado')->default(false);
            $table->timestamp('fecha_obtenido')->useCurrent();
            $table->timestamps();
            
            $table->unique(['id_personaje', 'id_titulo']);
        });

        // ===== SISTEMA DE BADGES/LOGROS =====
        Schema::create('badge', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion');
            $table->string('imagen_url')->nullable();
            $table->enum('tipo', ['academico', 'social', 'asistencia', 'especial', 'clan'])->default('academico');
            $table->json('criterios')->nullable(); // criterios específicos
            $table->enum('rareza', ['comun', 'raro', 'epico', 'legendario'])->default('comun');
            $table->integer('puntos_prestigio')->default(0); // Para sistema de prestigio
            $table->boolean('oculto')->default(false); // Logros secretos
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('personaje_badge', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_personaje')->constrained('personaje');
            $table->foreignId('id_badge')->constrained('badge');
            $table->timestamp('fecha_obtenido');
            $table->json('progreso_obtencion')->nullable(); // Como se logró
            $table->timestamps();
            
            $table->unique(['id_personaje', 'id_badge']);
        });

        // ===== COMPORTAMIENTO Y ASISTENCIA =====
        Schema::create('tipo_comportamiento', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->text('descripcion')->nullable();
            $table->integer('puntos_salud')->default(0); // Daño/curación a la salud
            $table->integer('puntos_energia')->default(0); // Modificador de energía
            $table->integer('puntos_luz')->default(0); // Modificador de luz
            $table->enum('tipo', ['positivo', 'negativo'])->default('positivo');
            $table->string('icono', 10)->nullable();
            $table->boolean('afecta_equipo')->default(false); // Si afecta al clan/fireteam
            $table->timestamps();
        });

        Schema::create('registro_comportamiento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_personaje')->constrained('personaje');
            $table->foreignId('id_tipo_comportamiento')->constrained('tipo_comportamiento');
            $table->text('descripcion')->nullable();
            $table->text('observacion')->nullable();
            $table->integer('salud_anterior');
            $table->integer('salud_posterior');
            $table->timestamp('fecha')->useCurrent();
            $table->timestamps();
        });

        Schema::create('asistencia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_clase')->constrained('clase');
            $table->foreignId('id_personaje')->constrained('personaje');
            $table->foreignId('id_docente')->constrained('docente');
            $table->date('fecha');
            $table->boolean('presente')->default(true);
            $table->enum('estado', ['presente', 'tardanza', 'ausente', 'justificado'])->default('presente');
            $table->text('justificacion')->nullable();
            $table->integer('minutos_tardanza')->nullable();
            $table->timestamps();
            
            $table->unique(['id_clase', 'id_personaje', 'fecha']);
        });

        // ===== ECONOMÍA VIRTUAL =====
        Schema::create('transaccion_glimmer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_personaje')->constrained('personaje');
            $table->enum('tipo', ['ingreso', 'gasto']);
            $table->integer('cantidad');
            $table->string('descripcion');
            $table->string('referencia_tipo')->nullable(); // 'actividad', 'comportamiento', 'item_tienda'
            $table->bigInteger('referencia_id')->nullable();
            $table->foreignId('otorgado_por')->nullable()->constrained('docente');
            $table->timestamps();
        });

        Schema::create('item_tienda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_clase')->constrained('clase');
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->integer('precio_glimmer');
            $table->enum('tipo', ['gear', 'shader', 'emblem', 'emote', 'consumible', 'privilegio']);
            $table->string('imagen_url')->nullable();
            $table->boolean('disponible')->default(true);
            $table->boolean('cantidad_limitada')->default(false);
            $table->integer('cantidad_disponible')->nullable();
            $table->json('requisitos')->nullable(); // {nivel_min: 10, clase_rpg: "titan"}
            $table->timestamps();
        });

        // ===== INTERACCIONES SOCIALES =====
        Schema::create('emote', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);
            $table->text('descripcion');
            $table->string('animacion_css', 100)->nullable(); // Clase CSS para animación
            $table->string('icono_url')->nullable();
            $table->integer('nivel_requerido')->default(1);
            $table->integer('precio_tienda')->nullable();
            $table->enum('rareza', ['comun', 'raro', 'epico', 'exotico'])->default('comun');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('personaje_emote', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_personaje')->constrained('personaje');
            $table->foreignId('id_emote')->constrained('emote');
            $table->boolean('equipado')->default(false);
            $table->integer('slot_equipado')->nullable(); // 1,2,3,4
            $table->timestamp('fecha_desbloqueado')->useCurrent();
            $table->timestamps();
            
            $table->unique(['id_personaje', 'id_emote']);
        });

        Schema::create('interaccion_social', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_personaje_origen')->constrained('personaje');
            $table->foreignId('id_personaje_destino')->constrained('personaje');
            $table->enum('tipo_interaccion', ['kudos', 'ayuda', 'colaboracion', 'emote', 'revive']);
            $table->bigInteger('id_contexto')->nullable(); // ID de actividad, clase, etc.
            $table->string('tipo_contexto')->nullable(); // 'actividad', 'clase', 'evento'
            $table->text('mensaje')->nullable();
            $table->timestamp('fecha_interaccion')->useCurrent();
            $table->timestamps();
        });

        // ===== NOTIFICACIONES =====
        Schema::create('notificacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuario');
            $table->string('titulo', 100);
            $table->text('mensaje');
            $table->enum('tipo', ['info', 'success', 'warning', 'error', 'achievement'])->default('info');
            $table->enum('categoria', ['sistema', 'academico', 'social', 'clan', 'evento'])->default('sistema');
            $table->boolean('leida')->default(false);
            $table->json('datos')->nullable();
            $table->string('accion_url')->nullable();
            $table->string('icono_url')->nullable();
            $table->timestamps();
        });

        // ===== CONFIGURACIÓN DEL SISTEMA =====
        Schema::create('configuracion_clase', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_clase')->constrained('clase');
            $table->boolean('permitir_personajes')->default(true);
            $table->boolean('permitir_tienda')->default(true);
            $table->boolean('permitir_clanes')->default(true);
            $table->boolean('sistema_poderes_activo')->default(true);
            $table->boolean('sistema_badges_activo')->default(true);
            $table->boolean('economia_virtual_activa')->default(true);
            $table->boolean('eventos_especiales_activos')->default(true);
            $table->json('multiplicadores_xp')->nullable(); // {base: 1.0, evento: 1.5}
            $table->json('limites_recursos')->nullable(); // Límites de salud/energía/luz
            $table->json('cooldowns_personalizados')->nullable();
            $table->timestamps();
        });

        // ===== ESTADÍSTICAS Y REPORTES =====
        Schema::create('estadistica_clase', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_clase')->constrained('clase');
            $table->decimal('promedio_nivel', 5, 2)->nullable();
            $table->decimal('promedio_participacion', 5, 2)->nullable();
            $table->decimal('promedio_power_level', 8, 2)->nullable();
            $table->json('distribucion_niveles')->nullable();
            $table->json('distribucion_clases_rpg')->nullable();
            $table->integer('total_personajes_activos')->default(0);
            $table->date('fecha_calculo');
            $table->timestamps();
        });

        Schema::create('estadistica_personaje', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_personaje')->constrained('personaje');
            $table->integer('actividades_completadas')->default(0);
            $table->integer('eventos_participados')->default(0);
            $table->integer('poderes_usados')->default(0);
            $table->integer('interacciones_sociales')->default(0);
            $table->bigInteger('experiencia_total_ganada')->default(0);
            $table->integer('glimmer_total_ganado')->default(0);
            $table->integer('dias_consecutivos_activo')->default(0);
            $table->decimal('promedio_notas', 5, 2)->nullable();
            $table->integer('veces_revivido')->default(0);
            $table->integer('veces_revivio_otros')->default(0);
            $table->date('fecha_calculo');
            $table->timestamps();
        });

        // ===== SISTEMA DE SESIONES DE CLASE =====
        Schema::create('sesion_clase', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_clase')->constrained('clase');
            $table->timestamp('fecha_inicio');
            $table->timestamp('fecha_fin')->nullable();
            $table->boolean('activa')->default(true);
            $table->integer('duracion_planificada')->nullable(); // en minutos
            $table->text('tema_sesion')->nullable();
            $table->text('notas_sesion')->nullable();
            $table->json('objetivos_sesion')->nullable();
            $table->boolean('permite_poderes')->default(true); // Control de poderes por sesión
            $table->timestamps();
        });

        // Relación de asistencia con sesiones
        Schema::table('asistencia', function (Blueprint $table) {
            $table->foreignId('id_sesion')->nullable()->constrained('sesion_clase');
        });

        // ===== SISTEMA DE MISIONES (QUEST SYSTEM) =====
        Schema::create('mision', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_clase')->constrained('clase');
            $table->string('titulo', 100);
            $table->text('descripcion');
            $table->text('lore_historia')->nullable(); // Narrativa de la misión
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_fin')->nullable();
            $table->enum('tipo_mision', ['principal', 'secundaria', 'diaria', 'semanal', 'especial']);
            $table->enum('dificultad', ['facil', 'normal', 'heroico', 'legendario'])->default('normal');
            $table->integer('poder_requerido')->default(750);
            $table->bigInteger('experiencia_bonus')->default(0);
            $table->integer('glimmer_bonus')->default(0);
            $table->json('recompensas_especiales')->nullable();
            $table->boolean('requiere_fireteam')->default(false);
            $table->integer('min_participantes')->default(1);
            $table->integer('max_participantes')->default(6);
            $table->boolean('activa')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        // Relación de actividades con misiones
        Schema::table('actividad', function (Blueprint $table) {
            $table->foreignId('id_mision')->nullable()->constrained('mision');
        });

        Schema::create('progreso_mision', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_mision')->constrained('mision');
            $table->foreignId('id_personaje')->constrained('personaje');
            $table->integer('actividades_completadas')->default(0);
            $table->integer('total_actividades_requeridas')->default(1);
            $table->decimal('porcentaje_progreso', 5, 2)->default(0);
            $table->boolean('completada')->default(false);
            $table->timestamp('fecha_inicio')->useCurrent();
            $table->timestamp('fecha_completada')->nullable();
            $table->boolean('recompensas_reclamadas')->default(false);
            $table->json('progreso_detallado')->nullable(); // Objetivos específicos
            $table->timestamps();
            
            $table->unique(['id_mision', 'id_personaje']);
        });

        // ===== TABLAS DE ESTADOS AUXILIARES =====
        Schema::create('estado_tarea', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique(); // 'pendiente', 'entregada', 'evaluada', 'tarde'
            $table->text('descripcion')->nullable();
            $table->string('color_hex', 7)->default('#6B7280'); // Color para UI
            $table->timestamps();
        });

        // ===== LOGS DEL SISTEMA =====
        Schema::create('log_uso_poder', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_personaje')->constrained('personaje');
            $table->foreignId('id_poder')->constrained('poder_habilidad');
            $table->foreignId('id_personaje_objetivo')->nullable()->constrained('personaje'); // Para poderes dirigidos
            $table->string('contexto')->nullable(); // 'clase', 'actividad', 'evento'
            $table->bigInteger('contexto_id')->nullable();
            $table->json('parametros_uso')->nullable(); // Detalles específicos del uso
            $table->boolean('exitoso')->default(true);
            $table->text('resultado')->nullable();
            $table->timestamp('fecha_uso')->useCurrent();
            $table->timestamps();
        });

        Schema::create('log_experiencia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_personaje')->constrained('personaje');
            $table->bigInteger('experiencia_anterior');
            $table->bigInteger('experiencia_ganada');
            $table->bigInteger('experiencia_posterior');
            $table->integer('nivel_anterior');
            $table->integer('nivel_posterior');
            $table->string('fuente')->nullable(); // 'actividad', 'comportamiento', 'bonus'
            $table->bigInteger('fuente_id')->nullable();
            $table->text('descripcion')->nullable();
            $table->boolean('subio_nivel')->default(false);
            $table->timestamp('fecha_ganancia')->useCurrent();
            $table->timestamps();
        });

        // ===== TABLAS DE CONFIGURACIÓN GLOBAL =====
        Schema::create('configuracion_global', function (Blueprint $table) {
            $table->id();
            $table->string('clave', 100)->unique();
            $table->text('valor');
            $table->string('tipo', 20)->default('string'); // 'string', 'integer', 'boolean', 'json'
            $table->text('descripcion')->nullable();
            $table->string('categoria', 50)->default('general');
            $table->boolean('editable')->default(true);
            $table->timestamps();
        });

        // ===== TOKENS Y CÓDIGOS DE ACCESO =====
        Schema::create('codigo_acceso_temporal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_clase')->constrained('clase');
            $table->string('codigo', 10)->unique();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_expiracion');
            $table->integer('usos_maximos')->default(1);
            $table->integer('usos_actuales')->default(0);
            $table->boolean('activo')->default(true);
            $table->json('restricciones')->nullable(); // Restricciones adicionales
            $table->timestamps();
        });
    }

    public function down()
    {
        // Eliminar en orden inverso para respetar las llaves foráneas
        Schema::dropIfExists('codigo_acceso_temporal');
        Schema::dropIfExists('configuracion_global');
        Schema::dropIfExists('log_experiencia');
        Schema::dropIfExists('log_uso_poder');
        Schema::dropIfExists('estado_tarea');
        Schema::dropIfExists('progreso_mision');
        
        // Eliminar columna agregada a actividad
        Schema::table('actividad', function (Blueprint $table) {
            $table->dropForeign(['id_mision']);
            $table->dropColumn('id_mision');
        });
        
        Schema::dropIfExists('mision');
        
        // Eliminar columna agregada a asistencia
        Schema::table('asistencia', function (Blueprint $table) {
            $table->dropForeign(['id_sesion']);
            $table->dropColumn('id_sesion');
        });
        
        Schema::dropIfExists('sesion_clase');
        Schema::dropIfExists('estadistica_personaje');
        Schema::dropIfExists('estadistica_clase');
        Schema::dropIfExists('configuracion_clase');
        Schema::dropIfExists('interaccion_social');
        Schema::dropIfExists('personaje_emote');
        Schema::dropIfExists('emote');
        Schema::dropIfExists('item_tienda');
        Schema::dropIfExists('transaccion_glimmer');
        Schema::dropIfExists('asistencia');
        Schema::dropIfExists('registro_comportamiento');
        Schema::dropIfExists('tipo_comportamiento');
        Schema::dropIfExists('personaje_badge');
        Schema::dropIfExists('badge');
        Schema::dropIfExists('personaje_titulo');
        Schema::dropIfExists('titulo_rango');
        Schema::dropIfExists('ranking_clase');
        Schema::dropIfExists('participacion_evento');
        Schema::dropIfExists('evento_especial');
        Schema::dropIfExists('entrega_actividad');
        Schema::dropIfExists('participacion_actividad');
        Schema::dropIfExists('actividad');
        Schema::dropIfExists('tipo_actividad');
        Schema::dropIfExists('miembro_clan');
        Schema::dropIfExists('clan_clase');
        Schema::dropIfExists('inventario_personaje');
        Schema::dropIfExists('item_equipamiento');
        Schema::dropIfExists('personaje_poder');
        Schema::dropIfExists('personaje');
        Schema::dropIfExists('inscripcion_clase');
        Schema::dropIfExists('clase');
        Schema::dropIfExists('nivel_experiencia');
        Schema::dropIfExists('poder_habilidad');
        Schema::dropIfExists('clase_rpg');
        Schema::dropIfExists('docente');
        Schema::dropIfExists('estudiante');
        Schema::dropIfExists('notificacion');
        Schema::dropIfExists('usuario');
        Schema::dropIfExists('estado_usuario');
        Schema::dropIfExists('tipo_usuario');
    }
};