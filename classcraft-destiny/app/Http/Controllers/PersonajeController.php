<?php
// app/Http/Controllers/PersonajeController.php

namespace App\Http\Controllers;

use App\Models\{Personaje, TipoComportamiento, NivelExperiencia};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonajeController extends Controller
{
    /**
     * Obtener estadísticas detalladas del personaje
     */
    public function estadisticas(Personaje $personaje)
    {
        $this->authorize('view', $personaje);

        // Calcular experiencia necesaria para siguiente nivel
        $siguienteNivel = NivelExperiencia::where('nivel', $personaje->nivel + 1)->first();
        $experienciaParaSiguienteNivel = $siguienteNivel ? 
            $siguienteNivel->experiencia_requerida - $personaje->experiencia_actual : 0;

        return response()->json([
            'personaje' => [
                'id' => $personaje->id,
                'nombre' => $personaje->nombre_personaje,
                'nivel' => $personaje->nivel,
                'experiencia_actual' => $personaje->experiencia_actual,
                'experiencia_siguiente_nivel' => $experienciaParaSiguienteNivel,
                'porcentaje_experiencia' => $siguienteNivel ? 
                    (($personaje->experiencia_actual / $siguienteNivel->experiencia_requerida) * 100) : 100,
                'estadisticas' => [
                    'salud' => [
                        'actual' => $personaje->salud_actual,
                        'maxima' => $personaje->salud_maxima,
                        'porcentaje' => $personaje->porcentajeVida()
                    ],
                    'energia' => [
                        'actual' => $personaje->energia_actual,
                        'maxima' => $personaje->energia_maxima,
                        'porcentaje' => ($personaje->energia_actual / $personaje->energia_maxima) * 100
                    ],
                    'luz' => [
                        'actual' => $personaje->luz_actual,
                        'maxima' => $personaje->luz_maxima,
                        'porcentaje' => ($personaje->luz_actual / $personaje->luz_maxima) * 100
                    ]
                ],
                'clase_rpg' => [
                    'nombre' => $personaje->claseRpg->nombre,
                    'icono' => $personaje->claseRpg->icono,
                    'color_primario' => $personaje->claseRpg->color_primario
                ],
                'habilidades_desbloqueadas' => $personaje->habilidades_desbloqueadas,
                'equipamiento' => $personaje->equipamiento_actual,
                'aspecto_visual' => $personaje->aspecto_visual,
                'prestigio' => $personaje->prestigio ?? 0
            ]
        ]);
    }

    /**
     * Ganar experiencia
     */
    public function ganarExperiencia(Request $request, Personaje $personaje)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1|max:10000',
            'razon' => 'required|string|max:255'
        ]);

        $this->authorize('update', $personaje);

        $nivelAnterior = $personaje->nivel;
        $experienciaAnterior = $personaje->experiencia_actual;

        $personaje->ganarExperiencia($request->cantidad);

        $subioNivel = $personaje->nivel > $nivelAnterior;

        return response()->json([
            'success' => true,
            'message' => "¡{$request->cantidad} XP ganados!",
            'subio_nivel' => $subioNivel,
            'nivel_anterior' => $nivelAnterior,
            'nivel_actual' => $personaje->nivel,
            'experiencia_anterior' => $experienciaAnterior,
            'experiencia_actual' => $personaje->experiencia_actual,
            'nuevas_habilidades' => $subioNivel ? $this->obtenerHabilidadesDesbloqueadas($personaje) : []
        ]);
    }

    /**
     * Usar habilidad especial
     */
    public function usarHabilidad(Request $request, Personaje $personaje)
    {
        $request->validate([
            'habilidad' => 'required|string',
            'objetivo' => 'nullable|exists:personaje,id'
        ]);

        $this->authorize('update', $personaje);

        if (!$personaje->puedeUsarHabilidad($request->habilidad)) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes esta habilidad desbloqueada.'
            ], 403);
        }

        // Lógica específica según la habilidad
        $resultado = $this->ejecutarHabilidad($personaje, $request->habilidad, $request->objetivo);

        return response()->json($resultado);
    }

    /**
     * Cambiar equipamiento del personaje
     */
    public function cambiarEquipamiento(Request $request, Personaje $personaje)
    {
        $request->validate([
            'tipo' => 'required|in:armadura,arma_primaria,arma_secundaria,artefacto,shader',
            'item_id' => 'required|string',
            'item_data' => 'array'
        ]);

        $this->authorize('update', $personaje);

        $equipamientoActual = $personaje->equipamiento_actual ?? [];
        $equipamientoActual[$request->tipo] = [
            'id' => $request->item_id,
            'data' => $request->item_data ?? [],
            'equipado_en' => now()->toISOString()
        ];

        $personaje->update(['equipamiento_actual' => $equipamientoActual]);

        return response()->json([
            'success' => true,
            'message' => 'Equipamiento actualizado exitosamente.',
            'equipamiento_actual' => $equipamientoActual
        ]);
    }

    /**
     * Obtener accesorios desbloqueables para el personaje
     */
    public function accesoriosDisponibles(Personaje $personaje)
    {
        $this->authorize('view', $personaje);

        // Accesorios base disponibles para todos
        $accesoriosBase = [
            'shaders' => [
                ['id' => 'default', 'nombre' => 'Guardián Básico', 'requisito' => 'Nivel 1'],
                ['id' => 'vanguard', 'nombre' => 'Vanguardia', 'requisito' => 'Nivel 5'],
                ['id' => 'crucible', 'nombre' => 'Crisol', 'requisito' => 'Nivel 10'],
            ],
            'armaduras' => [
                ['id' => 'base_helmet', 'nombre' => 'Casco Básico', 'requisito' => 'Nivel 1'],
                ['id' => 'base_chest', 'nombre' => 'Peto Básico', 'requisito' => 'Nivel 1'],
                ['id' => 'base_legs', 'nombre' => 'Grebas Básicas', 'requisito' => 'Nivel 1'],
            ]
        ];

        // Accesorios premium para notas altas (18-20)
        $accesoriosPremium = [
            'shaders' => [
                ['id' => 'trials_of_osiris', 'nombre' => 'Juicios de Osiris', 'requisito' => 'Nota 18+'],
                ['id' => 'raid_prestige', 'nombre' => 'Prestigio de Incursión', 'requisito' => 'Nota 19+'],
                ['id' => 'flawless', 'nombre' => 'Impecable', 'requisito' => 'Nota 20'],
            ],
            'emotes' => [
                ['id' => 'victory_dance', 'nombre' => 'Danza de Victoria', 'requisito' => 'Nota 18+'],
                ['id' => 'legendary_wave', 'nombre' => 'Saludo Legendario', 'requisito' => 'Nota 19+'],
                ['id' => 'exotic_celebration', 'nombre' => 'Celebración Exótica', 'requisito' => 'Nota 20'],
            ],
            'efectos' => [
                ['id' => 'aura_brillante', 'nombre' => 'Aura Brillante', 'requisito' => 'Nota 18+'],
                ['id' => 'trail_luz', 'nombre' => 'Rastro de Luz', 'requisito' => 'Nota 19+'],
                ['id' => 'explosion_luz', 'nombre' => 'Explosión de Luz', 'requisito' => 'Nota 20'],
            ]
        ];

        return response()->json([
            'accesorios_base' => $accesoriosBase,
            'accesorios_premium' => $accesoriosPremium,
            'nivel_actual' => $personaje->nivel,
            'puede_acceder_premium' => $this->puedeAccederPremium($personaje)
        ]);
    }

    /**
     * Ejecutar habilidad específica
     */
    private function ejecutarHabilidad(Personaje $personaje, string $habilidad, $objetivoId = null): array
    {
        switch ($habilidad) {
            case 'curar':
                return $this->ejecutarCuracion($personaje);
            
            case 'revivir':
                return $this->ejecutarRevivir($personaje, $objetivoId);
            
            case 'boost_energia':
                return $this->ejecutarBoostEnergia($personaje);
            
            default:
                return [
                    'success' => false,
                    'message' => 'Habilidad no implementada.'
                ];
        }
    }

    /**
     * Ejecutar curación
     */
    private function ejecutarCuracion(Personaje $personaje): array
    {
        if ($personaje->energia_actual < 20) {
            return [
                'success' => false,
                'message' => 'No tienes suficiente energía para usar esta habilidad.'
            ];
        }

        $curacion = min(30, $personaje->salud_maxima - $personaje->salud_actual);
        $personaje->salud_actual += $curacion;
        $personaje->energia_actual -= 20;
        $personaje->save();

        return [
            'success' => true,
            'message' => "¡Recuperaste {$curacion} puntos de salud!",
            'curacion' => $curacion,
            'estadisticas_actuales' => [
                'salud' => $personaje->salud_actual,
                'energia' => $personaje->energia_actual
            ]
        ];
    }

    /**
     * Ejecutar revivir compañero
     */
    private function ejecutarRevivir(Personaje $personaje, $objetivoId): array
    {
        if (!$objetivoId) {
            return [
                'success' => false,
                'message' => 'Debes seleccionar un compañero para revivir.'
            ];
        }

        $objetivo = Personaje::where('id', $objetivoId)
                             ->where('id_clase', $personaje->id_clase)
                             ->first();

        if (!$objetivo) {
            return [
                'success' => false,
                'message' => 'Compañero no encontrado.'
            ];
        }

        if ($objetivo->salud_actual > 0) {
            return [
                'success' => false,
                'message' => 'Este compañero no necesita ser revivido.'
            ];
        }

        if ($personaje->luz_actual < 50) {
            return [
                'success' => false,
                'message' => 'No tienes suficiente luz para revivir a tu compañero.'
            ];
        }

        $objetivo->salud_actual = $objetivo->salud_maxima * 0.5; // Revive con 50% de salud
        $objetivo->save();

        $personaje->luz_actual -= 50;
        $personaje->save();

        return [
            'success' => true,
            'message' => "¡Has revivido a {$objetivo->nombre_personaje}!",
            'objetivo_revivido' => [
                'nombre' => $objetivo->nombre_personaje,
                'salud_actual' => $objetivo->salud_actual
            ]
        ];
    }

    /**
     * Ejecutar boost de energía
     */
    private function ejecutarBoostEnergia(Personaje $personaje): array
    {
        $boost = min(40, $personaje->energia_maxima - $personaje->energia_actual);
        $personaje->energia_actual += $boost;
        $personaje->save();

        return [
            'success' => true,
            'message' => "¡Recuperaste {$boost} puntos de energía!",
            'boost' => $boost,
            'energia_actual' => $personaje->energia_actual
        ];
    }

    /**
     * Obtener habilidades desbloqueadas en el nuevo nivel
     */
    private function obtenerHabilidadesDesbloqueadas(Personaje $personaje): array
    {
        $nivelActual = NivelExperiencia::where('nivel', $personaje->nivel)->first();
        
        return $nivelActual && $nivelActual->poder_desbloqueado ? 
            [$nivelActual->poder_desbloqueado] : [];
    }

    /**
     * Verificar si puede acceder a accesorios premium
     */
    private function puedeAccederPremium(Personaje $personaje): bool
    {
        // TODO: Implementar sistema de calificaciones
        // Por ahora, simulamos basándose en el nivel
        return $personaje->nivel >= 18;
    }
}