</style>
</head>
<body x-data="fireteamData()">
    <div class="fireteam-container">
        <!-- Header Section -->
        <div class="header-section holo-panel">
            <div class="class-info">
                <h1 class="class-title">MATEMÁTICAS 5TO A</h1>
                <div class="professor-name">Prof. Carlos Mendoza</div>
            </div>
            <div class="fireteam-status">
                <div class="online-count">
                    <span>●</span>
                    <span x-text="onlineTeammates + ' ONLINE'"></span>
                </div>
                <div style="color: var(--destiny-gold); font-family: 'Orbitron', monospace;">
                    FIRETEAM ACTIVO
                </div>
            </div>
        </div>

        <!-- Character Section -->
        <div class="character-section holo-panel">
            <h3 style="font-family: 'Orbitron', monospace; color: var(--destiny-gold); margin-bottom: 1rem; text-align: center;">
                MI GUARDIÁN
            </h3>
            
            <div class="character-avatar">
                <div class="avatar-circle">AG</div>
                <div class="character-name" x-text="character.nombre_personaje"></div>
                <div class="character-class" x-text="character.clase_rpg"></div>
            </div>

            <div class="power-level" x-text="character.power_level"></div>

            <div class="level-progress">
                <div class="level-ring">
                    <div class="level-number" x-text="character.nivel"></div>
                </div>
            </div>

            <div style="text-align: center; margin-bottom: 1rem;">
                <div style="color: var(--destiny-cyan); font-size: 0.9rem;">EXPERIENCIA</div>
                <div style="font-family: 'Orbitron', monospace; font-weight: 700; color: var(--destiny-gold);">
                    <span x-text="character.experiencia_actual.toLocaleString()"></span> / 
                    <span x-text="character.experiencia_siguiente.toLocaleString()"></span>
                </div>
            </div>

            <div class="resources-grid">
                <div class="resource-bar">
                    <div class="resource-header">
                        <span class="resource-label">SALUD</span>
                        <span class="resource-value" 
                              :style="character.salud_actual < 30 ? 'color: var(--destiny-danger)' : 'color: var(--destiny-success)'"
                              x-text="character.salud_actual + '/' + character.salud_maxima">
                        </span>
                    </div>
                    <div class="progress-container">
                        <div class="progress-fill health-fill" 
                             :class="{ 'critical': character.salud_actual < 30 }"
                             :style="'width: ' + (character.salud_actual / character.salud_maxima * 100) + '%'">
                        </div>
                    </div>
                </div>

                <div class="resource-bar">
                    <div class="resource-header">
                        <span class="resource-label">ENERGÍA</span>
                        <span class="resource-value" style="color: var(--destiny-orange);" 
                              x-text="character.energia_actual + '/' + character.energia_maxima">
                        </span>
                    </div>
                    <div class="progress-container">
                        <div class="progress-fill energy-fill" 
                             :style="'width: ' + (character.energia_actual / character.energia_maxima * 100) + '%'">
                        </div>
                    </div>
                </div>

                <div class="resource-bar">
                    <div class="resource-header">
                        <span class="resource-label">LUZ</span>
                        <span class="resource-value" style="color: var(--destiny-purple);" 
                              x-text="character.luz_actual + '/' + character.luz_maxima">
                        </span>
                    </div>
                    <div class="progress-container">
                        <div class="progress-fill light-fill" 
                             :style="'width: ' + (character.luz_actual / character.luz_maxima * 100) + '%'">
                        </div>
                    </div>
                </div>
            </div>

            <div class="abilities-section">
                <h4 style="color: var(--destiny-cyan); margin-bottom: 0.5rem;">HABILIDADES</h4>
                <div class="abilities-grid">
                    <template x-for="ability in abilities" :key="ability.name">
                        <div class="ability-btn" 
                             :class="{ 'cooldown': ability.cooldown > 0 }"
                             @click="useAbility(ability)">
                            <div class="ability-icon" x-text="ability.icon"></div>
                            <div class="ability-name" x-text="ability.name"></div>
                            <div x-show="ability.cooldown > 0" class="cooldown-timer" x-text="ability.cooldown"></div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Fireteam Section -->
        <div class="fireteam-section holo-panel">
            <h3 style="font-family: 'Orbitron', monospace; color: var(--destiny-gold); margin-bottom: 1rem;">
                MI FIRETEAM
            </h3>

            <template x-for="teammate in teammates" :key="teammate.id">
                <div class="teammate-card" 
                     :class="{ 'critical': teammate.estado_salud === 'critico' }">
                    <div :class="teammate.online ? 'online-indicator' : 'offline-indicator'"></div>
                    
                    <div class="teammate-avatar" x-text="teammate.nombre.charAt(0)"></div>
                    
                    <div class="teammate-info">
                        <div class="teammate-name" x-text="teammate.personaje"></div>
                        <div class="teammate-level" x-text="teammate.clase_rpg + ' • Nv. ' + teammate.nivel"></div>
                        <div class="teammate-health">
                            <div class="progress-fill" 
                                 :class="teammate.estado_salud === 'critico' ? 'health-fill critical' : 'health-fill'"
                                 :style="'width: ' + teammate.salud_porcentaje + '%'">
                            </div>
                        </div>
                    </div>

                    <div class="teammate-actions">
                        <button class="action-btn" 
                                :disabled="teammate.estado_salud !== 'critico'"
                                @click="reviveTeammate(teammate)"
                                title="Revivir">
                            ⚡
                        </button>
                        <button class="action-btn" 
                                @click="buffTeammate(teammate)"
                                title="Dar Buff">
                            ↗️
                        </button>
                        <button class="action-btn" 
                                @click="chatWithTeammate(teammate)"
                                title="Chat">
                            💬
                        </button>
                    </div>
                </div>
            </template>

            <div style="margin-top: 1rem; padding: 1rem; background: var(--destiny-bg-primary); border-radius: 8px;">
                <h4 style="color: var(--destiny-cyan); margin-bottom: 0.5rem;">CHAT DEL FIRETEAM</h4>
                <div style="height: 100px; overflow-y: auto; margin-bottom: 0.5rem; font-size: 0.9rem;">
                    <div style="color: var(--destiny-gold);">Ana: ¡Necesito ayuda con el ejercicio 5!</div>
                    <div style="color: var(--destiny-purple);">Luis: Te ayudo, un momento</div>
                    <div style="color: var(--destiny-cyan);">Sofia: Excelente trabajo equipo 💪</div>
                </div>
                <input type="text" 
                       placeholder="Escribe un mensaje..."
                       style="width: 100%; padding: 0.5rem; background: var(--destiny-bg-secondary); border: 1px solid var(--destiny-cyan); border-radius: 4px; color: var(--destiny-cyan);">
            </div>
        </div>

        <!-- Activities Section -->
        <div class="activities-section holo-panel">
            <h3 style="font-family: 'Orbitron', monospace; color: var(--destiny-gold); margin-bottom: 1rem;">
                MISIONES ACTIVAS
            </h3>

            <template x-for="activity in activities" :key="activity.id">
                <div class="activity-card">
                    <div class="activity-header">
                        <div class="activity-title" x-text="activity.titulo"></div>
                        <div class="activity-type" x-text="activity.tipo"></div>
                    </div>
                    
                    <div class="activity-deadline">
                        📅 <span x-text="activity.dias_restantes + ' días restantes'"></span>
                    </div>
                    
                    <div class="activity-reward">
                        ⭐ <span x-text="activity.xp_recompensa + ' XP'"></span>
                    </div>
                    
                    <div style="margin-top: 0.5rem;">
                        <div style="height: 4px; background: var(--destiny-bg-primary); border-radius: 2px; overflow: hidden;">
                            <div style="height: 100%; background: linear-gradient(90deg, var(--destiny-orange), var(--destiny-gold)); width: 30%;"></div>
                        </div>
                        <div style="font-size: 0.8rem; color: var(--destiny-cyan); margin-top: 0.25rem;">
                            Progreso: 30%
                        </div>
                    </div>
                </div>
            </template>

            <div style="margin-top: 1rem; padding: 1rem; background: var(--destiny-bg-primary); border-radius: 8px; border: 1px solid var(--destiny-purple);">
                <h4 style="color: var(--destiny-purple); margin-bottom: 0.5rem;">📅 PRÓXIMOS EVENTOS</h4>
                <div style="font-size: 0.9rem;">
                    <div style="margin-bottom: 0.25rem;">• Examen Final - 15 Jul 2025</div>
                    <div style="margin-bottom: 0.25rem;">• Proyecto Grupal - 20 Jul 2025</div>
                    <div>• Competencia Inter-clases - 25 Jul 2025</div>
                </div>
            </div>
        </div>

        <!-- Ranking Section -->
        <div class="ranking-section holo-panel">
            <h3 style="font-family: 'Orbitron', monospace; color: var(--destiny-gold); margin-bottom: 1rem;">
                RANKING DEL FIRETEAM
            </h3>

            <template x-for="player in ranking" :key="player.posicion">
                <div class="ranking-item" :class="{ 'me': player.es_yo }">
                    <div class="ranking-position" x-text="player.posicion"></div>
                    <div class="ranking-info">
                        <div class="ranking-name" x-text="player.nombre + (player.es_yo ? ' (TÚ)' : '')"></div>
                        <div class="ranking-stats">
                            Nv. <span x-text="player.nivel"></span> • 
                            <span x-text="player.xp.toLocaleString()"></span> XP
                        </div>
                    </div>
                </div>
            </template>

            <div style="margin-top: 1rem; padding: 1rem; background: var(--destiny-bg-primary); border-radius: 8px;">
                <h4 style="color: var(--destiny-cyan); margin-bottom: 0.5rem;">📊 MIS ESTADÍSTICAS</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; font-size: 0.9rem;">
                    <div>Actividades Completadas: <strong style="color: var(--destiny-gold);">12</strong></div>
                    <div>Racha Actual: <strong style="color: var(--destiny-success);">5 días</strong></div>
                    <div>Colaboraciones: <strong style="color: var(--destiny-purple);">8</strong></div>
                    <div>Puntos de Comportamiento: <strong style="color: var(--destiny-cyan);">+45</strong></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fireteamData() {
            return {
                character: {
                    nombre_personaje: 'Ana el Guardián',
                    clase_rpg: 'Titán',
                    nivel: 18,
                    experiencia_actual: 15750,
                    experiencia_siguiente: 18000,
                    salud_actual: 85,
                    salud_maxima: 100,
                    energia_actual: 7,
                    energia_maxima: 10,
                    luz_actual: 5,
                    luz_maxima: 8,
                    power_level: 894
                },

                abilities: [
                    { name: 'Curar', icon: '❤️', cooldown: 0 },
                    { name: 'Escudo', icon: '🛡️', cooldown: 45 },
                    { name: 'Impulso', icon: '⚡', cooldown: 0 }
                ],

                teammates: [
                    {
                        id: 1,
                        nombre: 'Luis Rodríguez',
                        personaje: 'Luis el Cazador',
                        clase_rpg: 'Cazador',
                        nivel: 16,
                        estado_salud: 'critico',
                        salud_porcentaje: 25,
                        online: true
                    },
                    {
                        id: 2,
                        nombre: 'Sofia Herrera',
                        personaje: 'Sofia la Hechicera',
                        clase_rpg: 'Hechicero',
                        nivel: 22,
                        estado_salud: 'normal',
                        salud_porcentaje: 95,
                        online: true
                    },
                    {
                        id: 3,
                        nombre: 'Gabriel Torres',
                        personaje: 'Gabriel el Titán',
                        clase_rpg: 'Titán',
                        nivel: 20,
                        estado_salud: 'normal',
                        salud_porcentaje: 78,
                        online: false
                    }
                ],

                activities: [
                    {
                        id: 1,
                        titulo: 'Examen Unidad 3',
                        tipo: 'evaluacion',
                        fecha_limite: '2025-07-10',
                        dias_restantes: 8,
                        xp_recompensa: 500,
                        estado: 'pendiente'
                    },
                    {
                        id: 2,
                        titulo: 'Tarea: Ecuaciones',
                        tipo: 'tarea',
                        fecha_limite: '2025-07-05',
                        dias_restantes: 3,
                        xp_recompensa: 150,
                        estado: 'en_progreso'
                    },
                    {
                        id: 3,
                        titulo: 'Proyecto Grupal',
                        tipo: 'proyecto',
                        fecha_limite: '2025-07-20',
                        dias_restantes: 18,
                        xp_recompensa: 750,
                        estado: 'pendiente'
                    }
                ],

                ranking: [
                    { posicion: 1, nombre: 'Sofia Herrera', nivel: 28, xp: 45000 },
                    { posicion: 2, nombre: 'Gabriel Torres', nivel: 20, xp: 28000 },
                    { posicion: 3, nombre: 'Ana García', nivel: 18, xp: 15750, es_yo: true },
                    { posicion: 4, nombre: 'Luis Rodríguez', nivel: 16, xp: 12500 },
                    { posicion: 5, nombre: 'María López', nivel: 15, xp: 11250 }
                ],

                onlineTeammates: 3,

                init() {
                    // Simular cooldowns de habilidades
                    setInterval(() => {
                        this.abilities.forEach(ability => {
                            if (ability.cooldown > 0) {
                                ability.cooldown--;
                            }
                        });
                    }, 1000);
                },

                useAbility(ability) {
                    if (ability.cooldown > 0) return;
                    
                    alert(`Usando habilidad: ${ability.name}`);
                    
                    // Simular cooldown
                    switch(ability.name) {
                        case 'Curar':
                            ability.cooldown = 30;
                            this.character.salud_actual = Math.min(this.character.salud_maxima, this.character.salud_actual + 20);
                            break;
                        case 'Escudo':
                            ability.cooldown = 60;
                            break;
                        case 'Impulso':
                            ability.cooldown = 15;
                            break;
                    }
                },

                reviveTeammate(teammate) {
                    if (teammate.estado_salud !== 'critico') return;
                    
                    if (this.character.energia_actual >= 2) {
                        teammate.estado_salud = 'normal';
                        teammate.salud_porcentaje = 50;
                        this.character.energia_actual -= 2;
                        alert(`¡Has revivido a ${teammate.nombre}!`);
                    } else {
                        alert('No tienes suficiente energía para revivir');
                    }
                },

                buffTeammate(teammate) {
                    if (this.character.luz_actual >= 1) {
                        this.character.luz_actual -= 1;
                        alert(`¡Has dado un buff a ${teammate.nombre}!`);
                    } else {
                        alert('No tienes suficiente luz para dar buff');
                    }
                },

                chatWithTeammate(teammate) {
                    alert(`Iniciando chat privado con ${teammate.nombre}`);
                }
            }
        }
    </script>
