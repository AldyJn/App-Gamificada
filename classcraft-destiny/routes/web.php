<?php
// routes/web.php - Sistema Educativo Gamificado Zenthoria
// Rutas completas para ClassCraft-Destiny con estética Destiny 2

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    WelcomeController,
    HomeController
};
use App\Http\Controllers\Auth\{
    AuthController,
    ForgotPasswordController,
    ResetPasswordController,
    EmailVerificationController
};
use App\Http\Controllers\Profesor\{
    ProfesorController,
    ClaseProfesorController,
    EstudianteProfesorController,
    ReporteController,
    ActividadProfesorController
};
use App\Http\Controllers\Estudiante\{
    EstudianteController,
    PersonajeController,
    ClaseEstudianteController,
    RankingController,
    LogroController,
    ChatController
};
use App\Http\Controllers\{
    ClaseRpgController,
    NotificationController,
    ConfiguracionController,
    ApiController
};

/*
|--------------------------------------------------------------------------
| Rutas Web - Sistema Zenthoria
|--------------------------------------------------------------------------
*/

// ===== RUTAS PÚBLICAS =====

// Landing Page principal
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Páginas informativas
Route::get('/about', [WelcomeController::class, 'about'])->name('about');
Route::get('/features', [WelcomeController::class, 'features'])->name('features');
Route::get('/contact', [WelcomeController::class, 'contact'])->name('contact');
Route::post('/contact', [WelcomeController::class, 'submitContact'])->name('contact.submit');

// ===== AUTENTICACIÓN =====

Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
         ->middleware('throttle:5,1')
         ->name('login.submit');
    
    // Registro
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])
         ->middleware('throttle:3,1')
         ->name('register.submit');
    
    // Recuperación de contraseña
    Route::get('/forgot-password', [ForgotPasswordController::class, 'show'])
         ->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
         ->middleware('throttle:3,1')
         ->name('password.email');
    
    // Reset de contraseña
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'show'])
         ->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])
         ->middleware('throttle:3,1')
         ->name('password.update');
});

// Logout (para usuarios autenticados)
Route::post('/logout', [AuthController::class, 'logout'])
     ->middleware('auth')
     ->name('logout');

// Verificación de email
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'show'])
         ->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
         ->middleware(['signed', 'throttle:6,1'])
         ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])
         ->middleware('throttle:6,1')
         ->name('verification.send');
});

// ===== DASHBOARD PRINCIPAL (Redirige según tipo de usuario) =====
Route::get('/dashboard', [HomeController::class, 'dashboard'])
     ->middleware(['auth', 'verified'])
     ->name('dashboard');

