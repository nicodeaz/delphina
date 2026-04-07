@extends('layouts.app')

@section('title', 'Manage Available Dates')

@php
    $availableDatesByDay = $availableDates->groupBy(fn ($item) => $item->date->format('Y-m-d'));
    $monthKeys = $availableDatesByDay->keys()->map(fn ($date) => substr($date, 0, 7))->unique()->values();
    $requestedMonth = request('month');
    $currentMonthKey = $requestedMonth && preg_match('/^\d{4}-\d{2}$/', $requestedMonth)
        ? $requestedMonth
        : ($monthKeys->first() ?? now()->format('Y-m'));
    $currentMonth = \Carbon\Carbon::createFromFormat('Y-m', $currentMonthKey)->startOfMonth();
    $monthDates = $availableDatesByDay->filter(fn ($items, $date) => str_starts_with($date, $currentMonthKey));
    $selectedDateKey = request('date');
    $selectedDateKey = $selectedDateKey && $availableDatesByDay->has($selectedDateKey)
        ? $selectedDateKey
        : ($monthDates->keys()->sort()->first() ?? $currentMonth->toDateString());
    $selectedEntries = $availableDatesByDay->get($selectedDateKey, collect());
    $startOffset = $currentMonth->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
    $endOffset = $currentMonth->copy()->endOfMonth()->endOfWeek(\Carbon\Carbon::SUNDAY);
    $calendarDays = [];
    for ($day = $startOffset->copy(); $day->lte($endOffset); $day->addDay()) {
        $calendarDays[] = $day->copy();
    }
    $prevMonth = $currentMonth->copy()->subMonth()->format('Y-m');
    $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');
@endphp

@section('content')
<div class="min-h-screen bg-gradient-to-br from-beige-50 via-white to-olive-50 py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-olive">Admin availability</p>
                <h1 class="mt-3 text-4xl font-semibold text-gray-900">Calendar view for open dates</h1>
                <p class="mt-3 max-w-2xl text-sm text-gray-600">Manage the studio schedule visually, inspect each day, and jump straight into adding or editing availability blocks.</p>
            </div>
            <a href="{{ route('admin.available-dates.create', ['date' => $selectedDateKey]) }}" class="inline-flex items-center justify-center rounded-2xl bg-olive px-5 py-3 font-semibold text-white shadow-lg transition hover:bg-green-700">
                <i class="fas fa-plus mr-2"></i>
                Add availability
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-8 xl:grid-cols-[minmax(0,1fr),360px]">
            <section class="rounded-[2rem] bg-white p-5 shadow-xl ring-1 ring-stone-100 md:p-8">
                <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-olive">Month overview</p>
                        <h2 class="mt-2 text-2xl font-semibold text-gray-900">{{ $currentMonth->format('F Y') }}</h2>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.available-dates.index', ['month' => $prevMonth]) }}" class="rounded-2xl border border-stone-200 px-4 py-3 text-sm font-medium text-gray-700 transition hover:border-olive hover:text-olive">Previous</a>
                        <a href="{{ route('admin.available-dates.index', ['month' => $nextMonth]) }}" class="rounded-2xl border border-stone-200 px-4 py-3 text-sm font-medium text-gray-700 transition hover:border-olive hover:text-olive">Next</a>
                    </div>
                </div>

                <div class="mb-4 grid grid-cols-7 gap-2 text-center text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">
                    <span>Mon</span>
                    <span>Tue</span>
                    <span>Wed</span>
                    <span>Thu</span>
                    <span>Fri</span>
                    <span>Sat</span>
                    <span>Sun</span>
                </div>

                <div class="grid grid-cols-7 gap-2">
                    @foreach($calendarDays as $day)
                        @php
                            $dayKey = $day->format('Y-m-d');
                            $isCurrentMonth = $day->format('Y-m') === $currentMonthKey;
                            $dayEntries = $availableDatesByDay->get($dayKey, collect());
                            $isSelected = $dayKey === $selectedDateKey;
                        @endphp
                        <a href="{{ route('admin.available-dates.index', ['month' => $currentMonthKey, 'date' => $dayKey]) }}"
                           class="min-h-[92px] rounded-[1.5rem] border p-3 transition {{ $isSelected ? 'border-olive bg-olive text-white shadow-lg' : ($dayEntries->isNotEmpty() ? 'border-stone-200 bg-white hover:border-olive hover:bg-olive/5' : 'border-transparent bg-stone-50 text-gray-400') }} {{ !$isCurrentMonth ? 'opacity-45' : '' }}">
                            <div class="flex items-start justify-between gap-2">
                                <span class="text-sm font-semibold">{{ $day->day }}</span>
                                @if($dayEntries->isNotEmpty())
                                    <span class="rounded-full px-2 py-1 text-[10px] font-semibold {{ $isSelected ? 'bg-white/20 text-white' : 'bg-olive/10 text-olive' }}">{{ $dayEntries->count() }}</span>
                                @endif
                            </div>
                            <div class="mt-6 text-xs leading-relaxed {{ $isSelected ? 'text-white/80' : 'text-gray-500' }}">
                                @if($dayEntries->isNotEmpty())
                                    {{ $dayEntries->first()->start_time }} - {{ $dayEntries->sortByDesc('end_time')->first()->end_time }}
                                @else
                                    No slots
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>

            <aside class="rounded-[2rem] bg-white p-6 shadow-xl ring-1 ring-stone-100 lg:sticky lg:top-24 lg:self-start">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-olive">Selected day</p>
                <h2 class="mt-2 text-2xl font-semibold text-gray-900">{{ \Carbon\Carbon::parse($selectedDateKey)->format('l, d F Y') }}</h2>
                <p class="mt-3 text-sm text-gray-500">Review every configured time block for this date and make edits instantly.</p>

                <div class="mt-6 space-y-4">
                    @forelse($selectedEntries->sortBy('start_time') as $entry)
                        <div class="rounded-3xl border border-stone-200 bg-stone-50 p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.25em] text-gray-400">Time range</p>
                                    <p class="mt-2 text-lg font-semibold text-gray-900">{{ $entry->start_time }} - {{ $entry->end_time }}</p>
                                    <p class="mt-2 text-sm text-gray-500">{{ $entry->notes ?: 'No notes for this time block.' }}</p>
                                </div>
                                <a href="{{ route('admin.available-dates.edit', $entry) }}" class="rounded-2xl border border-stone-200 px-4 py-2 text-sm font-medium text-gray-700 transition hover:border-olive hover:text-olive">Edit</a>
                            </div>
                            <form method="POST" action="{{ route('admin.available-dates.destroy', $entry) }}" class="mt-4" onsubmit="return confirm('Delete this availability block?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-medium text-red-600 transition hover:text-red-700">Delete slot</button>
                            </form>
                        </div>
                    @empty
                        <div class="rounded-3xl border border-dashed border-stone-200 px-5 py-6 text-sm text-gray-500">
                            No availability blocks have been added for this day yet.
                        </div>
                    @endforelse
                </div>

                <div class="mt-6 rounded-3xl bg-olive/5 p-5">
                    <p class="text-xs uppercase tracking-[0.25em] text-gray-400">Quick action</p>
                    <button type="button" id="openAvailabilityModal" class="mt-3 inline-flex items-center text-sm font-semibold text-olive hover:text-green-700">
                        Add a new time block for this day
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </aside>
        </div>
    </div>
