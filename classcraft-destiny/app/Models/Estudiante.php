<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiante';

    protected $fillable = [
        'id_usuario',
        'codigo_estudiante',
        'grado',
        'seccion'
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
     * Relación con inscripciones en clases
     */
    public function inscripciones(): HasMany
    {
        return $this->hasMany(InscripcionClase::class, 'id_estudiante');
    }

    /**
     * Relación muchos a muchos con clases a través de inscripciones
     */
    public function clases(): BelongsToMany
    {
        return $this->belongsToMany(
            Clase::class,
            'inscripcion_clase',
            'id_estudiante',
            'id_clase'
        )->withPivot([
            'fecha_ingreso',
            'activo'
        ])->withTimestamps();
    }

    /**
     * Relación con personajes
     */
    public function personajes(): HasMany
    {
        return $this->hasMany(Personaje::class, 'id_estudiante');
    }

    /**
     * Relación con entregas de actividades
     */
    public function entregas(): HasMany
    {
        return $this->hasMany(EntregaActividad::class, 'id_estudiante');
    }

    /**
     * Relación con registros de comportamiento
     */
    public function comportamientos(): HasMany
    {
        return $this->hasMany(RegistroComportamiento::class, 'id_estudiante');
    }

    /**
     * Relación con asistencias
     */
    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'id_estudiante');
    }

    /**
     * Relación con transacciones de moneda
     */
    public function transaccionesMoneda(): HasMany
    {
        return $this->hasMany(TransaccionMoneda::class, 'id_estudiante');
    }

    /**
     * Relación con progreso de misiones
     */
    public function progresoMisiones(): HasMany
    {
        return $this->hasMany(ProgresoMision::class, 'id_estudiante');
    }

    /**
     * Relación con badges obtenidos
     */
    public function badges(): HasMany
    {
        return $this->hasMany(EstudianteBadge::class, 'id_estudiante');
    }

    // ==========================================
    // MÉTODOS DE CONVENIENCIA
    // ==========================================

    /**
     * Obtiene el personaje del estudiante en una clase específica
     */
    public function personajeEnClase(int $claseId): ?Personaje
    {
        return $this->personajes()->where('id_clase', $claseId)->first();
    }

    /**
     * Obtiene las clases activas del estudiante
     */
    public function clasesActivas()
    {
        return $this->clases()->wherePivot('activo', true);
    }

    /**
     * Obtiene la inscripción en una clase específica
     */
    public function inscripcionEnClase(int $claseId): ?InscripcionClase
    {
        return $this->inscripciones()->where('id_clase', $claseId)->first();
    }

    /**
     * Verifica si está inscrito en una clase específica
     */
    public function estaInscritoEnClase(int $claseId): bool
    {
        return $this->inscripciones()
            ->where('id_clase', $claseId)
            ->where('activo', true)
            ->exists();
    }

    /**
     * Obtiene el total de experiencia del estudiante
     */
    public function getTotalExperienciaAttribute(): int
    {
        return $this->personajes()->sum('experiencia');
    }

    /**
     * Obtiene el nivel promedio de los personajes del estudiante
     */
    public function getNivelPromedioAttribute(): float
    {
        $personajes = $this->personajes;
        if ($personajes->isEmpty()) {
            return 0;
        }

        return $personajes->avg('nivel');
    }

    /**
     * Obtiene el número de actividades completadas
     */
    public function getActividadesCompletadasAttribute(): int
    {
        return $this->entregas()->count();
    }

    /**
     * Obtiene el número de misiones completadas
     */
    public function getMisionesCompletadasAttribute(): int
    {
        return $this->progresoMisiones()->where('completada', true)->count();
    }

    /**
     * Obtiene el balance total de monedas del estudiante
     */
    public function getBalanceMonedaAttribute(): int
    {
        $ingresos = $this->transaccionesMoneda()
            ->where('tipo', 'ingreso')
            ->sum('cantidad');

        $gastos = $this->transaccionesMoneda()
            ->where('tipo', 'gasto')
            ->sum('cantidad');

        return $ingresos - $gastos;
    }

    // ==========================================
    // MÉTODOS DE ESTADÍSTICAS
    // ==========================================

    /**
     * Obtiene estadísticas generales del estudiante
     */
    public function getEstadisticasGenerales(): array
    {
        return [
            'total_experiencia' => $this->total_experiencia,
            'nivel_promedio' => $this->nivel_promedio,
            'actividades_completadas' => $this->actividades_completadas,
            'misiones_completadas' => $this->misiones_completadas,
            'balance_moneda' => $this->balance_moneda,
            'badges_obtenidos' => $this->badges()->count(),
            'clases_activas' => $this->clasesActivas()->count(),
            'comportamientos_positivos' => $this->comportamientos()
                ->whereHas('tipoComportamiento', function($q) {
                    $q->where('tipo', 'positivo');
                })->count(),
            'comportamientos_negativos' => $this->comportamientos()
                ->whereHas('tipoComportamiento', function($q) {
                    $q->where('tipo', 'negativo');
                })->count()
        ];
    }

    /**
     * Obtiene estadísticas del estudiante en una clase específica
     */
    public function getEstadisticasEnClase(int $claseId): array
    {
        $personaje = $this->personajeEnClase($claseId);
        $progreso = $this->progresoMisiones()->whereHas('mision', function($q) use ($claseId) {
            $q->where('id_clase', $claseId);
        })->get();

        return [
            'personaje' => $personaje ? [
                'nombre' => $personaje->nombre,
                'nivel' => $personaje->nivel,
                'experiencia' => $personaje->experiencia,
                'clase_rpg' => $personaje->claseRpg->nombre ?? 'Sin clase'
            ] : null,
            'actividades_completadas' => $this->entregas()
                ->whereHas('actividad', function($q) use ($claseId) {
                    $q->where('id_clase', $claseId);
                })->count(),
            'misiones_completadas' => $progreso->where('completada', true)->count(),
            'misiones_en_progreso' => $progreso->where('completada', false)->count(),
            'balance_moneda_clase' => $this->transaccionesMoneda()
                ->where('id_clase', $claseId)
                ->sum('cantidad'),
            'badges_obtenidos_clase' => $this->badges()
                ->where('id_clase', $claseId)
                ->count()
        ];
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope para estudiantes con inscripciones activas
     */
    public function scopeConInscripcionesActivas($query)
    {
        return $query->whereHas('inscripciones', function($q) {
            $q->where('activo', true);
        });
    }

    /**
     * Scope para estudiantes de una clase específica
     */
    public function scopeDeClase($query, int $claseId)
    {
        return $query->whereHas('inscripciones', function($q) use ($claseId) {
            $q->where('id_clase', $claseId)->where('activo', true);
        });
    }

    /**
     * Scope para estudiantes por grado
     */
    public function scopePorGrado($query, string $grado)
    {
        return $query->where('grado', $grado);
    }

    /**
     * Scope para estudiantes por sección
     */
    public function scopePorSeccion($query, string $seccion)
    {
        return $query->where('seccion', $seccion);
    }
}