@extends('layouts.app')

@section('title', 'Forja del Guardián')

@section('content')
<div class="min-h-screen destiny-bg-primary relative overflow-hidden">
    <!-- Animated Background -->
    <div class="fixed inset-0 z-0">
        <div class="forge-background">
            <div class="forge-particles"></div>
            <div class="energy-rings"></div>
        </div>
    </div>

    <div class="relative z-10 container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-4 destiny-glow-purple tracking-wider">
                FORJA DEL GUARDIÁN
            </h1>
            <p class="text-destiny-gold text-lg md:text-xl opacity-90">
                {{ $clase->nombre }} - Creación de tu Avatar
            </p>
        </div>

        <div class="max-w-6xl mx-auto">
            <form id="crearPersonajeForm" action="{{ route('estudiante.crear-personaje', $clase) }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @csrf
                
                <!-- Panel de Selección de Clase -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Información Básica -->
                    <div class="holo-panel p-6 md:p-8">
                        <h2 class="text-2xl font-bold text-white mb-6 destiny-glow-gold flex items-center">
                            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Identidad del Guardián
                        </h2>
                        
                        <div class="form-group">
                            <label for="nombre_personaje" class="block text-destiny-gold font-semibold mb-3">
                                Nombre del Guardián *
                            </label>
                            <input 
                                type="text" 
                                id="nombre_personaje" 
                                name="nombre_personaje" 
                                value="{{ old('nombre_personaje') }}"
                                class="w-full p-4 destiny-input text-lg @error('nombre_personaje') border-red-500 @enderror"
                                placeholder="Ej: Zara Solaris"
                                required
                                maxlength="50"
                            >
                            <div class="flex justify-between items-center mt-2">
                                @error('nombre_personaje')
                                    <p class="text-red-400 text-sm">{{ $message }}</p>
                                @else
                                    <p class="text-destiny-cyan text-sm opacity-70">Elige un nombre único para tu Guardián</p>
                                @enderror
                                <span class="text-destiny-gold text-sm" id="nameCounter">0/50</span>
                            </div>
                        </div>
                    </div>

                    <!-- Selección de Clase RPG -->
                    <div class="holo-panel p-6 md:p-8">
                        <h2 class="text-2xl font-bold text-white mb-6 destiny-glow-cyan flex items-center">
                            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                            Clase de Guardián
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Cazador -->
                            <div class="class-card" data-class="1">
                                <input type="radio" name="clase_rpg_id" value="1" id="cazador" class="hidden class-radio" {{ old('clase_rpg_id') == '1' ? 'checked' : '' }}>
                                <label for="cazador" class="block cursor-pointer">
                                    <div class="class-container hunter">
                                        <div class="class-header">
                                            <div class="class-icon">
                                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                            </div>
                                            <h3 class="class-title">CAZADOR</h3>
                                        </div>
                                        <div class="class-stats">
                                            <div class="stat-bar">
                                                <span class="stat-label">Agilidad</span>
                                                <div class="stat-progress">
                                                    <div class="stat-fill" style="width: 95%"></div>
                                                </div>
                                            </div>
                                            <div class="stat-bar">
                                                <span class="stat-label">Sigilo</span>
                                                <div class="stat-progress">
                                                    <div class="stat-fill" style="width: 90%"></div>
                                                </div>
                                            </div>
                                            <div class="stat-bar">
                                                <span class="stat-label">Precisión</span>
                                                <div class="stat-progress">
                                                    <div class="stat-fill" style="width: 85%"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="class-description">
                                            <p>Maestros de la velocidad y la precisión. Expertos en ataques rápidos y tácticas de reconocimiento.</p>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <!-- Hechicero -->
                            <div class="class-card" data-class="2">
                                <input type="radio" name="clase_rpg_id" value="2" id="hechicero" class="hidden class-radio" {{ old('clase_rpg_id') == '2' ? 'checked' : '' }}>
                                <label for="hechicero" class="block cursor-pointer">
                                    <div class="class-container warlock">
                                        <div class="class-header">
                                            <div class="class-icon">
                                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                                </svg>
                                            </div>
                                            <h3 class="class-title">HECHICERO</h3>
                                        </div>
                                        <div class="class-stats">
                                            <div class="stat-bar">
                                                <span class="stat-label">Inteligencia</span>
                                                <div class="stat-progress">
                                                    <div class="stat-fill" style="width: 95%"></div>
                                                </div>
                                            </div>
                                            <div class="stat-bar">
                                                <span class="stat-label">Poderes Místicos</span>
                                                <div class="stat-progress">
                                                    <div class="stat-fill" style="width: 90%"></div>
                                                </div>
                                            </div>
                                            <div class="stat-bar">
                                                <span class="stat-label">Conocimiento</span>
                                                <div class="stat-progress">
                                                    <div class="stat-fill" style="width: 85%"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="class-description">
                                            <p>Manipuladores de energía arcana. Especializados en hechizos devastadores y conocimiento ancestral.</p>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <!-- Titán -->
                            <div class="class-card" data-class="3">
                                <input type="radio" name="clase_rpg_id" value="3" id="titan" class="hidden class-radio" {{ old('clase_rpg_id') == '3' ? 'checked' : '' }}>
                                <label for="titan" class="block cursor-pointer">
                                    <div class="class-container titan">
                                        <div class="class-header">
                                            <div class="class-icon">
                                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                                </svg>
                                            </div>
                                            <h3 class="class-title">TITÁN</h3>
                                        </div>
                                        <div class="class-stats">
                                            <div class="stat-bar">
                                                <span class="stat-label">Fuerza</span>
                                                <div class="stat-progress">
                                                    <div class="stat-fill" style="width: 95%"></div>
                                                </div>
                                            </div>
                                            <div class="stat-bar">
                                                <span class="stat-label">Resistencia</span>
                                                <div class="stat-progress">
                                                    <div class="stat-fill" style="width: 90%"></div>
                                                </div>
                                            </div>
                                            <div class="stat-bar">
                                                <span class="stat-label">Liderazgo</span>
                                                <div class="stat-progress">
                                                    <div class="stat-fill" style="width: 85%"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="class-description">
                                            <p>Defensores inquebrantables. Especializados en protección del equipo y resistencia extrema.</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        @error('clase_rpg_id')
                            <p class="text-red-400 text-sm mt-4">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Personalización Visual -->
                    <div class="holo-panel p-6 md:p-8">
                        <h2 class="text-2xl font-bold text-white mb-6 destiny-glow-purple flex items-center">
                            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a4 4 0 004-4V5z"></path>
                            </svg>
                            Aspecto Visual
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Color de Armadura -->
                            <div class="form-group">
                                <label class="block text-destiny-gold font-semibold mb-3">
                                    Color Principal de Armadura
                                </label>
                                <div class="grid grid-cols-4 gap-3">
                                    <div class="color-option" data-color="blue">
                                        <input type="radio" name="aspecto_visual[color_armadura]" value="blue" id="color_blue" class="hidden" {{ old('aspecto_visual.color_armadura') == 'blue' ? 'checked' : '' }}>
                                        <label for="color_blue" class="block w-full h-12 rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300" style="background: linear-gradient(135deg, #3B82F6, #1E40AF);"></label>
                                        <span class="text-xs text-center block mt-1 text-white">Azul</span>
                                    </div>
                                    <div class="color-option" data-color="red">
                                        <input type="radio" name="aspecto_visual[color_armadura]" value="red" id="color_red" class="hidden" {{ old('aspecto_visual.color_armadura') == 'red' ? 'checked' : '' }}>
                                        <label for="color_red" class="block w-full h-12 rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300" style="background: linear-gradient(135deg, #EF4444, #B91C1C);"></label>
                                        <span class="text-xs text-center block mt-1 text-white">Rojo</span>
                                    </div>
                                    <div class="color-option" data-color="gold">
                                        <input type="radio" name="aspecto_visual[color_armadura]" value="gold" id="color_gold" class="hidden" {{ old('aspecto_visual.color_armadura') == 'gold' ? 'checked' : '' }}>
                                        <label for="color_gold" class="block w-full h-12 rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300" style="background: linear-gradient(135deg, #F59E0B, #D97706);"></label>
                                        <span class="text-xs text-center block mt-1 text-white">Dorado</span>
                                    </div>
                                    <div class="color-option" data-color="purple">
                                        <input type="radio" name="aspecto_visual[color_armadura]" value="purple" id="color_purple" class="hidden" {{ old('aspecto_visual.color_armadura') == 'purple' ? 'checked' : '' }}>
                                        <label for="color_purple" class="block w-full h-12 rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300" style="background: linear-gradient(135deg, #8B5CF6, #7C3AED);"></label>
                                        <span class="text-xs text-center block mt-1 text-white">Púrpura</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Shader -->
                            <div class="form-group">
                                <label for="shader" class="block text-destiny-gold font-semibold mb-3">
                                    Shader de Armadura
                                </label>
                                <select id="shader" name="aspecto_visual[shader]" class="w-full p-3 destiny-input">
                                    <option value="default" {{ old('aspecto_visual.shader') == 'default' ? 'selected' : '' }}>Estándar</option>
                                    <option value="metallic" {{ old('aspecto_visual.shader') == 'metallic' ? 'selected' : '' }}>Metálico</option>
                                    <option value="chrome" {{ old('aspecto_visual.shader') == 'chrome' ? 'selected' : '' }}>Cromado</option>
                                    <option value="matte" {{ old('aspecto_visual.shader') == 'matte' ? 'selected' : '' }}>Mate</option>
                                    <option value="glowing" {{ old('aspecto_visual.shader') == 'glowing' ? 'selected' : '' }}>Luminiscente</option>
                                </select>
                            </div>

                            <!-- Emblema -->
                            <div class="form-group md:col-span-2">
                                <label class="block text-destiny-gold font-semibold mb-3">
                                    Emblema del Guardián
                                </label>
                                <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
                                    @php
                                        $emblemas = [
                                            'phoenix' => '🔥',
                                            'wolf' => '🐺', 
                                            'eagle' => '🦅',
                                            'dragon' => '🐉',
                                            'star' => '⭐',
                                            'moon' => '🌙'
                                        ];
                                    @endphp
                                    
                                    @foreach($emblemas as $key => $emoji)
                                        <div class="emblem-option">
                                            <input type="radio" name="aspecto_visual[emblem]" value="{{ $key }}" id="emblem_{{ $key }}" class="hidden" {{ old('aspecto_visual.emblem') == $key ? 'checked' : '' }}>
                                            <label for="emblem_{{ $key }}" class="block w-full h-16 rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 bg-destiny-bg-secondary hover:bg-destiny-bg-tertiary flex items-center justify-center text-2xl">
                                                {{ $emoji }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel de Vista Previa -->
                <div class="lg:col-span-1">
                    <div class="holo-panel p-6 sticky top-8">
                        <h3 class="text-xl font-bold text-white mb-6 destiny-glow-gold text-center">
                            Vista Previa del Guardián
                        </h3>
                        
                        <!-- Avatar Preview -->
                        <div class="character-preview mb-6">
                            <div class="character-display" id="characterDisplay">
                                <div class="character-silhouette" id="characterSilhouette">
                                    <div class="character-glow" id="characterGlow"></div>
                                    <div class="character-details">
                                        <div class="character-name" id="previewName">Guardián Sin Nombre</div>
                                        <div class="character-class" id="previewClass">Selecciona una clase</div>
                                        <div class="character-emblem" id="previewEmblem">⚔️</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stats Preview -->
                        <div class="stats-preview mb-6" id="statsPreview">
                            <h4 class="text-destiny-gold font-semibold mb-3">Estadísticas Base</h4>
                            <div class="space-y-2" id="previewStats">
                                <div class="text-destiny-cyan text-sm">Selecciona una clase para ver estadísticas</div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full destiny-btn destiny-btn-success" id="forgeBtn">
                            <span class="btn-text flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Forjar Guardián
                            </span>
                            <span class="btn-loading hidden">
                                <svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Forjando...
                            </span>
                        </button>

                        <div class="mt-6 text-center">
                            <p class="text-destiny-cyan text-sm opacity-80">
                                Tu Guardián será único e irrepetible
                            </p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.destiny-input {
    background: rgba(46, 47, 61, 0.8);
    border: 1px solid rgba(199, 184, 138, 0.3);
    border-radius: 8px;
    color: white;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.destiny-input:focus {
    outline: none;
    border-color: #C7B88A;
    box-shadow: 0 0 20px rgba(199, 184, 138, 0.3);
}

.destiny-input::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

.destiny-input option {
    background: #2E2F3D;
    color: white;
}

.forge-background {
    position: absolute;
    inset: 0;
    background: 
        radial-gradient(circle at 25% 25%, rgba(182, 161, 228, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(110, 193, 228, 0.1) 0%, transparent 50%);
}

.forge-particles {
    position: absolute;
    inset: 0;
    background-image: 
        radial-gradient(2px 2px at 20px 30px, rgba(199, 184, 138, 0.3), transparent),
        radial-gradient(2px 2px at 40px 70px, rgba(110, 193, 228, 0.3), transparent),
        radial-gradient(1px 1px at 90px 40px, rgba(182, 161, 228, 0.3), transparent);
    animation: sparkle 4s linear infinite;
}

@keyframes sparkle {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 0.8; }
}

.energy-rings {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 800px;
    height: 800px;
    border: 1px solid rgba(199, 184, 138, 0.1);
    border-radius: 50%;
    animation: rotate 20s linear infinite;
}

.energy-rings::before,
.energy-rings::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border: 1px solid rgba(110, 193, 228, 0.1);
    border-radius: 50%;
}

.energy-rings::before {
    width: 600px;
    height: 600px;
    animation: rotate 15s linear infinite reverse;
}

.energy-rings::after {
    width: 400px;
    height: 400px;
    animation: rotate 10s linear infinite;
}

@keyframes rotate {
    from { transform: translate(-50%, -50%) rotate(0deg); }
    to { transform: translate(-50%, -50%) rotate(360deg); }
}

.class-card {
    transition: all 0.3s ease;
}

.class-container {
    background: rgba(46, 47, 61, 0.6);
    border: 2px solid rgba(58, 61, 83, 0.8);
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    min-height: 300px;
    display: flex;
    flex-direction: column;
}

.class-container:hover {
    transform: translateY(-5px);
    border-color: rgba(199, 184, 138, 0.5);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.class-radio:checked + label .class-container {
    border-color: #C7B88A;
    box-shadow: 0 0 30px rgba(199, 184, 138, 0.3);
    background: rgba(199, 184, 138, 0.1);
}

.class-header {
    text-align: center;
    margin-bottom: 20px;
}

.class-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 15px;
    background: rgba(199, 184, 138, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #C7B88A;
    border: 2px solid rgba(199, 184, 138, 0.3);
    transition: all 0.3s ease;
}

.hunter .class-icon { border-color: rgba(110, 193, 228, 0.5); color: #6EC1E4; }
.warlock .class-icon { border-color: rgba(182, 161, 228, 0.5); color: #B6A1E4; }
.titan .class-icon { border-color: rgba(245, 158, 11, 0.5); color: #F59E0B; }

.class-title {
    font-size: 18px;
    font-weight: bold;
    color: white;
    margin: 0;
    letter-spacing: 1px;
}

.class-stats {
    flex: 1;
    margin-bottom: 15px;
}

.stat-bar {
    margin-bottom: 12px;
}

.stat-label {
    display: block;
    color: #C7B88A;
    font-size: 12px;
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-progress {
    height: 6px;
    background: rgba(58, 61, 83, 0.8);
    border-radius: 3px;
    overflow: hidden;
}

.stat-fill {
    height: 100%;
    background: linear-gradient(90deg, #C7B88A, #6EC1E4);
    border-radius: 3px;
    transition: width 0.5s ease;
}

.hunter .stat-fill { background: linear-gradient(90deg, #6EC1E4, #3B82F6); }
.warlock .stat-fill { background: linear-gradient(90deg, #B6A1E4, #8B5CF6); }
.titan .stat-fill { background: linear-gradient(90deg, #F59E0B, #EF4444); }

.class-description {
    color: rgba(255, 255, 255, 0.8);
    font-size: 14px;
    line-height: 1.4;
    text-align: center;
}

.color-option input:checked + label {
    border-color: #C7B88A !important;
    box-shadow: 0 0 15px rgba(199, 184, 138, 0.4) !important;
    transform: scale(1.05);
}

.emblem-option input:checked + label {
    border-color: #C7B88A !important;
    box-shadow: 0 0 15px rgba(199, 184, 138, 0.4) !important;
    background: rgba(199, 184, 138, 0.1) !important;
}

.character-preview {
    text-align: center;
}

.character-display {
    position: relative;
    width: 200px;
    height: 250px;
    margin: 0 auto;
    background: radial-gradient(circle, rgba(199, 184, 138, 0.1) 0%, transparent 70%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.character-silhouette {
    position: relative;
    width: 120px;
    height: 180px;
    background: linear-gradient(180deg, rgba(110, 193, 228, 0.3) 0%, rgba(182, 161, 228, 0.3) 100%);
    border-radius: 60px 60px 30px 30px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    transition: all 0.5s ease;
    border: 2px solid rgba(199, 184, 138, 0.3);
}

.character-glow {
    position: absolute;
    inset: -10px;
    background: radial-gradient(circle, rgba(199, 184, 138, 0.2) 0%, transparent 70%);
    border-radius: inherit;
    animation: pulse 2s ease-in-out infinite;
}

.character-details {
    position: relative;
    z-index: 2;
    text-align: center;
}

.character-name {
    color: white;
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 8px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
}

.character-class {
    color: #C7B88A;
    font-size: 12px;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.character-emblem {
    font-size: 32px;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.5));
}

.hunter-preview { 
    background: linear-gradient(180deg, rgba(110, 193, 228, 0.4) 0%, rgba(59, 130, 246, 0.3) 100%);
    border-color: rgba(110, 193, 228, 0.5);
}

.warlock-preview { 
    background: linear-gradient(180deg, rgba(182, 161, 228, 0.4) 0%, rgba(139, 92, 246, 0.3) 100%);
    border-color: rgba(182, 161, 228, 0.5);
}

.titan-preview { 
    background: linear-gradient(180deg, rgba(245, 158, 11, 0.4) 0%, rgba(239, 68, 68, 0.3) 100%);
    border-color: rgba(245, 158, 11, 0.5);
}

.form-group {
    position: relative;
}

.btn-loading {
    display: flex;
    align-items: center;
    justify-content: center;
}

@keyframes forge {
    0% { transform: scale(1) rotate(0deg); }
    50% { transform: scale(1.1) rotate(180deg); }
    100% { transform: scale(1) rotate(360deg); }
}

.forging {
    animation: forge 2s ease-in-out infinite;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('crearPersonajeForm');
    const nombreInput = document.getElementById('nombre_personaje');
    const nameCounter = document.getElementById('nameCounter');
    const classRadios = document.querySelectorAll('.class-radio');
    const colorRadios = document.querySelectorAll('input[name="aspecto_visual[color_armadura]"]');
    const emblemRadios = document.querySelectorAll('input[name="aspecto_visual[emblem]"]');
    const forgeBtn = document.getElementById('forgeBtn');
    
    // Preview elements
    const previewName = document.getElementById('previewName');
    const previewClass = document.getElementById('previewClass');
    const previewEmblem = document.getElementById('previewEmblem');
    const characterSilhouette = document.getElementById('characterSilhouette');
    const previewStats = document.getElementById('previewStats');
    
    // Classes data
    const classesData = {
        '1': {
            name: 'CAZADOR',
            stats: [
                { label: 'Agilidad', value: 95 },
                { label: 'Sigilo', value: 90 },
                { label: 'Precisión', value: 85 }
            ],
            className: 'hunter-preview'
        },
        '2': {
            name: 'HECHICERO',
            stats: [
                { label: 'Inteligencia', value: 95 },
                { label: 'Poderes Místicos', value: 90 },
                { label: 'Conocimiento', value: 85 }
            ],
            className: 'warlock-preview'
        },
        '3': {
            name: 'TITÁN',
            stats: [
                { label: 'Fuerza', value: 95 },
                { label: 'Resistencia', value: 90 },
                { label: 'Liderazgo', value: 85 }
            ],
            className: 'titan-preview'
        }
    };

    const emblems = {
        'phoenix': '🔥',
        'wolf': '🐺',
        'eagle': '🦅', 
        'dragon': '🐉',
        'star': '⭐',
        'moon': '🌙'
    };

    // Name input counter
    nombreInput.addEventListener('input', function() {
        const length = this.value.length;
        nameCounter.textContent = `${length}/50`;
        previewName.textContent = this.value || 'Guardián Sin Nombre';
        
        if (length > 40) {
            nameCounter.classList.add('text-yellow-400');
        } else {
            nameCounter.classList.remove('text-yellow-400');
        }
    });

    // Class selection
    classRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                updateClassPreview(this.value);
            }
        });
    });

    function updateClassPreview(classId) {
        const classData = classesData[classId];
        if (!classData) return;

        // Update class name
        previewClass.textContent = classData.name;
        
        // Update character appearance
        characterSilhouette.className = `character-silhouette ${classData.className}`;
        
        // Update stats
        const statsHTML = classData.stats.map(stat => `
            <div class="flex justify-between items-center text-sm">
                <span class="text-destiny-gold">${stat.label}</span>
                <span class="text-white">${stat.value}%</span>
            </div>
        `).join('');
        previewStats.innerHTML = statsHTML;
    }

    // Color selection
    colorRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                updateColorPreview(this.value);
            }
        });
    });

    function updateColorPreview(color) {
        // This could update character silhouette color
        // For now, just add a subtle effect
        characterSilhouette.style.filter = `hue-rotate(${getColorRotation(color)}deg)`;
    }

    function getColorRotation(color) {
        const rotations = {
            'blue': 0,
            'red': 45,
            'gold': 90,
            'purple': 270
        };
        return rotations[color] || 0;
    }

    // Emblem selection
    emblemRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                updateEmblemPreview(this.value);
            }
        });
    });

    function updateEmblemPreview(emblem) {
        previewEmblem.textContent = emblems[emblem] || '⚔️';
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate required fields
        if (!nombreInput.value.trim()) {
            nombreInput.focus();
            nombreInput.classList.add('border-red-500');
            return;
        }
        
        const selectedClass = document.querySelector('.class-radio:checked');
        if (!selectedClass) {
            document.querySelector('.class-card').scrollIntoView({ behavior: 'smooth' });
            return;
        }
        
        // Start forging animation
        const btnText = forgeBtn.querySelector('.btn-text');
        const btnLoading = forgeBtn.querySelector('.btn-loading');
        
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
        forgeBtn.disabled = true;
        
        // Add forging effect to character
        characterSilhouette.classList.add('forging');
        
        // Submit after animation
        setTimeout(() => {
            form.submit();
        }, 3000);
    });

    // Initialize preview with existing values
    if (nombreInput.value) {
        nameCounter.textContent = `${nombreInput.value.length}/50`;
        previewName.textContent = nombreInput.value;
    }
    
    const selectedClassRadio = document.querySelector('.class-radio:checked');
    if (selectedClassRadio) {
        updateClassPreview(selectedClassRadio.value);
    }
    
    const selectedColorRadio = document.querySelector('input[name="aspecto_visual[color_armadura]"]:checked');
    if (selectedColorRadio) {
        updateColorPreview(selectedColorRadio.value);
    }
    
    const selectedEmblemRadio = document.querySelector('input[name="aspecto_visual[emblem]"]:checked');
    if (selectedEmblemRadio) {
        updateEmblemPreview(selectedEmblemRadio.value);
    }
});
</script>
@endsection