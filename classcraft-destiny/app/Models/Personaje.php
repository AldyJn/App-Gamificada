<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personaje extends Model
{
    use HasFactory;

    protected $table = 'personaje';

    protected $fillable = [
        'id_estudiante',
        'id_clase',
        'id_clase_rpg',
        'nombre',
        'nivel',
        'experiencia',
        'salud_actual',
        'salud_maxima',
        'monedas',
        'avatar_personalizado',
        'estadisticas',
        'habilidades_desbloqueadas',
        'items_equipados',
    ];

    protected $casts = [
        'nivel' => 'integer',
        'experiencia' => 'integer',
        'salud_actual' => 'integer',
        'salud_maxima' => 'integer',
        'monedas' => 'integer',
        'estadisticas' => 'array',
        'habilidades_desbloqueadas' => 'array',
        'items_equipados' => 'array',
    ];

    /**
     * Relación con estudiante
     */
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante');
    }

    /**
     * Relación con clase
     */
    public function clase()
    {
        return $this->belongsTo(Clase::class, 'id_clase');
    }

    /**
     * Relación con clase RPG
     */
    public function claseRpg()
    {
        return $this->belongsTo(ClaseRpg::class, 'id_clase_rpg');
    }

    /**
     * Relación con transacciones de moneda
     */
    public function transacciones()
    {
        return $this->hasMany(TransaccionMoneda::class, 'id_personaje');
    }

    /**
     * Ganar experiencia
     */
    public function ganarExperiencia($cantidad, $motivo = null)
    {
        $nivelAnterior = $this->nivel;
        $this->experiencia += $cantidad;
        
        // Verificar si sube de nivel
        $nuevoNivel = $this->calcularNivelPorExperiencia($this->experiencia);
        if ($nuevoNivel > $this->nivel) {
            $this->subirNivel($nuevoNivel);
        }
        
        $this->save();
        
        // Registrar la transacción de experiencia si es necesario
        // TransaccionExperiencia::create([...]);
        
        return [
            'experiencia_ganada' => $cantidad,
            'nivel_anterior' => $nivelAnterior,
            'nivel_actual' => $this->nivel,
            'subio_nivel' => $nuevoNivel > $nivelAnterior,
        ];
    }

    /**
     * Ganar/perder monedas
     */
    public function ajustarMonedas($cantidad, $motivo, $tipo = 'ganancia')
    {
        $cantidadAnterior = $this->monedas;
        $this->monedas = max(0, $this->monedas + $cantidad);
        $this->save();
        
        // Registrar transacción
        TransaccionMoneda::create([
            'id_personaje' => $this->id,
            'cantidad' => $cantidad,
            'tipo' => $tipo,
            'motivo' => $motivo,
            'saldo_anterior' => $cantidadAnterior,
            'saldo_nuevo' => $this->monedas,
        ]);
        
        return $this->monedas;
    }

    /**
     * Ajustar salud
     */
    public function ajustarSalud($cantidad, $motivo = null)
    {
        $this->salud_actual = max(0, min($this->salud_maxima, $this->salud_actual + $cantidad));
        $this->save();
        
        return $this->salud_actual;
    }

    /**
     * Calcular nivel basado en experiencia
     */
    private function calcularNivelPorExperiencia($experiencia)
    {
        // Lógica simple: cada 100 XP = 1 nivel
        // Puedes hacer esto más complejo consultando la tabla nivel_experiencia
        return max(1, floor($experiencia / 100) + 1);
    }

    /**
     * Subir de nivel
     */
    private function subirNivel($nuevoNivel)
    {
        $nivelesGanados = $nuevoNivel - $this->nivel;
        $this->nivel = $nuevoNivel;
        
        // Bonificaciones por subir de nivel
        $bonificacionMonedas = $nivelesGanados * ($this->clase->obtenerConfiguracion('monedas_por_nivel', 25));
        $bonificacionSalud = $nivelesGanados * 10;
        
        $this->monedas += $bonificacionMonedas;
        $this->salud_maxima += $bonificacionSalud;
        $this->salud_actual = $this->salud_maxima; // Restaurar salud completa
        
        // Registrar transacción de monedas por nivel
        if ($bonificacionMonedas > 0) {
            TransaccionMoneda::create([
                'id_personaje' => $this->id,
                'cantidad' => $bonificacionMonedas,
                'tipo' => 'ganancia',
                'motivo' => "Subida de nivel (Nivel $nuevoNivel)",
                'saldo_anterior' => $this->monedas - $bonificacionMonedas,
                'saldo_nuevo' => $this->monedas,
            ]);
        }
    }

    /**
     * Obtener experiencia requerida para el siguiente nivel
     */
    public function getExperienciaProximoNivelAttribute()
    {
        return ($this->nivel) * 100; // Fórmula simple
    }

    /**
     * Obtener progreso hacia el siguiente nivel (0-100)
     */
    public function getProgresoNivelAttribute()
    {
        $xpNivelActual = ($this->nivel - 1) * 100;
        $xpProximoNivel = $this->nivel * 100;
        $xpEnNivel = $this->experiencia - $xpNivelActual;
        $xpRequeridoNivel = $xpProximoNivel - $xpNivelActual;
        
        return $xpRequeridoNivel > 0 ? min(100, ($xpEnNivel / $xpRequeridoNivel) * 100) : 0;
    }

    /**
     * Obtener stats totales (base + bonificaciones)
     */
    public function getStatsTotalesAttribute()
    {
        $statsBase = $this->claseRpg->stats_base ?? [];
        $bonificacionesNivel = $this->obtenerBonificacionesNivel();
        $bonificacionesItems = $this->obtenerBonificacionesItems();
        
        $stats = [];
        foreach (['salud', 'ataque', 'defensa', 'velocidad'] as $stat) {
            $stats[$stat] = ($statsBase[$stat] ?? 0) + 
                           ($bonificacionesNivel[$stat] ?? 0) + 
                           ($bonificacionesItems[$stat] ?? 0);
        }
        
        return $stats;
    }

    /**
     * Obtener bonificaciones por nivel
     */
    private function obtenerBonificacionesNivel()
    {
        $multiplicadorNivel = max(0, $this->nivel - 1) * 0.1;
        $statsBase = $this->claseRpg->stats_base ?? [];
        
        $bonificaciones = [];
        foreach ($statsBase as $stat => $valor) {
            $bonificaciones[$stat] = round($valor * $multiplicadorNivel);
        }
        
        return $bonificaciones;
    }

    /**
     * Obtener bonificaciones por items equipados
     */
    private function obtenerBonificacionesItems()
    {
        // Por ahora retorna array vacío, se implementará cuando se haga el sistema de items
        return [];
    }

    /**
     * Verificar si puede usar una habilidad específica
     */
    public function puedeUsarHabilidad($habilidad)
    {
        $habilidadesDesbloqueadas = $this->habilidades_desbloqueadas ?? [];
        return in_array($habilidad, $habilidadesDesbloqueadas);
    }

    /**
     * Scope para personajes de una clase específica
     */
    public function scopePorClase($query, $claseId)
    {
        return $query->where('id_clase', $claseId);
    }

    /**
     * Scope para ranking de nivel
     */
    public function scopeRankingNivel($query)
    {
        return $query->orderByDesc('nivel')->orderByDesc('experiencia');
    }
}