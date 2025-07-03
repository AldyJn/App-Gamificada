<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Iniciando seeding de la base de datos...');

        // 1. Seeders básicos del sistema (OBLIGATORIOS)
        $this->call([
            TipoUsuarioSeeder::class,
            EstadoUsuarioSeeder::class,
            TipoActividadSeeder::class,
            ClaseRpgSeeder::class,
            NivelExperienciaSeeder::class,
            TipoComportamientoSeeder::class,
            BadgeSeeder::class,
        ]);

        $this->command->info('✅ Datos básicos del sistema creados');

        // 2. Configuraciones de clase (para clases existentes)
        if ($this->existenClases()) {
            $this->call(ConfiguracionClaseSeeder::class);
            $this->command->info('✅ Configuraciones de clase inicializadas');
        }

        // // 3. Datos de prueba (OPCIONAL - solo si se solicita)
        // if ($this->shouldSeedTestData()) {
        //     $this->call(DatosPruebaSeeder::class);
        //     $this->command->info('✅ Datos de prueba generados');
        // }

        $this->command->info('🎉 ¡Seeding completado exitosamente!');
        $this->mostrarInstruccionesSiguientes();
    }

    /**
     * Verificar si existen clases en la base de datos
     */
    private function existenClases(): bool
    {
        return \App\Models\Clase::count() > 0;
    }

    /**
     * Determinar si se deben crear datos de prueba
     */
    private function shouldSeedTestData(): bool
    {
        // En ambiente local, preguntar al usuario
        if (app()->environment('local', 'testing')) {
            return $this->command->confirm('¿Deseas crear datos de prueba (usuarios, clases, actividades)?', true);
        }

        // En producción, nunca crear datos de prueba
        return false;
    }

    /**
     * Mostrar instrucciones para los siguientes pasos
     */
    private function mostrarInstruccionesSiguientes(): void
    {
        $this->command->info("\n📋 Próximos pasos recomendados:");
        $this->command->line("1. Ejecutar: php artisan serve");
        $this->command->line("2. Crear tu primer usuario docente si no tienes datos de prueba");
        $this->command->line("3. Probar la aplicación en el navegador");
        
        if (app()->environment('local', 'testing')) {
            $this->command->info("\n🔧 Comandos útiles para desarrollo:");
            $this->command->line("• php artisan db:seed --class=DatosPruebaSeeder");
            $this->command->line("• php artisan migrate:refresh --seed");
        }
    }
}