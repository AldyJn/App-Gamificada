<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SesionClase extends Model
{
    use HasFactory;

    protected $table = 'sesion_clase';

    protected $fillable = [
        'id_clase',
        'fecha_inicio',
        'fecha_fin',
        'activa',
        'duracion_planificada',
        'notas_sesion'
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'activa' => 'boolean',
        'duracion_planificada' => 'integer'
    ];

    public function clase(): BelongsTo
    {
        return $this->belongsTo(Clase::class, 'id_clase');
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'id_sesion');
    }
}