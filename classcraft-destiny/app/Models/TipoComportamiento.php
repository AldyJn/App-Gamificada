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
        'puntos_base',
        'tipo',
        'icono',
        'color',
        'activo',
    ];

    protected $casts = [
        'puntos_base' => 'integer',
        'activo' => 'boolean',
    ];

    /**
     * Relación con registros de comportamiento
     */
    public function registros()
    {
        return $this->hasMany(RegistroComportamiento::class, 'id_tipo_comportamiento');
    }

    /**
     * Scope para comportamientos positivos
     */
    public function scopePositivos($query)
    {
        return $query->where('tipo', 'positivo');
    }

    /**
     * Scope para comportamientos negativos
     */
    public function scopeNegativos($query)
    {
        return $query->where('tipo', 'negativo');
    }

    /**
     * Scope para comportamientos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Determinar si es comportamiento positivo
     */
    public function esPositivo()
    {
        return $this->tipo === 'positivo';
    }

    /**
     * Determinar si es comportamiento negativo
     */
    public function esNegativo()
    {
        return $this->tipo === 'negativo';
    }
}