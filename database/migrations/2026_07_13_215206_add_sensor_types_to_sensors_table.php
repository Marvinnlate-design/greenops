<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE sensors MODIFY type ENUM('soil_moisture', 'water_level', 'temperature', 'humidity', 'ph', 'solar_power', 'battery_soc', 'biogas_flow') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE sensors MODIFY type ENUM('soil_moisture', 'water_level', 'temperature', 'humidity', 'ph') NOT NULL");
    }
};