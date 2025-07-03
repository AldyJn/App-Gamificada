@extends('layouts.app')

@section('title', 'Inicializar Operación')

@section('content')
<div class="min-h-screen destiny-bg-primary">
    <!-- Particles Background -->
    <div class="fixed inset-0 z-0">
        <div class="particles-container">
            @for($i = 0; $i < 50; $i++)
                <div class="particle"></div>
            @endfor
        </div>
    </div>

    <div class="relative z-10 container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-4 destiny-glow-gold tracking-wider">
                INICIALIZAR OPERACIÓN
            </h1>
            <p class="text-destiny-cyan text-lg md:text-xl opacity-90">
                Configurar nueva operación de entrenamiento del Guardián
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Formulario Principal -->
            <div class="lg:col-span-2">
                <form id="crearClaseForm" action="{{ route('profesor.crear-clase') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Step 1: Información Básica -->
                    <div class="holo-panel p-6 md:p-8 step-panel" data-step="1">
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 rounded-full bg-destiny-gold flex items-center justify-center mr-4">
                                <span class="text-destiny-bg-primary font-bold">1</span>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Datos de Operación</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="nombre" class="block text-destiny-gold font-semibold mb-2">
                                    Nombre de la Operación *
                                </label>
                                <input 
                                    type="text" 
                                    id="nombre" 
                                    name="nombre" 
                                    value="{{ old('nombre') }}"
                                    class="w-full p-3 destiny-input @error('nombre') border-red-500 @enderror"
                                    placeholder="Ej: Matemáticas Avanzadas"
                                    required
                                >
                                @error('nombre')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="grado" class="block text-destiny-gold font-semibold mb-2">
                                    Grado
                                </label>
                                <select id="grado" name="grado" class="w-full p-3 destiny-input">
                                    <option value="">Seleccionar grado</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}°" {{ old('grado') == $i.'°' ? 'selected' : '' }}>
                                            {{ $i }}°
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="seccion" class="block text-destiny-gold font-semibold mb-2">
                                    Sección
                                </label>
                                <input 
                                    type="text" 
                                    id="seccion" 
                                    name="seccion" 
                                    value="{{ old('seccion') }}"
                                    class="w-full p-3 destiny-input"
                                    placeholder="Ej: A, B, C"
                                    maxlength="2"
                                >
                            </div>

                            <div class="form-group">
                                <label for="tema_visual" class="block text-destiny-gold font-semibold mb-2">
                                    Tema Visual
                                </label>
                                <select id="tema_visual" name="tema_visual" class="w-full p-3 destiny-input">
                                    <option value="destiny_default" {{ old('tema_visual') == 'destiny_default' ? 'selected' : '' }}>
                                        Destiny Clásico
                                    </option>
                                    <option value="destiny_dark" {{ old('tema_visual') == 'destiny_dark' ? 'selected' : '' }}>
                                        Oscuridad Profunda
                                    </option>
                                    <option value="destiny_light" {{ old('tema_visual') == 'destiny_light' ? 'selected' : '' }}>
                                        Luz del Viajero
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group mt-6">
                            <label for="descripcion" class="block text-destiny-gold font-semibold mb-2">
                                Descripción de la Operación
                            </label>
                            <textarea 
                                id="descripcion" 
                                name="descripcion" 
                                rows="4" 
                                class="w-full p-3 destiny-input resize-none"
                                placeholder="Describe los objetivos y contenido de esta operación..."
                            >{{ old('descripcion') }}</textarea>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="button" class="destiny-btn destiny-btn-primary next-step">
                                Continuar →
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Configuración Temporal -->
                    <div class="holo-panel p-6 md:p-8 step-panel hidden" data-step="2">
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 rounded-full bg-destiny-cyan flex items-center justify-center mr-4">
                                <span class="text-destiny-bg-primary font-bold">2</span>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Configuración Temporal</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="fecha_inicio" class="block text-destiny-gold font-semibold mb-2">
                                    Fecha de Inicio *
                                </label>
                                <input 
                                    type="date" 
                                    id="fecha_inicio" 
                                    name="fecha_inicio" 
                                    value="{{ old('fecha_inicio') }}"
                                    class="w-full p-3 destiny-input @error('fecha_inicio') border-red-500 @enderror"
                                    required
                                >
                                @error('fecha_inicio')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="fecha_fin" class="block text-destiny-gold font-semibold mb-2">
                                    Fecha de Finalización *
                                </label>
                                <input 
                                    type="date" 
                                    id="fecha_fin" 
                                    name="fecha_fin" 
                                    value="{{ old('fecha_fin') }}"
                                    class="w-full p-3 destiny-input @error('fecha_fin') border-red-500 @enderror"
                                    required
                                >
                                @error('fecha_fin')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mt-6">
                            <label class="flex items-center space-x-3">
                                <input 
                                    type="checkbox" 
                                    id="modo_competitivo" 
                                    name="modo_competitivo" 
                                    value="1"
                                    {{ old('modo_competitivo') ? 'checked' : '' }}
                                    class="w-5 h-5 text-destiny-gold bg-destiny-bg-secondary border-destiny-gold rounded focus:ring-destiny-gold"
                                >
                                <span class="text-white">
                                    Activar Modo Competitivo
                                    <span class="block text-sm text-destiny-cyan opacity-80">
                                        Los estudiantes competirán por rankings y recompensas especiales
                                    </span>
                                </span>
                            </label>
                        </div>

                        <div class="flex justify-between mt-6">
                            <button type="button" class="destiny-btn destiny-btn-secondary prev-step">
                                ← Anterior
                            </button>
                            <button type="button" class="destiny-btn destiny-btn-primary next-step">
                                Continuar →
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Confirmación -->
                    <div class="holo-panel p-6 md:p-8 step-panel hidden" data-step="3">
                        <div class="flex items-center mb-6">
                            <div class="w-8 h-8 rounded-full bg-destiny-purple flex items-center justify-center mr-4">
                                <span class="text-destiny-bg-primary font-bold">3</span>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Confirmación</h2>
                        </div>

                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between items-center py-2 border-b border-destiny-bg-tertiary">
                                <span class="text-destiny-gold">Operación:</span>
                                <span class="text-white" id="confirm-nombre">-</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-destiny-bg-tertiary">
                                <span class="text-destiny-gold">Grado/Sección:</span>
                                <span class="text-white" id="confirm-grado-seccion">-</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-destiny-bg-tertiary">
                                <span class="text-destiny-gold">Duración:</span>
                                <span class="text-white" id="confirm-duracion">-</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-destiny-bg-tertiary">
                                <span class="text-destiny-gold">Modo Competitivo:</span>
                                <span class="text-white" id="confirm-competitivo">-</span>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" class="destiny-btn destiny-btn-secondary prev-step">
                                ← Anterior
                            </button>
                            <button type="submit" class="destiny-btn destiny-btn-success" id="submitBtn">
                                <span class="btn-text">🚀 Inicializar Operación</span>
                                <span class="btn-loading hidden">
                                    <svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Inicializando...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Panel de Resumen -->
            <div class="lg:col-span-1">
                <div class="holo-panel p-6 sticky top-8">
                    <h3 class="text-xl font-bold text-white mb-4 destiny-glow-gold">
                        Resumen de Operación
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="summary-item">
                            <span class="text-destiny-gold text-sm">Estado:</span>
                            <span class="text-white block" id="summary-status">Configurando...</span>
                        </div>
                        
                        <div class="summary-item">
                            <span class="text-destiny-gold text-sm">Progreso:</span>
                            <div class="w-full bg-destiny-bg-secondary rounded-full h-2 mt-1">
                                <div class="bg-destiny-gold h-2 rounded-full transition-all duration-300" 
                                     style="width: 33%" id="progress-bar"></div>
                            </div>
                            <span class="text-destiny-cyan text-xs mt-1 block" id="progress-text">1 de 3 completado</span>
                        </div>

                        <div class="pt-4 border-t border-destiny-bg-tertiary">
                            <div class="text-center">
                                <div class="w-24 h-24 mx-auto mb-4 bg-destiny-bg-secondary rounded-lg flex items-center justify-center border border-destiny-gold">
                                    <span class="text-destiny-gold text-xs">QR Code</span>
                                </div>
                                <p class="text-destiny-cyan text-sm opacity-80">
                                    El código QR se generará al completar la operación
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

