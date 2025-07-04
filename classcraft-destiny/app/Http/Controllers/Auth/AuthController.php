<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\TipoUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
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
     * Procesar login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'El correo es obligatorio',
            'email.email' => 'El formato del correo no es válido',
            'password.required' => 'La contraseña es obligatoria'
        ]);

        // Buscar usuario por correo
        $usuario = Usuario::where('correo', $request->email)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->contraseña_hash)) {
            throw ValidationException::withMessages([
                'email' => 'Las credenciales no coinciden con nuestros registros.',
            ]);
        }

        // Verificar que el usuario esté activo
        if (!$usuario->activo) {
            throw ValidationException::withMessages([
                'email' => 'Tu cuenta está desactivada. Contacta al administrador.',
            ]);
        }

        // Actualizar último acceso
        $usuario->update(['ultimo_acceso' => now()]);

        // Autenticar usuario
        Auth::login($usuario, $request->boolean('remember'));

        // Regenerar sesión
        $request->session()->regenerate();

        // Redirigir al dashboard
        return redirect()->intended(route('dashboard'));
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
     * Procesar registro
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
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', // Al menos una minúscula, mayúscula y número
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
            // Crear usuario
            $usuario = Usuario::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'correo' => $request->correo,
                'contraseña_hash' => Hash::make($request->password),
                'id_tipo_usuario' => $request->id_tipo_usuario,
                'fecha_registro' => now(),
                'ultimo_acceso' => now(),
                'activo' => true,
            ]);

            // Autenticar inmediatamente
            Auth::login($usuario);

            // Regenerar sesión
            $request->session()->regenerate();

            return redirect()->route('dashboard')->with('success', 
                '¡Registro exitoso! Bienvenido a la Academia Zenthoria, ' . $usuario->nombre . '!');

        } catch (\Exception $e) {
            \Log::error('Error en registro: ' . $e->getMessage(), [
                'request_data' => $request->except('password', 'password_confirmation'),
                'exception' => $e
            ]);

            return back()->withErrors([
                'general' => 'Error al crear la cuenta. Intenta nuevamente.'
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