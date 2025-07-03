<?php
// app/Http/Middleware/AuthGuardianMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthGuardianMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debes iniciar sesión para acceder a la Torre.',
                    'redirect' => route('login')
                ], 401);
            }
            
            return redirect()->guest(route('login'))
                           ->with('warning', 'La Luz te llama, Guardián. Inicia sesión para continuar.');
        }

        // Actualizar último acceso
        Auth::user()->update(['ultimo_acceso' => now()]);

        return $next($request);
    }
}