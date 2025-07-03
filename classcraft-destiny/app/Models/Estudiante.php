<?php
// app/Models/Estudiante.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiante';

    protected $fillable = [
        'id_usuario',
        'codigo_estudiante',
        'grado',
        'seccion'
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function inscripciones()
    {
        return $this->hasMany(InscripcionClase::class, 'id_estudiante');
    }

    public function clases()
    {
        return $this->belongsToMany(Clase::class, 'inscripcion_clase', 'id_estudiante', 'id_clase')
                    ->wherePivot('activo', true);
    }

    public function personajes()
    {
        return $this->hasMany(Personaje::class, 'id_estudiante');
    }

    // Métodos de utilidad
    public function clasesActivas()
    {
        return $this->clases()->where('activo', true);
    }

    public function personajePrincipal()
    {
        return $this->personajes()->where('principal', true)->first();
    }

    public function generarCodigoEstudiante(): string
    {
        return 'EST' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
}