<?php

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
        // Configurar esquema de base de datos
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
                if ($usuario->esEstudiante() && $usuario->estudiante) {
                    // Solo cargar si existe la relación estudiante
                    try {
                        $view->with([
                            'nivelGuardian' => 1, // Valor por defecto
                            'experienciaActual' => 0, // Valor por defecto
                        ]);
                    } catch (\Exception $e) {
                        // Ignorar errores de carga de datos de estudiante
                    }
                }
                
                // Datos específicos para profesores - CORREGIDO
                if ($usuario->esDocente() && $usuario->docente) {
                    try {
                        $view->with([
                            'clasesActivas' => $usuario->docente->totalClasesActivas(),
                            'totalEstudiantes' => $usuario->docente->totalEstudiantes(),
                        ]);
                    } catch (\Exception $e) {
                        // Si hay error, usar valores por defecto
                        $view->with([
                            'clasesActivas' => 0,
                            'totalEstudiantes' => 0,
                        ]);
                    }
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