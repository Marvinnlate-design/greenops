<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sensor_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sensor_id')->constrained()->onDelete('cascade');
            $table->decimal('value', 10, 2);
            $table->timestamp('reading_time');
            $table->timestamps();

            // Index pour les recherches temporelles
            $table->index(['sensor_id', 'reading_time']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sensor_readings');
    }
};