<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Usuario;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class EstudianteInscripcionController extends Controller
{
    /**
     * Mostrar formulario de inscripción por código de clase
     */
    public function mostrarFormulario()
    {
        return Inertia::render('Estudiante/Inscripcion', [
            'titulo' => 'Únete a una Clase',
            'descripcion' => 'Ingresa el código de tu clase para unirte'
        ]);
    }

    /**
     * Procesar inscripción de estudiante con código
     */
    public function inscribirseConCodigo(Request $request)
    {
        try {
            $request->validate([
                'codigo_clase' => 'required|string|size:6',
                'nombre' => 'required|string|max:100',
                'apellido' => 'required|string|max:100',
                'correo' => 'required|email|unique:usuario,correo',
                'contraseña' => 'required|string|min:6|confirmed',
                'grado' => 'nullable|string|max:20',
                'seccion' => 'nullable|string|max:10'
            ]);

            // Buscar la clase por código
            $clase = Clase::where('codigo', strtoupper($request->codigo_clase))
                          ->where('activa', true)
                          ->where('permitir_inscripcion', true)
                          ->first();

            if (!$clase) {
                throw ValidationException::withMessages([
                    'codigo_clase' => 'Código de clase inválido o la clase no permite inscripciones.'
                ]);
            }

            // Verificar si la clase no ha terminado
            if ($clase->fecha_fin && $clase->fecha_fin->isPast()) {
                throw ValidationException::withMessages([
                    'codigo_clase' => 'Esta clase ya ha finalizado.'
                ]);
            }

            DB::beginTransaction();

            // Crear usuario
            $usuario = Usuario::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'correo' => $request->correo,
                'contraseña_hash' => Hash::make($request->contraseña),
                'salt' => \Str::random(32),
                'id_tipo_usuario' => 2, // estudiante
                'id_estado' => 1, // activo
                'timezone' => 'America/Lima',
                'notificaciones_push' => true,
                'configuracion_ui' => json_encode([
                    'tema' => 'destiny_dark',
                    'animaciones' => true,
                    'sonidos' => true,
                    'idioma' => 'es',
                    'mostrar_tutoriales' => true
                ]),
                'estadisticas_globales' => json_encode([
                    'personajes_creados' => 0,
                    'total_xp_ganada' => 0,
                    'actividades_completadas' => 0,
                    'tiempo_total_plataforma' => 0
                ])
            ]);

            // Crear perfil de estudiante
            $estudiante = Estudiante::create([
                'id_usuario' => $usuario->id,
                'codigo_estudiante' => $this->generarCodigoEstudiante(),
                'grado' => $request->grado ?? $clase->grado,
                'seccion' => $request->seccion ?? $clase->seccion
            ]);

            // Inscribir en la clase usando el método del modelo
            if (!$clase->agregarEstudiante($usuario->id)) {
                throw new \Exception('No se pudo inscribir en la clase');
            }

            DB::commit();

            // Autenticar al usuario
            auth()->login($usuario);

            return redirect()->route('estudiante.dashboard')
                ->with('success', "¡Bienvenido! Te has unido exitosamente a la clase: {$clase->nombre}");

        } catch (ValidationException $e) {
            DB::rollback();
            throw $e;

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error en inscripción: ' . $e->getMessage());
            
            throw ValidationException::withMessages([
                'general' => 'Error al procesar la inscripción. Inténtalo nuevamente.'
            ]);
        }
    }

    /**
     * Dashboard del estudiante
     */
    public function dashboard()
    {
        try {
            $user = auth()->user();
            
            if (!$user->esEstudiante()) {
                abort(403, 'Acceso denegado');
            }

            // Cargar clases del estudiante
            $clases = $user->clasesComoEstudiante()
                ->with(['docente'])
                ->get()
                ->map(function($clase) {
                    return [
                        'id' => $clase->id,
                        'nombre' => $clase->nombre,
                        'descripcion' => $clase->descripcion,
                        'codigo' => $clase->codigo,
                        'docente' => $clase->docente->nombre . ' ' . $clase->docente->apellido,
                        'fecha_inicio' => $clase->fecha_inicio->format('Y-m-d'),
                        'fecha_fin' => $clase->fecha_fin->format('Y-m-d'),
                        'progreso' => $clase->obtenerProgreso(),
                        'activa' => $clase->estaActiva(),
                        'fecha_inscripcion' => $clase->pivot->created_at ?? now()
                    ];
                });

            // Estadísticas del estudiante
            $estadisticasGlobales = json_decode($user->estadisticas_globales ?? '{}', true);
            $estadisticas = [
                'total_clases' => $clases->count(),
                'clases_activas' => $clases->where('activa', true)->count(),
                'actividades_completadas' => $estadisticasGlobales['actividades_completadas'] ?? 0,
                'xp_total' => $estadisticasGlobales['total_xp_ganada'] ?? 0
            ];

            return Inertia::render('Dashboard/Estudiante', [
                'clases' => $clases,
                'estadisticas' => $estadisticas,
                'estudiante' => [
                    'nombre' => $user->nombre . ' ' . $user->apellido,
                    'correo' => $user->correo,
                    'codigo' => $user->estudiante->codigo_estudiante ?? 'N/A'
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error en dashboard estudiante: ' . $e->getMessage());
            abort(500, 'Error interno del servidor');
        }
    }

    /**
     * Ver clase específica como estudiante
     */
    public function verClase(Clase $clase)
    {
        try {
            $user = auth()->user();
            
            // Verificar que el estudiante esté inscrito
            if (!$clase->estudiantes()->where('usuario.id', $user->id)->exists()) {
                abort(403, 'No tienes acceso a esta clase');
            }

            // Obtener compañeros de clase
            $companeros = $clase->estudiantesActivos()
                ->where('id', '!=', $user->id)
                ->map(function($estudiante) {
                    return [
                        'id' => $estudiante->id,
                        'nombre' => $estudiante->nombre . ' ' . $estudiante->apellido,
                        'avatar' => $estudiante->avatar ?? '/images/avatars/default.jpg'
                    ];
                });

            // Estadísticas del estudiante para esta clase
            $estadisticasGlobales = json_decode($user->estadisticas_globales ?? '{}', true);

            return Inertia::render('Estudiante/Clase', [
                'clase' => [
                    'id' => $clase->id,
                    'nombre' => $clase->nombre,
                    'descripcion' => $clase->descripcion,
                    'docente' => $clase->docente->nombre . ' ' . $clase->docente->apellido,
                    'fecha_inicio' => $clase->fecha_inicio->format('Y-m-d'),
                    'fecha_fin' => $clase->fecha_fin->format('Y-m-d'),
                    'total_estudiantes' => $clase->totalEstudiantes(),
                    'progreso' => $clase->obtenerProgreso()
                ],
                'companeros' => $companeros,
                'mi_progreso' => [
                    'xp' => $estadisticasGlobales['total_xp_ganada'] ?? 0,
                    'nivel' => $this->calcularNivel($estadisticasGlobales['total_xp_ganada'] ?? 0),
                    'actividades_completadas' => $estadisticasGlobales['actividades_completadas'] ?? 0
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error viendo clase como estudiante: ' . $e->getMessage());
            abort(500, 'Error interno del servidor');
        }
    }

    /**
     * Unirse a clase adicional (para estudiantes ya registrados)
     */
    public function unirseAClase(Request $request)
    {
        try {
            $request->validate([
                'codigo_clase' => 'required|string|size:6'
            ]);

            $user = auth()->user();
            
            if (!$user->esEstudiante()) {
                abort(403, 'Solo los estudiantes pueden unirse a clases');
            }

            // Buscar la clase
            $clase = Clase::where('codigo', strtoupper($request->codigo_clase))
                          ->where('activa', true)
                          ->where('permitir_inscripcion', true)
                          ->first();

            if (!$clase) {
                throw ValidationException::withMessages([
                    'codigo_clase' => 'Código de clase inválido o la clase no permite inscripciones.'
                ]);
            }

            // Verificar si ya está inscrito
            if ($clase->estudiantes()->where('usuario.id', $user->id)->exists()) {
                throw ValidationException::withMessages([
                    'codigo_clase' => 'Ya estás inscrito en esta clase.'
                ]);
            }

            // Inscribir en la clase
            if (!$clase->agregarEstudiante($user->id)) {
                throw new \Exception('No se pudo unir a la clase');
            }

            return redirect()->route('estudiante.dashboard')
                ->with('success', "Te has unido exitosamente a la clase: {$clase->nombre}");

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors());

        } catch (\Exception $e) {
            Log::error('Error uniéndose a clase: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al unirse a la clase');
        }
    }

    /**
     * Verificar código de clase (AJAX)
     */
    public function verificarCodigo(Request $request)
    {
        try {
            $request->validate([
                'codigo' => 'required|string|size:6'
            ]);

            $clase = Clase::where('codigo', strtoupper($request->codigo))
                          ->where('activa', true)
                          ->where('permitir_inscripcion', true)
                          ->with('docente')
                          ->first();

            if (!$clase) {
                return response()->json([
                    'valido' => false,
                    'mensaje' => 'Código inválido o clase no disponible'
                ]);
            }

            if ($clase->fecha_fin && $clase->fecha_fin->isPast()) {
                return response()->json([
                    'valido' => false,
                    'mensaje' => 'Esta clase ya ha finalizado'
                ]);
            }

            return response()->json([
                'valido' => true,
                'clase' => [
                    'nombre' => $clase->nombre,
                    'descripcion' => $clase->descripcion,
                    'docente' => $clase->docente->nombre . ' ' . $clase->docente->apellido,
                    'grado' => $clase->grado,
                    'seccion' => $clase->seccion,
                    'total_estudiantes' => $clase->totalEstudiantes()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error verificando código: ' . $e->getMessage());
            
            return response()->json([
                'valido' => false,
                'mensaje' => 'Error al verificar el código'
            ], 500);
        }
    }

    /**
     * Generar código único para estudiante
     */
    private function generarCodigoEstudiante(): string
    {
        do {
            $codigo = 'EST' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Estudiante::where('codigo_estudiante', $codigo)->exists());

        return $codigo;
    }

    /**
     * Calcular nivel basado en XP
     */
    private function calcularNivel(int $xp): int
    {
        // Fórmula simple: cada nivel requiere 1000 XP más que el anterior
        // Nivel 1: 0-999 XP, Nivel 2: 1000-2999 XP, etc.
        return intval($xp / 1000) + 1;
    }
}