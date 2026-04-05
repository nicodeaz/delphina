<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Client User',
            'email' => 'client@example.com',
            'role' => 'client',
        ]);

        \App\Models\Service::create([
            'name' => 'Manicura Básica',
            'description' => 'Manicura completa con esmaltado.',
            'price' => 25.00,
            'duration' => 60,
        ]);

        \App\Models\Service::create([
            'name' => 'Pedicura Premium',
            'description' => 'Pedicura con masaje y esmaltado semipermanente.',
            'price' => 35.00,
            'duration' => 90,
        ]);

        \App\Models\Service::create([
            'name' => 'Nail Art Completo',
            'description' => 'Diseño personalizado de uñas.',
            'price' => 50.00,
            'duration' => 120,
        ]);
    }
}
