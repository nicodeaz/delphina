@extends('layouts.admin')

@section('title', 'Check your email')

@section('content')
<div class="flex min-h-screen items-center justify-center px-4 py-12">
    <div class="w-full max-w-sm">
        <div class="mb-8 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-olive/10 text-olive">
                <i class="fas fa-envelope-open-text text-xl"></i>
            </div>
            <h1 class="mt-4 text-2xl font-semibold text-gray-900">Check your email</h1>
            <p class="mt-2 text-sm text-gray-600">We sent a 6-digit code to <strong class="text-gray-900">{{ $maskedEmail }}</strong>.</p>
        </div>

        @if(session('status'))
            <p class="mb-4 rounded-2xl bg-green-50 px-4 py-3 text-center text-sm text-green-800">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('admin.login.verify.post') }}" class="space-y-4 rounded-[1.75rem] bg-white p-6 shadow-xl ring-1 ring-stone-100" x-data="{ sending: false }" @submit="sending = true">
            @csrf
            <div>
                <label for="code" class="mb-1 block text-sm font-medium text-gray-700">Login code</label>
                <input id="code" name="code" type="text" inputmode="numeric" pattern="[0-9 ]*" maxlength="7" autocomplete="one-time-code" required autofocus
                       class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-center font-mono text-2xl tracking-[0.5em] focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20"
                       placeholder="••••••">
                @error('code')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" :disabled="sending" class="w-full rounded-2xl bg-olive px-6 py-3.5 font-semibold text-white shadow-lg transition hover:bg-green-700 disabled:opacity-60">
                <span x-text="sending ? 'Checking…' : 'Log in'">Log in</span>
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-500" x-data="{ wait: {{ (int) $canResendIn }} }" x-init="if (wait > 0) { const t = setInterval(() => { if (--wait <= 0) clearInterval(t) }, 1000) }">
            <p>Didn’t get it? Check your spam folder, or</p>
            <form method="POST" action="{{ route('admin.login.resend') }}" class="mt-1">
                @csrf
                <button type="submit" :disabled="wait > 0" class="font-semibold text-olive hover:underline disabled:cursor-not-allowed disabled:text-gray-400 disabled:no-underline">
                    <span x-show="wait <= 0">send a new code</span>
                    <span x-show="wait > 0" x-cloak>send a new code in <span x-text="wait"></span>s</span>
                </button>
            </form>
            <a href="{{ route('admin.login') }}" class="mt-4 inline-block text-gray-400 hover:text-olive">← Use a different account</a>
        </div>
    </div>
</div>
@endsection
