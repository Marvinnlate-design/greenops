<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('automation_rules', function (Blueprint $table) {

            $table->id();

            $table->foreignId('sensor_id')->constrained()->cascadeOnDelete();

            $table->foreignId('actuator_id')->constrained()->cascadeOnDelete();

            $table->enum('operator', [
                '<',
                '>',
                '<=',
                '>='
            ]);

            $table->double('threshold');

            $table->boolean('action_value');

            $table->boolean('is_active')->default(true);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('automation_rules');
    }
};