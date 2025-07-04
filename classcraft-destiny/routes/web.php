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
    
    // Registro - ¡AMBAS RUTAS NECESARIAS!
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
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==========================================
    // GESTIÓN DE CLASES
    // ==========================================
    Route::prefix('clases')->name('clases.')->group(function () {
        // Listar clases
        Route::get('/', [ClaseController::class, 'index'])->name('index');
        
        // Ver clase específica
        Route::get('/{clase}', [ClaseController::class, 'show'])->name('show');
        
        // Crear clase (solo docentes)
        Route::middleware('user.type:docente')->group(function () {
            Route::get('/crear', [ClaseController::class, 'create'])->name('create');
            Route::post('/', [ClaseController::class, 'store'])->name('store');
            Route::get('/{clase}/editar', [ClaseController::class, 'edit'])->name('edit');
            Route::put('/{clase}', [ClaseController::class, 'update'])->name('update');
            Route::delete('/{clase}', [ClaseController::class, 'destroy'])->name('destroy');
        });
        
        // Unirse a clase (solo estudiantes)
        Route::middleware('user.type:estudiante')->group(function () {
            Route::get('/unirse', [ClaseController::class, 'showJoinForm'])->name('unirse.form');
            Route::post('/unirse', [ClaseController::class, 'joinClass'])->name('unirse');
        });
        
        // Salir de clase
        Route::post('/{clase}/salir', [ClaseController::class, 'leaveClass'])->name('salir');
    });

    // ==========================================
    // PERFIL Y CONFIGURACIÓN
    // ==========================================
    Route::get('/perfil', function() {
        if (auth()->user()->esDocente()) {
            return redirect()->route('docentes.perfil');
        }
        return redirect()->route('estudiantes.perfil');
    })->name('perfil');

    // ==========================================
    // RUTAS ESPECÍFICAS DE DOCENTES
    // ==========================================
    Route::prefix('docentes')->name('docentes.')->middleware('user.type:docente')->group(function () {
        Route::get('/', [ProfesorController::class, 'index'])->name('index');
        Route::get('/perfil', [ProfesorController::class, 'perfil'])->name('perfil');
        Route::get('/mis-clases', [ProfesorController::class, 'misClases'])->name('mis-clases');
        Route::get('/estadisticas', [ProfesorController::class, 'estadisticas'])->name('estadisticas');
    });

    // ==========================================
    // RUTAS ESPECÍFICAS DE ESTUDIANTES
    // ==========================================
    Route::prefix('estudiantes')->name('estudiantes.')->middleware('user.type:estudiante')->group(function () {
        Route::get('/', [EstudianteController::class, 'index'])->name('index');
        Route::get('/perfil', [EstudianteController::class, 'perfil'])->name('perfil');
        Route::get('/mis-clases', [EstudianteController::class, 'misClases'])->name('mis-clases');
        Route::get('/progreso', [EstudianteController::class, 'progreso'])->name('progreso');
    });

    // ==========================================
    // API ENDPOINTS (AJAX)
    // ==========================================
    Route::prefix('api')->name('api.')->group(function () {
        // Verificar código de clase
        Route::post('/clases/verificar-codigo', [ClaseController::class, 'verifyCode']);
        
        // Stats del dashboard
        Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    });
});

// ==========================================
// RUTAS DE RESPALDO
// ==========================================
Route::fallback(function () {
    return inertia('Error', [
        'status' => 404,
        'message' => 'Página no encontrada'
    ]);
});