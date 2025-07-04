<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Usuario;
use App\Models\Actividad;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Verificar si es docente
        if ($user->esDocente()) {
            return $this->dashboardDocente($user);
        }
        
        // Verificar si es estudiante
        if ($user->esEstudiante()) {
            return $this->dashboardEstudiante($user);
        }
        
        // ✅ CORREGIDO: Si no tiene tipo definido, redirigir a perfil existente
        return redirect()->route('perfil');
    }
    
    private function dashboardDocente($user)
    {
        // ✅ CORREGIDO: Usar relación correcta y campos de la migración
        $clases = Clase::whereHas('docente', function($query) use ($user) {
            $query->where('id_usuario', $user->id);
        })
        ->with(['inscripciones.estudiante.usuario'])
        ->orderBy('created_at', 'desc')
        ->get();
        
        // Calcular estadísticas
        $totalClases = $clases->count();
        $totalEstudiantes = $clases->sum(function($clase) {
            return $clase->inscripciones->count();
        });
        
        $clasesActivas = $clases->filter(function($clase) {
            return $clase->fecha_fin >= now() && $clase->fecha_inicio <= now();
        })->count();
        
        // ✅ CORREGIDO: Actividades relacionadas correctamente
        $actividadesRecientes = Actividad::whereHas('clase.docente', function($query) use ($user) {
            $query->where('id_usuario', $user->id);
        })->orderBy('created_at', 'desc')
        ->take(5)
        ->get();
        
        return Inertia::render('Dashboard/Docente', [
            'clases' => $clases,
            'estadisticas' => [
                'total_clases' => $totalClases,
                'clases_activas' => $clasesActivas,
                'total_estudiantes' => $totalEstudiantes,
            ],
            'actividades_recientes' => $actividadesRecientes
        ]);
    }
    
    private function dashboardEstudiante($user)
    {
        // ✅ CORREGIDO: Verificar si tiene perfil de estudiante
        if (!$user->estudiante) {
            return Inertia::render('Dashboard/ConfigurarPerfil', [
                'usuario' => $user,
                'mensaje' => 'Necesitas completar tu perfil de estudiante'
            ]);
        }

        // ✅ CORREGIDO: Obtener clases usando relaciones correctas
        $clases = $user->estudiante->clases()
            ->with(['docente.usuario'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calcular estadísticas
        $totalClases = $clases->count();
        $clasesActivas = $clases->filter(function($clase) {
            return $clase->fecha_fin >= now() && $clase->fecha_inicio <= now();
        })->count();
        
        // ✅ CORREGIDO: Actividades pendientes usando relaciones correctas
        $actividadesPendientes = Actividad::whereHas('clase.inscripciones', function($query) use ($user) {
            $query->where('id_estudiante', $user->estudiante->id)
                  ->where('activo', true);
        })->where('fecha_limite', '>', now())
        ->orderBy('fecha_limite', 'asc')
        ->take(5)
        ->get();
        
        return Inertia::render('Dashboard/Estudiante', [
            'clases' => $clases,
            'estudiante' => $user->estudiante,
            'estadisticas' => [
                'total_clases' => $totalClases,
                'clases_activas' => $clasesActivas,
                'actividades_pendientes' => $actividadesPendientes->count()
            ],
            'actividades_pendientes' => $actividadesPendientes
        ]);
    }

    /**
     * ✅ AGREGADO: Método para configurar perfil si no existe
     */
    public function configurarPerfil()
    {
        $user = auth()->user();
        
        return Inertia::render('Dashboard/ConfigurarPerfil', [
            'usuario' => $user,
            'tipos_usuario' => \App\Models\TipoUsuario::all()
        ]);
    }

    /**
     * ✅ AGREGADO: Stats para API calls (si se necesitan)
     */
    public function stats()
    {
        $user = auth()->user();
        
        if ($user->esDocente()) {
            $clases = Clase::whereHas('docente', function($query) use ($user) {
                $query->where('id_usuario', $user->id);
            })->count();
            
            return response()->json([
                'total_clases' => $clases,
                'tipo_usuario' => 'docente'
            ]);
        }
        
        if ($user->esEstudiante()) {
            $clases = $user->estudiante ? $user->estudiante->clases()->count() : 0;
            
            return response()->json([
                'total_clases' => $clases,
                'tipo_usuario' => 'estudiante'
            ]);
        }
        
        return response()->json([
            'total_clases' => 0,
            'tipo_usuario' => 'sin_definir'
        ]);
    }
}