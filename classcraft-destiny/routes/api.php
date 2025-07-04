<?php
// routes/api.php - CORREGIDO CON CONTROLADORES EXISTENTES

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Profesor\ProfesorController;
use App\Http\Controllers\EstudianteController;  
use App\Http\Controllers\Estudiante\{
    PersonajeController,
    RankingController,
    LogroController
};
/*
|--------------------------------------------------------------------------
| API Routes - ClassCraft Destiny System
|--------------------------------------------------------------------------
*/

// ===== AUTENTICACIÓN API =====
Route::prefix('auth')->name('api.auth.')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/me', [AuthController::class, 'me'])->name('me');
        Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    });
});

// ===== RUTAS AUTENTICADAS =====
Route::middleware('auth:sanctum')->group(function () {
    
    // ===== API PROFESOR =====
    Route::middleware('role:profesor')->prefix('profesor')->name('api.profesor.')->group(function () {
        
        // Dashboard y estadísticas
        Route::get('/dashboard', [ProfesorController::class, 'dashboard'])->name('dashboard');
        Route::get('/estadisticas', [ProfesorController::class, 'estadisticas'])->name('estadisticas');
        
        // Gestión de clases
        Route::get('/clases', [ProfesorController::class, 'misClases'])->name('clases.index');
        Route::post('/clases', [ProfesorController::class, 'crearClase'])->name('clases.store');
        Route::get('/clases/{clase}', [ProfesorController::class, 'mostrarClase'])->name('clases.show');
        Route::put('/clases/{clase}', [ProfesorController::class, 'actualizarClase'])->name('clases.update');
        Route::delete('/clases/{clase}', [ProfesorController::class, 'eliminarClase'])->name('clases.destroy');
        
        // Gestión de estudiantes en clase
        Route::post('/clases/{clase}/estudiantes', [ProfesorController::class, 'agregarEstudiante'])
             ->name('clases.estudiantes.add');
        Route::delete('/clases/{clase}/estudiantes/{estudiante}', [ProfesorController::class, 'removerEstudiante'])
             ->name('clases.estudiantes.remove');
        Route::get('/clases/{clase}/estudiante-aleatorio', [ProfesorController::class, 'estudianteAleatorio'])
             ->name('clases.estudiante-aleatorio');
        
        // Sistema de comportamientos
        Route::post('/clases/{clase}/comportamientos', [ProfesorController::class, 'aplicarComportamiento'])
             ->middleware('throttle:20,1')
             ->name('clases.comportamientos.apply');
        Route::get('/clases/{clase}/comportamientos/historial', [ProfesorController::class, 'historialComportamientos'])
             ->name('clases.comportamientos.history');
        
        // Herramientas
        Route::get('/estudiantes/buscar', [ProfesorController::class, 'buscarEstudiantes'])
             ->name('estudiantes.search');
        Route::post('/clases/{clase}/regenerar-qr', [ProfesorController::class, 'regenerarQR'])
             ->name('clases.regenerate-qr');
        Route::get('/clases/{clase}/exportar', [ProfesorController::class, 'exportarDatos'])
             ->name('clases.export');
    });
    
    // ===== API ESTUDIANTE =====
    Route::middleware('role:estudiante')->prefix('estudiante')->name('api.estudiante.')->group(function () {
        
        // Dashboard y perfil
        Route::get('/dashboard', [EstudianteController::class, 'dashboard'])->name('dashboard');
        Route::get('/perfil', [EstudianteController::class, 'perfil'])->name('profile');
        Route::put('/perfil', [EstudianteController::class, 'actualizarPerfil'])->name('profile.update');
        
        // Gestión de clases
        Route::get('/clases', [EstudianteController::class, 'misClases'])->name('clases.index');
        Route::post('/clases/unirse', [EstudianteController::class, 'unirseClase'])
             ->middleware('throttle:join-class')
             ->name('clases.join');
        Route::post('/clases/{clase}/salir', [EstudianteController::class, 'salirDeClase'])
             ->name('clases.leave');
        
        // Gestión de personajes
        Route::get('/personajes', [EstudianteController::class, 'misPersonajes'])->name('personajes.index');
        Route::post('/clases/{clase}/personajes', [EstudianteController::class, 'crearPersonaje'])
             ->name('personajes.create');
     //    Route::get('/personajes/{personaje}', [PersonajeController::class, 'show'])
     //         ->name('personajes.show');
     //    Route::put('/personajes/{personaje}/personalizar', [PersonajeController::class, 'personalizar'])
     //         ->name('personajes.customize');
        
        // Rankings y logros
     //    Route::get('/rankings', [RankingController::class, 'index'])->name('rankings');
     //    Route::get('/rankings/{clase}', [RankingController::class, 'rankingClase'])->name('rankings.clase');
     //    Route::get('/logros', [LogroController::class, 'index'])->name('achievements');
        Route::get('/historial', [EstudianteController::class, 'historial'])->name('history');
        
        // Notificaciones (si existen métodos)
        Route::get('/notificaciones', [EstudianteController::class, 'notificaciones'])->name('notifications');
        Route::put('/notificaciones/{notificacion}/leer', [EstudianteController::class, 'marcarNotificacionLeida'])
             ->name('notifications.read');
        Route::put('/notificaciones/leer-todas', [EstudianteController::class, 'marcarTodasLeidas'])
             ->name('notifications.read-all');
    });
    
//     // ===== API PERSONAJES =====
//     Route::prefix('personajes')->name('api.personajes.')->group(function () {
        
//         // Estadísticas y datos básicos
//         Route::get('/{personaje}', [PersonajeController::class, 'show'])
//              ->middleware('can:view,personaje')
//              ->name('show');
//         Route::get('/{personaje}/estadisticas', [PersonajeController::class, 'estadisticas'])
//              ->middleware('can:view,personaje')
//              ->name('stats');
        
//         // Sistema de experiencia y niveles
//         Route::post('/{personaje}/experiencia', [PersonajeController::class, 'ganarExperiencia'])
//              ->middleware('can:update,personaje')
//              ->middleware('throttle:20,1')
//              ->name('gain-xp');
        
//         // Sistema de habilidades
//         Route::post('/{personaje}/habilidades/{habilidad}', [PersonajeController::class, 'usarHabilidad'])
//              ->middleware('can:usar-habilidad,personaje')
//              ->middleware('throttle:use-ability')
//              ->name('use-ability');
//         Route::get('/{personaje}/habilidades/disponibles', [PersonajeController::class, 'habilidadesDisponibles'])
//              ->middleware('can:view,personaje')
//              ->name('available-abilities');
        
//         // Sistema de equipamiento
//         Route::put('/{personaje}/equipamiento', [PersonajeController::class, 'cambiarEquipamiento'])
//              ->middleware('can:update,personaje')
//              ->name('change-equipment');
//         Route::get('/{personaje}/equipamiento/disponible', [PersonajeController::class, 'equipamientoDisponible'])
//              ->middleware('can:view,personaje')
//              ->name('available-equipment');
        
//         // Interacciones sociales
//         Route::post('/{personaje}/revivir/{objetivo}', [PersonajeController::class, 'revivirCompañero'])
//              ->middleware('can:usar-habilidad,personaje')
//              ->middleware('throttle:use-ability')
//              ->name('revive-teammate');
//         Route::post('/{personaje}/curar', [PersonajeController::class, 'autoConsumirse'])
//              ->middleware('can:usar-habilidad,personaje')
//              ->middleware('throttle:use-ability')
//              ->name('self-heal');
//     });
    
    // ===== API UTILIDADES GENERALES =====
    Route::prefix('utils')->name('api.utils.')->group(function () {
        
        // Datos del usuario actual
        Route::get('/me', function (Request $request) {
            $usuario = $request->user();
            return response()->json([
                'id' => $usuario->id,
                'nombre_completo' => $usuario->nombreCompleto(),
                'correo' => $usuario->correo,
                'avatar' => $usuario->avatar,
                'tipo_usuario' => $usuario->tipoUsuario->nombre,
                'configuracion_ui' => $usuario->configuracion_ui,
                'ultimo_acceso' => $usuario->ultimo_acceso,
                'timezone' => $usuario->timezone,
            ]);
        })->name('me');
        
        // Configuración global del sistema
        Route::get('/config', function () {
            return response()->json([
                'app_name' => config('app.name'),
                'version' => '1.0.0',
                'tema_destiny' => [
                    'colores' => [
                        'primary' => '#1B1C29',
                        'accent_gold' => '#C7B88A',
                        'accent_cyan' => '#6EC1E4',
                        'accent_purple' => '#B6A1E4',
                    ],
                    'fuentes' => [
                        'headers' => 'Orbitron',
                        'body' => 'Open Sans',
                    ]
                ],
                'limites' => [
                    'max_nivel' => 100,
                    'max_clases_por_profesor' => 10,
                    'max_personajes_por_estudiante' => 3,
                ]
            ]);
        })->name('config');
        
        // Tipos de comportamiento disponibles
        Route::get('/comportamientos', function () {
            return response()->json(\App\Models\TipoComportamiento::all());
        })->name('behaviors');
        
        // Niveles de experiencia
        Route::get('/niveles-experiencia', function () {
            return response()->json(\App\Models\NivelExperiencia::all());
        })->name('experience-levels');
    });
});

