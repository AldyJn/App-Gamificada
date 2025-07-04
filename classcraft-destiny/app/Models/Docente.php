<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Docente extends Model
{
    use HasFactory;

    protected $table = 'docente';

    protected $fillable = [
        'id_usuario',
        'especialidad'
    ];

    // ==========================================
    // RELACIONES
    // ==========================================

    /**
     * Relación con usuario
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    /**
     * Relación con clases que gestiona
     */
    public function clases(): HasMany
    {
        return $this->hasMany(Clase::class, 'docente_id'); // Corregido: usar docente_id
    }

    // ==========================================
    // MÉTODOS PRINCIPALES
    // ==========================================

    /**
     * Obtiene todas las clases activas del docente
     */
    public function clasesActivas()
    {
        return $this->clases()->where('activa', true);
    }

    /**
     * Método principal: Obtiene el total de estudiantes del docente
     */
    public function totalEstudiantes(): int
    {
        try {
            // Contar estudiantes únicos en todas las clases del docente
            $total = 0;
            $clases = $this->clases()->get();
            
            foreach ($clases as $clase) {
                // Contar estudiantes activos en cada clase usando la relación corregida
                $total += $clase->estudiantes()->count();
            }
            
            return $total;
        } catch (\Exception $e) {
            return 0; // Retorna 0 si hay error
        }
    }

    /**
     * Obtiene todos los estudiantes del docente (método auxiliar)
     */
    public function misEstudiantes()
    {
        try {
            // Obtener IDs de todas las clases del docente
            $clasesIds = $this->clases()->pluck('id');
            
            if ($clasesIds->isEmpty()) {
                return collect();
            }
            
            // Buscar estudiantes inscritos en esas clases
            return Usuario::whereHas('clasesComoEstudiante', function($query) use ($clasesIds) {
                $query->whereIn('clase.id', $clasesIds)
                      ->wherePivot('activo', true);
            })->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Obtiene el total de clases del docente
     */
    public function totalClases(): int
    {
        return $this->clases()->count();
    }

    /**
     * Obtiene el total de clases activas del docente
     */
    public function totalClasesActivas(): int
    {
        return $this->clasesActivas()->count();
    }

    /**
     * Verifica si el docente puede gestionar una clase específica
     */
    public function puedeGestionarClase(Clase $clase): bool
    {
        return $clase->docente_id === $this->usuario->id;
    }

    // ==========================================
    // MÉTODOS DE ESTADÍSTICAS
    // ==========================================

    /**
     * Obtiene estadísticas generales del docente
     */
    public function getEstadisticasGenerales(): array
    {
        try {
            return [
                'total_clases' => $this->totalClases(),
                'clases_activas' => $this->totalClasesActivas(),
                'total_estudiantes' => $this->totalEstudiantes(),
                'promedio_estudiantes_por_clase' => $this->promedioEstudiantesPorClase()
            ];
        } catch (\Exception $e) {
            return [
                'total_clases' => 0,
                'clases_activas' => 0,
                'total_estudiantes' => 0,
                'promedio_estudiantes_por_clase' => 0
            ];
        }
    }

    /**
     * Calcula el promedio de estudiantes por clase
     */
    private function promedioEstudiantesPorClase(): float
    {
        $totalClases = $this->totalClases();
        
        if ($totalClases === 0) {
            return 0;
        }
        
        return round($this->totalEstudiantes() / $totalClases, 1);
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope para docentes con clases activas
     */
    public function scopeConClasesActivas($query)
    {
        return $query->whereHas('clases', function($q) {
            $q->where('activa', true);
        });
    }

    /**
     * Scope para docentes por especialidad
     */
    public function scopePorEspecialidad($query, string $especialidad)
    {
        return $query->where('especialidad', $especialidad);
    }
}