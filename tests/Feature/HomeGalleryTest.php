<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeGalleryTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_displays_the_local_nail_gallery(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('The');
        $response->assertSee('Delfi Edit');
        $response->assertSee('img/opt/hero-silk-1600.webp');
        $response->assertSee('img/opt/01-pink-polka-dot-600.webp');
        $response->assertDontSee('Follow My');
    }
}
