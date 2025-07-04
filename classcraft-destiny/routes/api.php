<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí se registran las rutas API para la aplicación. Estas rutas son
| cargadas por RouteServiceProvider y se asignan al grupo middleware "api".
|
*/

// ==========================================
// RUTAS API PÚBLICAS
// ==========================================

// Información básica de la API
Route::get('/info', function () {
    return response()->json([
        'app' => config('app.name'),
        'version' => '1.0.0',
        'status' => 'active',
        'timestamp' => now()->toISOString(),
    ]);
});

// ==========================================
// RUTAS API PROTEGIDAS
// ==========================================
Route::middleware('auth:sanctum')->group(function () {
    
    // Usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Información del perfil
    Route::get('/profile', function (Request $request) {
        $user = $request->user();
        return response()->json([
            'id' => $user->id,
            'nombre' => $user->nombre,
            'apellido' => $user->apellido,
            'correo' => $user->correo,
            'tipo_usuario' => $user->tipo_usuario_string,
            'es_docente' => $user->esDocente(),
            'es_estudiante' => $user->esEstudiante(),
            'ultimo_acceso' => $user->ultimo_acceso,
        ]);
    });

    // Estadísticas del usuario
    Route::get('/stats', function (Request $request) {
        $user = $request->user();
        
        if ($user->esDocente()) {
            $totalClases = $user->clasesComoDocente()->count();
            $totalEstudiantes = $user->clasesComoDocente()
                                   ->withCount('estudiantes')
                                   ->get()
                                   ->sum('estudiantes_count');
            
            return response()->json([
                'total_clases' => $totalClases,
                'total_estudiantes' => $totalEstudiantes,
                'clases_activas' => $user->clasesComoDocente()->where('activo', true)->count(),
            ]);
        }
        
        if ($user->esEstudiante()) {
            $totalClases = $user->clasesComoEstudiante()->count();
            $personajes = $user->personajes();
            
            return response()->json([
                'total_clases' => $totalClases,
                'total_personajes' => $personajes->count(),
                'nivel_promedio' => $personajes->avg('nivel') ?? 0,
            ]);
        }
        
        return response()->json([
            'message' => 'No hay estadísticas disponibles'
        ]);
    });
});

// ==========================================
// RUTAS API PARA WEBHOOKS O INTEGRACIONES
// ==========================================

// Webhook para notificaciones externas
Route::post('/webhook/notifications', function (Request $request) {
    // Aquí se procesarían notificaciones externas
    return response()->json(['status' => 'received']);
});

// ==========================================
// RUTAS DE SALUD Y MONITOREO
// ==========================================

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toISOString(),
        'services' => [
            'database' => 'operational',
            'cache' => 'operational',
            'storage' => 'operational',
        ]
    ]);
});

// Métricas básicas
Route::get('/metrics', function () {
    return response()->json([
        'users_count' => \App\Models\Usuario::count(),
        'classes_count' => \App\Models\Clase::count(),
        'active_sessions' => \Illuminate\Support\Facades\Session::getHandler()->read(session()->getId()) ? 1 : 0,
        'timestamp' => now()->toISOString(),
    ]);
});