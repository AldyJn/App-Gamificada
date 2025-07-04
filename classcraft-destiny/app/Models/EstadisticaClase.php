<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstadisticaClase extends Model
{
    use HasFactory;

    protected $table = 'estadistica_clase';

    protected $fillable = [
        'id_clase',
        'promedio_nivel',
        'promedio_participacion',
        'distribucion_niveles'
    ];

    protected $casts = [
        'promedio_nivel' => 'decimal:2',
        'promedio_participacion' => 'decimal:2',
        'distribucion_niveles' => 'array'
    ];

    public function clase(): BelongsTo
    {
        return $this->belongsTo(Clase::class, 'id_clase');
    }
}
