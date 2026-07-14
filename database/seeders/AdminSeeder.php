<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Service;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // On vérifie si l'utilisateur existe déjà pour éviter les doublons
        $existingUser = User::where('email', 'marvinnlate@gmail.com')->first();
        
        if (!$existingUser) {
            // S'assurer que le service "Informatique" existe
            $service = Service::firstOrCreate(
                ['name' => 'Informatique'],
                ['description' => 'Service technique et support']
            );

            // Créer l'utilisateur Admin
            User::create([
                'name' => 'Marvin NLATE',
                'email' => 'marvinnlate@gmail.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'service_id' => $service->id,
                'role' => 'admin',
            ]);
            
            $this->command->info('Admin user created successfully.');
        } else {
            $this->command->info('Admin user already exists.');
        }
    }
}