@extends('layouts.app')

@section('title', 'Admin Login - Nails by Delphina')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-nude via-white to-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <h2 class="text-3xl font-serif font-bold text-gray-900">Admin Access</h2>
            <p class="mt-2 text-sm text-gray-600">Sign in to manage your appointments</p>
        </div>

        <!-- Login Form -->
        <div class="bg-white py-8 px-6 shadow-xl rounded-2xl">
            <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-olive focus:border-olive"
                           value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-olive focus:border-olive">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-olive to-green-700 hover:from-green-700 hover:to-olive focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-olive transition-all duration-300">
                        Sign In
                    </button>
                </div>
            </form>
        </div>

        <!-- Back to Site -->
        <div class="text-center">
            <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-olive transition-colors">
                ← Back to website
            </a>
        </div>
    </div>
</div>
@endsection