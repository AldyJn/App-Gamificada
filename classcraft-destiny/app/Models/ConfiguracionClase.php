<?php
// ===== app/Models/ConfiguracionClase.php =====
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracionClase extends Model
{
    use HasFactory;

    protected $table = 'configuracion_clase';

    protected $fillable = [
        'id_clase',
        'clave',
        'valor',
        'tipo',
        'descripcion',
    ];

    /**
     * Relación con clase
     */
    public function clase()
    {
        return $this->belongsTo(Clase::class, 'id_clase');
    }

    /**
     * Obtener valor convertido según su tipo
     */
    public function getValorConvertidoAttribute()
    {
        switch ($this->tipo) {
            case 'boolean':
                return filter_var($this->valor, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $this->valor;
            case 'decimal':
                return (float) $this->valor;
            case 'json':
                return json_decode($this->valor, true);
            default:
                return $this->valor;
        }
    }

    /**
     * Scope para configuraciones por clave
     */
    public function scopePorClave($query, $clave)
    {
        return $query->where('clave', $clave);
    }

    /**
     * Scope para configuraciones por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }
}