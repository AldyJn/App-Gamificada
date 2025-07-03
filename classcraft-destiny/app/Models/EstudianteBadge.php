<?php
// ===== app/Models/EstudianteBadge.php =====
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstudianteBadge extends Model
{
    use HasFactory;

    protected $table = 'estudiante_badge';

    protected $fillable = [
        'id_estudiante',
        'id_badge',
        'fecha_obtencion',
        'notificado',
    ];

    protected $casts = [
        'fecha_obtencion' => 'datetime',
        'notificado' => 'boolean',
    ];

    /**
     * Relación con estudiante
     */
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante');
    }

    /**
     * Relación con badge
     */
    public function badge()
    {
        return $this->belongsTo(Badge::class, 'id_badge');
    }

    /**
     * Marcar como notificado
     */
    public function marcarComoNotificado()
    {
        $this->notificado = true;
        $this->save();
    }

    /**
     * Scope para badges no notificados
     */
    public function scopeNoNotificados($query)
    {
        return $query->where('notificado', false);
    }

    /**
     * Scope para badges obtenidos recientemente
     */
    public function scopeRecientes($query, $dias = 7)
    {
        return $query->where('fecha_obtencion', '>=', now()->subDays($dias));
    }
}