</div>

<div id="availabilityModal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/40 p-4">
    <div class="w-full max-w-xl rounded-[2rem] bg-white p-6 shadow-2xl ring-1 ring-stone-100 md:p-8">
        <div class="mb-6 flex items-start justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-olive">New availability block</p>
                <h3 class="mt-2 text-2xl font-semibold text-gray-900">Add schedule directly from calendar</h3>
            </div>
            <button type="button" id="closeAvailabilityModal" class="rounded-xl border border-stone-200 px-3 py-2 text-sm font-medium text-gray-600 hover:border-olive hover:text-olive">Close</button>
        </div>

        <form method="POST" action="{{ route('admin.available-dates.store') }}" class="space-y-5">
            @csrf
            <div>
                <label for="modal_date" class="mb-2 block text-sm font-medium text-gray-700">Date</label>
                <input type="date" id="modal_date" name="date" required min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}" value="{{ $selectedDateKey }}" class="w-full rounded-2xl border border-stone-300 px-4 py-3 focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20">
            </div>
            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="modal_start_time" class="mb-2 block text-sm font-medium text-gray-700">Start time</label>
                    <input type="time" id="modal_start_time" name="start_time" required value="09:00" class="w-full rounded-2xl border border-stone-300 px-4 py-3 focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20">
                </div>
                <div>
                    <label for="modal_end_time" class="mb-2 block text-sm font-medium text-gray-700">End time</label>
                    <input type="time" id="modal_end_time" name="end_time" required value="18:00" class="w-full rounded-2xl border border-stone-300 px-4 py-3 focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20">
                </div>
            </div>
            <div>
                <label for="modal_notes" class="mb-2 block text-sm font-medium text-gray-700">Notes</label>
                <textarea id="modal_notes" name="notes" rows="3" class="w-full rounded-2xl border border-stone-300 px-4 py-3 focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20" placeholder="Optional notes for this day..."></textarea>
            </div>
            <div class="flex items-center justify-end gap-3">
                <button type="button" id="cancelAvailabilityModal" class="rounded-2xl border border-stone-200 px-5 py-3 font-semibold text-gray-700 hover:border-olive hover:text-olive">Cancel</button>
                <button type="submit" class="rounded-2xl bg-olive px-5 py-3 font-semibold text-white shadow-lg hover:bg-green-700">Save availability</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('availabilityModal');
    const openButton = document.getElementById('openAvailabilityModal');
    const closeButton = document.getElementById('closeAvailabilityModal');
    const cancelButton = document.getElementById('cancelAvailabilityModal');

    if (!modal || !openButton) return;

    const closeModal = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };

    const openModal = () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    openButton.addEventListener('click', openModal);
    if (closeButton) closeButton.addEventListener('click', closeModal);
    if (cancelButton) cancelButton.addEventListener('click', closeModal);

    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            closeModal();
        }
    });
});
</script>
@endsection