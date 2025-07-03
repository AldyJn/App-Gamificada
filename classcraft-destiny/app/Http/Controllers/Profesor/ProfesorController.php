<?php
// app/Http/Controllers/Profesor/ProfesorController.php

namespace App\Http\Controllers\Profesor;

use App\Http\Controllers\Controller;
use App\Models\{Clase, Estudiante, Usuario, InscripcionClase, TipoComportamiento};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProfesorController extends Controller
{
    /**
     * Dashboard principal del profesor - Vista tipo "Carta Estelar"
     */
    public function dashboard()
    {
        $docente = Auth::user()->docente;
        
        // Estadísticas del dashboard
        $estadisticas = [
            'clases_activas' => $docente->clasesActivas()->count(),
            'total_estudiantes' => $docente->totalEstudiantes(),
            'clases_hoy' => $docente->clases()
                ->whereJsonContains('horarios_clase', [now()->format('l') => now()->format('H:i')])
                ->count(),
            'tareas_pendientes' => 0, // TODO: Implementar cuando tengas sistema de tareas
        ];

        // Clases para la vista carta estelar
        $clases = $docente->clases()
            ->with(['inscripciones.estudiante.usuario', 'inscripciones.estudiante.personajes'])
            ->withCount(['inscripciones as estudiantes_count' => function($query) {
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
                    'estudiantes_count' => $clase->estudiantes_count,
                    'activa' => $clase->estaActiva(),
                    'progreso' => $this->calcularProgresoClase($clase),
                    'fecha_inicio' => $clase->fecha_inicio,
                    'fecha_fin' => $clase->fecha_fin,
                    'tema_visual' => $clase->tema_visual,
                    'alertas' => $this->obtenerAlertasClase($clase)
                ];
            });

        return view('profesor.dashboard', compact('estadisticas', 'clases'));
    }

    /**
     * Mostrar formulario de creación de clase
     */
    public function crearClaseForm()
    {
        return view('profesor.crear-clase');
    }

    /**
     * Crear nueva clase
     */
    public function crearClase(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'grado' => 'nullable|string|max:50',
            'seccion' => 'nullable|string|max:10',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'modo_competitivo' => 'boolean',
            'tema_visual' => 'string|in:destiny_default,destiny_dark,destiny_light'
        ]);

        $docente = Auth::user()->docente;

        $clase = $docente->clases()->create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'grado' => $request->grado,
            'seccion' => $request->seccion,
            'año_academico' => now()->year,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'tema_visual' => $request->tema_visual ?? 'destiny_default',
            'modo_competitivo' => $request->boolean('modo_competitivo'),
            'configuracion_gamificacion' => [
                'multiplicador_xp_base' => 1.0,
                'habilitar_clanes' => true,
                'eventos_especiales' => true,
                'sistema_prestigio' => true,
                'dificultad_adaptativa' => true,
                'permitir_pvp' => $request->boolean('modo_competitivo')
            ]
        ]);

        // Generar código de invitación y QR
        $clase->generarCodigoInvitacion();
        $clase->generarQR();

        return redirect()->route('profesor.dashboard')
               ->with('success', "¡Clase '{$clase->nombre}' creada exitosamente! Código: {$clase->codigo_invitacion}");
    }

    /**
     * Ver detalles de una clase específica
     */
    public function verClase(Clase $clase)
    {
        $this->authorize('view', $clase); // Verificar que es su clase

        $estudiantes = $clase->estudiantes()
            ->with(['usuario', 'personajes' => function($query) use ($clase) {
                $query->where('id_clase', $clase->id);
            }])
            ->get()
            ->map(function($estudiante) {
                $personaje = $estudiante->personajes->first();
                return [
                    'id' => $estudiante->id,
                    'nombre_completo' => $estudiante->usuario->nombreCompleto(),
                    'correo' => $estudiante->usuario->correo,
                    'codigo_estudiante' => $estudiante->codigo_estudiante,
                    'personaje' => $personaje ? [
                        'nombre' => $personaje->nombre_personaje,
                        'clase_rpg' => $personaje->claseRpg->nombre,
                        'nivel' => $personaje->nivel,
                        'salud_porcentaje' => $personaje->porcentajeVida(),
                        'energia_actual' => $personaje->energia_actual,
                        'luz_actual' => $personaje->luz_actual,
                    ] : null,
                    'fecha_ingreso' => $estudiante->pivot->fecha_ingreso,
                    'activo' => $estudiante->pivot->activo
                ];
            });

        $estadisticas = [
            'total_estudiantes' => $estudiantes->count(),
            'estudiantes_activos' => $estudiantes->where('activo', true)->count(),
            'promedio_nivel' => $estudiantes->avg('personaje.nivel') ?? 0,
            'clases_rpg_distribucion' => $estudiantes->groupBy('personaje.clase_rpg')->map->count()
        ];

        return view('profesor.clase-detalle', compact('clase', 'estudiantes', 'estadisticas'));
    }

    /**
     * Selector aleatorio de estudiantes para preguntas
     */
    public function estudianteAleatorio(Clase $clase)
    {
        $this->authorize('view', $clase);

        $estudiante = $clase->estudianteAleatorio();

        if (!$estudiante) {
            return response()->json([
                'success' => false,
                'message' => 'No hay estudiantes disponibles en esta clase.'
            ]);
        }

        $personaje = $estudiante->personajes()
            ->where('id_clase', $clase->id)
            ->with('claseRpg')
            ->first();

        return response()->json([
            'success' => true,
            'estudiante' => [
                'id' => $estudiante->id,
                'nombre' => $estudiante->usuario->nombre,
                'apellido' => $estudiante->usuario->apellido,
                'correo' => $estudiante->usuario->correo,
                'personaje' => $personaje ? [
                    'nombre' => $personaje->nombre_personaje,
                    'clase_rpg' => $personaje->claseRpg->nombre,
                    'nivel' => $personaje->nivel,
                    'icono' => $personaje->claseRpg->icono
                ] : null
            ]
        ]);
    }

    /**
     * Agregar estudiante a clase manualmente
     */
    public function agregarEstudiante(Request $request, Clase $clase)
    {
        $request->validate([
            'estudiante_id' => 'required|exists:estudiante,id'
        ]);

        $this->authorize('update', $clase);

        $estudiante = Estudiante::find($request->estudiante_id);

        // Verificar si ya está inscrito
        if ($clase->estudiantes()->where('estudiante.id', $estudiante->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'El estudiante ya está inscrito en esta clase.'
            ]);
        }

        // Inscribir estudiante
        InscripcionClase::create([
            'id_clase' => $clase->id,
            'id_estudiante' => $estudiante->id,
            'fecha_ingreso' => now(),
            'activo' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => "Estudiante {$estudiante->usuario->nombreCompleto()} agregado exitosamente."
        ]);
    }

    /**
     * Aplicar comportamiento (positivo/negativo) a estudiante
     */
    public function aplicarComportamiento(Request $request, Clase $clase)
    {
        $request->validate([
            'estudiante_id' => 'required|exists:estudiante,id',
            'tipo_comportamiento_id' => 'required|exists:tipo_comportamiento,id',
            'observaciones' => 'nullable|string|max:500'
        ]);

        $this->authorize('update', $clase);

        $estudiante = Estudiante::find($request->estudiante_id);
        $comportamiento = TipoComportamiento::find($request->tipo_comportamiento_id);
        
        $personaje = $estudiante->personajes()
            ->where('id_clase', $clase->id)
            ->first();

        if (!$personaje) {
            return response()->json([
                'success' => false,
                'message' => 'El estudiante no tiene personaje en esta clase.'
            ]);
        }

        // Aplicar efectos del comportamiento
        $personaje->aplicarComportamiento($comportamiento);

        // Registrar en historial (TODO: Crear tabla de historial)
        
        return response()->json([
            'success' => true,
            'message' => "Comportamiento '{$comportamiento->nombre}' aplicado exitosamente.",
            'personaje_actualizado' => [
                'salud_actual' => $personaje->salud_actual,
                'energia_actual' => $personaje->energia_actual,
                'luz_actual' => $personaje->luz_actual,
                'salud_porcentaje' => $personaje->porcentajeVida()
            ]
        ]);
    }

    /**
     * Buscar estudiantes para agregar a clase
     */
    public function buscarEstudiantes(Request $request)
    {
        $query = $request->get('q', '');
        
        $estudiantes = Usuario::where('id_tipo_usuario', 2) // Solo estudiantes
            ->where(function($q) use ($query) {
                $q->where('nombre', 'ILIKE', "%{$query}%")
                  ->orWhere('apellido', 'ILIKE', "%{$query}%")
                  ->orWhere('correo', 'ILIKE', "%{$query}%");
            })
            ->with('estudiante')
            ->limit(10)
            ->get()
            ->map(function($usuario) {
                return [
                    'id' => $usuario->estudiante->id,
                    'nombre_completo' => $usuario->nombreCompleto(),
                    'correo' => $usuario->correo,
                    'codigo' => $usuario->estudiante->codigo_estudiante,
                    'grado' => $usuario->estudiante->grado,
                    'seccion' => $usuario->estudiante->seccion
                ];
            });

        return response()->json($estudiantes);
    }

    /**
     * Calcular progreso de la clase
     */
    private function calcularProgresoClase(Clase $clase): float
    {
        $fechaInicio = $clase->fecha_inicio;
        $fechaFin = $clase->fecha_fin;
        $hoy = now()->toDateString();

        if ($hoy < $fechaInicio) {
            return 0;
        }

        if ($hoy > $fechaFin) {
            return 100;
        }

        $totalDias = $fechaInicio->diffInDays($fechaFin);
        $diasTranscurridos = $fechaInicio->diffInDays($hoy);

        return ($diasTranscurridos / $totalDias) * 100;
    }

    /**
     * Obtener alertas de la clase
     */
    private function obtenerAlertasClase(Clase $clase): array
    {
        $alertas = [];

        // Verificar estudiantes con salud baja
        $estudiantesCriticos = $clase->personajes()
            ->whereRaw('(salud_actual::float / salud_maxima::float) < 0.3')
            ->count();

        if ($estudiantesCriticos > 0) {
            $alertas[] = [
                'tipo' => 'warning',
                'mensaje' => "{$estudiantesCriticos} estudiante(s) en estado crítico",
                'icono' => '⚠️'
            ];
        }

        // Verificar si la clase está por terminar
        if ($clase->fecha_fin->diffInDays(now()) <= 7) {
            $alertas[] = [
                'tipo' => 'info',
                'mensaje' => 'La clase termina en ' . $clase->fecha_fin->diffInDays(now()) . ' días',
                'icono' => '📅'
            ];
        }

        return $alertas;
    }
}