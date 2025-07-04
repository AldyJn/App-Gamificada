<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Dashboard principal - Sin redirecciones, renderiza directo
     */
    public function index()
    {
        // Cargar el usuario con su relación tipoUsuario
        $user = auth()->user()->load('tipoUsuario');
        
        // Si es docente, mostrar dashboard de profesor
        if ($user->esDocente()) {
            return $this->dashboardProfesor($user);
        }
        
        // Si es estudiante, mostrar dashboard de estudiante
        if ($user->esEstudiante()) {
            return $this->dashboardEstudiante($user);
        }
        
        // Si no tiene tipo definido, mostrar dashboard básico
        return $this->dashboardBasico($user);
    }
    
    /**
     * Dashboard específico para profesores
     */
    private function dashboardProfesor($user)
    {
        // Obtener clases del profesor
        $clases = Clase::where('id_docente', $user->id)
            ->withCount(['estudiantes as total_estudiantes' => function($query) {
                // Aquí asumo que tienes una tabla pivot o relación
                // Ajusta según tu estructura real
            }])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($clase) {
                return [
                    'id' => $clase->id,
                    'nombre' => $clase->nombre,
                    'descripcion' => $clase->descripcion,
                    'codigo' => $clase->codigo ?? 'SIN_CODIGO',
                    'fecha_inicio' => $clase->fecha_inicio ? $clase->fecha_inicio->format('Y-m-d') : date('Y-m-d'),
                    'fecha_fin' => $clase->fecha_fin ? $clase->fecha_fin->format('Y-m-d') : date('Y-m-d', strtotime('+1 month')),
                    'total_estudiantes' => 0, // Temporal hasta que configures la relación
                    'activa' => true,
                    'progreso' => [
                        'completadas' => 0,
                        'total_actividades' => 0,
                        'porcentaje' => 0,
                        'dias_restantes' => 30
                    ],
                    'puede_seleccionar' => false // Temporal
                ];
            });

        // Estadísticas del profesor
        $estadisticas = [
            'total_clases' => $clases->count(),
            'clases_activas' => $clases->where('activa', true)->count(),
            'total_estudiantes' => 0, // Temporal
            'promedio_estudiantes' => 0 // Temporal
        ];

        return Inertia::render('Dashboard/Profesor', [
            'clases' => $clases,
            'estadisticas' => $estadisticas,
            'profesor' => [
                'nombre' => $user->nombre,
                'correo' => $user->correo
            ]
        ]);
    }
    
    /**
     * Dashboard específico para estudiantes
     */
    private function dashboardEstudiante($user)
    {
        // Por ahora, dashboard básico para estudiantes
        return Inertia::render('Dashboard/Estudiante', [
            'mensaje' => 'Dashboard de Estudiante - En construcción',
            'estudiante' => [
                'nombre' => $user->nombre,
                'correo' => $user->correo
            ]
        ]);
    }
    
    /**
     * Dashboard básico si no tiene tipo definido
     */
    private function dashboardBasico($user)
    {
        return Inertia::render('Dashboard/Basico', [
            'mensaje' => 'Bienvenido al sistema. Tu perfil está en configuración.',
            'usuario' => [
                'nombre' => $user->nombre,
                'correo' => $user->correo,
                'tipo' => 'indefinido'
            ]
        ]);
    }
}