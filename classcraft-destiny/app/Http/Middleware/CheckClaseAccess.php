<?php
// ==========================================
// MIDDLEWARE: CheckClaseAccess.php
// ==========================================


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Clase;

class CheckClaseAccess
{
    public function handle(Request $request, Closure $next)
    {
        $claseId = $request->route('clase');
        
        if (!$claseId) {
            return redirect()->route('dashboard')->with('error', 'Clase no encontrada');
        }

        $clase = Clase::find($claseId);
        
        if (!$clase) {
            return redirect()->route('dashboard')->with('error', 'Clase no encontrada');
        }

        $user = auth()->user();
        
        // Si es docente, verificar que sea el propietario
        if ($user->esDocente()) {
            if ($clase->docente_id !== $user->id) {
                return redirect()->route('dashboard')->with('error', 'No tienes acceso a esta clase');
            }
        }
        
        // Si es estudiante, verificar que esté inscrito
        if ($user->esEstudiante()) {
            if (!$clase->estudiantes()->where('usuario_id', $user->id)->exists()) {
                return redirect()->route('dashboard')->with('error', 'No estás inscrito en esta clase');
            }
        }

        return $next($request);
    }
}