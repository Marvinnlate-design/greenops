<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('actuators', function (Blueprint $table) {
            $table->id();
            $table->string('name');                   // ex: "Pompe à eau", "Vanne Serre 1"
            $table->enum('type', ['pump', 'valve', 'relay']);
            $table->integer('gpio_pin')->nullable();   // pin sur l'ESP32
            $table->boolean('is_manual_only')->default(false); // false = peut être auto via règle
            $table->boolean('status')->default(false); // 0=off, 1=on
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('actuators');
    }
};