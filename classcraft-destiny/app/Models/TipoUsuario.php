<?php

// ===== app/Models/TipoUsuario.php =====
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoUsuario extends Model
{
    use HasFactory;

    protected $table = 'tipo_usuario';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /**
     * Relación con usuarios
     */
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_tipo_usuario');
    }

    /**
     * Scope para obtener tipo específico
     */
    public function scopeTipo($query, $nombre)
    {
        return $query->where('nombre', $nombre);
    }
}
