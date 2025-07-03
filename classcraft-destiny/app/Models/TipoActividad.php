<?php

// ===== app/Models/TipoActividad.php =====
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoActividad extends Model
{
    use HasFactory;

    protected $table = 'tipo_actividad';

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'color',
        'puntos_base',
        'permite_intentos_multiples',
        'requiere_entrega',
    ];

    protected $casts = [
        'puntos_base' => 'integer',
        'permite_intentos_multiples' => 'boolean',
        'requiere_entrega' => 'boolean',
    ];

    /**
     * Relación con actividades
     */
    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'id_tipo_actividad');
    }

    /**
     * Scope para tipos que requieren entrega
     */
    public function scopeRequierenEntrega($query)
    {
        return $query->where('requiere_entrega', true);
    }

    /**
     * Scope para tipos que permiten múltiples intentos
     */
    public function scopeMultiplesIntentos($query)
    {
        return $query->where('permite_intentos_multiples', true);
    }
}
