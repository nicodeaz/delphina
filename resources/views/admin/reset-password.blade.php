@extends('layouts.admin')

@section('title', 'Choose a new password')

@section('content')
<div class="flex min-h-screen items-center justify-center px-4 py-12">
    <div class="w-full max-w-sm">
        <div class="mb-8 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-olive/10 text-olive">
                <i class="fas fa-key text-xl"></i>
            </div>
            <h1 class="mt-4 text-2xl font-semibold text-gray-900">{{ $valid ? 'Choose a new password' : 'This link has expired' }}</h1>
            @unless($valid)
                <p class="mt-2 text-sm text-gray-600">Links work once and only for a short while. Ask for a new one and use the latest email.</p>
            @endunless
        </div>

        @if($valid)
            <form method="POST" action="{{ route('admin.password.store') }}" class="space-y-4 rounded-[1.75rem] bg-white p-6 shadow-xl ring-1 ring-stone-100" x-data="{ sending: false }" @submit="sending = true">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Visible username field so Safari/iCloud Keychain saves the new password for the right account --}}
                <div>
                    <label for="email" class="mb-1 block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" name="email" type="email" autocomplete="username" readonly value="{{ $email }}"
                           class="w-full rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3 text-base text-gray-600">
                </div>

                <div>
                    <label for="password" class="mb-1 block text-sm font-medium text-gray-700">New password</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" minlength="10" required autofocus
                           class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-base focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20">
                    <p class="mt-1 text-xs text-gray-500">At least 10 characters.</p>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1 block text-sm font-medium text-gray-700">Repeat new password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="10" required
                           class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-base focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20">
                </div>

                <button type="submit" :disabled="sending" class="w-full rounded-2xl bg-olive px-6 py-3.5 font-semibold text-white shadow-lg transition hover:bg-green-700 disabled:opacity-60">
                    <span x-text="sending ? 'Saving…' : 'Save and log in'">Save and log in</span>
                </button>
            </form>
        @else
            <a href="{{ route('admin.password.request') }}" class="block w-full rounded-2xl bg-olive px-6 py-3.5 text-center font-semibold text-white shadow-lg transition hover:bg-green-700">Send me a new link</a>
        @endif

        <a href="{{ route('admin.login') }}" class="mt-6 block text-center text-sm text-gray-500 hover:text-olive">← Back to log in</a>
    </div>
</div>
@endsection
