{{-- resources/views/estudiante/logros.blade.php --}}
@extends('layouts.destiny')

@section('title', 'Logros - Colección de Medallas')

@section('content')
<div class="destiny-container">
    {{-- Header del Coleccionista --}}
    <div class="achievements-header">
        <div class="collector-banner">
            <div class="grimoire-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="collector-info">
                <h1>Colección de Medallas</h1>
                <p>Tus logros como Guardián de la Academia</p>
            </div>
            <div class="collector-stats">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats->total_logros ?? 0 }}</div>
                    <div class="stat-label">Logros Obtenidos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $stats->porcentaje_completado ?? 0 }}%</div>
                    <div class="stat-label">Completado</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $stats->puntos_logros ?? 0 }}</div>
                    <div class="stat-label">Puntos de Logro</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Showcase de Logros Favoritos --}}
    <div class="showcase-section">
        <h2>Logros Destacados</h2>
        <div class="showcase-grid">
            @foreach($logros_destacados as $logro)
            <div class="showcase-item {{ $logro->rareza }}">
                <div class="showcase-icon">
                    <img src="{{ $logro->icono }}" alt="{{ $logro->nombre }}">
                    <div class="rarity-glow {{ $logro->rareza }}"></div>
                </div>
                <div class="showcase-info">
                    <h3>{{ $logro->nombre }}</h3>
                    <p>{{ $logro->descripcion }}</p>
                    <div class="achievement-date">
                        Obtenido el {{ $logro->fecha_obtenido->format('d/m/Y') }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Filtros y Categorías --}}
    <div class="achievements-controls">
        <div class="category-tabs">
            <button class="category-btn active" data-category="todos">Todos</button>
            <button class="category-btn" data-category="academicos">Académicos</button>
            <button class="category-btn" data-category="sociales">Sociales</button>
            <button class="category-btn" data-category="especiales">Especiales</button>
            <button class="category-btn" data-category="eventos">Eventos</button>
            <button class="category-btn" data-category="secretos">Secretos</button>
        </div>
        
        <div class="achievement-filters">
            <div class="rarity-filter">
                <button class="rarity-btn active" data-rarity="todos">Todas</button>
                <button class="rarity-btn comun" data-rarity="comun">Común</button>
                <button class="rarity-btn raro" data-rarity="raro">Raro</button>
                <button class="rarity-btn legendario" data-rarity="legendario">Legendario</button>
                <button class="rarity-btn exotico" data-rarity="exotico">Exótico</button>
            </div>
            
            <div class="status-filter">
                <button class="status-btn active" data-status="todos">Todos</button>
                <button class="status-btn" data-status="obtenido">Obtenidos</button>
                <button class="status-btn" data-status="progreso">En Progreso</button>
                <button class="status-btn" data-status="bloqueado">Bloqueados</button>
            </div>
            
            <div class="search-achievements">
                <input type="text" id="achievement-search" placeholder="Buscar logro...">
                <i class="fas fa-search"></i>
            </div>
        </div>
    </div>

    {{-- Próximos a Completar --}}
    <div class="next-achievements">
        <h2>Cerca de Completar</h2>
        <div class="next-grid">
            @foreach($proximos_logros as $logro)
            <div class="next-item">
                <div class="achievement-icon progress">
                    <img src="{{ $logro->icono }}" alt="{{ $logro->nombre }}">
                    <div class="progress-overlay">
                        <div class="progress-circle">
                            <svg class="progress-svg" viewBox="0 0 36 36">
                                <path class="progress-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                <path class="progress-bar" stroke-dasharray="{{ $logro->progreso }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            </svg>
                            <div class="progress-text">{{ $logro->progreso }}%</div>
                        </div>
                    </div>
                </div>
                <div class="next-info">
                    <h4>{{ $logro->nombre }}</h4>
                    <p>{{ $logro->descripcion }}</p>
                    <div class="progress-bar-container">
                        <div class="progress-bar-fill" style="width: {{ $logro->progreso }}%"></div>
                    </div>
                    <div class="progress-details">
                        {{ $logro->progreso_actual }}/{{ $logro->progreso_total }} {{ $logro->unidad }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Grid Principal de Logros --}}
    <div class="achievements-grid-container">
        <div class="achievements-grid" id="achievements-grid">
            @foreach($logros as $logro)
            <div class="achievement-card {{ $logro->rareza }} {{ $logro->estado }}" 
                 data-category="{{ $logro->categoria }}" 
                 data-rarity="{{ $logro->rareza }}" 
                 data-status="{{ $logro->estado }}"
                 onclick="showAchievementDetails({{ $logro->id }})">
                
                <div class="achievement-icon-container">
                    <img src="{{ $logro->icono }}" alt="{{ $logro->nombre }}" class="achievement-icon">
                    
                    @if($logro->estado === 'obtenido')
                        <div class="completion-mark">
                            <i class="fas fa-check"></i>
                        </div>
                    @elseif($logro->estado === 'progreso')
                        <div class="progress-indicator">
                            <div class="progress-ring">
                                <svg viewBox="0 0 36 36">
                                    <path class="progress-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    <path class="progress-bar" stroke-dasharray="{{ $logro->progreso ?? 0 }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                </svg>
                            </div>
                        </div>
                    @else
                        <div class="locked-overlay">
                            <i class="fas fa-lock"></i>
                        </div>
                    @endif
                    
                    <div class="rarity-border {{ $logro->rareza }}"></div>
                </div>
                
                <div class="achievement-info">
                    <h3 class="achievement-name">{{ $logro->nombre }}</h3>
                    <p class="achievement-description">{{ $logro->descripcion }}</p>
                    
                    <div class="achievement-meta">
                        <div class="achievement-rarity {{ $logro->rareza }}">
                            {{ ucfirst($logro->rareza) }}
                        </div>
                        <div class="achievement-points">
                            {{ $logro->puntos }} pts
                        </div>
                    </div>
                    
                    @if($logro->estado === 'obtenido')
                        <div class="completion-date">
                            Obtenido: {{ $logro->fecha_obtenido->format('d/m/Y') }}
                        </div>
                    @elseif($logro->estado === 'progreso')
                        <div class="progress-info">
                            Progreso: {{ $logro->progreso_actual }}/{{ $logro->progreso_total }}
                        </div>
                    @endif
                    
                    <div class="achievement-stats">
                        <div class="global-completion">
                            <i class="fas fa-users"></i>
                            {{ $logro->porcentaje_global }}% de jugadores
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        {{-- Loading indicator --}}
        <div class="loading-more" id="loading-more">
            <div class="destiny-spinner"></div>
            <p>Cargando más logros...</p>
        </div>
    </div>
</div>

{{-- Modal de Detalles del Logro --}}
<div class="achievement-modal" id="achievement-modal">
    <div class="modal-overlay" onclick="closeAchievementModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <button class="close-btn" onclick="closeAchievementModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="modal-body">
            {{-- Contenido cargado dinámicamente --}}
        </div>
    </div>
</div>

<style>
.destiny-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #16213e 100%);
    color: #ffffff;
}

