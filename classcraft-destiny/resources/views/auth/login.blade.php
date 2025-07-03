{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.auth')

@section('title', 'Acceso a la Torre')

@section('content')
<div class="min-h-screen flex items-center justify-center relative overflow-hidden">
    <!-- Enhanced Starfield Background -->
    <div class="absolute inset-0 starfield-login">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/20 via-purple-900/20 to-cyan-900/20"></div>
    </div>
    
    <!-- Floating Geometric Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="floating-hex hex-1"></div>
        <div class="floating-hex hex-2"></div>
        <div class="floating-hex hex-3"></div>
        <div class="floating-triangle tri-1"></div>
        <div class="floating-triangle tri-2"></div>
    </div>
    
    <!-- Login Container -->
    <div class="w-full max-w-md z-10 relative">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <!-- Logo/Icon -->
            <div class="mx-auto mb-6 w-20 h-20 rounded-full destiny-glow-gold flex items-center justify-center"
                 style="background: linear-gradient(135deg, var(--destiny-bg-secondary), var(--destiny-bg-tertiary));">
                <svg class="w-12 h-12 text-gold-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
                    <path d="M11 14h2v-4h-2v4zm0-6h2V6h-2v2z" opacity="0.7"/>
                </svg>
            </div>
            
            <!-- Title -->
            <h1 class="destiny-heading text-3xl font-bold mb-2">
                Acceso a la Torre
            </h1>
            <p class="destiny-text-muted text-sm">
                Identifícate para acceder al sistema de entrenamiento
            </p>
        </div>
        
        <!-- Login Form -->
        <div class="holo-panel rounded-2xl p-8 space-y-6">
            <form method="POST" action="{{ route('login') }}" class="space-y-6" id="loginForm">
                @csrf
                
                <!-- Email Field -->
                <div class="space-y-2">
                    <label for="correo" class="block destiny-text text-sm font-medium">
                        <span class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                            <span>Correo de Acceso</span>
                        </span>
                    </label>
                    <div class="relative">
                        <input id="correo" 
                               name="correo" 
                               type="email" 
                               autocomplete="email" 
                               required
                               value="{{ old('correo') }}"
                               class="w-full px-4 py-3 rounded-lg bg-black/20 border border-cyan-500/30 text-white placeholder-gray-400 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 focus:outline-none transition-all duration-300 destiny-glow"
                               placeholder="guardian@torre.com">
                        @error('correo')
                            <div class="absolute -bottom-6 left-0 text-red-400 text-xs flex items-center space-x-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                </div>
                
                <!-- Password Field -->
                <div class="space-y-2">
                    <label for="password" class="block destiny-text text-sm font-medium">
                        <span class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span>Código de Seguridad</span>
                        </span>
                    </label>
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" 
                               id="password" 
                               name="password" 
                               autocomplete="current-password" 
                               required
                               class="w-full px-4 py-3 pr-12 rounded-lg bg-black/20 border border-cyan-500/30 text-white placeholder-gray-400 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20 focus:outline-none transition-all duration-300 destiny-glow"
                               placeholder="••••••••">
                        <button @click="show = !show" 
                                type="button"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-cyan-400 transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                            </svg>
                        </button>
                        @error('password')
                            <div class="absolute -bottom-6 left-0 text-red-400 text-xs flex items-center space-x-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                </svg>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                </div>
                
                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" 
                               name="remember" 
                               id="remember"
                               class="w-4 h-4 rounded border-cyan-500/30 bg-black/20 text-cyan-400 focus:ring-cyan-400/20 focus:ring-2">
                        <span class="destiny-text text-sm">Recordar sesión</span>
                    </label>
                    
                    <a href="#" class="destiny-text text-sm hover:text-cyan-400 transition-colors">
                        ¿Olvidaste tu código?
                    </a>
                </div>
                
                <!-- Login Button -->
                <button type="submit" 
                        class="w-full destiny-btn px-6 py-3 rounded-lg font-semibold text-sm tracking-wide transition-all duration-300 transform hover:scale-105"
                        id="loginButton">
                    <span class="flex items-center justify-center space-x-2">
                        <span>Inicializar Conexión</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </span>
                </button>
            </form>
            
            <!-- Divider -->
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-cyan-500/30"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 destiny-text-muted" style="background-color: var(--destiny-bg-secondary);">
                        O
                    </span>
                </div>
            </div>
            
            <!-- Register Link -->
            <div class="text-center">
                <p class="destiny-text text-sm mb-3">
                    ¿Nuevo en el sistema?
                </p>
                <a href="{{ route('register') }}" 
                   class="destiny-btn inline-flex items-center px-6 py-2 rounded-lg text-sm font-medium transition-all duration-300 transform hover:scale-105">
                    <span class="flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        <span>Registrar Nuevo Guardián</span>
                    </span>
                </a>
            </div>
        </div>
        
        <!-- Demo Credentials (Solo en desarrollo) -->
        @if(app()->environment('local'))
            <div class="mt-6 holo-panel rounded-lg p-4 border-l-4 border-yellow-500">
                <h3 class="destiny-text text-sm font-semibold text-yellow-400 mb-2">
                    🧪 Credenciales de Prueba
                </h3>
                <div class="space-y-2 text-xs destiny-text-muted">
                    <div class="flex justify-between">
                        <span>👨‍🏫 Profesor:</span>
                        <span>profesor@test.com | password123</span>
                    </div>
                    <div class="flex justify-between">
                        <span>👥 Estudiante:</span>
                        <span>ana@estudiante.test.com | password123</span>
                    </div>
                </div>
                <div class="mt-3 flex space-x-2">
                    <button onclick="fillCredentials('profesor@test.com', 'password123')" 
                            class="text-xs px-3 py-1 rounded bg-cyan-500/20 text-cyan-400 hover:bg-cyan-500/30 transition-colors">
                        Usar Profesor
                    </button>
                    <button onclick="fillCredentials('ana@estudiante.test.com', 'password123')" 
                            class="text-xs px-3 py-1 rounded bg-purple-500/20 text-purple-400 hover:bg-purple-500/30 transition-colors">
                        Usar Estudiante
                    </button>
                </div>
            </div>
        @endif
        
        <!-- Footer Info -->
        <div class="mt-8 text-center">
            <p class="destiny-text-muted text-xs">
                Sistema Educativo Gamificado v1.0<br>
                Powered by ClassCraft-Destiny Engine
            </p>
        </div>
    </div>
</div>

<!-- Additional Styles for Login Page -->
<style>
    .starfield-login {
        background: radial-gradient(ellipse at center, #1B1C29 0%, #0D0E1A 100%);
        position: relative;
    }
    
    .starfield-login::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: 
            radial-gradient(3px 3px at 10% 20%, rgba(110, 193, 228, 0.9), transparent),
            radial-gradient(2px 2px at 80% 80%, rgba(199, 184, 138, 0.8), transparent),
            radial-gradient(1px 1px at 30% 70%, rgba(182, 161, 228, 0.7), transparent),
            radial-gradient(1px 1px at 90% 10%, rgba(110, 193, 228, 0.6), transparent),
            radial-gradient(2px 2px at 50% 50%, rgba(255, 123, 0, 0.5), transparent);
        background-size: 400px 400px, 300px 300px, 200px 200px, 150px 150px, 100px 100px;
        animation: twinkle 8s linear infinite;
    }
    
    @keyframes twinkle {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.7; transform: scale(1.1); }
    }
    
    /* Floating Geometric Elements */
    .floating-hex {
        position: absolute;
        width: 60px;
        height: 60px;
        border: 2px solid rgba(110, 193, 228, 0.3);
        clip-path: polygon(30% 0%, 70% 0%, 100% 50%, 70% 100%, 30% 100%, 0% 50%);
        animation: float-hex 20s linear infinite;
    }
    
    .hex-1 {
        top: 10%;
        left: 10%;
        animation-delay: 0s;
    }
    
    .hex-2 {
        top: 60%;
        right: 15%;
        animation-delay: -7s;
        border-color: rgba(199, 184, 138, 0.3);
    }
    
    .hex-3 {
        bottom: 20%;
        left: 20%;
        animation-delay: -14s;
        border-color: rgba(182, 161, 228, 0.3);
    }
    
    .floating-triangle {
        position: absolute;
        width: 0;
        height: 0;
        border-left: 20px solid transparent;
        border-right: 20px solid transparent;
        border-bottom: 35px solid rgba(110, 193, 228, 0.2);
        animation: float-triangle 15s linear infinite;
    }
    
    .tri-1 {
        top: 30%;
        right: 20%;
        animation-delay: -3s;
    }
    
    .tri-2 {
        bottom: 40%;
        left: 80%;
        animation-delay: -10s;
        border-bottom-color: rgba(199, 184, 138, 0.2);
    }
    
    @keyframes float-hex {
        0% { transform: translateY(0px) rotate(0deg); opacity: 0.3; }
        50% { transform: translateY(-20px) rotate(180deg); opacity: 0.7; }
        100% { transform: translateY(0px) rotate(360deg); opacity: 0.3; }
    }
    
    @keyframes float-triangle {
        0% { transform: translateX(0px) rotate(0deg); opacity: 0.2; }
        33% { transform: translateX(30px) rotate(120deg); opacity: 0.6; }
        66% { transform: translateX(-15px) rotate(240deg); opacity: 0.4; }
        100% { transform: translateX(0px) rotate(360deg); opacity: 0.2; }
    }
    
    /* Enhanced Input Focus Effects */
    input:focus {
        box-shadow: 
            0 0 0 1px rgba(110, 193, 228, 0.5),
            0 0 20px rgba(110, 193, 228, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }
    
    /* Loading Animation for Button */
    .btn-loading {
        pointer-events: none;
        opacity: 0.7;
    }
    
    .btn-loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        margin: auto;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
</style>

<!-- Scripts for Enhanced Functionality -->
<script>
    // Auto-fill credentials function (development only)
    @if(app()->environment('local'))
    function fillCredentials(email, password) {
        document.getElementById('correo').value = email;
        document.getElementById('password').value = password;
        
        // Add visual feedback
        const emailInput = document.getElementById('correo');
        const passInput = document.getElementById('password');
        
        emailInput.classList.add('destiny-glow-gold');
        passInput.classList.add('destiny-glow-gold');
        
        setTimeout(() => {
            emailInput.classList.remove('destiny-glow-gold');
            passInput.classList.remove('destiny-glow-gold');
        }, 1000);
    }
    @endif
    
    // Enhanced form submission with loading state
    document.getElementById('loginForm').addEventListener('submit', function() {
        const button = document.getElementById('loginButton');
        button.classList.add('btn-loading');
        button.innerHTML = '<span class="loading-ring w-4 h-4"></span>';
    });
    
    // Auto-focus first input
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('correo').focus();
    });
    
    // Enter key navigation between fields
    document.getElementById('correo').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('password').focus();
        }
    });
</script>
@endsection