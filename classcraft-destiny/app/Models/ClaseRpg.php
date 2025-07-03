<?php
// app/Models/ClaseRpg.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaseRpg extends Model
{
    use HasFactory;

    protected $table = 'clase_rpg';

    protected $fillable = [
        'nombre',
        'descripcion',
        'icono',
        'color_primario',
        'color_secundario',
        'salud_base',
        'energia_base',
        'luz_base',
        'habilidades_iniciales',
        'incrementos_nivel',
        'estilo_combate',
        'ventajas',
        'requisitos_desbloqueo'
    ];

    protected $casts = [
        'habilidades_iniciales' => 'array',
        'incrementos_nivel' => 'array',
        'ventajas' => 'array',
        'requisitos_desbloqueo' => 'array'
    ];

    // Relaciones
    public function personajes()
    {
        return $this->hasMany(Personaje::class, 'id_clase_rpg');
    }

    // Métodos de utilidad
    public function crearPersonaje(Estudiante $estudiante, Clase $clase, string $nombrePersonaje): Personaje
    {
        return Personaje::create([
            'id_estudiante' => $estudiante->id,
            'id_clase' => $clase->id,
            'id_clase_rpg' => $this->id,
            'nombre_personaje' => $nombrePersonaje,
            'nivel' => 1,
            'experiencia_actual' => 0,
            'salud_actual' => $this->salud_base,
            'salud_maxima' => $this->salud_base,
            'energia_actual' => $this->energia_base,
            'energia_maxima' => $this->energia_base,
            'luz_actual' => $this->luz_base,
            'luz_maxima' => $this->luz_base,
            'principal' => !$estudiante->personajes()->exists(),
            'habilidades_desbloqueadas' => $this->habilidades_iniciales,
            'equipamiento_actual' => $this->equipamientoInicial(),
        ]);
    }

    protected function equipamientoInicial(): array
    {
        return [
            'armadura' => null,
            'arma_primaria' => null,
            'arma_secundaria' => null,
            'artefacto' => null,
            'shader' => 'default',
            'accesorios' => []
        ];
    }
}