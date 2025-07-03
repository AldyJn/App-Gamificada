{{-- resources/views/estudiante/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Centro de Operaciones')

@section('content')
<div class="container mx-auto px-4 py-6 space-y-6">
    
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
        <div>
            <h1 class="destiny-heading text-3xl font-bold mb-2">
                Centro de Operaciones
            </h1>
            <p class="destiny-text-muted">
                Bienvenido, Guardián {{ auth()->user()->nombreCompleto() }}
            </p>
        </div>
        
        <!-- Quick Actions -->
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('estudiante.unirse-clase.form') }}" 
               class="destiny-btn px-6 py-3 rounded-lg font-semibold text-sm transition-all duration-300 transform hover:scale-105">
                <span class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Unirse a Fireteam</span>
                </span>
            </a>
            
            <a href="{{ route('estudiante.rankings') }}" 
               class="destiny-btn px-6 py-3 rounded-lg font-semibold text-sm transition-all duration-300 transform hover:scale-105"
               style="border-color: var(--destiny-purple); color: var(--destiny-purple);">
                <span class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span>Ver Rankings</span>
                </span>
            </a>
        </div>
    </div>
    
    <!-- Guardián Central Display -->
    <div class="relative min-h-96">
        <div class="holo-panel rounded-3xl p-8 overflow-hidden relative">
            <!-- Background Effects -->
            <div class="absolute inset-0 guardian-bg"></div>
            
            <!-- Main Guardian Section -->
            <div class="relative z-10 flex flex-col lg:flex-row items-center justify-center space-y-8 lg:space-y-0 lg:space-x-12">
                
                <!-- Left Panel - Stats -->
                <div class="lg:w-1/3 space-y-6">
                    <!-- Guardian Info -->
                    <div class="text-center lg:text-left">
                        <h2 class="destiny-heading text-xl font-bold mb-2">
                            @if($personajePrincipal)
                                {{ $personajePrincipal->nombre_personaje }}
                            @else
                                Guardián en Espera
                            @endif
                        </h2>
                        <p class="destiny-text-muted text-sm">
                            @if($personajePrincipal)
                                {{ $personajePrincipal->claseRpg->nombre }} - Power Level {{ $personajePrincipal->power_level ?? 750 }}
                            @else
                                Pendiente de asignación a Fireteam
                            @endif
                        </p>
                    </div>
                    
                    @if($personajePrincipal)
                        <!-- Resource Bars -->
                        <div class="space-y-4">
                            <!-- Health Bar -->
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="destiny-text">Salud</span>
                                    <span class="destiny-text">{{ $personajePrincipal->salud_actual }}/{{ $personajePrincipal->salud_maxima }}</span>
                                </div>
                                <div class="h-3 rounded-full bg-black/40 overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-red-500 to-red-400 rounded-full transition-all duration-1000 destiny-glow"
                                         style="width: {{ ($personajePrincipal->salud_actual / $personajePrincipal->salud_maxima) * 100 }}%;"></div>
                                </div>
                            </div>
                            
                            <!-- Energy Bar -->
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="destiny-text">Energía</span>
                                    <span class="destiny-text">{{ $personajePrincipal->energia_actual }}/{{ $personajePrincipal->energia_maxima }}</span>
                                </div>
                                <div class="h-3 rounded-full bg-black/40 overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full transition-all duration-1000 destiny-glow"
                                         style="width: {{ ($personajePrincipal->energia_actual / $personajePrincipal->energia_maxima) * 100 }}%;"></div>
                                </div>
                            </div>
                            
                            <!-- Light Bar -->
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="destiny-text">Luz</span>
                                    <span class="destiny-text">{{ $personajePrincipal->luz_actual }}/{{ $personajePrincipal->luz_maxima }}</span>
                                </div>
                                <div class="h-3 rounded-full bg-black/40 overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-yellow-400 to-orange-400 rounded-full transition-all duration-1000"
                                         style="width: {{ ($personajePrincipal->luz_actual / $personajePrincipal->luz_maxima) * 100 }}%; box-shadow: 0 0 10px rgba(255, 193, 7, 0.6);"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Quick Abilities -->
                        <div class="grid grid-cols-3 gap-3">
                            <button onclick="useAbility('heal')" 
                                    class="ability-btn p-3 rounded-lg bg-green-500/20 border border-green-500/50 hover:bg-green-500/30 transition-all duration-300"
                                    title="Curación Rápida">
                                <svg class="w-6 h-6 mx-auto text-green-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                <div class="text-xs mt-1 text-green-400">Curar</div>
                            </button>
                            
                            <button onclick="useAbility('boost')" 
                                    class="ability-btn p-3 rounded-lg bg-blue-500/20 border border-blue-500/50 hover:bg-blue-500/30 transition-all duration-300"
                                    title="Impulso de Energía">
                                <svg class="w-6 h-6 mx-auto text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <div class="text-xs mt-1 text-blue-400">Impulso</div>
                            </button>
                            
                            <button onclick="useAbility('shield')" 
                                    class="ability-btn p-3 rounded-lg bg-purple-500/20 border border-purple-500/50 hover:bg-purple-500/30 transition-all duration-300"
                                    title="Escudo Protector">
                                <svg class="w-6 h-6 mx-auto text-purple-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M12,7C13.4,7 14.8,8.6 14.8,10V11H16V17H8V11H9.2V10C9.2,8.6 10.6,7 12,7M12,8.2C11.2,8.2 10.4,8.7 10.4,10V11H13.6V10C13.6,8.7 12.8,8.2 12,8.2Z"/>
                                </svg>
                                <div class="text-xs mt-1 text-purple-400">Escudo</div>
                            </button>
                        </div>
                    @endif
                </div>
                
                <!-- Center - Guardian Avatar & XP Ring -->
                <div class="relative flex-shrink-0">
                    @if($personajePrincipal)
                        <!-- XP Ring Background -->
                        <div class="relative w-56 h-56">
                            <!-- Outer Ring -->
                            <svg class="absolute inset-0 w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                <!-- Background Circle -->
                                <circle cx="50" cy="50" r="45" 
                                        fill="none" 
                                        stroke="rgba(110, 193, 228, 0.2)" 
                                        stroke-width="2"/>
                                <!-- Progress Circle -->
                                <circle cx="50" cy="50" r="45" 
                                        fill="none" 
                                        stroke="var(--destiny-cyan)" 
                                        stroke-width="3"
                                        stroke-dasharray="283"
                                        stroke-dashoffset="{{ 283 - (($personajePrincipal->experiencia_actual / max($personajePrincipal->experiencia_siguiente ?? 1000, 1)) * 283) }}"
                                        stroke-linecap="round"
                                        class="transition-all duration-2000 destiny-glow"/>
                            </svg>
                            
                            <!-- Guardian Avatar -->
                            <div class="absolute inset-4 rounded-full overflow-hidden destiny-glow-gold">
                                <img src="{{ $personajePrincipal->imagen_personalizada ?? '/images/avatars/default-guardian.jpg' }}" 
                                     alt="Guardian Avatar"
                                     class="w-full h-full object-cover">
                                
                                <!-- Level Badge -->
                                <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-full px-4 py-2 destiny-glow">
                                    <span class="text-white font-bold text-lg">{{ $personajePrincipal->nivel }}</span>
                                </div>
                            </div>
                            
                            <!-- XP Info -->
                            <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 text-center">
                                <div class="destiny-text text-sm font-medium">
                                    {{ number_format($personajePrincipal->experiencia_actual) }} XP
                                </div>
                                <div class="destiny-text-muted text-xs">
                                    Siguiente: {{ number_format(($personajePrincipal->experiencia_siguiente ?? 1000) - $personajePrincipal->experiencia_actual) }} XP
                                </div>
                            </div>
                            
                            <!-- Power Level -->
                            <div class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 text-center">
                                <div class="destiny-heading text-lg font-bold text-yellow-400">
                                    ⚡ {{ $personajePrincipal->power_level ?? 750 }}
                                </div>
                                <div class="destiny-text-muted text-xs">
                                    Power Level
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="w-56 h-56 rounded-full border-2 border-dashed border-cyan-500/50 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto text-cyan-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                                <p class="destiny-text text-sm">Únete a un Fireteam</p>
                                <p class="destiny-text text-sm">para crear tu Guardián</p>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Right Panel - Quick Stats -->
                <div class="lg:w-1/3 space-y-6">
                    @if($personajePrincipal)
                        <!-- Achievements -->
                        <div class="space-y-3">
                            <h3 class="destiny-heading text-lg font-bold">Logros Recientes</h3>
                            <div class="space-y-2">
                                <div class="flex items-center space-x-3 p-3 rounded-lg bg-black/20">
                                    <div class="w-8 h-8 rounded-full bg-yellow-500 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-yellow-900" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="destiny-text text-sm font-medium">Subida de Nivel</div>
                                        <div class="destiny-text-muted text-xs">Alcanzaste nivel {{ $personajePrincipal->nivel }}</div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-3 p-3 rounded-lg bg-black/20">
                                    <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-900" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="destiny-text text-sm font-medium">Misión Completada</div>
                                        <div class="destiny-text-muted text-xs">Tarea de Matemáticas</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Guardian Stats -->
                        <div class="space-y-3">
                            <h3 class="destiny-heading text-lg font-bold">Estadísticas</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center p-3 rounded-lg bg-black/20">
                                    <div class="destiny-heading text-lg font-bold text-cyan-400">{{ $estadisticas['nivel_total'] }}</div>
                                    <div class="destiny-text-muted text-xs">Nivel</div>
                                </div>
                                <div class="text-center p-3 rounded-lg bg-black/20">
                                    <div class="destiny-heading text-lg font-bold text-purple-400">{{ $estadisticas['rango_global'] }}</div>
                                    <div class="destiny-text-muted text-xs">Posición</div>
                                </div>
                                <div class="text-center p-3 rounded-lg bg-black/20">
                                    <div class="destiny-heading text-lg font-bold text-yellow-400">{{ $estadisticas['clases_activas'] }}</div>
                                    <div class="destiny-text-muted text-xs">Fireteams</div>
                                </div>
                                <div class="text-center p-3 rounded-lg bg-black/20">
                                    <div class="destiny-heading text-lg font-bold text-green-400">95%</div>
                                    <div class="destiny-text-muted text-xs">Precisión</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Orbital Classes Section -->
    @if($clases->count() > 0)
        <div class="holo-panel rounded-2xl p-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="destiny-heading text-2xl font-bold">
                    Fireteams Activos
                </h2>
                <div class="flex items-center space-x-4 text-sm destiny-text-muted">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full destiny-glow-gold"></div>
                        <span>Activo</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <span>Crítico</span>
                    </div>
                </div>
            </div>
            
            <!-- Orbital View -->
            <div class="relative min-h-96 orbital-system" id="orbitalSystem">
                @foreach($clases as $index => $clase)
                    <div class="absolute orbital-node {{ $clase['activa'] ? 'active' : 'inactive' }}"
                         style="
                            left: {{ 50 + (40 * cos(($index * 360 / $clases->count()) * pi() / 180)) }}%;
                            top: {{ 50 + (30 * sin(($index * 360 / $clases->count()) * pi() / 180)) }}%;
                         "
                         data-clase-id="{{ $clase['id'] }}"
                         onclick="openClassModal({{ $clase['id'] }})">
                        
                        <!-- Orbit Line -->
                        <div class="orbit-line"></div>
                        
                        <!-- Class Node -->
                        <div class="relative node-container w-24 h-24 cursor-pointer transform transition-all duration-300 hover:scale-110">
                            <!-- Node Background -->
                            <div class="absolute inset-0 rounded-full {{ $clase['activa'] ? 'destiny-glow-gold' : 'destiny-glow' }}"></div>
                            
                            <!-- Node Content -->
                            <div class="relative z-10 w-full h-full rounded-full border-2 {{ $clase['activa'] ? 'border-yellow-400' : 'border-cyan-400' }} flex flex-col items-center justify-center"
                                 style="background: radial-gradient(circle, rgba(46, 47, 61, 0.9) 0%, rgba(58, 61, 83, 0.8) 100%);">
                                <div class="text-center">
                                    <div class="text-xs font-bold destiny-heading">
                                        {{ strtoupper(substr($clase['nombre'], 0, 3)) }}
                                    </div>
                                    @if($clase['personaje'])
                                        <div class="text-xs text-yellow-400">
                                            Nv.{{ $clase['personaje']['nivel'] }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Status Indicators -->
                            @if($clase['personaje'] && $clase['personaje']['salud_porcentaje'] < 30)
                                <div class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full flex items-center justify-center destiny-glow">
                                    <span class="text-white text-xs">!</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Class Name -->
                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 text-center">
                            <div class="destiny-text text-xs font-medium whitespace-nowrap">
                                {{ $clase['nombre'] }}
                            </div>
                            @if($clase['personaje'])
                                <div class="destiny-text-muted text-xs">
                                    {{ $clase['personaje']['clase_rpg'] }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
                
                <!-- Center Point -->
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-4 h-4 rounded-full bg-cyan-400 destiny-glow"></div>
            </div>
        </div>
    @endif
    
    <!-- Bottom Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Rankings -->
        @if(count($ranking) > 0)
            <div class="holo-panel rounded-xl p-6">
                <h3 class="destiny-heading text-lg font-bold mb-4">
                    Top Guardianes
                </h3>
                <div class="space-y-3">
                    @foreach($ranking as $guardian)
                        <div class="flex items-center justify-between p-3 rounded-lg {{ $guardian['es_yo'] ? 'bg-cyan-500/20 border border-cyan-500/50' : 'bg-black/20' }}">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full {{ $guardian['posicion'] <= 3 ? 'destiny-glow-gold bg-yellow-500' : 'destiny-glow bg-cyan-500' }} flex items-center justify-center">
                                    <span class="text-xs font-bold text-black">{{ $guardian['posicion'] }}</span>
                                </div>
                                <div>
                                    <div class="destiny-text text-sm font-medium {{ $guardian['es_yo'] ? 'text-cyan-400' : '' }}">
                                        {{ $guardian['nombre'] }}
                                    </div>
                                    <div class="destiny-text-muted text-xs">
                                        {{ $guardian['clase_rpg'] }} - Nivel {{ $guardian['nivel'] }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="destiny-text text-sm">{{ number_format($guardian['experiencia']) }} XP</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        
        <!-- Próximas Actividades -->
        <div class="holo-panel rounded-xl p-6">
            <h3 class="destiny-heading text-lg font-bold mb-4">
                Próximas Misiones
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 rounded-lg bg-blue-500/20 border border-blue-500/50">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-900" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="destiny-text text-sm font-medium">Examen de Matemáticas</div>
                            <div class="destiny-text-muted text-xs">En 2 días</div>
                        </div>
                    </div>
                    <div class="text-blue-400 text-sm font-medium">+500 XP</div>
                </div>
                
                <div class="flex items-center justify-between p-3 rounded-lg bg-yellow-500/20 border border-yellow-500/50">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-yellow-500 flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-900" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="destiny-text text-sm font-medium">Proyecto de Física</div>
                            <div class="destiny-text-muted text-xs">En 1 semana</div>
                        </div>
                    </div>
                    <div class="text-yellow-400 text-sm font-medium">+750 XP</div>
                </div>
                
                <div class="flex items-center justify-between p-3 rounded-lg bg-green-500/20 border border-green-500/50">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-900" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="destiny-text text-sm font-medium">Quiz de Biología</div>
                            <div class="destiny-text-muted text-xs">Hoy</div>
                        </div>
                    </div>
                    <div class="text-green-400 text-sm font-medium">+200 XP</div>
                </div>
                
                <!-- Empty slot -->
                <div class="flex items-center justify-center p-4 rounded-lg border-2 border-dashed border-cyan-500/50">
                    <a href="{{ route('estudiante.unirse-clase.form') }}" class="destiny-text-muted text-sm hover:text-cyan-400 transition-colors">
                        + Unirse a nuevo Fireteam
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Class Details Modal -->
<div id="classModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="holo-panel rounded-2xl p-8 max-w-lg w-full mx-4 destiny-glow">
        <div class="flex justify-between items-start mb-6">
            <h3 class="destiny-heading text-xl font-bold" id="modalClassName">Detalles del Fireteam</h3>
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
            <a id="enterClassLink" href="#" 
               class="destiny-btn px-6 py-3 rounded-lg font-semibold transition-all duration-300">
                Entrar al Fireteam
            </a>
        </div>
    </div>
</div>

<!-- Ability Usage Modal -->
<div id="abilityModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="holo-panel rounded-2xl p-8 max-w-md w-full mx-4 destiny-glow">
        <div class="text-center">
            <div id="abilityIcon" class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center"></div>
            <h3 class="destiny-heading text-xl font-bold mb-2" id="abilityName">Habilidad</h3>
            <p class="destiny-text-muted text-sm mb-6" id="abilityDescription">Descripción de la habilidad</p>
            
            <div id="abilityResult" class="hidden space-y-4">
                <div class="holo-panel rounded-lg p-4">
                    <div id="abilityEffect" class="destiny-text text-sm"></div>
                </div>
            </div>
            
            <div class="flex space-x-4 mt-6">
                <button onclick="closeAbilityModal()" 
                        class="flex-1 px-6 py-3 rounded-lg border border-gray-500 text-gray-400 hover:border-gray-400 hover:text-white transition-colors">
                    Cancelar
                </button>
                <button onclick="executeAbility()" 
                        class="flex-1 destiny-btn px-6 py-3 rounded-lg font-semibold transition-all duration-300"
                        id="executeButton">
                    Usar Habilidad
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Styles for Student Dashboard -->
<style>
    /* Guardian Background Effects */
    .guardian-bg {
        background: 
            radial-gradient(circle at 30% 20%, rgba(110, 193, 228, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 70% 80%, rgba(182, 161, 228, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 50% 50%, rgba(199, 184, 138, 0.05) 0%, transparent 70%);
    }
    
    .guardian-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: 
            radial-gradient(2px 2px at 15% 25%, rgba(110, 193, 228, 0.8), transparent),
            radial-gradient(1px 1px at 85% 75%, rgba(199, 184, 138, 0.6), transparent),
            radial-gradient(1px 1px at 50% 15%, rgba(182, 161, 228, 0.7), transparent),
            radial-gradient(1px 1px at 25% 85%, rgba(110, 193, 228, 0.5), transparent);
        background-size: 300px 300px, 200px 200px, 150px 150px, 100px 100px;
        animation: guardianFloat 12s linear infinite;
        pointer-events: none;
    }
    
    @keyframes guardianFloat {
        0%, 100% { 
            opacity: 1; 
            transform: translate(0, 0) scale(1);
        }
        25% { 
            opacity: 0.8; 
            transform: translate(10px, -10px) scale(1.05);
        }
        50% { 
            opacity: 0.9; 
            transform: translate(-5px, 5px) scale(0.95);
        }
        75% { 
            opacity: 0.7; 
            transform: translate(8px, 8px) scale(1.02);
        }
    }
    
    /* Orbital System */
    .orbital-system {
        background: 
            radial-gradient(circle at center, rgba(110, 193, 228, 0.1) 0%, transparent 60%);
        position: relative;
    }
    
    .orbital-system::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 80%;
        height: 60%;
        border: 1px solid rgba(110, 193, 228, 0.2);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        animation: orbitRotate 20s linear infinite;
    }
    
    .orbital-system::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 60%;
        height: 40%;
        border: 1px solid rgba(199, 184, 138, 0.15);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        animation: orbitRotate 15s linear infinite reverse;
    }
    
    @keyframes orbitRotate {
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        100% { transform: translate(-50%, -50%) rotate(360deg); }
    }
    
    .orbital-node {
        transition: all 0.3s ease;
        z-index: 10;
    }
    
    .orbital-node:hover {
        z-index: 20;
        transform: scale(1.1);
    }
    
    .orbit-line {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 2px;
        height: 200px;
        background: linear-gradient(to bottom, 
            transparent 0%, 
            rgba(110, 193, 228, 0.3) 20%, 
            rgba(110, 193, 228, 0.6) 50%, 
            rgba(110, 193, 228, 0.3) 80%, 
            transparent 100%);
        transform: translate(-50%, -50%);
        animation: orbitPulse 3s ease-in-out infinite;
        pointer-events: none;
    }
    
    @keyframes orbitPulse {
        0%, 100% { opacity: 0.3; }
        50% { opacity: 0.8; }
    }
    
    /* Ability Buttons */
    .ability-btn {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .ability-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, 
            transparent 0%,
            rgba(255, 255, 255, 0.2) 50%,
            transparent 100%);
        transition: left 0.5s ease;
    }
    
    .ability-btn:hover::before {
        left: 100%;
    }
    
    .ability-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .ability-btn.cooldown {
        opacity: 0.6;
        background: repeating-linear-gradient(
            45deg,
            rgba(255, 0, 0, 0.1),
            rgba(255, 0, 0, 0.1) 10px,
            transparent 10px,
            transparent 20px
        );
    }
    
    /* XP Ring Animation */
    .xp-ring {
        animation: xpGlow 2s ease-in-out infinite alternate;
    }
    
    @keyframes xpGlow {
        0% { filter: drop-shadow(0 0 5px rgba(110, 193, 228, 0.5)); }
        100% { filter: drop-shadow(0 0 15px rgba(110, 193, 228, 0.8)); }
    }
    
    /* Resource Bars Animation */
    .resource-bar {
        position: relative;
        overflow: hidden;
    }
    
    .resource-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, 
            transparent 0%,
            rgba(255, 255, 255, 0.3) 50%,
            transparent 100%);
        animation: shimmer 2s infinite;
    }
    
    @keyframes shimmer {
        0% { left: -100%; }
        100% { left: 100%; }
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .orbital-node {
            position: relative !important;
            display: inline-block;
            margin: 1rem;
        }
        
        .orbital-system {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            min-height: 200px;
        }
        
        .orbital-system::before,
        .orbital-system::after {
            display: none;
        }
        
        .orbit-line {
            display: none;
        }
        
        .guardian-bg::before {
            animation-duration: 8s;
        }
    }
    
    /* Loading States */
    .loading-state {
        opacity: 0.7;
        pointer-events: none;
    }
    
    .loading-state::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        border: 2px solid rgba(110, 193, 228, 0.3);
        border-top: 2px solid var(--destiny-cyan);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        transform: translate(-50%, -50%);
    }
