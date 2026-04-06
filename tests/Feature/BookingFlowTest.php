<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\AvailableDate;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    protected $service;
    protected $availableDate;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test service
        $this->service = Service::create([
            'name' => 'Test Gel Manicure',
            'description' => 'Professional gel manicure',
            'price' => 45.00,
            'duration' => 60,
        ]);

        // Create available dates (test dates) - manually, not via seeder
        // Create next 30 days of availability (Mon-Fri 09-18, Sat 10-16, no Sundays)
        $today = \Carbon\Carbon::now();
        
        for ($i = 1; $i <= 30; $i++) {
            $date = $today->copy()->addDays($i);
            $dayOfWeek = $date->dayOfWeek; // 0 = Sun, 1 = Mon, etc.
            
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
            ]);
        }

        $this->user = User::factory()->create();
    }

    /** @test */
    public function user_can_book_appointment_with_valid_data()
    {
        // Get the first available date from the set  we created in setUp
        $availableDate = AvailableDate::where('is_active', true)->orderBy('date')->first();
        $this->assertNotNull($availableDate, "No available dates were created in setUp");

        $response = $this->post(route('bookings.store'), [
            'service_id' => $this->service->id,
            'date' => $availableDate->date->toDateString(),
            'time' => '10:00',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+34600000000',
        ]);

        // Should redirect to payment page
        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        
        // Should create appointment
        $this->assertDatabaseHas('appointments', [
            'service_id' => $this->service->id,
            'date' => $availableDate->date->toDateString(),
            'time' => '10:00',
            'status' => 'confirmed', // Auto-confirmed
        ]);

        // Should create payment with €15 deposit
        $appointment = Appointment::latest()->first();
        $this->assertDatabaseHas('payments', [
            'appointment_id' => $appointment->id,
            'amount' => 15.00,
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function user_cannot_book_time_slot_already_booked()
    {
        $tomorrow = now()->addDay();

        // Book first appointment
        Appointment::create([
            'service_id' => $this->service->id,
            'date' => $tomorrow->toDateString(),
            'time' => '10:00',
            'status' => 'confirmed',
            'name' => 'First User',
            'email' => 'first@example.com',
            'phone' => '+34600000001',
        ]);

        // Try to book same time slot
        $response = $this->post(route('bookings.store'), [
            'service_id' => $this->service->id,
            'date' => $tomorrow->toDateString(),
            'time' => '10:00',
            'name' => 'Second User',
            'email' => 'second@example.com',
            'phone' => '+34600000002',
        ]);

        $response->assertSessionHasErrors('time');
    }

    /** @test */
    public function user_cannot_book_time_outside_available_hours()
    {
        $tomorrow = now()->addDay();

        // Try to book outside available hours (available: 09:00-18:00)
        $response = $this->post(route('bookings.store'), [
            'service_id' => $this->service->id,
            'date' => $tomorrow->toDateString(),
            'time' => '08:00', // Before available hours
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+34600000000',
        ]);

        $response->assertSessionHasErrors('time');
    }

    /** @test */
    public function user_cannot_book_on_closed_date()
    {
        // Tomorrow is Sunday (closed day - no available dates)
        $dayAfterTomorrow = now()->addDays(3);

        $response = $this->post(route('bookings.store'), [
            'service_id' => $this->service->id,
            'date' => $dayAfterTomorrow->toDateString(),
            'time' => '10:00',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+34600000000',
        ]);

        $response->assertSessionHasErrors('time');
    }

    /** @test */
    public function api_returns_available_slots()
    {
        $tomorrow = now()->addDay();

        $response = $this->getJson('/api/appointments/available?service_id=' . $this->service->id . '&date=' . $tomorrow->toDateString());

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'available_slots',
            'date',
            'service_id',
        ]);

        // Should have multiple slots throughout the day
        $this->assertCount(17, $response['available_slots']); // 09:00-18:00, every 30 mins = 17 slots
    }

    /** @test */
    public function api_excludes_booked_slots_from_available_slots()
    {
        $tomorrow = now()->addDay();

        // Book a slot
        Appointment::create([
            'service_id' => $this->service->id,
            'date' => $tomorrow->toDateString(),
            'time' => '10:00',
            'status' => 'confirmed',
            'name' => 'User',
            'email' => 'user@example.com',
            'phone' => '+34600000000',
        ]);

        $response = $this->getJson('/api/appointments/available?service_id=' . $this->service->id . '&date=' . $tomorrow->toDateString());

        $response->assertStatus(200);

        // 10:00 should NOT be in available slots
        $this->assertNotContains('10:00', $response['available_slots']);

        // Other times should be available
        $this->assertContains('09:00', $response['available_slots']);
        $this->assertContains('11:00', $response['available_slots']);
    }

    /** @test */
    public function api_returns_next_available_dates()
    {
        $response = $this->getJson('/api/appointments/next-available-dates');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'available_dates',
            'count',
        ]);

        // Should have at least one available date
        $this->assertGreaterThan(0, $response['count']);
    }

    /** @test */
    public function appointment_requires_valid_date_format()
    {
        $response = $this->post(route('bookings.store'), [
            'service_id' => $this->service->id,
            'date' => 'invalid-date',
            'time' => '10:00',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+34600000000',
        ]);

        $response->assertSessionHasErrors('date');
    }

    /** @test */
    public function appointment_requires_valid_time_format()
    {
        $tomorrow = now()->addDay();

        $response = $this->post(route('bookings.store'), [
            'service_id' => $this->service->id,
            'date' => $tomorrow->toDateString(),
            'time' => 'invalid-time',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+34600000000',
        ]);

        $response->assertSessionHasErrors('time');
    }

    /** @test */
    public function appointment_cannot_be_in_past()
    {
        $yesterday = now()->subDay();

        $response = $this->post(route('bookings.store'), [
            'service_id' => $this->service->id,
            'date' => $yesterday->toDateString(),
            'time' => '10:00',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+34600000000',
        ]);

        $response->assertSessionHasErrors('date');
    }
}
