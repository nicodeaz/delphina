<?php

namespace Tests\Feature;

use App\Mail\AdminLoginCodeMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminTwoFactorLoginTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        config(['auth.admin_two_factor' => true]);
        Mail::fake();

        $this->admin = User::factory()->create([
            'email' => 'studio@example.com',
            'password' => Hash::make('correct horse battery'),
            'role' => 'admin',
        ]);
    }

    private function sentCode(): string
    {
        $code = null;
        Mail::assertSent(AdminLoginCodeMail::class, function (AdminLoginCodeMail $mail) use (&$code) {
            $code = $mail->code;

            return $mail->hasTo('studio@example.com');
        });

        return $code;
    }

    public function test_password_alone_does_not_log_in_and_a_code_is_emailed(): void
    {
        $this->post(route('admin.login.post'), ['email' => 'studio@example.com', 'password' => 'correct horse battery'])
            ->assertRedirect(route('admin.login.verify'));

        $this->assertGuest();
        $this->assertMatchesRegularExpression('/^\d{6}$/', $this->sentCode());
        $this->get(route('admin.agenda'))->assertRedirect(route('admin.login'));
    }

    public function test_correct_code_logs_in_and_lands_on_the_agenda(): void
    {
        $this->post(route('admin.login.post'), ['email' => 'studio@example.com', 'password' => 'correct horse battery']);
        $this->get(route('admin.login.verify'))->assertOk()->assertSee('st••••@example.com');

        $this->post(route('admin.login.verify.post'), ['code' => $this->sentCode()])
            ->assertRedirect(route('admin.agenda'));

        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_wrong_codes_are_limited(): void
    {
        $this->post(route('admin.login.post'), ['email' => 'studio@example.com', 'password' => 'correct horse battery']);
        $code = $this->sentCode();
        $wrong = $code === '000000' ? '111111' : '000000';

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('admin.login.verify.post'), ['code' => $wrong])->assertSessionHasErrors('code');
        }

        // Even the right code is refused once the attempts are used up.
        $this->post(route('admin.login.verify.post'), ['code' => $code])->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_expired_code_is_refused(): void
    {
        $this->post(route('admin.login.post'), ['email' => 'studio@example.com', 'password' => 'correct horse battery']);
        $code = $this->sentCode();

        $this->travel(11)->minutes();

        $this->post(route('admin.login.verify.post'), ['code' => $code])->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_wrong_password_sends_nothing_and_is_throttled(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('admin.login.post'), ['email' => 'studio@example.com', 'password' => 'nope'])
                ->assertSessionHasErrors('email');
        }

        $this->post(route('admin.login.post'), ['email' => 'studio@example.com', 'password' => 'correct horse battery'])
            ->assertSessionHasErrors('email');

        $this->assertStringStartsWith('Too many attempts', session('errors')->first('email'));

        Mail::assertNothingSent();
        $this->assertGuest();
    }

    public function test_non_admin_users_cannot_log_in(): void
    {
        User::factory()->create(['email' => 'client@example.com', 'password' => Hash::make('whatever123'), 'role' => 'client']);

        $this->post(route('admin.login.post'), ['email' => 'client@example.com', 'password' => 'whatever123'])
            ->assertSessionHasErrors('email');

        Mail::assertNothingSent();
    }

    public function test_verify_page_needs_a_pending_login(): void
    {
        $this->get(route('admin.login.verify'))->assertRedirect(route('admin.login'));
    }
}
