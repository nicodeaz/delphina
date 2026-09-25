<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

/**
 * The studio's starting menu. Idempotent (matched by name), so it never
 * overwrites prices or descriptions Delfi has changed from the admin.
 */
class ServiceCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['Trial Consultation', 'A short chat about your style, nail health and the best service plan for you.', 0, 30],

            ['BIAB - Clear or Nude Base', 'Builder in a bottle for strong, natural-looking nails in a clear or nude finish.', 30, 60],
            ['BIAB - With Colour', 'Strengthening BIAB finished with the colour of your choice.', 35, 60],
            ['BIAB - With Nail Art', 'BIAB with a custom nail art design.', 40, 75],

            ['Gel Nails - Plain Colour', 'Classic gel nails in the colour of your choice.', 50, 120],
            ['Gel Nails - French Tip / Ombre', 'Gel nails with an elegant French tip or soft ombre finish.', 55, 120],
            ['Gel Nails - Infills / Refills', 'Maintenance for your existing gel nails.', 45, 90],

            ['Soft Gel Extensions - Plain Colour', 'Lightweight soft gel extensions in a solid colour.', 40, 90],
            ['Soft Gel Extensions - French Tip / Ombre', 'Soft gel extensions with a French tip or ombre design.', 45, 90],
            ['Soft Gel Extensions - Infills / Refills', 'Maintenance for your soft gel extensions.', 35, 75],
            ['Soft Gel Overlay', 'Soft gel overlay on your natural nails.', 45, 90],

            ['Gel Polish - On Natural Nails', 'Long-lasting gel polish on your natural nails.', 25, 45],
            ['Gel Polish - Removal & Reapplication', 'Gentle removal of old gel polish and a fresh new application.', 30, 60],
            ['Gel Polish - Removal Only', 'Safe removal of gel polish.', 12, 30],
        ];

        foreach ($services as [$name, $description, $price, $duration]) {
            Service::firstOrCreate(
                ['name' => $name],
                ['description' => $description, 'price' => $price, 'duration' => $duration]
            );
        }
    }
}
