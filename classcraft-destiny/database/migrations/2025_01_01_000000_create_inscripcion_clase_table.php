<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inscripcion_clase', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('id_clase')
                  ->constrained('clase')
                  ->onDelete('cascade')
                  ->comment('ID de la clase');
                  
            $table->foreignId('id_estudiante')
                  ->constrained('usuario')
                  ->onDelete('cascade')
                  ->comment('ID del estudiante (referencia a usuario)');
            
            // Datos de inscripción
            $table->timestamp('fecha_ingreso')
                  ->default(now())
                  ->comment('Fecha de inscripción en la clase');
                  
            $table->boolean('activo')
                  ->default(true)
                  ->comment('Si la inscripción está activa');
                  
            $table->json('datos_adicionales')
                  ->nullable()
                  ->comment('Datos adicionales de la inscripción (configuraciones, preferencias, etc.)');
            
            // Metadatos
            $table->timestamps();
            
            // Índices
            $table->unique(['id_clase', 'id_estudiante'], 'inscripcion_unica');
            $table->index('activo');
            $table->index('fecha_ingreso');
            
            // Comentario de tabla
            $table->comment('Tabla pivot para la relación muchos a muchos entre clases y estudiantes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscripcion_clase');
    }
};