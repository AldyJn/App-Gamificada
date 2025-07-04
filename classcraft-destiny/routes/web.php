<?php
// routes/web.php - SUPER SIMPLIFICADO - Solo lo esencial

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Profesor\ProfesorController;

/*
|--------------------------------------------------------------------------
| Rutas Web Esenciales - Sistema Zenthoria
|--------------------------------------------------------------------------
*/

// ===== RUTAS PÚBLICAS =====

// Landing Page principal
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

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
});

// Logout (para usuarios autenticados)
Route::post('/logout', [AuthController::class, 'logout'])
     ->middleware('auth')
     ->name('logout');

// ===== RUTAS DEL PROFESOR =====
Route::middleware(['auth', 'verified', 'profesor'])->prefix('profesor')->name('profesor.')->group(function () {
    // Dashboard Principal
    Route::get('/dashboard', [ProfesorController::class, 'dashboard'])->name('dashboard');
});

// ===== RUTAS DEL ESTUDIANTE =====
Route::middleware(['auth', 'verified', 'estudiante'])->prefix('estudiante')->name('estudiante.')->group(function () {
    // Dashboard Principal - Crear una ruta simple sin controlador por ahora
    Route::get('/dashboard', function () {
        return view('estudiante.dashboard');
    })->name('dashboard');
});

// ===== RUTA DE REDIRECCIÓN GENERAL =====
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->esDocente()) {
        return redirect()->route('profesor.dashboard');
    } elseif ($user->esEstudiante()) {
        return redirect()->route('estudiante.dashboard');
    }
    
    return redirect('/');
})->name('dashboard');