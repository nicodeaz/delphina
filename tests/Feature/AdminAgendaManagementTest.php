<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\AvailableDate;
use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAgendaManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Service $gel;

    private Service $art;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->gel = Service::create(['name' => 'Gel - Full Set', 'description' => 'Gel', 'price' => 45, 'duration' => 60]);
        $this->art = Service::create(['name' => 'Nail Art', 'description' => 'Art', 'price' => 10, 'duration' => 30]);
    }

    public function test_guests_are_sent_to_the_login_page(): void
    {
        $this->get(route('admin.agenda'))->assertRedirect(route('admin.login'));
        $this->postJson(route('admin.bookings.store'), [])->assertStatus(401);
    }

    public function test_admin_can_open_hours_on_many_days_at_once(): void
    {
        $start = now()->next('Monday');

        $response = $this->actingAs($this->admin)->postJson(route('admin.available-dates.bulk'), [
            'start_date' => $start->toDateString(),
            'end_date' => $start->copy()->addDays(13)->toDateString(),
            'weekdays' => [1, 2, 3, 4, 5],
            'blocks' => [
                ['start_time' => '09:00', 'end_time' => '13:00'],
                ['start_time' => '14:00', 'end_time' => '18:00'],
            ],
        ]);

        $response->assertOk()->assertJson(['days' => 10, 'created' => 20]);
        $this->assertSame(20, AvailableDate::count());

        // Running it again doesn't duplicate blocks.
        $this->actingAs($this->admin)->postJson(route('admin.available-dates.bulk'), [
            'start_date' => $start->toDateString(),
            'end_date' => $start->copy()->addDays(13)->toDateString(),
            'weekdays' => [1, 2, 3, 4, 5],
            'blocks' => [['start_time' => '09:00', 'end_time' => '13:00']],
        ])->assertJson(['created' => 0]);

        // Replacing swaps the hours on those days.
        $this->actingAs($this->admin)->postJson(route('admin.available-dates.bulk'), [
            'start_date' => $start->toDateString(),
            'end_date' => $start->toDateString(),
            'weekdays' => [1],
            'blocks' => [['start_time' => '10:00', 'end_time' => '16:00']],
            'replace_existing' => true,
        ])->assertJson(['created' => 1]);

        $this->assertSame(
            [['10:00', '16:00']],
            AvailableDate::whereDate('date', $start->toDateString())->get()->map(fn ($a) => [substr($a->start_time, 0, 5), substr($a->end_time, 0, 5)])->all()
        );
    }

    public function test_bulk_rejects_blocks_that_end_before_they_start(): void
    {
        $this->actingAs($this->admin)->postJson(route('admin.available-dates.bulk'), [
            'start_date' => now()->addDay()->toDateString(),
            'end_date' => now()->addDays(7)->toDateString(),
            'weekdays' => [1, 2, 3, 4, 5, 6, 0],
            'blocks' => [['start_time' => '18:00', 'end_time' => '09:00']],
        ])->assertStatus(422);

        $this->assertSame(0, AvailableDate::count());
    }

    public function test_availability_can_be_edited_and_deleted_as_json(): void
    {
        $block = AvailableDate::create(['date' => now()->addDays(2)->toDateString(), 'start_time' => '09:00', 'end_time' => '17:00', 'is_active' => true]);

        $this->actingAs($this->admin)->putJson(route('admin.available-dates.update', $block), [
            'date' => $block->date->toDateString(),
            'start_time' => '10:00',
            'end_time' => '15:00',
        ])->assertOk();

        $this->assertSame('10:00', substr($block->fresh()->start_time, 0, 5));

        $this->actingAs($this->admin)->deleteJson(route('admin.available-dates.destroy', $block))->assertOk();
        $this->assertSame(0, AvailableDate::count());
    }

    public function test_admin_can_add_a_manual_booking_with_several_services(): void
    {
        $date = now()->addDays(3)->toDateString();

        $this->actingAs($this->admin)->postJson(route('admin.bookings.store'), [
            'name' => 'Instagram Client',
            'phone' => '0871234567',
            'service_ids' => [$this->gel->id, $this->art->id],
            'date' => $date,
            'time' => '11:00',
            'deposit_paid' => true,
        ])->assertOk();

        $appointments = Appointment::all();
        $this->assertCount(2, $appointments);
        $this->assertSame(1, $appointments->pluck('group_id')->unique()->count());
        $this->assertTrue($appointments->every(fn ($a) => $a->status === 'approved'));

        $payment = Payment::sole();
        $this->assertSame('paid', $payment->status);
        $this->assertSame($appointments->first()->group_id, $payment->group_id);
    }

    public function test_overlapping_manual_booking_asks_for_confirmation(): void
    {
        $date = now()->addDays(3)->toDateString();

        // Existing booking 10:00 with 60 + 30 minutes of services -> busy until 11:30.
        $this->actingAs($this->admin)->postJson(route('admin.bookings.store'), [
            'name' => 'First', 'service_ids' => [$this->gel->id, $this->art->id], 'date' => $date, 'time' => '10:00',
        ])->assertOk();

        $this->actingAs($this->admin)->postJson(route('admin.bookings.store'), [
            'name' => 'Second', 'service_ids' => [$this->art->id], 'date' => $date, 'time' => '11:00',
        ])->assertStatus(409)->assertJson(['conflict' => true]);

        $this->actingAs($this->admin)->postJson(route('admin.bookings.store'), [
            'name' => 'Second', 'service_ids' => [$this->art->id], 'date' => $date, 'time' => '11:00', 'force' => true,
        ])->assertOk();

        $this->actingAs($this->admin)->postJson(route('admin.bookings.store'), [
            'name' => 'Third', 'service_ids' => [$this->art->id], 'date' => $date, 'time' => '12:00',
        ])->assertOk();
    }

    public function test_admin_can_edit_and_move_a_whole_booking(): void
    {
        $this->actingAs($this->admin)->postJson(route('admin.bookings.store'), [
            'name' => 'Client', 'service_ids' => [$this->gel->id, $this->art->id], 'date' => now()->addDays(3)->toDateString(), 'time' => '10:00',
        ]);

        $first = Appointment::orderBy('id')->first();
        $newDate = now()->addDays(5)->toDateString();

        $this->actingAs($this->admin)->putJson(route('admin.bookings.update', $first), [
            'name' => 'Client Renamed',
            'phone' => '0870000000',
            'email' => 'client@example.com',
            'date' => $newDate,
            'time' => '14:30',
            'notes' => 'Almond shape',
        ])->assertOk();

        foreach (Appointment::all() as $appointment) {
            $this->assertSame('Client Renamed', $appointment->name);
            $this->assertSame($newDate, $appointment->date->toDateString());
            $this->assertSame('14:30', substr($appointment->time, 0, 5));
        }
    }

    public function test_admin_can_delete_a_whole_booking_with_its_deposit(): void
    {
        $this->actingAs($this->admin)->postJson(route('admin.bookings.store'), [
            'name' => 'Client', 'service_ids' => [$this->gel->id, $this->art->id], 'date' => now()->addDays(3)->toDateString(), 'time' => '10:00',
        ]);

        $this->actingAs($this->admin)->deleteJson(route('admin.bookings.destroy', Appointment::first()))->assertOk();

        $this->assertSame(0, Appointment::count());
        $this->assertSame(0, Payment::count());
    }

    public function test_multi_service_bookings_block_their_full_duration_for_clients(): void
    {
        $date = now()->addDays(3)->toDateString();
        AvailableDate::create(['date' => $date, 'start_time' => '09:00', 'end_time' => '13:00', 'is_active' => true]);

        // 09:00 booking with 60 + 30 min -> busy until 10:30.
        $this->actingAs($this->admin)->postJson(route('admin.bookings.store'), [
            'name' => 'Busy', 'service_ids' => [$this->gel->id, $this->art->id], 'date' => $date, 'time' => '09:00',
        ]);

        $slots = collect($this->getJson(route('api.appointments.available', ['date' => $date, 'duration' => 30]))->json('slots'))
            ->where('available', true)->pluck('time')->all();

        $this->assertNotContains('10:00', $slots);
        $this->assertContains('10:30', $slots);
        // A 30-min service can't start at 12:45-ish past the window: last start is 12:30.
        $this->assertSame('12:30', end($slots));
    }

    public function test_public_booking_returns_json_redirect_to_payment(): void
    {
        $date = now()->addDays(3)->toDateString();
        AvailableDate::create(['date' => $date, 'start_time' => '09:00', 'end_time' => '13:00', 'is_active' => true]);

        $response = $this->postJson(route('bookings.store'), [
            'services' => json_encode([$this->gel->name => ['id' => $this->gel->id]]),
            'appointment_date' => $date,
            'appointment_time' => '12:30', // 60 min would run past 13:00
            'name' => 'Guest',
            'email' => 'guest@example.com',
            'phone' => '0871234567',
            'terms' => 1,
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors('appointment_time');

        $response = $this->postJson(route('bookings.store'), [
            'services' => json_encode([$this->gel->name => ['id' => $this->gel->id]]),
            'appointment_date' => $date,
            'appointment_time' => '11:00',
            'name' => 'Guest',
            'email' => 'guest@example.com',
            'phone' => '0871234567',
            'terms' => 1,
        ]);

        $appointment = Appointment::sole();
        $response->assertOk()->assertJson(['redirect' => route('payments.show', $appointment)]);
        $this->get(route('payments.show', $appointment))->assertOk()->assertSee('Pay €15.00 with Revolut');
    }

    public function test_public_booking_rejects_oversized_service_lists(): void
    {
        $services = [];
        for ($i = 0; $i < 11; $i++) {
            $services["Service {$i}"] = ['id' => $i];
        }

        $this->postJson(route('bookings.store'), [
            'services' => json_encode($services),
            'appointment_date' => now()->addDays(3)->toDateString(),
            'appointment_time' => '11:00',
            'name' => 'Guest',
            'email' => 'guest@example.com',
            'phone' => '0871234567',
            'terms' => 1,
        ])->assertStatus(422)->assertJsonValidationErrors('services');

        $this->assertSame(0, Appointment::count());
    }

    public function test_home_page_includes_the_booking_sheet(): void
    {
        $this->get(route('home'))->assertOk()->assertSee('bookingSheet()', false)->assertSee('Book your appointment');
    }
}
