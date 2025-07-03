<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;

    protected $table = 'docente';

    protected $fillable = [
        'id_usuario',
        'especialidad',
        'biografia',
        'anos_experiencia',
        'certificaciones',
    ];

    protected $casts = [
        'certificaciones' => 'array',
        'anos_experiencia' => 'integer',
    ];

    /**
     * Relación con usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    /**
     * Relación con clases que enseña
     */
    public function clases()
    {
        return $this->hasMany(Clase::class, 'id_docente');
    }

    /**
     * Obtener todas las actividades creadas por el docente
     */
    public function actividades()
    {
        return $this->hasManyThrough(Actividad::class, Clase::class, 'id_docente', 'id_clase');
    }

    /**
     * Obtener todos los estudiantes del docente
     */
    public function estudiantes()
    {
        return $this->hasManyThrough(
            Estudiante::class,
            InscripcionClase::class,
            'id_clase',
            'id',
            'id',
            'id_estudiante'
        )->whereHas('clase', function($q) {
            $q->where('id_docente', $this->id);
        });
    }

    /**
     * Obtener estadísticas del docente
     */
    public function getEstadisticasAttribute()
    {
        return [
            'total_clases' => $this->clases()->count(),
            'total_estudiantes' => $this->estudiantes()->count(),
            'total_actividades' => $this->actividades()->count(),
            'clases_activas' => $this->clases()->where('activa', true)->count(),
        ];
    }
}
