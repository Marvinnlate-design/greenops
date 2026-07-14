<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {

            // Une annonce peut concerner tous les services
            $table->foreignId('service_id')
                ->nullable()
                ->change();

            // Priorité
            $table->enum('priority',[
                'normal',
                'important',
                'urgent'
            ])->default('normal');

            // Nombre de vues
            $table->unsignedInteger('views')
                ->default(0);

            // Date d'expiration
            $table->timestamp('expires_at')
                ->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {

            $table->dropColumn([
                'priority',
                'views',
                'expires_at'
            ]);

        });
    }
};