// ===== RUTAS DEL PROFESOR =====
Route::middleware(['auth', 'verified', 'profesor'])->prefix('profesor')->name('profesor.')->group(function () {
    
    // Dashboard Principal
    Route::get('/dashboard', [ProfesorController::class, 'dashboard'])->name('dashboard');
    
    // ===== GESTIÓN DE CLASES =====
    
    // Crear nueva clase
    Route::get('/crear-clase', [ProfesorController::class, 'crearClaseForm'])->name('crear-clase.form');
    Route::post('/crear-clase', [ProfesorController::class, 'crearClase'])->name('crear-clase');
    
    // Listar mis clases
    Route::get('/mis-clases', [ProfesorController::class, 'misClases'])->name('mis-clases');
    
    // Gestión individual de clases
    Route::middleware('clase.access')->group(function () {
        // Ver detalles de clase
        Route::get('/clase/{clase}', [ClaseProfesorController::class, 'verClase'])->name('clase.ver');
        
        // Editar clase
        Route::get('/clase/{clase}/editar', [ClaseProfesorController::class, 'editarForm'])->name('clase.editar.form');
        Route::put('/clase/{clase}', [ClaseProfesorController::class, 'actualizar'])->name('clase.editar');
        
        // Configuración de clase
        Route::get('/clase/{clase}/configuracion', [ClaseProfesorController::class, 'configuracion'])->name('clase.configuracion');
        Route::put('/clase/{clase}/configuracion', [ClaseProfesorController::class, 'actualizarConfiguracion'])->name('clase.configuracion.actualizar');
        
        // Eliminar/Archivar clase
        Route::delete('/clase/{clase}', [ClaseProfesorController::class, 'eliminar'])->name('clase.eliminar');
        Route::patch('/clase/{clase}/archivar', [ClaseProfesorController::class, 'archivar'])->name('clase.archivar');
        
        // QR y códigos de invitación
        Route::post('/clase/{clase}/regenerar-qr', [ClaseProfesorController::class, 'regenerarQR'])->name('clase.regenerar-qr');
        Route::get('/clase/{clase}/qr', [ClaseProfesorController::class, 'mostrarQR'])->name('clase.qr');
    });
    
    // ===== GESTIÓN DE ESTUDIANTES =====
    
    Route::middleware('clase.access')->group(function () {
        // Selector aleatorio de estudiantes
        Route::post('/clase/{clase}/estudiante-aleatorio', [EstudianteProfesorController::class, 'estudianteAleatorio'])
             ->name('clase.estudiante-aleatorio');
        
        // Agregar estudiantes
        Route::post('/clase/{clase}/agregar-estudiante', [EstudianteProfesorController::class, 'agregarEstudiante'])
             ->name('clase.agregar-estudiante');
        
        // Remover estudiantes
        Route::delete('/clase/{clase}/estudiante/{estudiante}', [EstudianteProfesorController::class, 'removerEstudiante'])
             ->name('clase.remover-estudiante');
        
        // Ver perfil de estudiante en clase
        Route::get('/clase/{clase}/estudiante/{estudiante}', [EstudianteProfesorController::class, 'verEstudiante'])
             ->name('clase.estudiante.ver');
    });
    
    // Buscar estudiantes
    Route::get('/buscar-estudiantes', [EstudianteProfesorController::class, 'buscarEstudiantes'])
         ->name('buscar-estudiantes');
    
    // ===== SISTEMA DE COMPORTAMIENTOS =====
    
    Route::middleware('clase.access')->group(function () {
        // Aplicar comportamiento
        Route::post('/clase/{clase}/comportamiento', [EstudianteProfesorController::class, 'aplicarComportamiento'])
             ->middleware('throttle:20,1')
             ->name('clase.comportamiento');
        
        // Historial de comportamientos
        Route::get('/clase/{clase}/historial-comportamientos', [EstudianteProfesorController::class, 'historialComportamientos'])
             ->name('clase.historial');
        
        // Revertir comportamiento
        Route::delete('/comportamiento/{comportamiento}', [EstudianteProfesorController::class, 'revertirComportamiento'])
             ->name('comportamiento.revertir');
    });
    
    // ===== ACTIVIDADES Y TAREAS =====
    
    Route::middleware('clase.access')->group(function () {
        // Crear actividad
        Route::get('/clase/{clase}/crear-actividad', [ActividadProfesorController::class, 'crearForm'])->name('actividad.crear.form');
        Route::post('/clase/{clase}/actividades', [ActividadProfesorController::class, 'crear'])->name('actividad.crear');
        
        // Gestionar actividades
        Route::get('/clase/{clase}/actividades', [ActividadProfesorController::class, 'listar'])->name('actividades.listar');
        Route::get('/actividad/{actividad}', [ActividadProfesorController::class, 'ver'])->name('actividad.ver');
        Route::put('/actividad/{actividad}', [ActividadProfesorController::class, 'actualizar'])->name('actividad.actualizar');
        Route::delete('/actividad/{actividad}', [ActividadProfesorController::class, 'eliminar'])->name('actividad.eliminar');
        
        // Calificaciones
        Route::get('/actividad/{actividad}/calificar', [ActividadProfesorController::class, 'calificarForm'])->name('actividad.calificar.form');
        Route::post('/actividad/{actividad}/calificar', [ActividadProfesorController::class, 'calificar'])->name('actividad.calificar');
    });
    
    // ===== REPORTES Y ESTADÍSTICAS =====
    
    // Dashboard de estadísticas
    Route::get('/estadisticas', [ReporteController::class, 'dashboard'])->name('estadisticas');
    
    // Reportes por clase
    Route::get('/clase/{clase}/reporte', [ReporteController::class, 'reporteClase'])
         ->middleware('clase.access')
         ->name('clase.reporte');
    
    // Exportar datos
    Route::get('/clase/{clase}/exportar', [ReporteController::class, 'exportarDatos'])
         ->middleware('clase.access')
         ->name('clase.exportar');
    
    // Reporte general
    Route::get('/reporte-general', [ReporteController::class, 'reporteGeneral'])->name('reporte-general');
    
    // ===== CONFIGURACIÓN DEL PROFESOR =====
    
    Route::get('/perfil', [ProfesorController::class, 'perfil'])->name('perfil');
    Route::put('/perfil', [ProfesorController::class, 'actualizarPerfil'])->name('perfil.actualizar');
    Route::get('/configuracion', [ProfesorController::class, 'configuracion'])->name('configuracion');
    Route::put('/configuracion', [ProfesorController::class, 'actualizarConfiguracion'])->name('configuracion.actualizar');
});

