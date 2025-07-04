<?php

// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Clase;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->esDocente()) {
            return $this->dashboardProfesor($user);
        }
        
        if ($user->esEstudiante()) {
            return $this->dashboardEstudiante($user);
        }
        
        return $this->dashboardBasico($user);
    }
    
    private function dashboardProfesor($user)
    {
        // USAR QUERY DIRECTA PARA EVITAR PROBLEMAS DE RELACIONES
        $clases = DB::table('clase')
            ->where('id_docente', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($clase) {
                // Contar estudiantes de forma directa
                $totalEstudiantes = DB::table('inscripcion_clase')
                    ->where('id_clase', $clase->id)
                    ->where('activo', true)
                    ->count();

                return [
                    'id' => $clase->id,
                    'nombre' => $clase->nombre,
                    'descripcion' => $clase->descripcion,
                    'codigo' => $clase->codigo ?? 'SIN_CODIGO',
                    'fecha_inicio' => $clase->fecha_inicio ?? date('Y-m-d'),
                    'fecha_fin' => $clase->fecha_fin ?? date('Y-m-d', strtotime('+1 month')),
                    'total_estudiantes' => $totalEstudiantes,
                    'activa' => $clase->activa ?? true,
                    'progreso' => [
                        'completadas' => 0,
                        'total_actividades' => 0,
                        'porcentaje' => 0,
                        'dias_restantes' => 30
                    ]
                ];
            });

        $totalEstudiantes = DB::table('inscripcion_clase')
            ->join('clase', 'inscripcion_clase.id_clase', '=', 'clase.id')
            ->where('clase.id_docente', $user->id)
            ->where('inscripcion_clase.activo', true)
            ->count();

        $estadisticas = [
            'total_clases' => $clases->count(),
            'clases_activas' => $clases->where('activa', true)->count(),
            'total_estudiantes' => $totalEstudiantes,
            'promedio_estudiantes' => $clases->count() > 0 ? round($totalEstudiantes / $clases->count(), 1) : 0
        ];

        return Inertia::render('Dashboard/Profesor', [
            'clases' => $clases,
            'estadisticas' => $estadisticas,
            'profesor' => [
                'nombre' => $user->nombre . ' ' . $user->apellido,
                'correo' => $user->correo
            ]
        ]);
    }
    
    private function dashboardEstudiante($user)
    {
        $clases = DB::table('inscripcion_clase')
            ->join('clase', 'inscripcion_clase.id_clase', '=', 'clase.id')
            ->join('usuarios as docentes', 'clase.id_docente', '=', 'docentes.id')
            ->where('inscripcion_clase.id_estudiante', $user->id)
            ->where('inscripcion_clase.activo', true)
            ->select([
                'clase.id',
                'clase.nombre',
                'clase.descripcion',
                'clase.codigo',
                'clase.fecha_inicio',
                'clase.fecha_fin',
                'clase.activa',
                'docentes.nombre as docente_nombre',
                'docentes.apellido as docente_apellido'
            ])
            ->get()
            ->map(function($clase) {
                return [
                    'id' => $clase->id,
                    'nombre' => $clase->nombre,
                    'descripcion' => $clase->descripcion,
                    'codigo' => $clase->codigo,
                    'docente' => $clase->docente_nombre . ' ' . $clase->docente_apellido,
                    'fecha_inicio' => $clase->fecha_inicio,
                    'fecha_fin' => $clase->fecha_fin,
                    'progreso' => 0,
                    'activa' => $clase->activa
                ];
            });

        $estadisticas = [
            'total_clases' => $clases->count(),
            'clases_activas' => $clases->where('activa', true)->count(),
            'actividades_completadas' => 0,
            'xp_total' => 0
        ];

        return Inertia::render('Dashboard/Estudiante', [
            'clases' => $clases,
            'estadisticas' => $estadisticas,
            'estudiante' => [
                'nombre' => $user->nombre . ' ' . $user->apellido,
                'correo' => $user->correo,
                'codigo' => 'N/A'
            ]
        ]);
    }
    
    private function dashboardBasico($user)
    {
        return Inertia::render('Dashboard/Basico', [
            'mensaje' => 'Bienvenido al sistema. Tu perfil está en configuración.',
            'usuario' => [
                'nombre' => $user->nombre . ' ' . $user->apellido,
                'correo' => $user->correo,
                'tipo' => 'indefinido'
            ]
        ]);
    }
}
