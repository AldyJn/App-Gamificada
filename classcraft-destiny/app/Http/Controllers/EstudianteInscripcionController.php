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

class EstudianteInscripcionController extends Controller
{
    public function dashboard()
    {
        try {
            $user = auth()->user();
            
            if (!$user->esEstudiante()) {
                abort(403, 'Acceso denegado');
            }

            $clases = DB::table('inscripcion_clase')
                ->join('clase', 'inscripcion_clase.id_clase', '=', 'clase.id')
                ->join('usuarios as docentes', 'clase.id_docente', '=', 'docentes.id')
                ->where('inscripcion_clase.id_estudiante', $user->id)  // COLUMNA CORRECTA
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
                    'docentes.apellido as docente_apellido',
                    'inscripcion_clase.created_at as fecha_inscripcion'
                ])
                ->orderBy('clase.created_at', 'desc')
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
                        'activa' => $clase->activa,
                        'fecha_inscripcion' => $clase->fecha_inscripcion
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

        } catch (\Exception $e) {
            Log::error('Error en dashboard estudiante: ' . $e->getMessage());
            abort(500, 'Error interno del servidor');
        }
    }

    public function unirseAClase(Request $request)
    {
        try {
            $request->validate([
                'codigo_clase' => 'required|string|size:6'
            ]);

            $user = auth()->user();
            
            if (!$user->esEstudiante()) {
                throw ValidationException::withMessages([
                    'codigo_clase' => 'Solo los estudiantes pueden unirse a clases.'
                ]);
            }

            $clase = Clase::where('codigo', strtoupper($request->codigo_clase))
                          ->where('activa', true)
                          ->where('permitir_inscripcion', true)
                          ->first();

            if (!$clase) {
                throw ValidationException::withMessages([
                    'codigo_clase' => 'Código de clase inválido o la clase no permite inscripciones.'
                ]);
            }

            $yaInscrito = DB::table('inscripcion_clase')
                ->where('id_clase', $clase->id)
                ->where('id_estudiante', $user->id)  // COLUMNA CORRECTA
                ->where('activo', true)
                ->exists();

            if ($yaInscrito) {
                throw ValidationException::withMessages([
                    'codigo_clase' => 'Ya estás inscrito en esta clase.'
                ]);
            }

            DB::table('inscripcion_clase')->insert([
                'id_clase' => $clase->id,
                'id_estudiante' => $user->id,  // COLUMNA CORRECTA
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Te has unido exitosamente a la clase: {$clase->nombre}"
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error uniéndose a clase: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al unirse a la clase. Inténtalo nuevamente.'
            ], 500);
        }
    }
}