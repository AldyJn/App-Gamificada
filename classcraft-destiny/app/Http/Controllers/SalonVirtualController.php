<?php
// app/Http/Controllers/SalonVirtualController.php
namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Personaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SalonVirtualController extends Controller
{
    public function index(Clase $clase)
    {
        $user = Auth::user();
        
        // Verificar acceso a la clase
        if ($user->esDocente()) {
            abort_unless($clase->id_docente === $user->docente->id, 403);
            
            $estudiantes = $clase->estudiantes()
                ->with(['usuario', 'personajes' => function($query) use ($clase) {
                    $query->where('id_clase', $clase->id)->with('claseRpg');
                }])
                ->wherePivot('activo', true)
                ->get()
                ->map(function($estudiante) {
                    $personaje = $estudiante->personajes->first();
                    return [
                        'id' => $estudiante->id,
                        'nombre' => $estudiante->usuario->nombre,
                        'personaje' => $personaje ? [
                            'id' => $personaje->id,
                            'nombre' => $personaje->nombre,
                            'nivel' => $personaje->nivel,
                            'clase_rpg' => $personaje->claseRpg->nombre,
                            'avatar' => $personaje->avatar_base,
                            'posicion' => [
                                'x' => rand(100, 800),
                                'y' => rand(100, 600)
                            ]
                        ] : null
                    ];
                });
                
            return Inertia::render('SalonVirtual/Profesor', [
                'clase' => $clase,
                'estudiantes' => $estudiantes,
                'configuracion' => [
                    'permitir_movimiento' => true,
                    'mostrar_niveles' => true,
                    'tema_salon' => 'destiny'
                ]
            ]);
        } else {
            // Verificar que el estudiante esté inscrito
            $inscripcion = $clase->estudiantes()
                ->where('estudiante.id', $user->estudiante->id)
                ->wherePivot('activo', true)
                ->first();
                
            abort_unless($inscripcion, 403);
            
            $miPersonaje = Personaje::where('id_clase', $clase->id)
                ->where('id_estudiante', $user->estudiante->id)
                ->with('claseRpg')
                ->first();
                
            abort_unless($miPersonaje, 403, 'Debes crear un personaje primero');
            
            // Obtener compañeros de clase
            $companeros = $clase->estudiantes()
                ->where('estudiante.id', '!=', $user->estudiante->id)
                ->with(['usuario', 'personajes' => function($query) use ($clase) {
                    $query->where('id_clase', $clase->id)->with('claseRpg');
                }])
                ->wherePivot('activo', true)
                ->get()
                ->map(function($estudiante) {
                    $personaje = $estudiante->personajes->first();
                    return [
                        'id' => $estudiante->id,
                        'nombre' => $estudiante->usuario->nombre,
                        'personaje' => $personaje ? [
                            'nombre' => $personaje->nombre,
                            'nivel' => $personaje->nivel,
                            'clase_rpg' => $personaje->claseRpg->nombre,
                            'avatar' => $personaje->avatar_base
                        ] : null
                    ];
                });
            
            return Inertia::render('SalonVirtual/Estudiante', [
                'clase' => $clase->load('docente.usuario'),
                'mi_personaje' => [
                    'id' => $miPersonaje->id,
                    'nombre' => $miPersonaje->nombre,
                    'nivel' => $miPersonaje->nivel,
                    'experiencia' => $miPersonaje->experiencia,
                    'clase_rpg' => $miPersonaje->claseRpg->nombre,
                    'avatar' => $miPersonaje->avatar_base
                ],
                'companeros' => $companeros
            ]);
        }
    }
}