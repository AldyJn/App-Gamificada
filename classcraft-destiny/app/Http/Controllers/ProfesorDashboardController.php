<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ProfesorDashboardController extends Controller
{
    /**
     * Dashboard principal del profesor
     */
    public function index()
    {
        $profesor = Auth::user();
        
        // Verificar que sea docente
        if (!$profesor->esDocente()) {
            abort(403, 'Acceso denegado');
        }

        // Obtener clases del profesor con estudiantes
        $clases = Clase::where('docente_id', $profesor->id)
            ->with(['estudiantes' => function($query) {
                $query->wherePivot('activo', true);
            }])
            ->withCount(['estudiantes as total_estudiantes' => function($query) {
                $query->wherePivot('activo', true);
            }])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($clase) {
                return [
                    'id' => $clase->id,
                    'nombre' => $clase->nombre,
                    'descripcion' => $clase->descripcion,
                    'codigo' => $clase->codigo,
                    'fecha_inicio' => $clase->fecha_inicio->format('Y-m-d'),
                    'fecha_fin' => $clase->fecha_fin->format('Y-m-d'),
                    'total_estudiantes' => $clase->total_estudiantes,
                    'activa' => $clase->estaActiva(),
                    'progreso' => $clase->obtenerProgreso(),
                    'puede_seleccionar' => $clase->total_estudiantes > 0
                ];
            });

        // Estadísticas del profesor
        $estadisticas = [
            'total_clases' => $clases->count(),
            'clases_activas' => $clases->where('activa', true)->count(),
            'total_estudiantes' => $clases->sum('total_estudiantes'),
            'promedio_estudiantes' => $clases->count() > 0 
                ? round($clases->sum('total_estudiantes') / $clases->count(), 1) 
                : 0
        ];

        return Inertia::render('Dashboard/Profesor', [
            'clases' => $clases,
            'estadisticas' => $estadisticas,
            'profesor' => [
                'nombre' => $profesor->nombre,
                'correo' => $profesor->correo
            ]
        ]);
    }

    /**
     * Crear nueva clase
     */
    public function crearClase(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'grado' => 'nullable|string|max:50',
            'seccion' => 'nullable|string|max:10'
        ]);

        $codigo = Clase::generarCodigoUnico();

        $clase = Clase::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'codigo' => $codigo,
            'docente_id' => Auth::id(),
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'grado' => $request->grado,
            'seccion' => $request->seccion,
            'activa' => true,
            'permitir_inscripcion' => true,
            'configuracion_gamificacion' => [
                'multiplicador_xp' => 1.0,
                'modo_competitivo' => false,
                'sistema_puntos' => true
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Clase creada exitosamente',
            'clase' => [
                'id' => $clase->id,
                'nombre' => $clase->nombre,
                'codigo' => $clase->codigo,
                'fecha_inicio' => $clase->fecha_inicio->format('Y-m-d'),
                'fecha_fin' => $clase->fecha_fin->format('Y-m-d'),
                'total_estudiantes' => 0,
                'activa' => true,
                'progreso' => $clase->obtenerProgreso(),
                'puede_seleccionar' => false
            ]
        ]);
    }

    /**
     * Ver detalles de una clase específica
     */
    public function verClase(Clase $clase)
    {
        // Verificar que la clase pertenece al profesor
        if ($clase->docente_id !== Auth::id()) {
            abort(403, 'No tienes acceso a esta clase');
        }

        $clase->load(['estudiantes' => function($query) {
            $query->wherePivot('activo', true)
                  ->orderBy('nombre');
        }]);

        $estudiantes = $clase->estudiantes->map(function($estudiante) {
            return [
                'id' => $estudiante->id,
                'nombre' => $estudiante->nombre,
                'correo' => $estudiante->correo,
                'fecha_inscripcion' => $estudiante->pivot->fecha_inscripcion
            ];
        });

        return Inertia::render('Clases/Ver', [
            'clase' => [
                'id' => $clase->id,
                'nombre' => $clase->nombre,
                'descripcion' => $clase->descripcion,
                'codigo' => $clase->codigo,
                'fecha_inicio' => $clase->fecha_inicio->format('Y-m-d'),
                'fecha_fin' => $clase->fecha_fin->format('Y-m-d'),
                'total_estudiantes' => $estudiantes->count(),
                'activa' => $clase->estaActiva(),
                'progreso' => $clase->obtenerProgreso()
            ],
            'estudiantes' => $estudiantes
        ]);
    }

    /**
     * Agregar estudiante a la clase por código
     */
    public function agregarEstudiante(Request $request, Clase $clase)
    {
        $request->validate([
            'codigo_estudiante' => 'required|string'
        ]);

        // Verificar que la clase pertenece al profesor
        if ($clase->docente_id !== Auth::id()) {
            abort(403, 'No tienes acceso a esta clase');
        }

        // Buscar estudiante por código o correo
        $estudiante = Usuario::where('correo', $request->codigo_estudiante)
            ->orWhere('codigo_estudiante', $request->codigo_estudiante)
            ->first();

        if (!$estudiante) {
            throw ValidationException::withMessages([
                'codigo_estudiante' => 'No se encontró un estudiante con ese código o correo.'
            ]);
        }

        if (!$estudiante->esEstudiante()) {
            throw ValidationException::withMessages([
                'codigo_estudiante' => 'El usuario no es un estudiante.'
            ]);
        }

        $resultado = $clase->agregarEstudiante($estudiante);

        if (!$resultado) {
            throw ValidationException::withMessages([
                'codigo_estudiante' => 'El estudiante ya está inscrito en esta clase.'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Estudiante agregado exitosamente',
            'estudiante' => [
                'id' => $estudiante->id,
                'nombre' => $estudiante->nombre,
                'correo' => $estudiante->correo,
                'fecha_inscripcion' => now()->format('Y-m-d H:i:s')
            ]
        ]);
    }

    /**
     * Seleccionar estudiante al azar para preguntar
     */
    public function seleccionarEstudianteAleatorio(Clase $clase)
    {
        // Verificar que la clase pertenece al profesor
        if ($clase->docente_id !== Auth::id()) {
            abort(403, 'No tienes acceso a esta clase');
        }

        $estudiante = $clase->seleccionarEstudianteAleatorio();

        if (!$estudiante) {
            return response()->json([
                'success' => false,
                'message' => 'No hay estudiantes disponibles en esta clase'
            ], 404);
        }

        // Registrar la selección (opcional - para estadísticas)
        // Puedes crear una tabla para llevar registro de las selecciones

        return response()->json([
            'success' => true,
            'estudiante' => [
                'id' => $estudiante->id,
                'nombre' => $estudiante->nombre,
                'mensaje' => "¡{$estudiante->nombre}, es tu turno de participar!"
            ]
        ]);
    }

    /**
     * Eliminar estudiante de la clase
     */
    public function removerEstudiante(Clase $clase, Usuario $estudiante)
    {
        // Verificar que la clase pertenece al profesor
        if ($clase->docente_id !== Auth::id()) {
            abort(403, 'No tienes acceso a esta clase');
        }

        $resultado = $clase->removerEstudiante($estudiante);

        if (!$resultado) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo remover al estudiante'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Estudiante removido de la clase'
        ]);
    }

    /**
     * Actualizar información de la clase
     */
    public function actualizarClase(Request $request, Clase $clase)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'grado' => 'nullable|string|max:50',
            'seccion' => 'nullable|string|max:10',
            'activa' => 'boolean'
        ]);

        // Verificar que la clase pertenece al profesor
        if ($clase->docente_id !== Auth::id()) {
            abort(403, 'No tienes acceso a esta clase');
        }

        $clase->update($request->only([
            'nombre', 'descripcion', 'fecha_inicio', 
            'fecha_fin', 'grado', 'seccion', 'activa'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Clase actualizada exitosamente',
            'clase' => [
                'id' => $clase->id,
                'nombre' => $clase->nombre,
                'fecha_inicio' => $clase->fecha_inicio->format('Y-m-d'),
                'fecha_fin' => $clase->fecha_fin->format('Y-m-d'),
                'activa' => $clase->activa
            ]
        ]);
    }
}