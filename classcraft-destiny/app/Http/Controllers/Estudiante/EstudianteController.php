<?php

namespace App\Http\Controllers\Estudiante;
use App\Http\Controllers\Controller;
use App\Models\{
    Estudiante, 
    Personaje,
    Badge,
    Clase,
    ClaseRpg, 
    InscripcionClase,
    EstadisticaPersonaje
};
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EstudianteController extends Controller
{
    // ==========================================
    // DASHBOARD PRINCIPAL (Lo mejor del segundo)
    // ==========================================
 public function dashboard()
    {
        $user = Auth::user();
        $estudiante = $user->estudiante;
        $personajePrincipal = $estudiante->personajePrincipal();

        // Si no tiene personaje, redirigir a creación
        if (!$personajePrincipal) {
            return redirect()->route('estudiantes.crear-personaje');
        }

        // Stats combinados (de ambos controladores)
        $estadisticas = [
            'nivel' => $personajePrincipal->nivel,
            'power_level' => $personajePrincipal->power_level,
            'clases_activas' => $estudiante->clasesActivas()->count(),
            'logros_obtenidos' => $estudiante->badges()->count(),
            'ranking_global' => $this->calcularRangoGlobal($estudiante),
            'experiencia_total' => $personajePrincipal->experiencia_total
        ];

        // Clases con personajes (del segundo)
        $clases = $estudiante->clases()
            ->with(['docente.usuario', 'personajes' => fn($q) => $q->where('id_estudiante', $estudiante->id)])
            ->get()
            ->map(function($clase) {
                $personaje = $clase->personajes->first();
                return [
                    'id' => $clase->id,
                    'nombre' => $clase->nombre,
                    'profesor' => $clase->docente->usuario->nombre,
                    'personaje' => $personaje ? [
                        'nombre' => $personaje->nombre_personaje,
                        'nivel' => $personaje->nivel,
                        'clase_rpg' => $personaje->claseRpg->nombre,
                        'avatar' => $personaje->avatar_url
                    ] : null
                ];
            });

        // Ranking de la clase principal
        $rankingClase = $this->obtenerRankingClase($estudiante);

        // Logros cercanos a completar
        $logrosCercanos = $this->getLogrosCercanos($estudiante);

        return Inertia::render('Estudiante/Dashboard', [
            'estadisticas' => $estadisticas,
            'clases' => $clases,
            'ranking_clase' => $rankingClase,
            'logros_cercanos' => $logrosCercanos,
            'personaje_principal' => $personajePrincipal
        ]);
    }
    // ==========================================
    // SISTEMA DE RANKINGS (Lo mejor del primero)
    // ==========================================
    public function rankings(Request $request)
    {
        $estudiante = Auth::user()->estudiante;
        $personajePrincipal = $estudiante->personajePrincipal();

        // Filtros mejorados del primer controlador
        $rankings = DB::table('personajes')
            ->join('estudiantes', 'personajes.id_estudiante', '=', 'estudiantes.id')
            ->select('personajes.*', DB::raw('ROW_NUMBER() OVER (ORDER BY power_level DESC) as posicion'))
            ->when($request->clase_rpg_id, fn($q, $id) => $q->where('id_clase_rpg', $id))
            ->limit(100)
            ->get();

        return Inertia::render('Estudiante/Rankings', [
            'rankings' => $rankings,
            'mi_posicion' => $rankings->where('id_estudiante', $estudiante->id)->first(),
            'filtros' => [
                'clases_rpg' => ClaseRpg::all(),
                'periodos' => ['semanal', 'mensual', 'anual']
            ]
        ]);
    }

    // ==========================================
    // SISTEMA DE LOGROS (Lo mejor del primero)
    // ==========================================
    public function logros(Request $request)
    {
        $estudiante = Auth::user()->estudiante;
        
        $logros = Badge::query()
            ->with(['estudiantes' => fn($q) => $q->where('estudiante_id', $estudiante->id)])
            ->get()
            ->map(function($logro) use ($estudiante) {
                $obtenido = $logro->estudiantes->isNotEmpty();
                return [
                    'id' => $logro->id,
                    'nombre' => $logro->nombre,
                    'imagen' => $logro->imagen_url,
                    'obtenido' => $obtenido,
                    'progreso' => $this->calcularProgresoLogro($logro, $estudiante)
                ];
            });

        return Inertia::render('Estudiante/Logros', [
            'logros' => $logros,
            'estadisticas' => [
                'total' => $logros->count(),
                'obtenidos' => $logros->where('obtenido', true)->count()
            ]
        ]);
    }

    // ==========================================
    // GESTIÓN DE CLASES (Lo mejor del segundo)
    // ==========================================
    public function unirseClase(Request $request)
    {
        $request->validate([
            'codigo_invitacion' => 'required|exists:clases,codigo_invitacion'
        ]);

        $clase = Clase::where('codigo_invitacion', $request->codigo_invitacion)->first();
        $estudiante = Auth::user()->estudiante;

        // Verificar si ya está inscrito
        if ($estudiante->clases()->where('clase_id', $clase->id)->exists()) {
            return back()->with('error', 'Ya estás inscrito en esta clase');
        }

        $estudiante->clases()->attach($clase->id, [
            'fecha_ingreso' => now(),
            'activo' => true
        ]);

        return redirect()->route('estudiantes.crear-personaje', $clase->id);
    }

    public function crearPersonaje(Request $request, Clase $clase)
    {
        $request->validate([
            'nombre_personaje' => 'required|string|max:50',
            'clase_rpg_id' => 'required|exists:clases_rpg,id'
        ]);

        $personaje = Personaje::create([
            'id_estudiante' => Auth::user()->estudiante->id,
            'id_clase' => $clase->id,
            'id_clase_rpg' => $request->clase_rpg_id,
            'nombre_personaje' => $request->nombre_personaje,
            'nivel' => 1,
            'experiencia' => 0
        ]);

        return redirect()->route('estudiantes.dashboard')
               ->with('success', '¡Personaje creado exitosamente!');
    }

    // ==========================================
    // MÉTODOS AUXILIARES COMBINADOS
    // ==========================================
/**
     * Calcular el rango global del estudiante
     */
    private function calcularRangoGlobal($estudiante)
    {
        $personajePrincipal = $estudiante->personajePrincipal();
        
        if (!$personajePrincipal) {
            return null;
        }

        // Contar estudiantes con mayor power level
        $estudiantesConMayorPower = DB::table('personajes')
            ->where('power_level', '>', $personajePrincipal->power_level)
            ->count();

        return $estudiantesConMayorPower + 1; // Posición del estudiante
    }

    /**
     * Obtener logros cercanos a completar
     */
    private function getLogrosCercanos($estudiante)
    {
        $logros = Badge::whereNotExists(function($query) use ($estudiante) {
            $query->select(DB::raw(1))
                  ->from('badge_estudiante')
                  ->whereColumn('badge_estudiante.badge_id', 'badges.id')
                  ->where('badge_estudiante.estudiante_id', $estudiante->id);
        })
        ->get()
        ->map(function($logro) use ($estudiante) {
            $progreso = $this->calcularProgresoLogro($logro, $estudiante);
            $progresoTotal = collect($progreso)->avg('porcentaje');
            
            return [
                'id' => $logro->id,
                'nombre' => $logro->nombre,
                'descripcion' => $logro->descripcion,
                'progreso_porcentaje' => round($progresoTotal),
                'progreso_detalle' => $progreso,
                'imagen' => $logro->imagen_url ?? '/images/badges/default.png'
            ];
        })
        ->filter(function($logro) {
            return $logro['progreso_porcentaje'] >= 50; // Solo logros con 50% o más
        })
        ->sortByDesc('progreso_porcentaje')
        ->take(3)
        ->values();

        return $logros;
    }

    /**
     * Calcular progreso de un logro específico
     */
    private function calcularProgresoLogro(Badge $logro, Estudiante $estudiante): array
    {
        $requisitos = json_decode($logro->requisitos, true) ?? [];
        $progreso = [];
        
        foreach ($requisitos as $key => $valorRequerido) {
            $valorActual = match($key) {
                'nivel' => $estudiante->personajePrincipal()?->nivel ?? 0,
                'actividades' => $estudiante->actividadesCompletadas()?->count() ?? 0,
                'clases_completadas' => $estudiante->clasesCompletadas()?->count() ?? 0,
                'puntos_totales' => $estudiante->personajePrincipal()?->power_level ?? 0,
                'dias_consecutivos' => $this->calcularDiasConsecutivos($estudiante),
                default => 0
            };
            
            $progreso[$key] = [
                'actual' => $valorActual,
                'requerido' => $valorRequerido,
                'porcentaje' => min(100, ($valorActual / max($valorRequerido, 1)) * 100)
            ];
        }

        return $progreso;
    }

    /**
     * Obtener ranking de clase
     */
    private function obtenerRankingClase(Estudiante $estudiante): array
    {
        $claseActiva = $estudiante->clases()->where('activo', true)->first();
        
        if (!$claseActiva) {
            return [];
        }

        return $claseActiva->personajes()
            ->with('estudiante.usuario')
            ->orderByDesc('power_level')
            ->limit(5)
            ->get()
            ->map(function($personaje, $index) use ($estudiante) {
                return [
                    'posicion' => $index + 1,
                    'nombre' => $personaje->estudiante->usuario->nombre,
                    'personaje_nombre' => $personaje->nombre_personaje,
                    'nivel' => $personaje->nivel,
                    'power_level' => $personaje->power_level,
                    'es_yo' => $personaje->id_estudiante === $estudiante->id,
                    'avatar' => $personaje->avatar_url ?? '/images/avatars/default.png'
                ];
            })
            ->toArray();
    }
      private function calcularDiasConsecutivos(Estudiante $estudiante): int
    {
        // Implementar lógica para calcular días consecutivos
        // Esto dependería de tu tabla de actividades/historial
        return 0; // Placeholder por ahora
    }
}