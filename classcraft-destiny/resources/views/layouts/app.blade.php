<!DOCTYPE html>
{{-- resources/views/layouts/app.blade.php --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'ClassCraft Destiny') }} - @yield('title', 'La Torre')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Destiny Theme CSS -->
    <style>
        :root {
            /* Destiny 2 Color Palette */
            --destiny-bg-primary: #1B1C29;
            --destiny-bg-secondary: #2E2F3D;
            --destiny-bg-tertiary: #3A3D53;
            --destiny-gold: #C7B88A;
            --destiny-cyan: #6EC1E4;
            --destiny-purple: #B6A1E4;
            --destiny-orange: #FF7B00;
            --destiny-void: #B19CD9;
            --destiny-arc: #79C7E3;
            --destiny-success: #7FBBA3;
            --destiny-warning: #F1C40F;
            --destiny-danger: #E74C3C;
            --destiny-info: #74B9FF;
            
            /* Typography */
            --font-header: 'Orbitron', monospace;
            --font-body: 'Open Sans', sans-serif;
        }
        
        * {
            scrollbar-width: thin;
            scrollbar-color: var(--destiny-cyan) var(--destiny-bg-secondary);
        }
        
        *::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        *::-webkit-scrollbar-track {
            background: var(--destiny-bg-secondary);
        }
        
        *::-webkit-scrollbar-thumb {
            background: var(--destiny-cyan);
            border-radius: 4px;
        }
        
        *::-webkit-scrollbar-thumb:hover {
            background: var(--destiny-purple);
        }
        
        /* Destiny Glow Effect */
        .destiny-glow {
            box-shadow: 
                0 0 5px rgba(110, 193, 228, 0.3),
                0 0 10px rgba(110, 193, 228, 0.2),
                0 0 15px rgba(110, 193, 228, 0.1);
        }
        
        .destiny-glow-gold {
            box-shadow: 
                0 0 5px rgba(199, 184, 138, 0.4),
                0 0 10px rgba(199, 184, 138, 0.3),
                0 0 15px rgba(199, 184, 138, 0.2);
        }
        
        .destiny-glow-purple {
            box-shadow: 
                0 0 5px rgba(182, 161, 228, 0.4),
                0 0 10px rgba(182, 161, 228, 0.3),
                0 0 15px rgba(182, 161, 228, 0.2);
        }
        
        /* Animated Background Particles */
        .starfield {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -10;
            background: radial-gradient(ellipse at bottom, #1B1C29 0%, #0D0E1A 100%);
        }
        
        .starfield::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(2px 2px at 20% 30%, rgba(110, 193, 228, 0.8), transparent),
                radial-gradient(2px 2px at 40% 70%, rgba(199, 184, 138, 0.6), transparent),
                radial-gradient(1px 1px at 90% 40%, rgba(182, 161, 228, 0.7), transparent),
                radial-gradient(1px 1px at 60% 10%, rgba(110, 193, 228, 0.5), transparent),
                radial-gradient(2px 2px at 80% 80%, rgba(199, 184, 138, 0.4), transparent);
            background-size: 550px 550px, 350px 350px, 250px 250px, 200px 200px, 150px 150px;
            animation: float 60s linear infinite;
        }
        
        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
            100% { transform: translate(0, 0) rotate(360deg); }
        }
        
        /* Holographic Panel Effect */
        .holo-panel {
            background: linear-gradient(135deg, 
                rgba(46, 47, 61, 0.9) 0%,
                rgba(58, 61, 83, 0.8) 50%,
                rgba(46, 47, 61, 0.9) 100%);
            border: 1px solid rgba(110, 193, 228, 0.3);
            backdrop-filter: blur(10px);
            position: relative;
        }
        
        .holo-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, 
                transparent 0%,
                rgba(110, 193, 228, 0.8) 50%,
                transparent 100%);
        }
        
        /* Destiny Button Styles */
        .destiny-btn {
            background: linear-gradient(135deg, 
                rgba(110, 193, 228, 0.2) 0%,
                rgba(58, 61, 83, 0.8) 50%,
                rgba(110, 193, 228, 0.2) 100%);
            border: 1px solid rgba(110, 193, 228, 0.5);
            color: var(--destiny-cyan);
            font-family: var(--font-header);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .destiny-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent 0%,
                rgba(110, 193, 228, 0.3) 50%,
                transparent 100%);
            transition: left 0.5s ease;
        }
        
        .destiny-btn:hover {
            border-color: var(--destiny-cyan);
            color: white;
            transform: translateY(-2px);
        }
        
        .destiny-btn:hover::before {
            left: 100%;
        }
        
        /* Navigation Styles */
        .nav-link {
            color: rgba(110, 193, 228, 0.8);
            font-family: var(--font-header);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--destiny-cyan);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover,
        .nav-link.active {
            color: white;
        }
        
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }
        
        /* Text Styles */
        .destiny-heading {
            font-family: var(--font-header);
            color: var(--destiny-cyan);
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 10px rgba(110, 193, 228, 0.5);
        }
        
        .destiny-text {
            font-family: var(--font-body);
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
        }
        
        .destiny-text-muted {
            color: rgba(199, 184, 138, 0.8);
        }
        
        /* Loading Animation */
        .loading-ring {
            border: 3px solid rgba(110, 193, 228, 0.3);
            border-top: 3px solid var(--destiny-cyan);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .starfield::before {
                animation-duration: 30s;
            }
            
            .destiny-glow {
                box-shadow: 
                    0 0 3px rgba(110, 193, 228, 0.3),
                    0 0 6px rgba(110, 193, 228, 0.2);
            }
        }
    </style>
