<?php
// ===== app/Models/EntregaActividad.php =====
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntregaActividad extends Model
{
    use HasFactory;

    protected $table = 'entrega_actividad';

    protected $fillable = [
        'id_actividad',
        'id_estudiante',
        'contenido_respuesta',
        'archivos_adjuntos',
        'fecha_entrega',
        'puntuacion',
        'comentario_docente',
        'estado',
        'tiempo_empleado',
        'intento_numero',
    ];

    protected $casts = [
        'fecha_entrega' => 'datetime',
        'puntuacion' => 'decimal:2',
        'archivos_adjuntos' => 'array',
        'tiempo_empleado' => 'integer',
        'intento_numero' => 'integer',
    ];

    /**
     * Relación con actividad
     */
    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'id_actividad');
    }

    /**
     * Relación con estudiante
     */
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante');
    }

    /**
     * Verificar si la entrega fue a tiempo
     */
    public function fueATiempo()
    {
        if (!$this->actividad->fecha_cierre) return true;
        
        return $this->fecha_entrega <= $this->actividad->fecha_cierre;
    }

    /**
     * Obtener calificación en letra
     */
    public function getCalificacionLetraAttribute()
    {
        if ($this->puntuacion === null) return 'Sin calificar';
        
        if ($this->puntuacion >= 90) return 'A';
        if ($this->puntuacion >= 80) return 'B';
        if ($this->puntuacion >= 70) return 'C';
        if ($this->puntuacion >= 60) return 'D';
        return 'F';
    }

    /**
     * Scope para entregas calificadas
     */
    public function scopeCalificadas($query)
    {
        return $query->whereNotNull('puntuacion');
    }

    /**
     * Scope para entregas pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para entregas a tiempo
     */
    public function scopeATiempo($query)
    {
        return $query->whereHas('actividad', function($q) {
            $q->whereRaw('entrega_actividad.fecha_entrega <= actividad.fecha_cierre');
        });
    }
}