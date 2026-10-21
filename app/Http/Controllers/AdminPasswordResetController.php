<?php

namespace App\Http\Controllers;

use App\Mail\AdminPasswordResetMail;
use App\Models\User;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

/**
 * "Forgot your password?" for the studio login: emails the admin a one-time
 * access link (token stored hashed in password_reset_tokens, expires per
 * config('auth.passwords.users.expire'), deleted once used). Opening it lets
 * her choose a new password, or just log in this once. The same link can be
 * sent from the server with `php artisan studio:access-link`.
 */
class AdminPasswordResetController extends Controller
{
    public function create()
    {
        return view('admin.forgot-password');
    }

    public function store(Request $request)
    {
        $request->validate(['email' => 'required|email|max:255']);

        $user = User::findByEmail($request->input('email'));

        if ($user?->isAdmin() && ! $this->tokens()->recentlyCreatedToken($user)) {
            try {
                Mail::to($user->email)->send(new AdminPasswordResetMail($user->createStudioAccessLink(), (int) config('auth.passwords.users.expire')));
            } catch (\Throwable $e) {
                Log::error('Failed to send studio access link: '.$e->getMessage());

                return back()->withInput()
                    ->withErrors(['email' => 'We couldn’t send the email. Please try again in a minute.']);
            }
        }

        // Same answer for any address, so the form doesn't reveal which one is the studio's.
        return back()->with('status', 'If that’s the studio email, a login link is on its way. Check your inbox (and spam) — it works for '.config('auth.passwords.users.expire').' minutes.');
    }

    public function edit(Request $request, string $token)
    {
        $email = (string) $request->query('email', '');
        $user = User::findByEmail($email);

        return view('admin.reset-password', [
            'token' => $token,
            'email' => $email,
            'valid' => $user?->isAdmin() && $this->tokens()->exists($user, $token),
        ]);
    }

    public function update(Request $request)
    {
        $skip = $request->boolean('skip');

        $validated = $request->validate([
            'token' => 'required|string',
            'email' => 'required|email|max:255',
            'password' => $skip ? 'nullable' : 'required|string|min:10|max:255|confirmed',
        ], [
            'password.min' => 'Use at least 10 characters.',
            'password.confirmed' => 'The new passwords don’t match.',
        ]);

        $user = User::findByEmail($validated['email']);

        if (! $user?->isAdmin() || ! $this->tokens()->exists($user, $validated['token'])) {
            return redirect()->route('admin.password.request')
                ->withErrors(['email' => 'That link has expired or was already used. Ask for a new one below.']);
        }

        $this->tokens()->delete($user);

        if (! $skip) {
            $user->forceFill([
                'password' => Hash::make($validated['password']),
                'remember_token' => Str::random(60),
            ])->save();
        }

        // The link already proved access to her inbox (the same thing the
        // emailed login code proves), so no second code is asked for here.
        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->route('admin.agenda');
    }

    private function tokens(): TokenRepositoryInterface
    {
        return Password::broker()->getRepository();
    }
}