</body>
</html><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fireteam - Matemáticas 5to A</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js" defer></script>
    <style>
        :root {
            --destiny-bg-primary: #1B1C29;
            --destiny-bg-secondary: #2E2F3D;
            --destiny-bg-tertiary: #3A3D53;
            --destiny-gold: #C7B88A;
            --destiny-cyan: #6EC1E4;
            --destiny-purple: #B6A1E4;
            --destiny-orange: #FF7B00;
            --destiny-success: #7FBBA3;
            --destiny-danger: #E74C3C;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background: var(--destiny-bg-primary);
            color: var(--destiny-cyan);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .fireteam-container {
            min-height: 100vh;
            display: grid;
            grid-template-areas: 
                "header header header"
                "character activities ranking"
                "fireteam activities ranking";
            grid-template-columns: 400px 1fr 350px;
            grid-template-rows: auto 1fr 1fr;
            gap: 1rem;
            padding: 1rem;
        }

        .holo-panel {
            background: linear-gradient(135deg, var(--destiny-bg-secondary), var(--destiny-bg-tertiary));
            border: 1px solid var(--destiny-cyan);
            border-radius: 8px;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .holo-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--destiny-cyan), transparent);
            animation: scan 3s linear infinite;
        }

        @keyframes scan {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .header-section {
            grid-area: header;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .class-info {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .class-title {
            font-family: 'Orbitron', monospace;
            font-size: 2rem;
            font-weight: 900;
            color: var(--destiny-gold);
            text-shadow: 0 0 20px var(--destiny-gold);
        }

        .professor-name {
            font-size: 1.1rem;
            color: var(--destiny-cyan);
            opacity: 0.9;
        }

        .fireteam-status {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-family: 'Orbitron', monospace;
            font-weight: 600;
        }

        .online-count {
            background: var(--destiny-success);
            color: var(--destiny-bg-primary);
            padding: 0.5rem 1rem;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .character-section {
            grid-area: character;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .character-avatar {
            position: relative;
            text-align: center;
            margin-bottom: 1rem;
        }

        .avatar-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: radial-gradient(circle, var(--destiny-gold), var(--destiny-orange));
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Orbitron', monospace;
            font-size: 3rem;
            font-weight: 900;
            color: var(--destiny-bg-primary);
            border: 3px solid var(--destiny-cyan);
            box-shadow: 0 0 30px var(--destiny-cyan);
            position: relative;
        }

        .character-name {
            font-family: 'Orbitron', monospace;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--destiny-gold);
            margin-bottom: 0.5rem;
        }

        .character-class {
            background: var(--destiny-purple);
            color: var(--destiny-bg-primary);
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .power-level {
            font-family: 'Orbitron', monospace;
            font-size: 2rem;
            font-weight: 900;
            color: var(--destiny-gold);
            text-align: center;
            text-shadow: 0 0 20px var(--destiny-gold);
            margin-bottom: 1rem;
        }

        .level-progress {
            position: relative;
            width: 100px;
            height: 100px;
            margin: 0 auto 1rem;
        }

        .level-ring {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: conic-gradient(var(--destiny-cyan) 0deg, var(--destiny-cyan) 70%, var(--destiny-bg-primary) 70%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .level-ring::before {
            content: '';
            width: 70%;
            height: 70%;
            background: var(--destiny-bg-secondary);
            border-radius: 50%;
            position: absolute;
        }

        .level-number {
            font-family: 'Orbitron', monospace;
            font-size: 1.5rem;
            font-weight: 900;
            color: var(--destiny-gold);
            z-index: 1;
        }

        .resources-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .resource-bar {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .resource-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .resource-label {
            font-weight: 600;
            color: var(--destiny-cyan);
        }

        .resource-value {
            font-family: 'Orbitron', monospace;
            font-weight: 700;
        }

        .progress-container {
            height: 12px;
            background: var(--destiny-bg-primary);
            border-radius: 6px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            transition: width 0.3s ease;
            position: relative;
        }

        .health-fill {
            background: linear-gradient(90deg, var(--destiny-success), var(--destiny-cyan));
        }

        .health-fill.critical {
            background: linear-gradient(90deg, var(--destiny-danger), var(--destiny-orange));
        }

        .energy-fill {
            background: linear-gradient(90deg, var(--destiny-orange), var(--destiny-gold));
        }

        .light-fill {
            background: linear-gradient(90deg, var(--destiny-purple), var(--destiny-cyan));
        }

        .abilities-section {
            margin-top: 1rem;
        }

        .abilities-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .ability-btn {
            background: var(--destiny-bg-secondary);
            border: 1px solid var(--destiny-cyan);
            padding: 1rem 0.5rem;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .ability-btn:hover:not(.cooldown) {
            background: var(--destiny-cyan);
            color: var(--destiny-bg-primary);
            transform: translateY(-2px);
        }

        .ability-btn.cooldown {
            opacity: 0.5;
            cursor: not-allowed;
            border-color: #666;
        }

        .ability-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .ability-name {
            font-size: 0.8rem;
            font-weight: 600;
        }

        .cooldown-timer {
            position: absolute;
            top: 0.25rem;
            right: 0.25rem;
            background: var(--destiny-danger);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .fireteam-section {
            grid-area: fireteam;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .teammate-card {
            background: var(--destiny-bg-secondary);
            border: 1px solid var(--destiny-cyan);
            padding: 1rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .teammate-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(110, 193, 228, 0.3);
        }

        .teammate-card.critical {
            border-color: var(--destiny-danger);
            background: linear-gradient(135deg, var(--destiny-bg-secondary), rgba(231, 76, 60, 0.1));
        }

        .teammate-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--destiny-purple);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--destiny-bg-primary);
        }

        .teammate-info {
            flex: 1;
        }

        .teammate-name {
            font-weight: 600;
            color: var(--destiny-gold);
            margin-bottom: 0.25rem;
        }

        .teammate-level {
            font-size: 0.9rem;
            color: var(--destiny-cyan);
            margin-bottom: 0.5rem;
        }

        .teammate-health {
            height: 4px;
            background: var(--destiny-bg-primary);
            border-radius: 2px;
            overflow: hidden;
        }

        .teammate-actions {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            width: 30px;
            height: 30px;
            border: 1px solid var(--destiny-cyan);
            background: var(--destiny-bg-primary);
            color: var(--destiny-cyan);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: var(--destiny-cyan);
            color: var(--destiny-bg-primary);
        }

        .online-indicator {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--destiny-success);
            box-shadow: 0 0 5px var(--destiny-success);
        }

        .offline-indicator {
            background: #666;
            box-shadow: none;
        }

        .activities-section {
            grid-area: activities;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            max-height: 80vh;
            overflow-y: auto;
        }

        .activity-card {
            background: var(--destiny-bg-secondary);
            border: 1px solid var(--destiny-cyan);
            padding: 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .activity-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(110, 193, 228, 0.3);
        }

        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .activity-title {
            font-weight: 600;
            color: var(--destiny-gold);
        }

        .activity-type {
            background: var(--destiny-purple);
            color: var(--destiny-bg-primary);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .activity-deadline {
            color: var(--destiny-orange);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .activity-reward {
            color: var(--destiny-gold);
            font-family: 'Orbitron', monospace;
            font-weight: 700;
        }

        .ranking-section {
            grid-area: ranking;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .ranking-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem;
            background: var(--destiny-bg-secondary);
            border-radius: 8px;
            border: 1px solid var(--destiny-cyan);
        }

        .ranking-item.me {
            border-color: var(--destiny-gold);
            background: linear-gradient(135deg, var(--destiny-bg-secondary), rgba(199, 184, 138, 0.1));
        }

        .ranking-position {
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            font-size: 1.2rem;
            color: var(--destiny-gold);
            min-width: 30px;
        }

        .ranking-info {
            flex: 1;
        }

        .ranking-name {
            font-weight: 600;
            color: var(--destiny-cyan);
            margin-bottom: 0.25rem;
        }

        .ranking-stats {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        @media (max-width: 1200px) {
            .fireteam-container {
                grid-template-areas: 
                    "header"
                    "character"
                    "activities"
                    "fireteam"
                    "ranking";
                grid-template-columns: 1fr;
                grid-template-rows: auto auto auto auto auto;
            }
        }

        @media (max-width: 768px) {
            .abilities-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .class-title {
                font-size: 1.5rem;
            }
            
            .power-level {
                font-size: 1.5rem;
            }
        }