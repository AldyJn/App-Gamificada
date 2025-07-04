<?php
// bootstrap/app.php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware globales
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        // Alias de middleware para rutas
        $middleware->alias([
            // ==========================================
            // MIDDLEWARES PERSONALIZADOS - SISTEMA EDUCATIVO
            // ==========================================
            
            // Control de tipos de usuario
            'user.type' => \App\Http\Middleware\CheckUserType::class,
            
            // Control de acceso a clases
            'clase.access' => \App\Http\Middleware\CheckClaseAccess::class,
            
            // ==========================================
            // MIDDLEWARES ESPECÍFICOS POR ROL
            // ==========================================
            
            // Middleware específico para docentes
            'docente' => \App\Http\Middleware\CheckUserType::class.':docente',
            
            // Middleware específico para estudiantes  
            'estudiante' => \App\Http\Middleware\CheckUserType::class.':estudiante',
            
            // ==========================================
            // MIDDLEWARES EXISTENTES
            // ==========================================
            'profesor' => \App\Http\Middleware\ProfesorMiddleware::class,
            'estudiante.old' => \App\Http\Middleware\EstudianteMiddleware::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'auth.guardian' => \App\Http\Middleware\AuthGuardianMiddleware::class,
        ]);

        // Middleware por grupos
        $middleware->group('web', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->group('api', [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Manejo personalizado de excepciones para la aplicación gamificada
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debes iniciar sesión para acceder como Guardián.',
                    'redirect' => route('login')
                ], 401);
            }
            
            return redirect()->guest(route('login'))
                           ->with('warning', 'Debes iniciar sesión para acceder a la Torre.');
        });

        $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para realizar esta acción.',
                ], 403);
            }
            
            return abort(403, 'No tienes permisos para acceder a esta sección de la Torre.');
        });
    })
    ->create();