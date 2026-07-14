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

class SensorDataController extends Controller
{
    /**
     * Réception des données provenant de l'ESP32
     */
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

        if (!$sensor || !$sensor->is_active) {
            continue;
        }

        // Enregistrer la mesure
        SensorReading::create([
            'sensor_id'    => $sensor->id,
            'value'        => $reading['value'],
            'reading_time' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Création automatique des alertes
        |--------------------------------------------------------------------------
        */

        $message = null;

        if (
            $sensor->min_threshold !== null &&
            $reading['value'] < $sensor->min_threshold
        ) {
            $message = "Valeur inférieure au seuil minimum ({$sensor->min_threshold}{$sensor->unit})";
        }

        if (
            $sensor->max_threshold !== null &&
            $reading['value'] > $sensor->max_threshold
        ) {
            $message = "Valeur supérieure au seuil maximum ({$sensor->max_threshold}{$sensor->unit})";
        }

        if ($message) {

            $existingAlert = Alert::where('sensor_id', $sensor->id)
                ->where('is_read', false)
                ->first();

            if (!$existingAlert) {

                Alert::create([
                    'sensor_id' => $sensor->id,
                    'value'     => $reading['value'],
                    'message'   => $message,
                    'is_read'   => false,
                ]);

            }

        }

        /*
        |--------------------------------------------------------------------------
        | Règles d'automatisation
        |--------------------------------------------------------------------------
        */

        $rules = AutomationRule::where('sensor_id', $sensor->id)
            ->where('is_active', true)
            ->get();

        foreach ($rules as $rule) {

            $conditionMet = false;

            switch ($rule->operator) {

                case '<':
                    $conditionMet = $reading['value'] < $rule->threshold;
                    break;

                case '>':
                    $conditionMet = $reading['value'] > $rule->threshold;
                    break;

                case '<=':
                    $conditionMet = $reading['value'] <= $rule->threshold;
                    break;

                case '>=':
                    $conditionMet = $reading['value'] >= $rule->threshold;
                    break;
            }

            if (!$conditionMet) {
                continue;
            }

            $actuator = Actuator::find($rule->actuator_id);

            if (!$actuator || $actuator->is_manual_only) {
                continue;
            }

            if ($actuator->status != $rule->action_value) {

                $actuator->status = $rule->action_value;
                $actuator->save();

                ActuatorLog::create([
                    'actuator_id' => $actuator->id,
                    'command'     => $rule->action_value,
                    'triggered_by'=> 'rule',
                    'rule_id'     => $rule->id,
                ]);

            }

            $commands[$actuator->id] = (bool) $actuator->status;
        }
    }

    foreach (Actuator::all() as $actuator) {

        $commands[$actuator->id] = (bool) $actuator->status;

    }

    return response()->json([
        'status' => 'ok',
        'commands' => $commands,
    ]);
}

    /**
     * Commande manuelle
     */
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