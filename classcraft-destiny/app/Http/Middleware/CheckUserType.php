<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $tipoRequerido): Response
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->with('warning', 'Debes iniciar sesión para acceder.');
        }

        $usuario = Auth::user();

        // Verificar el tipo de usuario requerido
        switch ($tipoRequerido) {
            case 'docente':
                if (!$usuario->esDocente()) {
                    return redirect()->route('dashboard')
                        ->with('error', 'Esta sección es solo para docentes.');
                }
                break;

            case 'estudiante':
                if (!$usuario->esEstudiante()) {
                    return redirect()->route('dashboard')
                        ->with('error', 'Esta sección es solo para estudiantes.');
                }
                break;

            case 'admin':
            case 'administrador':
                // Para futura implementación de administradores
                return redirect()->route('dashboard')
                    ->with('error', 'Funcionalidad de administrador en desarrollo.');
                break;

            default:
                return redirect()->route('dashboard')
                    ->with('error', 'Tipo de usuario no válido.');
        }

        return $next($request);
    }
}