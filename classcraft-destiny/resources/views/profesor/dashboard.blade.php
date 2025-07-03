{{-- resources/views/profesor/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Consola de Comando')

@section('content')
<div class="container mx-auto px-4 py-6 space-y-6">
    
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
        <div>
            <h1 class="destiny-heading text-3xl font-bold mb-2">
                Consola de Comando - Torre
            </h1>
            <p class="destiny-text-muted">
                Panel de control para Instructor {{ auth()->user()->nombreCompleto() }}
            </p>
        </div>
        
        <!-- Quick Actions -->
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('profesor.crear-clase.form') }}" 
               class="destiny-btn px-6 py-3 rounded-lg font-semibold text-sm transition-all duration-300 transform hover:scale-105">
                <span class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span>Nueva Operación</span>
                </span>
            </a>
            
            <button onclick="selectRandomStudent()" 
                    class="destiny-btn px-6 py-3 rounded-lg font-semibold text-sm transition-all duration-300 transform hover:scale-105"
                    style="border-color: var(--destiny-gold); color: var(--destiny-gold);">
                <span class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>Guardián Aleatorio</span>
                </span>
            </button>
        </div>
    </div>
    
    <!-- Statistics Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Active Classes -->
        <div class="holo-panel rounded-xl p-6 destiny-glow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="destiny-text-muted text-sm font-medium">Operaciones Activas</p>
                    <p class="destiny-heading text-2xl font-bold">{{ $estadisticas['clases_activas'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg destiny-glow-gold flex items-center justify-center"
                     style="background: linear-gradient(135deg, var(--destiny-gold), rgba(199, 184, 138, 0.7));">
                    <svg class="w-6 h-6 text-yellow-900" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-green-400">+{{ $estadisticas['clases_hoy'] }}</span>
                <span class="destiny-text-muted ml-2">sesiones hoy</span>
            </div>
        </div>
        
        <!-- Total Guardians -->
        <div class="holo-panel rounded-xl p-6 destiny-glow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="destiny-text-muted text-sm font-medium">Guardianes Totales</p>
                    <p class="destiny-heading text-2xl font-bold">{{ $estadisticas['total_estudiantes'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg destiny-glow-purple flex items-center justify-center"
                     style="background: linear-gradient(135deg, var(--destiny-purple), rgba(182, 161, 228, 0.7));">
                    <svg class="w-6 h-6 text-purple-900" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zM4 18v-6h3v7c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-7h3v6l2.54-7.63A1.5 1.5 0 0014.18 9H9.82c-.73 0-1.36.52-1.5 1.22L6 18H4z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-cyan-400">{{ number_format(($estadisticas['total_estudiantes'] / max($estadisticas['clases_activas'], 1)), 1) }}</span>
                <span class="destiny-text-muted ml-2">promedio por clase</span>
            </div>
        </div>
        
        <!-- Pending Tasks -->
        <div class="holo-panel rounded-xl p-6 destiny-glow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="destiny-text-muted text-sm font-medium">Tareas Pendientes</p>
                    <p class="destiny-heading text-2xl font-bold">{{ $estadisticas['tareas_pendientes'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg destiny-glow flex items-center justify-center"
                     style="background: linear-gradient(135deg, var(--destiny-cyan), rgba(110, 193, 228, 0.7));">
                    <svg class="w-6 h-6 text-cyan-900" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-orange-400">Prioridad Alta</span>
            </div>
        </div>
        
        <!-- Alerts -->
        <div class="holo-panel rounded-xl p-6 destiny-glow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="destiny-text-muted text-sm font-medium">Alertas Críticas</p>
                    <p class="destiny-heading text-2xl font-bold">{{ $estadisticas['alertas_criticas'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center"
                     style="background: linear-gradient(135deg, var(--destiny-danger), rgba(231, 76, 60, 0.7)); box-shadow: 0 0 10px rgba(231, 76, 60, 0.4);">
                    <svg class="w-6 h-6 text-red-900" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-red-400">Revisar urgente</span>
            </div>
        </div>
    </div>
    
    <!-- Stellar Map Section -->
    <div class="holo-panel rounded-2xl p-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="destiny-heading text-2xl font-bold">
                Mapa Estelar de Operaciones
            </h2>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2 text-sm destiny-text-muted">
                    <div class="w-3 h-3 rounded-full destiny-glow-gold"></div>
                    <span>Activa</span>
                    <div class="w-3 h-3 rounded-full border border-gray-500"></div>
                    <span>Inactiva</span>
                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                    <span>Alertas</span>
                </div>
            </div>
        </div>
        
        <!-- Stellar Map Container -->
        <div class="relative min-h-96 stellar-map" id="stellarMap">
            @if($clases->count() > 0)
                @foreach($clases as $index => $clase)
                    <!-- Class Node -->
                    <div class="absolute class-node {{ $clase['activa'] ? 'active' : 'inactive' }}"
                         style="left: {{ 20 + ($index * 25) % 60 }}%; top: {{ 30 + ($index * 15) % 40 }}%;"
                         data-clase-id="{{ $clase['id'] }}"
                         onclick="openClassDetails({{ $clase['id'] }})">
                        
                        <!-- Node Core -->
                        <div class="relative node-core w-20 h-20 rounded-full flex items-center justify-center cursor-pointer transform transition-all duration-300 hover:scale-110">
                            <!-- Glow Effect -->
                            <div class="absolute inset-0 rounded-full {{ $clase['activa'] ? 'destiny-glow-gold' : 'destiny-glow' }} opacity-70"></div>
                            
                            <!-- Node Content -->
                            <div class="relative z-10 text-center">
                                <div class="text-xs font-bold destiny-heading">
                                    {{ strtoupper(substr($clase['nombre'], 0, 3)) }}
                                </div>
                                <div class="text-xs destiny-text-muted">
                                    {{ $clase['estudiantes_count'] }} G
                                </div>
                            </div>
                            
                            <!-- Progress Ring -->
                            <svg class="absolute inset-0 w-full h-full transform -rotate-90">
                                <circle cx="40" cy="40" r="35" 
                                        fill="none" 
                                        stroke="rgba(110, 193, 228, 0.2)" 
                                        stroke-width="2"/>
                                <circle cx="40" cy="40" r="35" 
                                        fill="none" 
                                        stroke="{{ $clase['activa'] ? 'var(--destiny-gold)' : 'var(--destiny-cyan)' }}" 
                                        stroke-width="2"
                                        stroke-dasharray="220"
                                        stroke-dashoffset="{{ 220 - ($clase['progreso'] * 2.2) }}"
                                        class="transition-all duration-1000"/>
                            </svg>
                            
                            <!-- Alert Indicator -->
                            @if(count($clase['alertas']) > 0)
                                <div class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center text-white text-xs font-bold destiny-glow"
                                     style="box-shadow: 0 0 10px rgba(231, 76, 60, 0.6);">
                                    {{ count($clase['alertas']) }}
                                </div>
                            @endif
                        </div>
                        
                        <!-- Class Info Tooltip -->
                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-20">
                            <div class="holo-panel rounded-lg p-3 text-xs destiny-text whitespace-nowrap">
                                <div class="font-semibold">{{ $clase['nombre'] }}</div>
                                <div class="destiny-text-muted">{{ $clase['estudiantes_count'] }} Guardianes</div>
                                <div class="destiny-text-muted">Progreso: {{ number_format($clase['progreso'], 1) }}%</div>
                                @if($clase['fecha_fin'])
                                    <div class="destiny-text-muted">Fin: {{ $clase['fecha_fin']->format('d/m/Y') }}</div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Connection Lines (if applicable) -->
                        @if($index < $clases->count() - 1)
                            <div class="absolute top-1/2 left-full w-16 h-0.5 bg-gradient-to-r from-cyan-400/50 to-transparent connection-line"></div>
                        @endif
                    </div>
                @endforeach
            @else
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center h-96 text-center">
                    <div class="w-24 h-24 rounded-full destiny-glow-purple flex items-center justify-center mb-6"
                         style="background: linear-gradient(135deg, var(--destiny-bg-secondary), var(--destiny-bg-tertiary));">
                        <svg class="w-12 h-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <h3 class="destiny-heading text-xl font-bold mb-2">
                        Sistema en Espera
                    </h3>
                    <p class="destiny-text-muted mb-6 max-w-md">
                        No hay operaciones configuradas. Inicia tu primera misión de entrenamiento creando una nueva clase.
                    </p>
                    <a href="{{ route('profesor.crear-clase.form') }}" 
                       class="destiny-btn px-8 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                        Inicializar Primera Operación
                    </a>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Recent Activity Section -->
    @if($clases->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Classes -->
            <div class="holo-panel rounded-xl p-6">
                <h3 class="destiny-heading text-lg font-bold mb-4">
                    Operaciones Recientes
                </h3>
                <div class="space-y-4">
                    @foreach($clases->take(3) as $clase)
                        <div class="flex items-center justify-between p-4 rounded-lg bg-black/20 hover:bg-cyan-500/10 transition-colors cursor-pointer"
                             onclick="window.location.href='{{ route('profesor.clase.ver', $clase['id']) }}'">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-lg {{ $clase['activa'] ? 'destiny-glow-gold' : 'destiny-glow' }} flex items-center justify-center"
                                     style="background: {{ $clase['activa'] ? 'var(--destiny-gold)' : 'var(--destiny-cyan)' }};">
                                    <span class="text-xs font-bold text-black">
                                        {{ strtoupper(substr($clase['nombre'], 0, 2)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="destiny-text font-medium">{{ $clase['nombre'] }}</div>
                                    <div class="destiny-text-muted text-sm">{{ $clase['estudiantes_count'] }} Guardianes activos</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="destiny-text text-sm">{{ number_format($clase['progreso'], 1) }}%</div>
                                <div class="destiny-text-muted text-xs">Progreso</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- System Status -->
            <div class="holo-panel rounded-xl p-6">
                <h3 class="destiny-heading text-lg font-bold mb-4">
                    Estado del Sistema
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="destiny-text">Conexión Torre</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 rounded-full bg-green-400 destiny-glow"></div>
                            <span class="text-green-400 text-sm">Estable</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="destiny-text">Base de Datos</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 rounded-full bg-green-400 destiny-glow"></div>
                            <span class="text-green-400 text-sm">Operacional</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="destiny-text">Sincronización</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 rounded-full bg-yellow-400" style="box-shadow: 0 0 5px rgba(244, 244, 5, 0.6);"></div>
                            <span class="text-yellow-400 text-sm">Procesando</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="destiny-text">Última Actualización</span>
                        <span class="destiny-text-muted text-sm">{{ now()->format('H:i') }}</span>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="mt-6 pt-4 border-t border-cyan-500/30">
                    <div class="grid grid-cols-2 gap-3">
                        <button class="destiny-btn p-3 rounded-lg text-xs font-medium transition-all duration-300"
                                onclick="exportData()">
                            <svg class="w-4 h-4 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Exportar
                        </button>
                        <button class="destiny-btn p-3 rounded-lg text-xs font-medium transition-all duration-300"
                                onclick="generateReport()">
                            <svg class="w-4 h-4 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Reportes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Random Student Modal -->
<div id="randomStudentModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="holo-panel rounded-2xl p-8 max-w-md w-full mx-4 destiny-glow">
        <div class="text-center">
            <h3 class="destiny-heading text-xl font-bold mb-6">Selección Aleatoria de Guardián</h3>
            
            <!-- Spinner Wheel -->
            <div class="relative w-32 h-32 mx-auto mb-6">
                <div id="studentWheel" class="w-full h-full rounded-full border-4 border-cyan-400 destiny-glow flex items-center justify-center transition-transform duration-3000 ease-out">
                    <div class="text-center">
                        <div class="w-16 h-16 rounded-full destiny-glow-gold flex items-center justify-center mb-2"
                             style="background: linear-gradient(135deg, var(--destiny-gold), rgba(199, 184, 138, 0.7));">
                            <svg class="w-8 h-8 text-yellow-900" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                        <div id="selectedStudent" class="destiny-text text-sm font-medium">
                            Seleccionando...
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Selected Student Info -->
            <div id="studentInfo" class="hidden space-y-4">
                <div class="holo-panel rounded-lg p-4">
                    <div class="flex items-center space-x-4">
                        <img id="studentAvatar" class="w-12 h-12 rounded-lg destiny-glow-purple" src="" alt="Avatar">
                        <div class="text-left">
                            <div id="studentName" class="destiny-text font-semibold"></div>
                            <div id="studentClass" class="destiny-text-muted text-sm"></div>
                            <div id="studentLevel" class="text-cyan-400 text-sm"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex space-x-4 mt-6">
                <button onclick="closeRandomModal()" 
                        class="flex-1 px-6 py-3 rounded-lg border border-gray-500 text-gray-400 hover:border-gray-400 hover:text-white transition-colors">
                    Cerrar
                </button>
                <button onclick="selectRandomStudent()" 
                        class="flex-1 destiny-btn px-6 py-3 rounded-lg font-semibold transition-all duration-300"
                        id="spinButton">
                    <span class="flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>Girar Ruleta</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Class Details Modal -->
<div id="classDetailsModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="holo-panel rounded-2xl p-8 max-w-2xl w-full mx-4 destiny-glow max-h-96 overflow-y-auto">
        <div class="flex justify-between items-start mb-6">
            <h3 class="destiny-heading text-xl font-bold" id="modalClassName">Detalles de Operación</h3>
            <button onclick="closeClassModal()" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <div id="modalContent" class="space-y-6">
            <!-- Content will be loaded dynamically -->
        </div>
        
        <div class="flex justify-end space-x-4 mt-6 pt-6 border-t border-cyan-500/30">
            <button onclick="closeClassModal()" 
                    class="px-6 py-3 rounded-lg border border-gray-500 text-gray-400 hover:border-gray-400 hover:text-white transition-colors">
                Cerrar
            </button>
            <a id="viewClassLink" href="#" 
               class="destiny-btn px-6 py-3 rounded-lg font-semibold transition-all duration-300">
                Ver Detalles Completos
            </a>
        </div>
    </div>
</div>

<!-- Styles for Stellar Map -->
<style>
    .stellar-map {
        background: 
            radial-gradient(circle at 20% 30%, rgba(110, 193, 228, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(199, 184, 138, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 50% 50%, rgba(182, 161, 228, 0.05) 0%, transparent 70%);
        position: relative;
        overflow: hidden;
    }
    
    .stellar-map::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: 
            radial-gradient(1px 1px at 25% 25%, rgba(110, 193, 228, 0.8), transparent),
            radial-gradient(1px 1px at 75% 75%, rgba(199, 184, 138, 0.6), transparent),
            radial-gradient(1px 1px at 50% 10%, rgba(182, 161, 228, 0.7), transparent),
            radial-gradient(1px 1px at 10% 90%, rgba(110, 193, 228, 0.5), transparent);
        background-size: 200px 200px, 150px 150px, 100px 100px, 80px 80px;
        animation: starTwinkle 8s linear infinite;
        pointer-events: none;
    }
    
    @keyframes starTwinkle {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }
    
    .class-node {
        transition: all 0.3s ease;
        z-index: 10;
    }
    
    .class-node:hover {
        z-index: 20;
    }
    
    .class-node.active .node-core {
        background: radial-gradient(circle, rgba(199, 184, 138, 0.3) 0%, rgba(199, 184, 138, 0.1) 50%, transparent 70%);
    }
    
    .class-node.inactive .node-core {
        background: radial-gradient(circle, rgba(110, 193, 228, 0.2) 0%, rgba(110, 193, 228, 0.05) 50%, transparent 70%);
    }
    
    .connection-line {
        animation: dataFlow 3s linear infinite;
    }
    
    @keyframes dataFlow {
        0% { 
            background: linear-gradient(90deg, transparent 0%, transparent 100%);
        }
        50% { 
            background: linear-gradient(90deg, transparent 0%, rgba(110, 193, 228, 0.6) 50%, transparent 100%);
        }
        100% { 
            background: linear-gradient(90deg, transparent 0%, transparent 100%);
        }
    }
    
    /* Hover effects for nodes */
    .class-node:hover .node-core {
        transform: scale(1.1);
    }
    
    .class-node:hover + .class-node .connection-line {
        animation-duration: 1s;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .class-node {
            position: relative !important;
            display: inline-block;
            margin: 1rem;
        }
        
        .stellar-map {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            min-height: 300px;
        }
        
        .connection-line {
            display: none;
        }
    }
</style>

<!-- Scripts for Dashboard Functionality -->
<script>
    // Global variables
    let isSpinning = false;
    let availableStudents = [];
    
    // Initialize dashboard
    document.addEventListener('DOMContentLoaded', function() {
        loadAvailableStudents();
        initializeNodeAnimations();
        
        // Auto-refresh stats every 30 seconds
        setInterval(refreshStats, 30000);
    });
    
    // Load available students for random selection
    async function loadAvailableStudents() {
        try {
            // This would typically fetch from your API
            availableStudents = @json($clases->flatMap(function($clase) {
                return collect(range(1, $clase['estudiantes_count']))->map(function($i) use ($clase) {
                    return [
                        'nombre' => 'Guardián ' . $i,
                        'clase' => $clase['nombre'],
                        'nivel' => rand(1, 30),
                        'avatar' => '/images/avatars/default-guardian.jpg'
                    ];
                });
            }));
        } catch (error) {
            console.error('Error loading students:', error);
        }
    }
    
    // Random student selection
    function selectRandomStudent() {
        if (availableStudents.length === 0) {
            alert('No hay guardianes disponibles para selección.');
            return;
        }
        
        const modal = document.getElementById('randomStudentModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        if (!isSpinning) {
            spinWheel();
        }
    }
    
    function spinWheel() {
        if (isSpinning) return;
        
        isSpinning = true;
        const wheel = document.getElementById('studentWheel');
        const selectedDiv = document.getElementById('selectedStudent');
        const spinButton = document.getElementById('spinButton');
        const studentInfo = document.getElementById('studentInfo');
        
        // Disable button
        spinButton.disabled = true;
        spinButton.innerHTML = '<div class="loading-ring w-4 h-4 mx-auto"></div>';
        
        // Hide previous result
        studentInfo.classList.add('hidden');
        selectedDiv.textContent = 'Seleccionando...';
        
        // Animate wheel
        const rotations = 5 + Math.random() * 5; // 5-10 full rotations
        const finalRotation = rotations * 360;
        
        wheel.style.transform = `rotate(${finalRotation}deg)`;
        
        // Simulate selection process
        let currentIndex = 0;
        const selectionInterval = setInterval(() => {
            if (currentIndex < availableStudents.length) {
                selectedDiv.textContent = availableStudents[currentIndex].nombre;
                currentIndex++;
            } else {
                currentIndex = 0;
            }
        }, 100);
        
        // Final selection after animation
        setTimeout(() => {
            clearInterval(selectionInterval);
            
            const selectedStudent = availableStudents[Math.floor(Math.random() * availableStudents.length)];
            
            // Update UI with selected student
            document.getElementById('studentName').textContent = selectedStudent.nombre;
            document.getElementById('studentClass').textContent = selectedStudent.clase;
            document.getElementById('studentLevel').textContent = `Nivel ${selectedStudent.nivel}`;
            document.getElementById('studentAvatar').src = selectedStudent.avatar;
            
            selectedDiv.textContent = selectedStudent.nombre;
            studentInfo.classList.remove('hidden');
            
            // Reset button
            isSpinning = false;
            spinButton.disabled = false;
            spinButton.innerHTML = `
                <span class="flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>Nuevo Guardián</span>
                </span>
            `;
        }, 3000);
    }
    
    function closeRandomModal() {
        const modal = document.getElementById('randomStudentModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        
        // Reset wheel
        const wheel = document.getElementById('studentWheel');
        wheel.style.transform = 'rotate(0deg)';
        isSpinning = false;
    }
    
    // Class details modal
    async function openClassDetails(claseId) {
        const modal = document.getElementById('classDetailsModal');
        const content = document.getElementById('modalContent');
        const className = document.getElementById('modalClassName');
        const viewLink = document.getElementById('viewClassLink');
        
        try {
            // Show modal with loading
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            content.innerHTML = '<div class="text-center py-8"><div class="loading-ring w-8 h-8 mx-auto"></div></div>';
            
            // Find class data
            const clase = @json($clases).find(c => c.id == claseId);
            
            if (clase) {
                className.textContent = clase.nombre;
                viewLink.href = `/profesor/clase/${claseId}`;
                
                // Build content
                content.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <h4 class="destiny-text font-semibold mb-2">Información General</h4>
                                <div class="space-y-2 text-sm destiny-text-muted">
                                    <div class="flex justify-between">
                                        <span>Estado:</span>
                                        <span class="${clase.activa ? 'text-green-400' : 'text-gray-400'}">${clase.activa ? 'Activa' : 'Inactiva'}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Guardianes:</span>
                                        <span>${clase.estudiantes_count}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Progreso:</span>
                                        <span>${clase.progreso.toFixed(1)}%</span>
                                    </div>
                                    ${clase.fecha_fin ? `
                                    <div class="flex justify-between">
                                        <span>Finaliza:</span>
                                        <span>${new Date(clase.fecha_fin).toLocaleDateString()}</span>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <h4 class="destiny-text font-semibold mb-2">Estado Operacional</h4>
                                <div class="space-y-2">
                                    ${clase.alertas.length > 0 ? 
                                        clase.alertas.map(alerta => `
                                            <div class="flex items-center space-x-2 text-sm">
                                                <div class="w-2 h-2 rounded-full bg-${alerta.tipo === 'warning' ? 'yellow' : 'red'}-400"></div>
                                                <span class="destiny-text-muted">${alerta.mensaje}</span>
                                            </div>
                                        `).join('') :
                                        '<div class="text-sm destiny-text-muted">Sin alertas activas</div>'
                                    }
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error loading class details:', error);
            content.innerHTML = '<div class="text-center py-8 text-red-400">Error al cargar detalles</div>';
        }
    }
    
    function closeClassModal() {
        const modal = document.getElementById('classDetailsModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    
    // Initialize node animations
    function initializeNodeAnimations() {
        const nodes = document.querySelectorAll('.class-node');
        nodes.forEach((node, index) => {
            // Add staggered entrance animation
            node.style.animationDelay = `${index * 0.2}s`;
            node.classList.add('animate-fade-in');
        });
    }
    
    // Refresh statistics
    async function refreshStats() {
        try {
            // This would typically fetch fresh data from your API
            console.log('Refreshing stats...');
        } catch (error) {
            console.error('Error refreshing stats:', error);
        }
    }
    
    // Export data function
    function exportData() {
        alert('Función de exportación en desarrollo');
    }
    
    // Generate report function
    function generateReport() {
        alert('Función de reportes en desarrollo');
    }
    
    // Close modals on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeRandomModal();
            closeClassModal();
        }
    });
    
    // Add CSS animation class
    const style = document.createElement('style');
    style.textContent = `
        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;
    document.head.appendChild(style);
</script>
@endsection