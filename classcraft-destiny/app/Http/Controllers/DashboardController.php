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
        
        // Si no tiene tipo definido, redirigir a configuración
        return redirect()->route('configurar-perfil');
    }
    
    private function dashboardDocente($user)
    {
        // Obtener clases del docente
        $clases = Clase::where('docente_id', $user->id)
            ->with(['estudiantes'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calcular estadísticas
        $totalClases = $clases->count();
        $totalEstudiantes = $clases->sum(function($clase) {
            return $clase->estudiantes->count();
        });
        
        $clasesActivas = $clases->filter(function($clase) {
            return $clase->fecha_fin >= now() && $clase->fecha_inicio <= now();
        })->count();
        
        // Actividades recientes
        $actividadesRecientes = Actividad::whereHas('clase', function($query) use ($user) {
            $query->where('docente_id', $user->id);
        })->orderBy('created_at', 'desc')
        ->take(5)
        ->get();
        
        return Inertia::render('Dashboard/Docente', [
            'clases' => $clases,
            'estadisticas' => [
                'total_clases' => $totalClases,
                'clases_activas' => $clasesActivas,
                'total_estudiantes' => $totalEstudiantes,
                'actividades_recientes' => $actividadesRecientes
            ]
        ]);
    }
    
    private function dashboardEstudiante($user)
    {
        // Obtener clases del estudiante
        $clases = $user->clases()
            ->with(['docente', 'actividades'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calcular estadísticas
        $totalClases = $clases->count();
        $clasesActivas = $clases->filter(function($clase) {
            return $clase->fecha_fin >= now() && $clase->fecha_inicio <= now();
        })->count();
        
        // Actividades pendientes
        $actividadesPendientes = Actividad::whereHas('clase', function($query) use ($user) {
            $query->whereHas('estudiantes', function($subquery) use ($user) {
                $subquery->where('usuario_id', $user->id);
            });
        })->where('fecha_limite', '>', now())
        ->orderBy('fecha_limite', 'asc')
        ->take(5)
        ->get();
        
        return Inertia::render('Dashboard/Estudiante', [
            'clases' => $clases,
            'estadisticas' => [
                'total_clases' => $totalClases,
                'clases_activas' => $clasesActivas,
                'actividades_pendientes' => $actividadesPendientes->count()
            ],
            'actividades_pendientes' => $actividadesPendientes
        ]);
    }
}