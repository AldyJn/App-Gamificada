<?php
// ===== database/seeders/NivelExperienciaSeeder.php =====
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NivelExperienciaSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🔄 Generando 100 niveles de experiencia...');
        
        $data = [];
        $baseXP = 100;
        $multiplier = 1.15;
        
        for ($nivel = 1; $nivel <= 100; $nivel++) {
            $xpRequerida = ($nivel == 1) ? 0 : round($baseXP * pow($multiplier, $nivel - 1));
            
            // Títulos especiales por rangos
            $titulo = match(true) {
                $nivel <= 10 => 'Guardián Novato',
                $nivel <= 20 => 'Guardián Iniciado', 
                $nivel <= 30 => 'Guardián Experimentado',
                $nivel <= 40 => 'Veterano de la Torre',
                $nivel <= 50 => 'Señor de la Guerra',
                $nivel <= 60 => 'Campeón de la Luz',
                $nivel <= 70 => 'Leyenda Viviente',
                $nivel <= 80 => 'Conquistador del Vacío',
                $nivel <= 90 => 'Maestro del Destino',
                default => 'Inmortal'
            };
            
            // Recompensas especiales cada 10 niveles
            $recompensas = [];
            if ($nivel % 10 == 0) {
                $recompensas = [
                    'poder_desbloqueado' => true,
                    'slot_gear' => true,
                    'glimmer_bonus' => $nivel * 10,
                    'emblema_especial' => "level_{$nivel}"
                ];
            }
            
            $data[] = [
                'nivel' => $nivel,
                'experiencia_requerida' => $xpRequerida,
                'recompensas' => json_encode($recompensas),
                'titulo_desbloqueado' => $titulo,
                'poder_desbloqueado' => ($nivel % 10 == 0) ? "poder_nivel_{$nivel}" : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Insertar en lotes de 20 para evitar timeouts
            if (count($data) >= 20) {
                DB::table('nivel_experiencia')->insert($data);
                $data = [];
                $this->command->line("📊 Procesados niveles hasta el {$nivel}");
            }
        }
        
        // Insertar cualquier remanente
        if (!empty($data)) {
            DB::table('nivel_experiencia')->insert($data);
        }
        
        $this->command->info('✅ 100 niveles de experiencia creados exitosamente');
    }
}