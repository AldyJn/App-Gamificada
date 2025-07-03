<?php
// app/Http/Controllers/Auth/AuthController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de login con estética Destiny
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesar login y redirigir según tipo de usuario
     */
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required'
        ]);

        $usuario = Usuario::where('correo', $request->correo)
                          ->where('id_estado', 1) // Solo usuarios activos
                          ->first();

        if (!$usuario || !Hash::check($request->password, $usuario->contraseña_hash)) {
            throw ValidationException::withMessages([
                'correo' => ['Las credenciales no coinciden con nuestros registros.'],
            ]);
        }

        // Actualizar último acceso
        $usuario->update(['ultimo_acceso' => now()]);

        // Crear sesión
        Auth::login($usuario);

        // Redirigir según tipo de usuario
        return $usuario->esDocente() 
            ? redirect()->route('profesor.dashboard')
            : redirect()->route('estudiante.dashboard');
    }

    /**
     * Mostrar formulario de registro para estudiantes
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Registrar nuevo estudiante
     */
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'correo' => 'required|email|unique:usuario,correo',
            'password' => 'required|min:8|confirmed',
            'grado' => 'nullable|string|max:50',
            'seccion' => 'nullable|string|max:10'
        ]);

        // Crear usuario
        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'correo' => $request->correo,
            'contraseña_hash' => Hash::make($request->password),
            'salt' => Str::random(32),
            'id_tipo_usuario' => 2, // estudiante
            'id_estado' => 1, // activo
            'configuracion_ui' => [
                'tema' => 'destiny_dark',
                'animaciones' => true,
                'sonidos' => true,
                'idioma' => 'es',
                'mostrar_tutoriales' => true
            ]
        ]);

        // Crear perfil de estudiante
        $estudiante = $usuario->estudiante()->create([
            'codigo_estudiante' => $this->generarCodigoEstudiante(),
            'grado' => $request->grado,
            'seccion' => $request->seccion
        ]);

        Auth::login($usuario);

        return redirect()->route('estudiante.dashboard')
               ->with('success', '¡Bienvenido Guardián! Tu cuenta ha sido creada exitosamente.');
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Sesión cerrada exitosamente.');
    }

    /**
     * Generar código único para estudiante
     */
    private function generarCodigoEstudiante(): string
    {
        do {
            $codigo = 'EST' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (Usuario::whereHas('estudiante', function($query) use ($codigo) {
            $query->where('codigo_estudiante', $codigo);
        })->exists());

        return $codigo;
    }
}