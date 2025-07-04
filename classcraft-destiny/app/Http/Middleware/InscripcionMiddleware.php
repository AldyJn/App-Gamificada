<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InscripcionMiddleware
{
    /**
     * Middleware para validar acceso a funciones de inscripción
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth()->user();

        // Si no está autenticado, redirigir a login
        if (!$user) {
            return redirect()->route('login');
        }

        // Verificar roles específicos
        foreach ($roles as $role) {
            switch ($role) {
                case 'docente':
                    if (!$user->esDocente()) {
                        abort(403, 'Acceso denegado. Solo para docentes.');
                    }
                    break;
                    
                case 'estudiante':
                    if (!$user->esEstudiante()) {
                        abort(403, 'Acceso denegado. Solo para estudiantes.');
                    }
                    break;
                    
                case 'estudiante_activo':
                    if (!$user->esEstudiante() || !$this->estudianteEstaActivo($user)) {
                        abort(403, 'Acceso denegado. Estudiante no activo.');
                    }
                    break;
                    
                case 'clase_activa':
                    if (!$this->tieneClaseActiva($user)) {
                        return redirect()->route('estudiante.unirse')
                            ->with('info', 'Necesitas unirte a una clase primero.');
                    }
                    break;
            }
        }

        return $next($request);
    }

    /**
     * Verificar si el estudiante está activo
     */
    private function estudianteEstaActivo($user): bool
    {
        if (!$user->esEstudiante()) {
            return false;
        }

        return $user->id_estado === 1; // 1 = activo
    }

    /**
     * Verificar si el usuario tiene al menos una clase activa
     */
    private function tieneClaseActiva($user): bool
    {
        if (!$user->esEstudiante()) {
            return false;
        }

        return $user->clasesComoEstudiante()
                   ->wherePivot('activo', true)
                   ->exists();
    }
}