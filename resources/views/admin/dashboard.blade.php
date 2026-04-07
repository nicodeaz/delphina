@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-beige-50 to-olive-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-light text-gray-900 mb-2">Admin Panel</h1>
            <p class="text-xl text-gray-600">Manage your bookings and services</p>
        </div>


        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-xl">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-2xl">📅</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Appointments</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalAppointments }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-xl">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <span class="text-2xl">⏳</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $pendingAppointments }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-xl">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <span class="text-2xl">✅</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Approved</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $approvedAppointments }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-xl">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-olive-100 rounded-full flex items-center justify-center">
                        <span class="text-2xl">💰</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                        <p class="text-2xl font-bold text-gray-900">€{{ number_format($totalRevenue, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Monthly Appointments Chart -->
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Monthly Appointments</h3>
                <canvas id="monthlyAppointmentsChart" width="400" height="200"></canvas>
            </div>

            <!-- Monthly Revenue Chart -->
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Monthly Revenue</h3>
                <canvas id="monthlyRevenueChart" width="400" height="200"></canvas>
            </div>

            <!-- Popular Services Chart -->
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Popular Services</h3>
                <canvas id="popularServicesChart" width="400" height="200"></canvas>
            </div>

            <!-- Appointments Status Chart -->
            <div class="bg-white rounded-2xl shadow-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Appointments by Status</h3>
                <canvas id="appointmentsStatusChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Appointments Management -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-medium text-gray-900">Appointment Management</h2>
                    <div class="flex space-x-2">
                        <button id="filterAll" class="px-4 py-2 text-sm font-medium rounded-lg bg-olive-100 text-olive-700 hover:bg-olive-200 transition-colors filter-btn active" data-filter="all">
                            All
                        </button>
                        <button id="filterPending" class="px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors filter-btn" data-filter="pending">
                            Pending
                        </button>
                        <button id="filterApproved" class="px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors filter-btn" data-filter="approved">
                            Approved
                        </button>
                        <button id="filterToday" class="px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors filter-btn" data-filter="today">
                            Today
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="appointmentsTable">
                        @foreach($appointments as $appointment)
                        @php
                            $clientName = $appointment->user->name ?? $appointment->name ?? 'Guest Client';
                            $clientEmail = $appointment->user->email ?? $appointment->email ?? 'No email provided';
                        @endphp
                        <tr class="appointment-row" data-status="{{ $appointment->status }}" data-date="{{ $appointment->date->format('Y-m-d') }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-olive-100 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-olive-700">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($clientName, 0, 1)) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $clientName }}</div>
                                        <div class="text-sm text-gray-500">{{ $clientEmail }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $appointment->service->name }}</div>
                                <div class="text-sm text-gray-500">{{ $appointment->service->duration }} min</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $appointment->date->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $appointment->time }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full status-badge
                                    @if($appointment->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($appointment->status == 'approved') bg-green-100 text-green-800
                                    @elseif($appointment->status == 'rejected') bg-red-100 text-red-800
                                    @elseif($appointment->status == 'cancelled') bg-gray-100 text-gray-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    @switch($appointment->status)
                                        @case('pending')
                                            Pending
                                            @break
                                        @case('approved')
                                            Approved
                                            @break
                                        @case('rejected')
                                            Rejected
                                            @break
                                        @case('cancelled')
                                            Cancelled
                                            @break
                                        @default
                                            {{ ucfirst($appointment->status) }}
                                    @endswitch
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($appointment->payment)
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($appointment->payment->status == 'paid') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $appointment->payment->status == 'paid' ? 'Paid' : 'Pending' }}
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Not required
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    @if($appointment->status == 'pending')
                                        <form method="POST" action="{{ route('admin.appointments.updateStatus', $appointment->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.appointments.updateStatus', $appointment->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Reject
                                            </button>
                                        </form>
                                    @elseif($appointment->status == 'approved')
                                        <form method="POST" action="{{ route('admin.appointments.updateStatus', $appointment->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Complete
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.appointments.updateStatus', $appointment->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Cancel
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-500 text-sm">No actions available</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($appointments->isEmpty())
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-4xl">📅</span>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No appointments</h3>
                <p class="text-gray-500">No appointments found with the applied filters.</p>
            </div>
            @endif
        </div>

        <!-- Services Management Section -->
        <div class="mt-8 bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-medium text-gray-900">Services</h2>
                    <a href="{{ route('admin.services.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-olive-600 hover:bg-olive-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-olive-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        New Service
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($services as $service)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $service->name }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($service->description, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">€{{ $service->price }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $service->duration }} min</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.services.edit', $service->id) }}" class="text-olive-600 hover:text-olive-900">Edit</a>
                                    <form method="POST" action="{{ route('admin.services.destroy', $service->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this service?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const appointmentRows = document.querySelectorAll('.appointment-row');
    const today = new Date().toISOString().split('T')[0];

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;

            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active', 'bg-olive-100', 'text-olive-700'));
            this.classList.add('active', 'bg-olive-100', 'text-olive-700');

            // Filter rows
            appointmentRows.forEach(row => {
                const status = row.dataset.status;
                const date = row.dataset.date;
                let show = false;

                switch(filter) {
                    case 'all':
                        show = true;
                        break;
                    case 'pending':
                        show = status === 'pending';
                        break;
                    case 'approved':
                        show = status === 'approved';
                        break;
                    case 'today':
                        show = date === today;
                        break;
                }

                row.style.display = show ? '' : 'none';
            });
        });
    });

    // Initialize Charts
    document.addEventListener('DOMContentLoaded', function() {
        // Monthly Appointments Chart
        const monthlyAppointmentsCtx = document.getElementById('monthlyAppointmentsChart').getContext('2d');
        new Chart(monthlyAppointmentsCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Appointments',
                    data: @json(array_values($monthlyAppointments)),
                    borderColor: '#8B9A7C',
                    backgroundColor: 'rgba(139, 154, 124, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Monthly Revenue Chart
        const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
        new Chart(monthlyRevenueCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Revenue (€)',
                    data: @json(array_values($monthlyRevenue)),
                    backgroundColor: '#8B9A7C',
                    borderColor: '#6B7B5A',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '€' + value;
                            }
                        }
                    }
                }
            }
        });

        // Popular Services Chart
        const popularServicesCtx = document.getElementById('popularServicesChart').getContext('2d');
        new Chart(popularServicesCtx, {
            type: 'doughnut',
            data: {
                labels: @json(array_keys($popularServices)),
                datasets: [{
                    data: @json(array_values($popularServices)),
                    backgroundColor: [
                        '#8B9A7C',
                        '#A8B894',
                        '#C5D4A7',
                        '#E2E8D1',
                        '#F5F7F0'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Appointments Status Chart
        const appointmentsStatusCtx = document.getElementById('appointmentsStatusChart').getContext('2d');
        new Chart(appointmentsStatusCtx, {
            type: 'pie',
            data: {
                labels: @json(array_keys($appointmentsByStatus)),
                datasets: [{
                    data: @json(array_values($appointmentsByStatus)),
                    backgroundColor: [
                        '#8B9A7C', // approved
                        '#F59E0B', // pending
                        '#EF4444', // rejected
                        '#6B7280', // cancelled
                        '#3B82F6'  // other
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
});
</script>
@endsection