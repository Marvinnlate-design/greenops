<?php

namespace App\Services;

use App\Models\Alert;
use App\Models\Sensor;
use App\Models\SensorReading;

class AlertService
{
    public function checkReading(SensorReading $reading)
    {
        $sensor = $reading->sensor;

        if (!$sensor) {
            return;
        }

        $message = null;
        $level = 'warning';

        // Vérification seuil minimum
        if (
            $sensor->min_threshold !== null &&
            $reading->value < $sensor->min_threshold
        ) {

            $message = $sensor->name .
                " est en dessous du seuil minimum (" .
                $reading->value . " " . $sensor->unit . ")";

        }

        // Vérification seuil maximum
        if (
            $sensor->max_threshold !== null &&
            $reading->value > $sensor->max_threshold
        ) {

            $message = $sensor->name .
                " dépasse le seuil maximum (" .
                $reading->value . " " . $sensor->unit . ")";

            $level = 'critical';
        }

        if ($message) {

            // Évite les doublons
            $alreadyExists = Alert::where('sensor_id', $sensor->id)
                ->where('is_read', false)
                ->exists();

            if (!$alreadyExists) {

                Alert::create([
                    'sensor_id' => $sensor->id,
                    'value' => $reading->value,
                    'message' => $message,
                    'level' => $level,
                ]);

            }

        }

    }
}