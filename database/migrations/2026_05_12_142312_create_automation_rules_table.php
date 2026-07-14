<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('automation_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('sensor_id')->constrained()->onDelete('cascade');
            $table->enum('operator', ['<', '>', '<=', '>=']);
            $table->decimal('threshold', 10, 2);
            $table->foreignId('actuator_id')->constrained()->onDelete('cascade');
            $table->boolean('action_value'); // 0 = éteindre, 1 = allumer
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('automation_rules');
    }
};