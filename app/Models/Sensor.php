<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'type', 'unit', 'location', 'min_threshold', 'max_threshold', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function readings()
    {
        return $this->hasMany(SensorReading::class);
    }

    public function alerts()
{
    return $this->hasMany(Alert::class);
}

public function rules()
{
    return $this->hasMany(AutomationRule::class);
}
}