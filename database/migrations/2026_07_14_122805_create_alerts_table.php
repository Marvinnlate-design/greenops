<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alerts', function (Blueprint $table) {

            $table->id();

            $table->foreignId('sensor_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->double('value');

            $table->string('message');

            $table->enum('level', [
                'info',
                'warning',
                'critical'
            ])->default('warning');

            $table->boolean('is_read')->default(false);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};