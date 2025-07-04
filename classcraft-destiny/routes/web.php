<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfesorDashboardController;
use App\Http\Controllers\EstudianteInscripcionController;
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
// AUTENTICACIÓN
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:3,1');
    Route::post('/auth/check-email', [AuthController::class, 'checkEmail']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ==========================================
// RUTAS PROTEGIDAS POR AUTENTICACIÓN
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // Dashboard principal
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
            
            // Ver clase específica - NOTA: es "clase", no "clases"
            Route::get('/{clase}', [ProfesorDashboardController::class, 'verClase'])
                ->name('ver')
                ->middleware('clase.access');
            
            // Agregar estudiante a clase
            Route::post('/{clase}/agregar-estudiante', [ProfesorDashboardController::class, 'agregarEstudiante'])
                ->name('agregar-estudiante')
                ->middleware('clase.access');
            
            // Selección aleatoria de estudiantes
            Route::post('/{clase}/seleccionar-aleatorio', [ProfesorDashboardController::class, 'seleccionarEstudianteAleatorio'])
                ->name('seleccionar-aleatorio')
                ->middleware('clase.access');
                
            // Regenerar código de clase
            Route::post('/{clase}/regenerar-codigo', [ProfesorDashboardController::class, 'regenerarCodigo'])
                ->name('regenerar-codigo')
                ->middleware('clase.access');
        });
    });

    // ==========================================
    // RUTAS ESPECÍFICAS DE ESTUDIANTES 
    // ==========================================
    Route::prefix('estudiantes')->name('estudiantes.')->middleware('user.type:estudiante')->group(function () {
        
        // Dashboard del estudiante
        Route::get('/', [EstudianteInscripcionController::class, 'dashboard'])->name('dashboard');
        
        // Ver perfil del estudiante
        Route::get('/perfil', function() {
            return Inertia::render('Estudiantes/Perfil', [
                'estudiante' => [
                    'nombre' => auth()->user()->nombre,
                    'correo' => auth()->user()->correo
                ],
                'mensaje' => 'Perfil de estudiante en construcción'
            ]);
        })->name('perfil');
        
        // Mis clases
        Route::get('/mis-clases', [EstudianteInscripcionController::class, 'dashboard'])->name('mis-clases');
        
        // Ver clase específica - NOTA: es "clase", no "clases"
        Route::get('/clases/{clase}', [EstudianteInscripcionController::class, 'verClase'])
            ->name('clases.ver')
            ->middleware('clase.access');
        
        // Progreso del estudiante
        Route::get('/progreso', function() {
            return Inertia::render('Estudiantes/Progreso', [
                'progreso' => [],
                'mensaje' => 'Sistema de progreso en desarrollo'
            ]);
        })->name('progreso');
    });

    // ==========================================
    // RUTAS DE CLASES GENERALES
    // ==========================================
    Route::prefix('clases')->name('clases.')->group(function () {
        // Unirse a clase (para estudiantes)
        Route::post('/unirse', [EstudianteInscripcionController::class, 'unirseAClase'])
            ->name('unirse')
            ->middleware('user.type:estudiante');
            
        // Salir de clase (para estudiantes)
        Route::post('/{clase}/salir', [EstudianteInscripcionController::class, 'salirDeClase'])
            ->name('salir')
            ->middleware('user.type:estudiante');
    });

    // ==========================================
    // PERFIL GENERAL
    // ==========================================
    Route::get('/perfil', function() {
        $user = auth()->user();
        
        if ($user->esDocente()) {
            return Inertia::render('Perfil/Docente', [
                'usuario' => [
                    'nombre' => $user->nombre . ' ' . $user->apellido,
                    'correo' => $user->correo,
                    'tipo' => 'docente'
                ]
            ]);
        }
        
        if ($user->esEstudiante()) {
            return Inertia::render('Perfil/Estudiante', [
                'usuario' => [
                    'nombre' => $user->nombre . ' ' . $user->apellido,
                    'correo' => $user->correo,
                    'tipo' => 'estudiante'
                ]
            ]);
        }
        
        // Usuario sin tipo definido
        return Inertia::render('Perfil/General', [
            'usuario' => [
                'nombre' => $user->nombre . ' ' . $user->apellido,
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