<?php
// app/Models/InscripcionClase.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InscripcionClase extends Model
{
    use HasFactory;

    protected $table = 'inscripcion_clase';

    protected $fillable = [
        'id_estudiante',
        'id_clase',
        'fecha_inscripcion',
        'activo',
        'notas',
    ];

    protected $casts = [
        'fecha_inscripcion' => 'datetime',
        'activo' => 'boolean',
    ];

    /**
     * Relación con estudiante
     */
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante');
    }

    /**
     * Relación con clase
     */
    public function clase()
    {
        return $this->belongsTo(Clase::class, 'id_clase');
    }

    /**
     * Scope para inscripciones activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para inscripciones inactivas
     */
    public function scopeInactivas($query)
    {
        return $query->where('activo', false);
    }
}
