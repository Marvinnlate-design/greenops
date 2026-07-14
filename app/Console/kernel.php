<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Simuler 1 lecture par capteur toutes les 30 minutes
        // La commande sensors:simulate doit exister
        $schedule->command('sensors:simulate --count=1')
                 ->everyThirtyMinutes()
                 ->withoutOverlapping()  // Empêche les exécutions simultanées
                 ->runInBackground();    // Exécute en arrière-plan
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}