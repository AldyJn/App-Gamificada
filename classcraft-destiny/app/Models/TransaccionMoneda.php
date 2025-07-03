<?php
// ===== app/Models/TransaccionMoneda.php =====
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaccionMoneda extends Model
{
    use HasFactory;

    protected $table = 'transaccion_moneda';

    protected $fillable = [
        'id_personaje',
        'cantidad',
        'tipo',
        'motivo',
        'saldo_anterior',
        'saldo_nuevo',
        'id_item_tienda',
        'metadatos',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'saldo_anterior' => 'integer',
        'saldo_nuevo' => 'integer',
        'metadatos' => 'array',
    ];

    /**
     * Relación con personaje
     */
    public function personaje()
    {
        return $this->belongsTo(Personaje::class, 'id_personaje');
    }

    /**
     * Relación con item de tienda (si la transacción fue una compra)
     */
    public function itemTienda()
    {
        return $this->belongsTo(ItemTienda::class, 'id_item_tienda');
    }

    /**
     * Scope para ganancias
     */
    public function scopeGanancias($query)
    {
        return $query->where('tipo', 'ganancia');
    }

    /**
     * Scope para gastos
     */
    public function scopeGastos($query)
    {
        return $query->where('tipo', 'gasto');
    }

    /**
     * Scope para transacciones de un período
     */
    public function scopeEnPeriodo($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);
    }
}
