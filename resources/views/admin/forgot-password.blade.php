@extends('layouts.admin')

@section('title', 'Forgot your password')

@section('content')
<div class="flex min-h-screen items-center justify-center px-4 py-12">
    <div class="w-full max-w-sm">
        <div class="mb-8 text-center">
            <img src="{{ asset('img/studio/icon-192.png') }}" alt="" class="mx-auto h-16 w-16 rounded-2xl shadow-lg">
            <h1 class="mt-4 text-2xl font-semibold text-gray-900">Forgot your password?</h1>
            <p class="mt-2 text-sm text-gray-600">We’ll email you a one-time login link. It works for {{ config('auth.passwords.users.expire') }} minutes.</p>
        </div>

        @if(session('status'))
            <p class="mb-4 rounded-2xl bg-green-50 px-4 py-3 text-center text-sm text-green-800">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('admin.password.email') }}" class="space-y-4 rounded-[1.75rem] bg-white p-6 shadow-xl ring-1 ring-stone-100" x-data="{ sending: false }" @submit="sending = true">
            @csrf
            <div>
                <label for="email" class="mb-1 block text-sm font-medium text-gray-700">Email</label>
                <input id="email" name="email" type="email" autocomplete="email" required autofocus value="{{ old('email') }}"
                       class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-base focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" :disabled="sending" class="w-full rounded-2xl bg-olive px-6 py-3.5 font-semibold text-white shadow-lg transition hover:bg-green-700 disabled:opacity-60">
                <span x-text="sending ? 'Sending…' : 'Email me a login link'">Email me a login link</span>
            </button>
        </form>

        <a href="{{ route('admin.login') }}" class="mt-6 block text-center text-sm text-gray-500 hover:text-olive">← Back to log in</a>
    </div>
</div>
@endsection
