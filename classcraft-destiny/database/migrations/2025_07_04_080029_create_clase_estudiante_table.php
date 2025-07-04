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
        Schema::create('clase_estudiante', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clase_id')->constrained('clase')->onDelete('cascade');
            $table->foreignId('estudiante_id')->constrained('usuario')->onDelete('cascade');
            $table->timestamp('fecha_inscripcion')->useCurrent();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            // Índices
            $table->unique(['clase_id', 'estudiante_id']);
            $table->index(['clase_id', 'activo']);
            $table->index('estudiante_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clase_estudiante');
    }
};