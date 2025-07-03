<?php
// ===== app/Models/RegistroComportamiento.php =====
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroComportamiento extends Model
{
    use HasFactory;

    protected $table = 'registro_comportamiento';

    protected $fillable = [
        'id_estudiante',
        'id_clase',
        'id_tipo_comportamiento',
        'id_docente',
        'descripcion',
        'puntos_otorgados',
        'fecha_registro',
        'contexto_adicional',
        'publico',
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
        'puntos_otorgados' => 'integer',
        'contexto_adicional' => 'array',
        'publico' => 'boolean',
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
     * Relación con tipo de comportamiento
     */
    public function tipoComportamiento()
    {
        return $this->belongsTo(TipoComportamiento::class, 'id_tipo_comportamiento');
    }

    /**
     * Relación con docente que registró
     */
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'id_docente');
    }

    /**
     * Aplicar efectos del comportamiento al personaje
     */
    public function aplicarEfectos()
    {
        $personaje = $this->estudiante->personajeEnClase($this->id_clase);
        
        if (!$personaje) return;
        
        $tipo = $this->tipoComportamiento;
        
        // Aplicar puntos de experiencia/monedas
        if ($this->puntos_otorgados > 0) {
            $personaje->ganarExperiencia(
                $this->puntos_otorgados,
                "Comportamiento: {$tipo->descripcion}"
            );
            
            $personaje->ajustarMonedas(
                $this->puntos_otorgados,
                "Comportamiento: {$tipo->descripcion}",
                'ganancia'
            );
        }
        
        // Aplicar efectos negativos a la salud si es comportamiento negativo
        if ($tipo->tipo === 'negativo') {
            $perdidaSalud = abs($this->puntos_otorgados) * 2; // Convertir a positivo para restar salud
            $personaje->ajustarSalud(-$perdidaSalud, "Comportamiento negativo: {$tipo->descripcion}");
        }
    }

    /**
     * Scope para comportamientos positivos
     */
    public function scopePositivos($query)
    {
        return $query->whereHas('tipoComportamiento', function($q) {
            $q->where('tipo', 'positivo');
        });
    }

    /**
     * Scope para comportamientos negativos
     */
    public function scopeNegativos($query)
    {
        return $query->whereHas('tipoComportamiento', function($q) {
            $q->where('tipo', 'negativo');
        });
    }

    /**
     * Scope para comportamientos públicos
     */
    public function scopePublicos($query)
    {
        return $query->where('publico', true);
    }
}