@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-beige-50 to-olive-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-light text-gray-900 mb-4">Book Your Appointment</h1>
            <p class="text-xl text-gray-600">Choose your service and preferred time</p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Booking Form -->
            <div class="lg:col-span-2">
                <form action="{{ route('booking.store') }}" method="POST" class="bg-white p-8 rounded-2xl shadow-xl" id="bookingForm">
                    @csrf

                    <!-- Service Selection -->
                    <div class="mb-8">
                        <label for="service_id" class="block text-lg font-medium text-gray-900 mb-4">Select Your Service</label>
                        <div class="grid md:grid-cols-2 gap-4">
                            @foreach($services as $service)
                            <div class="service-option border-2 border-gray-200 rounded-xl p-6 hover:border-olive-300 hover:shadow-lg transition-all cursor-pointer" data-service-id="{{ $service->id }}">
                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 bg-olive-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-xl">💅</span>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $service->name }}</h3>
                                        <p class="text-gray-600 text-sm mb-3 leading-relaxed">{{ $service->description }}</p>
                                        <div class="flex justify-between items-center">
                                            <span class="text-2xl font-bold text-olive-600">€{{ $service->price }}</span>
                                            <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $service->duration }} min</span>
                                        </div>
                                    </div>
                                </div>
                                <input type="radio" name="service_id" value="{{ $service->id }}" class="hidden service-radio" required>
                            </div>
                            @endforeach
                        </div>
                        @error('service_id')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date Selection -->
                    <div class="mb-8">
                        <label for="date" class="block text-lg font-medium text-gray-900 mb-4">Select Date</label>
                        <div class="relative">
                            <input type="date" name="date" id="date" min="{{ date('Y-m-d', strtotime('+1 day')) }}" max="{{ date('Y-m-d', strtotime('+30 days')) }}"
                                   class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 text-lg focus:ring-2 focus:ring-olive-500 focus:border-olive-500 transition-all"
                                   required>
                            <div class="absolute right-3 top-3 text-gray-400">
                                📅
                            </div>
                        </div>
                        @error('date')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Time Selection -->
                    <div class="mb-8">
                        <label for="time" class="block text-lg font-medium text-gray-900 mb-4">Select Time</label>
                        <div id="timeSlots" class="grid grid-cols-3 md:grid-cols-4 gap-3">
                            <!-- Time slots will be populated by JavaScript -->
                        </div>
                        <input type="hidden" name="time" id="selectedTime" required>
                        @error('time')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submitBtn" class="w-full bg-olive-600 text-white py-4 px-6 rounded-xl hover:bg-olive-700 transition-all transform hover:scale-105 shadow-lg font-medium text-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                        <span class="flex items-center justify-center">
                            <span id="btnText">Continue to Payment</span>
                            <svg id="btnSpinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                </form>
            </div>

            <!-- Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-2xl shadow-xl sticky top-6">
                    <h3 class="text-xl font-medium text-gray-900 mb-6">Booking Summary</h3>

                    <div id="serviceSummary" class="hidden">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-olive-100 rounded-full flex items-center justify-center">
                                <span class="text-lg">💅</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900" id="summaryServiceName">-</p>
                                <p class="text-sm text-gray-500" id="summaryServiceDuration">-</p>
                            </div>
                        </div>
                        <div class="border-t pt-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Service</span>
                                <span class="font-medium" id="summaryServicePrice">€0</span>
                            </div>
                            <div class="flex justify-between items-center text-lg font-bold text-gray-900 border-t pt-2">
                                <span>Total</span>
                                <span id="summaryTotal">€0</span>
                            </div>
                        </div>
                    </div>

                    <div id="noServiceSelected" class="text-center text-gray-500">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">📅</span>
                        </div>
                        <p>Select a service to see the summary</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceOptions = document.querySelectorAll('.service-option');
    const dateInput = document.getElementById('date');
    const timeSlotsContainer = document.getElementById('timeSlots');
    const selectedTimeInput = document.getElementById('selectedTime');
    const bookingForm = document.getElementById('bookingForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');

    let selectedService = null;
    let availableSlots = [];

    // Service selection
    serviceOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected class from all options
            serviceOptions.forEach(opt => {
                opt.classList.remove('border-olive-500', 'bg-olive-50');
                opt.querySelector('.service-radio').checked = false;
            });

            // Add selected class to clicked option
            this.classList.add('border-olive-500', 'bg-olive-50');
            this.querySelector('.service-radio').checked = true;

            selectedService = this.dataset.serviceId;
            updateSummary();
            if (dateInput.value) {
                loadAvailableSlots();
            }
        });
    });

    // Date change
    dateInput.addEventListener('change', function() {
        if (selectedService) {
            loadAvailableSlots();
        }
    });

    // Load available time slots
    function loadAvailableSlots() {
        if (!selectedService || !dateInput.value) return;

        // Show loading
        timeSlotsContainer.innerHTML = '<div class="col-span-full text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-olive-600 mx-auto"></div><p class="text-gray-500 mt-2">Loading available time slots...</p></div>';

        fetch(`/api/services/${selectedService}/available-slots?date=${dateInput.value}`)
            .then(response => response.json())
            .then(data => {
                availableSlots = data.available_slots || [];
                renderTimeSlots();
            })
            .catch(error => {
                console.error('Error loading slots:', error);
                timeSlotsContainer.innerHTML = '<div class="col-span-full text-center py-8 text-red-500">Error loading available time slots</div>';
            });
    }

    // Render time slots
    function renderTimeSlots() {
        if (availableSlots.length === 0) {
            timeSlotsContainer.innerHTML = '<div class="col-span-full text-center py-8 text-gray-500">No time slots available for this date</div>';
            return;
        }

        timeSlotsContainer.innerHTML = '';
        availableSlots.forEach(slot => {
            const slotElement = document.createElement('button');
            slotElement.type = 'button';
            slotElement.className = 'time-slot p-3 border-2 border-gray-200 rounded-lg hover:border-olive-300 hover:bg-olive-50 transition-all text-center font-medium';
            slotElement.textContent = slot;
            slotElement.dataset.time = slot;

            slotElement.addEventListener('click', function() {
                // Remove selected class from all slots
                document.querySelectorAll('.time-slot').forEach(slot => {
                    slot.classList.remove('border-olive-500', 'bg-olive-100', 'text-olive-700');
                });

                // Add selected class
                this.classList.add('border-olive-500', 'bg-olive-100', 'text-olive-700');
                selectedTimeInput.value = this.dataset.time;
            });

            timeSlotsContainer.appendChild(slotElement);
        });
    }

    // Update summary
    function updateSummary() {
        const serviceSummary = document.getElementById('serviceSummary');
        const noServiceSelected = document.getElementById('noServiceSelected');

        if (!selectedService) {
            serviceSummary.classList.add('hidden');
            noServiceSelected.classList.remove('hidden');
            return;
        }

        const selectedOption = document.querySelector(`.service-option[data-service-id="${selectedService}"]`);
        if (!selectedOption) return;

        const serviceName = selectedOption.querySelector('h3').textContent;
        const servicePrice = selectedOption.querySelector('.text-2xl').textContent;
        const serviceDuration = selectedOption.querySelector('.text-sm').textContent;

        document.getElementById('summaryServiceName').textContent = serviceName;
        document.getElementById('summaryServicePrice').textContent = servicePrice;
        document.getElementById('summaryServiceDuration').textContent = serviceDuration;
        document.getElementById('summaryTotal').textContent = servicePrice;

        serviceSummary.classList.remove('hidden');
        noServiceSelected.classList.add('hidden');
    }

    // Form submission
    bookingForm.addEventListener('submit', function(e) {
        if (!selectedService || !selectedTimeInput.value) {
            e.preventDefault();
            alert('Please select a service and time slot');
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        btnText.textContent = 'Processing...';
        btnSpinner.classList.remove('hidden');
    });
});
</script>
@endsection