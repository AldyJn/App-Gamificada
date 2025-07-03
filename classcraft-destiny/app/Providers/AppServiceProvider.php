<?php
// app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registrar servicios específicos del sistema gamificado
        $this->app->singleton('guardian.experience', function ($app) {
            return new \App\Services\ExperienceService();
        });

        $this->app->singleton('guardian.notifications', function ($app) {
            return new \App\Services\NotificationService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurar esquema de base de datos para PostgreSQL
        Schema::defaultStringLength(191);
        
        // Compartir datos globales con todas las vistas
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $usuario = Auth::user();
                
                $view->with([
                    'usuarioActual' => $usuario,
                    'esProfesor' => $usuario->esDocente(),
                    'esEstudiante' => $usuario->esEstudiante(),
                    'configuracionUI' => $usuario->configuracion_ui ?? [],
                    'notificacionesPendientes' => $this->obtenerNotificacionesPendientes($usuario),
                ]);
                
                // Datos específicos para estudiantes
                if ($usuario->esEstudiante()) {
                    $personajePrincipal = $usuario->estudiante->personajePrincipal();
                    $view->with([
                        'personajePrincipal' => $personajePrincipal,
                        'nivelGuardian' => $personajePrincipal?->nivel ?? 1,
                        'experienciaActual' => $personajePrincipal?->experiencia_actual ?? 0,
                    ]);
                }
                
                // Datos específicos para profesores
                if ($usuario->esDocente()) {
                    $view->with([
                        'clasesActivas' => $usuario->docente->clasesActivas()->count(),
                        'totalEstudiantes' => $usuario->docente->totalEstudiantes(),
                    ]);
                }
            }
        });

        // Configurar timezone por defecto
        date_default_timezone_set(config('app.timezone', 'America/Lima'));
    }

    /**
     * Obtener notificaciones pendientes del usuario
     */
    private function obtenerNotificacionesPendientes(Usuario $usuario): int
    {
        // TODO: Implementar cuando tengas sistema de notificaciones
        return 0;
    }
}