.step-panel {
    opacity: 0;
    transform: translateX(20px);
    transition: all 0.5s ease;
}

.step-panel:not(.hidden) {
    opacity: 1;
    transform: translateX(0);
}

.form-group {
    position: relative;
}

.form-group input:focus + label,
.form-group textarea:focus + label {
    color: #C7B88A;
}

.particles-container {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.particle {
    position: absolute;
    width: 2px;
    height: 2px;
    background: rgba(199, 184, 138, 0.3);
    border-radius: 50%;
    animation: float 20s infinite linear;
}

@keyframes float {
    0% {
        transform: translateY(100vh) rotate(0deg);
        opacity: 0;
    }
    10% {
        opacity: 1;
    }
    90% {
        opacity: 1;
    }
    100% {
        transform: translateY(-100vh) rotate(360deg);
        opacity: 0;
    }
}

.particle:nth-child(odd) {
    animation-delay: -10s;
    animation-duration: 25s;
}

.particle:nth-child(even) {
    animation-delay: -5s;
    animation-duration: 15s;
}

/* Particle positioning will be handled by JavaScript */

.btn-loading {
    display: flex;
    align-items: center;
    justify-content: center;
}

.summary-item {
    padding: 12px 0;
    border-bottom: 1px solid rgba(58, 61, 83, 0.5);
}

.summary-item:last-child {
    border-bottom: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('crearClaseForm');
    const stepPanels = document.querySelectorAll('.step-panel');
    const nextButtons = document.querySelectorAll('.next-step');
    const prevButtons = document.querySelectorAll('.prev-step');
    const submitBtn = document.getElementById('submitBtn');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    
    let currentStep = 1;
    const totalSteps = 3;

    // Navegación entre pasos
    nextButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            if (validateCurrentStep()) {
                goToStep(currentStep + 1);
            }
        });
    });

    prevButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            goToStep(currentStep - 1);
        });
    });

    function goToStep(step) {
        if (step < 1 || step > totalSteps) return;
        
        // Ocultar paso actual
        stepPanels[currentStep - 1].classList.add('hidden');
        
        // Mostrar nuevo paso
        setTimeout(() => {
            stepPanels[step - 1].classList.remove('hidden');
            currentStep = step;
            updateProgress();
            
            if (step === 3) {
                updateConfirmation();
            }
        }, 200);
    }

    function updateProgress() {
        const progress = (currentStep / totalSteps) * 100;
        progressBar.style.width = progress + '%';
        progressText.textContent = `${currentStep} de ${totalSteps} completado`;
    }

    function validateCurrentStep() {
        const currentPanel = stepPanels[currentStep - 1];
        const requiredFields = currentPanel.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
                isValid = false;
            } else {
                field.classList.remove('border-red-500');
            }
        });

        return isValid;
    }

    function updateConfirmation() {
        const nombre = document.getElementById('nombre').value;
        const grado = document.getElementById('grado').value;
        const seccion = document.getElementById('seccion').value;
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFin = document.getElementById('fecha_fin').value;
        const competitivo = document.getElementById('modo_competitivo').checked;

        document.getElementById('confirm-nombre').textContent = nombre || '-';
        document.getElementById('confirm-grado-seccion').textContent = 
            [grado, seccion].filter(Boolean).join(' - ') || '-';
        document.getElementById('confirm-duracion').textContent = 
            fechaInicio && fechaFin ? `${fechaInicio} a ${fechaFin}` : '-';
        document.getElementById('confirm-competitivo').textContent = 
            competitivo ? 'Activado' : 'Desactivado';
    }

    // Envío del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoading = submitBtn.querySelector('.btn-loading');
        
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
        submitBtn.disabled = true;
        
        // Simular envío
        setTimeout(() => {
            form.submit();
        }, 1000);
    });

    // Validación en tiempo real
    const inputs = document.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('border-red-500');
        });
    });

    // Generar partículas
    const particlesContainer = document.querySelector('.particles-container');
    for (let i = 0; i < 50; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDelay = (Math.random() * 20 - 10) + 's';
        particle.style.animationDuration = (Math.random() * 10 + 15) + 's';
        particlesContainer.appendChild(particle);
    }
});
</script>
@endsection