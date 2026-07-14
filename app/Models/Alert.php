<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = [
        'sensor_id',
        'value',
        'threshold',
        'type',
        'is_read'
    ];

    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }
}