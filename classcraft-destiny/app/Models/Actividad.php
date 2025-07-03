<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Actividad extends Model
{
    use HasFactory;

    protected $table = 'actividad';

    protected $fillable = [
        'titulo',
        'descripcion',
        'instrucciones',
        'id_clase',
        'id_tipo_actividad',
        'id_mision',
        'puntos_xp',
        'puntos_moneda',
        'fecha_apertura',
        'fecha_cierre',
        'tiempo_limite',
        'intentos_maximos',
        'recursos_adjuntos',
        'criterios_evaluacion',
        'publicada',
        'configuracion',
    ];

    protected $casts = [
        'fecha_apertura' => 'datetime',
        'fecha_cierre' => 'datetime',
        'tiempo_limite' => 'integer',
        'intentos_maximos' => 'integer',
        'puntos_xp' => 'integer',
        'puntos_moneda' => 'integer',
        'publicada' => 'boolean',
        'recursos_adjuntos' => 'array',
        'criterios_evaluacion' => 'array',
        'configuracion' => 'array',
    ];

    /**
     * Relación con clase
     */
    public function clase()
    {
        return $this->belongsTo(Clase::class, 'id_clase');
    }

    /**
     * Relación con tipo de actividad
     */
    public function tipoActividad()
    {
        return $this->belongsTo(TipoActividad::class, 'id_tipo_actividad');
    }

    /**
     * Relación con misión
     */
    public function mision()
    {
        return $this->belongsTo(Mision::class, 'id_mision');
    }

    /**
     * Relación con entregas
     */
    public function entregas()
    {
        return $this->hasMany(EntregaActividad::class, 'id_actividad');
    }

    /**
     * Verificar si la actividad está disponible
     */
    public function estaDisponible()
    {
        $ahora = now();
        
        return $this->publicada &&
               ($this->fecha_apertura === null || $ahora->gte($this->fecha_apertura)) &&
               ($this->fecha_cierre === null || $ahora->lte($this->fecha_cierre));
    }

    /**
     * Verificar si la actividad ha expirado
     */
    public function haExpirado()
    {
        return $this->fecha_cierre && now()->gt($this->fecha_cierre);
    }

    /**
     * Obtener días restantes para la entrega
     */
    public function diasRestantes()
    {
        if (!$this->fecha_cierre) return null;
        
        $diasRestantes = now()->diffInDays($this->fecha_cierre, false);
        return $diasRestantes >= 0 ? $diasRestantes : 0;
    }

    /**
     * Obtener entrega de un estudiante específico
     */
    public function entregaDeEstudiante($estudianteId)
    {
        return $this->entregas()
            ->where('id_estudiante', $estudianteId)
            ->latest()
            ->first();
    }

    /**
     * Verificar si un estudiante puede hacer entrega
     */
    public function puedeEntregar($estudianteId)
    {
        if (!$this->estaDisponible()) return false;
        
        $entregas = $this->entregas()
            ->where('id_estudiante', $estudianteId)
            ->count();
            
        return $this->intentos_maximos === null || $entregas < $this->intentos_maximos;
    }

    /**
     * Scope para actividades publicadas
     */
    public function scopePublicadas($query)
    {
        return $query->where('publicada', true);
    }

    /**
     * Scope para actividades disponibles
     */
    public function scopeDisponibles($query)
    {
        $ahora = now();
        
        return $query->where('publicada', true)
            ->where(function($q) use ($ahora) {
                $q->whereNull('fecha_apertura')
                  ->orWhere('fecha_apertura', '<=', $ahora);
            })
            ->where(function($q) use ($ahora) {
                $q->whereNull('fecha_cierre')
                  ->orWhere('fecha_cierre', '>=', $ahora);
            });
    }

    /**
     * Scope para actividades por clase
     */
    public function scopePorClase($query, $claseId)
    {
        return $query->where('id_clase', $claseId);
    }

    /**
     * Obtener estadísticas de la actividad
     */
    public function getEstadisticasAttribute()
    {
        $totalEntregas = $this->entregas()->count();
        $estudiantesClase = $this->clase->estudiantesActivos()->count();
        
        return [
            'total_entregas' => $totalEntregas,
            'estudiantes_clase' => $estudiantesClase,
            'porcentaje_completion' => $estudiantesClase > 0 ? 
                round(($totalEntregas / $estudiantesClase) * 100, 2) : 0,
            'calificacion_promedio' => $this->entregas()->avg('puntuacion'),
            'entregas_a_tiempo' => $this->entregas()
                ->where('fecha_entrega', '<=', $this->fecha_cierre)
                ->count(),
        ];
    }
}
