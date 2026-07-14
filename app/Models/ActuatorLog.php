<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ActuatorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'actuator_id',
        'command',
        'triggered_by',
        'rule_id',
    ];

    protected $casts = [
        'command' => 'boolean',
    ];

    public function actuator()
    {
        return $this->belongsTo(Actuator::class);
    }

    public function rule()
    {
        return $this->belongsTo(AutomationRule::class);
    }
}