<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudioAppTest extends TestCase
{
    use RefreshDatabase;

    public function test_manifest_installs_the_admin_as_its_own_app(): void
    {
        $manifest = json_decode(file_get_contents(public_path('studio.webmanifest')), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('standalone', $manifest['display']);
        $this->assertSame('admin/agenda', $manifest['start_url']);
        $this->assertSame('admin/', $manifest['scope']);

        foreach ($manifest['icons'] as $icon) {
            $this->assertFileExists(public_path($icon['src']));
            [$width, $height] = getimagesize(public_path($icon['src']));
            $this->assertSame($icon['sizes'], "{$width}x{$height}");
        }

        $this->assertFileExists(public_path('studio-sw.js'));
        $this->assertFileExists(public_path('studio-offline.html'));
        $this->assertFileExists(public_path('img/studio/apple-touch-icon.png'));
    }

    public function test_admin_pages_link_the_manifest_and_service_worker(): void
    {
        $this->get(route('admin.login'))->assertOk()
            ->assertSee(asset('studio.webmanifest'))
            ->assertSee('studio-sw.js')
            ->assertSee('apple-mobile-web-app-capable', false)
            ->assertSee(route('admin.install'));
    }

    public function test_install_guide_is_reachable_without_logging_in(): void
    {
        $this->get(route('admin.install'))->assertOk()
            ->assertSee('Add to Home Screen')
            ->assertSee('Install app');
    }

    public function test_agenda_suggests_installing_the_app(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('admin.agenda'))->assertOk()
            ->assertSee('Put your studio on your home screen')
            ->assertSee(route('admin.install'));
    }

    public function test_logout_stays_in_the_studio_app(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('admin.logout'))->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }
}
