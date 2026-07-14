<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\AutomationRuleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ActuatorController;
use App\Http\Controllers\Api\SensorDataController;

Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Annonces
    |--------------------------------------------------------------------------
    */
    Route::middleware(['track.announcements'])->group(function () {

        Route::get('/announcements', [AnnouncementController::class, 'index'])
            ->name('announcements.index');

        Route::get('/announcements/create', [AnnouncementController::class, 'create'])
            ->name('announcements.create');

        Route::post('/announcements', [AnnouncementController::class, 'store'])
            ->name('announcements.store');


        Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])
            ->name('announcements.show');

        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])
            ->name('announcements.destroy');

    });

    /*
|--------------------------------------------------------------------------
| ALERTES
|--------------------------------------------------------------------------
*/

Route::get('/alerts', [AlertController::class, 'index'])
    ->name('alerts.index');

Route::patch('/alerts/{alert}/read', [AlertController::class, 'markAsRead'])
    ->name('alerts.read');    

Route::post('/alerts/read-all', [AlertController::class, 'readAll'])
    ->name('alerts.readAll');


/*
|--------------------------------------------------------------------------
| AUTOMATISATIONS
|--------------------------------------------------------------------------
*/

Route::resource('automation-rules', AutomationRuleController::class);
    /*
    |--------------------------------------------------------------------------
    | Profil
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Équipements
    |--------------------------------------------------------------------------
    */
    Route::get('/actuators', [ActuatorController::class, 'index'])
        ->name('actuators.index');

    Route::post('/actuator/{id}/toggle', [ActuatorController::class, 'toggle'])
        ->name('actuator.toggle');

    /*
    |--------------------------------------------------------------------------
    | Administration
    |--------------------------------------------------------------------------
    */
    Route::middleware(['admin'])->group(function () {

        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');

    });

});

/*
|--------------------------------------------------------------------------
| API ESP32
|--------------------------------------------------------------------------
*/

Route::post('/api/sensor-data', [SensorDataController::class, 'receive'])
    ->name('api.sensor.data');