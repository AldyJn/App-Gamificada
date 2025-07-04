<?php
// app/Http/Controllers/ClaseController.php
namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Estudiante;
use App\Models\InscripcionClase;
use App\Models\ClaseRpg;
use App\Models\Personaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Inertia\Inertia;

class ClaseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->esDocente()) {
            // Mostrar clases del docente
            $clases = Clase::where('id_docente', $user->docente->id)
                ->withCount(['estudiantes as estudiantes_count' => function($query) {
                    $query->where('inscripcion_clase.activo', true);
                }])
                ->with(['inscripciones' => function($query) {
                    $query->where('activo', true);
                }])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($clase) {
                    return [
                        'id' => $clase->id,
                        'nombre' => $clase->nombre,
                        'descripcion' => $clase->descripcion,
                        'codigo_invitacion' => $clase->codigo_invitacion,
                        'grado' => $clase->grado,
                        'seccion' => $clase->seccion,
                        'estudiantes_count' => $clase->estudiantes_count,
                        'activo' => $clase->activo,
                        'fecha_inicio' => $clase->fecha_inicio,
                        'fecha_fin' => $clase->fecha_fin,
                        'created_at' => $clase->created_at
                    ];
                });
                
            return Inertia::render('Dashboard/Profesor', [
                'clases' => [
                    'data' => $clases
                ],
                'estadisticas' => [
                    'clases_activas' => $clases->where('activo', true)->count(),
                    'total_estudiantes' => $clases->sum('estudiantes_count'),
                    'promedio_estudiantes' => $clases->avg('estudiantes_count') ?? 0
                ]
            ]);
        } else {
            // Mostrar clases del estudiante
            $clases = $user->estudiante->clases()
                ->withPivot('fecha_ingreso', 'activo')
                ->with(['docente.usuario', 'personajes' => function($query) use ($user) {
                    $query->where('id_estudiante', $user->estudiante->id);
                }])
                ->orderBy('inscripcion_clase.created_at', 'desc')
                ->get()
                ->map(function($clase) {
                    $personaje = $clase->personajes->first();
                    return [
                        'clase' => [
                            'id' => $clase->id,
                            'nombre' => $clase->nombre,
                            'descripcion' => $clase->descripcion,
                            'grado' => $clase->grado,
                            'seccion' => $clase->seccion
                        ],
                        'docente' => $clase->docente->usuario->nombre,
                        'personaje' => $personaje ? [
                            'id' => $personaje->id,
                            'nombre' => $personaje->nombre,
                            'nivel' => $personaje->nivel,
                            'experiencia' => $personaje->experiencia,
                            'imagen_perfil' => $personaje->imagen_perfil
                        ] : null,
                        'fecha_ingreso' => $clase->pivot->fecha_ingreso,
                        'activo' => $clase->pivot->activo
                    ];
                });
                
            return Inertia::render('Dashboard/Estudiante', [
                'clases' => $clases,
                'estadisticas' => [
                    'clases_activas' => $clases->where('clase.pivot.activo', true)->count(),
                    'personajes_creados' => $clases->whereNotNull('personaje')->count()
                ]
            ]);
        }
    }

    public function create()
    {
        // Solo docentes pueden crear clases
        abort_unless(Auth::user()->esDocente(), 403);
        
        $clasesRpg = ClaseRpg::where('activo', true)->get();
        
        return Inertia::render('Clases/Create', [
            'clasesRpg' => $clasesRpg
        ]);
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->esDocente(), 403);
        
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:500',
            'grado' => 'required|string|max:10',
            'seccion' => 'required|string|max:10',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        try {
            DB::beginTransaction();
            
            $clase = Clase::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'id_docente' => Auth::user()->docente->id,
                'grado' => $request->grado,
                'seccion' => $request->seccion,
                'año_academico' => date('Y'),
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'activo' => true
            ]);

            DB::commit();
            
            return redirect()->route('dashboard')
                ->with('success', 'Clase creada exitosamente. Código de invitación: ' . $clase->codigo_invitacion);
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al crear la clase: ' . $e->getMessage()]);
        }
    }

    public function show(Clase $clase)
    {
        $user = Auth::user();
        
        // Verificar acceso
        if ($user->esDocente()) {
            abort_unless($clase->id_docente === $user->docente->id, 403);
            
            // Cargar estudiantes con sus personajes
            $estudiantes = $clase->estudiantes()
                ->with(['usuario', 'personajes' => function($query) use ($clase) {
                    $query->where('id_clase', $clase->id)
                        ->with('claseRpg');
                }])
                ->wherePivot('activo', true)
                ->get()
                ->map(function($estudiante) {
                    $personaje = $estudiante->personajes->first();
                    return [
                        'id' => $estudiante->id,
                        'nombre' => $estudiante->usuario->nombre,
                        'correo' => $estudiante->usuario->correo,
                        'codigo_estudiante' => $estudiante->codigo_estudiante,
                        'personaje' => $personaje ? [
                            'nombre' => $personaje->nombre,
                            'nivel' => $personaje->nivel,
                            'clase_rpg' => $personaje->claseRpg->nombre,
                            'avatar_base' => $personaje->avatar_base
                        ] : null
                    ];
                });
            
            return Inertia::render('Clases/Show', [
                'clase' => $clase,
                'estudiantes' => $estudiantes,
                'puede_seleccionar' => $estudiantes->count() > 0
            ]);
        } else {
            $inscripcion = InscripcionClase::where('id_clase', $clase->id)
                ->where('id_estudiante', $user->estudiante->id)
                ->where('activo', true)
                ->first();
            abort_unless($inscripcion, 403);
            
            $personaje = Personaje::where('id_clase', $clase->id)
                ->where('id_estudiante', $user->estudiante->id)
                ->with('claseRpg')
                ->first();
            
            return Inertia::render('Clases/ShowEstudiante', [
                'clase' => $clase->load('docente.usuario'),
                'mi_personaje' => $personaje,
                'salon_virtual' => [
                    'url' => route('salon.virtual', $clase->id),
                    'activo' => true
                ]
            ]);
        }
    }

    // Método para que estudiantes se unan a clases
    public function showUnirse()
    {
        abort_unless(Auth::user()->esEstudiante(), 403);
        
        $clasesRpg = ClaseRpg::where('activo', true)->get();
        
        return Inertia::render('Clases/Unirse', [
            'clasesRpg' => $clasesRpg
        ]);
    }

    public function unirse(Request $request)
    {
        abort_unless(Auth::user()->esEstudiante(), 403);
        
        $request->validate([
            'codigo_invitacion' => 'required|string|size:6',
            'personaje' => 'required|array',
            'personaje.nombre' => 'required|string|max:50',
            'personaje.id_clase_rpg' => 'required|exists:clase_rpg,id'
        ]);

        $clase = Clase::where('codigo_invitacion', strtoupper($request->codigo_invitacion))
            ->where('activo', true)
            ->first();

        if (!$clase) {
            return back()->withErrors(['codigo_invitacion' => 'Código de invitación inválido o clase no activa.']);
        }

        $estudiante = Auth::user()->estudiante;
        
        // Verificar si ya está inscrito
        $inscripcionExistente = InscripcionClase::where('id_clase', $clase->id)
            ->where('id_estudiante', $estudiante->id)
            ->first();

        if ($inscripcionExistente) {
            if ($inscripcionExistente->activo) {
                return back()->withErrors(['codigo_invitacion' => 'Ya estás inscrito en esta clase.']);
            } else {
                // Reactivar inscripción
                $inscripcionExistente->update(['activo' => true]);
            }
        } else {
            try {
                DB::beginTransaction();
                
                // Crear nueva inscripción
                InscripcionClase::create([
                    'id_clase' => $clase->id,
                    'id_estudiante' => $estudiante->id,
                    'fecha_ingreso' => now(),
                    'activo' => true
                ]);
                
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return back()->withErrors(['error' => 'Error al unirse a la clase.']);
            }
        }
        
        // Crear personaje si no existe
        $personajeExistente = Personaje::where('id_clase', $clase->id)
            ->where('id_estudiante', $estudiante->id)
            ->first();
            
        if (!$personajeExistente) {
            try {
                DB::beginTransaction();
                
                Personaje::create([
                    'id_estudiante' => $estudiante->id,
                    'id_clase' => $clase->id,
                    'id_clase_rpg' => $request->personaje['id_clase_rpg'],
                    'nombre' => $request->personaje['nombre'],
                    'nivel' => 1,
                    'experiencia' => 0,
                    'puntos_vida' => 100,
                    'puntos_mana' => 100,
                    'avatar_base' => 'default'
                ]);
                
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return back()->withErrors(['error' => 'Error al crear el personaje.']);
            }
        }
        
        return redirect()->route('salon.virtual', $clase->id)
            ->with('success', 'Te has unido exitosamente a la clase: ' . $clase->nombre);
    }

    // API para verificar código de invitación
    public function verificarCodigo(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|size:6'
        ]);

        $clase = Clase::where('codigo_invitacion', strtoupper($request->codigo))
            ->where('activo', true)
            ->select('id', 'nombre', 'descripcion', 'grado', 'seccion')
            ->first();

        if (!$clase) {
            return response()->json(['valido' => false]);
        }

        return response()->json([
            'valido' => true,
            'clase' => $clase
        ]);
    }

    // Método para selección aleatoria de estudiantes
    public function seleccionarAleatorio(Clase $clase)
    {
        abort_unless(Auth::user()->esDocente(), 403);
        abort_unless($clase->id_docente === Auth::user()->docente->id, 403);
        
        $estudiantes = $clase->estudiantes()
            ->with(['usuario', 'personajes' => function($query) use ($clase) {
                $query->where('id_clase', $clase->id)->with('claseRpg');
            }])
            ->wherePivot('activo', true)
            ->get();
            
        if ($estudiantes->isEmpty()) {
            return response()->json([
                'error' => 'No hay estudiantes en esta clase'
            ], 404);
        }
        
        $estudianteAleatorio = $estudiantes->random();
        $personaje = $estudianteAleatorio->personajes->first();
        
        return response()->json([
            'estudiante' => [
                'id' => $estudianteAleatorio->id,
                'nombre' => $estudianteAleatorio->usuario->nombre,
                'correo' => $estudianteAleatorio->usuario->correo,
                'personaje' => $personaje ? [
                    'nombre' => $personaje->nombre,
                    'nivel' => $personaje->nivel,
                    'clase_rpg' => $personaje->claseRpg->nombre,
                    'avatar' => $personaje->avatar_base
                ] : null
            ]
        ]);
    }
}