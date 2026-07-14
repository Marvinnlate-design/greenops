<?php

namespace App\Console\Commands;

use App\Models\Sensor;
use App\Models\SensorReading;
use App\Models\Actuator;
use App\Models\ActuatorLog;
use App\Models\AutomationRule;
use App\Events\SensorReadingRecorded;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SimulateSensorData extends Command
{
protected $signature = 'sensors:simulate {--count=1 : Nombre de lectures à générer par capteur} {--sleep=0 : Temps d\'attente entre chaque génération (secondes)}';
    protected $description = 'Simule des données de capteurs pour la démonstration';

    public function handle()
    {
        $count = $this->option('count');
        $sleep = (int) $this->option('sleep');
        
        $sensors = Sensor::where('is_active', true)->get();
        
        if ($sensors->isEmpty()) {
            $this->error('Aucun capteur actif trouvé. Lance d\'abord le seeder.');
            return 1;
        }

        $this->info("Génération de {$count} lectures pour " . $sensors->count() . " capteurs...");

        for ($i = 0; $i < $count; $i++) {
            foreach ($sensors as $sensor) {
                $value = $this->generateRealisticValue($sensor);
                
                $reading = SensorReading::create([
                    'sensor_id' => $sensor->id,
                    'value' => $value,
                    'reading_time' => Carbon::now()->subSeconds($count - $i),
                ]);

                // Déclencher l'événement pour les alertes
                event(new SensorReadingRecorded($reading));

                // Évaluer les règles d'automatisation
                $this->evaluateRules($reading);
            }

            if ($sleep > 0) {
                sleep($sleep);
            }
        }

        $this->info("✅ Génération terminée !");
        return 0;
    }

    private function generateRealisticValue(Sensor $sensor): float
    {
        $baseValue = 0;
        $variation = 0;
        $min = 0;
        $max = 100;

        switch ($sensor->type) {
            case 'soil_moisture':
                $baseValue = 55; // Humidité moyenne
                $variation = 25;
                $min = 20;
                $max = 85;
                break;
            case 'temperature':
                if (str_contains($sensor->name, 'Serre')) {
                    $baseValue = 28; // Température serre
                    $variation = 8;
                    $min = 18;
                    $max = 38;
                } elseif (str_contains($sensor->name, 'digesteur')) {
                    $baseValue = 37; // Température digestion
                    $variation = 5;
                    $min = 30;
                    $max = 45;
                } elseif (str_contains($sensor->name, 'compost')) {
                    $baseValue = 52; // Compost actif
                    $variation = 10;
                    $min = 40;
                    $max = 65;
                } else {
                    $baseValue = 25;
                    $variation = 10;
                    $min = 15;
                    $max = 40;
                }
                break;
            case 'humidity':
                $baseValue = 70;
                $variation = 15;
                $min = 45;
                $max = 90;
                break;
            case 'water_level':
                $baseValue = 100;
                $variation = 30;
                $min = 20;
                $max = 150;
                break;
            case 'ph':
                $baseValue = 6.5;
                $variation = 1.0;
                $min = 5.0;
                $max = 8.0;
                break;
            case 'solar_power':
                // Simuler la production solaire en fonction de l'heure
                $hour = Carbon::now()->hour;
                if ($hour >= 6 && $hour <= 18) {
                    // Journée : production maximale à midi
                    $peakFactor = 1 - abs($hour - 12) / 12;
                    $baseValue = 5 * $peakFactor; // Max 5 kWh
                    $variation = 0.5;
                } else {
                    $baseValue = 0;
                    $variation = 0.1;
                }
                $min = 0;
                $max = 6;
                break;
            case 'battery_soc':
                // Simuler une batterie qui se décharge la nuit et se recharge le jour
                $hour = Carbon::now()->hour;
                if ($hour >= 6 && $hour <= 18) {
                    $baseValue = 80 + (($hour - 6) / 12) * 20; // Charge progressive
                } else {
                    $baseValue = 80 - (($hour - 18) / 12) * 60; // Décharge
                    if ($baseValue < 20) $baseValue = 20;
                }
                $variation = 3;
                $min = 15;
                $max = 98;
                break;
            case 'biogas_flow':
                $baseValue = 2.5;
                $variation = 1.0;
                $min = 0.5;
                $max = 5.0;
                break;
            default:
                $baseValue = 50;
                $variation = 20;
                $min = 0;
                $max = 100;
        }

        // Générer une valeur aléatoire avec une distribution normale approximative
        $value = $baseValue + (mt_rand(-100, 100) / 100) * $variation;
        $value = max($min, min($max, $value));

        // Arrondir à 2 décimales
        return round($value, 2);
    }

    private function evaluateRules(SensorReading $reading)
    {
        $rules = AutomationRule::where('sensor_id', $reading->sensor_id)
            ->where('is_active', true)
            ->get();

        foreach ($rules as $rule) {
            $conditionMet = false;
            switch ($rule->operator) {
                case '<': $conditionMet = $reading->value < $rule->threshold; break;
                case '>': $conditionMet = $reading->value > $rule->threshold; break;
                case '<=': $conditionMet = $reading->value <= $rule->threshold; break;
                case '>=': $conditionMet = $reading->value >= $rule->threshold; break;
            }

            if ($conditionMet) {
                $actuator = Actuator::find($rule->actuator_id);
                if ($actuator && !$actuator->is_manual_only && $actuator->status != $rule->action_value) {
                    $actuator->status = $rule->action_value;
                    $actuator->save();

                    ActuatorLog::create([
                        'actuator_id' => $actuator->id,
                        'command' => $rule->action_value,
                        'triggered_by' => 'rule',
                        'rule_id' => $rule->id,
                    ]);
                }
            }
        }
    }
}