</head>

<body class="font-sans antialiased min-h-screen" style="background-color: var(--destiny-bg-primary);">
    <!-- Animated Starfield Background -->
    <div class="starfield"></div>
    
    <div id="app" class="min-h-screen flex flex-col relative z-10">
        <!-- Navigation Header -->
        @auth
            <nav class="holo-panel border-b border-cyan-500/30 sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <!-- Logo/Brand -->
                        <div class="flex items-center space-x-4">
                            <div class="destiny-glow rounded-lg p-2">
                                <svg class="w-8 h-8 text-cyan-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z"/>
                                </svg>
                            </div>
                            <span class="destiny-heading text-xl font-bold">
                                @if(auth()->user()->esDocente())
                                    Torre de Instrucción
                                @else
                                    Centro del Guardián
                                @endif
                            </span>
                        </div>
                        
                        <!-- Navigation Links -->
                        <div class="hidden md:flex space-x-8">
                            @if(auth()->user()->esDocente())
                                <a href="{{ route('profesor.dashboard') }}" 
                                   class="nav-link {{ request()->routeIs('profesor.dashboard') ? 'active' : '' }}">
                                    Consola
                                </a>
                                <a href="{{ route('profesor.crear-clase.form') }}" 
                                   class="nav-link {{ request()->routeIs('profesor.crear-clase*') ? 'active' : '' }}">
                                    Nueva Operación
                                </a>
                                <a href="{{ route('profesor.estadisticas') }}" 
                                   class="nav-link {{ request()->routeIs('profesor.estadisticas') ? 'active' : '' }}">
                                    Reportes
                                </a>
                            @else
                                <a href="{{ route('estudiante.dashboard') }}" 
                                   class="nav-link {{ request()->routeIs('estudiante.dashboard') ? 'active' : '' }}">
                                    Operaciones
                                </a>
                                <a href="{{ route('estudiante.unirse-clase.form') }}" 
                                   class="nav-link {{ request()->routeIs('estudiante.unirse-clase*') ? 'active' : '' }}">
                                    Unirse a Fireteam
                                </a>
                                <a href="{{ route('estudiante.rankings') }}" 
                                   class="nav-link {{ request()->routeIs('estudiante.rankings') ? 'active' : '' }}">
                                    Rankings
                                </a>
                                <a href="{{ route('estudiante.logros') }}" 
                                   class="nav-link {{ request()->routeIs('estudiante.logros') ? 'active' : '' }}">
                                    Logros
                                </a>
                            @endif
                        </div>
                        
                        <!-- User Menu -->
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <button class="relative p-2 rounded-lg hover:bg-cyan-500/10 transition-colors destiny-glow">
                                <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M15 17h5l-5 5-5-5h5V3h5v14z"/>
                                </svg>
                                <span class="absolute -top-1 -right-1 bg-orange-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                    3
                                </span>
                            </button>
                            
                            <!-- User Avatar & Info -->
                            <div class="flex items-center space-x-3">
                                <div class="text-right hidden sm:block">
                                    <div class="destiny-text text-sm font-medium">
                                        {{ auth()->user()->nombreCompleto() }}
                                    </div>
                                    <div class="destiny-text-muted text-xs">
                                        @if(auth()->user()->esDocente())
                                            Instructor de la Torre
                                        @else
                                            Guardián
                                            @if($personajePrincipal ?? null)
                                                - Nivel {{ $personajePrincipal->nivel }}
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="relative">
                                    <img class="w-10 h-10 rounded-lg destiny-glow-gold border-2 border-gold-400/50" 
                                         src="{{ auth()->user()->avatar ?? '/images/avatars/default-guardian.jpg' }}" 
                                         alt="Avatar">
                                    @if(auth()->user()->esEstudiante() && ($personajePrincipal ?? null))
                                        <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full destiny-glow-purple flex items-center justify-center text-xs font-bold"
                                             style="background-color: var(--destiny-purple); color: white;">
                                            {{ $personajePrincipal->nivel }}
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Dropdown Menu -->
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" 
                                            class="p-1 rounded-lg hover:bg-cyan-500/10 transition-colors">
                                        <svg class="w-4 h-4 text-cyan-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                        </svg>
                                    </button>
                                    
                                    <div x-show="open" 
                                         @click.away="open = false"
                                         x-transition
                                         class="absolute right-0 mt-2 w-48 holo-panel rounded-lg shadow-lg py-1 z-50">
                                        @if(auth()->user()->esEstudiante())
                                            <a href="{{ route('estudiante.perfil') }}" 
                                               class="block px-4 py-2 text-sm destiny-text hover:bg-cyan-500/10">
                                                Mi Perfil
                                            </a>
                                        @endif
                                        <a href="#" class="block px-4 py-2 text-sm destiny-text hover:bg-cyan-500/10">
                                            Configuración
                                        </a>
                                        <div class="border-t border-cyan-500/30 my-1"></div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="block w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-red-500/10">
                                                Desconectar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mobile Menu Button -->
                        <div class="md:hidden">
                            <button x-data="{ open: false }" 
                                    @click="open = !open"
                                    class="p-2 rounded-lg hover:bg-cyan-500/10 transition-colors">
                                <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </nav>
        @endauth
        
        <!-- Flash Messages -->
        @if(session('success') || session('error') || session('warning') || session('info'))
            <div class="fixed top-20 right-4 z-50 space-y-2" id="flash-messages">
                @if(session('success'))
                    <div class="holo-panel rounded-lg p-4 border-l-4 border-green-500 destiny-glow max-w-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                            </svg>
                            <span class="destiny-text text-sm">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="holo-panel rounded-lg p-4 border-l-4 border-red-500 destiny-glow max-w-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                            </svg>
                            <span class="destiny-text text-sm">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif
            </div>
            
            <script>
                // Auto-hide flash messages after 5 seconds
                setTimeout(function() {
                    const flashMessages = document.getElementById('flash-messages');
                    if (flashMessages) {
                        flashMessages.style.opacity = '0';
                        flashMessages.style.transform = 'translateX(100%)';
                        setTimeout(() => flashMessages.remove(), 300);
                    }
                }, 5000);
            </script>
        @endif
        
        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>
        
        <!-- Footer -->
        <footer class="holo-panel border-t border-cyan-500/30 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    <div class="destiny-text-muted text-sm">
                        © {{ date('Y') }} ClassCraft Destiny - Sistema Educativo Gamificado
                    </div>
                    <div class="flex space-x-6 text-sm">
                        <a href="#" class="destiny-text-muted hover:text-cyan-400 transition-colors">Soporte</a>
                        <a href="#" class="destiny-text-muted hover:text-cyan-400 transition-colors">Documentación</a>
                        <a href="#" class="destiny-text-muted hover:text-cyan-400 transition-colors">Estado del Sistema</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Alpine.js for interactivity -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- Custom Scripts -->
    @stack('scripts')
</body>
</html>