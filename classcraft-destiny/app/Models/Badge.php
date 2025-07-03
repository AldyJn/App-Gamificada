<?php
// ===== app/Models/Badge.php =====
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $table = 'badge';

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'color',
        'criterio',
        'activo',
        'categoria',
        'rareza',
    ];

    protected $casts = [
        'criterio' => 'array',
        'activo' => 'boolean',
    ];

    /**
     * Relación muchos a muchos con estudiantes
     */
    public function estudiantes()
    {
        return $this->belongsToMany(
            Estudiante::class,
            'estudiante_badge',
            'id_badge',
            'id_estudiante'
        )->withPivot(['fecha_obtencion', 'notificado'])
         ->withTimestamps();
    }

    /**
     * Verificar si un estudiante cumple los criterios para este badge
     */
    public function cumpleCriterios($estudiante, $clase = null)
    {
        $criterio = $this->criterio;
        
        if (!$criterio || !is_array($criterio)) return false;
        
        switch ($criterio['tipo'] ?? '') {
            case 'automatico':
                return true; // Se otorga automáticamente en eventos específicos
                
            case 'contador':
                return $this->verificarContador($estudiante, $criterio);
                
            case 'comportamiento':
                return $this->verificarComportamiento($estudiante, $criterio, $clase);
                
            case 'puntuacion':
                return $this->verificarPuntuacion($estudiante, $criterio);
                
            case 'asistencia_consecutiva':
                return $this->verificarAsistenciaConsecutiva($estudiante, $criterio, $clase);
                
            default:
                return false;
        }
    }

    /**
     * Verificar criterio de contador
     */
    private function verificarContador($estudiante, $criterio)
    {
        $evento = $criterio['evento'] ?? '';
        $cantidadRequerida = $criterio['cantidad'] ?? 1;
        
        switch ($evento) {
            case 'actividades_completadas':
                $cantidad = $estudiante->entregas()->whereNotNull('fecha_entrega')->count();
                break;
                
            default:
                return false;
        }
        
        return $cantidad >= $cantidadRequerida;
    }

    /**
     * Verificar criterio de comportamiento
     */
    private function verificarComportamiento($estudiante, $criterio, $clase)
    {
        $nombreComportamiento = $criterio['nombre'] ?? '';
        $cantidadRequerida = $criterio['cantidad'] ?? 1;
        
        $query = $estudiante->comportamientos()
            ->whereHas('tipoComportamiento', function($q) use ($nombreComportamiento) {
                $q->where('nombre', $nombreComportamiento);
            });
            
        if ($clase) {
            $query->where('id_clase', $clase->id);
        }
        
        return $query->count() >= $cantidadRequerida;
    }

    /**
     * Verificar criterio de puntuación
     */
    private function verificarPuntuacion($estudiante, $criterio)
    {
        $puntuacionMinima = $criterio['minima'] ?? 100;
        
        $entregas = $estudiante->entregas()
            ->where('puntuacion', '>=', $puntuacionMinima)
            ->count();
            
        return $entregas > 0;
    }

    /**
     * Verificar criterio de asistencia consecutiva
     */
    private function verificarAsistenciaConsecutiva($estudiante, $criterio, $clase)
    {
        $diasRequeridos = $criterio['dias'] ?? 30;
        
        if (!$clase) return false;
        
        // Obtener últimas asistencias del estudiante en la clase
        $asistencias = $estudiante->asistencias()
            ->where('id_clase', $clase->id)
            ->where('presente', true)
            ->orderByDesc('fecha')
            ->take($diasRequeridos)
            ->pluck('fecha')
            ->toArray();
            
        if (count($asistencias) < $diasRequeridos) return false;
        
        // Verificar que sean días consecutivos
        $fechaActual = \Carbon\Carbon::parse($asistencias[0]);
        for ($i = 1; $i < count($asistencias); $i++) {
            $fechaAnterior = \Carbon\Carbon::parse($asistencias[$i]);
            if ($fechaActual->diffInDays($fechaAnterior) > 1) {
                return false;
            }
            $fechaActual = $fechaAnterior;
        }
        
        return true;
    }

    /**
     * Otorgar badge a un estudiante
     */
    public function otorgarA($estudiante)
    {
        $existe = $this->estudiantes()
            ->where('id_estudiante', $estudiante->id)
            ->exists();
            
        if (!$existe) {
            $this->estudiantes()->attach($estudiante->id, [
                'fecha_obtencion' => now(),
                'notificado' => false,
            ]);
            
            return true;
        }
        
        return false;
    }

    /**
     * Scope para badges activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para badges por categoría
     */
    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }
}