<?php

namespace App\Listeners;

use App\Events\SensorReadingRecorded;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendThresholdAlert
{
    public function handle(SensorReadingRecorded $event)
    {
        $reading = $event->sensorReading;
        $sensor = $reading->sensor; // ← récupération correcte du capteur

        // Si aucun seuil défini, on ne fait rien
        if (is_null($sensor->min_threshold) && is_null($sensor->max_threshold)) {
            return;
        }

        $value = $reading->value;
        $alertTriggered = false;
        $message = '';

        if (!is_null($sensor->min_threshold) && $value < $sensor->min_threshold) {
            $alertTriggered = true;
            $message = "Valeur sous le seuil minimum : {$value} {$sensor->unit} (seuil min: {$sensor->min_threshold})";
        } elseif (!is_null($sensor->max_threshold) && $value > $sensor->max_threshold) {
            $alertTriggered = true;
            $message = "Valeur au-dessus du seuil maximum : {$value} {$sensor->unit} (seuil max: {$sensor->max_threshold})";
        }

        if ($alertTriggered) {
            // Envoyer un email aux admins et chefs
            $users = User::whereIn('role', ['admin', 'chef'])->get();

            foreach ($users as $user) {
                Mail::raw("Alerte capteur: {$sensor->name}\n{$message}\nHeure : {$reading->reading_time}", function ($mail) use ($user, $sensor) {
                    $mail->to($user->email)
                         ->subject("[GreenOps] Alerte capteur - {$sensor->name}");
                });
            }

            // Journalisation
            Log::channel('daily')->warning($message, [
                'sensor_id' => $sensor->id,
                'value' => $value,
                'reading_time' => $reading->reading_time
            ]);
        }
    }
}