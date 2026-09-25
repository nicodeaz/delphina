<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Safe to run in production: only seeds the service menu.
     * The admin account is created with `php artisan studio:admin`.
     */
    public function run(): void
    {
        $this->call(ServiceCatalogSeeder::class);
    }
}