// ===== RUTAS DEL ESTUDIANTE =====
Route::middleware(['auth', 'verified', 'estudiante'])->prefix('estudiante')->name('estudiante.')->group(function () {
    
    // Dashboard Principal
    Route::get('/dashboard', [EstudianteController::class, 'dashboard'])->name('dashboard');
    
    // ===== UNIRSE A CLASES =====
    
    // Formularios para unirse
    Route::get('/unirse-clase', [EstudianteController::class, 'unirseClaseForm'])->name('unirse-clase.form');
    Route::post('/unirse-clase', [EstudianteController::class, 'unirseClase'])
         ->middleware('throttle:join-class')
         ->name('unirse-clase');
    
    // Explorar clases públicas
    Route::get('/explorar-clases', [EstudianteController::class, 'explorarClases'])->name('explorar-clases');
    
    // ===== GESTIÓN DE PERSONAJES =====
    
    Route::middleware('clase.access')->group(function () {
        // Crear personaje
        Route::get('/clase/{clase}/crear-personaje', [EstudianteController::class, 'crearPersonajeForm'])
             ->name('crear-personaje.form');
        Route::post('/clase/{clase}/crear-personaje', [EstudianteController::class, 'crearPersonaje'])
             ->name('crear-personaje');
        
        // Ver detalles de clase
        Route::get('/clase/{clase}', [ClaseEstudianteController::class, 'claseDetalle'])->name('clase-detalle');
        
        // Salir de clase
        Route::post('/clase/{clase}/salir', [ClaseEstudianteController::class, 'salirDeClase'])->name('clase.salir');
    });
    
    // ===== PERSONALIZACIÓN DE PERSONAJES =====
    
    Route::middleware('can:administrar-personaje,personaje')->group(function () {
        // Personalizar personaje
        Route::get('/personaje/{personaje}/personalizar', [PersonajeController::class, 'personalizarForm'])
             ->name('personaje.personalizar.form');
        Route::put('/personaje/{personaje}/personalizar', [PersonajeController::class, 'personalizar'])
             ->name('personaje.personalizar');
        
        // Cambiar equipamiento
        Route::put('/personaje/{personaje}/equipamiento', [PersonajeController::class, 'cambiarEquipamiento'])
             ->name('personaje.equipamiento');
        
        // Ver estadísticas detalladas
        Route::get('/personaje/{personaje}/estadisticas', [PersonajeController::class, 'estadisticas'])
             ->name('personaje.estadisticas');
        
        // Usar habilidades
        Route::post('/personaje/{personaje}/habilidad', [PersonajeController::class, 'usarHabilidad'])
             ->middleware('throttle:use-ability')
             ->name('personaje.habilidad');
    });
    
    // ===== RANKINGS Y LOGROS =====
    
    // Rankings
    Route::get('/rankings', [RankingController::class, 'index'])->name('rankings');
    Route::get('/rankings/global', [RankingController::class, 'global'])->name('rankings.global');
    Route::get('/rankings/clase/{claseRpg}', [RankingController::class, 'porClase'])->name('rankings.clase');
    Route::get('/rankings/semanal', [RankingController::class, 'semanal'])->name('rankings.semanal');
    
    // Logros
    Route::get('/logros', [LogroController::class, 'index'])->name('logros');
    Route::get('/logros/categoria/{categoria}', [LogroController::class, 'porCategoria'])->name('logros.categoria');
    Route::get('/logro/{logro}', [LogroController::class, 'detalle'])->name('logro.detalle');
    
    // ===== ACTIVIDADES Y TAREAS =====
    
    // Ver mis actividades
    Route::get('/actividades', [ClaseEstudianteController::class, 'misActividades'])->name('actividades');
    Route::get('/actividad/{actividad}', [ClaseEstudianteController::class, 'verActividad'])->name('actividad.ver');
    
    // Entregar actividad
    Route::post('/actividad/{actividad}/entregar', [ClaseEstudianteController::class, 'entregarActividad'])
         ->name('actividad.entregar');
    
    // ===== COMUNICACIÓN =====
    
    // Chat de clase
    Route::middleware('clase.access')->group(function () {
        Route::get('/clase/{clase}/chat', [ChatController::class, 'index'])->name('clase.chat');
        Route::post('/clase/{clase}/chat', [ChatController::class, 'enviarMensaje'])->name('clase.chat.enviar');
    });
    
    // ===== PERFIL Y CONFIGURACIÓN =====
    
    // Perfil del estudiante
    Route::get('/perfil', [EstudianteController::class, 'perfil'])->name('perfil');
    Route::put('/perfil', [EstudianteController::class, 'actualizarPerfil'])->name('perfil.actualizar');
    
    // Historial académico
    Route::get('/historial', [EstudianteController::class, 'historial'])->name('historial');
    
    // Configuración
    Route::get('/configuracion', [EstudianteController::class, 'configuracion'])->name('configuracion');
    Route::put('/configuracion', [EstudianteController::class, 'actualizarConfiguracion'])->name('configuracion.actualizar');
    
    // Notificaciones
    Route::get('/notificaciones', [EstudianteController::class, 'notificaciones'])->name('notificaciones');
    Route::put('/notificaciones/{notificacion}/leer', [EstudianteController::class, 'marcarNotificacionLeida'])
         ->name('notificaciones.leer');
    Route::put('/notificaciones/leer-todas', [EstudianteController::class, 'marcarTodasLeidas'])
         ->name('notificaciones.leer-todas');
});

