<?php
// app/Models/Docente.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;

    protected $table = 'docente';

    protected $fillable = [
        'id_usuario',
        'codigo_docente',
        'especialidad',
        'años_experiencia',
        'nivel_guardian',
        'titulo_especialidad'
    ];

    protected $casts = [
        'años_experiencia' => 'integer',
        'nivel_guardian' => 'integer'
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function clases()
    {
        return $this->hasMany(Clase::class, 'id_docente');
    }

    // Métodos de utilidad
    public function clasesActivas()
    {
        return $this->clases()->where('activo', true)->where('fecha_fin', '>=', now());
    }

    public function totalEstudiantes()
    {
        return $this->clases()
            ->whereHas('inscripciones', function($query) {
                $query->where('activo', true);
            })
            ->withCount('inscripciones')
            ->sum('inscripciones_count');
    }
}