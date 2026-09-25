<?php

namespace App\Http\Controllers;

use App\Mail\AdminLoginCodeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

/**
 * Studio login: password first, then a 6-digit code emailed to the admin.
 * The pending login lives in the session (code stored hashed) until the
 * code is confirmed; only then is the user actually authenticated.
 */
class AdminAuthController extends Controller
{
    private const CODE_TTL_MINUTES = 10;

    private const MAX_CODE_ATTEMPTS = 5;

    private const RESEND_COOLDOWN_SECONDS = 60;

    public function showLoginForm()
    {
        if (auth()->user()?->isAdmin()) {
            return redirect()->route('admin.agenda');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|max:255',
        ]);

        $throttleKey = 'admin-login|'.Str::lower($credentials['email']).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withInput($request->only('email'))
                ->withErrors(['email' => "Too many attempts. Please try again in {$seconds} seconds."]);
        }

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! $user->isAdmin() || ! Hash::check($credentials['password'], $user->password)) {
            RateLimiter::hit($throttleKey, 60);

            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'These details don’t match our records.']);
        }

        RateLimiter::clear($throttleKey);

        if (! config('auth.admin_two_factor')) {
            return $this->completeLogin($request, $user);
        }

        $request->session()->put('admin_2fa', [
            'user_id' => $user->id,
            'code_hash' => null,
            'expires_at' => 0,
            'attempts' => 0,
            'sent_at' => 0,
        ]);

        if (! $this->sendCode($request, $user)) {
            $request->session()->forget('admin_2fa');

            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'We couldn’t send your login code by email. Please try again in a minute.']);
        }

        return redirect()->route('admin.login.verify');
    }

    public function showVerifyForm(Request $request)
    {
        $pending = $request->session()->get('admin_2fa');

        if (! $pending) {
            return redirect()->route('admin.login');
        }

        $user = User::find($pending['user_id']);

        return view('admin.verify', [
            'maskedEmail' => $user ? $this->maskEmail($user->email) : '',
            'canResendIn' => max(0, $pending['sent_at'] + self::RESEND_COOLDOWN_SECONDS - now()->getTimestamp()),
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|string|max:12']);

        $pending = $request->session()->get('admin_2fa');

        if (! $pending) {
            return redirect()->route('admin.login')->withErrors(['email' => 'Please log in again.']);
        }

        if (now()->getTimestamp() > $pending['expires_at'] || $pending['attempts'] >= self::MAX_CODE_ATTEMPTS) {
            $request->session()->forget('admin_2fa');

            return redirect()->route('admin.login')
                ->withErrors(['email' => 'That code expired. Please log in again to get a new one.']);
        }

        $code = preg_replace('/\D/', '', $request->input('code'));

        if (! $pending['code_hash'] || ! Hash::check($code, $pending['code_hash'])) {
            $pending['attempts']++;
            $request->session()->put('admin_2fa', $pending);
            $left = self::MAX_CODE_ATTEMPTS - $pending['attempts'];

            return back()->withErrors(['code' => $left > 0
                ? "That code isn’t right. {$left} ".($left === 1 ? 'try' : 'tries').' left.'
                : 'Too many wrong codes. Please log in again.']);
        }

        $user = User::find($pending['user_id']);
        $request->session()->forget('admin_2fa');

        if (! $user || ! $user->isAdmin()) {
            return redirect()->route('admin.login');
        }

        return $this->completeLogin($request, $user);
    }

    public function resend(Request $request)
    {
        $pending = $request->session()->get('admin_2fa');
        $user = $pending ? User::find($pending['user_id']) : null;

        if (! $user) {
            return redirect()->route('admin.login');
        }

        if (now()->getTimestamp() < $pending['sent_at'] + self::RESEND_COOLDOWN_SECONDS) {
            return back()->withErrors(['code' => 'Please wait a moment before asking for another code.']);
        }

        if (! $this->sendCode($request, $user)) {
            return back()->withErrors(['code' => 'We couldn’t send the code. Please try again in a minute.']);
        }

        return back()->with('status', 'We sent you a new code.');
    }

    /**
     * Change password from the backend menu. Also rotates the remember-me
     * token so other devices that stayed logged in have to log in again.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:10|max:255|confirmed',
        ], [
            'password.min' => 'Use at least 10 characters.',
            'password.confirmed' => 'The new passwords don’t match.',
        ]);

        $user = $request->user();

        if (! Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'message' => 'Your current password isn’t right.',
                'errors' => ['current_password' => ['Your current password isn’t right.']],
            ], 422);
        }

        $user->forceFill([
            'password' => Hash::make($validated['password']),
            'remember_token' => Str::random(60),
        ])->save();

        return response()->json(['success' => true]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function sendCode(Request $request, User $user): bool
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        try {
            Mail::to($user->email)->send(new AdminLoginCodeMail($code, self::CODE_TTL_MINUTES));
        } catch (\Throwable $e) {
            Log::error('Failed to send admin login code: '.$e->getMessage());

            return false;
        }

        $request->session()->put('admin_2fa', array_merge($request->session()->get('admin_2fa', []), [
            'user_id' => $user->id,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->getTimestamp() + self::CODE_TTL_MINUTES * 60,
            'attempts' => 0,
            'sent_at' => now()->getTimestamp(),
        ]));

        return true;
    }

    private function completeLogin(Request $request, User $user)
    {
        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->intended(route('admin.agenda'));
    }

    private function maskEmail(string $email): string
    {
        [$name, $domain] = array_pad(explode('@', $email, 2), 2, '');

        return Str::substr($name, 0, 2).str_repeat('•', max(1, Str::length($name) - 2)).'@'.$domain;
    }
}
