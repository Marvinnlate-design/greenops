<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Sensor;
use App\Models\Actuator;
use App\Models\SensorReading;
use App\Models\ActuatorLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Récupérer tous les capteurs actifs avec leur dernière lecture
        $sensors = Sensor::where('is_active', true)->with(['readings' => function($query) {
            $query->latest('reading_time')->limit(1);
        }])->get();

        // 2. Dernières lectures pour chaque capteur (formatées)
        $latestReadings = [];
        foreach ($sensors as $sensor) {
            $lastReading = $sensor->readings->first();
            $latestReadings[$sensor->id] = [
                'sensor' => $sensor,
                'value' => $lastReading ? $lastReading->value : null,
                'time' => $lastReading ? $lastReading->reading_time : null,
                'unit' => $sensor->unit,
                'icon' => $this->getSensorIcon($sensor->type),
                'color' => $this->getSensorColor($sensor->type),
                'threshold_ok' => $this->checkThreshold($sensor, $lastReading ? $lastReading->value : null),
            ];
        }

        // 3. Données pour les graphiques (dernières 50 lectures pour chaque capteur)
        $chartData = $this->prepareChartData();

        // 4. Derniers logs des actionneurs
        $recentLogs = ActuatorLog::with('actuator')->latest()->take(10)->get();

        // 5. Actionneurs pour le contrôle (on les passe pour les boutons sur le dashboard si besoin)
        $actuators = Actuator::all();

        // 6. Statistiques globales
    $alertCount = 0;
foreach ($sensors as $sensor) {
    $lastReading = $sensor->readings->first();
    if ($lastReading && !$this->checkThreshold($sensor, $lastReading->value)) {
        $alertCount++;
    }
}

$stats = [
    'total_sensors' => $sensors->count(),
    'active_actuators' => Actuator::where('status', true)->count(),
    'total_readings' => SensorReading::count(),
    'alerts' => $alertCount,  // ✅ Nouveau calcul
];

        return view('dashboard', compact(
            'alerts',
            'latestReadings',
            'chartData',
            'recentLogs',
            'actuators',
            'stats'
        ));
    }

    private function getSensorIcon(string $type): string
    {
        return match($type) {
            'soil_moisture' => '💧',
            'temperature' => '🌡️',
            'humidity' => '💨',
            'water_level' => '🌊',
            'ph' => '🧪',
            'solar_power' => '☀️',
            'battery_soc' => '🔋',
            'biogas_flow' => '🔥',
            default => '📊',
        };
    }

    private function getSensorColor(string $type): string
    {
        return match($type) {
            'soil_moisture' => 'blue',
            'temperature' => 'red',
            'humidity' => 'cyan',
            'water_level' => 'indigo',
            'ph' => 'purple',
            'solar_power' => 'yellow',
            'battery_soc' => 'green',
            'biogas_flow' => 'orange',
            default => 'gray',
        };
    }

    private function checkThreshold($sensor, $value): bool
    {
        if ($value === null) return true;
        if ($sensor->min_threshold !== null && $value < $sensor->min_threshold) return false;
        if ($sensor->max_threshold !== null && $value > $sensor->max_threshold) return false;
        return true;
    }

    private function prepareChartData(): array
    {
        $sensors = Sensor::where('is_active', true)->get();
        $data = [];

        foreach ($sensors as $sensor) {
            $readings = SensorReading::where('sensor_id', $sensor->id)
                ->latest('reading_time')
                ->limit(50)
                ->orderBy('reading_time', 'asc')
                ->get();

            if ($readings->isNotEmpty()) {
                $data[$sensor->id] = [
                    'name' => $sensor->name,
                    'unit' => $sensor->unit,
                    'color' => $this->getSensorColor($sensor->type),
                    'labels' => $readings->pluck('reading_time')->map(fn($d) => $d->format('H:i'))->toArray(),
                    'values' => $readings->pluck('value')->toArray(),
                ];
            }
        }

        return $data;
    }
}