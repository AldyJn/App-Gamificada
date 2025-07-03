<?php

namespace App\Http\Controllers;

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
                        'salud' => $personaje->porcentajeVida()
                    ] : null
                ];
            });

        // Ranking de la clase principal (del segundo)
        $rankingClase = $this->obtenerRankingClase($estudiante);

        // Logros cercanos a completar (del primero)
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

    // Del primer controlador (mejorado)
    private function calcularProgresoLogro(Badge $logro, Estudiante $estudiante): array
    {
        $requisitos = json_decode($logro->requisitos, true) ?? [];
        $progreso = [];
        
        foreach ($requisitos as $key => $valorRequerido) {
            $valorActual = match($key) {
                'nivel' => $estudiante->personajePrincipal()->nivel,
                'actividades' => $estudiante->actividadesCompletadas()->count(),
                default => 0
            };
            
            $progreso[$key] = [
                'actual' => $valorActual,
                'requerido' => $valorRequerido,
                'porcentaje' => min(100, ($valorActual / $valorRequerido) * 100)
            ];
        }

        return $progreso;
    }

    // Del segundo controlador (optimizado)
    private function obtenerRankingClase(Estudiante $estudiante): array
    {
        return $estudiante->clases()->first()
            ?->personajes()
            ->with('estudiante.usuario')
            ->orderByDesc('nivel')
            ->limit(5)
            ->get()
            ->map(fn($p) => [
                'nombre' => $p->estudiante->usuario->nombre,
                'nivel' => $p->nivel,
                'es_yo' => $p->id_estudiante === $estudiante->id
            ]) ?? [];
    }
}