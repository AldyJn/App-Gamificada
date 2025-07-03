{{-- resources/views/estudiante/rankings.blade.php --}}
@extends('layouts.destiny')

@section('title', 'Rankings - Torre de Vanguardia')

@section('content')
<div class="destiny-container">
    {{-- Header con Mi Posición --}}
    <div class="ranking-header">
        <div class="guardian-banner">
            <div class="power-level">{{ $mi_posicion->power_level ?? 0 }}</div>
            <div class="guardian-info">
                <h1>Torre de Vanguardia</h1>
                <p>Clasificaciones de los Guardianes más poderosos</p>
            </div>
            <div class="my-position">
                <div class="position-card">
                    <div class="position-number">#{{ $mi_posicion->posicion ?? '---' }}</div>
                    <div class="position-label">Mi Posición</div>
                    <div class="position-trend {{ $mi_posicion->tendencia ?? 'neutral' }}">
                        <i class="fas fa-arrow-{{ $mi_posicion->tendencia === 'subiendo' ? 'up' : ($mi_posicion->tendencia === 'bajando' ? 'down' : 'right') }}"></i>
                        {{ ucfirst($mi_posicion->tendencia ?? 'estable') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtros y Categorías --}}
    <div class="ranking-controls">
        <div class="ranking-tabs">
            <button class="tab-btn active" data-tab="global">Global</button>
            <button class="tab-btn" data-tab="clase">Por Clase</button>
            <button class="tab-btn" data-tab="semanal">Semanal</button>
            <button class="tab-btn" data-tab="materia">Por Materia</button>
        </div>
        
        <div class="ranking-filters">
            <select id="clase-filter" class="destiny-select">
                <option value="">Todas las Clases</option>
                <option value="cazador">Cazador</option>
                <option value="hechicero">Hechicero</option>
                <option value="titan">Titán</option>
            </select>
            
            <select id="nivel-filter" class="destiny-select">
                <option value="">Todos los Niveles</option>
                <option value="1-10">Nivel 1-10</option>
                <option value="11-20">Nivel 11-20</option>
                <option value="21-30">Nivel 21-30</option>
                <option value="31+">Nivel 31+</option>
            </select>
            
            <div class="search-box">
                <input type="text" id="guardian-search" placeholder="Buscar Guardián...">
                <i class="fas fa-search"></i>
            </div>
        </div>
    </div>

    {{-- Podium Top 3 --}}
    <div class="podium-container">
        <div class="podium">
            {{-- Segundo Lugar --}}
            @if(isset($rankings['global'][1]))
            <div class="podium-position second">
                <div class="guardian-avatar">
                    <img src="{{ $rankings['global'][1]->avatar ?? asset('images/default-avatar.png') }}" alt="Avatar">
                    <div class="class-emblem {{ $rankings['global'][1]->clase_rpg }}"></div>
                </div>
                <div class="guardian-name">{{ $rankings['global'][1]->nombre }}</div>
                <div class="power-level">{{ $rankings['global'][1]->power_level }}</div>
                <div class="position-medal silver">2</div>
            </div>
            @endif

            {{-- Primer Lugar --}}
            @if(isset($rankings['global'][0]))
            <div class="podium-position first">
                <div class="crown">👑</div>
                <div class="guardian-avatar">
                    <img src="{{ $rankings['global'][0]->avatar ?? asset('images/default-avatar.png') }}" alt="Avatar">
                    <div class="class-emblem {{ $rankings['global'][0]->clase_rpg }}"></div>
                </div>
                <div class="guardian-name">{{ $rankings['global'][0]->nombre }}</div>
                <div class="power-level">{{ $rankings['global'][0]->power_level }}</div>
                <div class="position-medal gold">1</div>
            </div>
            @endif

            {{-- Tercer Lugar --}}
            @if(isset($rankings['global'][2]))
            <div class="podium-position third">
                <div class="guardian-avatar">
                    <img src="{{ $rankings['global'][2]->avatar ?? asset('images/default-avatar.png') }}" alt="Avatar">
                    <div class="class-emblem {{ $rankings['global'][2]->clase_rpg }}"></div>
                </div>
                <div class="guardian-name">{{ $rankings['global'][2]->nombre }}</div>
                <div class="power-level">{{ $rankings['global'][2]->power_level }}</div>
                <div class="position-medal bronze">3</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Lista de Rankings --}}
    <div class="ranking-content">
        <div class="tab-content active" id="global-tab">
            <div class="ranking-list" id="global-ranking">
                @foreach($rankings['global'] as $index => $guardian)
                    @if($index >= 3)
                    <div class="ranking-item {{ $guardian->id === auth()->id() ? 'my-rank' : '' }}">
                        <div class="rank-position">{{ $index + 1 }}</div>
                        <div class="guardian-info">
                            <div class="avatar-container">
                                <img src="{{ $guardian->avatar ?? asset('images/default-avatar.png') }}" alt="Avatar">
                                <div class="class-indicator {{ $guardian->clase_rpg }}"></div>
                            </div>
                            <div class="guardian-details">
                                <div class="guardian-name">{{ $guardian->nombre }}</div>
                                <div class="guardian-level">Nivel {{ $guardian->nivel }}</div>
                            </div>
                        </div>
                        <div class="guardian-stats">
                            <div class="power-level">{{ $guardian->power_level }}</div>
                            <div class="experience">{{ number_format($guardian->experiencia_total) }} XP</div>
                        </div>
                        <div class="rank-actions">
                            <button class="btn-icon" onclick="viewProfile({{ $guardian->id }})">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
            
            {{-- Lazy Loading Indicator --}}
            <div class="loading-indicator" id="loading-global">
                <div class="destiny-spinner"></div>
                <p>Cargando más guardianes...</p>
            </div>
        </div>

        {{-- Otras pestañas similar structure --}}
        <div class="tab-content" id="clase-tab">
            <div class="clase-selector">
                <button class="clase-btn active" data-clase="cazador">Cazador</button>
                <button class="clase-btn" data-clase="hechicero">Hechicero</button>
                <button class="clase-btn" data-clase="titan">Titán</button>
            </div>
            <div class="ranking-list" id="clase-ranking">
                {{-- Contenido cargado dinámicamente --}}
            </div>
        </div>

        <div class="tab-content" id="semanal-tab">
            <div class="ranking-list" id="semanal-ranking">
                {{-- Contenido cargado dinámicamente --}}
            </div>
        </div>

        <div class="tab-content" id="materia-tab">
            <div class="materia-selector">
                <button class="materia-btn active" data-materia="matematicas">Matemáticas</button>
                <button class="materia-btn" data-materia="fisica">Física</button>
                <button class="materia-btn" data-materia="quimica">Química</button>
            </div>
            <div class="ranking-list" id="materia-ranking">
                {{-- Contenido cargado dinámicamente --}}
            </div>
        </div>
    </div>
