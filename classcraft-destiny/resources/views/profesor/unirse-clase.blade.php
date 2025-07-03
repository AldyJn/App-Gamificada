@extends('layouts.app')

@section('title', 'Scanner QR - Acceso a Operación')

@section('content')
<div class="min-h-screen destiny-bg-primary">
    <!-- Particles Background -->
    <div class="fixed inset-0 z-0">
        <div class="particles-container">
            @for($i = 0; $i < 30; $i++)
                <div class="particle"></div>
            @endfor
        </div>
    </div>

    <div class="relative z-10 container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-4 destiny-glow-cyan tracking-wider">
                SCANNER QR
            </h1>
            <p class="text-destiny-gold text-lg md:text-xl opacity-90">
                Acceso a Operación del Guardián
            </p>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Scanner Panel -->
                <div class="holo-panel p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-white mb-2 destiny-glow-gold">
                            Terminal de Acceso
                        </h2>
                        <p class="text-destiny-cyan opacity-80">
                            Escanea el código QR o ingresa manualmente
                        </p>
                    </div>

                    <!-- QR Scanner Simulator -->
                    <div class="scanner-container mb-8">
                        <div class="scanner-frame">
                            <div class="scanner-overlay">
                                <div class="scanner-line"></div>
                                <div class="scanner-corners">
                                    <div class="corner top-left"></div>
                                    <div class="corner top-right"></div>
                                    <div class="corner bottom-left"></div>
                                    <div class="corner bottom-right"></div>
                                </div>
                                <div class="scanner-target" id="scannerTarget">
                                    <div class="target-center">
                                        <svg class="w-16 h-16 text-destiny-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 16h4.01M19.02 16.91l1.41-1.41M4.98 19.78l1.41-1.41M4.98 4.22l1.41 1.41M19.02 7.09l1.41 1.41"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="scanner-status text-center mt-4">
                            <p class="text-destiny-cyan" id="scannerStatus">
                                Posiciona el código QR en el centro del escáner
                            </p>
                        </div>
                    </div>

                    <!-- Manual Input -->
                    <form id="unirseClaseForm" action="{{ route('estudiante.unirse-clase') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="codigo_invitacion" class="block text-destiny-gold font-semibold mb-3">
                                Código de Invitación
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="codigo_invitacion" 
                                    name="codigo_invitacion" 
                                    value="{{ old('codigo_invitacion') }}"
                                    class="w-full p-4 destiny-input text-center text-lg tracking-widest @error('codigo_invitacion') border-red-500 @enderror"
                                    placeholder="XXXXXX"
                                    maxlength="6"
                                    required
                                >
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <svg class="w-5 h-5 text-destiny-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-3a1 1 0 011-1h2.586l6.243-6.243A6 6 0 0121 9z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('codigo_invitacion')
                                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="w-full destiny-btn destiny-btn-primary mt-6" id="submitBtn">
                            <span class="btn-text">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Acceder a Operación
                            </span>
                            <span class="btn-loading hidden">
                                <svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Verificando acceso...
                            </span>
                        </button>
                    </form>
                </div>

                <!-- Clases Disponibles -->
                <div class="holo-panel p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-white mb-2 destiny-glow-purple">
                            Operaciones Disponibles
                        </h2>
                        <p class="text-destiny-cyan opacity-80">
                            Clases públicas que puedes unirte
                        </p>
                    </div>

                    <div class="space-y-4" id="clasesDisponibles">
                        <!-- Ejemplo de clases disponibles -->
                        <div class="clase-item">
                            <div class="bg-destiny-bg-secondary rounded-lg p-4 border border-destiny-bg-tertiary hover:border-destiny-gold transition-all duration-300 cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-white font-semibold">Matemáticas Avanzadas</h3>
                                        <p class="text-destiny-cyan text-sm">10° A - Prof. García</p>
                                        <p class="text-destiny-gold text-xs mt-1">25 Guardianes activos</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block w-3 h-3 bg-green-400 rounded-full"></span>
                                        <span class="text-green-400 text-xs ml-1">Activa</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="clase-item">
                            <div class="bg-destiny-bg-secondary rounded-lg p-4 border border-destiny-bg-tertiary hover:border-destiny-gold transition-all duration-300 cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-white font-semibold">Historia Universal</h3>
                                        <p class="text-destiny-cyan text-sm">9° B - Prof. Martínez</p>
                                        <p class="text-destiny-gold text-xs mt-1">18 Guardianes activos</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block w-3 h-3 bg-green-400 rounded-full"></span>
                                        <span class="text-green-400 text-xs ml-1">Activa</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="clase-item">
                            <div class="bg-destiny-bg-secondary rounded-lg p-4 border border-destiny-bg-tertiary opacity-60">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-white font-semibold">Física Cuántica</h3>
                                        <p class="text-destiny-cyan text-sm">11° A - Prof. López</p>
                                        <p class="text-destiny-gold text-xs mt-1">Solo por invitación</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block w-3 h-3 bg-yellow-400 rounded-full"></span>
                                        <span class="text-yellow-400 text-xs ml-1">Privada</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 text-center">
                        <button class="destiny-btn destiny-btn-secondary" id="refreshClases">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Actualizar Lista
                        </button>
                    </div>
                </div>
            </div>

            <!-- Status Messages -->
            <div class="mt-8 text-center" id="statusMessages">
                @if(session('success'))
                    <div class="bg-green-500 bg-opacity-20 border border-green-500 text-green-400 px-6 py-4 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-500 bg-opacity-20 border border-red-500 text-red-400 px-6 py-4 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif
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

