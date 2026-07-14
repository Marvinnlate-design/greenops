<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usersData = [
            // ADMIN
            ['Marvin NLATE', 'marvinnlate@gmail.com', 'admin', 'informatique'],
            
            // DIRECTION
            ['BANDOMA LEVIKA INDONGO Genisia', 'levikagenisia@gmail.com', 'directrice', 'direction'],
            
            // TECHNIQUE
            ['MBA YOUMA Van Lawson', 'mbayoumavanlawson@gmail.com', 'chef de service', 'technique'],
            ['MOMBO MABIKA Yann Sidney', 'yannsidneymombomabika4@gmail.com', 'agent', 'technique'],
            ['MOS MBOUMBE Amos', 'mosmboumbeyamos@gmail.com', 'agent', 'technique'],
            
            // AGRICOLE
            ['NGUELE NGUELE Amour Lionel', 'amournguele7@gmail.com', 'chef de service', 'agricole'],
            ['AMBOUROUET AGOMA HERVANE Gervie', 'hervaneambourouet@gmail.com', 'agent', 'agricole'],
            ['ASSA LANGA Jemima Keren', 'bensonemonica@gmail.com', 'agent', 'agricole'],
            ['SOMBE NAHLE Nadia', 'sombenahletnadia@gmail.com', 'agent', 'agricole'],
            
            // COMMERCIAL
            ['BISSAGOU BOUASSA Ophelia Tessie', 'opheliabissagou@gmail.com', 'agent', 'commercial'],
            ['YALA MOUSSODA Chancia', 'yalachancia5@gmail.com', 'chef de service', 'commercial'],
            
            // JURIDIQUE
            ['ESSONO OWONO MBOM Emmanuel Junior', 'essono_emmanuel@icloud.com', 'chef de service', 'juridique'],
            
            // INFORMATIQUE (AUTRE)
            ['ESSOO Eliya Roby', 'eliyaessone05@gmail.com', 'chef de service', 'informatique'],
        ];

        foreach ($usersData as $u) {
            // On s'assure que le service existe
            $service = Service::firstOrCreate(
                ['name' => strtolower($u[3])],
                
            );

            // On crée l'utilisateur s'il n'existe pas déjà (évite les erreurs de doublons)
            User::firstOrCreate(
                ['email' => $u[1]],
                [
                    'name' => $u[0],
                    'role' => $u[2],
                    'service_id' => $service->id,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}