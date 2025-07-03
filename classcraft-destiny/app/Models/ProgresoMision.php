<?php
// ===== app/Models/ProgresoMision.php =====
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgresoMision extends Model
{
    use HasFactory;

    protected $table = 'progreso_mision';

    protected $fillable = [
        'id_mision',
        'id_estudiante',
        'objetivos_completados',
        'porcentaje_completado',
        'completada',
        'fecha_completado',
        'recompensa_otorgada',
    ];

    protected $casts = [
        'objetivos_completados' => 'array',
        'porcentaje_completado' => 'decimal:2',
        'completada' => 'boolean',
        'fecha_completado' => 'datetime',
        'recompensa_otorgada' => 'boolean',
    ];

    /**
     * Relación con misión
     */
    public function mision()
    {
        return $this->belongsTo(Mision::class, 'id_mision');
    }

    /**
     * Relación con estudiante
     */
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante');
    }

    /**
     * Actualizar progreso
     */
    public function actualizarProgreso()
    {
        $objetivosTotales = count($this->mision->objetivos ?? []);
        $objetivosCompletados = count($this->objetivos_completados ?? []);
        
        if ($objetivosTotales > 0) {
            $this->porcentaje_completado = ($objetivosCompletados / $objetivosTotales) * 100;
            
            if ($this->porcentaje_completado >= 100 && !$this->completada) {
                $this->completada = true;
                $this->fecha_completado = now();
                $this->otorgarRecompensa();
            }
        }
        
        $this->save();
    }

    /**
     * Otorgar recompensa al completar la misión
     */
    private function otorgarRecompensa()
    {
        if ($this->recompensa_otorgada) return;
        
        $personaje = $this->estudiante->personajeEnClase($this->mision->id_clase);
        
        if ($personaje) {
            // Otorgar experiencia
            if ($this->mision->recompensa_xp > 0) {
                $personaje->ganarExperiencia(
                    $this->mision->recompensa_xp, 
                    "Misión completada: {$this->mision->titulo}"
                );
            }
            
            // Otorgar monedas
            if ($this->mision->recompensa_monedas > 0) {
                $personaje->ajustarMonedas(
                    $this->mision->recompensa_monedas,
                    "Misión completada: {$this->mision->titulo}",
                    'ganancia'
                );
            }
            
            $this->recompensa_otorgada = true;
            $this->save();
        }
    }

    /**
     * Marcar objetivo como completado
     */
    public function completarObjetivo($objetivoId)
    {
        $objetivosCompletados = $this->objetivos_completados ?? [];
        
        if (!in_array($objetivoId, $objetivosCompletados)) {
            $objetivosCompletados[] = $objetivoId;
            $this->objetivos_completados = $objetivosCompletados;
            $this->actualizarProgreso();
        }
    }
}