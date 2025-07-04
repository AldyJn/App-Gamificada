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
        Schema::table('clase', function (Blueprint $table) {
            // Agregar columnas que podrían faltar
if (!Schema::hasColumn('clase', 'codigo')) {
    $table->string('codigo', 10)->nullable()->unique()->after('nombre');
}
            
            if (!Schema::hasColumn('clase', 'grado')) {
                $table->string('grado', 50)->nullable()->after('descripcion');
            }
            
            if (!Schema::hasColumn('clase', 'seccion')) {
                $table->string('seccion', 10)->nullable()->after('grado');
            }
            
            if (!Schema::hasColumn('clase', 'activa')) {
                $table->boolean('activa')->default(true)->after('fecha_fin');
            }
            
            if (!Schema::hasColumn('clase', 'permitir_inscripcion')) {
                $table->boolean('permitir_inscripcion')->default(true)->after('activa');
            }
            
            if (!Schema::hasColumn('clase', 'configuracion_gamificacion')) {
                $table->json('configuracion_gamificacion')->nullable()->after('permitir_inscripcion');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clase', function (Blueprint $table) {
            $columns = ['codigo', 'grado', 'seccion', 'activa', 'permitir_inscripcion', 'configuracion_gamificacion'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('clase', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};