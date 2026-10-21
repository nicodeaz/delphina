<?php

namespace Tests\Feature;

use App\Mail\AdminPasswordResetMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();

        $this->admin = User::factory()->create([
            'email' => 'studio@example.com',
            'password' => Hash::make('old password 123'),
            'role' => 'admin',
        ]);
    }

    private function sentLink(): string
    {
        $url = null;
        Mail::assertSent(AdminPasswordResetMail::class, function (AdminPasswordResetMail $mail) use (&$url) {
            $url = $mail->url;

            return $mail->hasTo('studio@example.com');
        });

        return $url;
    }

    /** @return array{0: string, 1: string} token and email from the emailed link */
    private function tokenAndEmail(string $url): array
    {
        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);

        return [basename((string) parse_url($url, PHP_URL_PATH)), $query['email']];
    }

    public function test_login_page_links_to_forgot_password(): void
    {
        $this->get(route('admin.login'))->assertOk()->assertSee(route('admin.password.request'));
    }

    public function test_admin_gets_a_link_and_can_choose_a_new_password(): void
    {
        $this->post(route('admin.password.email'), ['email' => 'Studio@Example.com'])
            ->assertSessionHas('status');

        [$token, $email] = $this->tokenAndEmail($this->sentLink());

        $this->get(route('admin.password.reset', ['token' => $token, 'email' => $email]))
            ->assertOk()->assertSee('Save and log in');

        $this->post(route('admin.password.store'), [
            'token' => $token,
            'email' => $email,
            'password' => 'brand new password',
            'password_confirmation' => 'brand new password',
        ])->assertRedirect(route('admin.agenda'));

        $this->assertAuthenticatedAs($this->admin);
        $this->assertTrue(Hash::check('brand new password', $this->admin->fresh()->password));
    }

    public function test_link_works_only_once(): void
    {
        $this->post(route('admin.password.email'), ['email' => 'studio@example.com']);
        [$token, $email] = $this->tokenAndEmail($this->sentLink());

        $reset = fn (string $password) => $this->post(route('admin.password.store'), [
            'token' => $token, 'email' => $email, 'password' => $password, 'password_confirmation' => $password,
        ]);

        $reset('brand new password')->assertRedirect(route('admin.agenda'));
        auth()->logout();

        $reset('another password 1')->assertRedirect(route('admin.password.request'))->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->assertTrue(Hash::check('brand new password', $this->admin->fresh()->password));
        $this->get(route('admin.password.reset', ['token' => $token, 'email' => $email]))->assertSee('This link has expired');
    }

    public function test_link_expires(): void
    {
        $this->post(route('admin.password.email'), ['email' => 'studio@example.com']);
        [$token, $email] = $this->tokenAndEmail($this->sentLink());

        $this->travel(config('auth.passwords.users.expire') + 1)->minutes();

        $this->post(route('admin.password.store'), [
            'token' => $token, 'email' => $email, 'password' => 'brand new password', 'password_confirmation' => 'brand new password',
        ])->assertRedirect(route('admin.password.request'));

        $this->assertGuest();
        $this->assertTrue(Hash::check('old password 123', $this->admin->fresh()->password));
    }

    public function test_wrong_token_is_rejected(): void
    {
        $this->post(route('admin.password.email'), ['email' => 'studio@example.com']);

        $this->post(route('admin.password.store'), [
            'token' => 'not-the-token', 'email' => 'studio@example.com', 'password' => 'brand new password', 'password_confirmation' => 'brand new password',
        ])->assertRedirect(route('admin.password.request'));

        $this->assertGuest();
    }

    public function test_non_admins_and_unknown_emails_get_the_same_answer_and_no_mail(): void
    {
        User::factory()->create(['email' => 'client@example.com', 'role' => 'client']);

        foreach (['client@example.com', 'nobody@example.com'] as $email) {
            $this->post(route('admin.password.email'), ['email' => $email])->assertSessionHas('status');
        }

        Mail::assertNothingSent();
    }

    public function test_new_password_must_be_long_enough_and_match(): void
    {
        $this->post(route('admin.password.email'), ['email' => 'studio@example.com']);
        [$token, $email] = $this->tokenAndEmail($this->sentLink());

        $this->post(route('admin.password.store'), [
            'token' => $token, 'email' => $email, 'password' => 'short', 'password_confirmation' => 'short',
        ])->assertSessionHasErrors('password');

        $this->post(route('admin.password.store'), [
            'token' => $token, 'email' => $email, 'password' => 'brand new password', 'password_confirmation' => 'different password',
        ])->assertSessionHasErrors('password');

        $this->assertGuest();
    }
}
