@extends('layouts.admin')

@section('title', 'Log in')

@section('content')
<div class="flex min-h-screen items-center justify-center px-4 py-12">
    <div class="w-full max-w-sm">
        <div class="mb-8 text-center">
            <img src="{{ asset('img/logo_green.png') }}" alt="Nails by Delphina" class="mx-auto h-16 w-auto">
            <p class="mt-4 text-xs font-semibold uppercase tracking-[0.3em] text-olive">Delphina Studio</p>
            <h1 class="mt-2 text-2xl font-semibold text-gray-900">Welcome back</h1>
        </div>

        <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-4 rounded-[1.75rem] bg-white p-6 shadow-xl ring-1 ring-stone-100" x-data="{ sending: false }" @submit="sending = true">
            @csrf

            <div>
                <label for="email" class="mb-1 block text-sm font-medium text-gray-700">Email</label>
                <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                       class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-base focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-1 block text-sm font-medium text-gray-700">Password</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required
                       class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-base focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" :disabled="sending" class="w-full rounded-2xl bg-olive px-6 py-3.5 font-semibold text-white shadow-lg transition hover:bg-green-700 disabled:opacity-60">
                <span x-text="sending ? 'Logging in…' : 'Log in'">Log in</span>
            </button>
        </form>

        <a href="{{ route('home') }}" class="mt-6 block text-center text-sm text-gray-500 hover:text-olive">← Back to website</a>
    </div>
</div>
@endsection
