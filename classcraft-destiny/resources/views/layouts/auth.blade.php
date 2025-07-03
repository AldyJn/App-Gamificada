<!DOCTYPE html>
{{-- resources/views/layouts/auth.blade.php --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'ClassCraft Destiny') }} - @yield('title', 'Acceso')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Destiny Authentication Theme CSS -->
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
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: var(--font-body);
            background-color: var(--destiny-bg-primary);
            color: white;
            overflow-x: hidden;
        }
        
        /* Scrollbar Styles */
        * {
            scrollbar-width: thin;
            scrollbar-color: var(--destiny-cyan) var(--destiny-bg-secondary);
        }
        
        *::-webkit-scrollbar {
            width: 6px;
        }
        
        *::-webkit-scrollbar-track {
            background: var(--destiny-bg-secondary);
        }
        
        *::-webkit-scrollbar-thumb {
            background: var(--destiny-cyan);
            border-radius: 3px;
        }
        
        /* Destiny Glow Effects */
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
            box-shadow: 
                0 0 20px rgba(110, 193, 228, 0.4),
                0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .destiny-btn:hover::before {
            left: 100%;
        }
        
        .destiny-btn:active {
            transform: translateY(0);
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
        
        /* Form Input Styles */
        input[type="email"],
        input[type="password"],
        input[type="text"] {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(110, 193, 228, 0.3);
            color: white;
            font-family: var(--font-body);
        }
        
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="text"]:focus {
            border-color: var(--destiny-cyan);
            box-shadow: 
                0 0 0 1px rgba(110, 193, 228, 0.5),
                0 0 20px rgba(110, 193, 228, 0.2);
            outline: none;
        }
        
        input[type="email"]::placeholder,
        input[type="password"]::placeholder,
        input[type="text"]::placeholder {
            color: rgba(199, 184, 138, 0.6);
        }
        
        /* Checkbox Styles */
        input[type="checkbox"] {
            appearance: none;
            width: 16px;
            height: 16px;
            border: 1px solid rgba(110, 193, 228, 0.5);
            border-radius: 3px;
            background: rgba(0, 0, 0, 0.2);
            position: relative;
            cursor: pointer;
        }
        
        input[type="checkbox"]:checked {
            background: var(--destiny-cyan);
            border-color: var(--destiny-cyan);
        }
        
        input[type="checkbox"]:checked::before {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .destiny-glow {
                box-shadow: 
                    0 0 3px rgba(110, 193, 228, 0.3),
                    0 0 6px rgba(110, 193, 228, 0.2);
            }
            
            .holo-panel {
                backdrop-filter: blur(5px);
            }
        }
        
        /* Page Transition Effects */
        .page-enter {
            animation: pageEnter 0.8s ease-out;
        }
        
        @keyframes pageEnter {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        /* Error Message Styles */
        .error-message {
            color: var(--destiny-danger);
            font-size: 0.75rem;
            font-family: var(--font-body);
            margin-top: 0.25rem;
        }
        
        /* Success Message Styles */
        .success-message {
            color: var(--destiny-success);
            font-size: 0.875rem;
            font-family: var(--font-body);
        }
        
        /* Custom Link Styles */
        a {
            color: var(--destiny-cyan);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        a:hover {
            color: white;
            text-shadow: 0 0 5px rgba(110, 193, 228, 0.7);
        }
    </style>
</head>

<body class="page-enter">
    <div id="app" class="min-h-screen">
        <!-- Background Effects -->
        <div class="fixed inset-0 z-0">
            <!-- Base gradient -->
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-blue-900/20 to-purple-900/20"></div>
            
            <!-- Animated grid overlay -->
            <div class="absolute inset-0 opacity-20">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="50" height="50" patternUnits="userSpaceOnUse">
                            <path d="M 50 0 L 0 0 0 50" fill="none" stroke="rgba(110, 193, 228, 0.3)" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>
        </div>
        
        <!-- Flash Messages -->
        @if(session('success') || session('error') || session('warning') || session('info'))
            <div class="fixed top-4 right-4 z-50 space-y-2" id="flash-messages">
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
                
                @if(session('warning'))
                    <div class="holo-panel rounded-lg p-4 border-l-4 border-yellow-500 destiny-glow max-w-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                            </svg>
                            <span class="destiny-text text-sm">{{ session('warning') }}</span>
                        </div>
                    </div>
                @endif
                
                @if(session('info'))
                    <div class="holo-panel rounded-lg p-4 border-l-4 border-blue-500 destiny-glow max-w-sm">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                            </svg>
                            <span class="destiny-text text-sm">{{ session('info') }}</span>
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
        <main class="relative z-10">
            @yield('content')
        </main>
        
        <!-- Footer (Optional) -->
        <footer class="relative z-10 mt-auto">
            <div class="text-center py-6">
                <p class="destiny-text-muted text-xs">
                    © {{ date('Y') }} ClassCraft Destiny - Sistema de Entrenamiento Avanzado
                </p>
            </div>
        </footer>
    </div>
    
    <!-- Alpine.js for interactivity -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- Custom Scripts -->
    @stack('scripts')
    
    <!-- Page Load Animation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add entrance animation to main content
            const mainContent = document.querySelector('main');
            if (mainContent) {
                mainContent.style.animation = 'pageEnter 0.8s ease-out';
            }
            
            // Initialize any dynamic elements
            initializeAuthPage();
        });
        
        function initializeAuthPage() {
            // Add keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    // Close any open dropdowns or modals
                    const openDropdowns = document.querySelectorAll('[x-data]');
                    openDropdowns.forEach(dropdown => {
                        if (dropdown.__x) {
                            dropdown.__x.$data.open = false;
                        }
                    });
                }
            });
            
            // Add focus management for better accessibility
            const inputs = document.querySelectorAll('input');
            inputs.forEach((input, index) => {
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' && input.type !== 'submit') {
                        e.preventDefault();
                        const nextInput = inputs[index + 1];
                        if (nextInput) {
                            nextInput.focus();
                        } else {
                            // Submit form if it's the last input
                            const form = input.closest('form');
                            if (form) {
                                const submitButton = form.querySelector('button[type="submit"]');
                                if (submitButton) {
                                    submitButton.click();
                                }
                            }
                        }
                    }
                });
            });
            
            // Add visual feedback for form interactions
            const formElements = document.querySelectorAll('input, button');
            formElements.forEach(element => {
                element.addEventListener('focus', function() {
                    this.classList.add('destiny-glow');
                });
                
                element.addEventListener('blur', function() {
                    this.classList.remove('destiny-glow');
                });
            });
        }
        
        // Add loading state to forms
        function addLoadingState(form) {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.classList.add('btn-loading');
                submitButton.disabled = true;
                
                // Create loading spinner
                const spinner = document.createElement('div');
                spinner.className = 'loading-ring w-4 h-4 mx-auto';
                submitButton.innerHTML = '';
                submitButton.appendChild(spinner);
            }
        }
        
        // Remove loading state from forms
        function removeLoadingState(form) {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.classList.remove('btn-loading');
                submitButton.disabled = false;
            }
        }
        
        // Handle form submissions globally
        document.addEventListener('submit', function(e) {
            addLoadingState(e.target);
        });
        
        // Handle failed form submissions (validation errors)
        @if($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.querySelector('form');
                if (form) {
                    removeLoadingState(form);
                }
                
                // Focus first input with error
                const firstError = document.querySelector('.error-message');
                if (firstError) {
                    const input = firstError.closest('.space-y-2')?.querySelector('input');
                    if (input) {
                        input.focus();
                        input.select();
                    }
                }
            });
        @endif
    </script>
</body>
</html>