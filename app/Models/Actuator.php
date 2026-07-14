<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actuator extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'type', 'gpio_pin', 'is_manual_only', 'status'
    ];

    protected $casts = [
        'is_manual_only' => 'boolean',
        'status' => 'boolean',
    ];

    public function rules()
    {
        return $this->hasMany(AutomationRule::class);
    }

    public function logs()
    {
        return $this->hasMany(ActuatorLog::class);
    }
    
}