@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-beige-50 to-olive-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-light text-gray-900 mb-4">Complete Your Booking</h1>
            <p class="text-xl text-gray-600">Choose your preferred date and time</p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Booking Form -->
            <div class="lg:col-span-2">
                <form action="{{ route('bookings.store') }}" method="POST" class="bg-white p-8 rounded-2xl shadow-xl" id="bookingForm">
                    @csrf

                    <!-- Pre-selected Services (if coming from home) -->
                    <div id="preSelectedServices" class="mb-8 hidden">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Your Services</h3>
                        <div id="servicesList" class="space-y-3">
                            <!-- Services will be populated here -->
                        </div>
                    </div>

                    <!-- Single Service Selection (if no pre-selection) -->
                    <div id="serviceSelectionSection" class="mb-8">
                        <label for="service_id" class="block text-lg font-medium text-gray-900 mb-4">Select Your Service</label>
                        <div class="grid md:grid-cols-2 gap-4">
                            @foreach($services as $service)
                            <div class="service-option border-2 border-gray-200 rounded-xl p-6 hover:border-olive-300 hover:shadow-lg transition-all cursor-pointer bg-white group" data-service-id="{{ $service->id }}" data-service-name="{{ $service->name }}" data-service-price="{{ $service->price }}" data-service-duration="{{ $service->duration }}">
                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 bg-olive-100 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-olive-200 transition-colors">
                                        <span class="text-xl">💅</span>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900 mb-2 group-hover:text-olive-600 transition-colors">{{ $service->name }}</h3>
                                        <p class="text-gray-600 text-sm mb-3 leading-relaxed">{{ $service->description }}</p>
                                        <div class="flex justify-between items-center">
                                            <span class="text-2xl font-bold text-olive-600">€{{ $service->price }}</span>
                                            <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">{{ $service->duration }} min</span>
                                        </div>
                                    </div>
                                    <div class="w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                                        <div class="w-3 h-3 bg-olive-600 rounded-full opacity-0 transition-opacity"></div>
                                    </div>
                                </div>
                                <input type="radio" name="service_id" value="{{ $service->id }}" class="hidden service-radio">
                            </div>
                            @endforeach
                        </div>
                        @error('service_id')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                <input type="text" name="name" id="name" required
                                       class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 text-lg focus:ring-2 focus:ring-olive-500 focus:border-olive-500 transition-all"
                                       placeholder="Enter your full name">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <input type="email" name="email" id="email" required
                                       class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 text-lg focus:ring-2 focus:ring-olive-500 focus:border-olive-500 transition-all"
                                       placeholder="your@email.com">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-6">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone" id="phone" required
                                   class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 text-lg focus:ring-2 focus:ring-olive-500 focus:border-olive-500 transition-all"
                                   placeholder="+34 600 000 000">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Date Selection -->
                    <div class="mb-8">
                        <label for="date" class="block text-lg font-medium text-gray-900 mb-4">Select Date</label>
                        <div class="relative">
                            <input type="date" name="date" id="date" 
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

                    <div id="serviceSummary">
                        <div class="space-y-3 mb-4" id="summaryServices">
                            <p class="text-gray-500 text-sm">Select a service</p>
                        </div>

                        <div id="dateTimeSummary" class="mb-4 p-3 bg-gray-50 rounded-lg hidden">
                            <div class="flex items-center space-x-2 mb-2">
                                <span class="text-sm text-gray-600">📅 Date:</span>
                                <span class="text-sm font-medium text-gray-900" id="summaryDate">-</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600">⏰ Time:</span>
                                <span class="text-sm font-medium text-gray-900" id="summaryTime">-</span>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium" id="summaryServicePrice">€0</span>
                            </div>
                            <div class="flex justify-between items-center text-lg font-bold text-gray-900 border-t pt-2">
                                <span>Total</span>
                                <span id="summaryTotal">€0</span>
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <span class="font-medium">💳 Payment:</span> €15 deposit required to confirm booking
                            </p>
                        </div>
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

        fetch(`/api/appointments/available?service_id=${selectedService}&date=${dateInput.value}`)
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
            timeSlotsContainer.innerHTML = '<div class="col-span-full text-center py-8 text-gray-500"><div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"><span class="text-2xl">⏰</span></div><p>No time slots available for this date</p><p class="text-sm mt-2">Try selecting a different date</p></div>';
            return;
        }

        timeSlotsContainer.innerHTML = '';
        availableSlots.forEach(slot => {
            const slotElement = document.createElement('button');
            slotElement.type = 'button';
            slotElement.className = 'time-slot p-4 border-2 border-green-200 bg-green-50 rounded-xl hover:border-olive-400 hover:bg-olive-100 transition-all text-center font-medium text-green-700 hover:text-olive-700 group';
            slotElement.innerHTML = `
                <div class="text-lg font-bold">${slot}</div>
                <div class="text-xs text-green-600 group-hover:text-olive-600 mt-1">Available</div>
            `;
            slotElement.dataset.time = slot;

            slotElement.addEventListener('click', function() {
                // Remove selected class from all slots
                document.querySelectorAll('.time-slot').forEach(slot => {
                    slot.classList.remove('border-olive-500', 'bg-olive-100', 'text-olive-700', 'ring-2', 'ring-olive-200');
                    slot.classList.add('border-green-200', 'bg-green-50', 'text-green-700');
                    const status = slot.querySelector('.text-xs');
                    if (status) {
                        status.textContent = 'Available';
                        status.classList.remove('text-olive-600');
                        status.classList.add('text-green-600');
                    }
                });

                // Add selected class
                this.classList.remove('border-green-200', 'bg-green-50', 'text-green-700');
                this.classList.add('border-olive-500', 'bg-olive-100', 'text-olive-700', 'ring-2', 'ring-olive-200');
                const status = this.querySelector('.text-xs');
                if (status) {
                    status.textContent = 'Selected';
                    status.classList.remove('text-green-600');
                    status.classList.add('text-olive-600');
                }
                selectedTimeInput.value = this.dataset.time;

                // Update summary with selected time
                updateSummary();
            });

            timeSlotsContainer.appendChild(slotElement);
        });
    }

    // Update summary
    function updateSummary() {
        const serviceSummary = document.getElementById('serviceSummary');
        const noServiceSelected = document.getElementById('noServiceSelected');
        const dateTimeSummary = document.getElementById('dateTimeSummary');

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

        // Update date and time if selected
        if (dateInput.value) {
            const date = new Date(dateInput.value);
            const formattedDate = date.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            document.getElementById('summaryDate').textContent = formattedDate;
        }

        if (selectedTimeInput.value) {
            document.getElementById('summaryTime').textContent = selectedTimeInput.value;
            dateTimeSummary.classList.remove('hidden');
        } else {
            dateTimeSummary.classList.add('hidden');
        }

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

    // Real-time validation
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');

    function validateField(input, validator) {
        const value = input.value.trim();
        const isValid = validator(value);

        if (value === '') {
            input.classList.remove('border-red-500', 'border-green-500');
            input.classList.add('border-gray-300');
            return;
        }

        if (isValid) {
            input.classList.remove('border-red-500', 'border-gray-300');
            input.classList.add('border-green-500');
        } else {
            input.classList.remove('border-green-500', 'border-gray-300');
            input.classList.add('border-red-500');
        }
    }

    nameInput.addEventListener('input', () => validateField(nameInput, value => value.length >= 2));
    emailInput.addEventListener('input', () => validateField(emailInput, value => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)));
    phoneInput.addEventListener('input', () => validateField(phoneInput, value => /^[\+]?[0-9\s\-\(\)]{10,}$/.test(value)));

    // Update summary when date changes
    dateInput.addEventListener('change', updateSummary);
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceOptions = document.querySelectorAll('.service-option');
    const serviceSelectionSection = document.getElementById('serviceSelectionSection');
    const preSelectedServices = document.getElementById('preSelectedServices');
    const dateInput = document.getElementById('date');
    const timeSlotsContainer = document.getElementById('timeSlots');
    const selectedTimeInput = document.getElementById('selectedTime');
    const bookingForm = document.getElementById('bookingForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');

    let selectedService = null;
    let availableSlots = [];
    let preSelectedServiceName = null;

    // Get URL parameters for pre-selected services
    const urlParams = new URLSearchParams(window.location.search);
    const services = urlParams.get('services');

    // Initialize: Check if we have pre-selected services
    if (services) {
        const serviceNames = services.split(',').map(s => decodeURIComponent(s));
        preSelectedServiceName = serviceNames[0]; // For now, take first service (multi-service is future)
        
        // Find and select this service
        const matchingOption = Array.from(serviceOptions).find(opt => 
            opt.dataset.serviceName === preSelectedServiceName
        );

        if (matchingOption) {
            selectService(matchingOption);
            
            // Hide service selection, show pre-selected view
            serviceSelectionSection.classList.add('hidden');
            preSelectedServices.classList.remove('hidden');
            
            // Build services list display
            const servicesList = document.getElementById('servicesList');
            servicesList.innerHTML = `
                <div class="flex justify-between items-start gap-2 p-4 bg-olive/5 rounded-lg border-2 border-olive-200">
                    <div>
                        <p class="font-medium text-gray-900">${matchingOption.dataset.serviceName}</p>
                        <p class="text-xs text-gray-500">${matchingOption.dataset.serviceDuration} min</p>
                    </div>
                    <p class="font-semibold text-olive">€${matchingOption.dataset.servicePrice}</p>
                </div>
            `;
        }
    }

    function selectService(option) {
        // Remove selected class from all options
        serviceOptions.forEach(opt => {
            opt.classList.remove('border-olive-500', 'bg-olive-50');
            opt.querySelector('.service-radio').checked = false;
        });

        // Add selected class to clicked option
        option.classList.add('border-olive-500', 'bg-olive-50');
        option.querySelector('.service-radio').checked = true;

        selectedService = option.dataset.serviceId;
        updateSummary();
    }

    // Service selection via click
    serviceOptions.forEach(option => {
        option.addEventListener('click', function() {
            selectService(this);
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

    // Load available time slots from API
    function loadAvailableSlots() {
        if (!selectedService || !dateInput.value) return;

        // Show loading
        timeSlotsContainer.innerHTML = '<div class="col-span-full text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-olive-600 mx-auto"></div><p class="text-gray-500 mt-2">Loading available time slots...</p></div>';

        fetch(`/api/appointments/available?service_id=${selectedService}&date=${dateInput.value}`)
            .then(response => {
                if (!response.ok) throw new Error('API error');
                return response.json();
            })
            .then(data => {
                availableSlots = data.available_slots || [];
                renderTimeSlots();
            })
            .catch(error => {
                console.error('Error loading slots:', error);
                timeSlotsContainer.innerHTML = '<div class="col-span-full text-center py-8 text-red-500"><strong>Error loading time slots</strong><p class="text-sm mt-2">Please try again</p></div>';
            });
    }

    // Render time slots
    function renderTimeSlots() {
        if (availableSlots.length === 0) {
            timeSlotsContainer.innerHTML = '<div class="col-span-full text-center py-8 text-gray-500"><div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"><span class="text-2xl">⏰</span></div><p>No time slots available for this date</p><p class="text-sm mt-2">Try selecting a different date</p></div>';
            return;
        }

        timeSlotsContainer.innerHTML = '';
        availableSlots.forEach(slot => {
            const slotElement = document.createElement('button');
            slotElement.type = 'button';
            slotElement.className = 'time-slot p-4 border-2 border-green-200 bg-green-50 rounded-xl hover:border-olive-400 hover:bg-olive-100 transition-all text-center font-medium text-green-700 hover:text-olive-700 group';
            slotElement.innerHTML = `
                <div class="text-lg font-bold">${slot}</div>
                <div class="text-xs text-green-600 group-hover:text-olive-600 mt-1">Available</div>
            `;
            slotElement.dataset.time = slot;

            slotElement.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove selected class from all slots
                document.querySelectorAll('.time-slot').forEach(s => {
                    s.classList.remove('border-olive-500', 'bg-olive-100', 'text-olive-700', 'ring-2', 'ring-olive-200');
                    s.classList.add('border-green-200', 'bg-green-50', 'text-green-700');
                    const status = s.querySelector('.text-xs');
                    if (status) {
                        status.textContent = 'Available';
                        status.classList.remove('text-olive-600');
                        status.classList.add('text-green-600');
                    }
                });

                // Add selected class
                this.classList.remove('border-green-200', 'bg-green-50', 'text-green-700');
                this.classList.add('border-olive-500', 'bg-olive-100', 'text-olive-700', 'ring-2', 'ring-olive-200');
                const status = this.querySelector('.text-xs');
                if (status) {
                    status.textContent = 'Selected';
                    status.classList.remove('text-green-600');
                    status.classList.add('text-olive-600');
                }
                selectedTimeInput.value = this.dataset.time;

                // Update summary with selected time
                updateSummary();
            });

            timeSlotsContainer.appendChild(slotElement);
        });
    }

    // Update summary
    function updateSummary() {
        const summaryServices = document.getElementById('summaryServices');
        const dateTimeSummary = document.getElementById('dateTimeSummary');

        if (!selectedService) {
            summaryServices.innerHTML = '<p class="text-gray-500 text-sm">Select a service</p>';
            return;
        }

        const selectedOption = document.querySelector(`.service-option[data-service-id="${selectedService}"]`);
        if (!selectedOption) return;

        const serviceName = selectedOption.dataset.serviceName;
        const servicePrice = selectedOption.dataset.servicePrice;
        const serviceDuration = selectedOption.dataset.serviceDuration;

        summaryServices.innerHTML = `
            <div class="flex justify-between items-start gap-2 p-3 bg-olive/5 rounded-lg">
                <div>
                    <p class="font-medium text-gray-900 text-sm">${serviceName}</p>
                    <p class="text-xs text-gray-500">${serviceDuration} min</p>
                </div>
                <p class="font-semibold text-olive">€${servicePrice}</p>
            </div>
        `;

        document.getElementById('summaryServicePrice').textContent = `€${servicePrice}`;
        document.getElementById('summaryTotal').textContent = `€${servicePrice}`;

        // Update date and time if selected
        if (dateInput.value) {
            const date = new Date(dateInput.value + 'T00:00:00');
            const formattedDate = date.toLocaleDateString('en-US', {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
            document.getElementById('summaryDate').textContent = formattedDate;
        }

        if (selectedTimeInput.value) {
            document.getElementById('summaryTime').textContent = selectedTimeInput.value;
            dateTimeSummary.classList.remove('hidden');
        } else {
            dateTimeSummary.classList.add('hidden');
        }
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

    // Real-time validation
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');

    function validateField(input, validator) {
        const value = input.value.trim();
        const isValid = validator(value);

        if (value === '') {
            input.classList.remove('border-red-500', 'border-green-500');
            input.classList.add('border-gray-300');
            return;
        }

        if (isValid) {
            input.classList.remove('border-red-500', 'border-gray-300');
            input.classList.add('border-green-500');
        } else {
            input.classList.remove('border-green-500', 'border-gray-300');
            input.classList.add('border-red-500');
        }
    }

    nameInput.addEventListener('input', () => validateField(nameInput, value => value.length >= 2));
    emailInput.addEventListener('input', () => validateField(emailInput, value => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)));
    phoneInput.addEventListener('input', () => validateField(phoneInput, value => /^[\+]?[0-9\s\-\(\)]{10,}$/.test(value)));

    // Update summary when date changes
    dateInput.addEventListener('change', updateSummary);

    // Set min date to tomorrow
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    dateInput.min = tomorrow.toISOString().split('T')[0];
    
    // Set max date to 30 days from now
    const maxDate = new Date();
    maxDate.setDate(maxDate.getDate() + 30);
    dateInput.max = maxDate.toISOString().split('T')[0];
});
</script>

@endsection