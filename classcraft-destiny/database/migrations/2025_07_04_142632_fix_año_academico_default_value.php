<?php
// 🔧 CREAR NUEVA MIGRACIÓN PARA CORREGIR año_academico
// Ejecutar: php artisan make:migration fix_año_academico_default_value

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('clase', function (Blueprint $table) {
            // Hacer que año_academico tenga un valor por defecto
            $table->string('año_academico')->default(date('Y'))->change();
        });
    }

    public function down()
    {
        Schema::table('clase', function (Blueprint $table) {
            // Revertir el cambio
            $table->string('año_academico')->nullable(false)->change();
        });
    }
};