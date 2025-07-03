<?php
// app/Providers/AuthServiceProvider.php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\{Usuario, Clase, Personaje, Estudiante, Docente};
use App\Policies\{ClasePolicy, PersonajePolicy, EstudiantePolicy, DocentePolicy};

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Clase::class => ClasePolicy::class,
        Personaje::class => PersonajePolicy::class,
        Estudiante::class => EstudiantePolicy::class,
        Docente::class => DocentePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gates personalizados para el sistema gamificado
        Gate::define('gestionar-clase', function (Usuario $usuario, Clase $clase) {
            return $usuario->esDocente() && $clase->id_docente === $usuario->docente->id;
        });

        Gate::define('acceder-clase', function (Usuario $usuario, Clase $clase) {
            if ($usuario->esDocente()) {
                return $clase->id_docente === $usuario->docente->id;
            }
            
            if ($usuario->esEstudiante()) {
                return $clase->estudiantes()
                    ->where('estudiante.id', $usuario->estudiante->id)
                    ->where('inscripcion_clase.activo', true)
                    ->exists();
            }
            
            return false;
        });

        Gate::define('administrar-personaje', function (Usuario $usuario, Personaje $personaje) {
            if ($usuario->esEstudiante()) {
                return $personaje->id_estudiante === $usuario->estudiante->id;
            }
            
            if ($usuario->esDocente()) {
                return $personaje->clase->id_docente === $usuario->docente->id;
            }
            
            return false;
        });

        Gate::define('aplicar-comportamiento', function (Usuario $usuario, Clase $clase) {
            return $usuario->esDocente() && $clase->id_docente === $usuario->docente->id;
        });

        Gate::define('usar-habilidad', function (Usuario $usuario, Personaje $personaje) {
            return $usuario->esEstudiante() && 
                   $personaje->id_estudiante === $usuario->estudiante->id &&
                   $personaje->salud_actual > 0; // Solo si no está caído
        });

        Gate::define('acceder-accesorios-premium', function (Usuario $usuario, Personaje $personaje) {
            return $usuario->esEstudiante() && 
                   $personaje->id_estudiante === $usuario->estudiante->id &&
                   $personaje->nivel >= 18; // Requisito para accesorios premium
        });

        // Super Admin Gate (para futuras funciones administrativas)
        Gate::define('super-admin', function (Usuario $usuario) {
            return $usuario->correo === 'admin@classcraft-destiny.com';
        });
    }
}