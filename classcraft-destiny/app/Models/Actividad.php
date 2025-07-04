<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Actividad extends Model
{
    use HasFactory;

    protected $table = 'actividad';

    protected $fillable = [
        'id_clase',
        'id_tipo_actividad',
        'id_mision',
        'titulo',
        'descripcion',
        'fecha_inicio',
        'fecha_entrega',
        'puntos_experiencia',
        'puntos_moneda',
        'activa'
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_entrega' => 'datetime',
        'puntos_experiencia' => 'integer',
        'puntos_moneda' => 'integer',
        'activa' => 'boolean'
    ];

    // ==========================================
    // RELACIONES
    // ==========================================

    /**
     * Relación con la clase
     */
    public function clase(): BelongsTo
    {
        return $this->belongsTo(Clase::class, 'id_clase');
    }

    /**
     * Relación con el tipo de actividad
     */
    public function tipoActividad(): BelongsTo
    {
        return $this->belongsTo(TipoActividad::class, 'id_tipo_actividad');
    }

    /**
     * Relación con la misión (opcional)
     */
    public function mision(): BelongsTo
    {
        return $this->belongsTo(Mision::class, 'id_mision');
    }

    /**
     * Relación con entregas de estudiantes
     */
    public function entregas(): HasMany
    {
        return $this->hasMany(EntregaActividad::class, 'id_actividad');
    }

    // ==========================================
    // MÉTODOS DE VERIFICACIÓN DE ESTADO
    // ==========================================

    /**
     * Verifica si la actividad está activa
     */
    public function estaActiva(): bool
    {
        return $this->activa;
    }

    /**
     * Verifica si la actividad está disponible (dentro del período)
     */
    public function estaDisponible(): bool
    {
        if (!$this->activa) {
            return false;
        }

        $ahora = Carbon::now();
        
        // Si no hay fecha de inicio, está disponible
        if (!$this->fecha_inicio) {
            return true;
        }

        // Si hay fecha de inicio pero no ha llegado
        if ($this->fecha_inicio > $ahora) {
            return false;
        }

        // Si hay fecha de entrega y ya pasó
        if ($this->fecha_entrega && $this->fecha_entrega < $ahora) {
            return false;
        }

        return true;
    }

    /**
     * Verifica si la actividad está vencida
     */
    public function estaVencida(): bool
    {
        if (!$this->fecha_entrega) {
            return false;
        }

        return $this->fecha_entrega < Carbon::now();
    }

    /**
     * Verifica si la actividad aún no ha comenzado
     */
    public function noHaComenzado(): bool
    {
        if (!$this->fecha_inicio) {
            return false;
        }

        return $this->fecha_inicio > Carbon::now();
    }

    // ==========================================
    // MÉTODOS DE ESTADÍSTICAS
    // ==========================================

    /**
     * Obtiene el número total de entregas
     */
    public function getTotalEntregasAttribute(): int
    {
        return $this->entregas()->count();
    }

    /**
     * Obtiene el número de entregas calificadas
     */
    public function getTotalEntregasCalificadasAttribute(): int
    {
        return $this->entregas()->whereNotNull('nota')->count();
    }

    /**
     * Obtiene el número de entregas pendientes de calificar
     */
    public function getTotalEntregasPendientesAttribute(): int
    {
        return $this->entregas()->whereNull('nota')->count();
    }

    /**
     * Obtiene el promedio de notas
     */
    public function getPromedioNotasAttribute(): float
    {
        return $this->entregas()->whereNotNull('nota')->avg('nota') ?? 0;
    }

    /**
     * Obtiene el porcentaje de participación
     */
    public function getPorcentajeParticipacionAttribute(): float
    {
        $totalEstudiantes = $this->clase->total_estudiantes_activos;
        
        if ($totalEstudiantes === 0) {
            return 0;
        }

        return ($this->total_entregas / $totalEstudiantes) * 100;
    }

    /**
     * Obtiene el tiempo restante para la entrega
     */
    public function getTiempoRestanteAttribute(): ?string
    {
        if (!$this->fecha_entrega) {
            return null;
        }

        $ahora = Carbon::now();
        
        if ($this->fecha_entrega < $ahora) {
            return 'Vencida';
        }

        return $ahora->diffForHumans($this->fecha_entrega, true);
    }

    // ==========================================
    // MÉTODOS DE ESTADO
    // ==========================================

    /**
     * Obtiene el estado actual de la actividad
     */
    public function getEstadoAttribute(): string
    {
        if (!$this->activa) {
            return 'inactiva';
        }

        if ($this->noHaComenzado()) {
            return 'proxima';
        }

        if ($this->estaVencida()) {
            return 'vencida';
        }

        return 'activa';
    }

    /**
     * Obtiene el color del estado
     */
    public function getColorEstadoAttribute(): string
    {
        return match($this->estado) {
            'activa' => 'success',
            'proxima' => 'warning',
            'vencida' => 'error',
            'inactiva' => 'grey',
            default => 'primary'
        };
    }

    // ==========================================
    // MÉTODOS DE ENTREGAS
    // ==========================================

    /**
     * Verifica si un estudiante ha entregado la actividad
     */
    public function estudianteHaEntregado(Estudiante $estudiante): bool
    {
        return $this->entregas()->where('id_estudiante', $estudiante->id)->exists();
    }

    /**
     * Obtiene la entrega de un estudiante específico
     */
    public function entregaDeEstudiante(Estudiante $estudiante): ?EntregaActividad
    {
        return $this->entregas()->where('id_estudiante', $estudiante->id)->first();
    }

    /**
     * Obtiene estudiantes que no han entregado
     */
    public function estudiantesSinEntrega()
    {
        $estudiantesConEntrega = $this->entregas()->pluck('id_estudiante');
        
        return $this->clase->estudiantes()
            ->wherePivot('activo', true)
            ->whereNotIn('estudiante.id', $estudiantesConEntrega);
    }

    // ==========================================
    // MÉTODOS DE PUNTUACIÓN
    // ==========================================

    /**
     * Calcula los puntos totales de la actividad
     */
    public function getPuntosTotalesAttribute(): int
    {
        return $this->puntos_experiencia + $this->puntos_moneda;
    }

    /**
     * Otorga puntos a un estudiante por completar la actividad
     */
    public function otorgarPuntosAEstudiante(Estudiante $estudiante): void
    {
        $personaje = $estudiante->personajeEnClase($this->id_clase);
        
        if ($personaje && $this->puntos_experiencia > 0) {
            $personaje->ganarExperiencia($this->puntos_experiencia);
        }

        if ($this->puntos_moneda > 0) {
            TransaccionMoneda::create([
                'id_estudiante' => $estudiante->id,
                'id_clase' => $this->id_clase,
                'tipo' => 'ingreso',
                'cantidad' => $this->puntos_moneda,
                'descripcion' => "Actividad: {$this->titulo}",
                'referencia_tipo' => 'actividad',
                'referencia_id' => $this->id
            ]);
        }
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope para actividades activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    /**
     * Scope para actividades disponibles
     */
    public function scopeDisponibles($query)
    {
        $ahora = Carbon::now();
        
        return $query->where('activa', true)
            ->where(function($q) use ($ahora) {
                $q->whereNull('fecha_inicio')
                  ->orWhere('fecha_inicio', '<=', $ahora);
            })
            ->where(function($q) use ($ahora) {
                $q->whereNull('fecha_entrega')
                  ->orWhere('fecha_entrega', '>', $ahora);
            });
    }

    /**
     * Scope para actividades vencidas
     */
    public function scopeVencidas($query)
    {
        return $query->where('fecha_entrega', '<', Carbon::now());
    }

    /**
     * Scope para actividades próximas
     */
    public function scopeProximas($query)
    {
        return $query->where('fecha_inicio', '>', Carbon::now());
    }

    /**
     * Scope para actividades de una clase específica
     */
    public function scopeDeClase($query, int $claseId)
    {
        return $query->where('id_clase', $claseId);
    }

    /**
     * Scope para actividades de una misión específica
     */
    public function scopeDeMision($query, int $misionId)
    {
        return $query->where('id_mision', $misionId);
    }

    /**
     * Scope para actividades por tipo
     */
    public function scopePorTipo($query, int $tipoId)
    {
        return $query->where('id_tipo_actividad', $tipoId);
    }
}