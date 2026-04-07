@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-nude via-white to-olive/10 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-light text-gray-900 mb-2">Edit Service</h1>
            <p class="text-xl text-gray-600">Update service information</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <form action="{{ route('admin.services.update', $service) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Service Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $service->name) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-olive focus:border-transparent transition-colors @error('name') border-red-500 @enderror"
                           placeholder="e.g., Gel Nails - French Tip" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-olive focus:border-transparent transition-colors @error('description') border-red-500 @enderror"
                              placeholder="Describe the service in detail..." required>{{ old('description', $service->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price and Duration -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (€)</label>
                        <input type="number" name="price" id="price" value="{{ old('price', $service->price) }}" step="0.01" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-olive focus:border-transparent transition-colors @error('price') border-red-500 @enderror"
                               placeholder="0.00" required>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Duration -->
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                        <input type="number" name="duration" id="duration" value="{{ old('duration', $service->duration) }}" min="1"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-olive focus:border-transparent transition-colors @error('duration') border-red-500 @enderror"
                               placeholder="60" required>
                        @error('duration')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.services.index') }}"
                       class="px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-olive-500 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-olive to-green-700 hover:from-olive/90 hover:to-green-700/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-olive-500 transition-all transform hover:scale-105 shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        Update Service
                    </button>
                </div>
            </form>
        </div>

        <!-- Back to Services -->
        <div class="mt-8 text-center">
            <a href="{{ route('admin.services.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-olive-500 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Services
            </a>
        </div>
    </div>
</div>
@endsection