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

    // ✅ CORREGIDO: Agregados campos faltantes
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
        'timezone',
        'notificaciones_push',
        'configuracion_ui',
        'estadisticas_globales',
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
        'notificaciones_push' => 'boolean',
        'configuracion_ui' => 'array',
        'estadisticas_globales' => 'array',
    ];

    // ==========================================
    // ACCESOR PARA COMPATIBILIDAD
    // ==========================================

    /**
     * ✅ AGREGADO: Accesor para simular campo 'activo' 
     * basado en 'eliminado' y 'id_estado'
     */
    public function getActivoAttribute(): bool
    {
        return !$this->eliminado && $this->id_estado == 1;
    }

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
     * Relación con notificaciones
     */
    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class, 'id_usuario');
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
     * ✅ MEJORADO: Verifica si el usuario está activo usando la lógica correcta
     */
    public function estaActivo(): bool
    {
        return !$this->eliminado && $this->id_estado === 1;
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
    // SCOPES
    // ==========================================

    /**
     * Scope para usuarios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('eliminado', false)->where('id_estado', 1);
    }

    /**
     * Scope para docentes
     */
    public function scopeDocentes($query)
    {
        return $query->whereHas('tipoUsuario', function ($q) {
            $q->where('nombre', 'docente');
        });
    }

    /**
     * Scope para estudiantes
     */
    public function scopeEstudiantes($query)
    {
        return $query->whereHas('tipoUsuario', function ($q) {
            $q->where('nombre', 'estudiante');
        });
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
     * Accessor para email (usado por Laravel Auth)
     */
    public function getEmailAttribute()
    {
        return $this->correo;
    }

    /**
     *  Accessor para password (usado por Laravel Auth)
     */
    public function getPasswordAttribute()
    {
        return $this->contraseña_hash;
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

        return $this->docente->clases()->with([
            'inscripciones.estudiante.usuario',
            'actividades',
            'personajes'
        ])->get();
    }

    /**
     * Verifica si el docente puede gestionar una clase
     */
    public function puedeGestionarClase($clase): bool
    {
        return $this->esDocente() && $this->docente && $clase->id_docente === $this->docente->id;
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

        return $this->estudiante->clases()->wherePivot('activo', true)->get();
    }

    /**
     * Verifica si el estudiante está inscrito en una clase
     */
    public function estaInscritoEnClase($clase): bool
    {
        if (!$this->esEstudiante()) {
            return false;
        }

        return $this->estudiante->inscripciones()
            ->where('id_clase', $clase->id)
            ->where('activo', true)
            ->exists();
    }

    /**
     * Obtiene el personaje del estudiante en una clase específica
     */
    public function personajeEnClase($clase)
    {
        if (!$this->esEstudiante()) {
            return null;
        }

        return $this->estudiante->personajes()
            ->where('id_clase', $clase->id)
            ->first();
    }
}