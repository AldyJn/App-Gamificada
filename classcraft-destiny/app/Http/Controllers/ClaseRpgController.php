<?php
// app/Http/Controllers/ClaseRpgController.php

namespace App\Http\Controllers;

use App\Models\ClaseRpg;
use Illuminate\Http\Request;

class ClaseRpgController extends Controller
{
    /**
     * Obtener todas las clases RPG disponibles
     */
    public function index()
    {
        $clasesRpg = ClaseRpg::all()->map(function($clase) {
            return [
                'id' => $clase->id,
                'nombre' => $clase->nombre,
                'descripcion' => $clase->descripcion,
                'icono' => $clase->icono,
                'color_primario' => $clase->color_primario,
                'color_secundario' => $clase->color_secundario,
                'estadisticas_base' => [
                    'salud' => $clase->salud_base,
                    'energia' => $clase->energia_base,
                    'luz' => $clase->luz_base
                ],
                'estilo_combate' => $clase->estilo_combate,
                'ventajas' => $clase->ventajas,
                'habilidades_iniciales' => $clase->habilidades_iniciales,
                'requisitos_desbloqueo' => $clase->requisitos_desbloqueo
            ];
        });

        return response()->json($clasesRpg);
    }

    /**
     * Obtener detalles de una clase RPG específica
     */
    public function show(ClaseRpg $claseRpg)
    {
        return response()->json([
            'id' => $claseRpg->id,
            'nombre' => $claseRpg->nombre,
            'descripcion' => $claseRpg->descripcion,
            'icono' => $claseRpg->icono,
            'colores' => [
                'primario' => $claseRpg->color_primario,
                'secundario' => $claseRpg->color_secundario
            ],
            'estadisticas_base' => [
                'salud' => $claseRpg->salud_base,
                'energia' => $claseRpg->energia_base,
                'luz' => $claseRpg->luz_base
            ],
            'incrementos_nivel' => $claseRpg->incrementos_nivel,
            'estilo_combate' => $claseRpg->estilo_combate,
            'ventajas' => $claseRpg->ventajas,
            'habilidades_iniciales' => $claseRpg->habilidades_iniciales,
            'requisitos_desbloqueo' => $claseRpg->requisitos_desbloqueo,
            'total_personajes' => $claseRpg->personajes()->count()
        ]);
    }
}