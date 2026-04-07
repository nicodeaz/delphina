@extends('layouts.app')

@section('title', 'Add Available Date')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-beige-50 via-white to-olive-50 py-10">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] bg-white p-6 shadow-xl ring-1 ring-stone-100 md:p-8">
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-olive">Admin availability</p>
            <h1 class="mt-3 text-3xl font-semibold text-gray-900">Add a new available date</h1>
            <p class="mt-2 text-sm text-gray-500">Create a time block for a specific day. You can add multiple blocks for the same date if needed.</p>

            <form method="POST" action="{{ route('admin.available-dates.store') }}" class="mt-8 space-y-6">
                @csrf

                <div>
                    <label for="date" class="mb-2 block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="date" id="date" value="{{ old('date', request('date')) }}"
                           class="w-full rounded-2xl border border-stone-300 px-4 py-3 focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20 @error('date') border-red-500 @enderror"
                           required min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}">
                    @error('date')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="start_time" class="mb-2 block text-sm font-medium text-gray-700">Start time</label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time', '09:00') }}"
                               class="w-full rounded-2xl border border-stone-300 px-4 py-3 focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20 @error('start_time') border-red-500 @enderror"
                               required>
                        @error('start_time')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="end_time" class="mb-2 block text-sm font-medium text-gray-700">End time</label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time', '18:00') }}"
                               class="w-full rounded-2xl border border-stone-300 px-4 py-3 focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20 @error('end_time') border-red-500 @enderror"
                               required>
                        @error('end_time')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="notes" class="mb-2 block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="4"
                              class="w-full rounded-2xl border border-stone-300 px-4 py-3 focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20 @error('notes') border-red-500 @enderror"
                              placeholder="Special opening notes, breaks, or comments for this day...">{{ old('notes') }}</textarea>
                    @error('notes')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:justify-between">
                    <a href="{{ route('admin.available-dates.index', ['date' => old('date', request('date'))]) }}" class="inline-flex items-center justify-center rounded-2xl border border-stone-200 px-5 py-3 font-semibold text-gray-700 transition hover:border-olive hover:text-olive">Cancel</a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-olive px-5 py-3 font-semibold text-white shadow-lg transition hover:bg-green-700">
                        <i class="fas fa-save mr-2"></i>
                        Save availability
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection