<?php
// app/Models/Personaje.php

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
        'nombre_personaje',
        'nivel',
        'experiencia_actual',
        'salud_actual',
        'salud_maxima',
        'energia_actual',
        'energia_maxima',
        'luz_actual',
        'luz_maxima',
        'principal',
        'aspecto_visual',
        'estadisticas_personaje',
        'habilidades_desbloqueadas',
        'equipamiento_actual',
        'prestigio'
    ];

    protected $casts = [
        'principal' => 'boolean',
        'aspecto_visual' => 'array',
        'estadisticas_personaje' => 'array',
        'habilidades_desbloqueadas' => 'array',
        'equipamiento_actual' => 'array'
    ];

    // Relaciones
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante');
    }

    public function clase()
    {
        return $this->belongsTo(Clase::class, 'id_clase');
    }

    public function claseRpg()
    {
        return $this->belongsTo(ClaseRpg::class, 'id_clase_rpg');
    }

    // Métodos de utilidad
    // public function ganarExperiencia(int $cantidad): bool
    // {
    //     $this->experiencia_actual += $cantidad;
        
    //     // Verificar si sube de nivel
    //     $nivelRequerido = NivelExperiencia::where('nivel', $this->nivel + 1)->first();
        
    //     if ($nivelRequerido && $this->experiencia_actual >= $nivelRequerido->experiencia_requerida) {
    //         $this->subirNivel();
    //     }
        
    //     return $this->save();
    // }

    protected function subirNivel(): void
    {
        $this->nivel++;
        
        // Aumentar estadísticas base según la clase
        $incrementos = $this->claseRpg->incrementos_nivel;
        $this->salud_maxima += $incrementos['salud'] ?? 10;
        $this->energia_maxima += $incrementos['energia'] ?? 5;
        $this->luz_maxima += $incrementos['luz'] ?? 3;
        
        // Restaurar recursos al subir de nivel
        $this->salud_actual = $this->salud_maxima;
        $this->energia_actual = $this->energia_maxima;
        $this->luz_actual = $this->luz_maxima;
    }

    public function aplicarComportamiento(TipoComportamiento $comportamiento): void
    {
        $this->salud_actual = max(0, min($this->salud_maxima, 
            $this->salud_actual + $comportamiento->puntos_salud));
        
        $this->energia_actual = max(0, min($this->energia_maxima, 
            $this->energia_actual + $comportamiento->puntos_energia));
        
        $this->luz_actual = max(0, min($this->luz_maxima, 
            $this->luz_actual + $comportamiento->puntos_luz));
        
        $this->save();
    }

    public function porcentajeVida(): float
    {
        return ($this->salud_actual / $this->salud_maxima) * 100;
    }

    public function puedeUsarHabilidad(string $habilidad): bool
    {
        return in_array($habilidad, $this->habilidades_desbloqueadas ?? []);
    }
}