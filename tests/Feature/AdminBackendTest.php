<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminBackendTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Service $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->service = Service::create(['name' => 'BIAB - Overlay', 'description' => 'BIAB', 'price' => 40, 'duration' => 60]);
    }

    private function booking(string $name, string $date, string $status = 'pending', bool $depositPaid = false): Appointment
    {
        $groupId = uniqid('booking_', true);
        $appointment = Appointment::create([
            'service_id' => $this->service->id,
            'date' => $date,
            'time' => '10:00',
            'status' => $status,
            'name' => $name,
            'email' => strtolower($name).'@example.com',
            'phone' => '0871234567',
            'group_id' => $groupId,
        ]);
        Payment::create([
            'appointment_id' => $appointment->id,
            'amount' => Payment::AMOUNT,
            'status' => $depositPaid ? 'paid' : 'pending',
            'group_id' => $groupId,
        ]);

        return $appointment;
    }

    public function test_every_admin_screen_uses_the_backend_layout_without_public_chrome(): void
    {
        foreach (['admin.agenda', 'admin.appointments.index', 'admin.services.index', 'admin.dashboard'] as $route) {
            $this->actingAs($this->admin)->get(route($route))
                ->assertOk()
                ->assertSee('Delphina Studio')
                ->assertSee('View website')
                ->assertDontSee('chatbot-launcher', false)
                ->assertDontSee('bookingSheet()', false)
                ->assertDontSee('Book an appointment');
        }
    }

    public function test_bookings_list_filters_and_search(): void
    {
        $this->booking('Upcoming', now()->addDays(2)->toDateString(), 'approved', true);
        $this->booking('Unpaid', now()->addDays(3)->toDateString());
        $this->booking('Earlier', now()->subDays(3)->toDateString(), 'completed', true);
        $this->booking('Dropped', now()->addDays(4)->toDateString(), 'cancelled');

        $this->actingAs($this->admin)->get(route('admin.appointments.index'))
            ->assertSee('Upcoming')->assertSee('Unpaid')->assertDontSee('Earlier')->assertDontSee('Dropped');

        $this->actingAs($this->admin)->get(route('admin.appointments.index', ['filter' => 'deposit']))
            ->assertSee('Unpaid')->assertDontSee('"customer":"Upcoming"', false);

        $this->actingAs($this->admin)->get(route('admin.appointments.index', ['filter' => 'past']))
            ->assertSee('Earlier')->assertDontSee('Unpaid');

        $this->actingAs($this->admin)->get(route('admin.appointments.index', ['filter' => 'cancelled']))
            ->assertSee('Dropped')->assertDontSee('Unpaid');

        $this->actingAs($this->admin)->get(route('admin.appointments.index', ['filter' => 'all', 'q' => 'earl']))
            ->assertSee('Earlier')->assertDontSee('Unpaid');
    }

    public function test_old_admin_urls_redirect_to_the_new_screens(): void
    {
        $this->actingAs($this->admin)->get(route('admin.available-dates.index'))->assertRedirect(route('admin.agenda'));
        $this->actingAs($this->admin)->get('/admin/payments')->assertRedirect(route('admin.appointments.index', ['filter' => 'deposit']));
    }

    public function test_stats_count_bookings_not_service_rows(): void
    {
        // One booking with two services = one booking.
        $groupId = uniqid('booking_', true);
        foreach ([$this->service, Service::create(['name' => 'Nail Art', 'description' => 'Art', 'price' => 10, 'duration' => 30])] as $service) {
            Appointment::create([
                'service_id' => $service->id, 'date' => now()->addDay()->toDateString(), 'time' => '10:00', 'status' => 'approved',
                'name' => 'Double', 'email' => 'd@example.com', 'phone' => '1', 'group_id' => $groupId,
            ]);
        }

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertOk();
        $this->assertSame(1, $response->viewData('upcomingBookings'));
    }

    public function test_public_pages_only_show_a_studio_link_to_a_logged_in_admin(): void
    {
        $this->actingAs($this->admin)->get(route('home'))
            ->assertOk()
            ->assertSee(route('admin.agenda'), false)
            ->assertDontSee('Available Dates');
    }

    public function test_admin_can_change_password_from_the_backend(): void
    {
        $this->admin->update(['password' => Hash::make('old password 123')]);

        $this->actingAs($this->admin)->putJson(route('admin.password.update'), [
            'current_password' => 'wrong one',
            'password' => 'a much longer phrase',
            'password_confirmation' => 'a much longer phrase',
        ])->assertStatus(422)->assertJsonValidationErrors('current_password');

        $this->actingAs($this->admin)->putJson(route('admin.password.update'), [
            'current_password' => 'old password 123',
            'password' => 'short',
            'password_confirmation' => 'short',
        ])->assertStatus(422)->assertJsonValidationErrors('password');

        $this->actingAs($this->admin)->putJson(route('admin.password.update'), [
            'current_password' => 'old password 123',
            'password' => 'a much longer phrase',
            'password_confirmation' => 'a much longer phrase',
        ])->assertOk();

        $this->assertTrue(Hash::check('a much longer phrase', $this->admin->fresh()->password));
        $this->actingAs($this->admin)->get(route('admin.agenda'))->assertSee('Change password');
    }

    public function test_google_verification_tag_only_renders_when_configured(): void
    {
        $this->get(route('home'))->assertDontSee('google-site-verification', false);

        config(['services.google.site_verification' => 'abc123token']);
        $this->get(route('home'))->assertSee('<meta name="google-site-verification" content="abc123token">', false);
    }

    public function test_footer_links_to_the_studio_login(): void
    {
        $this->get(route('home'))->assertSee('href="'.route('admin.login').'" class="footer-link font-medium" rel="nofollow">Admin</a>', false);
    }

    public function test_login_page_has_no_admin_navigation_for_guests(): void
    {
        $this->get(route('admin.login'))->assertOk()->assertSee('Welcome back')->assertDontSee('View website');
    }
}
