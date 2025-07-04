<?php
// app/Http/Middleware/ClaseAccessMiddleware.php

// app/Http/Middleware/ClaseAccessMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        if ($usuario->esDocente()) {
            if ($clase->id_docente !== $usuario->id) {
                abort(403, 'NO TIENES ACCESO A ESTA CLASE');
            }
        } elseif ($usuario->esEstudiante()) {
            $estaInscrito = DB::table('inscripcion_clase')
                ->where('id_clase', $clase->id)
                ->where('id_estudiante', $usuario->id)  // COLUMNA CORRECTA
                ->where('activo', true)
                ->exists();
                
            if (!$estaInscrito) {
                abort(403, 'NO ESTÁS INSCRITO EN ESTA CLASE');
            }
        } else {
            abort(403, 'TIPO DE USUARIO NO AUTORIZADO');
        }

        return $next($request);
    }
}