// ===== RUTAS DE ACCESO POR QR/CÓDIGO =====

// Unirse a clase mediante QR o código (requiere autenticación)
Route::get('/unirse/{codigoInvitacion}', [EstudianteController::class, 'unirseClasePorCodigo'])
     ->middleware('auth')
     ->name('unirse-clase-codigo');

// Vista previa de clase pública (sin autenticación)
Route::get('/clase-preview/{codigoInvitacion}', [ClaseEstudianteController::class, 'previsualizarClase'])
     ->name('clase-preview');

// ===== API INTERNA PARA FUNCIONALIDADES DINÁMICAS =====

Route::middleware('auth')->prefix('api')->name('api.')->group(function () {
    
    // ===== DATOS GENERALES =====
    
    // Información del usuario actual
    Route::get('/me', [ApiController::class, 'me'])->name('me');
    
    // Configuración del sistema
    Route::get('/config', [ApiController::class, 'config'])->name('config');
    
    // ===== CLASES RPG =====
    
    // Listado de clases RPG
    Route::get('/clases-rpg', [ClaseRpgController::class, 'index'])->name('clases-rpg.index');
    Route::get('/clases-rpg/{claseRpg}', [ClaseRpgController::class, 'show'])->name('clases-rpg.show');
    
    // ===== NOTIFICACIONES =====
    
    // Obtener notificaciones del usuario
    Route::get('/notificaciones', [NotificationController::class, 'index'])->name('notificaciones.api');
    Route::post('/notificaciones/{id}/leer', [NotificationController::class, 'marcarLeida'])->name('notificaciones.marcar');
    
    // ===== DATOS EN TIEMPO REAL =====
    
    // Rankings en vivo
    Route::get('/rankings/live', [RankingController::class, 'live'])->name('rankings.live');
    
    // Actividad reciente
    Route::get('/actividad-reciente', [ApiController::class, 'actividadReciente'])->name('actividad-reciente');
    
    // Estados de personajes
    Route::get('/personajes/estados', [PersonajeController::class, 'estados'])->name('personajes.estados');
    
    // ===== BÚSQUEDAS =====
    
    // Búsqueda global
    Route::get('/buscar', [ApiController::class, 'buscar'])->name('buscar');
    
    // Buscar usuarios
    Route::get('/usuarios/buscar', [ApiController::class, 'buscarUsuarios'])->name('usuarios.buscar');
    
    // ===== VALIDACIONES =====
    
    // Validar código de clase
    Route::post('/validar-codigo-clase', [ApiController::class, 'validarCodigoClase'])->name('validar-codigo-clase');
    
    // Verificar disponibilidad de email
    Route::post('/verificar-email', [ApiController::class, 'verificarEmail'])->name('verificar-email');
});

