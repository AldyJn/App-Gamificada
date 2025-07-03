<?php

// ===== app/Models/Asistencia.php =====
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencia';

    protected $fillable = [
        'id_estudiante',
        'id_clase',
        'fecha',
        'presente',
        'tarde',
        'observaciones',
        'registrado_por',
    ];

    protected $casts = [
        'fecha' => 'date',
        'presente' => 'boolean',
        'tarde' => 'boolean',
    ];

    /**
     * Relación con estudiante
     */
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante');
    }

    /**
     * Relación con clase
     */
    public function clase()
    {
        return $this->belongsTo(Clase::class, 'id_clase');
    }

    /**
     * Relación con quien registró la asistencia
     */
    public function registradoPor()
    {
        return $this->belongsTo(Usuario::class, 'registrado_por');
    }

    /**
     * Aplicar efectos de asistencia al personaje
     */
    public function aplicarEfectos()
    {
        $personaje = $this->estudiante->personajeEnClase($this->id_clase);
        
        if (!$personaje) return;
        
        if ($this->presente) {
            // Ganar XP por asistencia
            $xpAsistencia = $this->clase->obtenerConfiguracion('xp_por_asistencia', 5);
            $personaje->ganarExperiencia($xpAsistencia, 'Asistencia a clase');
            
            // Regenerar salud si no llegó tarde
            if (!$this->tarde) {
                $regeneracionSalud = $this->clase->obtenerConfiguracion('regeneracion_salud_diaria', 10);
                $personaje->ajustarSalud($regeneracionSalud, 'Regeneración diaria por asistencia');
            }
        }
    }

    /**
     * Scope para asistencias presentes
     */
    public function scopePresentes($query)
    {
        return $query->where('presente', true);
    }

    /**
     * Scope para ausencias
     */
    public function scopeAusentes($query)
    {
        return $query->where('presente', false);
    }

    /**
     * Scope para llegadas tarde
     */
    public function scopeTarde($query)
    {
        return $query->where('tarde', true)->where('presente', true);
    }

    /**
     * Scope para rango de fechas
     */
    public function scopeEntreFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
    }
}
