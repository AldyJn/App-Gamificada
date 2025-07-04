<?php
// app/Http/Controllers/WelcomeController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    /**
     * Página de bienvenida principal
     */
    public function index()
    {
        // Si el usuario ya está autenticado, redirigir al dashboard
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->esDocente()) {
                return redirect()->route('profesor.dashboard');
            } elseif ($user->esEstudiante()) {
                return redirect()->route('estudiante.dashboard');
            }
            
            return redirect()->route('dashboard');
        }

        // Datos para el componente Vue (opcionales, tu componente usa datos demo)
        $data = [
            'features' => [
                [
                    'title' => 'Sistema de Clases RPG',
                    'description' => 'Convierte tu aula en una aventura épica donde cada estudiante es un Guardián.',
                    'icon' => '🎮'
                ],
                [
                    'title' => 'Gamificación Educativa',
                    'description' => 'Niveles, experiencia, logros y recompensas que motivan el aprendizaje.',
                    'icon' => '⭐'
                ],
                [
                    'title' => 'Gestión Intuitiva',
                    'description' => 'Dashboard estilo Destiny para profesores y estudiantes.',
                    'icon' => '📊'
                ],
                [
                    'title' => 'Comportamientos y Puntos',
                    'description' => 'Sistema de recompensas y consecuencias gamificado.',
                    'icon' => '🏆'
                ]
            ],
            'stats' => [
                'estudiantes' => '1000+',
                'clases' => '50+',
                'profesores' => '25+',
                'logros' => '100+'
            ]
        ];

        // Usar Inertia para renderizar el componente Vue
        return Inertia::render('Welcome/Index', $data);
    }

    /**
     * Página sobre nosotros
     */
    public function about()
    {
        $data = [
            'team' => [
                [
                    'name' => 'Equipo ClassCraft',
                    'role' => 'Desarrolladores',
                    'description' => 'Creando el futuro de la educación gamificada'
                ]
            ]
        ];

        return Inertia::render('Welcome/About', $data);
    }

    /**
     * Página de características
     */
    public function features()
    {
        $data = [
            'features' => [
                [
                    'category' => 'Para Profesores',
                    'items' => [
                        'Dashboard estilo Destiny con vista espacial',
                        'Gestión de clases y estudiantes',
                        'Sistema de comportamientos y puntos',
                        'Reportes y estadísticas en tiempo real',
                        'Códigos QR para unirse a clases',
                        'Selector aleatorio de estudiantes'
                    ]
                ],
                [
                    'category' => 'Para Estudiantes',
                    'items' => [
                        'Personajes RPG personalizables',
                        'Sistema de niveles y experiencia',
                        'Logros y badges coleccionables',
                        'Rankings y competencias',
                        'Inventario y recompensas',
                        'Progreso visual gamificado'
                    ]
                ],
                [
                    'category' => 'Sistema de Juego',
                    'items' => [
                        'Clases RPG: Titán, Cazador, Hechicero',
                        'Sistema de salud, energía y luz',
                        'Habilidades especiales únicas',
                        'Eventos y misiones especiales',
                        'Modo competitivo entre clases',
                        'Integración con actividades académicas'
                    ]
                ]
            ]
        ];

        return Inertia::render('Welcome/Features', $data);
    }

    /**
     * Página de contacto
     */
    public function contact()
    {
        return Inertia::render('Welcome/Contact');
    }

    /**
     * Procesar formulario de contacto
     */
    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000'
        ]);

        // TODO: Implementar envío de email o almacenamiento en BD
        // Por ahora solo simulamos el envío exitoso
        
        return back()->with('success', 'Mensaje enviado exitosamente. Te contactaremos pronto.');
    }
}