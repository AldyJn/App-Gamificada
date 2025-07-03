<?php
// app/Http/Middleware/RoleMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $usuario = Auth::user();
        $tipoUsuario = $usuario->tipoUsuario->nombre;

        if (!in_array($tipoUsuario, $roles)) {
            abort(403, 'No tienes permisos para acceder a esta sección de la Torre.');
        }

        return $next($request);
    }
}