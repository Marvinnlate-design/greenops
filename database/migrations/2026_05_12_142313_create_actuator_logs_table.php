<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('actuator_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actuator_id')->constrained()->onDelete('cascade');
            $table->boolean('command');               // 0 ou 1
            $table->enum('triggered_by', ['manual', 'auto', 'rule']);
            $table->foreignId('rule_id')->nullable()->constrained('automation_rules')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('actuator_logs');
    }
};