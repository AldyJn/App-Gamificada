<?php

// ==========================================
// MIDDLEWARE: CheckUserType.php
// ==========================================

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserType
{
    public function handle(Request $request, Closure $next, $tipo)
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();
        
        // Verificar si el usuario tiene el tipo correcto
        if ($tipo === 'docente' && !$user->esDocente()) {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos de docente');
        }
        
        if ($tipo === 'estudiante' && !$user->esEstudiante()) {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos de estudiante');
        }

        return $next($request);
    }
}
