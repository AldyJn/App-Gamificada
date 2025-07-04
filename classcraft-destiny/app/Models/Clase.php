<?php
// app/Models/Clase.php - CORRECCIÓN COMPLETA

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

    protected $table = 'clase'; // 🔧 Cambiar a 'clases' si es necesario

    protected $fillable = [
        'nombre',
        'descripcion', 
        'codigo', // 🔧 Usar 'codigo' en lugar de 'codigo_invitacion'
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
        'configuracion_gamificacion' => 'array',
        'año_academico' => 'string'
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
     * 🔧 CORREGIDO: Ahora busca en la columna correcta
     */
    public static function generarCodigoUnico(): string
    {
        do {
            // Generar código: 3 letras + 3 números (ej: ABC123)
            $letras = strtoupper(Str::random(3));
            $numeros = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
            $codigo = $letras . $numeros;
            
        } while (static::where('codigo', $codigo)->exists()); // 🔧 CORREGIDO: usar 'codigo'

        return $codigo;
    }

    // ==========================================
    // MÉTODOS DE INSTANCIA
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
     * Obtener el progreso de la clase
     */
    public function obtenerProgreso(): float
    {
        if (!$this->fecha_inicio || !$this->fecha_fin) {
            return 0.0;
        }

        $totalDias = $this->fecha_inicio->diffInDays($this->fecha_fin);
        $diasTranscurridos = $this->fecha_inicio->diffInDays(now());
        
        if ($totalDias <= 0) {
            return 100.0;
        }

        return min(100.0, max(0.0, ($diasTranscurridos / $totalDias) * 100));
    }

    /**
     * Verificar si puede seleccionar estudiantes aleatorios
     */
    public function puedeSeleccionarEstudiantes(): bool
    {
        return $this->estudiantes()->count() > 0;
    }

    /**
     * Obtener estudiantes activos
     */
    public function estudiantesActivos()
    {
        return $this->estudiantes()->wherePivot('activo', true);
    }

    /**
     * Agregar estudiante a la clase
     */
    public function agregarEstudiante($estudianteId)
    {
        return $this->estudiantes()->attach($estudianteId, [
            'fecha_ingreso' => now(),
            'activo' => true
        ]);
    }

    /**
     * Obtener total de estudiantes
     */
    public function getTotalEstudiantesAttribute(): int
    {
        return $this->estudiantes()->wherePivot('activo', true)->count();
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
     * Scope para clases de un docente
     */
    public function scopeDeDocente($query, $docenteId)
    {
        return $query->where('id_docente', $docenteId);
    }

    /**
     * Scope para clases por código
     */
    public function scopePorCodigo($query, $codigo)
    {
        return $query->where('codigo', strtoupper($codigo));
    }
}