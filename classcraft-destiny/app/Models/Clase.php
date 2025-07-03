<?php
// app/Models/Clase.php

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
        'codigo_acceso',
        'id_docente',
        'materia',
        'grado',
        'seccion',
        'ano_escolar',
        'activa',
        'configuracion',
        'imagen_banner',
        'color_tema',
    ];

    protected $casts = [
        'activa' => 'boolean',
        'configuracion' => 'array',
    ];

    /**
     * Generar código de acceso automáticamente
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($clase) {
            if (empty($clase->codigo_acceso)) {
                $clase->codigo_acceso = static::generarCodigoUnico();
            }
        });
    }

    /**
     * Generar código único de 6 caracteres
     */
    public static function generarCodigoUnico()
    {
        do {
            $codigo = strtoupper(Str::random(6));
        } while (static::where('codigo_acceso', $codigo)->exists());
        
        return $codigo;
    }

    /**
     * Relación con docente
     */
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'id_docente');
    }

    /**
     * Relación muchos a muchos con estudiantes
     */
    public function estudiantes()
    {
        return $this->belongsToMany(
            Estudiante::class,
            'inscripcion_clase',
            'id_clase',
            'id_estudiante'
        )->withPivot(['fecha_inscripcion', 'activo', 'notas'])
         ->withTimestamps();
    }

    /**
     * Relación con actividades
     */
    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'id_clase');
    }

    /**
     * Relación con personajes
     */
    public function personajes()
    {
        return $this->hasMany(Personaje::class, 'id_clase');
    }

    /**
     * Relación con misiones
     */
    public function misiones()
    {
        return $this->hasMany(Mision::class, 'id_clase');
    }

    /**
     * Relación con configuraciones
     */
    public function configuraciones()
    {
        return $this->hasMany(ConfiguracionClase::class, 'id_clase');
    }

    /**
     * Relación con items de tienda
     */
    public function itemsTienda()
    {
        return $this->hasMany(ItemTienda::class, 'id_clase');
    }

    /**
     * Obtener estudiantes activos de la clase
     */
    public function estudiantesActivos()
    {
        return $this->estudiantes()->wherePivot('activo', true);
    }

    /**
     * Obtener configuración específica
     */
    public function obtenerConfiguracion($clave, $defecto = null)
    {
        $config = $this->configuraciones()
            ->where('clave', $clave)
            ->first();
            
        if ($config) {
            // Convertir según el tipo
            switch ($config->tipo) {
                case 'boolean':
                    return filter_var($config->valor, FILTER_VALIDATE_BOOLEAN);
                case 'integer':
                    return (int) $config->valor;
                case 'decimal':
                    return (float) $config->valor;
                case 'json':
                    return json_decode($config->valor, true);
                default:
                    return $config->valor;
            }
        }
        
        return $defecto;
    }

    /**
     * Establecer configuración
     */
    public function establecerConfiguracion($clave, $valor, $descripcion = null)
    {
        $tipo = $this->determinarTipoValor($valor);
        $valorString = is_array($valor) ? json_encode($valor) : (string) $valor;
        
        return $this->configuraciones()->updateOrCreate(
            ['clave' => $clave],
            [
                'valor' => $valorString,
                'tipo' => $tipo,
                'descripcion' => $descripcion,
            ]
        );
    }

    /**
     * Determinar tipo de valor para configuración
     */
    private function determinarTipoValor($valor)
    {
        if (is_bool($valor)) return 'boolean';
        if (is_int($valor)) return 'integer';
        if (is_float($valor)) return 'decimal';
        if (is_array($valor)) return 'json';
        return 'string';
    }

    /**
     * Scope para clases activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    /**
     * Scope para clases por docente
     */
    public function scopePorDocente($query, $docenteId)
    {
        return $query->where('id_docente', $docenteId);
    }

    /**
     * Obtener estadísticas de la clase
     */
    public function getEstadisticasAttribute()
    {
        return [
            'total_estudiantes' => $this->estudiantesActivos()->count(),
            'total_actividades' => $this->actividades()->count(),
            'total_misiones' => $this->misiones()->count(),
            'actividades_completadas' => $this->actividades()
                ->whereHas('entregas', function($q) {
                    $q->whereNotNull('fecha_entrega');
                })->count(),
            'promedio_nivel' => $this->personajes()->avg('nivel') ?? 1,
        ];
    }
}