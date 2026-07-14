<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorDataController;

Route::post('/sensor-data', [SensorDataController::class, 'receive'])->name('api.sensor.data');
