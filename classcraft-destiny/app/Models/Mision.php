<?php

// ===== app/Models/Mision.php =====
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mision extends Model
{
    use HasFactory;

    protected $table = 'mision';

    protected $fillable = [
        'titulo',
        'descripcion',
        'objetivos',
        'id_clase',
        'fecha_inicio',
        'fecha_limite',
        'recompensa_xp',
        'recompensa_monedas',
        'activa',
        'orden',
        'requisitos',
        'tipo_mision',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_limite' => 'datetime',
        'recompensa_xp' => 'integer',
        'recompensa_monedas' => 'integer',
        'activa' => 'boolean',
        'orden' => 'integer',
        'objetivos' => 'array',
        'requisitos' => 'array',
    ];

    /**
     * Relación con clase
     */
    public function clase()
    {
        return $this->belongsTo(Clase::class, 'id_clase');
    }

    /**
     * Relación con actividades
     */
    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'id_mision');
    }

    /**
     * Relación con progreso de estudiantes
     */
    public function progresos()
    {
        return $this->hasMany(ProgresoMision::class, 'id_mision');
    }

    /**
     * Verificar si la misión está disponible
     */
    public function estaDisponible()
    {
        $ahora = now();
        
        return $this->activa &&
               ($this->fecha_inicio === null || $ahora->gte($this->fecha_inicio)) &&
               ($this->fecha_limite === null || $ahora->lte($this->fecha_limite));
    }

    /**
     * Obtener progreso de un estudiante específico
     */
    public function progresoDeEstudiante($estudianteId)
    {
        return $this->progresos()
            ->where('id_estudiante', $estudianteId)
            ->first();
    }

    /**
     * Verificar si un estudiante completó la misión
     */
    public function completadaPorEstudiante($estudianteId)
    {
        $progreso = $this->progresoDeEstudiante($estudianteId);
        return $progreso && $progreso->completada;
    }

    /**
     * Scope para misiones activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    /**
     * Scope para misiones disponibles
     */
    public function scopeDisponibles($query)
    {
        $ahora = now();
        
        return $query->where('activa', true)
            ->where(function($q) use ($ahora) {
                $q->whereNull('fecha_inicio')
                  ->orWhere('fecha_inicio', '<=', $ahora);
            })
            ->where(function($q) use ($ahora) {
                $q->whereNull('fecha_limite')
                  ->orWhere('fecha_limite', '>=', $ahora);
            });
    }

    /**
     * Obtener estadísticas de la misión
     */
    public function getEstadisticasAttribute()
    {
        $totalEstudiantes = $this->clase->estudiantesActivos()->count();
        $estudiantesCompletaron = $this->progresos()->where('completada', true)->count();
        
        return [
            'total_estudiantes' => $totalEstudiantes,
            'estudiantes_completaron' => $estudiantesCompletaron,
            'porcentaje_completion' => $totalEstudiantes > 0 ? 
                round(($estudiantesCompletaron / $totalEstudiantes) * 100, 2) : 0,
            'promedio_progreso' => $this->progresos()->avg('porcentaje_completado') ?? 0,
        ];
    }
}
