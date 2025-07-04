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
use Inertia\Inertia;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de login con componente Vue
     */
    public function showLoginForm()
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => true,
            'status' => session('status'),
        ]);
    }

    /**
     * Mostrar formulario de registro con componente Vue
     */
    public function showRegisterForm()
    {
        return Inertia::render('Auth/Register', [
            'mustVerifyEmail' => false,
            'status' => session('status'),
        ]);
    }

    /**
     * Procesar login y redirigir según tipo de usuario
     */
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required'
        ], [
            'correo.required' => 'El correo es obligatorio',
            'correo.email' => 'El correo debe ser válido',
            'password.required' => 'La contraseña es obligatoria'
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
        Auth::login($usuario, $request->boolean('remember'));

        // Regenerar ID de sesión por seguridad
        $request->session()->regenerate();

        // Redirigir según tipo de usuario
        return $usuario->esDocente() 
            ? redirect()->intended(route('profesor.dashboard'))
            : redirect()->intended(route('estudiante.dashboard'));
    }

    /**
     * Procesar registro de nuevo usuario
     */
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'correo' => 'required|email|unique:usuarios,correo',
            'password' => 'required|string|min:8|confirmed',
            'id_tipo_usuario' => 'required|integer|in:1,2',
            'clase_guardian' => 'nullable|string|in:titan,hunter,warlock',
            'nombre_personaje' => 'nullable|string|max:50',
            'newsletter' => 'boolean'
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'apellido.required' => 'El apellido es obligatorio',
            'correo.required' => 'El correo es obligatorio',
            'correo.email' => 'El correo debe ser válido',
            'correo.unique' => 'Este correo ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'id_tipo_usuario.required' => 'Selecciona un tipo de usuario',
            'id_tipo_usuario.in' => 'Tipo de usuario inválido'
        ]);

        // Crear nuevo usuario
        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'correo' => $request->correo,
            'contraseña_hash' => Hash::make($request->password),
            'id_tipo_usuario' => $request->id_tipo_usuario,
            'id_estado' => 1, // Activo
            'avatar' => $this->getAvatarForClass($request->clase_guardian),
            'fecha_registro' => now(),
            'ultimo_acceso' => now(),
            'configuracion_ui' => json_encode([
                'tema' => 'dark',
                'animaciones' => true,
                'sonidos' => true,
                'clase_guardian' => $request->clase_guardian,
                'nombre_personaje' => $request->nombre_personaje
            ])
        ]);

        // Si es estudiante, crear personaje
        if ($request->id_tipo_usuario == 2 && $request->clase_guardian) {
            $this->createPersonajeForStudent($usuario, $request);
        }

        // Autenticar automáticamente
        Auth::login($usuario);

        // Regenerar ID de sesión
        $request->session()->regenerate();

        // Redirigir según tipo de usuario
        return $usuario->esDocente() 
            ? redirect()->route('profesor.dashboard')
            : redirect()->route('estudiante.dashboard');
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome');
    }

    /**
     * Obtener avatar según la clase de guardián
     */
    private function getAvatarForClass($claseGuardian)
    {
        $avatars = [
            'titan' => '/images/avatars/titan_default.jpg',
            'hunter' => '/images/avatars/hunter_default.jpg',
            'warlock' => '/images/avatars/warlock_default.jpg'
        ];

        return $avatars[$claseGuardian] ?? '/images/avatars/default.jpg';
    }

    /**
     * Crear personaje para estudiante
     */
    private function createPersonajeForStudent($usuario, $request)
    {
        // Solo si tienes modelo Personaje
        if (class_exists('App\Models\Personaje')) {
            \App\Models\Personaje::create([
                'id_usuario' => $usuario->id,
                'nombre' => $request->nombre_personaje ?: $usuario->nombre,
                'clase' => $request->clase_guardian,
                'nivel' => 1,
                'experiencia' => 0,
                'puntos_luz' => 0,
                'fuerza' => 10,
                'agilidad' => 10,
                'intelecto' => 10,
                'avatar' => $this->getAvatarForClass($request->clase_guardian),
                'fecha_creacion' => now()
            ]);
        }
    }

    /**
     * Verificar disponibilidad de email (para AJAX)
     */
    public function checkEmail(Request $request)
    {
        $request->validate([
            'correo' => 'required|email'
        ]);

        $exists = Usuario::where('correo', $request->correo)->exists();

        return response()->json([
            'disponible' => !$exists,
            'message' => $exists ? 'Este correo ya está registrado' : 'Correo disponible'
        ]);
    }
}