<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Clase;
use Illuminate\Support\Facades\DB;

class ConfiguracionClaseSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('⚙️ Inicializando configuraciones para clases existentes...');
        
        // Obtener todas las clases existentes
        $clases = DB::table('clase')->get();
        
        if ($clases->isEmpty()) {
            $this->command->warn('No hay clases para configurar');
            return;
        }
        
        // Configuración por defecto para cada clase
        $configuracionDefecto = [
            'permitir_personajes' => true,
            'permitir_tienda' => true,
            'permitir_clanes' => true,
            'sistema_poderes_activo' => true,
            'sistema_badges_activo' => true,
            'economia_virtual_activa' => true,
            'eventos_especiales_activos' => true,
            'multiplicadores_xp' => json_encode([
                'base' => 1.0,
                'primera_participacion' => 2.0,
                'trabajo_equipo' => 1.25,
                'evento_especial' => 1.5,
                'clan_bonus' => 1.1,
                'fin_de_semana' => 1.15
            ]),
            'limites_recursos' => json_encode([
                'salud_minima' => 0,
                'energia_minima' => 0,
                'luz_minima' => 0,
                'max_glimmer_personal' => 10000
            ]),
            'cooldowns_personalizados' => json_encode([
                'revive_compañero' => 7200,      // 2 horas
                'uso_poder_super' => 86400,      // 24 horas  
                'cambio_clan' => 604800          // 7 días
            ]),
        ];
        
        foreach ($clases as $clase) {
            // Verificar si ya existe configuración para esta clase
            $existe = DB::table('configuracion_clase')
                ->where('id_clase', $clase->id)
                ->exists();
                
            if (!$existe) {
                // Insertar configuración por defecto
                $configuracionConId = array_merge(
                    ['id_clase' => $clase->id],
                    $configuracionDefecto,
                    [
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
                
                DB::table('configuracion_clase')->insert($configuracionConId);
                
                $this->command->line("✅ Configuración aplicada a clase: {$clase->nombre}");
            } else {
                $this->command->line("⏭️ Clase ya configurada: {$clase->nombre}");
            }
        }
        
        $this->command->info("✅ Configuraciones inicializadas para {$clases->count()} clase(s)");
    }
}