// ===== WEBHOOKS Y INTEGRACIONES (SIN AUTENTICACIÓN) =====
Route::prefix('webhooks')->name('api.webhooks.')->group(function () {
    
    // Webhook para sistemas externos (LMS, etc.)
    Route::post('/lms-sync', function (Request $request) {
        // TODO: Implementar sincronización con LMS externos
        return response()->json(['status' => 'received']);
    })->name('lms-sync');
    
    // Webhook para notificaciones push
    Route::post('/notifications', function (Request $request) {
        // TODO: Implementar sistema de notificaciones push
        return response()->json(['status' => 'received']);
    })->name('notifications');
});

// ===== RUTAS DE SALUD Y MONITOREO =====
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0',
        'environment' => app()->environment(),
        'database' => \Illuminate\Support\Facades\DB::connection()->getPdo() ? 'connected' : 'disconnected',
    ]);
})->name('api.health');

Route::get('/metrics', function () {
    return response()->json([
        'total_usuarios' => \App\Models\Usuario::count(),
        'total_clases' => \App\Models\Clase::count(),
        'total_personajes' => \App\Models\Personaje::count(),
        'clases_activas' => \App\Models\Clase::where('activo', true)->count(),
        'estudiantes_activos' => \App\Models\Usuario::where('id_tipo_usuario', 2)
                                                   ->where('ultimo_acceso', '>=', now()->subDays(7))
                                                   ->count(),
    ]);
})->middleware('auth:sanctum')->name('api.metrics');