<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    \App\Models\Service::create(['name' => 'Informatique']);
    \App\Models\Service::create(['name' => 'Technique']);
    \App\Models\Service::create(['name' => 'Agricol']);
    \App\Models\Service::create(['name' => 'Juridique']);
    \App\Models\Service::create(['name' => 'Commercial']);
    \App\Models\Service::create(['name' => 'Direction']);
}
}
