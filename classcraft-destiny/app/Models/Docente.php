<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Docente extends Model
{
    use HasFactory;

    protected $table = 'docente';

    protected $fillable = [
        'id_usuario',
        'especialidad'
    ];

    // ==========================================
    // RELACIONES
    // ==========================================

    /**
     * Relación con usuario
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    /**
     * Relación con clases que gestiona
     */
    public function clases(): HasMany
    {
        return $this->hasMany(Clase::class, 'id_docente');
    }

    /**
     * Relación con asistencias registradas
     */
    public function asistenciasRegistradas(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'id_docente');
    }

    /**
     * Relación con transacciones de moneda otorgadas
     */
    public function transaccionesOtorgadas(): HasMany
    {
        return $this->hasMany(TransaccionMoneda::class, 'otorgado_por');
    }

    // ==========================================
    // MÉTODOS DE CONVENIENCIA
    // ==========================================

    /**
     * Obtiene todas las clases activas del docente
     */
    public function clasesActivas()
    {
        return $this->clases()->where('activo', true);
    }

    /**
     * Obtiene todos los estudiantes del docente
     */
    public function misEstudiantes()
    {
        return Estudiante::whereHas('inscripciones', function($query) {
            $query->whereIn('id_clase', $this->clases()->pluck('id'))
                  ->where('activo', true);
        })->with(['usuario', 'personajes']);
    }

    /**
     * Obtiene el total de estudiantes del docente
     */
    public function getTotalEstudiantesAttribute(): int
    {
        return $this->misEstudiantes()->count();
    }

    /**
     * Obtiene el total de clases del docente
     */
    public function getTotalClasesAttribute(): int
    {
        return $this->clases()->count();
    }

    /**
     * Obtiene el total de clases activas del docente
     */
    public function getTotalClasesActivasAttribute(): int
    {
        return $this->clasesActivas()->count();
    }

    /**
     * Obtiene el total de actividades creadas por el docente
     */
    public function getTotalActividadesAttribute(): int
    {
        return Actividad::whereIn('id_clase', $this->clases()->pluck('id'))->count();
    }

    /**
     * Verifica si el docente puede gestionar una clase específica
     */
    public function puedeGestionarClase(Clase $clase): bool
    {
        return $clase->id_docente === $this->id;
    }

    /**
     * Verifica si el docente puede gestionar un estudiante específico
     */
    public function puedeGestionarEstudiante(Estudiante $estudiante): bool
    {
        return $estudiante->inscripciones()
            ->whereIn('id_clase', $this->clases()->pluck('id'))
            ->where('activo', true)
            ->exists();
    }

    // ==========================================
    // MÉTODOS DE ESTADÍSTICAS
    // ==========================================

    /**
     * Obtiene estadísticas generales del docente
     */
    public function getEstadisticasGenerales(): array
    {
        $clases = $this->clases()->with(['inscripciones', 'actividades'])->get();
        
        return [
            'total_clases' => $clases->count(),
            'clases_activas' => $clases->where('activo', true)->count(),
            'total_estudiantes' => $clases->sum(function($clase) {
                return $clase->inscripciones()->where('activo', true)->count();
            }),
            'total_actividades' => $clases->sum(function($clase) {
                return $clase->actividades()->count();
            }),
            'actividades_activas' => $clases->sum(function($clase) {
                return $clase->actividades()->where('activa', true)->count();
            }),
            'promedio_estudiantes_por_clase' => $clases->where('activo', true)->isEmpty() ? 0 : 
                $clases->where('activo', true)->avg(function($clase) {
                    return $clase->inscripciones()->where('activo', true)->count();
                }),
            'transacciones_otorgadas' => $this->transaccionesOtorgadas()->count(),
            'experiencia_total_otorgada' => $this->calcularExperienciaOtorgada()
        ];
    }

    /**
     * Obtiene estadísticas de una clase específica
     */
    public function getEstadisticasClase(int $claseId): array
    {
        $clase = $this->clases()->find($claseId);
        
        if (!$clase) {
            return [];
        }

        $estudiantes = $clase->inscripciones()->where('activo', true)->count();
        $actividades = $clase->actividades()->count();
        $misiones = $clase->misiones()->count();
        $personajes = $clase->personajes()->count();

        return [
            'clase_nombre' => $clase->nombre,
            'total_estudiantes' => $estudiantes,
            'estudiantes_con_personaje' => $personajes,
            'total_actividades' => $actividades,
            'actividades_activas' => $clase->actividades()->where('activa', true)->count(),
            'total_misiones' => $misiones,
            'misiones_activas' => $clase->misiones()->where('activa', true)->count(),
            'promedio_nivel' => $clase->personajes()->avg('nivel') ?? 0,
            'total_experiencia_clase' => $clase->personajes()->sum('experiencia'),
            'comportamientos_registrados' => $clase->comportamientos()->count(),
            'asistencias_registradas' => $clase->asistencias()->count()
        ];
    }

    /**
     * Calcula la experiencia total otorgada por el docente
     */
    private function calcularExperienciaOtorgada(): int
    {
        $clasesIds = $this->clases()->pluck('id');
        
        return Actividad::whereIn('id_clase', $clasesIds)
            ->sum('puntos_experiencia');
    }

    // ==========================================
    // MÉTODOS DE REPORTES
    // ==========================================

    /**
     * Genera reporte de rendimiento de estudiantes
     */
    public function getReporteRendimientoEstudiantes(): array
    {
        $estudiantes = $this->misEstudiantes()->get();
        $reporte = [];

        foreach ($estudiantes as $estudiante) {
            $estadisticas = $estudiante->getEstadisticasGenerales();
            $reporte[] = [
                'estudiante' => [
                    'id' => $estudiante->id,
                    'nombre' => $estudiante->usuario->nombre,
                    'codigo' => $estudiante->codigo_estudiante,
                    'grado' => $estudiante->grado,
                    'seccion' => $estudiante->seccion
                ],
                'estadisticas' => $estadisticas
            ];
        }

        return $reporte;
    }

    /**
     * Genera reporte de actividad por clase
     */
    public function getReporteActividadClases(): array
    {
        $clases = $this->clases()->get();
        $reporte = [];

        foreach ($clases as $clase) {
            $reporte[] = [
                'clase' => [
                    'id' => $clase->id,
                    'nombre' => $clase->nombre,
                    'grado' => $clase->grado,
                    'seccion' => $clase->seccion,
                    'activa' => $clase->activo
                ],
                'estadisticas' => $this->getEstadisticasClase($clase->id)
            ];
        }

        return $reporte;
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope para docentes con clases activas
     */
    public function scopeConClasesActivas($query)
    {
        return $query->whereHas('clases', function($q) {
            $q->where('activo', true);
        });
    }

    /**
     * Scope para docentes por especialidad
     */
    public function scopePorEspecialidad($query, string $especialidad)
    {
        return $query->where('especialidad', $especialidad);
    }
}