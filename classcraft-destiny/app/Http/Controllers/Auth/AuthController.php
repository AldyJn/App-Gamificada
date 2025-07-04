<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\TipoUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function showLoginForm()
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => true,
            'status' => session('status'),
        ]);
    }

    /**
     * Procesar login - COMPLETAMENTE CORREGIDO
     */
    public function login(Request $request)
    {
        // ✅ CORREGIDO: Validar 'correo' que coincide con la vista Vue
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required'
        ], [
            'correo.required' => 'El correo es obligatorio',
            'correo.email' => 'El formato del correo no es válido',
            'password.required' => 'La contraseña es obligatoria'
        ]);

        // ✅ CORREGIDO: Usar 'correo' del request
        $usuario = Usuario::where('correo', $request->correo)->first();

        // ✅ LOGGING mejorado para debugging
        Log::info('Intento de login', [
            'correo' => $request->correo,
            'usuario_encontrado' => $usuario ? 'Sí' : 'No',
            'ip' => $request->ip()
        ]);

        if (!$usuario) {
            Log::warning('Usuario no encontrado', ['correo' => $request->correo]);
            throw ValidationException::withMessages([
                'correo' => 'No se encontró una cuenta con este correo electrónico.',
            ]);
        }

        if (!Hash::check($request->password, $usuario->contraseña_hash)) {
            Log::warning('Contraseña incorrecta', ['correo' => $request->correo]);
            throw ValidationException::withMessages([
                'correo' => 'La contraseña es incorrecta.',
            ]);
        }

        // ✅ CORREGIDO: Verificar estado usando los campos reales de la migración
        if ($usuario->eliminado) {
            Log::warning('Usuario eliminado', ['correo' => $request->correo]);
            throw ValidationException::withMessages([
                'correo' => 'Esta cuenta ha sido desactivada. Contacta al administrador.',
            ]);
        }

        if ($usuario->id_estado != 1) { // 1 = activo según el seeder
            Log::warning('Usuario con estado inválido', [
                'correo' => $request->correo,
                'estado' => $usuario->id_estado
            ]);
            throw ValidationException::withMessages([
                'correo' => 'Tu cuenta no está disponible. Contacta al administrador.',
            ]);
        }

        try {
            // Actualizar último acceso
            $usuario->update(['ultimo_acceso' => now()]);

            // Autenticar usuario
            Auth::login($usuario, $request->boolean('remember'));

            // Regenerar sesión
            $request->session()->regenerate();

            Log::info('Login exitoso', [
                'correo' => $request->correo, 
                'user_id' => $usuario->id,
                'tipo_usuario' => $usuario->id_tipo_usuario
            ]);

            // Redirigir al dashboard
            return redirect()->intended(route('dashboard'));

        } catch (\Exception $e) {
            Log::error('Error durante login', [
                'correo' => $request->correo,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw ValidationException::withMessages([
                'correo' => 'Error interno del servidor. Intenta nuevamente.',
            ]);
        }
    }

    /**
     * Mostrar formulario de registro
     */
    public function showRegisterForm()
    {
        return Inertia::render('Auth/Register', [
            'tipos_usuario' => TipoUsuario::all(),
            'status' => session('status'),
        ]);
    }

    /**
     * Procesar registro - COMPLETAMENTE CORREGIDO
     */
    public function register(Request $request)
    {
        // Validación completa
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'correo' => 'required|string|email|max:100|unique:usuario,correo',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ],
            'id_tipo_usuario' => 'required|exists:tipo_usuario,id',
            'terminos' => 'accepted',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'apellido.required' => 'El apellido es obligatorio',
            'correo.required' => 'El correo es obligatorio',
            'correo.email' => 'El formato del correo no es válido',
            'correo.unique' => 'Ya existe una cuenta con este correo',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula y un número',
            'id_tipo_usuario.required' => 'Debe seleccionar un tipo de usuario',
            'id_tipo_usuario.exists' => 'El tipo de usuario seleccionado no es válido',
            'terminos.accepted' => 'Debe aceptar los términos y condiciones',
        ]);

        try {
            // ✅ CORREGIDO: Crear usuario usando campos que existen en la migración
            $usuario = Usuario::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'correo' => $request->correo,
                'contraseña_hash' => Hash::make($request->password),
                'salt' => Str::random(32),  // ✅ Agregado: requerido por la migración
                'id_tipo_usuario' => $request->id_tipo_usuario,
                'id_estado' => 1, // ✅ CORREGIDO: usar id_estado en lugar de activo
                'ultimo_acceso' => now(),
                'timezone' => 'America/Lima',
                'eliminado' => false, // ✅ CORREGIDO: usar eliminado en lugar de activo
                // ✅ ELIMINADO: 'fecha_registro' no existe en la migración
                // ✅ ELIMINADO: 'activo' no existe en la migración
            ]);

            Log::info('Usuario registrado exitosamente', [
                'correo' => $usuario->correo,
                'id' => $usuario->id,
                'tipo' => $usuario->id_tipo_usuario
            ]);

            // Autenticar inmediatamente
            Auth::login($usuario);

            // Regenerar sesión
            $request->session()->regenerate();

            return redirect()->route('dashboard')->with('success', 
                '¡Registro exitoso! Bienvenido a la Academia Zenthoria, ' . $usuario->nombre . '!');

        } catch (\Exception $e) {
            Log::error('Error en registro', [
                'error' => $e->getMessage(),
                'request_data' => $request->except('password', 'password_confirmation'),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'general' => 'Error al crear la cuenta. Intenta nuevamente: ' . $e->getMessage()
            ])->withInput($request->except('password', 'password_confirmation'));
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Sesión cerrada correctamente');
    }

    /**
     * Verificar disponibilidad del correo (para AJAX)
     */
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $exists = Usuario::where('correo', $request->email)->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Este correo ya está registrado' : 'Correo disponible'
        ]);
    }
}