.achievements-header {
    padding: 2rem 0;
    background: linear-gradient(90deg, rgba(138,43,226,0.1) 0%, rgba(255,215,0,0.1) 100%);
    border-bottom: 2px solid #8a2be2;
}

.collector-banner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.grimoire-icon {
    font-size: 4rem;
    color: #8a2be2;
    text-shadow: 0 0 30px rgba(138,43,226,0.7);
    animation: float 3s ease-in-out infinite;
}

.collector-info h1 {
    font-size: 2.5rem;
    margin: 0;
    background: linear-gradient(45deg, #8a2be2, #ffd700);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.collector-stats {
    display: flex;
    gap: 2rem;
}

.stat-card {
    background: rgba(255,255,255,0.1);
    border: 2px solid #8a2be2;
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    backdrop-filter: blur(10px);
    min-width: 120px;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: #ffd700;
    text-shadow: 0 0 10px rgba(255,215,0,0.5);
}

.stat-label {
    font-size: 0.9rem;
    color: #cccccc;
    margin-top: 0.5rem;
}

.showcase-section {
    max-width: 1200px;
    margin: 0 auto;
    padding: 3rem 2rem;
}

.showcase-section h2 {
    font-size: 2rem;
    color: #ffd700;
    margin-bottom: 2rem;
    text-align: center;
}

.showcase-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.showcase-item {
    background: rgba(255,255,255,0.1);
    border-radius: 15px;
    padding: 2rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    backdrop-filter: blur(10px);
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.showcase-item.exotico {
    border-color: #ffd700;
    background: linear-gradient(135deg, rgba(255,215,0,0.1), rgba(255,215,0,0.05));
}

.showcase-item.legendario {
    border-color: #8a2be2;
    background: linear-gradient(135deg, rgba(138,43,226,0.1), rgba(138,43,226,0.05));
}

.showcase-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(255,215,0,0.3);
}

.showcase-icon {
    position: relative;
    width: 80px;
    height: 80px;
}

.showcase-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px;
}

