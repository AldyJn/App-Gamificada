<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificacion';

    protected $fillable = [
        'id_usuario',
        'titulo',
        'mensaje',
        'tipo',
        'leida',
        'datos',
        'accion_url'
    ];

    protected $casts = [
        'leida' => 'boolean',
        'datos' => 'array'
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}