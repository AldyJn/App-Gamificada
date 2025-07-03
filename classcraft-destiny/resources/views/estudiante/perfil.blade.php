{{-- resources/views/estudiante/perfil.blade.php --}}
@extends('layouts.destiny')

@section('title', 'Perfil - Hoja de Guardián')

@section('content')
<div class="destiny-container">
    {{-- Header del Guardián --}}
    <div class="guardian-header">
        <div class="guardian-banner">
            <div class="guardian-avatar-large">
                <img src="{{ $perfil->avatar ?? asset('images/default-avatar.png') }}" alt="Avatar del Guardián">
                <div class="power-level-badge">{{ $perfil->power_level }}</div>
                <div class="class-emblem-large {{ $perfil->clase_rpg }}"></div>
            </div>
            
            <div class="guardian-identity">
                <h1>{{ $perfil->nombre }} {{ $perfil->apellido }}</h1>
                <div class="guardian-title">{{ $perfil->titulo_activo ?? 'Guardián Novato' }}</div>
                <div class="guardian-clan">
                    <i class="fas fa-users"></i>
                    {{ $perfil->clan ?? 'Sin Clan' }}
                </div>
                <div class="guardian-registration">
                    Guardián desde {{ $perfil->fecha_registro->format('d/m/Y') }}
                </div>
            </div>
            
            <div class="guardian-stats-overview">
                <div class="stat-circle">
                    <div class="stat-value">{{ $perfil->nivel }}</div>
                    <div class="stat-label">Nivel</div>
                </div>
                <div class="stat-circle">
                    <div class="stat-value">{{ number_format($perfil->experiencia_total) }}</div>
                    <div class="stat-label">XP Total</div>
                </div>
                <div class="stat-circle">
                    <div class="stat-value">{{ $perfil->ranking_mejor ?? '---' }}</div>
                    <div class="stat-label">Mejor Ranking</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Selector de Personajes --}}
    @if(count($personajes) > 1)
    <div class="character-selector">
        <h2>Seleccionar Personaje</h2>
        <div class="characters-grid">
            @foreach($personajes as $personaje)
            <div class="character-card {{ $personaje->id === $personaje_activo->id ? 'active' : '' }}" 
                 onclick="switchCharacter({{ $personaje->id }})">
                <div class="character-icon">
                    <img src="{{ $personaje->avatar }}" alt="{{ $personaje->clase_rpg }}">
                    <div class="class-indicator {{ $personaje->clase_rpg }}"></div>
                </div>
                <div class="character-info">
                    <h4>{{ ucfirst($personaje->clase_rpg) }}</h4>
                    <div class="character-level">Nivel {{ $personaje->nivel }}</div>
                    <div class="character-power">{{ $personaje->power_level }} PL</div>
                </div>
                <div class="play-time">
                    {{ $personaje->tiempo_jugado }} hrs
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Contenido Principal del Perfil --}}
    <div class="profile-content">
        <div class="profile-left">
            {{-- Stats Detallados --}}
            <div class="detailed-stats">
                <h2>Estadísticas del Guardián</h2>
                <div class="stats-container">
                    {{-- Radar Chart --}}
                    <div class="radar-chart-container">
                        <canvas id="statsRadarChart"></canvas>
                    </div>
                    
                    {{-- Stats List --}}
                    <div class="stats-list">
                        <div class="stat-item">
                            <div class="stat-icon"><i class="fas fa-graduation-cap"></i></div>
                            <div class="stat-info">
                                <div class="stat-name">Conocimiento</div>
                                <div class="stat-bar">
                                    <div class="stat-fill" style="width: {{ $stats->conocimiento }}%"></div>
                                </div>
                                <div class="stat-value">{{ $stats->conocimiento }}/100</div>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-icon"><i class="fas fa-lightbulb"></i></div>
                            <div class="stat-info">
                                <div class="stat-name">Creatividad</div>
                                <div class="stat-bar">
                                    <div class="stat-fill" style="width: {{ $stats->creatividad }}%"></div>
                                </div>
                                <div class="stat-value">{{ $stats->creatividad }}/100</div>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-icon"><i class="fas fa-users"></i></div>
                            <div class="stat-info">
                                <div class="stat-name">Colaboración</div>
                                <div class="stat-bar">
                                    <div class="stat-fill" style="width: {{ $stats->colaboracion }}%"></div>
                                </div>
                                <div class="stat-value">{{ $stats->colaboracion }}/100</div>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-icon"><i class="fas fa-clock"></i></div>
                            <div class="stat-info">
                                <div class="stat-name">Puntualidad</div>
                                <div class="stat-bar">
                                    <div class="stat-fill" style="width: {{ $stats->puntualidad }}%"></div>
                                </div>
                                <div class="stat-value">{{ $stats->puntualidad }}/100</div>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-icon"><i class="fas fa-trophy"></i></div>
                            <div class="stat-info">
                                <div class="stat-name">Liderazgo</div>
                                <div class="stat-bar">
                                    <div class="stat-fill" style="width: {{ $stats->liderazgo }}%"></div>
                                </div>
                                <div class="stat-value">{{ $stats->liderazgo }}/100</div>
                            </div>
                        </div>
                        
                        <div class="stat-item">
                            <div class="stat-icon"><i class="fas fa-puzzle-piece"></i></div>
                            <div class="stat-info">
                                <div class="stat-name">Resolución</div>
                                <div class="stat-bar">
                                    <div class="stat-fill" style="width: {{ $stats->resolucion }}%"></div>
                                </div>
                                <div class="stat-value">{{ $stats->resolucion }}/100</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Equipamiento --}}
            <div class="equipment-section">
                <h2>Equipamiento Actual</h2>
                <div class="equipment-grid">
                    <div class="equipment-slot helmet" data-slot="casco">
                        @if($equipamiento->casco)
                            <img src="{{ $equipamiento->casco->icono }}" alt="{{ $equipamiento->casco->nombre }}" 
                                 class="equipment-item {{ $equipamiento->casco->rareza }}"
                                 data-tooltip="{{ $equipamiento->casco->nombre }}">
                        @else
                            <div class="empty-slot">
                                <i class="fas fa-hard-hat"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="equipment-slot gauntlets" data-slot="guantes">
                        @if($equipamiento->guantes)
                            <img src="{{ $equipamiento->guantes->icono }}" alt="{{ $equipamiento->guantes->nombre }}" 
                                 class="equipment-item {{ $equipamiento->guantes->rareza }}"
                                 data-tooltip="{{ $equipamiento->guantes->nombre }}">
                        @else
                            <div class="empty-slot">
                                <i class="fas fa-hand-paper"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="equipment-slot chest" data-slot="pecho">
                        @if($equipamiento->pecho)
                            <img src="{{ $equipamiento->pecho->icono }}" alt="{{ $equipamiento->pecho->nombre }}" 
                                 class="equipment-item {{ $equipamiento->pecho->rareza }}"
                                 data-tooltip="{{ $equipamiento->pecho->nombre }}">
                        @else
                            <div class="empty-slot">
                                <i class="fas fa-tshirt"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="equipment-slot legs" data-slot="piernas">
                        @if($equipamiento->piernas)
                            <img src="{{ $equipamiento->piernas->icono }}" alt="{{ $equipamiento->piernas->nombre }}" 
                                 class="equipment-item {{ $equipamiento->piernas->rareza }}"
                                 data-tooltip="{{ $equipamiento->piernas->nombre }}">
                        @else
                            <div class="empty-slot">
                                <i class="fas fa-socks"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="equipment-slot boots" data-slot="botas">
                        @if($equipamiento->botas)
                            <img src="{{ $equipamiento->botas->icono }}" alt="{{ $equipamiento->botas->nombre }}" 
                                 class="equipment-item {{ $equipamiento->botas->rareza }}"
                                 data-tooltip="{{ $equipamiento->botas->nombre }}">
                        @else
                            <div class="empty-slot">
                                <i class="fas fa-shoe-prints"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="equipment-slot weapon" data-slot="arma">
                        @if($equipamiento->arma)
                            <img src="{{ $equipamiento->arma->icono }}" alt="{{ $equipamiento->arma->nombre }}" 
                                 class="equipment-item {{ $equipamiento->arma->rareza }}"
                                 data-tooltip="{{ $equipamiento->arma->nombre }}">
                        @else
                            <div class="empty-slot">
                                <i class="fas fa-magic"></i>
                            </div>
                        @endif
                    </div>
                </div>
                
                {{-- Equipment Stats Summary --}}
                <div class="equipment-summary">
                    <div class="equipment-stat">
                        <i class="fas fa-shield-alt"></i>
                        <span>Defensa: {{ $equipamiento->defensa_total ?? 0 }}</span>
                    </div>
                    <div class="equipment-stat">
                        <i class="fas fa-sword"></i>
                        <span>Ataque: {{ $equipamiento->ataque_total ?? 0 }}</span>
                    </div>
                    <div class="equipment-stat">
                        <i class="fas fa-bolt"></i>
                        <span>Velocidad: {{ $equipamiento->velocidad_total ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-right">
            {{-- Progreso Académico --}}
            <div class="academic-progress">
                <h2>Progreso Académico</h2>
                <div class="subjects-progress">
                    @foreach($progreso_materias as $materia)
                    <div class="subject-card">
                        <div class="subject-header">
                            <div class="subject-icon">
                                <i class="{{ $materia->icono }}"></i>
                            </div>
                            <div class="subject-info">
                                <h4>{{ $materia->nombre }}</h4>
                                <div class="subject-level">Nivel {{ $materia->nivel }}</div>
                            </div>
                            <div class="subject-grade">
                                <div class="grade-circle {{ $materia->grado_color }}">
                                    {{ $materia->promedio }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="subject-progress-bar">
                            <div class="progress-fill" style="width: {{ $materia->progreso_porcentaje }}%"></div>
                        </div>
                        
                        <div class="subject-stats">
                            <div class="subject-stat">
                                <span>Clases:</span>
                                <span>{{ $materia->clases_completadas }}/{{ $materia->clases_totales }}</span>
                            </div>
                            <div class="subject-stat">
                                <span>Tareas:</span>
                                <span>{{ $materia->tareas_completadas }}/{{ $materia->tareas_totales }}</span>
                            </div>
                            <div class="subject-stat">
                                <span>Exámenes:</span>
                                <span>{{ $materia->examenes_aprobados }}/{{ $materia->examenes_totales }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                {{-- Overall Academic Chart --}}
                <div class="academic-chart-container">
                    <canvas id="academicProgressChart"></canvas>
                </div>
            </div>

            {{-- Logros Recientes --}}
            <div class="recent-achievements">
                <h2>Logros Recientes</h2>
                <div class="achievements-showcase">
                    @foreach($logros_recientes as $logro)
                    <div class="achievement-showcase {{ $logro->rareza }}">
                        <div class="achievement-icon-showcase">
                            <img src="{{ $logro->icono }}" alt="{{ $logro->nombre }}">
                            <div class="rarity-glow {{ $logro->rareza }}"></div>
                        </div>
                        <div class="achievement-info-showcase">
                            <h4>{{ $logro->nombre }}</h4>
                            <p>{{ $logro->descripcion }}</p>
                            <div class="achievement-date">
                                {{ $logro->fecha_obtenido->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="view-all-achievements">
                    <a href="{{ route('estudiante.logros') }}" class="btn-primary">
                        Ver Todos los Logros
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="quick-stats">
                <h2>Estadísticas Rápidas</h2>
                <div class="quick-stats-grid">
                    <div class="quick-stat">
                        <div class="quick-stat-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="quick-stat-info">
                            <div class="quick-stat-value">{{ $estadisticas->clases_completadas }}</div>
                            <div class="quick-stat-label">Clases Completadas</div>
                        </div>
                    </div>
                    
                    <div class="quick-stat">
                        <div class="quick-stat-icon">
                            <i class="fas fa-fire"></i>
                        </div>
                        <div class="quick-stat-info">
                            <div class="quick-stat-value">{{ $estadisticas->racha_actual }}</div>
                            <div class="quick-stat-label">Racha Actual</div>
                        </div>
                    </div>
                    
                    <div class="quick-stat">
                        <div class="quick-stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="quick-stat-info">
                            <div class="quick-stat-value">{{ number_format($estadisticas->notas_promedio, 1) }}</div>
                            <div class="quick-stat-label">Promedio General</div>
                        </div>
                    </div>
                    
                    <div class="quick-stat">
                        <div class="quick-stat-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="quick-stat-info">
                            <div class="quick-stat-value">{{ $estadisticas->logros_totales }}</div>
                            <div class="quick-stat-label">Logros Obtenidos</div>
                        </div>
                    </div>
                    
                    <div class="quick-stat">
                        <div class="quick-stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="quick-stat-info">
                            <div class="quick-stat-value">{{ $estadisticas->tiempo_total }}h</div>
                            <div class="quick-stat-label">Tiempo Total</div>
                        </div>
                    </div>
                    
                    <div class="quick-stat">
                        <div class="quick-stat-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="quick-stat-info">
                            <div class="quick-stat-value">#{{ $estadisticas->ranking_mejor ?? '---' }}</div>
                            <div class="quick-stat-label">Mejor Ranking</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Historial de Actividad --}}
    <div class="activity-history">
        <div class="history-header">
            <h2>Historial de Actividad</h2>
            <div class="history-filters">
                <select id="activity-filter" class="destiny-select">
                    <option value="todos">Todas las Actividades</option>
                    <option value="logros">Logros</option>
                    <option value="clases">Clases</option>
                    <option value="tareas">Tareas</option>
                    <option value="examenes">Exámenes</option>
                </select>
                <select id="time-filter" class="destiny-select">
                    <option value="7">Últimos 7 días</option>
                    <option value="30">Últimos 30 días</option>
                    <option value="90">Últimos 3 meses</option>
                    <option value="365">Último año</option>
                </select>
            </div>
        </div>
        
        <div class="activity-timeline" id="activity-timeline">
            @foreach($historial as $actividad)
            <div class="timeline-item {{ $actividad->tipo }}" data-type="{{ $actividad->tipo }}" data-date="{{ $actividad->fecha }}">
                <div class="timeline-marker">
                    <i class="{{ $actividad->icono }}"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <h4>{{ $actividad->accion }}</h4>
                        <div class="timeline-date">{{ $actividad->fecha->format('d/m/Y H:i') }}</div>
                    </div>
                    <p>{{ $actividad->detalles }}</p>
                    @if($actividad->experiencia_ganada > 0)
                    <div class="experience-gained">
                        +{{ $actividad->experiencia_ganada }} XP
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="load-more-history">
            <button class="btn-secondary" id="load-more-btn">
                Cargar Más Actividades
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
    </div>

    {{-- Export Options --}}
    <div class="profile-actions">
        <button class="btn-primary" onclick="exportProfilePDF()">
            <i class="fas fa-file-pdf"></i>
            Exportar Perfil a PDF
        </button>
        <button class="btn-secondary" onclick="shareProfile()">
            <i class="fas fa-share"></i>
            Compartir Perfil
        </button>
    </div>
</div>

{{-- Equipment Tooltip --}}
<div class="equipment-tooltip" id="equipment-tooltip">
    <div class="tooltip-content">
        <div class="tooltip-header">
            <h4 id="tooltip-name"></h4>
            <div class="tooltip-rarity" id="tooltip-rarity"></div>
        </div>
        <div class="tooltip-description" id="tooltip-description"></div>
        <div class="tooltip-stats" id="tooltip-stats"></div>
    </div>
</div>

<style>
.destiny-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #16213e 100%);
    color: #ffffff;
}

.guardian-header {
    padding: 2rem 0;
    background: linear-gradient(90deg, rgba(255,215,0,0.1) 0%, rgba(138,43,226,0.1) 100%);
    border-bottom: 2px solid #ffd700;
}

.guardian-banner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.guardian-avatar-large {
    position: relative;
    width: 150px;
    height: 150px;
}

.guardian-avatar-large img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border: 4px solid #ffd700;
    object-fit: cover;
    box-shadow: 0 0 30px rgba(255,215,0,0.5);
}

.power-level-badge {
    position: absolute;
    top: -10px;
    right: -10px;
    background: linear-gradient(45deg, #ffd700, #ffed4a);
    color: #000000;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: bold;
    font-size: 1.2rem;
    box-shadow: 0 0 20px rgba(255,215,0,0.5);
}

.class-emblem-large {
    position: absolute;
    bottom: -10px;
    left: -10px;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 3px solid #ffd700;
}

.class-emblem-large.cazador { background: linear-gradient(45deg, #00ff00, #32cd32); }
.class-emblem-large.hechicero { background: linear-gradient(45deg, #9370db, #8a2be2); }
.class-emblem-large.titan { background: linear-gradient(45deg, #ff4500, #ff6b35); }

.guardian-identity h1 {
    font-size: 2.5rem;
    margin: 0 0 0.5rem 0;
    background: linear-gradient(45deg, #ffd700, #ff6b35);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.guardian-title {
    font-size: 1.2rem;
    color: #8a2be2;
    margin-bottom: 0.5rem;
    font-weight: bold;
}

.guardian-clan {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #cccccc;
    margin-bottom: 0.5rem;
}

.guardian-registration {
    color: #999999;
    font-size: 0.9rem;
}

.guardian-stats-overview {
    display: flex;
    gap: 2rem;
}

.stat-circle {
    background: rgba(255,255,255,0.1);
    border: 2px solid #ffd700;
    border-radius: 50%;
    width: 100px;
    height: 100px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
}

.stat-value {
    font-size: 1.5rem;
    font-weight: bold;
    color: #ffd700;
}

.stat-label {
    font-size: 0.8rem;
    color: #cccccc;
    text-align: center;
}

.character-selector {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.character-selector h2 {
    color: #ffd700;
    margin-bottom: 2rem;
    text-align: center;
}

.characters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.character-card {
    background: rgba(255,255,255,0.1);
    border: 2px solid transparent;
    border-radius: 15px;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    backdrop-filter: blur(10px);
}

.character-card:hover, .character-card.active {
    border-color: #ffd700;
    background: rgba(255,215,0,0.1);
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(255,215,0,0.3);
}

.character-icon {
    position: relative;
    width: 80px;
    height: 80px;
    margin: 0 auto 1rem;
}

.character-icon img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border: 2px solid #ffd700;
    object-fit: cover;
}

.class-indicator {
    position: absolute;
    bottom: -5px;
    right: -5px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 2px solid #ffd700;
}

.class-indicator.cazador { background: linear-gradient(45deg, #00ff00, #32cd32); }
.class-indicator.hechicero { background: linear-gradient(45deg, #9370db, #8a2be2); }
.class-indicator.titan { background: linear-gradient(45deg, #ff4500, #ff6b35); }

.character-info h4 {
    color: #ffd700;
    margin: 0 0 0.5rem 0;
}

.character-level, .character-power {
    color: #cccccc;
    font-size: 0.9rem;
    margin-bottom: 0.3rem;
}

.play-time {
    color: #8a2be2;
    font-size: 0.8rem;
    font-weight: bold;
}

.profile-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
}

.detailed-stats {
    background: rgba(255,255,255,0.05);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    backdrop-filter: blur(10px);
}

.detailed-stats h2 {
    color: #ffd700;
    margin-bottom: 2rem;
    text-align: center;
}

.stats-container {
    display: flex;
    gap: 2rem;
    align-items: center;
}

.radar-chart-container {
    flex: 1;
    max-width: 300px;
    max-height: 300px;
}

.stats-list {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
}

.stat-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(45deg, #ffd700, #ffed4a);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #000000;
    font-size: 1.2rem;
}

.stat-info {
    flex: 1;
}

.stat-name {
    color: #ffffff;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.stat-bar {
    width: 100%;
    height: 8px;
    background: rgba(255,255,255,0.2);
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.stat-fill {
    height: 100%;
    background: linear-gradient(90deg, #ffd700, #ffed4a);
    border-radius: 4px;
    transition: width 0.5s ease;
}

.stat-value {
    color: #ffd700;
    font-size: 0.9rem;
    font-weight: bold;
}

.equipment-section {
    background: rgba(255,255,255,0.05);
    border-radius: 15px;
    padding: 2rem;
    backdrop-filter: blur(10px);
}

.equipment-section h2 {
    color: #ffd700;
    margin-bottom: 2rem;
    text-align: center;
}

.equipment-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: repeat(3, 1fr);
    gap: 1rem;
    max-width: 300px;
    margin: 0 auto 2rem;
}

.equipment-slot {
    aspect-ratio: 1;
    background: rgba(255,255,255,0.1);
    border: 2px solid #666666;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.equipment-slot:hover {
    border-color: #ffd700;
    transform: scale(1.05);
}

.equipment-slot.helmet { grid-column: 2; grid-row: 1; }
.equipment-slot.gauntlets { grid-column: 1; grid-row: 2; }
.equipment-slot.chest { grid-column: 2; grid-row: 2; }
.equipment-slot.weapon { grid-column: 3; grid-row: 2; }
.equipment-slot.legs { grid-column: 2; grid-row: 3; }
.equipment-slot.boots { grid-column: 1; grid-row: 3; }

.equipment-item {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
}

.equipment-item.exotico {
    border: 2px solid #ffd700;
    box-shadow: 0 0 15px rgba(255,215,0,0.5);
}

.equipment-item.legendario {
    border: 2px solid #8a2be2;
    box-shadow: 0 0 15px rgba(138,43,226,0.5);
}

.equipment-item.raro {
    border: 2px solid #4CAF50;
    box-shadow: 0 0 15px rgba(76,175,80,0.5);
}

.empty-slot {
    color: #666666;
    font-size: 2rem;
}

.equipment-summary {
    display: flex;
    justify-content: space-around;
    gap: 1rem;
}

.equipment-stat {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #ffd700;
    font-weight: bold;
}

.academic-progress {
    background: rgba(255,255,255,0.05);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    backdrop-filter: blur(10px);
}

.academic-progress h2 {
    color: #ffd700;
    margin-bottom: 2rem;
    text-align: center;
}

.subjects-progress {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
}

.subject-card {
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
    padding: 1.5rem;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.subject-card:hover {
    border-color: #ffd700;
    background: rgba(255,255,255,0.15);
}

.subject-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.subject-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(45deg, #8a2be2, #9370db);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 1.5rem;
}

.subject-info h4 {
    color: #ffffff;
    margin: 0 0 0.3rem 0;
}

.subject-level {
    color: #cccccc;
    font-size: 0.9rem;
}

.grade-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
}

.grade-circle.excellent { background: #4CAF50; color: #ffffff; }
.grade-circle.good { background: #ffd700; color: #000000; }
.grade-circle.average { background: #ff9800; color: #ffffff; }
.grade-circle.poor { background: #f44336; color: #ffffff; }

.subject-progress-bar {
    width: 100%;
    height: 8px;
    background: rgba(255,255,255,0.2);
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 1rem;
}

.subject-stats {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
}

.subject-stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.9rem;
}

.subject-stat span:first-child {
    color: #cccccc;
}

.subject-stat span:last-child {
    color: #ffd700;
    font-weight: bold;
}

.academic-chart-container {
    height: 300px;
}

.recent-achievements {
    background: rgba(255,255,255,0.05);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    backdrop-filter: blur(10px);
}

.recent-achievements h2 {
    color: #ffd700;
    margin-bottom: 2rem;
    text-align: center;
}

.achievements-showcase {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
}

.achievement-showcase {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.achievement-showcase:hover {
    transform: translateX(5px);
}

.achievement-showcase.exotico {
    border-color: #ffd700;
    background: linear-gradient(90deg, rgba(255,215,0,0.1), rgba(255,215,0,0.05));
}

.achievement-icon-showcase {
    position: relative;
    width: 60px;
    height: 60px;
}

.achievement-icon-showcase img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px;
}

.rarity-glow {
    position: absolute;
    top: -3px;
    left: -3px;
    right: -3px;
    bottom: -3px;
    border-radius: 13px;
    z-index: -1;
}

.rarity-glow.exotico {
    background: linear-gradient(45deg, #ffd700, #ffed4a);
    box-shadow: 0 0 15px rgba(255,215,0,0.5);
}

.rarity-glow.legendario {
    background: linear-gradient(45deg, #8a2be2, #9370db);
    box-shadow: 0 0 15px rgba(138,43,226,0.5);
}

.achievement-info-showcase h4 {
    color: #ffd700;
    margin: 0 0 0.5rem 0;
}

.achievement-info-showcase p {
    color: #cccccc;
    margin: 0 0 0.5rem 0;
    font-size: 0.9rem;
    line-height: 1.3;
}

.achievement-date {
    color: #8a2be2;
    font-size: 0.8rem;
    font-weight: bold;
}

.view-all-achievements {
    text-align: center;
}

.btn-primary, .btn-secondary {
    background: linear-gradient(45deg, #ffd700, #ffed4a);
    color: #000000;
    border: none;
    padding: 1rem 2rem;
    border-radius: 25px;
    cursor: pointer;
    font-weight: bold;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
}

.btn-secondary {
    background: linear-gradient(45deg, #8a2be2, #9370db);
    color: #ffffff;
}

.btn-primary:hover, .btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(255,215,0,0.3);
}

.quick-stats {
    background: rgba(255,255,255,0.05);
    border-radius: 15px;
    padding: 2rem;
    backdrop-filter: blur(10px);
}

.quick-stats h2 {
    color: #ffd700;
    margin-bottom: 2rem;
    text-align: center;
}

.quick-stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.quick-stat {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
    transition: all 0.3s ease;
}

.quick-stat:hover {
    background: rgba(255,255,255,0.15);
    transform: translateY(-2px);
}

.quick-stat-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(45deg, #ffd700, #ffed4a);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #000000;
    font-size: 1.3rem;
}

.quick-stat-value {
    font-size: 1.5rem;
    font-weight: bold;
    color: #ffd700;
    margin-bottom: 0.3rem;
}

.quick-stat-label {
    color: #cccccc;
    font-size: 0.9rem;
}

.activity-history {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
    background: rgba(255,255,255,0.05);
    border-radius: 15px;
    margin-bottom: 2rem;
    backdrop-filter: blur(10px);
}

.history-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.history-header h2 {
    color: #ffd700;
    margin: 0;
}

.history-filters {
    display: flex;
    gap: 1rem;
}

.destiny-select {
    background: rgba(255,255,255,0.1);
    border: 2px solid #ffd700;
    color: #ffffff;
    padding: 0.8rem 1.5rem;
    border-radius: 25px;
    backdrop-filter: blur(10px);
}

.activity-timeline {
    position: relative;
    padding-left: 2rem;
}

.activity-timeline::before {
    content: '';
    position: absolute;
    left: 1rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #ffd700, #8a2be2);
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
    padding-left: 2rem;
}

.timeline-marker {
    position: absolute;
    left: -2rem;
    top: 0.5rem;
    width: 2rem;
    height: 2rem;
    background: linear-gradient(45deg, #ffd700, #ffed4a);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #000000;
    font-size: 0.9rem;
    z-index: 1;
    box-shadow: 0 0 15px rgba(255,215,0,0.5);
}

.timeline-item.logros .timeline-marker {
    background: linear-gradient(45deg, #8a2be2, #9370db);
    color: #ffffff;
}

.timeline-item.examenes .timeline-marker {
    background: linear-gradient(45deg, #4CAF50, #66BB6A);
    color: #ffffff;
}

.timeline-content {
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
    padding: 1.5rem;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.timeline-content:hover {
    border-color: #ffd700;
    background: rgba(255,255,255,0.15);
}

.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.timeline-header h4 {
    color: #ffd700;
    margin: 0;
}

.timeline-date {
    color: #cccccc;
    font-size: 0.9rem;
}

.timeline-content p {
    color: #ffffff;
    margin: 0 0 1rem 0;
    line-height: 1.4;
}

.experience-gained {
    background: linear-gradient(45deg, #ffd700, #ffed4a);
    color: #000000;
    padding: 0.3rem 1rem;
    border-radius: 15px;
    font-weight: bold;
    font-size: 0.9rem;
    display: inline-block;
}

.load-more-history {
    text-align: center;
    margin-top: 2rem;
}

.profile-actions {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
    display: flex;
    justify-content: center;
    gap: 2rem;
}

.equipment-tooltip {
    position: absolute;
    z-index: 1000;
    background: linear-gradient(135deg, #1a1a2e, #16213e);
    border: 2px solid #ffd700;
    border-radius: 10px;
    padding: 1rem;
    max-width: 300px;
    display: none;
    pointer-events: none;
    box-shadow: 0 10px 40px rgba(0,0,0,0.8);
}

.tooltip-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.tooltip-header h4 {
    color: #ffffff;
    margin: 0;
}

.tooltip-rarity {
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: bold;
    text-transform: uppercase;
}

.tooltip-rarity.exotico {
    background: #ffd700;
    color: #000000;
}

.tooltip-rarity.legendario {
    background: #8a2be2;
    color: #ffffff;
}

.tooltip-rarity.raro {
    background: #4CAF50;
    color: #ffffff;
}

.tooltip-description {
    color: #cccccc;
    margin-bottom: 1rem;
    line-height: 1.4;
}

.tooltip-stats {
    border-top: 1px solid rgba(255,255,255,0.2);
    padding-top: 0.5rem;
}

.tooltip-stat {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.3rem;
    font-size: 0.9rem;
}

.tooltip-stat-name {
    color: #cccccc;
}

.tooltip-stat-value {
    color: #ffd700;
    font-weight: bold;
}

@media (max-width: 1024px) {
    .profile-content {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .stats-container {
        flex-direction: column;
    }
    
    .radar-chart-container {
        max-width: 250px;
        max-height: 250px;
    }
}

@media (max-width: 768px) {
    .guardian-banner {
        flex-direction: column;
        gap: 2rem;
        text-align: center;
    }
    
    .guardian-stats-overview {
        justify-content: center;
    }
    
    .characters-grid {
        grid-template-columns: 1fr;
    }
    
    .equipment-grid {
        max-width: 250px;
    }
    
    .equipment-summary {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .quick-stats-grid {
        grid-template-columns: 1fr;
    }
    
    .history-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .history-filters {
        flex-direction: column;
        width: 100%;
    }
    
    .destiny-select {
        width: 100%;
    }
    
    .profile-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .activity-timeline {
        padding-left: 1rem;
    }
    
    .activity-timeline::before {
        left: 0.5rem;
    }
    
    .timeline-marker {
        left: -1.5rem;
    }
    
    .timeline-item {
        padding-left: 1.5rem;
    }
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Charts
    initializeRadarChart();
    initializeAcademicChart();
    
    // Initialize tooltips
    initializeEquipmentTooltips();
    
    // Initialize activity filters
    initializeActivityFilters();
    
    // Initialize load more functionality
    initializeLoadMore();
});

function initializeRadarChart() {
    const ctx = document.getElementById('statsRadarChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: ['Conocimiento', 'Creatividad', 'Colaboración', 'Puntualidad', 'Liderazgo', 'Resolución'],
            datasets: [{
                label: 'Estadísticas del Guardián',
                data: [
                    {{ $stats->conocimiento ?? 0 }},
                    {{ $stats->creatividad ?? 0 }},
                    {{ $stats->colaboracion ?? 0 }},
                    {{ $stats->puntualidad ?? 0 }},
                    {{ $stats->liderazgo ?? 0 }},
                    {{ $stats->resolucion ?? 0 }}
                ],
                backgroundColor: 'rgba(255, 215, 0, 0.2)',
                borderColor: '#ffd700',
                borderWidth: 2,
                pointBackgroundColor: '#ffd700',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        display: false
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.2)'
                    },
                    angleLines: {
                        color: 'rgba(255, 255, 255, 0.2)'
                    },
                    pointLabels: {
                        color: '#ffffff',
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    }
                }
            }
        }
    });
}

function initializeAcademicChart() {
    const ctx = document.getElementById('academicProgressChart').getContext('2d');
    
    const materias = @json($progreso_materias->pluck('nombre'));
    const promedios = @json($progreso_materias->pluck('promedio'));
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: materias,
            datasets: [{
                label: 'Promedio por Materia',
                data: promedios,
                backgroundColor: [
                    'rgba(255, 215, 0, 0.8)',
                    'rgba(138, 43, 226, 0.8)',
                    'rgba(76, 175, 80, 0.8)',
                    'rgba(255, 152, 0, 0.8)',
                    'rgba(244, 67, 54, 0.8)',
                    'rgba(33, 150, 243, 0.8)'
                ],
                borderColor: [
                    '#ffd700',
                    '#8a2be2',
                    '#4CAF50',
                    '#ff9800',
                    '#f44336',
                    '#2196F3'
                ],
                borderWidth: 2,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 20,
                    ticks: {
                        color: '#ffffff'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.2)'
                    }
                },
                x: {
                    ticks: {
                        color: '#ffffff',
                        maxRotation: 45
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

function initializeEquipmentTooltips() {
    const equipmentSlots = document.querySelectorAll('.equipment-slot');
    const tooltip = document.getElementById('equipment-tooltip');
    
    equipmentSlots.forEach(slot => {
        const item = slot.querySelector('.equipment-item');
        if (item) {
            slot.addEventListener('mouseenter', function(e) {
                showEquipmentTooltip(e, item);
            });
            
            slot.addEventListener('mouseleave', function() {
                hideEquipmentTooltip();
            });
            
            slot.addEventListener('mousemove', function(e) {
                moveTooltip(e);
            });
        }
    });
}

function showEquipmentTooltip(event, item) {
    const tooltip = document.getElementById('equipment-tooltip');
    const itemData = getEquipmentData(item);
    
    // Populate tooltip content
    document.getElementById('tooltip-name').textContent = itemData.name;
    document.getElementById('tooltip-rarity').textContent = itemData.rarity;
    document.getElementById('tooltip-rarity').className = `tooltip-rarity ${itemData.rarity}`;
    document.getElementById('tooltip-description').textContent = itemData.description;
    
    // Populate stats
    const statsContainer = document.getElementById('tooltip-stats');
    statsContainer.innerHTML = '';
    
    if (itemData.stats) {
        Object.entries(itemData.stats).forEach(([statName, statValue]) => {
            const statDiv = document.createElement('div');
            statDiv.className = 'tooltip-stat';
            statDiv.innerHTML = `
                <span class="tooltip-stat-name">${statName}:</span>
                <span class="tooltip-stat-value">+${statValue}</span>
            `;
            statsContainer.appendChild(statDiv);
        });
    }
    
    tooltip.style.display = 'block';
    moveTooltip(event);
}

function hideEquipmentTooltip() {
    const tooltip = document.getElementById('equipment-tooltip');
    tooltip.style.display = 'none';
}

function moveTooltip(event) {
    const tooltip = document.getElementById('equipment-tooltip');
    tooltip.style.left = (event.pageX + 10) + 'px';
    tooltip.style.top = (event.pageY + 10) + 'px';
}

function getEquipmentData(item) {
    // This would normally fetch from a data attribute or API
    // For demo purposes, return mock data
    return {
        name: item.alt || 'Equipo Misterioso',
        rarity: item.classList.contains('exotico') ? 'exotico' : 
                item.classList.contains('legendario') ? 'legendario' : 'raro',
        description: 'Un poderoso elemento de equipo forjado por los mejores artesanos de la Academia.',
        stats: {
            'Defensa': Math.floor(Math.random() * 50) + 10,
            'Ataque': Math.floor(Math.random() * 30) + 5,
            'Velocidad': Math.floor(Math.random() * 20) + 5
        }
    };
}

function switchCharacter(characterId) {
    // Show loading state
    const cards = document.querySelectorAll('.character-card');
    cards.forEach(card => card.classList.add('loading'));
    
    // Make API call to switch character
    fetch(`/api/perfil/cambiar-personaje/${characterId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the page to show new character data
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error switching character:', error);
        cards.forEach(card => card.classList.remove('loading'));
    });
}

function initializeActivityFilters() {
    const activityFilter = document.getElementById('activity-filter');
    const timeFilter = document.getElementById('time-filter');
    
    activityFilter.addEventListener('change', filterActivities);
    timeFilter.addEventListener('change', filterActivities);
}

function filterActivities() {
    const activityType = document.getElementById('activity-filter').value;
    const timeRange = document.getElementById('time-filter').value;
    const timelineItems = document.querySelectorAll('.timeline-item');
    
    const now = new Date();
    const cutoffDate = new Date(now.getTime() - (timeRange * 24 * 60 * 60 * 1000));
    
    timelineItems.forEach(item => {
        let show = true;
        
        // Filter by activity type
        if (activityType !== 'todos') {
            if (!item.classList.contains(activityType)) {
                show = false;
            }
        }
        
        // Filter by time range
        if (show) {
            const itemDate = new Date(item.dataset.date);
            if (itemDate < cutoffDate) {
                show = false;
            }
        }
        
        item.style.display = show ? 'block' : 'none';
        
        // Add animation
        if (show) {
            item.style.animation = 'fadeInUp 0.5s ease forwards';
        }
    });
}

function initializeLoadMore() {
    const loadMoreBtn = document.getElementById('load-more-btn');
    let offset = {{ count($historial) }};
    let isLoading = false;
    
    loadMoreBtn.addEventListener('click', function() {
        if (isLoading) return;
        
        isLoading = true;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cargando...';
        
        fetch(`/api/historial?offset=${offset}&limit=10`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    appendActivityItems(data);
                    offset += data.length;
                } else {
                    loadMoreBtn.style.display = 'none';
                }
                
                isLoading = false;
                loadMoreBtn.innerHTML = 'Cargar Más Actividades <i class="fas fa-chevron-down"></i>';
            })
            .catch(error => {
                console.error('Error loading more activities:', error);
                isLoading = false;
                loadMoreBtn.innerHTML = 'Cargar Más Actividades <i class="fas fa-chevron-down"></i>';
            });
    });
}

function appendActivityItems(activities) {
    const timeline = document.getElementById('activity-timeline');
    
    activities.forEach(activity => {
        const item = document.createElement('div');
        item.className = `timeline-item ${activity.tipo}`;
        item.dataset.type = activity.tipo;
        item.dataset.date = activity.fecha;
        
        item.innerHTML = `
            <div class="timeline-marker">
                <i class="${activity.icono}"></i>
            </div>
            <div class="timeline-content">
                <div class="timeline-header">
                    <h4>${activity.accion}</h4>
                    <div class="timeline-date">${activity.fecha_formateada}</div>
                </div>
                <p>${activity.detalles}</p>
                ${activity.experiencia_ganada > 0 ? `
                    <div class="experience-gained">
                        +${activity.experiencia_ganada} XP
                    </div>
                ` : ''}
            </div>
        `;
        
        timeline.appendChild(item);
        
        // Add entrance animation
        item.style.animation = 'fadeInUp 0.5s ease forwards';
    });
}

function exportProfilePDF() {
    // Show loading state
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando PDF...';
    btn.disabled = true;
    
    // Make API call to generate PDF
    fetch('/api/perfil/exportar-pdf', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.blob())
    .then(blob => {
        // Create download link
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'perfil-guardian.pdf';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
        
        // Reset button
        btn.innerHTML = originalText;
        btn.disabled = false;
    })
    .catch(error => {
        console.error('Error generating PDF:', error);
        btn.innerHTML = originalText;
        btn.disabled = false;
        alert('Error al generar el PDF. Inténtalo de nuevo.');
    });
}

function shareProfile() {
    if (navigator.share) {
        navigator.share({
            title: 'Mi Perfil de Guardián - ClassCraft Destiny',
            text: 'Mira mi progreso como Guardián en la Academia',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Enlace copiado al portapapeles');
        });
    }
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .loading {
        opacity: 0.5;
        pointer-events: none;
    }
    
    .character-card.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        border: 2px solid #ffd700;
        border-top: 2px solid transparent;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        transform: translate(-50%, -50%);
    }
    
    @keyframes spin {
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        100% { transform: translate(-50%, -50%) rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>
@endsection