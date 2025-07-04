<?php
// app/Http/Controllers/ProfesorDashboardController.php — VERSIÓN LIMPIA

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ProfesorDashboardController extends Controller
{
    /**
     * Crear una nueva clase.
     */
    public function crearClase(Request $request): JsonResponse
    {
        try {
            // Validación
            $request->validate([
                'nombre'        => 'required|string|max:255',
                'descripcion'   => 'nullable|string|max:1000',
                'fecha_inicio'  => 'required|date|after_or_equal:today',
                'fecha_fin'     => 'required|date|after:fecha_inicio',
                'grado'         => 'nullable|string|max:50',
                'seccion'       => 'nullable|string|max:10',
            ]);

            /** @var \App\Models\Usuario $usuario */
            $usuario  = Auth::user();
            $docente  = $usuario->docente;   // Relación 1‑a‑1 usuario→docente

            // Si el usuario aún no tiene registro en Docente, créalo automáticamente.
            if (!$docente) {
                $docente = Docente::create([
                    'id_usuario'     => $usuario->id,
                    'especialidad'   => 'General',
                    'codigo_docente' => 'DOC'.str_pad($usuario->id, 3, '0', STR_PAD_LEFT),
                ]);
                Log::info('Docente creado automáticamente', ['docente_id' => $docente->id]);
            }

            // Generar código único para la clase
            $codigo = Clase::generarCodigoUnico();

            $datosClase = [
                'nombre'                   => $request->nombre,
                'descripcion'              => $request->descripcion,
                'codigo'                   => $codigo,
                'id_docente'               => $docente->id,
                'fecha_inicio'             => $request->fecha_inicio,
                'fecha_fin'                => $request->fecha_fin,
                'grado'                    => $request->grado,
                'seccion'                  => $request->seccion,
                'año_academico'            => date('Y'),
                'activa'                   => true,
                'permitir_inscripcion'     => true,
                'configuracion_gamificacion' => [
                    'multiplicador_xp'   => 1.0,
                    'modo_competitivo'   => false,
                    'sistema_puntos'     => true,
                ],
            ];

            Log::info('Creando clase', $datosClase);
            $clase = Clase::create($datosClase);

            return response()->json([
                'success' => true,
                'message' => 'Clase creada exitosamente',
                'clase'   => [
                    'id'                => $clase->id,
                    'nombre'            => $clase->nombre,
                    'codigo'            => $clase->codigo,
                    'fecha_inicio'      => $clase->fecha_inicio->format('Y-m-d'),
                    'fecha_fin'         => $clase->fecha_fin->format('Y-m-d'),
                    'total_estudiantes' => 0,
                    'activa'            => true,
                    'progreso'          => 0,
                    'puede_seleccionar' => false,
                    'estudiantes'       => [],
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Throwable $th) {
            Log::error('Error creando clase', [
                'exception' => $th,
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la clase: '.$th->getMessage(),
            ], 500);
        }
    }

    /**
     * Vista detallada de la clase (Inertia).
     */
    public function verClase(Clase $clase)
    {
        if ($clase->id_docente !== Auth::id()) {
            abort(403, 'No tienes acceso a esta clase');
        }

        // Cargar estudiantes (activos si existe pivot)
        $clase->load(['estudiantes' => function ($q) {
            if (\Schema::hasTable('inscripcion_clase')) {
                $q->wherePivot('activo', true);
            }
            $q->orderBy('nombre');
        }]);

        $estudiantes = $clase->estudiantes->map(fn ($e) => [
            'id'               => $e->id,
            'nombre'           => trim($e->nombre.' '.($e->apellido ?? '')),
            'correo'           => $e->correo,
            'fecha_inscripcion'=> $e->pivot->created_at ?? now(),
        ]);

        return Inertia::render('Clases/Ver', [
            'clase' => [
                'id'                => $clase->id,
                'nombre'            => $clase->nombre,
                'descripcion'       => $clase->descripcion,
                'codigo'            => $clase->codigo,
                'fecha_inicio'      => $clase->fecha_inicio->format('Y-m-d'),
                'fecha_fin'         => $clase->fecha_fin->format('Y-m-d'),
                'total_estudiantes' => $estudiantes->count(),
                'activa'            => method_exists($clase, 'estaActiva') ? $clase->estaActiva() : $clase->activa,
                'progreso'          => method_exists($clase, 'obtenerProgreso') ? $clase->obtenerProgreso() : 0,
                'grado'             => $clase->grado,
                'seccion'           => $clase->seccion,
                'año_academico'     => $clase->año_academico,
            ],
            'estudiantes' => $estudiantes,
            'profesor'    => [
                'nombre' => Auth::user()->nombre,
                'correo' => Auth::user()->correo,
            ],
        ]);
    }

    /**
     * Seleccionar aleatoriamente un estudiante de la clase.
     */
    public function seleccionarEstudianteAleatorio(Clase $clase): JsonResponse
    {
        try {
            if ($clase->id_docente !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes acceso a esta clase',
                ], 403);
            }

            // Usar un scope, una relación personalizada o fallback a pivot.
            $estudiantes = method_exists($clase, 'estudiantesActivos')
                ? $clase->estudiantesActivos()->get()
                : (\Schema::hasTable('inscripcion_clase')
                    ? $clase->estudiantes()->wherePivot('activo', true)->get()
                    : $clase->estudiantes);

            if ($estudiantes->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay estudiantes disponibles en esta clase',
                ]);
            }

            $e = $estudiantes->random();

            return response()->json([
                'success'   => true,
                'estudiante'=> [
                    'id'       => $e->id,
                    'nombre'   => $e->nombre,
                    'apellido' => $e->apellido ?? '',
                    'correo'   => $e->correo,
                ],
            ]);
        } catch (\Throwable $th) {
            Log::error('Error seleccionando estudiante aleatorio', ['exception' => $th]);

            return response()->json([
                'success' => false,
                'message' => 'Error al seleccionar estudiante',
            ], 500);
        }
    }
}
