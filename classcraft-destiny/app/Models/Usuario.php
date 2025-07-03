<?php
// app/Models/Usuario.php

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
        'email',
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

    // Relaciones
    // public function tipoUsuario()
    // {
    //     return $this->belongsTo(TipoUsuario::class, 'id_tipo_usuario');
    // }

    // public function estado()
    // {
    //     return $this->belongsTo(EstadoUsuario::class, 'id_estado');
    // }

    public function docente()
    {
        return $this->hasOne(Docente::class, 'id_usuario');
    }

    public function estudiante()
    {
        return $this->hasOne(Estudiante::class, 'id_usuario');
    }

    // Métodos de utilidad
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
}