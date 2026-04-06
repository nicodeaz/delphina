@extends('layouts.app')

@section('title', 'My Profile - Nail Art Studio')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid md:grid-cols-3 gap-8">
        <!-- Sidebar -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="text-center mb-6">
                    <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&size=100&background=E8C4D4&color=fff" 
                         alt="" class="w-24 h-24 rounded-full mx-auto mb-4">
                    <h2 class="text-2xl font-serif font-bold text-gray-900">{{ auth()->user()->name }}</h2>
                    <p class="text-gray-600">{{ auth()->user()->email }}</p>
                </div>

                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg bg-rose/20 text-gray-900 font-medium">
                            <i class="fas fa-user text-rose"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('appointments.my') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg hover:bg-gray-100 text-gray-700 transition-colors">
                            <i class="fas fa-calendar-alt text-gray-600"></i>
                            <span>My Appointments</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="md:col-span-2 space-y-6">
            <!-- Edit Profile Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                <h1 class="text-3xl font-serif font-bold text-gray-900 mb-8">Edit Profile</h1>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose focus:border-transparent @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose focus:border-transparent @error('email') border-red-500 @enderror"
                               required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-900 mb-2">Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose focus:border-transparent"
                               placeholder="+353 1 234 5678">
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('home') }}" class="px-6 py-2 text-gray-700 hover:text-gray-900 transition-colors">Cancel</a>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-rose to-pink-400 text-white rounded-lg hover:shadow-lg transition-all duration-300 font-semibold">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