.scanner-container {
    position: relative;
    max-width: 300px;
    margin: 0 auto;
}

.scanner-frame {
    position: relative;
    width: 100%;
    aspect-ratio: 1;
    background: linear-gradient(135deg, rgba(110, 193, 228, 0.1), rgba(182, 161, 228, 0.1));
    border-radius: 16px;
    overflow: hidden;
    border: 2px solid rgba(110, 193, 228, 0.3);
}

.scanner-overlay {
    position: absolute;
    inset: 0;
    background: 
        radial-gradient(circle at center, transparent 40%, rgba(27, 28, 41, 0.8) 70%),
        linear-gradient(90deg, transparent 48%, rgba(110, 193, 228, 0.1) 50%, transparent 52%),
        linear-gradient(0deg, transparent 48%, rgba(110, 193, 228, 0.1) 50%, transparent 52%);
}

.scanner-line {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, #6EC1E4, transparent);
    animation: scan 2s ease-in-out infinite;
    box-shadow: 0 0 10px #6EC1E4;
}

@keyframes scan {
    0% { transform: translateY(0); opacity: 1; }
    50% { opacity: 0.8; }
    100% { transform: translateY(296px); opacity: 0; }
}

.scanner-corners {
    position: absolute;
    inset: 20px;
}

.corner {
    position: absolute;
    width: 30px;
    height: 30px;
    border: 3px solid #C7B88A;
    box-shadow: 0 0 10px rgba(199, 184, 138, 0.5);
}

.corner.top-left {
    top: 0;
    left: 0;
    border-right: none;
    border-bottom: none;
    border-top-left-radius: 8px;
}

.corner.top-right {
    top: 0;
    right: 0;
    border-left: none;
    border-bottom: none;
    border-top-right-radius: 8px;
}

.corner.bottom-left {
    bottom: 0;
    left: 0;
    border-right: none;
    border-top: none;
    border-bottom-left-radius: 8px;
}

.corner.bottom-right {
    bottom: 0;
    right: 0;
    border-left: none;
    border-top: none;
    border-bottom-right-radius: 8px;
}

.scanner-target {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 80px;
    height: 80px;
    border: 2px dashed rgba(110, 193, 228, 0.5);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { 
        border-color: rgba(110, 193, 228, 0.5);
        transform: translate(-50%, -50%) scale(1);
    }
    50% { 
        border-color: rgba(110, 193, 228, 0.8);
        transform: translate(-50%, -50%) scale(1.1);
    }
}

