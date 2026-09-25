<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\AvailableDate;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    protected Service $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = Service::create([
            'name' => 'Test Gel Manicure',
            'description' => 'Professional gel manicure',
            'price' => 45.00,
            'duration' => 60,
        ]);

        // Create next 30 days of availability (Mon-Fri 09-18, Sat 10-16, no Sundays)
        $today = Carbon::now();

        for ($i = 1; $i <= 30; $i++) {
            $date = $today->copy()->addDays($i);
            $dayOfWeek = $date->dayOfWeek; // 0 = Sun, 1 = Mon, etc.

            if ($dayOfWeek == 0) {
                continue;
            }

            $startTime = '09:00';
            $endTime = '18:00';

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
    }

    private function bookingPayload(array $overrides = []): array
    {
        $availableDate = AvailableDate::where('is_active', true)->orderBy('date')->first();

        return array_merge([
            'services' => json_encode([$this->service->name => ['duration' => $this->service->duration]]),
            'appointment_date' => $availableDate->date->toDateString(),
            'appointment_time' => '10:00',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+34600000000',
            'terms' => '1',
        ], $overrides);
    }

    public function test_user_can_book_appointment_with_valid_data(): void
    {
        $response = $this->post(route('bookings.store'), $this->bookingPayload());

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $appointment = Appointment::latest()->first();

        $this->assertNotNull($appointment);
        $this->assertSame($this->service->id, $appointment->service_id);
        $this->assertSame('10:00', $appointment->time);
        $this->assertSame('pending', $appointment->status);

        $response->assertRedirect(route('payments.show', $appointment->id));

        $this->assertDatabaseHas('payments', [
            'appointment_id' => $appointment->id,
            'amount' => 15.00,
            'status' => 'pending',
        ]);
    }

    public function test_user_cannot_book_time_slot_already_booked(): void
    {
        $availableDate = AvailableDate::where('is_active', true)->orderBy('date')->first();

        Appointment::create([
            'service_id' => $this->service->id,
            'date' => $availableDate->date->toDateString(),
            'time' => '10:00',
            'status' => 'pending',
            'name' => 'First User',
            'email' => 'first@example.com',
            'phone' => '+34600000001',
        ]);

        $response = $this->post(route('bookings.store'), $this->bookingPayload([
            'name' => 'Second User',
            'email' => 'second@example.com',
            'phone' => '+34600000002',
        ]));

        $response->assertSessionHasErrors('appointment_time');
    }

    public function test_user_cannot_book_time_outside_available_hours(): void
    {
        $response = $this->post(route('bookings.store'), $this->bookingPayload([
            'appointment_time' => '08:00',
        ]));

        $response->assertSessionHasErrors('appointment_time');
    }

    public function test_user_cannot_book_on_closed_date(): void
    {
        // The next Sunday has no AvailableDate rows created in setUp.
        $sunday = Carbon::now()->next(Carbon::SUNDAY);

        $response = $this->post(route('bookings.store'), $this->bookingPayload([
            'appointment_date' => $sunday->toDateString(),
        ]));

        $response->assertSessionHasErrors('appointment_time');
    }

    public function test_api_returns_available_slots(): void
    {
        $availableDate = AvailableDate::where('is_active', true)->orderBy('date')->first();

        $response = $this->getJson('/api/appointments/available?date='.$availableDate->date->toDateString().'&duration=60');

        $response->assertStatus(200);
        $response->assertJsonStructure(['slots']);
        $this->assertNotEmpty($response->json('slots'));
    }

    public function test_api_excludes_booked_slots_from_available_slots(): void
    {
        // A weekday (09:00-18:00); Saturdays open at 10:00 so have no 09:00 slot.
        $availableDate = AvailableDate::where('is_active', true)->orderBy('date')->get()
            ->first(fn (AvailableDate $d) => ! $d->date->isSaturday());

        Appointment::create([
            'service_id' => $this->service->id,
            'date' => $availableDate->date->toDateString(),
            'time' => '10:00',
            'status' => 'pending',
            'name' => 'User',
            'email' => 'user@example.com',
            'phone' => '+34600000000',
        ]);

        $response = $this->getJson('/api/appointments/available?date='.$availableDate->date->toDateString().'&duration=60');

        $response->assertStatus(200);

        $slots = collect($response->json('slots'));
        $bookedSlot = $slots->firstWhere('time', '10:00');
        $freeSlot = $slots->firstWhere('time', '09:00');

        $this->assertNotNull($bookedSlot);
        $this->assertFalse($bookedSlot['available']);
        $this->assertNotNull($freeSlot);
        $this->assertTrue($freeSlot['available']);
    }

    public function test_appointment_requires_valid_date_format(): void
    {
        $response = $this->post(route('bookings.store'), $this->bookingPayload([
            'appointment_date' => 'invalid-date',
        ]));

        $response->assertSessionHasErrors('appointment_date');
    }

    public function test_appointment_requires_valid_time_format(): void
    {
        $response = $this->post(route('bookings.store'), $this->bookingPayload([
            'appointment_time' => 'invalid-time',
        ]));

        $response->assertSessionHasErrors('appointment_time');
    }

    public function test_appointment_cannot_be_in_past(): void
    {
        $yesterday = now()->subDay();

        $response = $this->post(route('bookings.store'), $this->bookingPayload([
            'appointment_date' => $yesterday->toDateString(),
        ]));

        $response->assertSessionHasErrors('appointment_date');
    }
}
