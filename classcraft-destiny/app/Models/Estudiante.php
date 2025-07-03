<?php
// ===== app/Models/EstadoUsuario.php =====
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
Use App\Models\Usuario;
Use App\Models\Personaje;
Use App\Models\EntregaActividad;
Use App\Models\Clase;
Use App\Models\RegistroComportamiento;
Use App\Models\Asistencia;
Use App\Models\Badge;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiante';

    protected $fillable = [
        'id_usuario',
        'codigo_estudiante',
        'grado',
        'seccion',
        'fecha_nacimiento',
        'telefono_contacto',
        'contacto_emergencia',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'contacto_emergencia' => 'array',
    ];

    /**
     * Relación con usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    /**
     * Relación muchos a muchos con clases
     */
    public function clases()
    {
        return $this->belongsToMany(
            Clase::class,
            'inscripcion_clase',
            'id_estudiante',
            'id_clase'
        )->withPivot(['fecha_inscripcion', 'activo', 'notas'])
         ->withTimestamps();
    }

    /**
     * Relación con personajes
     */
    public function personajes()
    {
        return $this->hasMany(Personaje::class, 'id_estudiante');
    }

    /**
     * Relación con entregas de actividades
     */
    public function entregas()
    {
        return $this->hasMany(EntregaActividad::class, 'id_estudiante');
    }

    /**
     * Relación con registros de comportamiento
     */
    public function comportamientos()
    {
        return $this->hasMany(RegistroComportamiento::class, 'id_estudiante');
    }

    /**
     * Relación con asistencias
     */
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'id_estudiante');
    }

    /**
     * Relación con badges obtenidos
     */
    public function badges()
    {
        return $this->belongsToMany(
            Badge::class,
            'estudiante_badge',
            'id_estudiante',
            'id_badge'
        )->withPivot(['fecha_obtencion', 'notificado'])
         ->withTimestamps();
    }

    /**
     * Obtener personaje de una clase específica
     */
    public function personajeEnClase($claseId)
    {
        return $this->personajes()->where('id_clase', $claseId)->first();
    }

    /**
     * Verificar si está inscrito en una clase
     */
    public function estaInscritoEn($claseId)
    {
        return $this->clases()
            ->where('clase.id', $claseId)
            ->wherePivot('activo', true)
            ->exists();
    }

    /**
     * Obtener estadísticas del estudiante
     */
    public function getEstadisticasAttribute()
    {
        return [
            'total_clases' => $this->clases()->wherePivot('activo', true)->count(),
            'total_entregas' => $this->entregas()->count(),
            'total_badges' => $this->badges()->count(),
            'promedio_calificaciones' => $this->entregas()->avg('puntuacion'),
            'nivel_promedio' => $this->personajes()->avg('nivel'),
        ];
    }
}
