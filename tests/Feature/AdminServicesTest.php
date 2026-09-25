<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminServicesTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_create_a_service_via_json(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson(route('admin.services.store'), [
                'name' => 'BIAB - Overlay',
                'description' => 'A strengthening overlay.',
                'price' => 45.50,
                'duration' => 60,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonPath('service.name', 'BIAB - Overlay');

        $this->assertDatabaseHas('services', [
            'name' => 'BIAB - Overlay',
            'price' => 45.50,
            'duration' => 60,
        ]);
    }

    public function test_service_creation_allows_a_missing_description(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson(route('admin.services.store'), [
                'name' => 'Quick add-on',
                'price' => 5,
                'duration' => 15,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('services', [
            'name' => 'Quick add-on',
        ]);
    }

    public function test_service_creation_validates_input(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->postJson(route('admin.services.store'), [
                'name' => '',
                'price' => -5,
                'duration' => 1,
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'price', 'duration']);
    }

    public function test_admin_can_update_a_service_via_json(): void
    {
        $admin = $this->admin();
        $service = Service::create([
            'name' => 'Gel Polish - Classic',
            'description' => 'Original description.',
            'price' => 25,
            'duration' => 30,
        ]);

        $response = $this->actingAs($admin)
            ->putJson(route('admin.services.update', $service), [
                'name' => 'Gel Polish - Classic Updated',
                'description' => 'Updated description.',
                'price' => 30,
                'duration' => 45,
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'name' => 'Gel Polish - Classic Updated',
            'price' => 30,
            'duration' => 45,
        ]);
    }

    public function test_admin_can_delete_a_service_with_no_appointments(): void
    {
        $admin = $this->admin();
        $service = Service::create([
            'name' => 'Unused Service',
            'description' => 'Never booked.',
            'price' => 10,
            'duration' => 20,
        ]);

        $response = $this->actingAs($admin)
            ->deleteJson(route('admin.services.destroy', $service));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }

    public function test_admin_cannot_delete_a_service_with_appointments(): void
    {
        $admin = $this->admin();
        $service = Service::create([
            'name' => 'Booked Service',
            'description' => 'Has history.',
            'price' => 40,
            'duration' => 60,
        ]);

        Appointment::create([
            'service_id' => $service->id,
            'user_id' => null,
            'name' => 'Jane Client',
            'email' => 'jane@example.com',
            'phone' => '+353899409670',
            'date' => now()->addDays(3)->toDateString(),
            'time' => '10:00',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->deleteJson(route('admin.services.destroy', $service));

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);

        $this->assertDatabaseHas('services', ['id' => $service->id]);
    }

    public function test_admin_cannot_delete_a_service_with_only_past_or_cancelled_appointments(): void
    {
        $admin = $this->admin();
        $service = Service::create([
            'name' => 'Old Service',
            'description' => 'Was booked once.',
            'price' => 20,
            'duration' => 30,
        ]);

        Appointment::create([
            'service_id' => $service->id,
            'user_id' => null,
            'name' => 'Past Client',
            'email' => 'past@example.com',
            'phone' => '+353899409670',
            'date' => now()->subMonth()->toDateString(),
            'time' => '09:00',
            'status' => 'cancelled',
        ]);

        $response = $this->actingAs($admin)
            ->deleteJson(route('admin.services.destroy', $service));

        $response->assertStatus(422);
        $this->assertDatabaseHas('services', ['id' => $service->id]);
    }
}
