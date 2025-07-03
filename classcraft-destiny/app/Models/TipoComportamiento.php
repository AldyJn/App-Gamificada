<?php
// app/Models/TipoComportamiento.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoComportamiento extends Model
{
    use HasFactory;

    protected $table = 'tipo_comportamiento';

    protected $fillable = [
        'nombre',
        'descripcion',
        'puntos_salud',
        'puntos_energia',
        'puntos_luz',
        'tipo',
        'icono',
        'afecta_equipo'
    ];

    protected $casts = [
        'afecta_equipo' => 'boolean'
    ];

    public function esPositivo(): bool
    {
        return $this->tipo === 'positivo';
    }

    public function esNegativo(): bool
    {
        return $this->tipo === 'negativo';
    }
}