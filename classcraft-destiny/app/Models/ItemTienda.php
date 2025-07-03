<?php
// ===== app/Models/ItemTienda.php =====
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTienda extends Model
{
    use HasFactory;

    protected $table = 'item_tienda';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'id_clase',
        'categoria',
        'tipo_item',
        'efectos',
        'imagen_url',
        'disponible',
        'stock_limitado',
        'stock_actual',
        'nivel_requerido',
        'requisitos',
    ];

    protected $casts = [
        'precio' => 'integer',
        'efectos' => 'array',
        'disponible' => 'boolean',
        'stock_limitado' => 'boolean',
        'stock_actual' => 'integer',
        'nivel_requerido' => 'integer',
        'requisitos' => 'array',
    ];

    /**
     * Relación con clase
     */
    public function clase()
    {
        return $this->belongsTo(Clase::class, 'id_clase');
    }

    /**
     * Relación con transacciones de compra
     */
    public function transacciones()
    {
        return $this->hasMany(TransaccionMoneda::class, 'id_item_tienda');
    }

    /**
     * Verificar si un personaje puede comprar este item
     */
    public function puedeSerCompradoPor($personaje)
    {
        // Verificar disponibilidad
        if (!$this->disponible) return false;
        
        // Verificar monedas suficientes
        if ($personaje->monedas < $this->precio) return false;
        
        // Verificar nivel requerido
        if ($this->nivel_requerido && $personaje->nivel < $this->nivel_requerido) return false;
        
        // Verificar stock
        if ($this->stock_limitado && $this->stock_actual <= 0) return false;
        
        // Verificar requisitos adicionales
        if ($this->requisitos && !$this->cumpleRequisitos($personaje)) return false;
        
        return true;
    }

    /**
     * Verificar requisitos adicionales
     */
    private function cumpleRequisitos($personaje)
    {
        $requisitos = $this->requisitos ?? [];
        
        foreach ($requisitos as $requisito) {
            switch ($requisito['tipo'] ?? '') {
                case 'badge':
                    $tieneBadge = $personaje->estudiante->badges()
                        ->where('nombre', $requisito['valor'])
                        ->exists();
                    if (!$tieneBadge) return false;
                    break;
                    
                case 'actividades_completadas':
                    $actividades = $personaje->estudiante->entregas()
                        ->whereNotNull('fecha_entrega')
                        ->count();
                    if ($actividades < ($requisito['cantidad'] ?? 0)) return false;
                    break;
                    
                case 'clase_rpg':
                    if ($personaje->id_clase_rpg !== ($requisito['id'] ?? 0)) return false;
                    break;
            }
        }
        
        return true;
    }

    /**
     * Procesar compra del item
     */
    public function comprarPor($personaje)
    {
        if (!$this->puedeSerCompradoPor($personaje)) {
            return false;
        }
        
        // Procesar transacción
        $personaje->ajustarMonedas(
            -$this->precio,
            "Compra: {$this->nombre}",
            'gasto'
        );
        
        // Actualizar stock si es limitado
        if ($this->stock_limitado) {
            $this->decrement('stock_actual');
        }
        
        // Aplicar efectos del item
        $this->aplicarEfectos($personaje);
        
        return true;
    }

    /**
     * Aplicar efectos del item al personaje
     */
    private function aplicarEfectos($personaje)
    {
        $efectos = $this->efectos ?? [];
        
        foreach ($efectos as $efecto) {
            switch ($efecto['tipo'] ?? '') {
                case 'salud':
                    $personaje->ajustarSalud($efecto['valor'] ?? 0, "Efecto de {$this->nombre}");
                    break;
                    
                case 'experiencia':
                    $personaje->ganarExperiencia($efecto['valor'] ?? 0, "Efecto de {$this->nombre}");
                    break;
                    
                case 'monedas':
                    $personaje->ajustarMonedas($efecto['valor'] ?? 0, "Efecto de {$this->nombre}", 'ganancia');
                    break;
                    
                case 'equipar':
                    $this->equiparEn($personaje, $efecto);
                    break;
            }
        }
    }

    /**
     * Equipar item en el personaje
     */
    private function equiparEn($personaje, $efecto)
    {
        $itemsEquipados = $personaje->items_equipados ?? [];
        $slot = $efecto['slot'] ?? 'misc';
        
        $itemsEquipados[$slot] = [
            'id_item' => $this->id,
            'nombre' => $this->nombre,
            'efectos' => $efecto['stats'] ?? [],
            'fecha_equipado' => now()->toISOString(),
        ];
        
        $personaje->items_equipados = $itemsEquipados;
        $personaje->save();
    }

    /**
     * Scope para items disponibles
     */
    public function scopeDisponibles($query)
    {
        return $query->where('disponible', true);
    }

    /**
     * Scope para items por categoría
     */
    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    /**
     * Scope para items en stock
     */
    public function scopeEnStock($query)
    {
        return $query->where(function($q) {
            $q->where('stock_limitado', false)
              ->orWhere('stock_actual', '>', 0);
        });
    }
}
