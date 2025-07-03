<?php
// app/Models/Usuario.php - ACTUALIZACIÓN COMPLETA

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        'timezone',
        'notificaciones_push',
        'configuracion_ui',
        'estadisticas_globales'
    ];

    protected $hidden = [
        'contraseña_hash',
        'salt',
        'remember_token',
    ];

    protected $casts = [
        'ultimo_acceso' => 'datetime',
        'notificaciones_push' => 'boolean',
        'configuracion_ui' => 'array',
        'estadisticas_globales' => 'array',
        'email_verified_at' => 'datetime',
    ];

    // ===== MÉTODOS DE AUTENTICACIÓN LARAVEL =====
    
    /**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        return $this->contraseña_hash;
    }

    /**
     * Get the column name for the "remember me" token.
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getAuthIdentifierName()
    {
        return 'correo';
    }

    /**
     * Get the unique identifier for the user.
     */
    public function getAuthIdentifier()
    {
        return $this->correo;
    }

    /**
     * Get the email attribute for password resets.
     */
    public function getEmailForPasswordReset()
    {
        return $this->correo;
    }

    // ===== RELACIONES =====
    public function tipoUsuario()
    {
        return $this->belongsTo(TipoUsuario::class, 'id_tipo_usuario');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoUsuario::class, 'id_estado');
    }

    public function docente()
    {
        return $this->hasOne(Docente::class, 'id_usuario');
    }

    public function estudiante()
    {
        return $this->hasOne(Estudiante::class, 'id_usuario');
    }

    // ===== MÉTODOS DE UTILIDAD =====
    public function esDocente(): bool
    {
        return $this->id_tipo_usuario === 1;
    }

    public function esEstudiante(): bool
    {
        return $this->id_tipo_usuario === 2;
    }

    public function nombreCompleto(): string
    {
        return $this->nombre . ' ' . $this->apellido;
    }

    public function estaActivo(): bool
    {
        return $this->id_estado === 1;
    }
}