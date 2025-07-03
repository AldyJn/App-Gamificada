<?php
// ===== app/Models/NivelExperiencia.php =====
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NivelExperiencia extends Model
{
    use HasFactory;

    protected $table = 'nivel_experiencia';

    protected $fillable = [
        'nivel',
        'xp_requerida',
        'titulo',
        'beneficios',
    ];

    protected $casts = [
        'nivel' => 'integer',
        'xp_requerida' => 'integer',
        'beneficios' => 'array',
    ];

    /**
     * Obtener nivel basado en experiencia acumulada
     */
    public static function obtenerNivelPorExperiencia($experiencia)
    {
        $nivel = static::where('xp_requerida', '<=', $experiencia)
            ->orderByDesc('nivel')
            ->first();
            
        return $nivel ? $nivel->nivel : 1;
    }

    /**
     * Obtener experiencia requerida para un nivel específico
     */
    public static function obtenerXpRequerida($nivel)
    {
        $nivelData = static::where('nivel', $nivel)->first();
        return $nivelData ? $nivelData->xp_requerida : 0;
    }

    /**
     * Obtener siguiente nivel
     */
    public function siguienteNivel()
    {
        return static::where('nivel', '>', $this->nivel)
            ->orderBy('nivel')
            ->first();
    }

    /**
     * Obtener nivel anterior
     */
    public function nivelAnterior()
    {
        return static::where('nivel', '<', $this->nivel)
            ->orderByDesc('nivel')
            ->first();
    }

    /**
     * Scope para niveles por rango
     */
    public function scopeEnRango($query, $nivelMin, $nivelMax)
    {
        return $query->whereBetween('nivel', [$nivelMin, $nivelMax]);
    }
}