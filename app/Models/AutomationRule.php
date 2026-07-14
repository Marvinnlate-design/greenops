<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutomationRule extends Model
{
    protected $fillable = [

        'sensor_id',

        'actuator_id',

        'operator',

        'threshold',

        'action_value',

        'is_active'

    ];

    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }

    public function actuator()
    {
        return $this->belongsTo(Actuator::class);
    }
}