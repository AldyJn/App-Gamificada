<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfesorDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use Inertia\Inertia;

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
    
    // ==========================================
    // DASHBOARD PRINCIPAL - SIN REDIRECCIONES
    // ==========================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==========================================
    // RUTAS ESPECÍFICAS DE PROFESORES
    // ==========================================
    Route::prefix('profesor')->name('profesor.')->middleware('user.type:docente')->group(function () {
        
        // Dashboard del profesor
        Route::get('/', [ProfesorDashboardController::class, 'index'])->name('dashboard');
        
        // Gestión de clases
        Route::prefix('clases')->name('clases.')->group(function () {
            // Crear clase
            Route::post('/', [ProfesorDashboardController::class, 'crearClase'])->name('crear');
            
            // Ver clase específica
            Route::get('/{clase}', [ProfesorDashboardController::class, 'verClase'])->name('ver');
            
            // Selección aleatoria de estudiantes
            Route::post('/{clase}/seleccionar-aleatorio', [ProfesorDashboardController::class, 'seleccionarEstudianteAleatorio'])->name('seleccionar-aleatorio');
        });
    });

    // ==========================================
    // RUTAS ESPECÍFICAS DE ESTUDIANTES 
    // ==========================================
    Route::prefix('estudiantes')->name('estudiantes.')->middleware('user.type:estudiante')->group(function () {
        
        // Perfil del estudiante (SIN REDIRECCIONES)
        Route::get('/perfil', function() {
            return Inertia::render('Estudiantes/Perfil', [
                'estudiante' => [
                    'nombre' => auth()->user()->nombre,
                    'correo' => auth()->user()->correo
                ],
                'mensaje' => 'Perfil de estudiante en construcción'
            ]);
        })->name('perfil');
        
        Route::get('/mis-clases', function() {
            return Inertia::render('Estudiantes/MisClases', [
                'clases' => [],
                'mensaje' => 'Funcionalidad en desarrollo'
            ]);
        })->name('mis-clases');
        
        Route::get('/progreso', function() {
            return Inertia::render('Estudiantes/Progreso', [
                'progreso' => [],
                'mensaje' => 'Sistema de progreso en desarrollo'
            ]);
        })->name('progreso');
    });

    // ==========================================
    // PERFIL GENERAL - SIN REDIRECCIONES
    // ==========================================
    Route::get('/perfil', function() {
        $user = auth()->user();
        
        if ($user->esDocente()) {
            return Inertia::render('Perfil/Docente', [
                'usuario' => [
                    'nombre' => $user->nombre,
                    'correo' => $user->correo,
                    'tipo' => 'docente'
                ]
            ]);
        }
        
        if ($user->esEstudiante()) {
            return Inertia::render('Perfil/Estudiante', [
                'usuario' => [
                    'nombre' => $user->nombre,
                    'correo' => $user->correo,
                    'tipo' => 'estudiante'
                ]
            ]);
        }
        
        // Usuario sin tipo definido
        return Inertia::render('Perfil/General', [
            'usuario' => [
                'nombre' => $user->nombre,
                'correo' => $user->correo,
                'tipo' => 'indefinido'
            ]
        ]);
    })->name('perfil');

    // ==========================================
    // API ENDPOINTS (AJAX)
    // ==========================================
    Route::prefix('api')->name('api.')->group(function () {
        // Stats del dashboard
        Route::get('/dashboard/stats', function() {
            return response()->json([
                'stats' => [
                    'usuarios' => 0,
                    'clases' => 0,
                    'actividades' => 0
                ]
            ]);
        });
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