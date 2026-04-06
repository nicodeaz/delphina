@extends('layouts.app')

@section('title', 'My Appointments - Nail Art Studio')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-4xl font-serif font-bold text-gray-900 mb-2">My Appointments</h1>
            <p class="text-gray-600">Manage and track all your nail studio appointments</p>
        </div>
        <a href="{{ route('book') }}" class="mt-4 md:mt-0 px-6 py-3 bg-gradient-to-r from-rose to-pink-400 text-white rounded-lg hover:shadow-lg transition-all duration-300 font-semibold inline-flex items-center justify-center">
            <i class="fas fa-plus mr-2"></i> Book New Appointment
        </a>
    </div>

    <!-- Appointments Grid -->
    @if($appointments->count() > 0)
        <div class="space-y-6">
            @foreach($appointments as $appointment)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                    <div class="flex flex-col md:flex-row">
                        <!-- Image -->
                        <div class="md:w-1/4 bg-gradient-to-br from-rose/10 to-pink-300/10 p-6 flex items-center justify-center">
                            <div class="text-6xl">💅</div>
                        </div>

                        <!-- Content -->
                        <div class="md:w-3/4 p-6 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-2xl font-serif font-bold text-gray-900">{{ $appointment->service->name }}</h3>
                                    <div>{!! $appointment->status_badge !!}</div>
                                </div>

                                <p class="text-gray-600 mb-2">{{ $appointment->service->description }}</p>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 my-4 pt-4 border-t border-gray-200">
                                    <div>
                                        <p class="text-xs text-gray-600 font-semibold uppercase">Date</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $appointment->date->format('d/m') }}</p>
                                        <p class="text-sm text-gray-600">{{ $appointment->date->format('l') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600 font-semibold uppercase">Time</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $appointment->time }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600 font-semibold uppercase">Duration</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $appointment->service->duration }} min</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600 font-semibold uppercase">Price</p>
                                        <p class="text-lg font-bold text-rose">€{{ number_format($appointment->service->price, 2) }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200 mt-4">
                                <div class="flex items-center space-x-2">
                                    @if($appointment->payment)
                                        @if($appointment->isPaid())
                                            <span class="inline-flex items-center space-x-1 px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                                <i class="fas fa-check-circle"></i>
                                                <span>Paid</span>
                                            </span>
                                        @else
                                            <a href="{{ route('payments.show', $appointment) }}" class="inline-flex items-center space-x-1 px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg hover:bg-yellow-200 transition-colors font-semibold">
                                                <i class="fas fa-credit-card"></i>
                                                <span>Pay Now (€{{ number_format($appointment->payment->amount, 2) }})</span>
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('payments.show', $appointment) }}" class="inline-flex items-center space-x-1 px-4 py-2 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 transition-colors font-semibold">
                                            <i class="fas fa-credit-card"></i>
                                            <span>Pay (€15.00)</span>
                                        </a>
                                    @endif
                                </div>

                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('payments.show', $appointment) }}" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors font-medium">
                                        <i class="fas fa-eye"></i> View Payment
                                    </a>

                                    @if($appointment->canBeCancelled() && auth()->check() && auth()->user()->isAdmin())
                                        <form action="{{ route('admin.appointments.cancel', $appointment) }}" method="POST" class="inline" onclick="return confirm('Are you sure you want to cancel this appointment?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-4 py-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors font-medium">
                                                <i class="fas fa-times"></i> Cancel
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                            @method('PATCH')
                                            <button type="submit" class="px-4 py-2 text-red-700 hover:bg-red-50 rounded-lg transition-colors font-medium">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $appointments->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-20">
            <div class="text-6xl mb-4">📅</div>
            <h2 class="text-2xl font-serif font-bold text-gray-900 mb-2">No Appointments Yet</h2>
            <p class="text-gray-600 mb-8">Start by booking your first nail appointment with us!</p>
            <a href="{{ route('book') }}" class="px-8 py-3 bg-gradient-to-r from-rose to-pink-400 text-white rounded-lg hover:shadow-lg transition-all duration-300 font-semibold inline-flex items-center">
                <i class="fas fa-calendar-plus mr-2"></i> Book Appointment Now
            </a>
        </div>
    @endif
</div>
@endsection
