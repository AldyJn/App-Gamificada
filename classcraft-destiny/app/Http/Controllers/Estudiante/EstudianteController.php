<?php

namespace App\Http\Controllers\Estudiante;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EstudianteController extends Controller
{
    /**
     * Dashboard principal del estudiante
     */
    public function index()
    {
        try {
            $user = Auth::user();
            
            return Inertia::render('Dashboard/ConfigurarPerfil', [
                'usuario' => [
                    'id' => $user->id,
                    'nombre' => $user->nombre,
                    'apellido' => $user->apellido ?? '',
                    'correo' => $user->correo,
                ],
                'mensaje' => 'Dashboard de estudiante funcionando'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en EstudianteController@index: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Error interno',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    /**
     * Mostrar perfil del estudiante - VERSIÓN SIMPLE
     */
    public function perfil()
    {
        try {
            $user = Auth::user();
            
            // Datos mínimos para evitar errores
            $datos = [
                'usuario' => [
                    'id' => $user->id,
                    'nombre' => $user->nombre,
                    'apellido' => $user->apellido ?? 'Sin apellido',
                    'correo' => $user->correo,
                    'avatar_url' => null,
                    'ultimo_acceso' => $user->ultimo_acceso,
                ],
                'estudiante' => [
                    'id' => $user->estudiante->id ?? 'No definido',
                    'codigo_estudiante' => 'EST-' . $user->id,
                    'grado' => 'No especificado',
                    'seccion' => 'No especificada',
                    'fecha_registro' => $user->created_at,
                ],
                'estadisticas' => [
                    'clases_inscritas' => 0,
                    'actividades_completadas' => 0,
                    'nivel_promedio' => 1,
                    'experiencia_total' => 0,
                ],
                'clases_recientes' => [],
                'logros_recientes' => [],
            ];

            return Inertia::render('Estudiante/Perfil', $datos);
            
        } catch (\Exception $e) {
            \Log::error('Error en EstudianteController@perfil: ' . $e->getMessage());
            
            // Devolver respuesta de error en formato JSON para debugging
            return response()->json([
                'error' => 'Error en perfil de estudiante',
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Mis clases - VERSIÓN SIMPLE
     */
    public function misClases()
    {
        try {
            $user = Auth::user();
            
            return Inertia::render('Dashboard/ConfigurarPerfil', [
                'usuario' => [
                    'nombre' => $user->nombre,
                    'correo' => $user->correo,
                ],
                'mensaje' => 'Mis clases - En desarrollo'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error en EstudianteController@misClases: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Progreso - VERSIÓN SIMPLE
     */
    public function progreso()
    {
        try {
            $user = Auth::user();
            
            return Inertia::render('Dashboard/ConfigurarPerfil', [
                'usuario' => [
                    'nombre' => $user->nombre,
                    'correo' => $user->correo,
                ],
                'mensaje' => 'Progreso - En desarrollo'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error en EstudianteController@progreso: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Dashboard específico - ALIAS DE INDEX
     */
    public function dashboard()
    {
        return $this->index();
    }
}