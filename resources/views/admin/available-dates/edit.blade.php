@extends('layouts.app')

@section('title', 'Editar Fecha Disponible')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-800 text-white">
                <h1 class="text-2xl font-bold">Editar Fecha Disponible</h1>
            </div>

            <form method="POST" action="{{ route('admin.available-dates.update', $availableDate) }}" class="p-6">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                    <input type="date" name="date" id="date" value="{{ old('date', $availableDate->date) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('date') border-red-500 @enderror"
                           required>
                    @error('date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Hora Inicio</label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $availableDate->start_time) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('start_time') border-red-500 @enderror"
                               required>
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">Hora Fin</label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $availableDate->end_time) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('end_time') border-red-500 @enderror"
                               required>
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="slot_duration" class="block text-sm font-medium text-gray-700 mb-2">Duración de cada slot (minutos)</label>
                    <select name="slot_duration" id="slot_duration"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('slot_duration') border-red-500 @enderror"
                            required>
                        <option value="15" {{ old('slot_duration', $availableDate->slot_duration) == '15' ? 'selected' : '' }}>15 minutos</option>
                        <option value="30" {{ old('slot_duration', $availableDate->slot_duration) == '30' ? 'selected' : '' }}>30 minutos</option>
                        <option value="45" {{ old('slot_duration', $availableDate->slot_duration) == '45' ? 'selected' : '' }}>45 minutos</option>
                        <option value="60" {{ old('slot_duration', $availableDate->slot_duration) == '60' ? 'selected' : '' }}>1 hora</option>
                        <option value="90" {{ old('slot_duration', $availableDate->slot_duration) == '90' ? 'selected' : '' }}>1.5 horas</option>
                        <option value="120" {{ old('slot_duration', $availableDate->slot_duration) == '120' ? 'selected' : '' }}>2 horas</option>
                    </select>
                    @error('slot_duration')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('admin.available-dates.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                        <i class="fas fa-save mr-2"></i>Actualizar Fecha
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection