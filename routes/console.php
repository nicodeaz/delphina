<?php

use App\Mail\AdminPasswordResetMail;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
 * Create or update the studio admin without putting a password in any file:
 *   php artisan studio:admin someone@example.com --name="Delfi" --only
 * The password is asked for interactively (or pass --password for hosts
 * without an interactive terminal; it then ends up in shell history).
 */
Artisan::command('studio:admin {email} {--name=Delfi} {--password=} {--only : Remove the admin role from every other user}', function (string $email) {
    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->error('That is not a valid email address.');

        return 1;
    }

    $password = $this->option('password');

    if (! $password) {
        $password = $this->secret('Password');
        if ($password !== $this->secret('Repeat password')) {
            $this->error('Passwords do not match.');

            return 1;
        }
    }

    if (strlen((string) $password) < 10) {
        $this->error('Use at least 10 characters.');

        return 1;
    }

    $user = User::updateOrCreate(
        ['email' => strtolower($email)],
        ['name' => $this->option('name'), 'password' => Hash::make($password), 'role' => 'admin']
    );

    if ($this->option('only')) {
        $demoted = User::where('role', 'admin')->where('id', '!=', $user->id)->update(['role' => 'client']);
        $this->line("Removed admin access from {$demoted} other account(s).");
    }

    $this->info("Admin ready: {$user->email}");

    return 0;
})->purpose('Create or update the studio admin account');

/*
 * Send the studio admin a one-time login link (same as "Forgot your
 * password?" on the login page), e.g. when she's locked out:
 *   php artisan studio:access-link someone@example.com
 * --print shows the link instead of emailing it (to pass on by WhatsApp if
 * mail isn't arriving). --create makes that email an admin first if it
 * isn't one yet (other admins keep their access).
 */
Artisan::command('studio:access-link {email} {--print : Show the link instead of emailing it} {--create : Make this email the studio admin if it isn\'t one yet} {--name=Delfi}', function (string $email) {
    $user = User::findByEmail($email);

    if (! $user?->isAdmin()) {
        if (! $this->option('create')) {
            $admins = User::where('role', 'admin')->pluck('email');
            $this->error($user ? "{$user->email} exists but is not a studio admin." : "No account with the email {$email}.");
            $this->line('Studio admin account(s) right now: '.($admins->isEmpty() ? 'none' : $admins->implode(', ')));
            $this->line('Run again with --create to make this email the studio admin.');

            return 1;
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('That is not a valid email address.');

            return 1;
        }

        // A new account gets a random password; she chooses her own from the link.
        $user ??= new User(['email' => Str::lower(trim($email)), 'name' => $this->option('name'), 'password' => Hash::make(Str::random(40))]);
        $user->forceFill(['role' => 'admin'])->save();
        $this->info("{$user->email} is now a studio admin.");
    }

    $minutes = (int) config('auth.passwords.users.expire');
    $link = $user->createStudioAccessLink();

    if ($this->option('print')) {
        $this->line($link);
        $this->comment("Works once, for {$minutes} minutes.");

        return 0;
    }

    try {
        Mail::to($user->email)->send(new AdminPasswordResetMail($link, $minutes));
    } catch (Throwable $e) {
        $this->error('Could not send the email: '.$e->getMessage());
        $this->line('Use --print to get the link and send it another way.');

        return 1;
    }

    $this->info("Login link emailed to {$user->email} (works once, for {$minutes} minutes, opens ".parse_url($link, PHP_URL_HOST).').');

    return 0;
})->purpose('Email the studio admin a one-time login link');
