<?php

namespace App\Http\Controllers\Api;

use App\Services\AlertService;
use App\Http\Controllers\Controller;
use App\Models\Sensor;
use App\Models\Actuator;
use App\Models\SensorReading;
use App\Models\AutomationRule;
use App\Models\ActuatorLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SensorDataController extends Controller
{
    // Réception des données des capteurs (ESP32)
    public function receive(Request $request)
    {
        // Validation (à adapter selon vos clés)
        $validated = $request->validate([
            'sensors' => 'required|array',
            'sensors.*.sensor_id' => 'required|exists:sensors,id',
            'sensors.*.value' => 'required|numeric',
        ]);

        $commands = [];

        // 1. Enregistrement des relevés
        foreach ($validated['sensors'] as $reading) {
            $sensor = Sensor::find($reading['sensor_id']);
            if ($sensor && $sensor->is_active) {
                $sensorReading = SensorReading::create([
    'sensor_id' => $sensor->id,
    'value' => $reading['value'],
    'reading_time' => Carbon::now(),
]);

// Vérifier automatiquement les seuils
app(AlertService::class)->checkReading($sensorReading);

                // 2. Évaluation des règles d'automatisation
                $rules = AutomationRule::where('sensor_id', $sensor->id)
                            ->where('is_active', true)
                            ->get();

                foreach ($rules as $rule) {
                    $conditionMet = false;
                    switch ($rule->operator) {
                        case '<': $conditionMet = $reading['value'] < $rule->threshold; break;
                        case '>': $conditionMet = $reading['value'] > $rule->threshold; break;
                        case '<=': $conditionMet = $reading['value'] <= $rule->threshold; break;
                        case '>=': $conditionMet = $reading['value'] >= $rule->threshold; break;
                    }

                    if ($conditionMet) {
                        $actuator = Actuator::find($rule->actuator_id);
                        if ($actuator && !$actuator->is_manual_only) {
                            // Changer l'état si nécessaire
                            if ($actuator->status != $rule->action_value) {
                                $actuator->status = $rule->action_value;
                                $actuator->save();
                                ActuatorLog::create([
                                    'actuator_id' => $actuator->id,
                                    'command' => $rule->action_value,
                                    'triggered_by' => 'rule',
                                    'rule_id' => $rule->id,
                                ]);
                            }
                            // Stocker la commande à renvoyer à l'ESP32
                            $commands[$actuator->id] = (bool)$actuator->status;
                        }
                    }
                }
            }
        }

        // 3. Formatage des commandes pour l'ESP32 (envoyer l'état de tous les actionneurs)
        $allActuators = Actuator::all();
        foreach ($allActuators as $actuator) {
            $commands[$actuator->id] = (bool)$actuator->status;
        }

        return response()->json([
            'status' => 'ok',
            'commands' => $commands
        ]);
    }

    // Commande manuelle d'un actionneur (depuis le dashboard)
    public function toggleActuator(Request $request, $id)
    {
        $actuator = Actuator::findOrFail($id);
        $actuator->status = !$actuator->status;
        $actuator->save();

        ActuatorLog::create([
            'actuator_id' => $actuator->id,
            'command' => $actuator->status,
            'triggered_by' => 'manual',
            'rule_id' => null,
        ]);

        return response()->json([
            'status' => 'ok',
            'actuator_id' => $actuator->id,
            'state' => $actuator->status
        ]);
    }
}