<?php
// app/Http/Middleware/ClaseAccessMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Clase;

class ClaseAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $clase = $request->route('clase');
        
        if (!$clase instanceof Clase) {
            abort(404, 'Clase no encontrada.');
        }

        $usuario = Auth::user();

        // Verificar acceso según el tipo de usuario
        if ($usuario->esDocente()) {
            if ($clase->id_docente !== $usuario->docente->id) {
                abort(403, 'No tienes permisos para gestionar esta clase.');
            }
        } elseif ($usuario->esEstudiante()) {
            if (!$clase->estudiantes()->where('estudiante.id', $usuario->estudiante->id)->exists()) {
                abort(403, 'No estás inscrito en esta clase.');
            }
        } else {
            abort(403, 'Tipo de usuario no autorizado.');
        }

        return $next($request);
    }
}