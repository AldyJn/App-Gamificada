<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class Clase extends Model
{
    use HasFactory;

    protected $table = 'clase';

    protected $fillable = [
        'nombre',
        'descripcion', 
        'codigo',
        'id_docente',
        'fecha_inicio',
        'fecha_fin',
        'grado',
        'seccion',
        'año_academico',
        'activa',
        'permitir_inscripcion',
        'configuracion_gamificacion'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'activa' => 'boolean',
        'permitir_inscripcion' => 'boolean',
        'configuracion_gamificacion' => 'array'
    ];

    // ==========================================
    // RELACIONES
    // ==========================================

    /**
     * Relación con el docente
     */
    public function docente(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_docente');
    }

    /**
     * Relación many-to-many con estudiantes
     */
    public function estudiantes(): BelongsToMany
    {
        // Verificar qué tabla existe para usar la correcta
        if (Schema::hasTable('inscripcion_clase')) {
            return $this->belongsToMany(
                Usuario::class, 
                'inscripcion_clase', 
                'id_clase', 
                'id_estudiante'
            )->withTimestamps()
             ->withPivot(['activo', 'fecha_ingreso']);
        }
        
        // Fallback a tabla estándar
        return $this->belongsToMany(Usuario::class, 'clase_usuario')
                    ->withTimestamps();
    }

    // ==========================================
    // MÉTODOS ESTÁTICOS
    // ==========================================

    /**
     * Generar código único para la clase
     */
    public static function generarCodigoUnico(): string
    {
        do {
            // Generar código: 3 letras + 3 números (ej: ABC123)
            $letras = strtoupper(Str::random(3));
            $numeros = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
            $codigo = $letras . $numeros;
            
        } while (self::where('codigo', $codigo)->exists());
        
        return $codigo;
    }

    // ==========================================
    // MÉTODOS DE UTILIDAD
    // ==========================================

    /**
     * Verificar si la clase está activa
     */
    public function estaActiva(): bool
    {
        return $this->activa && 
               $this->fecha_inicio <= now() && 
               $this->fecha_fin >= now();
    }

    /**
     * Obtener progreso de la clase basado en fechas
     */
    public function obtenerProgreso(): int
    {
        $inicio = $this->fecha_inicio;
        $fin = $this->fecha_fin;
        $hoy = now();

        if ($hoy < $inicio) {
            return 0; // No ha comenzado
        }

        if ($hoy > $fin) {
            return 100; // Ha terminado
        }

        // Calcular porcentaje de progreso
        $totalDias = $inicio->diffInDays($fin);
        if ($totalDias <= 0) {
            return 100;
        }
        
        $diasTranscurridos = $inicio->diffInDays($hoy);
        return min(100, round(($diasTranscurridos / $totalDias) * 100));
    }

    /**
     * Agregar estudiante a la clase
     */
    public function agregarEstudiante($usuarioId): bool
    {
        try {
            // Verificar si ya está inscrito
            if ($this->estudiantes()->where('usuario.id', $usuarioId)->exists()) {
                return false;
            }

            // Inscribir usando la tabla correcta
            if (Schema::hasTable('inscripcion_clase')) {
                $this->estudiantes()->attach($usuarioId, [
                    'activo' => true,
                    'fecha_ingreso' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                $this->estudiantes()->attach($usuarioId, [
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            
            return true;
            
        } catch (\Exception $e) {
            \Log::error('Error agregando estudiante: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener total de estudiantes activos
     */
    public function totalEstudiantes(): int
    {
        try {
            if (Schema::hasTable('inscripcion_clase')) {
                return $this->estudiantes()->wherePivot('activo', true)->count();
            }
            
            return $this->estudiantes()->count();
            
        } catch (\Exception $e) {
            \Log::error('Error contando estudiantes: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Verificar si permite inscripciones
     */
    public function permiteInscripciones(): bool
    {
        return $this->permitir_inscripcion && 
               $this->activa && 
               !$this->fecha_fin->isPast();
    }

    /**
     * Regenerar código de la clase
     */
    public function regenerarCodigo(): string
    {
        $nuevoCodigo = self::generarCodigoUnico();
        $this->update(['codigo' => $nuevoCodigo]);
        return $nuevoCodigo;
    }

    /**
     * Obtener estudiantes activos con información completa
     */
    public function estudiantesActivos()
    {
        $query = $this->estudiantes()->orderBy('nombre');
        
        if (Schema::hasTable('inscripcion_clase')) {
            $query->wherePivot('activo', true);
        }
        
        return $query->get();
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope para clases activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    /**
     * Scope para clases de un docente específico
     */
    public function scopeDeDocente($query, $docenteId)
    {
        return $query->where('id_docente', $docenteId);
    }

    /**
     * Scope para buscar por código
     */
    public function scopePorCodigo($query, $codigo)
    {
        return $query->where('codigo', strtoupper(trim($codigo)));
    }

    /**
     * Scope para clases que permiten inscripción
     */
    public function scopeQuePermitenInscripcion($query)
    {
        return $query->where('permitir_inscripcion', true)
                     ->where('activa', true)
                     ->where('fecha_fin', '>=', now());
    }
}