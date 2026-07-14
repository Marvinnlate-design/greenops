<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->string('name');                   // ex: "Humidité Sol Serre 1"
            $table->enum('type', ['soil_moisture', 'water_level', 'temperature', 'humidity', 'ph'])->default('soil_moisture');
            $table->string('unit', 20)->nullable();   // "%", "cm", "°C"
            $table->string('location')->nullable();   // ex: "Serre 1 - zone sud"
            $table->decimal('min_threshold', 8, 2)->nullable();
            $table->decimal('max_threshold', 8, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sensors');
    }
};