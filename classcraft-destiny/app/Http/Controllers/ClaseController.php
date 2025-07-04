<?php

namespace App\Http\Controllers;

use App\Models\Clase;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ClaseController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Si es docente, mostrar sus clases
        if ($user->esDocente()) {
            $clases = Clase::where('docente_id', $user->id)
                ->withCount(['estudiantes', 'actividades'])
                ->orderBy('created_at', 'desc')
                ->get();
                
            return Inertia::render('Clases/Index', [
                'clases' => $clases
            ]);
        }
        
        // Si es estudiante, mostrar clases en las que está inscrito
        $clases = $user->clases()
            ->withCount(['estudiantes', 'actividades'])
            ->with('docente:id,nombre,correo')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return Inertia::render('Clases/IndexEstudiante', [
            'clases' => $clases
        ]);
    }

    public function create()
    {
        // Solo docentes pueden crear clases
        if (!auth()->user()->esDocente()) {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos para crear clases');
        }
        
        return Inertia::render('Clases/Create');
    }

    public function store(Request $request)
    {
        // Validación
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'codigo' => 'nullable|string|max:10|unique:clases,codigo',
            'permitir_inscripcion' => 'boolean',
            'notificar_actividades' => 'boolean'
        ]);

        // Generar código si no se proporcionó
        if (!$validated['codigo']) {
            $validated['codigo'] = $this->generarCodigoUnico();
        }

        // Crear la clase
        $clase = Clase::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'fecha_inicio' => $validated['fecha_inicio'],
            'fecha_fin' => $validated['fecha_fin'],
            'codigo' => strtoupper($validated['codigo']),
            'docente_id' => auth()->id(),
            'permitir_inscripcion' => $validated['permitir_inscripcion'] ?? true,
            'notificar_actividades' => $validated['notificar_actividades'] ?? true,
            'activa' => true
        ]);

        return redirect()->route('clases.show', $clase->id)
            ->with('success', 'Clase creada exitosamente');
    }

    public function show(Clase $clase)
    {
        // Verificar acceso
        if (!$this->puedeAccederClase($clase)) {
            return redirect()->route('dashboard')->with('error', 'No tienes acceso a esta clase');
        }

        // Cargar relaciones
        $clase->load([
            'docente:id,nombre,correo',
            'estudiantes' => function($query) {
                $query->select('usuarios.id', 'usuarios.nombre', 'usuarios.correo')
                      ->withPivot('fecha_inscripcion', 'activo');
            },
            'actividades' => function($query) {
                $query->orderBy('fecha_limite', 'desc')->take(5);
            }
        ]);

        return Inertia::render('Clases/Show', [
            'clase' => $clase,
            'estudiantes' => $clase->estudiantes,
            'actividades' => $clase->actividades
        ]);
    }

    public function edit(Clase $clase)
    {
        // Solo el docente puede editar
        if (!auth()->user()->esDocente() || $clase->docente_id !== auth()->id()) {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos para editar esta clase');
        }

        return Inertia::render('Clases/Edit', [
            'clase' => $clase
        ]);
    }

    public function update(Request $request, Clase $clase)
    {
        // Verificar permisos
        if (!auth()->user()->esDocente() || $clase->docente_id !== auth()->id()) {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos para editar esta clase');
        }

        // Validación
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'codigo' => 'nullable|string|max:10|unique:clases,codigo,' . $clase->id,
            'permitir_inscripcion' => 'boolean',
            'notificar_actividades' => 'boolean',
            'activa' => 'boolean'
        ]);

        // Actualizar
        $clase->update($validated);

        return redirect()->route('clases.show', $clase->id)
            ->with('success', 'Clase actualizada exitosamente');
    }

    public function destroy(Clase $clase)
    {
        // Solo el docente puede eliminar
        if (!auth()->user()->esDocente() || $clase->docente_id !== auth()->id()) {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos para eliminar esta clase');
        }

        $clase->delete();

        return redirect()->route('clases.index')
            ->with('success', 'Clase eliminada exitosamente');
    }

    // Métodos específicos para funcionalidad del profesor

    public function agregarEstudiante(Request $request, Clase $clase)
    {
        // Verificar permisos
        if (!$this->puedeGestionarClase($clase)) {
            return response()->json(['error' => 'No tienes permisos'], 403);
        }

        $request->validate([
            'estudiante_id' => 'required|exists:usuarios,id'
        ]);

        $estudiante = Usuario::find($request->estudiante_id);
        
        // Verificar que sea estudiante
        if (!$estudiante->esEstudiante()) {
            return response()->json(['error' => 'El usuario no es un estudiante'], 400);
        }

        // Verificar si ya está inscrito
        if ($clase->estudiantes()->where('usuario_id', $estudiante->id)->exists()) {
            return response()->json(['error' => 'El estudiante ya está inscrito'], 400);
        }

        // Agregar estudiante
        $clase->estudiantes()->attach($estudiante->id, [
            'fecha_inscripcion' => now(),
            'activo' => true
        ]);

        return redirect()->back()->with('success', 'Estudiante agregado exitosamente');
    }

    public function removerEstudiante(Clase $clase, Usuario $estudiante)
    {
        // Verificar permisos
        if (!$this->puedeGestionarClase($clase)) {
            return redirect()->back()->with('error', 'No tienes permisos');
        }

        // Remover estudiante
        $clase->estudiantes()->detach($estudiante->id);

        return redirect()->back()->with('success', 'Estudiante removido exitosamente');
    }

    public function estudianteAleatorio(Clase $clase)
    {
        // Verificar permisos
        if (!$this->puedeGestionarClase($clase)) {
            return response()->json(['error' => 'No tienes permisos'], 403);
        }

        $estudiantes = $clase->estudiantes()->where('activo', true)->get();

        if ($estudiantes->isEmpty()) {
            return response()->json(['error' => 'No hay estudiantes activos en la clase'], 400);
        }

        $estudianteAleatorio = $estudiantes->random();

        return response()->json([
            'estudiante' => $estudianteAleatorio
        ]);
    }

    public function unirse()
    {
        // Solo estudiantes pueden unirse
        if (!auth()->user()->esEstudiante()) {
            return redirect()->route('dashboard')->with('error', 'Solo los estudiantes pueden unirse a clases');
        }

        return Inertia::render('Clases/Unirse');
    }

    public function procesarUnion(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:10'
        ]);

        $clase = Clase::where('codigo', strtoupper($request->codigo))
            ->where('activa', true)
            ->first();

        if (!$clase) {
            return redirect()->back()->with('error', 'Código de clase no válido');
        }

        // Verificar si ya está inscrito
        if ($clase->estudiantes()->where('usuario_id', auth()->id())->exists()) {
            return redirect()->route('clases.show', $clase->id)
                ->with('info', 'Ya estás inscrito en esta clase');
        }

        // Verificar si permite inscripción
        if (!$clase->permitir_inscripcion) {
            return redirect()->back()->with('error', 'Esta clase no permite inscripción libre');
        }

        // Verificar fechas
        if (Carbon::parse((string)$clase->fecha_fin)->isPast()) {
            return redirect()->back()->with('error', 'Esta clase ya ha finalizado');
        }

        // Inscribir estudiante
        $clase->estudiantes()->attach(auth()->id(), [
            'fecha_inscripcion' => now(),
            'activo' => true
        ]);

        return redirect()->route('clases.show', $clase->id)
            ->with('success', 'Te has unido a la clase exitosamente');
    }

    // Métodos auxiliares

    private function generarCodigoUnico()
    {
        do {
            $codigo = strtoupper(Str::random(6));
        } while (Clase::where('codigo', $codigo)->exists());

        return $codigo;
    }

    private function puedeAccederClase(Clase $clase)
    {
        $user = auth()->user();
        
        // Si es docente y es propietario
        if ($user->esDocente() && $clase->docente_id === $user->id) {
            return true;
        }
        
        // Si es estudiante y está inscrito
        if ($user->esEstudiante() && $clase->estudiantes()->where('usuario_id', $user->id)->exists()) {
            return true;
        }
        
        return false;
    }

    private function puedeGestionarClase(Clase $clase)
    {
        $user = auth()->user();
        return $user->esDocente() && $clase->docente_id === $user->id;
    }
}