<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\AvailableDate;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAgendaTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_see_availability_and_bookings_in_the_agenda(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $service = Service::create([
            'name' => 'Gel manicure',
            'description' => 'A test manicure service.',
            'price' => 45,
            'duration' => 60,
        ]);
        $date = now()->addMonth()->startOfMonth()->addDays(3);

        AvailableDate::create([
            'date' => $date->toDateString(),
            'start_time' => '09:00',
            'end_time' => '17:00',
            'is_active' => true,
        ]);

        Appointment::create([
            'service_id' => $service->id,
            'date' => $date->toDateString(),
            'time' => '10:00',
            'status' => 'pending',
            'name' => 'Delfi Client',
            'email' => 'client@example.com',
            'phone' => '+353899409670',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.agenda', [
            'month' => $date->format('Y-m'),
        ]));

        $response->assertOk();
        $response->assertSee('Studio agenda');
        $response->assertSee('Delfi Client');
        $response->assertSee('09:00-17:00');
        $response->assertSee('Deposit pending');
    }
}