.target-center {
    animation: spin 4s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.particles-container {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.particle {
    position: absolute;
    width: 1px;
    height: 1px;
    background: rgba(110, 193, 228, 0.4);
    border-radius: 50%;
    animation: float 15s infinite linear;
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

.clase-item {
    transition: all 0.3s ease;
}

.clase-item:hover {
    transform: translateX(5px);
}

.form-group {
    position: relative;
}

.btn-loading {
    display: flex;
    align-items: center;
    justify-content: center;
}

.scanning {
    animation: scanning-effect 1.5s ease-in-out;
}

@keyframes scanning-effect {
    0% { border-color: rgba(110, 193, 228, 0.3); }
    50% { 
        border-color: #6EC1E4;
        box-shadow: 0 0 30px rgba(110, 193, 228, 0.6);
    }
    100% { border-color: rgba(110, 193, 228, 0.3); }
}

.code-valid {
    border-color: #10B981 !important;
    box-shadow: 0 0 20px rgba(16, 185, 129, 0.3) !important;
}

.code-invalid {
    border-color: #EF4444 !important;
    box-shadow: 0 0 20px rgba(239, 68, 68, 0.3) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('unirseClaseForm');
    const codigoInput = document.getElementById('codigo_invitacion');
    const submitBtn = document.getElementById('submitBtn');
    const scannerTarget = document.getElementById('scannerTarget');
    const scannerStatus = document.getElementById('scannerStatus');
    const refreshBtn = document.getElementById('refreshClases');
    
    // Códigos válidos simulados
    const validCodes = ['ABC123', 'DEF456', 'GHI789', 'JKL012'];
    
    // Auto-formatear el código
    codigoInput.addEventListener('input', function(e) {
        let value = e.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        if (value.length > 6) value = value.slice(0, 6);
        e.target.value = value;
        
        validateCode(value);
    });

    function validateCode(code) {
        codigoInput.classList.remove('code-valid', 'code-invalid', 'border-red-500');
        
        if (code.length === 6) {
            if (validCodes.includes(code)) {
                codigoInput.classList.add('code-valid');
                showScannerSuccess();
            } else {
                codigoInput.classList.add('code-invalid');
                showScannerError();
            }
        } else if (code.length > 0) {
            // Código en progreso
            scannerStatus.textContent = 'Validando código...';
            scannerTarget.classList.add('scanning');
        } else {
            // Campo vacío
            scannerStatus.textContent = 'Posiciona el código QR en el centro del escáner';
            scannerTarget.classList.remove('scanning');
        }
    }

    function showScannerSuccess() {
        scannerStatus.innerHTML = '<span class="text-green-400">✓ Código válido - Acceso autorizado</span>';
        scannerTarget.classList.remove('scanning');
        scannerTarget.style.borderColor = '#10B981';
    }

    function showScannerError() {
        scannerStatus.innerHTML = '<span class="text-red-400">✗ Código inválido - Verifica el código</span>';
        scannerTarget.classList.remove('scanning');
        scannerTarget.style.borderColor = '#EF4444';
        
        // Resetear después de 3 segundos
        setTimeout(() => {
            scannerStatus.textContent = 'Posiciona el código QR en el centro del escáner';
            scannerTarget.style.borderColor = 'rgba(110, 193, 228, 0.5)';
        }, 3000);
    }

    // Envío del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const codigo = codigoInput.value;
        
        if (codigo.length !== 6) {
            codigoInput.classList.add('border-red-500');
            return;
        }
        
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoading = submitBtn.querySelector('.btn-loading');
        
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
        submitBtn.disabled = true;
        
        // Simular verificación
        setTimeout(() => {
            if (validCodes.includes(codigo)) {
                // Redirigir o enviar formulario real
                showSuccessMessage('¡Acceso autorizado! Redirigiendo...');
                setTimeout(() => {
                    form.submit();
                }, 1500);
            } else {
                showErrorMessage('Código de invitación inválido');
                resetSubmitButton();
            }
        }, 2000);
    });

    function resetSubmitButton() {
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoading = submitBtn.querySelector('.btn-loading');
        
        btnText.classList.remove('hidden');
        btnLoading.classList.add('hidden');
        submitBtn.disabled = false;
    }

    function showSuccessMessage(message) {
        const statusDiv = document.getElementById('statusMessages');
        statusDiv.innerHTML = `
            <div class="bg-green-500 bg-opacity-20 border border-green-500 text-green-400 px-6 py-4 rounded-lg animate-pulse">
                ${message}
            </div>
        `;
    }

    function showErrorMessage(message) {
        const statusDiv = document.getElementById('statusMessages');
        statusDiv.innerHTML = `
            <div class="bg-red-500 bg-opacity-20 border border-red-500 text-red-400 px-6 py-4 rounded-lg">
                ${message}
            </div>
        `;
    }

    // Refresh de clases
    refreshBtn.addEventListener('click', function() {
        const icon = this.querySelector('svg');
        icon.classList.add('animate-spin');
        
        setTimeout(() => {
            icon.classList.remove('animate-spin');
            showSuccessMessage('Lista de operaciones actualizada');
            setTimeout(() => {
                document.getElementById('statusMessages').innerHTML = '';
            }, 3000);
        }, 1500);
    });

    // Simulador de QR codes
    function simulateQRScan() {
        const codes = validCodes;
        let index = 0;
        
        setInterval(() => {
            if (codigoInput.value === '' && Math.random() < 0.1) {
                // 10% de probabilidad de "escanear" un código
                const randomCode = codes[Math.floor(Math.random() * codes.length)];
                typeCode(randomCode);
            }
        }, 3000);
    }

    function typeCode(code) {
        let i = 0;
        const interval = setInterval(() => {
            codigoInput.value += code[i];
            codigoInput.dispatchEvent(new Event('input'));
            i++;
            if (i >= code.length) {
                clearInterval(interval);
            }
        }, 100);
    }

    // Generar partículas
    const particlesContainer = document.querySelector('.particles-container');
    for (let i = 0; i < 30; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDelay = (Math.random() * 15) + 's';
        particle.style.animationDuration = (Math.random() * 5 + 10) + 's';
        particlesContainer.appendChild(particle);
    }

    // Inicializar simulador (opcional)
    // simulateQRScan();
});
</script>
@endsection