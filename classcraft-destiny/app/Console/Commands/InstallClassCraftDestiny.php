<?php
// app/Console/Commands/InstallClassCraftDestiny.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallClassCraftDestiny extends Command
{
    protected $signature = 'classcraft:install';
    protected $description = 'Instala y configura el sistema ClassCraft-Destiny completo';

    public function handle()
    {
        $this->info('🚀 Instalando ClassCraft-Destiny System...');
        
        // Migrar base de datos
        $this->info('📊 Ejecutando migraciones...');
        Artisan::call('migrate:fresh');
        
        // Ejecutar seeders
        $this->info('🌱 Poblando base de datos...');
        Artisan::call('db:seed');
        
        // Crear enlace de storage
        $this->info('🔗 Creando enlace de storage...');
        Artisan::call('storage:link');
        
        // Limpiar caché
        $this->info('🧹 Limpiando caché...');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        
        $this->newLine();
        $this->info('✅ ¡Sistema ClassCraft-Destiny instalado exitosamente!');
        $this->newLine();
        
        $this->warn('🔑 CREDENCIALES DE ACCESO:');
        $this->line('👨‍🏫 PROFESOR: profesor@test.com | password123');
        $this->line('👥 ESTUDIANTES: ana@estudiante.test.com | password123');
        $this->line('                luis@estudiante.test.com | password123');
        $this->line('                (y más estudiantes de prueba)');
        $this->newLine();
        
        $this->info('🌐 Accede a: ' . config('app.url'));
        $this->info('🎮 ¡La Torre te espera, Guardián!');
    }
}