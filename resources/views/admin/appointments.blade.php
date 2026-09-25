@extends('layouts.admin')

@section('title', 'Bookings')

@php
    $bookingsConfig = [
        'deposit' => \App\Models\Payment::AMOUNT,
        'revolutLink' => config('services.revolut.payment_link'),
    ];
@endphp

@section('content')
<div class="py-6 md:py-8" x-data="bookingsList(@js($bookingsConfig))">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <div class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-olive">Clients</p>
                <h1 class="mt-2 text-3xl font-semibold text-gray-900 md:text-4xl">Bookings</h1>
            </div>
            <form method="GET" action="{{ route('admin.appointments.index') }}" class="relative sm:w-72">
                <input type="hidden" name="filter" value="{{ $filter }}">
                <i class="fas fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400"></i>
                <input type="search" name="q" value="{{ $search }}" placeholder="Search name, phone or email" class="w-full rounded-2xl border border-stone-200 bg-white py-3 pl-10 pr-4 text-base focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20">
            </form>
        </div>

        <div class="-mx-4 mb-5 flex gap-2 overflow-x-auto px-4 pb-1 [scrollbar-width:none] sm:mx-0 sm:px-0">
            @foreach($filters as $key => $label)
                <a href="{{ route('admin.appointments.index', array_filter(['filter' => $key, 'q' => $search])) }}"
                   class="shrink-0 rounded-full border px-4 py-2 text-sm font-semibold transition {{ $filter === $key ? 'border-olive bg-olive text-white' : 'border-stone-200 bg-white text-gray-600 hover:border-olive' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        @if($search !== '')
            <p class="mb-4 text-sm text-gray-500">
                {{ $total }} result{{ $total === 1 ? '' : 's' }} for “{{ $search }}”.
                <a href="{{ route('admin.appointments.index', ['filter' => $filter]) }}" class="font-semibold text-olive hover:underline">Clear</a>
            </p>
        @endif

        @forelse($bookingsByDate as $date => $bookings)
            @php $day = \Carbon\Carbon::parse($date); @endphp
            <section class="mb-5">
                <h2 class="mb-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.2em] {{ $day->isToday() ? 'text-olive' : 'text-gray-400' }}">
                    {{ $day->isToday() ? 'Today' : ($day->isTomorrow() ? 'Tomorrow' : $day->format('l j F Y')) }}
                </h2>
                <div class="divide-y divide-stone-100 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-stone-100">
                    @foreach($bookings as $booking)
                        <button type="button" @click="openBooking(@js($booking))" class="flex w-full items-center gap-4 px-4 py-3.5 text-left transition hover:bg-stone-50">
                            <span class="w-12 shrink-0 text-sm font-bold text-gray-900">{{ $booking['time'] }}</span>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate font-semibold text-gray-900">{{ $booking['customer'] }}</span>
                                <span class="block truncate text-xs text-gray-500">{{ $booking['services'] }}</span>
                            </span>
                            <span class="flex shrink-0 flex-col items-end gap-1">
                                <span class="text-sm font-semibold text-gray-900">€{{ number_format($booking['total_price'], 0) }}</span>
                                @if(in_array($booking['status'], ['cancelled', 'rejected']))
                                    <span class="rounded-full bg-stone-100 px-2 py-0.5 text-[10px] font-semibold text-gray-500">{{ ucfirst($booking['status']) }}</span>
                                @elseif($booking['balance_status'] === 'paid')
                                    <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-semibold text-emerald-800">Paid in full</span>
                                @elseif($booking['payment_status'] === 'paid')
                                    <span class="rounded-full bg-green-100 px-2 py-0.5 text-[10px] font-semibold text-green-800">Deposit paid</span>
                                @else
                                    <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold text-amber-800">Deposit pending</span>
                                @endif
                            </span>
                            <i class="fas fa-chevron-right text-xs text-gray-300"></i>
                        </button>
                    @endforeach
                </div>
            </section>
        @empty
            <div class="rounded-2xl border border-dashed border-stone-300 bg-white/60 px-6 py-12 text-center">
                <i class="fas fa-calendar-check mb-3 text-3xl text-stone-300"></i>
                <p class="font-semibold text-gray-700">
                    {{ $search !== '' ? 'No bookings match your search.' : ($filter === 'deposit' ? 'No deposits pending. All good!' : 'No bookings here yet.') }}
                </p>
                @if($filter === 'upcoming' && $search === '')
                    <a href="{{ route('admin.agenda') }}" class="mt-3 inline-block text-sm font-semibold text-olive hover:underline">Go to the agenda to add one</a>
                @endif
            </div>
        @endforelse

        @if($total > 200)
            <p class="mt-4 text-center text-xs text-gray-400">Showing the first 200. Use search to find older bookings.</p>
        @endif
    </div>

    @include('admin.partials.booking-modal')
</div>
@endsection

@push('scripts')
<script>
    function bookingsList(config) {
        return bookingManager(config);
    }
</script>
@endpush
