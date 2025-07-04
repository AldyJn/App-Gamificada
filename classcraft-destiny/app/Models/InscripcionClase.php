<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InscripcionClase extends Model
{
    use HasFactory;

    protected $table = 'inscripcion_clase';

    protected $fillable = [
        'id_clase',
        'id_estudiante',
        'fecha_ingreso',
        'activo',
        'datos_adicionales'
    ];

    protected $casts = [
        'fecha_ingreso' => 'datetime',
        'activo' => 'boolean',
        'datos_adicionales' => 'array'
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
     * Relación con el estudiante (usuario)
     */
    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_estudiante');
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope para inscripciones activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para inscripciones de una clase específica
     */
    public function scopeDeClase($query, $claseId)
    {
        return $query->where('id_clase', $claseId);
    }

    /**
     * Scope para inscripciones de un estudiante específico
     */
    public function scopeDeEstudiante($query, $estudianteId)
    {
        return $query->where('id_estudiante', $estudianteId);
    }

    // ==========================================
    // MÉTODOS DE UTILIDAD
    // ==========================================

    /**
     * Verificar si la inscripción está activa
     */
    public function estaActiva(): bool
    {
        return $this->activo;
    }

    /**
     * Desactivar la inscripción
     */
    public function desactivar(): bool
    {
        return $this->update(['activo' => false]);
    }

    /**
     * Activar la inscripción
     */
    public function activar(): bool
    {
        return $this->update(['activo' => true]);
    }

    /**
     * Obtener días desde la inscripción
     */
    public function diasDesdeInscripcion(): int
    {
        return $this->fecha_ingreso->diffInDays(now());
    }

    /**
     * Verificar si es una inscripción reciente (menos de 7 días)
     */
    public function esReciente(): bool
    {
        return $this->diasDesdeInscripcion() <= 7;
    }

    /**
     * Obtener datos adicionales específicos
     */
    public function getDatoAdicional($clave, $default = null)
    {
        return data_get($this->datos_adicionales, $clave, $default);
    }

    /**
     * Establecer un dato adicional
     */
    public function setDatoAdicional($clave, $valor): bool
    {
        $datos = $this->datos_adicionales ?? [];
        data_set($datos, $clave, $valor);
        
        return $this->update(['datos_adicionales' => $datos]);
    }

    // ==========================================
    // MÉTODOS ESTÁTICOS
    // ==========================================

    /**
     * Crear inscripción con validaciones
     */
    public static function crearInscripcion($claseId, $estudianteId, $datosAdicionales = []): self
    {
        // Verificar que no exista ya una inscripción activa
        $inscripcionExistente = self::where('id_clase', $claseId)
                                   ->where('id_estudiante', $estudianteId)
                                   ->where('activo', true)
                                   ->first();

        if ($inscripcionExistente) {
            throw new \Exception('El estudiante ya está inscrito en esta clase');
        }

        return self::create([
            'id_clase' => $claseId,
            'id_estudiante' => $estudianteId,
            'fecha_ingreso' => now(),
            'activo' => true,
            'datos_adicionales' => $datosAdicionales
        ]);
    }

    /**
     * Obtener estudiantes activos de una clase
     */
    public static function estudiantesActivosDeClase($claseId)
    {
        return self::with('estudiante')
                   ->where('id_clase', $claseId)
                   ->where('activo', true)
                   ->get()
                   ->pluck('estudiante');
    }

    /**
     * Obtener clases activas de un estudiante
     */
    public static function clasesActivasDeEstudiante($estudianteId)
    {
        return self::with('clase')
                   ->where('id_estudiante', $estudianteId)
                   ->where('activo', true)
                   ->get()
                   ->pluck('clase');
    }

    /**
     * Estadísticas de inscripciones por mes
     */
    public static function estadisticasPorMes($año = null)
    {
        $año = $año ?? date('Y');
        
        return self::selectRaw('MONTH(fecha_ingreso) as mes, COUNT(*) as total')
                   ->whereYear('fecha_ingreso', $año)
                   ->groupBy('mes')
                   ->orderBy('mes')
                   ->get()
                   ->mapWithKeys(function ($item) {
                       return [$item->mes => $item->total];
                   });
    }
}