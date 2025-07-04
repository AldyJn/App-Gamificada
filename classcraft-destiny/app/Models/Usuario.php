<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuario';

    protected $fillable = [
        'nombre',
        'apellido', 
        'correo',
        'avatar',
        'contraseña_hash',
        'salt',
        'id_tipo_usuario',
        'id_estado',
        'ultimo_acceso',
        'eliminado'
    ];

    protected $hidden = [
        'contraseña_hash',
        'salt',
        'remember_token',
    ];

    protected $casts = [
        'ultimo_acceso' => 'datetime',
        'eliminado' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    // ==========================================
    // RELACIONES
    // ==========================================

    /**
     * Relación con tipo de usuario
     */
    public function tipoUsuario(): BelongsTo
    {
        return $this->belongsTo(TipoUsuario::class, 'id_tipo_usuario');
    }

    /**
     * Relación con estado de usuario
     */
    public function estado(): BelongsTo
    {
        return $this->belongsTo(EstadoUsuario::class, 'id_estado');
    }

    /**
     * Relación con perfil de docente
     */
    public function docente(): HasOne
    {
        return $this->hasOne(Docente::class, 'id_usuario');
    }

    /**
     * Relación con perfil de estudiante
     */
    public function estudiante(): HasOne
    {
        return $this->hasOne(Estudiante::class, 'id_usuario');
    }

    /**
     * Relación con clases como estudiante (usando inscripcion_clase)
     */
    public function clasesComoEstudiante(): BelongsToMany
    {
        return $this->belongsToMany(
            Clase::class,
            'inscripcion_clase',  // tabla pivot
            'id_estudiante',      // foreign key en tabla pivot para este modelo
            'id_clase'            // foreign key en tabla pivot para el modelo relacionado
        )->withPivot(['fecha_ingreso', 'activo'])
         ->withTimestamps();
    }

    /**
     * Relación con clases como docente
     */
    public function clasesComoDocente(): HasMany
    {
        return $this->hasMany(Clase::class, 'docente_id');
    }

    // ==========================================
    // MÉTODOS DE VERIFICACIÓN DE ROLES
    // ==========================================

    /**
     * Verifica si el usuario es docente
     */
    public function esDocente(): bool
    {
        return $this->tipoUsuario && $this->tipoUsuario->nombre === 'docente';
    }

    /**
     * Verifica si el usuario es estudiante
     */
    public function esEstudiante(): bool
    {
        return $this->tipoUsuario && $this->tipoUsuario->nombre === 'estudiante';
    }

    /**
     * Verifica si el usuario es administrador
     */
    public function esAdministrador(): bool
    {
        return $this->tipoUsuario && $this->tipoUsuario->nombre === 'administrador';
    }

    // ==========================================
    // MÉTODOS DE CONVENIENCIA
    // ==========================================

    /**
     * Obtiene las clases del usuario según su rol
     */
    public function clases()
    {
        if ($this->esDocente()) {
            return $this->clasesComoDocente();
        }
        
        if ($this->esEstudiante()) {
            return $this->clasesComoEstudiante();
        }
        
        return collect();
    }

    /**
     * Obtiene las iniciales del usuario
     */
    public function getInicialesAttribute(): string
    {
        $nombres = explode(' ', $this->nombre);
        $iniciales = '';
        foreach ($nombres as $nombre) {
            if (!empty($nombre)) {
                $iniciales .= strtoupper(substr($nombre, 0, 1));
            }
        }
        return substr($iniciales, 0, 2);
    }

    /**
     * Verifica si el usuario está activo
     */
    public function estaActivo(): bool
    {
        return !$this->eliminado && $this->estado && $this->estado->nombre === 'activo';
    }

    /**
     * Obtiene el avatar del usuario o un placeholder
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        return "https://ui-avatars.com/api/?name=" . urlencode($this->nombre) . "&size=128&background=6366f1&color=fff";
    }

    /**
     * Actualiza el último acceso
     */
    public function actualizarUltimoAcceso(): void
    {
        $this->update(['ultimo_acceso' => now()]);
    }

    // ==========================================
    // MÉTODOS ESPECÍFICOS PARA DOCENTES
    // ==========================================

    /**
     * Obtiene todas las clases del docente
     */
    public function misClases()
    {
        if (!$this->esDocente()) {
            return collect();
        }

        return $this->clasesComoDocente()
            ->with(['estudiantes', 'actividades'])
            ->get();
    }

    /**
     * Verifica si el docente puede gestionar una clase
     */
    public function puedeGestionarClase(Clase $clase): bool
    {
        return $this->esDocente() && $clase->docente_id === $this->id;
    }

    // ==========================================
    // MÉTODOS ESPECÍFICOS PARA ESTUDIANTES
    // ==========================================

    /**
     * Obtiene las clases activas del estudiante
     */
    public function clasesActivas()
    {
        if (!$this->esEstudiante()) {
            return collect();
        }

        return $this->clasesComoEstudiante()
            ->wherePivot('activo', true)
            ->where('fecha_fin', '>=', now())
            ->get();
    }

    /**
     * Verifica si el estudiante está inscrito en una clase
     */
    public function estaInscritoEnClase(Clase $clase): bool
    {
        if (!$this->esEstudiante()) {
            return false;
        }

        return $this->clasesComoEstudiante()
            ->where('clase.id', $clase->id)
            ->wherePivot('activo', true)
            ->exists();
    }

    // ==========================================
    // MÉTODOS PARA AUTENTICACIÓN
    // ==========================================

    /**
     * Obtiene el campo de contraseña para autenticación
     */
    public function getAuthPassword()
    {
        return $this->contraseña_hash;
    }

    /**
     * Obtiene el campo de email para notificaciones
     */
    public function getEmailForVerification()
    {
        return $this->correo;
    }

    /**
     * Obtiene el nombre del campo de email
     */
    public function getEmailAttribute()
    {
        return $this->correo;
    }

    /**
     * Obtiene el nombre del campo de contraseña
     */
    public function getPasswordAttribute()
    {
        return $this->contraseña_hash;
    }
}