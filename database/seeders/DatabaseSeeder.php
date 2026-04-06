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
            'name' => 'Admin',
            'email' => 'admin@nails.com',
            'password' => bcrypt('password'),
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

        // Gel Nails services
        \App\Models\Service::create([
            'name' => 'Gel Nails - Plain Colour',
            'description' => 'Solid color gel application',
            'price' => 50.00,
            'duration' => 120,
        ]);

        \App\Models\Service::create([
            'name' => 'Gel Nails - French Tip / Ombre',
            'description' => 'Elegant gradient designs',
            'price' => 55.00,
            'duration' => 120,
        ]);

        \App\Models\Service::create([
            'name' => 'Gel Nails - Infills / Refills',
            'description' => 'Maintenance service',
            'price' => 45.00,
            'duration' => 90,
        ]);

        // BIAB services
        \App\Models\Service::create([
            'name' => 'BIAB - Clear or Nude Base',
            'description' => 'Natural look extensions',
            'price' => 30.00,
            'duration' => 60,
        ]);

        \App\Models\Service::create([
            'name' => 'BIAB - With Colour',
            'description' => 'Colored gel extensions',
            'price' => 35.00,
            'duration' => 60,
        ]);

        \App\Models\Service::create([
            'name' => 'BIAB - With Nail Art',
            'description' => 'Extensions with custom designs',
            'price' => 40.00,
            'duration' => 75,
        ]);

        // Soft Gel Extensions
        \App\Models\Service::create([
            'name' => 'Soft Gel Extensions - Plain Colour',
            'description' => 'Solid color extensions',
            'price' => 40.00,
            'duration' => 90,
        ]);

        \App\Models\Service::create([
            'name' => 'Soft Gel Extensions - French Tip / Ombre',
            'description' => 'Gradient designs',
            'price' => 45.00,
            'duration' => 90,
        ]);

        \App\Models\Service::create([
            'name' => 'Soft Gel Extensions - Infills / Refills',
            'description' => 'Maintenance service',
            'price' => 35.00,
            'duration' => 75,
        ]);

        // Gel Polish
        \App\Models\Service::create([
            'name' => 'Gel Polish - On Natural Nails',
            'description' => 'Gel polish application',
            'price' => 25.00,
            'duration' => 45,
        ]);

        \App\Models\Service::create([
            'name' => 'Gel Polish - Removal & Reapplication',
            'description' => 'Complete service',
            'price' => 30.00,
            'duration' => 60,
        ]);

        \App\Models\Service::create([
            'name' => 'Gel Polish - Removal Only',
            'description' => 'Gel polish removal',
            'price' => 12.00,
            'duration' => 30,
        ]);
    }
}
