<?php
// routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthApiController,
    ProfesorApiController,
    EstudianteApiController,
    PersonajeApiController,
    ClaseApiController
};

/*
|--------------------------------------------------------------------------
| API Routes - ClassCraft Destiny System
|--------------------------------------------------------------------------
*/

// ===== AUTENTICACIÓN API =====
Route::prefix('auth')->name('api.auth.')->group(function () {
    Route::post('/login', [AuthApiController::class, 'login'])->name('login');
    Route::post('/register', [AuthApiController::class, 'register'])->name('register');
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthApiController::class, 'logout'])->name('logout');
        Route::get('/me', [AuthApiController::class, 'me'])->name('me');
        Route::put('/profile', [AuthApiController::class, 'updateProfile'])->name('profile.update');
    });
});

// ===== RUTAS AUTENTICADAS =====
Route::middleware('auth:sanctum')->group(function () {
    
    // ===== API PROFESOR =====
    Route::middleware('role:profesor')->prefix('profesor')->name('api.profesor.')->group(function () {
        
        // Dashboard y estadísticas
        Route::get('/dashboard', [ProfesorApiController::class, 'dashboard'])->name('dashboard');
        Route::get('/estadisticas', [ProfesorApiController::class, 'estadisticas'])->name('estadisticas');
        
        // Gestión de clases
        Route::get('/clases', [ProfesorApiController::class, 'misClases'])->name('clases.index');
        Route::post('/clases', [ProfesorApiController::class, 'crearClase'])->name('clases.store');
        Route::get('/clases/{clase}', [ProfesorApiController::class, 'mostrarClase'])->name('clases.show');
        Route::put('/clases/{clase}', [ProfesorApiController::class, 'actualizarClase'])->name('clases.update');
        Route::delete('/clases/{clase}', [ProfesorApiController::class, 'eliminarClase'])->name('clases.destroy');
        
        // Gestión de estudiantes en clase
        Route::post('/clases/{clase}/estudiantes', [ProfesorApiController::class, 'agregarEstudiante'])
             ->name('clases.estudiantes.add');
        Route::delete('/clases/{clase}/estudiantes/{estudiante}', [ProfesorApiController::class, 'removerEstudiante'])
             ->name('clases.estudiantes.remove');
        Route::get('/clases/{clase}/estudiante-aleatorio', [ProfesorApiController::class, 'estudianteAleatorio'])
             ->name('clases.estudiante-aleatorio');
        
        // Sistema de comportamientos
        Route::post('/clases/{clase}/comportamientos', [ProfesorApiController::class, 'aplicarComportamiento'])
             ->middleware('throttle:20,1')
             ->name('clases.comportamientos.apply');
        Route::get('/clases/{clase}/comportamientos/historial', [ProfesorApiController::class, 'historialComportamientos'])
             ->name('clases.comportamientos.history');
        
        // Herramientas
        Route::get('/estudiantes/buscar', [ProfesorApiController::class, 'buscarEstudiantes'])
             ->name('estudiantes.search');
        Route::post('/clases/{clase}/regenerar-qr', [ProfesorApiController::class, 'regenerarQR'])
             ->name('clases.regenerate-qr');
        Route::get('/clases/{clase}/exportar', [ProfesorApiController::class, 'exportarDatos'])
             ->name('clases.export');
    });
    
    // ===== API ESTUDIANTE =====
    Route::middleware('role:estudiante')->prefix('estudiante')->name('api.estudiante.')->group(function () {
        
        // Dashboard y perfil
        Route::get('/dashboard', [EstudianteApiController::class, 'dashboard'])->name('dashboard');
        Route::get('/perfil', [EstudianteApiController::class, 'perfil'])->name('profile');
        Route::put('/perfil', [EstudianteApiController::class, 'actualizarPerfil'])->name('profile.update');
        
        // Gestión de clases
        Route::get('/clases', [EstudianteApiController::class, 'misClases'])->name('clases.index');
        Route::post('/clases/unirse', [EstudianteApiController::class, 'unirseClase'])
             ->middleware('throttle:join-class')
             ->name('clases.join');
        Route::post('/clases/{clase}/salir', [EstudianteApiController::class, 'salirDeClase'])
             ->name('clases.leave');
        
        // Gestión de personajes
        Route::get('/personajes', [EstudianteApiController::class, 'misPersonajes'])->name('personajes.index');
        Route::post('/clases/{clase}/personajes', [EstudianteApiController::class, 'crearPersonaje'])
             ->name('personajes.create');
        Route::get('/personajes/{personaje}', [EstudianteApiController::class, 'mostrarPersonaje'])
             ->name('personajes.show');
        Route::put('/personajes/{personaje}/personalizar', [EstudianteApiController::class, 'personalizarPersonaje'])
             ->name('personajes.customize');
        
        // Rankings y logros
        Route::get('/rankings', [EstudianteApiController::class, 'rankings'])->name('rankings');
        Route::get('/rankings/{clase}', [EstudianteApiController::class, 'rankingClase'])->name('rankings.clase');
        Route::get('/logros', [EstudianteApiController::class, 'logros'])->name('achievements');
        Route::get('/historial', [EstudianteApiController::class, 'historial'])->name('history');
        
        // Notificaciones
        Route::get('/notificaciones', [EstudianteApiController::class, 'notificaciones'])->name('notifications');
        Route::put('/notificaciones/{notificacion}/leer', [EstudianteApiController::class, 'marcarNotificacionLeida'])
             ->name('notifications.read');
        Route::put('/notificaciones/leer-todas', [EstudianteApiController::class, 'marcarTodasLeidas'])
             ->name('notifications.read-all');
    });
    
    // ===== API PERSONAJES (Accesible por propietario y profesor de la clase) =====
    Route::prefix('personajes')->name('api.personajes.')->group(function () {
        
        // Estadísticas y datos básicos
        Route::get('/{personaje}', [PersonajeApiController::class, 'show'])
             ->middleware('can:view,personaje')
             ->name('show');
        Route::get('/{personaje}/estadisticas', [PersonajeApiController::class, 'estadisticas'])
             ->middleware('can:view,personaje')
             ->name('stats');
        
        // Sistema de experiencia y niveles
        Route::post('/{personaje}/experiencia', [PersonajeApiController::class, 'ganarExperiencia'])
             ->middleware('can:update,personaje')
             ->middleware('throttle:20,1')
             ->name('gain-xp');
        
        // Sistema de habilidades
        Route::post('/{personaje}/habilidades/{habilidad}', [PersonajeApiController::class, 'usarHabilidad'])
             ->middleware('can:usar-habilidad,personaje')
             ->middleware('throttle:use-ability')
             ->name('use-ability');
        Route::get('/{personaje}/habilidades/disponibles', [PersonajeApiController::class, 'habilidadesDisponibles'])
             ->middleware('can:view,personaje')
             ->name('available-abilities');
        
        // Sistema de equipamiento
        Route::put('/{personaje}/equipamiento', [PersonajeApiController::class, 'cambiarEquipamiento'])
             ->middleware('can:update,personaje')
             ->name('change-equipment');
        Route::get('/{personaje}/equipamiento/disponible', [PersonajeApiController::class, 'equipamientoDisponible'])
             ->middleware('can:view,personaje')
             ->name('available-equipment');
        
        // Accesorios y personalización
        Route::get('/{personaje}/accesorios', [PersonajeApiController::class, 'accesoriosDisponibles'])
             ->middleware('can:view,personaje')
             ->name('accessories');
        Route::post('/{personaje}/accesorios/{accesorio}', [PersonajeApiController::class, 'equiparAccesorio'])
             ->middleware('can:update,personaje')
             ->name('equip-accessory');
        
        // Interacciones sociales
        Route::post('/{personaje}/revivir/{objetivo}', [PersonajeApiController::class, 'revivirCompañero'])
             ->middleware('can:usar-habilidad,personaje')
             ->middleware('throttle:use-ability')
             ->name('revive-teammate');
        Route::post('/{personaje}/curar', [PersonajeApiController::class, 'autoConsumirse'])
             ->middleware('can:usar-habilidad,personaje')
             ->middleware('throttle:use-ability')
             ->name('self-heal');
    });
    
    // ===== API CLASES (Información general y públicos) =====
    Route::prefix('clases')->name('api.clases.')->group(function () {
        
        // Información básica de clases (accesible por miembros)
        Route::get('/{clase}', [ClaseApiController::class, 'show'])
             ->middleware('can:acceder-clase,clase')
             ->name('show');
        Route::get('/{clase}/estudiantes', [ClaseApiController::class, 'estudiantes'])
             ->middleware('can:acceder-clase,clase')
             ->name('students');
        Route::get('/{clase}/estadisticas', [ClaseApiController::class, 'estadisticas'])
             ->middleware('can:acceder-clase,clase')
             ->name('stats');
        
        // Rankings y leaderboards
        Route::get('/{clase}/ranking', [ClaseApiController::class, 'ranking'])
             ->middleware('can:acceder-clase,clase')
             ->name('ranking');
        Route::get('/{clase}/actividad-reciente', [ClaseApiController::class, 'actividadReciente'])
             ->middleware('can:acceder-clase,clase')
             ->name('recent-activity');
        
        // Validación de códigos de invitación
        Route::post('/validar-codigo', [ClaseApiController::class, 'validarCodigo'])
             ->name('validate-code');
    });
    
    // ===== API CLASES RPG =====
    Route::prefix('clases-rpg')->name('api.clases-rpg.')->group(function () {
        Route::get('/', [ClaseRpgController::class, 'index'])->name('index');
        Route::get('/{claseRpg}', [ClaseRpgController::class, 'show'])->name('show');
        Route::get('/{claseRpg}/builds-recomendados', [ClaseRpgController::class, 'buildsRecomendados'])->name('builds');
    });
    
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