</style>

<!-- Scripts for Student Dashboard -->
<script>
    // Global variables
    let currentAbility = null;
    let abilityCooldowns = {};
    
    // Initialize dashboard
    document.addEventListener('DOMContentLoaded', function() {
        initializeOrbitAnimation();
        initializeAbilities();
        startResourceUpdates();
        
        // Auto-refresh every 30 seconds
        setInterval(refreshDashboard, 30000);
    });
    
    // Initialize orbital animation
    function initializeOrbitAnimation() {
        const nodes = document.querySelectorAll('.orbital-node');
        nodes.forEach((node, index) => {
            // Add staggered entrance animation
            node.style.animationDelay = `${index * 0.3}s`;
            node.classList.add('animate-fade-in');
            
            // Add hover sound effect (optional)
            node.addEventListener('mouseenter', function() {
                // Could play a subtle sound effect here
                this.style.transform = 'scale(1.1)';
            });
            
            node.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
    }
    
    // Initialize abilities
    function initializeAbilities() {
        const abilityButtons = document.querySelectorAll('.ability-btn');
        abilityButtons.forEach(button => {
            // Check if ability is on cooldown
            const abilityType = button.getAttribute('onclick').match(/'([^']+)'/)[1];
            if (abilityCooldowns[abilityType] && abilityCooldowns[abilityType] > Date.now()) {
                button.classList.add('cooldown');
                button.disabled = true;
                
                // Start cooldown timer
                const remainingTime = abilityCooldowns[abilityType] - Date.now();
                startCooldownTimer(button, remainingTime);
            }
        });
    }
    
    // Use ability function
    function useAbility(abilityType) {
        if (abilityCooldowns[abilityType] && abilityCooldowns[abilityType] > Date.now()) {
            showNotification('Habilidad en cooldown', 'warning');
            return;
        }
        
        currentAbility = abilityType;
        const modal = document.getElementById('abilityModal');
        const icon = document.getElementById('abilityIcon');
        const name = document.getElementById('abilityName');
        const description = document.getElementById('abilityDescription');
        
        // Configure modal based on ability type
        switch(abilityType) {
            case 'heal':
                icon.className = 'w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center bg-green-500/20 border border-green-500';
                icon.innerHTML = '<svg class="w-8 h-8 text-green-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';
                name.textContent = 'Curación Rápida';
                description.textContent = 'Restaura 30 puntos de salud instantáneamente. Cuesta 20 puntos de energía.';
                break;
            case 'boost':
                icon.className = 'w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center bg-blue-500/20 border border-blue-500';
                icon.innerHTML = '<svg class="w-8 h-8 text-blue-400" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>';
                name.textContent = 'Impulso de Energía';
                description.textContent = 'Recupera 40 puntos de energía. Sin costo adicional.';
                break;
            case 'shield':
                icon.className = 'w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center bg-purple-500/20 border border-purple-500';
                icon.innerHTML = '<svg class="w-8 h-8 text-purple-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M12,7C13.4,7 14.8,8.6 14.8,10V11H16V17H8V11H9.2V10C9.2,8.6 10.6,7 12,7M12,8.2C11.2,8.2 10.4,8.7 10.4,10V11H13.6V10C13.6,8.7 12.8,8.2 12,8.2Z"/></svg>';
                name.textContent = 'Escudo Protector';
                description.textContent = 'Proporciona protección temporal contra daño. Cuesta 30 puntos de luz.';
                break;
        }
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    
    // Execute ability
    async function executeAbility() {
        const button = document.getElementById('executeButton');
        const result = document.getElementById('abilityResult');
        const effect = document.getElementById('abilityEffect');
        
        button.classList.add('loading-state');
        button.textContent = 'Ejecutando...';
        
        try {
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 1500));
            
            // Show result based on ability type
            let effectText = '';
            let success = true;
            
            switch(currentAbility) {
                case 'heal':
                    effectText = '¡Curación exitosa! +30 puntos de salud restaurados.';
                    break;
                case 'boost':
                    effectText = '¡Impulso activado! +40 puntos de energía recuperados.';
                    break;
                case 'shield':
                    effectText = '¡Escudo activado! Protección temporal habilitada.';
                    break;
            }
            
            effect.textContent = effectText;
            result.classList.remove('hidden');
            
            // Start cooldown
            const cooldownTime = 30000; // 30 seconds
            abilityCooldowns[currentAbility] = Date.now() + cooldownTime;
            
            // Update UI
            updateResourceBars();
            
            setTimeout(() => {
                closeAbilityModal();
                showNotification(effectText, 'success');
            }, 2000);
            
        } catch (error) {
            effect.textContent = 'Error al ejecutar habilidad. Intenta nuevamente.';
            result.classList.remove('hidden');
            setTimeout(closeAbilityModal, 2000);
        }
        
        button.classList.remove('loading-state');
        button.textContent = 'Usar Habilidad';
    }
    
    // Close ability modal
    function closeAbilityModal() {
        const modal = document.getElementById('abilityModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        
        const result = document.getElementById('abilityResult');
        result.classList.add('hidden');
        
        currentAbility = null;
    }
    
    // Open class modal
    function openClassModal(claseId) {
        const modal = document.getElementById('classModal');
        const content = document.getElementById('modalContent');
        const className = document.getElementById('modalClassName');
        const enterLink = document.getElementById('enterClassLink');
        
        // Show modal with loading
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        content.innerHTML = '<div class="text-center py-8"><div class="loading-ring w-8 h-8 mx-auto"></div></div>';
        
        // Find class data
        const clases = @json($clases);
        const clase = clases.find(c => c.id == claseId);
        
        if (clase) {
            className.textContent = clase.nombre;
            enterLink.href = `/estudiante/clase/${claseId}`;
            
            // Build content
            content.innerHTML = `
                <div class="space-y-4">
                    <div>
                        <h4 class="destiny-text font-semibold mb-2">Información del Fireteam</h4>
                        <div class="space-y-2 text-sm destiny-text-muted">
                            <div class="flex justify-between">
                                <span>Profesor:</span>
                                <span>${clase.profesor}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Estado:</span>
                                <span class="${clase.activa ? 'text-green-400' : 'text-gray-400'}">${clase.activa ? 'Activo' : 'Inactivo'}</span>
                            </div>
                            ${clase.personaje ? `
                            <div class="flex justify-between">
                                <span>Tu Personaje:</span>
                                <span class="text-cyan-400">${clase.personaje.nombre} (${clase.personaje.clase_rpg})</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Nivel:</span>
                                <span class="text-yellow-400">${clase.personaje.nivel}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Salud:</span>
                                <span class="${clase.personaje.salud_porcentaje < 30 ? 'text-red-400' : 'text-green-400'}">${clase.personaje.salud_porcentaje.toFixed(1)}%</span>
                            </div>
                            ` : `
                            <div class="text-center p-4 border-2 border-dashed border-cyan-500/50 rounded-lg">
                                <span class="text-cyan-400">Personaje no creado</span>
                            </div>
                            `}
                        </div>
                    </div>
                </div>
            `;
        }
    }
    
    // Close class modal
    function closeClassModal() {
        const modal = document.getElementById('classModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    
    // Update resource bars
    function updateResourceBars() {
        // This would typically fetch fresh data from the server
        console.log('Updating resource bars...');
    }
    
    // Start resource updates
    function startResourceUpdates() {
        // Animate resource bars periodically
        setInterval(() => {
            const resourceBars = document.querySelectorAll('.resource-bar > div');
            resourceBars.forEach(bar => {
                bar.style.animation = 'none';
                setTimeout(() => {
                    bar.style.animation = 'shimmer 2s infinite';
                }, 10);
            });
        }, 10000);
    }
    
    // Refresh dashboard
    async function refreshDashboard() {
        try {
            // This would typically fetch fresh data from your API
            console.log('Refreshing dashboard...');
        } catch (error) {
            console.error('Error refreshing dashboard:', error);
        }
    }
    
    // Start cooldown timer
    function startCooldownTimer(button, duration) {
        const endTime = Date.now() + duration;
        
        const timer = setInterval(() => {
            const remaining = endTime - Date.now();
            
            if (remaining <= 0) {
                clearInterval(timer);
                button.classList.remove('cooldown');
                button.disabled = false;
                button.style.background = '';
            } else {
                const seconds = Math.ceil(remaining / 1000);
                button.title = `Cooldown: ${seconds}s`;
            }
        }, 100);
    }
    
    // Show notification
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-20 right-4 z-50 holo-panel rounded-lg p-4 border-l-4 destiny-glow max-w-sm transform translate-x-full transition-transform duration-300`;
        
        switch(type) {
            case 'success':
                notification.classList.add('border-green-500');
                notification.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                        <span class="destiny-text text-sm">${message}</span>
                    </div>
                `;
                break;
            case 'warning':
                notification.classList.add('border-yellow-500');
                notification.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                        </svg>
                        <span class="destiny-text text-sm">${message}</span>
                    </div>
                `;
                break;
            default:
                notification.classList.add('border-blue-500');
                notification.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                        </svg>
                        <span class="destiny-text text-sm">${message}</span>
                    </div>
                `;
        }
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
    
    // Close modals on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeClassModal();
            closeAbilityModal();
        }
    });
    
    // Add entrance animations
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