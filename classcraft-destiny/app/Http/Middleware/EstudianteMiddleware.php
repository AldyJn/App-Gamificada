<?php
// app/Http/Middleware/EstudianteMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstudianteMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                           ->with('warning', 'Debes iniciar sesión para acceder como Guardián.');
        }

        if (!Auth::user()->esEstudiante()) {
            return redirect()->route('profesor.dashboard')
                           ->with('error', 'Esta sección es solo para Guardianes.');
        }

        return $next($request);
    }
}