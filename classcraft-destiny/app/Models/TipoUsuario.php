<?php

// ==========================================
// app/Models/TipoUsuario.php
// ==========================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoUsuario extends Model
{
    use HasFactory;

    protected $table = 'tipo_usuario';

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    public function usuarios(): HasMany
    {
        return $this->hasMany(Usuario::class, 'id_tipo_usuario');
    }

    public static function docente(): ?self
    {
        return static::where('nombre', 'docente')->first();
    }

    public static function estudiante(): ?self
    {
        return static::where('nombre', 'estudiante')->first();
    }
}