</div>

<style>
.destiny-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #16213e 100%);
    color: #ffffff;
}

.ranking-header {
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

.power-level {
    font-size: 3rem;
    font-weight: bold;
    color: #ffd700;
    text-shadow: 0 0 20px rgba(255,215,0,0.5);
}

.guardian-info h1 {
    font-size: 2.5rem;
    margin: 0;
    background: linear-gradient(45deg, #ffd700, #ff6b35);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.my-position .position-card {
    background: rgba(255,255,255,0.1);
    border: 2px solid #ffd700;
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    backdrop-filter: blur(10px);
}

.position-number {
    font-size: 2rem;
    font-weight: bold;
    color: #ffd700;
}

.position-trend {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.position-trend.subiendo { color: #4CAF50; }
.position-trend.bajando { color: #f44336; }
.position-trend.neutral { color: #ffd700; }

.ranking-controls {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.ranking-tabs {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
}

.tab-btn {
    background: rgba(255,255,255,0.1);
    border: 2px solid transparent;
    color: #ffffff;
    padding: 1rem 2rem;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.tab-btn.active {
    background: linear-gradient(45deg, #ffd700, #ff6b35);
    border-color: #ffd700;
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(255,215,0,0.3);
}

.ranking-filters {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.destiny-select {
    background: rgba(255,255,255,0.1);
    border: 2px solid #ffd700;
    color: #ffffff;
    padding: 0.8rem 1.5rem;
    border-radius: 25px;
    backdrop-filter: blur(10px);
}

.search-box {
    position: relative;
}

.search-box input {
    background: rgba(255,255,255,0.1);
    border: 2px solid #ffd700;
    color: #ffffff;
    padding: 0.8rem 1.5rem 0.8rem 3rem;
    border-radius: 25px;
    width: 300px;
    backdrop-filter: blur(10px);
}

.search-box i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #ffd700;
}

.podium-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 3rem 2rem;
}

.podium {
    display: flex;
    justify-content: center;
    align-items: end;
    gap: 2rem;
    perspective: 1000px;
}

.podium-position {
    text-align: center;
    transform-style: preserve-3d;
    transition: transform 0.3s ease;
}

.podium-position:hover {
    transform: rotateY(10deg) translateZ(20px);
}

.podium-position.first {
    order: 2;
    transform: scale(1.1);
}

.podium-position.second {
    order: 1;
}

.podium-position.third {
    order: 3;
}

.crown {
    font-size: 2rem;
    margin-bottom: 1rem;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.guardian-avatar {
    position: relative;
    width: 80px;
    height: 80px;
    margin: 0 auto 1rem;
}

.guardian-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    border: 3px solid #ffd700;
    object-fit: cover;
}

.class-emblem {
    position: absolute;
    bottom: -5px;
    right: -5px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 2px solid #ffd700;
}

.class-emblem.cazador { background: linear-gradient(45deg, #00ff00, #32cd32); }
.class-emblem.hechicero { background: linear-gradient(45deg, #9370db, #8a2be2); }
.class-emblem.titan { background: linear-gradient(45deg, #ff4500, #ff6b35); }

.position-medal {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
    margin: 1rem auto;
    box-shadow: 0 0 20px rgba(255,215,0,0.5);
}

.position-medal.gold { background: linear-gradient(45deg, #ffd700, #ffed4a); }
.position-medal.silver { background: linear-gradient(45deg, #c0c0c0, #e8e8e8); }
.position-medal.bronze { background: linear-gradient(45deg, #cd7f32, #daa520); }

.ranking-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem 3rem;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.ranking-list {
    background: rgba(255,255,255,0.05);
    border-radius: 15px;
    padding: 2rem;
    backdrop-filter: blur(10px);
}

.ranking-item {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    margin-bottom: 1rem;
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.ranking-item:hover {
    background: rgba(255,255,255,0.15);
    transform: translateX(10px);
    border-color: #ffd700;
}

.ranking-item.my-rank {
    background: linear-gradient(90deg, rgba(255,215,0,0.2), rgba(138,43,226,0.2));
    border-color: #ffd700;
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

.rank-position {
    font-size: 1.5rem;
    font-weight: bold;
    color: #ffd700;
    min-width: 60px;
}

.guardian-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

.avatar-container {
    position: relative;
}

.avatar-container img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 2px solid #ffd700;
    object-fit: cover;
}

.class-indicator {
    position: absolute;
    bottom: -2px;
    right: -2px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid #ffd700;
}

.guardian-details {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}

.guardian-name {
    font-weight: bold;
    color: #ffffff;
}

.guardian-level {
    color: #cccccc;
    font-size: 0.9rem;
}

.guardian-stats {
    display: flex;
    flex-direction: column;
    align-items: end;
    gap: 0.3rem;
}

.guardian-stats .power-level {
    font-size: 1.2rem;
    font-weight: bold;
    color: #ffd700;
}

.guardian-stats .experience {
    color: #cccccc;
    font-size: 0.9rem;
}

.rank-actions {
    margin-left: 1rem;
}

.btn-icon {
    background: rgba(255,255,255,0.1);
    border: 2px solid #ffd700;
    color: #ffd700;
    padding: 0.5rem;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-icon:hover {
    background: #ffd700;
    color: #000000;
    transform: scale(1.1);
}

.loading-indicator {
    text-align: center;
    padding: 2rem;
    display: none;
}

.loading-indicator.active {
    display: block;
}

.destiny-spinner {
    width: 50px;
    height: 50px;
    border: 3px solid rgba(255,215,0,0.3);
    border-top: 3px solid #ffd700;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.clase-selector, .materia-selector {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    justify-content: center;
}

.clase-btn, .materia-btn {
    background: rgba(255,255,255,0.1);
    border: 2px solid transparent;
    color: #ffffff;
    padding: 1rem 2rem;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.clase-btn.active, .materia-btn.active {
    background: linear-gradient(45deg, #ffd700, #ff6b35);
    border-color: #ffd700;
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(255,215,0,0.3);
}

@media (max-width: 768px) {
    .guardian-banner {
        flex-direction: column;
        gap: 2rem;
    }
    
    .ranking-tabs {
        flex-wrap: wrap;
    }
    
    .ranking-filters {
        flex-direction: column;
    }
    
    .search-box input {
        width: 100%;
    }
    
    .podium {
        flex-direction: column;
        align-items: center;
    }
    
    .podium-position {
        order: unset !important;
        transform: scale(1) !important;
    }
    
    .ranking-item {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentTab = 'global';
    let currentOffset = 0;
    let isLoading = false;
    let hasMore = true;

    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tabName = this.dataset.tab;
            switchTab(tabName);
        });
    });

    function switchTab(tabName) {
        // Update active tab button
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');

        // Update active tab content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        document.getElementById(`${tabName}-tab`).classList.add('active');

        currentTab = tabName;
        currentOffset = 0;
        hasMore = true;
        
        // Load initial data for the new tab
        loadRankingData(tabName);
    }

    // Lazy loading with Intersection Observer
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !isLoading && hasMore) {
                loadMoreRankings();
            }
        });
    });

    // Start observing the loading indicator
    const loadingIndicator = document.getElementById('loading-global');
    observer.observe(loadingIndicator);

    // Search functionality
    document.getElementById('guardian-search').addEventListener('input', debounce(function() {
        const searchTerm = this.value;
        searchGuardians(searchTerm);
    }, 300));

    // Filters
    document.getElementById('clase-filter').addEventListener('change', function() {
        applyFilters();
    });

    document.getElementById('nivel-filter').addEventListener('change', function() {
        applyFilters();
    });

    // Class/Subject selectors
    document.querySelectorAll('.clase-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const clase = this.dataset.clase;
            setActiveButton(this, '.clase-btn');
            loadRankingData('clase', clase);
        });
    });

    document.querySelectorAll('.materia-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const materia = this.dataset.materia;
            setActiveButton(this, '.materia-btn');
            loadRankingData('materia', materia);
        });
    });

    function setActiveButton(activeBtn, selector) {
        document.querySelectorAll(selector).forEach(btn => {
            btn.classList.remove('active');
        });
        activeBtn.classList.add('active');
    }

    function loadRankingData(tab, filter = null) {
        const container = document.getElementById(`${tab}-ranking`);
        showLoading(container);
        
        fetch(`/api/rankings/${tab}?filter=${filter || ''}`)
            .then(response => response.json())
            .then(data => {
                hideLoading(container);
                renderRankingData(container, data);
            })
            .catch(error => {
                hideLoading(container);
                console.error('Error loading ranking data:', error);
            });
    }

    function loadMoreRankings() {
        if (isLoading || !hasMore) return;

        isLoading = true;
        const loadingIndicator = document.getElementById(`loading-${currentTab}`);
        loadingIndicator.classList.add('active');

        fetch(`/api/rankings/${currentTab}?offset=${currentOffset}&limit=20`)
            .then(response => response.json())
            .then(data => {
                if (data.length < 20) {
                    hasMore = false;
                }
                
                appendRankingData(data);
                currentOffset += 20;
                isLoading = false;
                loadingIndicator.classList.remove('active');
            })
            .catch(error => {
                isLoading = false;
                loadingIndicator.classList.remove('active');
                console.error('Error loading more rankings:', error);
            });
    }

    function renderRankingData(container, data) {
        container.innerHTML = data.map((guardian, index) => {
            const position = currentOffset + index + 1;
            return createRankingItem(guardian, position);
        }).join('');
    }

    function appendRankingData(data) {
        const container = document.getElementById(`${currentTab}-ranking`);
        const newItems = data.map((guardian, index) => {
            const position = currentOffset + index + 1;
            return createRankingItem(guardian, position);
        }).join('');
        
        container.insertAdjacentHTML('beforeend', newItems);
    }

    function createRankingItem(guardian, position) {
        const isMyRank = guardian.id === {{ auth()->id() ?? 'null' }};
        return `
            <div class="ranking-item ${isMyRank ? 'my-rank' : ''}" data-guardian-id="${guardian.id}">
                <div class="rank-position">${position}</div>
                <div class="guardian-info">
                    <div class="avatar-container">
                        <img src="${guardian.avatar || '/images/default-avatar.png'}" alt="Avatar">
                        <div class="class-indicator ${guardian.clase_rpg}"></div>
                    </div>
                    <div class="guardian-details">
                        <div class="guardian-name">${guardian.nombre}</div>
                        <div class="guardian-level">Nivel ${guardian.nivel}</div>
                    </div>
                </div>
                <div class="guardian-stats">
                    <div class="power-level">${guardian.power_level}</div>
                    <div class="experience">${guardian.experiencia_total.toLocaleString()} XP</div>
                </div>
                <div class="rank-actions">
                    <button class="btn-icon" onclick="viewProfile(${guardian.id})">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
        `;
    }

    function searchGuardians(searchTerm) {
        const items = document.querySelectorAll('.ranking-item');
        items.forEach(item => {
            const guardianName = item.querySelector('.guardian-name').textContent.toLowerCase();
            const match = guardianName.includes(searchTerm.toLowerCase());
            item.style.display = match ? 'flex' : 'none';
        });
    }

    function applyFilters() {
        const claseFilter = document.getElementById('clase-filter').value;
        const nivelFilter = document.getElementById('nivel-filter').value;
        
        const items = document.querySelectorAll('.ranking-item');
        items.forEach(item => {
            let show = true;
            
            if (claseFilter) {
                const classIndicator = item.querySelector('.class-indicator');
                if (!classIndicator.classList.contains(claseFilter)) {
                    show = false;
                }
            }
            
            if (nivelFilter && show) {
                const level = parseInt(item.querySelector('.guardian-level').textContent.match(/\d+/)[0]);
                const [min, max] = nivelFilter.split('-').map(n => parseInt(n) || Infinity);
                if (level < min || level > max) {
                    show = false;
                }
            }
            
            item.style.display = show ? 'flex' : 'none';
        });
    }

    function showLoading(container) {
        container.innerHTML = '<div class="destiny-spinner"></div>';
    }

    function hideLoading(container) {
        container.querySelector('.destiny-spinner')?.remove();
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
});

function viewProfile(guardianId) {
    window.location.href = `/estudiante/perfil/${guardianId}`;
}
</script>
@endsection