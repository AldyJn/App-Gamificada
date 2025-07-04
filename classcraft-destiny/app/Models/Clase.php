<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Clase extends Model
{
    use HasFactory;

    protected $table = 'clase';

    protected $fillable = [
        'nombre',
        'descripcion',
        'id_docente',
        'grado',
        'seccion',
        'año_academico',
        'activo',
        'codigo_invitacion',
        'qr_url',
        'fecha_inicio',
        'fecha_fin'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'activo' => 'boolean',
        'año_academico' => 'integer'
    ];

    // ==========================================
    // RELACIONES
    // ==========================================

    /**
     * Relación con el docente (propietario de la clase)
     */
    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class, 'id_docente');
    }

    /**
     * Relación con inscripciones de estudiantes
     */
    public function inscripciones(): HasMany
    {
        return $this->hasMany(InscripcionClase::class, 'id_clase');
    }

    /**
     * Relación con estudiantes a través de inscripciones
     */
    public function estudiantes(): BelongsToMany
    {
        return $this->belongsToMany(
            Estudiante::class,
            'inscripcion_clase',
            'id_clase',
            'id_estudiante'
        )->withPivot([
            'fecha_ingreso',
            'activo'
        ])->withTimestamps();
    }

    /**
     * Relación con actividades
     */
    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class, 'id_clase');
    }

    /**
     * Relación con personajes
     */
    public function personajes(): HasMany
    {
        return $this->hasMany(Personaje::class, 'id_clase');
    }

    /**
     * Relación con misiones
     */
    public function misiones(): HasMany
    {
        return $this->hasMany(Mision::class, 'id_clase');
    }

    /**
     * Relación con registros de comportamiento
     */
    public function comportamientos(): HasMany
    {
        return $this->hasMany(RegistroComportamiento::class, 'id_clase');
    }

    /**
     * Relación con asistencias
     */
    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'id_clase');
    }

    /**
     * Relación con items de tienda
     */
    public function itemsTienda(): HasMany
    {
        return $this->hasMany(ItemTienda::class, 'id_clase');
    }

    /**
     * Relación con transacciones de moneda
     */
    public function transaccionesMoneda(): HasMany
    {
        return $this->hasMany(TransaccionMoneda::class, 'id_clase');
    }

    /**
     * Relación con sesiones de clase
     */
    public function sesiones(): HasMany
    {
        return $this->hasMany(SesionClase::class, 'id_clase');
    }

    /**
     * Relación con configuración de clase
     */
    public function configuracion(): HasMany
    {
        return $this->hasMany(ConfiguracionClase::class, 'id_clase');
    }

    /**
     * Relación con badges obtenidos en esta clase
     */
    public function badgesObtenidos(): HasMany
    {
        return $this->hasMany(EstudianteBadge::class, 'id_clase');
    }

    /**
     * Relación con estadísticas de clase
     */
    public function estadisticas(): HasMany
    {
        return $this->hasMany(EstadisticaClase::class, 'id_clase');
    }

    // ==========================================
    // MÉTODOS DE VERIFICACIÓN DE ESTADO
    // ==========================================

    /**
     * Verifica si la clase está activa
     */
    public function estaActiva(): bool
    {
        return $this->activo;
    }

    /**
     * Verifica si la clase está en período activo por fechas
     */
    public function estaEnPeriodoActivo(): bool
    {
        if (!$this->fecha_inicio || !$this->fecha_fin) {
            return $this->activo;
        }

        $ahora = Carbon::now();
        return $this->fecha_inicio <= $ahora && $ahora <= $this->fecha_fin;
    }

    /**
     * Verifica si la clase ha terminado
     */
    public function haTerminado(): bool
    {
        if (!$this->fecha_fin) {
            return false;
        }
        
        return $this->fecha_fin < Carbon::now();
    }

    /**
     * Verifica si la clase aún no ha comenzado
     */
    public function noHaComenzado(): bool
    {
        if (!$this->fecha_inicio) {
            return false;
        }
        
        return $this->fecha_inicio > Carbon::now();
    }

    // ==========================================
    // MÉTODOS DE GESTIÓN DE ESTUDIANTES
    // ==========================================

    /**
     * Agrega un estudiante a la clase
     */
    public function agregarEstudiante(Estudiante $estudiante): bool
    {
        // Verificar si ya está inscrito
        if ($this->inscripciones()->where('id_estudiante', $estudiante->id)->exists()) {
            return false;
        }

        // Crear inscripción
        InscripcionClase::create([
            'id_clase' => $this->id,
            'id_estudiante' => $estudiante->id,
            'fecha_ingreso' => now(),
            'activo' => true
        ]);

        return true;
    }

    /**
     * Remueve un estudiante de la clase
     */
    public function removerEstudiante(Estudiante $estudiante): bool
    {
        $inscripcion = $this->inscripciones()
            ->where('id_estudiante', $estudiante->id)
            ->first();

        if (!$inscripcion) {
            return false;
        }

        // Eliminar inscripción
        $inscripcion->delete();

        // También eliminar personaje si existe
        $personaje = $this->personajes()
            ->where('id_estudiante', $estudiante->id)
            ->first();
        if ($personaje) {
            $personaje->delete();
        }

        return true;
    }

    /**
     * Activa/desactiva un estudiante en la clase
     */
    public function toggleEstudianteActivo(Estudiante $estudiante): bool
    {
        $inscripcion = $this->inscripciones()
            ->where('id_estudiante', $estudiante->id)
            ->first();

        if (!$inscripcion) {
            return false;
        }

        $inscripcion->update(['activo' => !$inscripcion->activo]);
        return true;
    }

    /**
     * Obtiene un estudiante aleatorio activo
     */
    public function estudianteAleatorio(): ?Estudiante
    {
        $estudiantes = $this->estudiantes()
            ->wherePivot('activo', true)
            ->get();

        if ($estudiantes->isEmpty()) {
            return null;
        }

        return $estudiantes->random();
    }

    // ==========================================
    // MÉTODOS DE ESTADÍSTICAS
    // ==========================================

    /**
     * Obtiene el número total de estudiantes
     */
    public function getTotalEstudiantesAttribute(): int
    {
        return $this->inscripciones()->count();
    }

    /**
     * Obtiene el número de estudiantes activos
     */
    public function getTotalEstudiantesActivosAttribute(): int
    {
        return $this->inscripciones()->where('activo', true)->count();
    }

    /**
     * Obtiene el número total de actividades
     */
    public function getTotalActividadesAttribute(): int
    {
        return $this->actividades()->count();
    }

    /**
     * Obtiene el número de actividades activas
     */
    public function getTotalActividadesActivasAttribute(): int
    {
        return $this->actividades()->where('activa', true)->count();
    }

    // ==========================================
    // MÉTODOS DE CÓDIGO DE INVITACIÓN
    // ==========================================

    /**
     * Genera un nuevo código de invitación
     */
    public function generarCodigoInvitacion(): string
    {
        do {
            $codigo = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        } while (self::where('codigo_invitacion', $codigo)->exists());

        $this->update(['codigo_invitacion' => $codigo]);

        return $codigo;
    }

    /**
     * Regenera el código de invitación
     */
    public function regenerarCodigoInvitacion(): string
    {
        return $this->generarCodigoInvitacion();
    }

    // ==========================================
    // MÉTODOS DE ESTADO
    // ==========================================

    /**
     * Obtiene el estado actual de la clase
     */
    public function getEstadoAttribute(): string
    {
        if (!$this->activo) {
            return 'inactiva';
        }

        if ($this->noHaComenzado()) {
            return 'proxima';
        }

        if ($this->haTerminado()) {
            return 'finalizada';
        }

        return 'activa';
    }

    /**
     * Obtiene el color del estado
     */
    public function getColorEstadoAttribute(): string
    {
        return match($this->estado) {
            'activa' => 'success',
            'proxima' => 'warning',
            'finalizada' => 'error',
            'inactiva' => 'grey',
            default => 'primary'
        };
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope para clases activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para clases del docente
     */
    public function scopeDelDocente($query, $docenteId)
    {
        return $query->where('id_docente', $docenteId);
    }

    /**
     * Scope para clases por año académico
     */
    public function scopeDelAño($query, $año)
    {
        return $query->where('año_academico', $año);
    }

    /**
     * Scope para clases por código
     */
    public function scopePorCodigo($query, $codigo)
    {
        return $query->where('codigo_invitacion', strtoupper($codigo));
    }

    // ==========================================
    // MÉTODOS DE BOOT
    // ==========================================

    protected static function boot()
    {
        parent::boot();

        // Generar código de invitación automáticamente
        static::creating(function ($clase) {
            if (!$clase->codigo_invitacion) {
                do {
                    $codigo = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
                } while (self::where('codigo_invitacion', $codigo)->exists());
                
                $clase->codigo_invitacion = $codigo;
            }

            // Establecer año académico actual si no se especifica
            if (!$clase->año_academico) {
                $clase->año_academico = Carbon::now()->year;
            }
        });
    }
}