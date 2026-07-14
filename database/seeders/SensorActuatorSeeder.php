<?php

namespace Database\Seeders;

use App\Models\Sensor;
use App\Models\Actuator;
use App\Models\AutomationRule;
use Illuminate\Database\Seeder;

class SensorActuatorSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Capteurs (types autorisés : soil_moisture, water_level, temperature, humidity, ph)
        $sensors = [
            [
                'name' => 'Humidité sol - Serre 1',
                'type' => 'soil_moisture',
                'unit' => '%',
                'location' => 'Serre 1 - Zone A',
                'min_threshold' => 40,
                'max_threshold' => 80,
                'is_active' => true,
            ],
            [
                'name' => 'Humidité sol - Serre 2',
                'type' => 'soil_moisture',
                'unit' => '%',
                'location' => 'Serre 2 - Zone B',
                'min_threshold' => 35,
                'max_threshold' => 75,
                'is_active' => true,
            ],
            [
                'name' => 'Température - Serre 1',
                'type' => 'temperature',
                'unit' => '°C',
                'location' => 'Serre 1 - Intérieur',
                'min_threshold' => 18,
                'max_threshold' => 35,
                'is_active' => true,
            ],
            [
                'name' => 'Température - Serre 2',
                'type' => 'temperature',
                'unit' => '°C',
                'location' => 'Serre 2 - Intérieur',
                'min_threshold' => 20,
                'max_threshold' => 32,
                'is_active' => true,
            ],
            [
                'name' => 'Humidité air - Serre 1',
                'type' => 'humidity',
                'unit' => '%',
                'location' => 'Serre 1 - Intérieur',
                'min_threshold' => 50,
                'max_threshold' => 85,
                'is_active' => true,
            ],
            [
                'name' => 'Niveau cuve eau',
                'type' => 'water_level',
                'unit' => 'cm',
                'location' => 'Cuve de stockage',
                'min_threshold' => 30,
                'max_threshold' => 150,
                'is_active' => true,
            ],
            [
                'name' => 'pH eau irrigation',
                'type' => 'ph',
                'unit' => 'pH',
                'location' => 'Station de phytoépuration',
                'min_threshold' => 5.5,
                'max_threshold' => 7.5,
                'is_active' => true,
            ],
            // Les capteurs suivants seront ajoutés après avoir modifié la table pour autoriser ces types
            /*
            [
                'name' => 'Production solaire',
                'type' => 'solar_power', // pas encore dans l'ENUM
                ...
            ],
            */
        ];

        foreach ($sensors as $sensor) {
            Sensor::create($sensor);
        }

        // 2. Actionneurs
        $actuators = [
            [
                'name' => 'Pompe irrigation - Serre 1',
                'type' => 'pump',
                'gpio_pin' => 1,
                'is_manual_only' => false,
                'status' => false,
            ],
            [
                'name' => 'Pompe irrigation - Serre 2',
                'type' => 'pump',
                'gpio_pin' => 2,
                'is_manual_only' => false,
                'status' => false,
            ],
            [
                'name' => 'Électrovanne - Serre 1 Zone A',
                'type' => 'valve',
                'gpio_pin' => 3,
                'is_manual_only' => false,
                'status' => false,
            ],
            [
                'name' => 'Électrovanne - Serre 2 Zone B',
                'type' => 'valve',
                'gpio_pin' => 4,
                'is_manual_only' => false,
                'status' => false,
            ],
            [
                'name' => 'Ventilation - Serre 1',
                'type' => 'relay',
                'gpio_pin' => 5,
                'is_manual_only' => false,
                'status' => false,
            ],
            [
                'name' => 'Ventilation - Serre 2',
                'type' => 'relay',
                'gpio_pin' => 6,
                'is_manual_only' => false,
                'status' => false,
            ],
        ];

        foreach ($actuators as $actuator) {
            Actuator::create($actuator);
        }

        // 3. Règles d'automatisation
        $rules = [
            [
                'name' => 'Arrosage Serre 1 - Humidité basse',
                'sensor_id' => 1,
                'operator' => '<',
                'threshold' => 45,
                'actuator_id' => 1,
                'action_value' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Arrêt arrosage Serre 1 - Humidité haute',
                'sensor_id' => 1,
                'operator' => '>',
                'threshold' => 70,
                'actuator_id' => 1,
                'action_value' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Ventilation Serre 1 - Température haute',
                'sensor_id' => 3,
                'operator' => '>',
                'threshold' => 32,
                'actuator_id' => 5,
                'action_value' => true,
                'is_active' => true,
            ],
        ];

        foreach ($rules as $rule) {
            AutomationRule::create($rule);
        }
    }
}