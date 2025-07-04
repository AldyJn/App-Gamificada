<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;

class EstudiantesController extends Controller
{
    public function buscar(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query || strlen($query) < 3) {
            return response()->json(['estudiantes' => []]);
        }
        
        $estudiantes = Usuario::whereHas('tipoUsuario', function($q) {
                $q->where('nombre', 'estudiante');
            })
            ->where(function($q) use ($query) {
                $q->where('nombre', 'LIKE', "%{$query}%")
                  ->orWhere('correo', 'LIKE', "%{$query}%");
            })
            ->select('id', 'nombre', 'correo')
            ->limit(10)
            ->get();
        
        return response()->json(['estudiantes' => $estudiantes]);
    }
}