// ===== RUTAS ADMINISTRATIVAS =====

Route::middleware(['auth', 'verified', 'can:super-admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard administrativo
    Route::get('/dashboard', [ConfiguracionController::class, 'dashboard'])->name('dashboard');
    
    // Gestión de usuarios
    Route::get('/usuarios', [ConfiguracionController::class, 'usuarios'])->name('usuarios');
    Route::get('/usuarios/{usuario}', [ConfiguracionController::class, 'verUsuario'])->name('usuarios.ver');
    Route::put('/usuarios/{usuario}/estado', [ConfiguracionController::class, 'cambiarEstadoUsuario'])->name('usuarios.estado');
    
    // Configuración global del sistema
    Route::get('/configuracion', [ConfiguracionController::class, 'configuracionGlobal'])->name('configuracion');
    Route::put('/configuracion', [ConfiguracionController::class, 'actualizarConfiguracionGlobal'])->name('configuracion.actualizar');
    
    // Gestión de contenido
    Route::get('/contenido', [ConfiguracionController::class, 'gestionContenido'])->name('contenido');
    
    // Logs y monitoreo
    Route::get('/logs', [ConfiguracionController::class, 'logs'])->name('logs');
    Route::get('/metricas', [ConfiguracionController::class, 'metricas'])->name('metricas');
    
    // Mantenimiento
    Route::post('/cache/limpiar', [ConfiguracionController::class, 'limpiarCache'])->name('cache.limpiar');
    Route::post('/sistema/reiniciar', [ConfiguracionController::class, 'reiniciarSistema'])->name('sistema.reiniciar');
});

// ===== RUTAS DE DESARROLLO (SOLO EN LOCAL) =====

if (app()->environment('local')) {
    Route::prefix('dev')->name('dev.')->group(function () {
        
        // Testing de emails
        Route::get('/test-emails', function () {
            return view('dev.test-emails');
        })->name('test-emails');
        
        // Componentes UI
        Route::get('/ui-components', function () {
            return view('dev.ui-components');
        })->name('ui-components');
        
        // Reset de datos demo
        Route::get('/reset-demo-data', function () {
            \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'UsuarioTestSeeder']);
            return redirect()->back()->with('success', 'Datos demo reiniciados exitosamente.');
        })->name('reset-demo');
        
        // Generar datos ficticios
        Route::get('/generate-fake-data', function () {
            \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'DatosFictíciosSeeder']);
            return 'Datos ficticios generados.';
        })->name('generate-fake');
        
        // Documentación de rutas
        Route::get('/routes', function () {
            return view('dev.routes', ['routes' => \Illuminate\Support\Facades\Route::getRoutes()]);
        })->name('routes');
    });
}

