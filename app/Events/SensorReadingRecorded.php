<?php

namespace App\Events;

use App\Models\SensorReading;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SensorReadingRecorded
{
    use Dispatchable, SerializesModels;

    public $sensorReading;

    public function __construct(SensorReading $sensorReading)
    {
        $this->sensorReading = $sensorReading;
    }
}