.rarity-glow {
    position: absolute;
    top: -5px;
    left: -5px;
    right: -5px;
    bottom: -5px;
    border-radius: 15px;
    z-index: -1;
}

.rarity-glow.exotico {
    background: linear-gradient(45deg, #ffd700, #ffed4a);
    box-shadow: 0 0 20px rgba(255,215,0,0.5);
}

.rarity-glow.legendario {
    background: linear-gradient(45deg, #8a2be2, #9370db);
    box-shadow: 0 0 20px rgba(138,43,226,0.5);
}

.showcase-info h3 {
    color: #ffd700;
    margin: 0 0 0.5rem 0;
    font-size: 1.3rem;
}

.showcase-info p {
    color: #cccccc;
    margin: 0 0 1rem 0;
    line-height: 1.4;
}

.achievement-date {
    color: #8a2be2;
    font-size: 0.9rem;
    font-weight: bold;
}

.achievements-controls {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem 2rem;
}

.category-tabs {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    justify-content: center;
}

.category-btn {
    background: rgba(255,255,255,0.1);
    border: 2px solid transparent;
    color: #ffffff;
    padding: 0.8rem 1.5rem;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.category-btn.active {
    background: linear-gradient(45deg, #8a2be2, #9370db);
    border-color: #8a2be2;
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(138,43,226,0.3);
}

.achievement-filters {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
}

.rarity-filter, .status-filter {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.rarity-btn, .status-btn {
    background: rgba(255,255,255,0.1);
    border: 2px solid transparent;
    color: #ffffff;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.8rem;
}

.rarity-btn.comun { border-color: #ffffff; }
.rarity-btn.raro { border-color: #4CAF50; }
.rarity-btn.legendario { border-color: #8a2be2; }
.rarity-btn.exotico { border-color: #ffd700; }

.rarity-btn.active, .status-btn.active {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(255,255,255,0.2);
}

.search-achievements {
    position: relative;
}

.search-achievements input {
    background: rgba(255,255,255,0.1);
    border: 2px solid #8a2be2;
    color: #ffffff;
    padding: 0.8rem 1.5rem 0.8rem 3rem;
    border-radius: 25px;
    width: 250px;
    backdrop-filter: blur(10px);
}

.search-achievements i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #8a2be2;
}

.next-achievements {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
    background: rgba(255,255,255,0.05);
    margin-bottom: 2rem;
    border-radius: 15px;
}

.next-achievements h2 {
    color: #ffd700;
    margin-bottom: 2rem;
    text-align: center;
}

.next-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.next-item {
    background: rgba(255,255,255,0.1);
    border: 2px solid #ffd700;
    border-radius: 10px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.achievement-icon.progress {
    position: relative;
    width: 60px;
    height: 60px;
}

.progress-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.progress-circle {
    width: 40px;
    height: 40px;
    position: relative;
}

.progress-svg {
    width: 100%;
    height: 100%;
    transform: rotate(-90deg);
}

.progress-bg {
    fill: none;
    stroke: rgba(255,255,255,0.2);
    stroke-width: 3;
}

.progress-bar {
    fill: none;
    stroke: #ffd700;
    stroke-width: 3;
    stroke-linecap: round;
    transition: stroke-dasharray 0.5s ease;
}

.progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 0.7rem;
    color: #ffd700;
    font-weight: bold;
}

.next-info h4 {
    color: #ffd700;
    margin: 0 0 0.5rem 0;
}

.next-info p {
    color: #cccccc;
    margin: 0 0 1rem 0;
    font-size: 0.9rem;
}

.progress-bar-container {
    width: 100%;
    height: 8px;
    background: rgba(255,255,255,0.2);
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.progress-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #ffd700, #ffed4a);
    border-radius: 4px;
    transition: width 0.5s ease;
}

.progress-details {
    color: #ffd700;
    font-size: 0.8rem;
    font-weight: bold;
}

.achievements-grid-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem 3rem;
}

.achievements-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.achievement-card {
    background: rgba(255,255,255,0.1);
    border-radius: 15px;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    backdrop-filter: blur(10px);
    position: relative;
    overflow: hidden;
}

.achievement-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(255,255,255,0.1);
}

.achievement-card.obtenido {
    border-color: #4CAF50;
    background: linear-gradient(135deg, rgba(76,175,80,0.1), rgba(76,175,80,0.05));
}

.achievement-card.progreso {
    border-color: #ffd700;
    background: linear-gradient(135deg, rgba(255,215,0,0.1), rgba(255,215,0,0.05));
}

.achievement-card.bloqueado {
    opacity: 0.6;
    border-color: #666666;
}

.achievement-icon-container {
    position: relative;
    width: 80px;
    height: 80px;
    margin: 0 auto 1rem;
}

.achievement-icon {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px;
}

.completion-mark {
    position: absolute;
    top: -5px;
    right: -5px;
    width: 30px;
    height: 30px;
    background: #4CAF50;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
    box-shadow: 0 0 15px rgba(76,175,80,0.5);
}

.progress-indicator {
    position: absolute;
    top: -5px;
    right: -5px;
    width: 30px;
    height: 30px;
}

.progress-ring {
    width: 100%;
    height: 100%;
}

.locked-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    color: #666666;
    font-size: 1.5rem;
}

.rarity-border {
    position: absolute;
    top: -3px;
    left: -3px;
    right: -3px;
    bottom: -3px;
    border-radius: 13px;
    z-index: -1;
}

.rarity-border.comun { background: linear-gradient(45deg, #ffffff, #e0e0e0); }
.rarity-border.raro { background: linear-gradient(45deg, #4CAF50, #66BB6A); }
.rarity-border.legendario { background: linear-gradient(45deg, #8a2be2, #9370db); }
.rarity-border.exotico { background: linear-gradient(45deg, #ffd700, #ffed4a); }

.achievement-info {
    text-align: center;
}

.achievement-name {
    color: #ffffff;
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
    font-weight: bold;
}

.achievement-description {
    color: #cccccc;
    margin: 0 0 1rem 0;
    font-size: 0.9rem;
    line-height: 1.4;
}

.achievement-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.achievement-rarity {
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: bold;
    text-transform: uppercase;
}

.achievement-rarity.comun { background: #ffffff; color: #000000; }
.achievement-rarity.raro { background: #4CAF50; color: #ffffff; }
.achievement-rarity.legendario { background: #8a2be2; color: #ffffff; }
.achievement-rarity.exotico { background: #ffd700; color: #000000; }

.achievement-points {
    color: #ffd700;
    font-weight: bold;
    font-size: 0.9rem;
}

.completion-date, .progress-info {
    color: #4CAF50;
    font-size: 0.8rem;
    margin-bottom: 0.5rem;
}

.achievement-stats {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(255,255,255,0.2);
}

.global-completion {
    color: #cccccc;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.loading-more {
    text-align: center;
    padding: 2rem;
    display: none;
}

.loading-more.active {
    display: block;
}

.achievement-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1000;
    display: none;
}

.achievement-modal.active {
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.8);
    backdrop-filter: blur(5px);
}

.modal-content {
    background: linear-gradient(135deg, #1a1a2e, #16213e);
    border: 2px solid #ffd700;
    border-radius: 20px;
    max-width: 600px;
    max-height: 80vh;
    overflow-y: auto;
    position: relative;
    z-index: 1001;
}

.modal-header {
    padding: 1rem 2rem;
    border-bottom: 1px solid rgba(255,255,255,0.2);
    display: flex;
    justify-content: flex-end;
}

.close-btn {
    background: none;
    border: none;
    color: #ffffff;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.close-btn:hover {
    background: rgba(255,255,255,0.1);
    color: #ffd700;
}

.modal-body {
    padding: 2rem;
}

@media (max-width: 768px) {
    .collector-banner {
        flex-direction: column;
        gap: 2rem;
        text-align: center;
    }
    
    .collector-stats {
        flex-direction: row;
        gap: 1rem;
    }
    
    .stat-card {
        min-width: 100px;
        padding: 1rem;
    }
    
    .category-tabs, .achievement-filters {
        justify-content: center;
    }
    
    .search-achievements input {
        width: 200px;
    }
    
    .showcase-grid {
        grid-template-columns: 1fr;
    }
    
    .showcase-item {
        flex-direction: column;
        text-align: center;
    }
    
    .next-grid {
        grid-template-columns: 1fr;
    }
    
    .next-item {
        flex-direction: column;
        text-align: center;
    }
    
    .achievements-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }
    
    .modal-content {
        margin: 1rem;
        max-width: calc(100% - 2rem);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentCategory = 'todos';
    let currentRarity = 'todos';
    let currentStatus = 'todos';
    let currentOffset = 0;
    let isLoading = false;
    let hasMore = true;

    // Category tabs
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;
            setActiveButton(this, '.category-btn');
            currentCategory = category;
            filterAchievements();
        });
    });

    // Rarity filter
    document.querySelectorAll('.rarity-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const rarity = this.dataset.rarity;
            setActiveButton(this, '.rarity-btn');
            currentRarity = rarity;
            filterAchievements();
        });
    });

    // Status filter
    document.querySelectorAll('.status-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const status = this.dataset.status;
            setActiveButton(this, '.status-btn');
            currentStatus = status;
            filterAchievements();
        });
    });

    // Search functionality
    document.getElementById('achievement-search').addEventListener('input', debounce(function() {
        const searchTerm = this.value.toLowerCase();
        searchAchievements(searchTerm);
    }, 300));

    // Lazy loading with Intersection Observer
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !isLoading && hasMore) {
                loadMoreAchievements();
            }
        });
    });

    const loadingElement = document.getElementById('loading-more');
    observer.observe(loadingElement);

    function setActiveButton(activeBtn, selector) {
        document.querySelectorAll(selector).forEach(btn => {
            btn.classList.remove('active');
        });
        activeBtn.classList.add('active');
    }

    function filterAchievements() {
        const cards = document.querySelectorAll('.achievement-card');
        cards.forEach(card => {
            const cardCategory = card.dataset.category;
            const cardRarity = card.dataset.rarity;
            const cardStatus = card.dataset.status;
            
            let show = true;
            
            if (currentCategory !== 'todos' && cardCategory !== currentCategory) {
                show = false;
            }
            
            if (currentRarity !== 'todos' && cardRarity !== currentRarity) {
                show = false;
            }
            
            if (currentStatus !== 'todos' && cardStatus !== currentStatus) {
                show = false;
            }
            
            card.style.display = show ? 'block' : 'none';
            
            // Add filter animation
            if (show) {
                card.style.animation = 'fadeInUp 0.5s ease forwards';
            }
        });
    }

    function searchAchievements(searchTerm) {
        const cards = document.querySelectorAll('.achievement-card');
        cards.forEach(card => {
            const name = card.querySelector('.achievement-name').textContent.toLowerCase();
            const description = card.querySelector('.achievement-description').textContent.toLowerCase();
            const match = name.includes(searchTerm) || description.includes(searchTerm);
            
            if (searchTerm === '') {
                filterAchievements(); // Reset to normal filters
            } else {
                card.style.display = match ? 'block' : 'none';
            }
        });
    }

    function loadMoreAchievements() {
        if (isLoading || !hasMore) return;

        isLoading = true;
        const loadingElement = document.getElementById('loading-more');
        loadingElement.classList.add('active');

        fetch(`/api/logros?offset=${currentOffset}&limit=20&category=${currentCategory}&rarity=${currentRarity}&status=${currentStatus}`)
            .then(response => response.json())
            .then(data => {
                if (data.length < 20) {
                    hasMore = false;
                }
                
                appendAchievements(data);
                currentOffset += 20;
                isLoading = false;
                loadingElement.classList.remove('active');
            })
            .catch(error => {
                isLoading = false;
                loadingElement.classList.remove('active');
                console.error('Error loading more achievements:', error);
            });
    }

    function appendAchievements(achievements) {
        const grid = document.getElementById('achievements-grid');
        
        achievements.forEach(achievement => {
            const card = createAchievementCard(achievement);
            grid.appendChild(card);
        });
    }

    function createAchievementCard(achievement) {
        const card = document.createElement('div');
        card.className = `achievement-card ${achievement.rareza} ${achievement.estado}`;
        card.dataset.category = achievement.categoria;
        card.dataset.rarity = achievement.rareza;
        card.dataset.status = achievement.estado;
        card.onclick = () => showAchievementDetails(achievement.id);
        
        let statusIcon = '';
        if (achievement.estado === 'obtenido') {
            statusIcon = '<div class="completion-mark"><i class="fas fa-check"></i></div>';
        } else if (achievement.estado === 'progreso') {
            statusIcon = `
                <div class="progress-indicator">
                    <div class="progress-ring">
                        <svg viewBox="0 0 36 36">
                            <path class="progress-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <path class="progress-bar" stroke-dasharray="${achievement.progreso || 0}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                        </svg>
                    </div>
                </div>
            `;
        } else {
            statusIcon = '<div class="locked-overlay"><i class="fas fa-lock"></i></div>';
        }
        
        let statusInfo = '';
        if (achievement.estado === 'obtenido') {
            statusInfo = `<div class="completion-date">Obtenido: ${achievement.fecha_obtenido}</div>`;
        } else if (achievement.estado === 'progreso') {
            statusInfo = `<div class="progress-info">Progreso: ${achievement.progreso_actual}/${achievement.progreso_total}</div>`;
        }
        
        card.innerHTML = `
            <div class="achievement-icon-container">
                <img src="${achievement.icono}" alt="${achievement.nombre}" class="achievement-icon">
                ${statusIcon}
                <div class="rarity-border ${achievement.rareza}"></div>
            </div>
            
            <div class="achievement-info">
                <h3 class="achievement-name">${achievement.nombre}</h3>
                <p class="achievement-description">${achievement.descripcion}</p>
                
                <div class="achievement-meta">
                    <div class="achievement-rarity ${achievement.rareza}">
                        ${achievement.rareza.charAt(0).toUpperCase() + achievement.rareza.slice(1)}
                    </div>
                    <div class="achievement-points">
                        ${achievement.puntos} pts
                    </div>
                </div>
                
                ${statusInfo}
                
                <div class="achievement-stats">
                    <div class="global-completion">
                        <i class="fas fa-users"></i>
                        ${achievement.porcentaje_global}% de jugadores
                    </div>
                </div>
            </div>
        `;
        
        return card;
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
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
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .achievement-card {
            animation: fadeInUp 0.5s ease forwards;
        }
    `;
    document.head.appendChild(style);
});

function showAchievementDetails(achievementId) {
    const modal = document.getElementById('achievement-modal');
    const modalBody = document.getElementById('modal-body');
    
    // Show loading state
    modalBody.innerHTML = `
        <div class="modal-loading">
            <div class="destiny-spinner"></div>
            <p>Cargando detalles del logro...</p>
        </div>
    `;
    
    modal.classList.add('active');
    
    // Fetch achievement details
    fetch(`/api/logros/${achievementId}`)
        .then(response => response.json())
        .then(achievement => {
            renderAchievementModal(achievement);
        })
        .catch(error => {
            console.error('Error loading achievement details:', error);
            modalBody.innerHTML = `
                <div class="modal-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Error al cargar los detalles del logro</p>
                </div>
            `;
        });
}

function renderAchievementModal(achievement) {
    const modalBody = document.getElementById('modal-body');
    
    let progressSection = '';
    if (achievement.estado === 'progreso') {
        progressSection = `
            <div class="modal-progress">
                <h3>Progreso Actual</h3>
                <div class="progress-container">
                    <div class="progress-bar-large">
                        <div class="progress-fill" style="width: ${achievement.progreso}%"></div>
                    </div>
                    <div class="progress-text-large">${achievement.progreso_actual}/${achievement.progreso_total} ${achievement.unidad}</div>
                </div>
                <div class="requirements">
                    <h4>Requisitos:</h4>
                    <ul>
                        ${achievement.requisitos.map(req => `<li>${req}</li>`).join('')}
                    </ul>
                </div>
            </div>
        `;
    }
    
    let historySection = '';
    if (achievement.estado === 'obtenido') {
        historySection = `
            <div class="modal-history">
                <h3>Detalles de Obtención</h3>
                <div class="completion-info">
                    <div class="completion-stat">
                        <i class="fas fa-calendar"></i>
                        <span>Fecha: ${achievement.fecha_obtenido}</span>
                    </div>
                    <div class="completion-stat">
                        <i class="fas fa-clock"></i>
                        <span>Tiempo jugado: ${achievement.tiempo_para_obtener}</span>
                    </div>
                    <div class="completion-stat">
                        <i class="fas fa-trophy"></i>
                        <span>Puntos obtenidos: ${achievement.puntos}</span>
                    </div>
                </div>
            </div>
        `;
    }
    
    modalBody.innerHTML = `
        <div class="modal-achievement">
            <div class="modal-header-info">
                <div class="modal-icon-container">
                    <img src="${achievement.icono}" alt="${achievement.nombre}">
                    <div class="modal-rarity-glow ${achievement.rareza}"></div>
                </div>
                <div class="modal-title">
                    <h2>${achievement.nombre}</h2>
                    <div class="modal-rarity ${achievement.rareza}">${achievement.rareza.toUpperCase()}</div>
                </div>
            </div>
            
            <div class="modal-description">
                <p>${achievement.descripcion}</p>
                ${achievement.descripcion_larga ? `<p class="extended-description">${achievement.descripcion_larga}</p>` : ''}
            </div>
            
            ${progressSection}
            ${historySection}
            
            <div class="modal-stats">
                <h3>Estadísticas Globales</h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-value">${achievement.porcentaje_global}%</div>
                        <div class="stat-label">de jugadores lo tienen</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">${achievement.total_completados}</div>
                        <div class="stat-label">jugadores completaron</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">${achievement.dificultad}/5</div>
                        <div class="stat-label">dificultad</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">${achievement.puntos}</div>
                        <div class="stat-label">puntos de logro</div>
                    </div>
                </div>
            </div>
            
            ${achievement.logros_relacionados ? `
                <div class="modal-related">
                    <h3>Logros Relacionados</h3>
                    <div class="related-achievements">
                        ${achievement.logros_relacionados.map(related => `
                            <div class="related-item" onclick="showAchievementDetails(${related.id})">
                                <img src="${related.icono}" alt="${related.nombre}">
                                <span>${related.nombre}</span>
                            </div>
                        `).join('')}
                    </div>
                </div>
            ` : ''}
        </div>
    `;
}

function closeAchievementModal() {
    const modal = document.getElementById('achievement-modal');
    modal.classList.remove('active');
}

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAchievementModal();
    }
});
</script>

<style>
/* Additional modal styles */
.modal-loading, .modal-error {
    text-align: center;
    padding: 3rem;
}

.modal-error {
    color: #f44336;
}

.modal-error i {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.modal-achievement {
    color: #ffffff;
}

.modal-header-info {
    display: flex;
    align-items: center;
    gap: 2rem;
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 2px solid rgba(255,255,255,0.2);
}

.modal-icon-container {
    position: relative;
    width: 100px;
    height: 100px;
}

.modal-icon-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 15px;
}

.modal-rarity-glow {
    position: absolute;
    top: -5px;
    left: -5px;
    right: -5px;
    bottom: -5px;
    border-radius: 20px;
    z-index: -1;
}

.modal-rarity-glow.exotico {
    background: linear-gradient(45deg, #ffd700, #ffed4a);
    box-shadow: 0 0 30px rgba(255,215,0,0.5);
}

.modal-rarity-glow.legendario {
    background: linear-gradient(45deg, #8a2be2, #9370db);
    box-shadow: 0 0 30px rgba(138,43,226,0.5);
}

.modal-title h2 {
    margin: 0 0 0.5rem 0;
    color: #ffd700;
    font-size: 2rem;
}

.modal-rarity {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: bold;
    font-size: 0.9rem;
}

.modal-rarity.exotico {
    background: #ffd700;
    color: #000000;
}

.modal-rarity.legendario {
    background: #8a2be2;
    color: #ffffff;
}

.modal-description {
    margin-bottom: 2rem;
    line-height: 1.6;
}

.extended-description {
    margin-top: 1rem;
    padding: 1rem;
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
    font-style: italic;
}

.modal-progress {
    margin-bottom: 2rem;
    padding: 2rem;
    background: rgba(255,215,0,0.1);
    border-radius: 15px;
    border: 2px solid #ffd700;
}

.modal-progress h3 {
    color: #ffd700;
    margin-bottom: 1rem;
}

.progress-container {
    margin-bottom: 1.5rem;
}

.progress-bar-large {
    width: 100%;
    height: 20px;
    background: rgba(255,255,255,0.2);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #ffd700, #ffed4a);
    border-radius: 10px;
    transition: width 0.5s ease;
}

.progress-text-large {
    color: #ffd700;
    font-weight: bold;
    text-align: center;
}

.requirements h4 {
    color: #ffd700;
    margin-bottom: 0.5rem;
}

.requirements ul {
    list-style: none;
    padding: 0;
}

.requirements li {
    padding: 0.5rem 0;
    padding-left: 1.5rem;
    position: relative;
}

.requirements li:before {
    content: '⭐';
    position: absolute;
    left: 0;
    color: #ffd700;
}

.modal-history {
    margin-bottom: 2rem;
    padding: 2rem;
    background: rgba(76,175,80,0.1);
    border-radius: 15px;
    border: 2px solid #4CAF50;
}

.modal-history h3 {
    color: #4CAF50;
    margin-bottom: 1rem;
}

.completion-info {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.completion-stat {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.completion-stat i {
    color: #4CAF50;
    width: 20px;
}

.modal-stats {
    margin-bottom: 2rem;
}

.modal-stats h3 {
    color: #ffd700;
    margin-bottom: 1rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 1rem;
}

.stat-item {
    text-align: center;
    padding: 1rem;
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: bold;
    color: #ffd700;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    color: #cccccc;
}

.modal-related h3 {
    color: #ffd700;
    margin-bottom: 1rem;
}

.related-achievements {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.related-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 100px;
}

.related-item:hover {
    background: rgba(255,255,255,0.2);
    transform: translateY(-2px);
}

.related-item img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
}

.related-item span {
    font-size: 0.8rem;
    text-align: center;
    color: #cccccc;
}

@media (max-width: 768px) {
    .modal-header-info {
        flex-direction: column;
        text-align: center;
    }
    
    .completion-info {
        gap: 0.5rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .related-achievements {
        justify-content: center;
    }
}
</style>
@endsection