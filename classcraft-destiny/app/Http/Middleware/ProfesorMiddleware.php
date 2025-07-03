<?php
// app/Http/Middleware/ProfesorMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfesorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                           ->with('warning', 'Debes iniciar sesión para acceder a la Torre.');
        }

        if (!Auth::user()->esDocente()) {
            return redirect()->route('estudiante.dashboard')
                           ->with('error', 'Esta sección es solo para Instructores de la Torre.');
        }

        return $next($request);
    }
}