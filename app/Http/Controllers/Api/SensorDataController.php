<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use App\Models\Actuator;
use App\Models\SensorReading;
use App\Models\AutomationRule;
use App\Models\ActuatorLog;
use App\Models\Alert;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SensorDataController extends Controller
{
    public function receive(Request $request)
    {
        $validated = $request->validate([
            'sensors' => 'required|array',
            'sensors.*.sensor_id' => 'required|exists:sensors,id',
            'sensors.*.value' => 'required|numeric',
        ]);

        $commands = [];

        foreach ($validated['sensors'] as $reading) {
            $sensor = Sensor::find($reading['sensor_id']);
            if ($sensor && $sensor->is_active) {
                // 1. Enregistrer la lecture
                $sensorReading = SensorReading::create([
                    'sensor_id' => $sensor->id,
                    'value' => $reading['value'],
                    'reading_time' => Carbon::now(),
                ]);

                // 2. Vérifier les seuils et créer une alerte si besoin
                $this->checkThresholdsAndCreateAlert($sensor, $sensorReading);

                // 3. Évaluer les règles d'automatisation
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
                            $commands[$actuator->id] = (bool)$actuator->status;
                        }
                    }
                }
            }
        }

        $allActuators = Actuator::all();
        foreach ($allActuators as $actuator) {
            $commands[$actuator->id] = (bool)$actuator->status;
        }

        return response()->json([
            'status' => 'ok',
            'commands' => $commands
        ]);
    }

    /**
     * Vérifie les seuils du capteur et crée une alerte si nécessaire
     */
    private function checkThresholdsAndCreateAlert($sensor, $sensorReading)
    {
        $value = $sensorReading->value;
        $alertMessage = null;

        // Vérifier si une alerte existe déjà pour ce capteur (non lue)
        $existingAlert = Alert::where('sensor_id', $sensor->id)
                              ->where('is_read', false)
                              ->first();

        // Vérifier les seuils
        if ($sensor->min_threshold !== null && $value < $sensor->min_threshold) {
            $alertMessage = "Valeur sous le seuil minimum : {$value} {$sensor->unit} (seuil min: {$sensor->min_threshold})";
        } elseif ($sensor->max_threshold !== null && $value > $sensor->max_threshold) {
            $alertMessage = "Valeur au-dessus du seuil maximum : {$value} {$sensor->unit} (seuil max: {$sensor->max_threshold})";
        }

        // Créer une alerte uniquement si :
        // 1. Il y a un message d'alerte
        // 2. ET il n'y a pas déjà une alerte non lue pour ce capteur
        if ($alertMessage && !$existingAlert) {
            Alert::create([
                'sensor_id' => $sensor->id,
                'value' => $value,
                'message' => $alertMessage,
                'is_read' => false,
                'created_at' => Carbon::now(),
            ]);

            Log::info('Alerte créée pour le capteur ' . $sensor->name . ': ' . $alertMessage);
        } elseif ($alertMessage && $existingAlert) {
            // Mettre à jour la valeur de l'alerte existante (optionnel)
            $existingAlert->update([
                'value' => $value,
                'updated_at' => Carbon::now(),
            ]);
            Log::info('Alerte mise à jour pour le capteur ' . $sensor->name);
        }
    }

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