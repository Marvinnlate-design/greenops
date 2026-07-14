<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = [
        'sensor_id',
        'value',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Capteur ayant généré l'alerte
     */
    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }
}