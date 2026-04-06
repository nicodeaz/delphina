<?php

namespace Database\Seeders;

use App\Models\AvailableDate;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AvailableDatesSeeder extends Seeder
{
    public function run(): void
    {
        // Create available dates for the next 30 days
        // Monday to Friday: 9:00 - 18:00
        // Saturday: 10:00 - 16:00
        // Sunday: Closed

        $startDate = now()->addDays(1);
        
        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dayOfWeek = $date->dayOfWeek; // 0 = Sunday, 1 = Monday, etc.

            // Skip Sundays (0)
            if ($dayOfWeek == 0) {
                continue;
            }

            $startTime = '09:00';
            $endTime = '18:00';

            // Saturday: different hours
            if ($dayOfWeek == 6) {
                $startTime = '10:00';
                $endTime = '16:00';
            }

            AvailableDate::create([
                'date' => $date->toDateString(),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'is_active' => true,
                'notes' => 'Scheduled availability',
            ]);
        }
    }
}
