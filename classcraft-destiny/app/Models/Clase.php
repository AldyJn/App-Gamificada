<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Clase extends Model
{
    use HasFactory;

    protected $table = 'clase';

    protected $fillable = [
        'nombre',
        'descripcion', 
        'codigo',
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
        'configuracion_gamificacion' => 'array'
    ];

    /**
     * Generar un código único para la clase
     */
    public static function generarCodigoUnico()
    {
        do {
            // Generar código de 6 caracteres aleatorios
            $codigo = strtoupper(Str::random(6));
        } while (self::where('codigo', $codigo)->exists());
        
        return $codigo;
    }

    /**
     * Relación con el docente
     */
    public function docente()
    {
        return $this->belongsTo(Usuario::class, 'id_docente');
    }

    /**
     * Relación many-to-many con estudiantes
     */
    public function estudiantes()
    {
        return $this->belongsToMany(Usuario::class, 'inscripcion_clase', 'id_clase', 'id_estudiante')
                    ->withTimestamps();
    }

    /**
     * Verificar si la clase está activa
     */
    public function estaActiva()
    {
        return $this->activa && 
               $this->fecha_inicio <= now() && 
               $this->fecha_fin >= now();
    }

    /**
     * Obtener progreso de la clase (placeholder)
     */
    public function obtenerProgreso()
    {
        // Calcular progreso basado en fechas
        $inicio = $this->fecha_inicio;
        $fin = $this->fecha_fin;
        $hoy = now();

        if ($hoy < $inicio) {
            return 0; // No ha comenzado
        }

        if ($hoy > $fin) {
            return 100; // Ha terminado
        }

        // Calcular porcentaje de progreso
        $totalDias = $inicio->diffInDays($fin);
        $diasTranscurridos = $inicio->diffInDays($hoy);

        return $totalDias > 0 ? round(($diasTranscurridos / $totalDias) * 100, 1) : 0;
    }

    /**
     * Agregar un estudiante a la clase
     */
    public function agregarEstudiante(Usuario $estudiante)
    {
        // Verificar si ya está inscrito
        if ($this->estudiantes()->where('id_estudiante', $estudiante->id)->exists()) {
            return false;
        }

        // Agregar el estudiante
        $this->estudiantes()->attach($estudiante->id, [
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return true;
    }

    /**
     * Remover un estudiante de la clase
     */
    public function removerEstudiante(Usuario $estudiante)
    {
        return $this->estudiantes()->detach($estudiante->id) > 0;
    }

    /**
     * Seleccionar un estudiante al azar de la clase
     */
    public function seleccionarEstudianteAleatorio()
    {
        return $this->estudiantes()->inRandomOrder()->first();
    }
}