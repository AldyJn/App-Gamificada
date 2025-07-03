<?php
// app/Models/Clase.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
        'fecha_fin',
        'tema_visual',
        'configuracion_gamificacion',
        'modo_competitivo',
        'horarios_clase'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'modo_competitivo' => 'boolean',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'configuracion_gamificacion' => 'array',
        'horarios_clase' => 'array'
    ];

    // Relaciones
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'id_docente');
    }

    public function inscripciones()
    {
        return $this->hasMany(InscripcionClase::class, 'id_clase');
    }

    public function estudiantes()
    {
        return $this->belongsToMany(Estudiante::class, 'inscripcion_clase', 'id_clase', 'id_estudiante')
                    ->wherePivot('activo', true);
    }

    public function personajes()
    {
        return $this->hasMany(Personaje::class, 'id_clase');
    }

    // Métodos de utilidad
    public function estaActiva(): bool
    {
        $hoy = now()->toDateString();
        return $this->activo && 
               $hoy >= $this->fecha_inicio && 
               $hoy <= $this->fecha_fin;
    }

    public function totalEstudiantes(): int
    {
        return $this->inscripciones()->where('activo', true)->count();
    }

    public function generarCodigoInvitacion(): string
    {
        do {
            $codigo = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8));
        } while (self::where('codigo_invitacion', $codigo)->exists());

        $this->update(['codigo_invitacion' => $codigo]);
        return $codigo;
    }

    public function generarQR(): string
    {
        $url = route('estudiante.unirse-clase', $this->codigo_invitacion);
        $qrPath = 'qr_codes/' . $this->codigo_invitacion . '.png';
        
        QrCode::format('png')
              ->size(300)
              ->generate($url, storage_path('app/public/' . $qrPath));
        
        $this->update(['qr_url' => '/storage/' . $qrPath]);
        return $this->qr_url;
    }

    public function estudianteAleatorio()
    {
        return $this->estudiantes()->inRandomOrder()->first();
    }
}