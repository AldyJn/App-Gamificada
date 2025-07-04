<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstadisticaPersonaje extends Model
{
    use HasFactory;

    protected $table = 'estadistica_personaje';

    protected $fillable = [
        'id_personaje',
        'misiones_completadas',
        'actividades_completadas'
    ];

    protected $casts = [
        'misiones_completadas' => 'integer',
        'actividades_completadas' => 'integer'
    ];

    public function personaje(): BelongsTo
    {
        return $this->belongsTo(Personaje::class, 'id_personaje');
    }
}