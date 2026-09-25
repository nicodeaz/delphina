<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

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
