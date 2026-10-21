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
 * link (token stored hashed in password_reset_tokens, expires per
 * config('auth.passwords.users.expire'), deleted once used) that opens a
 * form to choose a new password and logs her straight in.
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

        $user = User::where('email', Str::lower($request->input('email')))->first();

        if ($user?->isAdmin() && ! $this->tokens()->recentlyCreatedToken($user)) {
            $url = route('admin.password.reset', ['token' => $this->tokens()->create($user), 'email' => $user->email]);

            try {
                Mail::to($user->email)->send(new AdminPasswordResetMail($url, (int) config('auth.passwords.users.expire')));
            } catch (\Throwable $e) {
                Log::error('Failed to send admin password reset link: '.$e->getMessage());

                return back()->withInput()
                    ->withErrors(['email' => 'We couldn’t send the email. Please try again in a minute.']);
            }
        }

        // Same answer for any address, so the form doesn't reveal which one is the studio's.
        return back()->with('status', 'If that’s the studio email, a link to choose a new password is on its way. Check your inbox (and spam).');
    }

    public function edit(Request $request, string $token)
    {
        $email = (string) $request->query('email', '');
        $user = User::where('email', Str::lower($email))->first();

        return view('admin.reset-password', [
            'token' => $token,
            'email' => $email,
            'valid' => $user?->isAdmin() && $this->tokens()->exists($user, $token),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:10|max:255|confirmed',
        ], [
            'password.min' => 'Use at least 10 characters.',
            'password.confirmed' => 'The new passwords don’t match.',
        ]);

        $user = User::where('email', Str::lower($validated['email']))->first();

        if (! $user?->isAdmin() || ! $this->tokens()->exists($user, $validated['token'])) {
            return redirect()->route('admin.password.request')
                ->withErrors(['email' => 'That link has expired or was already used. Ask for a new one below.']);
        }

        $this->tokens()->delete($user);

        $user->forceFill([
            'password' => Hash::make($validated['password']),
            'remember_token' => Str::random(60),
        ])->save();

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
