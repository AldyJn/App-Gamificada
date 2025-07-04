<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClaseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Profesor\ProfesorController;
use App\Http\Controllers\Estudiante\EstudianteController;

// ==========================================
// PÁGINA DE INICIO
// ==========================================
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    
    return inertia('Welcome/Index', [
        'canLogin' => true,
        'canRegister' => true,
    ]);
})->name('welcome');

// ==========================================
// AUTENTICACIÓN - RUTAS WEB COMPLETAS
// ==========================================
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
         ->middleware('throttle:5,1');
    
    // Registro
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])
         ->middleware('throttle:3,1');
    
    // Verificar email (para AJAX)
    Route::post('/auth/check-email', [AuthController::class, 'checkEmail']);
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ==========================================
// RUTAS PROTEGIDAS POR AUTENTICACIÓN
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // Dashboard principal - accesible para todos los usuarios autenticados
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rutas de perfil - accesibles para todos
    Route::get('/perfil', [DashboardController::class, 'configurarPerfil'])->name('perfil');
    Route::get('/configurar-perfil', [DashboardController::class, 'configurarPerfil'])->name('configurar-perfil');

    // ==========================================
    // GESTIÓN DE CLASES - CON MIDDLEWARE ESPECÍFICO
    // ==========================================
    Route::prefix('clases')->name('clases.')->group(function () {
        // Rutas accesibles para todos los usuarios autenticados
        Route::get('/', [ClaseController::class, 'index'])->name('index');
        Route::get('/{clase}', [ClaseController::class, 'show'])->name('show');
        
        // Rutas SOLO para docentes
        Route::middleware('user.type:docente')->group(function () {
            Route::get('/crear', [ClaseController::class, 'create'])->name('create');
            Route::post('/', [ClaseController::class, 'store'])->name('store');
            Route::get('/{clase}/editar', [ClaseController::class, 'edit'])->name('edit');
            Route::put('/{clase}', [ClaseController::class, 'update'])->name('update');
            Route::delete('/{clase}', [ClaseController::class, 'destroy'])->name('destroy');
        });
        
        // Rutas SOLO para estudiantes
        Route::middleware('user.type:estudiante')->group(function () {
            Route::get('/unirse', [ClaseController::class, 'showJoinForm'])->name('unirse.form');
            Route::post('/unirse', [ClaseController::class, 'join'])->name('unirse');
        });
        
        // Salir de clase - disponible para estudiantes
        Route::post('/{clase}/salir', [ClaseController::class, 'leave'])
            ->name('salir')
            ->middleware('user.type:estudiante');
    });

    // ==========================================
    // RUTAS ESPECÍFICAS PARA DOCENTES
    // ==========================================
    Route::prefix('docentes')->name('docentes.')
        ->middleware('user.type:docente')
        ->group(function () {
            Route::get('/', [ProfesorController::class, 'index'])->name('index');
            Route::get('/perfil', [ProfesorController::class, 'perfil'])->name('perfil');
            Route::get('/mis-clases', [ProfesorController::class, 'misClases'])->name('mis-clases');
            Route::get('/estadisticas', [ProfesorController::class, 'estadisticas'])->name('estadisticas');
            
            // Gestión avanzada de clases
            Route::get('/clases/{clase}/gestionar', [ProfesorController::class, 'gestionarClase'])->name('gestionar-clase');
            Route::post('/clases/{clase}/comportamiento', [ProfesorController::class, 'aplicarComportamiento'])->name('aplicar-comportamiento');
            Route::get('/estudiantes/buscar', [ProfesorController::class, 'buscarEstudiantes'])->name('buscar-estudiantes');
        });

    // ==========================================
    // RUTAS ESPECÍFICAS PARA ESTUDIANTES
    // ==========================================
    Route::prefix('estudiantes')->name('estudiantes.')
        ->middleware('user.type:estudiante')
        ->group(function () {
            Route::get('/', [EstudianteController::class, 'index'])->name('index');
            Route::get('/perfil', [EstudianteController::class, 'perfil'])->name('perfil');
            Route::get('/mis-clases', [EstudianteController::class, 'misClases'])->name('mis-clases');
            Route::get('/progreso', [EstudianteController::class, 'progreso'])->name('progreso');
            
            // Dashboard específico del estudiante
            Route::get('/dashboard', [EstudianteController::class, 'dashboard'])->name('dashboard');
            
            // Gestión de personajes (para futuras funcionalidades)
            Route::get('/personaje', [EstudianteController::class, 'verPersonaje'])->name('personaje');
            Route::get('/crear-personaje', [EstudianteController::class, 'crearPersonaje'])->name('crear-personaje');
        });

    // ==========================================
    // API ENDPOINTS (AJAX) - CON AUTENTICACIÓN
    // ==========================================
    Route::prefix('api')->name('api.')->group(function () {
        // Stats del dashboard - accesible para todos
        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
        
        // API específica para docentes
        Route::middleware('user.type:docente')->group(function () {
            Route::post('/clases/verificar-codigo', [ClaseController::class, 'verifyCode']);
            Route::get('/docentes/estadisticas-avanzadas', [ProfesorController::class, 'estadisticasApi']);
        });
        
        // API específica para estudiantes
        Route::middleware('user.type:estudiante')->group(function () {
            Route::get('/estudiantes/progreso-detallado', [EstudianteController::class, 'progresoApi']);
            Route::post('/estudiantes/unirse-clase', [EstudianteController::class, 'unirseClaseApi']);
        });
    });
});

// ==========================================
// RUTAS DE RESPALDO Y ERRORES
// ==========================================
Route::fallback(function () {
    return inertia('Error', [
        'status' => 404,
        'message' => 'Página no encontrada en la Torre',
        'descripcion' => 'La página que buscas no existe o ha sido movida a otra ubicación.'
    ]);
});