<?php

namespace Tests\Feature;

use App\Mail\AppointmentConfirmationMail;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Exercises the whole guest journey end to end: an admin opens a slot on
 * the agenda, a guest books it, gets redirected to Revolut for the €15
 * deposit, and the admin manually confirms the deposit which approves the
 * appointment and fires the confirmation email.
 */
class FullBookingLifecycleTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_open_a_slot_and_it_appears_on_the_agenda(): void
    {
        $admin = $this->admin();
        $date = now()->addDays(5)->toDateString();

        $response = $this->actingAs($admin)->post(route('admin.available-dates.store'), [
            'date' => $date,
            'start_time' => '09:00',
            'end_time' => '17:00',
        ]);

        $response->assertRedirect(route('admin.agenda'));
        $this->assertDatabaseHas('available_dates', [
            'date' => $date.' 00:00:00',
            'start_time' => '09:00',
            'end_time' => '17:00',
            'is_active' => true,
        ]);

        $agendaResponse = $this->actingAs($admin)->get(route('admin.agenda', ['month' => now()->addDays(5)->format('Y-m')]));

        $agendaResponse->assertOk();
        $agendaResponse->assertSee('09:00');
        $agendaResponse->assertSee('17:00');
    }

    public function test_full_guest_booking_to_confirmation_email_flow(): void
    {
        Mail::fake();

        $admin = $this->admin();
        $service = Service::create([
            'name' => 'Gel Manicure',
            'description' => 'Gel manicure service',
            'price' => 45.00,
            'duration' => 60,
        ]);

        $date = now()->addDays(3)->toDateString();

        // 1) Admin opens up the agenda slot.
        $this->actingAs($admin)->post(route('admin.available-dates.store'), [
            'date' => $date,
            'start_time' => '09:00',
            'end_time' => '17:00',
        ])->assertRedirect(route('admin.agenda'));

        // 2) The public slots API reflects the newly opened availability.
        $slotsResponse = $this->getJson('/api/appointments/available?date='.$date.'&duration=60');
        $slotsResponse->assertOk();
        $slot = collect($slotsResponse->json('slots'))->firstWhere('time', '10:00');
        $this->assertNotNull($slot);
        $this->assertTrue($slot['available']);

        // 3) A guest books that slot (no authentication required).
        $bookingResponse = $this->post(route('bookings.store'), [
            'services' => json_encode([$service->name => ['duration' => $service->duration]]),
            'appointment_date' => $date,
            'appointment_time' => '10:00',
            'name' => 'Maria Guest',
            'email' => 'maria@example.com',
            'phone' => '+34611111111',
            'preferred_contact' => 'email',
            'terms' => '1',
        ]);

        $appointment = Appointment::where('email', 'maria@example.com')->firstOrFail();
        $bookingResponse->assertRedirect(route('payments.show', $appointment->id));
        $this->assertSame('pending', $appointment->status);

        $payment = Payment::where('appointment_id', $appointment->id)->firstOrFail();
        $this->assertSame('pending', $payment->status);
        $this->assertEquals(15.00, (float) $payment->amount);

        // 4) The guest is shown the deposit payment page and can continue to Revolut.
        $this->get(route('payments.show', $appointment->id))->assertOk();

        $processResponse = $this->post(route('payments.process', $appointment->id));
        $processResponse->assertRedirect();
        $revolutUrl = $processResponse->headers->get('Location');
        $this->assertStringStartsWith(config('services.revolut.payment_link'), $revolutUrl);
        $this->assertStringContainsString('currency=EUR', $revolutUrl);
        $this->assertStringContainsString('amount=1500', $revolutUrl);

        $payment->refresh();
        $this->assertSame('revolut', $payment->payment_method);
        // Payment Links have no webhook: it stays "pending" until an admin confirms it.
        $this->assertSame('pending', $payment->status);

        // 5) The admin sees the deposit is still pending on the agenda...
        $agenda = $this->actingAs($admin)->get(route('admin.agenda', ['month' => now()->addDays(3)->format('Y-m')]));
        $agenda->assertOk();
        $agenda->assertSee('Maria Guest');

        // ...and manually confirms the Revolut deposit.
        $confirmResponse = $this->actingAs($admin)->patch(route('admin.payments.confirm', $payment->id));
        $confirmResponse->assertRedirect();

        $payment->refresh();
        $appointment->refresh();
        $this->assertSame('paid', $payment->status);
        $this->assertSame('approved', $appointment->status);

        // 6) The confirmation email was sent to the guest.
        Mail::assertSent(AppointmentConfirmationMail::class, function ($mail) use ($appointment) {
            return $mail->hasTo($appointment->email)
                && $mail->appointment->id === $appointment->id;
        });
    }

    public function test_admin_can_mark_an_appointment_as_completed(): void
    {
        $admin = $this->admin();
        $service = Service::create([
            'name' => 'Gel Manicure',
            'description' => 'Gel manicure service',
            'price' => 45.00,
            'duration' => 60,
        ]);

        $appointment = Appointment::create([
            'service_id' => $service->id,
            'date' => now()->subDay()->toDateString(),
            'time' => '10:00',
            'status' => 'approved',
            'name' => 'Past Client',
            'email' => 'past@example.com',
            'phone' => '+34600000000',
        ]);

        $response = $this->actingAs($admin)->patch(
            route('admin.appointments.updateStatus', $appointment->id),
            ['status' => 'completed']
        );

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertSame('completed', $appointment->fresh()->status);
    }
}
