<?php
// ===========================================
// 1. MODELO CLASE CORREGIDO
// ===========================================

// app/Models/Clase.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Clase extends Model
{
    use HasFactory;

    protected $table = 'clase';

    protected $fillable = [
        'nombre',
        'descripcion', 
        'codigo',
        'id_docente',
        'fecha_inicio',
        'fecha_fin',
        'activa',
        'permitir_inscripcion'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'activa' => 'boolean',
        'permitir_inscripcion' => 'boolean'
    ];

    // RELACIONES
    public function docente(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_docente');
    }

    public function estudiantes(): BelongsToMany
    {
        return $this->belongsToMany(
            Usuario::class, 
            'inscripcion_clase', 
            'id_clase', 
            'id_estudiante'  // LA COLUMNA CORRECTA
        )->withTimestamps()
         ->withPivot(['activo']);
    }

    // MÉTODOS PRINCIPALES
    public function estaActiva(): bool
    {
        if (!$this->activa) return false;
        if (!$this->fecha_inicio || !$this->fecha_fin) return true;
        
        $hoy = now();
        return $hoy >= $this->fecha_inicio && $hoy <= $this->fecha_fin;
    }

    public function totalEstudiantes(): int
    {
        return DB::table('inscripcion_clase')
            ->where('id_clase', $this->id)
            ->where('activo', true)
            ->count();
    }

    public function estudiantesActivos()
    {
        return DB::table('inscripcion_clase')
            ->join('usuarios', 'inscripcion_clase.id_estudiante', '=', 'usuarios.id')
            ->where('inscripcion_clase.id_clase', $this->id)
            ->where('inscripcion_clase.activo', true)
            ->select([
                'usuarios.id',
                'usuarios.nombre',
                'usuarios.apellido',
                'usuarios.correo',
                'inscripcion_clase.created_at as fecha_inscripcion'
            ])
            ->get();
    }

    public function obtenerProgreso(): array
    {
        return [
            'completadas' => 0,
            'total_actividades' => 0,
            'porcentaje' => 0
        ];
    }

    public function agregarEstudiante(int $usuarioId): bool
    {
        try {
            $existe = DB::table('inscripcion_clase')
                ->where('id_clase', $this->id)
                ->where('id_estudiante', $usuarioId)
                ->exists();

            if ($existe) {
                DB::table('inscripcion_clase')
                    ->where('id_clase', $this->id)
                    ->where('id_estudiante', $usuarioId)
                    ->update(['activo' => true, 'updated_at' => now()]);
            } else {
                DB::table('inscripcion_clase')->insert([
                    'id_clase' => $this->id,
                    'id_estudiante' => $usuarioId,
                    'activo' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            return true;
        } catch (\Exception $e) {
            \Log::error('Error agregando estudiante: ' . $e->getMessage());
            return false;
        }
    }

    public function tieneEstudiante(int $usuarioId): bool
    {
        return DB::table('inscripcion_clase')
            ->where('id_clase', $this->id)
            ->where('id_estudiante', $usuarioId)
            ->where('activo', true)
            ->exists();
    }

    public function regenerarCodigo(): string
    {
        do {
            $codigo = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        } while (self::where('codigo', $codigo)->exists());
        
        $this->codigo = $codigo;
        $this->save();
        return $codigo;
    }

    public static function generarCodigoUnico(): string
    {
        do {
            $codigo = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        } while (self::where('codigo', $codigo)->exists());
        return $codigo;
    }
}