// ===== WEBHOOKS Y INTEGRACIONES EXTERNAS =====

Route::prefix('webhooks')->name('webhooks.')->group(function () {
    
    // Webhook para sistemas LMS externos
    Route::post('/lms-sync', function (Illuminate\Http\Request $request) {
        // TODO: Implementar sincronización con LMS externos
        \Illuminate\Support\Facades\Log::info('LMS Webhook recibido', $request->all());
        return response()->json(['status' => 'received']);
    })->name('lms-sync');
    
    // Webhook para notificaciones push
    Route::post('/notifications', function (Illuminate\Http\Request $request) {
        // TODO: Implementar sistema de notificaciones push
        return response()->json(['status' => 'received']);
    })->name('notifications');
    
    // Webhook para pagos (si se implementa sistema premium)
    Route::post('/payments', function (Illuminate\Http\Request $request) {
        // TODO: Implementar webhooks de pagos
        return response()->json(['status' => 'received']);
    })->name('payments');
});

// ===== RUTAS DE UTILIDAD =====

// Salud del sistema
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'version' => config('app.version', '1.0.0'),
        'environment' => app()->environment(),
        'database' => \Illuminate\Support\Facades\DB::connection()->getPdo() ? 'connected' : 'disconnected',
        'cache' => \Illuminate\Support\Facades\Cache::has('health_check') ? 'working' : 'not working',
    ]);
})->name('health');

// Métricas básicas (requiere autenticación)
Route::get('/metrics', function () {
    return response()->json([
        'total_usuarios' => \App\Models\Usuario::count(),
        'usuarios_activos_hoy' => \App\Models\Usuario::where('ultimo_acceso', '>=', now()->startOfDay())->count(),
        'total_clases' => \App\Models\Clase::count(),
        'clases_activas' => \App\Models\Clase::where('activo', true)->count(),
        'total_personajes' => \App\Models\Personaje::count(),
        'experiencia_total_distribuida' => \App\Models\Personaje::sum('experiencia_actual'),
        'ultimo_update' => now()->toISOString(),
    ]);
})->middleware('auth')->name('metrics');

// Sitemap XML
Route::get('/sitemap.xml', function () {
    $sitemap = new \Spatie\Sitemap\Sitemap();
    
    $sitemap->add(\Spatie\Sitemap\Tags\Url::create('/'))
            ->add(\Spatie\Sitemap\Tags\Url::create('/about'))
            ->add(\Spatie\Sitemap\Tags\Url::create('/features'))
            ->add(\Spatie\Sitemap\Tags\Url::create('/contact'))
            ->add(\Spatie\Sitemap\Tags\Url::create('/login'))
            ->add(\Spatie\Sitemap\Tags\Url::create('/register'));
    
    return $sitemap->toResponse($request);
})->name('sitemap');

// Robots.txt
Route::get('/robots.txt', function () {
    $robots = "User-agent: *\n";
    
    if (app()->environment('production')) {
        $robots .= "Allow: /\n";
        $robots .= "Sitemap: " . url('/sitemap.xml') . "\n";
    } else {
        $robots .= "Disallow: /\n";
    }
    
    return response($robots)->header('Content-Type', 'text/plain');
})->name('robots');

// ===== FALLBACK 404 PERSONALIZADO =====

Route::fallback(function () {
    if (request()->expectsJson()) {
        return response()->json([
            'message' => 'Endpoint no encontrado en la Torre.',
            'error' => 'Route not found'
        ], 404);
    }
    
    return view('errors.404-zenthoria')->with([
        'title' => 'Sector No Encontrado',
        'message' => 'La ubicación que buscas no existe en la Torre.'
    ]);
});

// ===== RUTAS DE MANTENIMIENTO =====

// Página de mantenimiento personalizada
Route::get('/maintenance', function () {
    return view('maintenance.zenthoria');
})->name('maintenance');

// Página de términos y condiciones
Route::get('/terms', function () {
    return view('legal.terms');
})->name('terms');

// Página de privacidad
Route::get('/privacy', function () {
    return view('legal.privacy');
})->name('privacy');