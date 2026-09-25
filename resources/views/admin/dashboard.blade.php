@extends('layouts.admin')

@section('title', 'Stats')

@php
    $tiles = [
        ['label' => 'Bookings this month', 'value' => $bookingsThisMonth, 'icon' => 'fa-calendar-check', 'tone' => 'bg-olive/10 text-olive'],
        ['label' => 'Upcoming bookings', 'value' => $upcomingBookings, 'icon' => 'fa-clock', 'tone' => 'bg-sky-100 text-sky-700'],
        ['label' => 'Deposits received', 'value' => '€'.number_format($depositsCollected, 0), 'icon' => 'fa-wallet', 'tone' => 'bg-green-100 text-green-700'],
        ['label' => 'Earned so far', 'value' => '€'.number_format($totalRevenue, 0), 'icon' => 'fa-euro-sign', 'tone' => 'bg-amber-100 text-amber-700', 'hint' => '€'.number_format($remainingBalance, 0).' still to collect'],
    ];
@endphp

@section('content')
<div class="py-6 md:py-8">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-olive">How it's going</p>
            <h1 class="mt-2 text-3xl font-semibold text-gray-900 md:text-4xl">Stats</h1>
        </div>

        <div class="mb-6 grid grid-cols-2 gap-3 lg:grid-cols-4 lg:gap-4">
            @foreach($tiles as $tile)
                <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-stone-100">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl {{ $tile['tone'] }}"><i class="fas {{ $tile['icon'] }} text-sm"></i></span>
                    <p class="mt-3 text-2xl font-bold text-gray-900">{{ $tile['value'] }}</p>
                    <p class="text-xs font-medium text-gray-500">{{ $tile['label'] }}</p>
                    @isset($tile['hint'])
                        <p class="mt-1 text-[11px] text-gray-400">{{ $tile['hint'] }}</p>
                    @endisset
                </div>
            @endforeach
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            <section class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-stone-100">
                <h2 class="mb-4 text-sm font-semibold text-gray-900">Bookings per month · {{ date('Y') }}</h2>
                <div class="h-56"><canvas id="monthlyAppointmentsChart"></canvas></div>
            </section>
            <section class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-stone-100">
                <h2 class="mb-4 text-sm font-semibold text-gray-900">Income per month · {{ date('Y') }}</h2>
                <div class="h-56"><canvas id="monthlyRevenueChart"></canvas></div>
            </section>
            <section class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-stone-100 lg:col-span-2">
                <h2 class="mb-4 text-sm font-semibold text-gray-900">Most booked services</h2>
                @if(empty($popularServices))
                    <p class="py-8 text-center text-sm text-gray-400">No bookings yet.</p>
                @else
                    @php $max = max($popularServices); @endphp
                    <ul class="space-y-3">
                        @foreach($popularServices as $name => $count)
                            <li>
                                <div class="mb-1 flex justify-between text-sm"><span class="truncate text-gray-700">{{ $name }}</span><span class="ml-3 font-semibold text-gray-900">{{ $count }}</span></div>
                                <div class="h-2 rounded-full bg-stone-100"><div class="h-2 rounded-full bg-olive" style="width: {{ round($count / $max * 100) }}%"></div></div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const common = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { x: { grid: { display: false } } },
    };

    new Chart(document.getElementById('monthlyAppointmentsChart'), {
        type: 'bar',
        data: { labels: months, datasets: [{ data: @json(array_values($monthlyAppointments)), backgroundColor: '#554F13', borderRadius: 6 }] },
        options: { ...common, scales: { ...common.scales, y: { beginAtZero: true, ticks: { precision: 0 } } } },
    });

    new Chart(document.getElementById('monthlyRevenueChart'), {
        type: 'line',
        data: { labels: months, datasets: [{ data: @json(array_values($monthlyRevenue)), borderColor: '#554F13', backgroundColor: 'rgba(85, 79, 19, 0.1)', fill: true, tension: 0.35 }] },
        options: { ...common, scales: { ...common.scales, y: { beginAtZero: true, ticks: { callback: (v) => '€' + v } } } },
    });
});
</script>
@endpush
