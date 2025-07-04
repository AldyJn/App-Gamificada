<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ProfesorDashboardController extends Controller
{
    /**
     * Dashboard principal del profesor
     */
    public function index()
    {
        try {
            $user = Auth::user();
            
            if (!$user->esDocente()) {
                abort(403, 'Acceso denegado');
            }

            // Cargar clases del docente
            $clases = Clase::where('id_docente', $user->id)
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
                        'total_estudiantes' => $clase->totalEstudiantes(),
                        'activa' => $clase->estaActiva(),
                        'progreso' => $clase->obtenerProgreso()
                    ];
                });

            // Estadísticas del profesor
            $totalEstudiantes = $clases->sum('total_estudiantes');

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

        } catch (\Exception $e) {
            Log::error('Error en dashboard profesor: ' . $e->getMessage());
            abort(500, 'Error interno del servidor');
        }
    }

    /**
     * Crear nueva clase
     */
    public function crearClase(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string|max:1000',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after:fecha_inicio'
            ]);

            DB::beginTransaction();

            $clase = new Clase();
            $clase->nombre = $request->nombre;
            $clase->descripcion = $request->descripcion ?? '';
            $clase->id_docente = Auth::id();
            $clase->fecha_inicio = $request->fecha_inicio;
            $clase->fecha_fin = $request->fecha_fin;
            $clase->activa = true;
            $clase->permitir_inscripcion = true;
            $clase->codigo = Clase::generarCodigoUnico();
            $clase->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Clase creada exitosamente',
                'clase' => [
                    'id' => $clase->id,
                    'nombre' => $clase->nombre,
                    'codigo' => $clase->codigo
                ]
            ]);

        } catch (ValidationException $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creando clase: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la clase. Inténtalo nuevamente.'
            ], 500);
        }
    }

    /**
     * Ver detalles de una clase específica
     */
    public function verClase(Clase $clase)
    {
        try {
            // Verificar que la clase pertenece al profesor autenticado
            if ($clase->id_docente !== Auth::id()) {
                abort(403, 'No tienes acceso a esta clase');
            }

            // Cargar estudiantes activos usando el método del modelo
            $estudiantes = $clase->estudiantesActivos()->map(function($estudiante) {
                return [
                    'id' => $estudiante->id,
                    'nombre' => $estudiante->nombre . ' ' . $estudiante->apellido,
                    'correo' => $estudiante->correo,
                    'fecha_inscripcion' => $estudiante->fecha_inscripcion
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

        } catch (\Exception $e) {
            Log::error('Error viendo clase: ' . $e->getMessage());
            abort(500, 'Error interno del servidor');
        }
    }

    /**
     * Agregar estudiante a la clase por código o correo
     */
    public function agregarEstudiante(Request $request, Clase $clase)
    {
        try {
            $request->validate([
                'codigo_estudiante' => 'required|string'
            ]);

            // Verificar que la clase pertenece al profesor
            if ($clase->id_docente !== Auth::id()) {
                abort(403, 'No tienes acceso a esta clase');
            }

            // Buscar estudiante por código o correo
            $estudiante = Usuario::where('correo', $request->codigo_estudiante)
                ->orWhereHas('estudiante', function($query) use ($request) {
                    $query->where('codigo_estudiante', $request->codigo_estudiante);
                })
                ->first();

            if (!$estudiante) {
                throw ValidationException::withMessages([
                    'codigo_estudiante' => 'No se encontró un estudiante con ese código o correo.'
                ]);
            }

            if (!$estudiante->esEstudiante()) {
                throw ValidationException::withMessages([
                    'codigo_estudiante' => 'El usuario encontrado no es un estudiante.'
                ]);
            }

            // Verificar si ya está inscrito
            if ($clase->tieneEstudiante($estudiante->id)) {
                throw ValidationException::withMessages([
                    'codigo_estudiante' => 'El estudiante ya está inscrito en esta clase.'
                ]);
            }

            // Agregar estudiante usando el método del modelo
            if ($clase->agregarEstudiante($estudiante->id)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Estudiante agregado exitosamente',
                    'estudiante' => [
                        'id' => $estudiante->id,
                        'nombre' => $estudiante->nombre . ' ' . $estudiante->apellido,
                        'correo' => $estudiante->correo,
                        'fecha_inscripcion' => now()
                    ]
                ]);
            } else {
                throw new \Exception('No se pudo agregar el estudiante');
            }

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error agregando estudiante: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar estudiante'
            ], 500);
        }
    }

    /**
     * Seleccionar estudiante aleatorio
     */
    public function seleccionarEstudianteAleatorio(Clase $clase)
    {
        try {
            // Verificar que la clase pertenece al profesor
            if ($clase->id_docente !== Auth::id()) {
                abort(403, 'No tienes acceso a esta clase');
            }

            $estudiantes = $clase->estudiantesActivos();

            if ($estudiantes->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay estudiantes en esta clase'
                ]);
            }

            $estudianteSeleccionado = $estudiantes->random();

            return response()->json([
                'success' => true,
                'estudiante' => [
                    'id' => $estudianteSeleccionado->id,
                    'nombre' => $estudianteSeleccionado->nombre . ' ' . $estudianteSeleccionado->apellido,
                    'correo' => $estudianteSeleccionado->correo
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error seleccionando estudiante: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al seleccionar estudiante'
            ], 500);
        }
    }

    /**
     * Regenerar código de clase
     */
    public function regenerarCodigo(Clase $clase)
    {
        try {
            // Verificar que la clase pertenece al profesor
            if ($clase->id_docente !== Auth::id()) {
                abort(403, 'No tienes acceso a esta clase');
            }

            $nuevoCodigo = $clase->regenerarCodigo();

            return response()->json([
                'success' => true,
                'message' => 'Código regenerado exitosamente',
                'nuevo_codigo' => $nuevoCodigo
            ]);

        } catch (\Exception $e) {
            Log::error('Error regenerando código: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al regenerar código'
            ], 500);
        }
    }
}