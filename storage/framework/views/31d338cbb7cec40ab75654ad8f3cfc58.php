

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-beige-50 to-olive-50">
    <!-- Hero Section -->
    <section class="relative py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h1 class="text-5xl md:text-6xl font-light text-gray-900 mb-6">
                    Book Your <span class="text-olive-600 font-medium">Appointment</span>
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Professional nail care services tailored to your style. Choose your services and book your perfect time.
                </p>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Services Selection -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                        <h2 class="text-3xl font-light text-gray-900 mb-8">Select Your Services</h2>

                        <!-- Service Categories -->
                        <div class="space-y-6">
                            <!-- Gel Category -->
                            <div class="service-category border border-gray-200 rounded-xl overflow-hidden">
                                <button class="w-full px-6 py-4 bg-gray-50 hover:bg-gray-100 transition-colors text-left flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-2xl">💅</span>
                                        <span class="text-lg font-medium text-gray-900">Gel Services</span>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div class="hidden px-6 py-4 space-y-3 bg-white">
                                    <?php $__currentLoopData = $services->where('category', 'gel'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-start space-x-4 p-4 border border-gray-200 rounded-lg hover:border-olive-300 hover:bg-olive-50 transition-all cursor-pointer group">
                                        <input type="checkbox" class="service-checkbox mt-1 w-4 h-4 text-olive-600 bg-gray-100 border-gray-300 rounded focus:ring-olive-500 focus:ring-2"
                                               data-service="<?php echo e($service->name); ?>"
                                               data-price="<?php echo e($service->price); ?>"
                                               data-duration="<?php echo e($service->duration); ?>">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-medium text-gray-900 group-hover:text-olive-600 transition-colors"><?php echo e($service->name); ?></h3>
                                            <p class="text-gray-600 text-sm mb-2"><?php echo e($service->description); ?></p>
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-500"><?php echo e($service->duration); ?> min</span>
                                                <span class="text-xl font-bold text-olive-600">€<?php echo e($service->price); ?></span>
                                            </div>
                                        </div>
                                    </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                            <!-- BIAB Category -->
                            <div class="service-category border border-gray-200 rounded-xl overflow-hidden">
                                <button class="w-full px-6 py-4 bg-gray-50 hover:bg-gray-100 transition-colors text-left flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-2xl">✨</span>
                                        <span class="text-lg font-medium text-gray-900">BIAB Services</span>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div class="hidden px-6 py-4 space-y-3 bg-white">
                                    <?php $__currentLoopData = $services->where('category', 'biab'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-start space-x-4 p-4 border border-gray-200 rounded-lg hover:border-olive-300 hover:bg-olive-50 transition-all cursor-pointer group">
                                        <input type="checkbox" class="service-checkbox mt-1 w-4 h-4 text-olive-600 bg-gray-100 border-gray-300 rounded focus:ring-olive-500 focus:ring-2"
                                               data-service="<?php echo e($service->name); ?>"
                                               data-price="<?php echo e($service->price); ?>"
                                               data-duration="<?php echo e($service->duration); ?>">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-medium text-gray-900 group-hover:text-olive-600 transition-colors"><?php echo e($service->name); ?></h3>
                                            <p class="text-gray-600 text-sm mb-2"><?php echo e($service->description); ?></p>
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-500"><?php echo e($service->duration); ?> min</span>
                                                <span class="text-xl font-bold text-olive-600">€<?php echo e($service->price); ?></span>
                                            </div>
                                        </div>
                                    </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                            <!-- Soft Gel Category -->
                            <div class="service-category border border-gray-200 rounded-xl overflow-hidden">
                                <button class="w-full px-6 py-4 bg-gray-50 hover:bg-gray-100 transition-colors text-left flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-2xl">🌸</span>
                                        <span class="text-lg font-medium text-gray-900">Soft Gel Services</span>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div class="hidden px-6 py-4 space-y-3 bg-white">
                                    <?php $__currentLoopData = $services->where('category', 'soft_gel'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-start space-x-4 p-4 border border-gray-200 rounded-lg hover:border-olive-300 hover:bg-olive-50 transition-all cursor-pointer group">
                                        <input type="checkbox" class="service-checkbox mt-1 w-4 h-4 text-olive-600 bg-gray-100 border-gray-300 rounded focus:ring-olive-500 focus:ring-2"
                                               data-service="<?php echo e($service->name); ?>"
                                               data-price="<?php echo e($service->price); ?>"
                                               data-duration="<?php echo e($service->duration); ?>">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-medium text-gray-900 group-hover:text-olive-600 transition-colors"><?php echo e($service->name); ?></h3>
                                            <p class="text-gray-600 text-sm mb-2"><?php echo e($service->description); ?></p>
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-500"><?php echo e($service->duration); ?> min</span>
                                                <span class="text-xl font-bold text-olive-600">€<?php echo e($service->price); ?></span>
                                            </div>
                                        </div>
                                    </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                            <!-- Polish Category -->
                            <div class="service-category border border-gray-200 rounded-xl overflow-hidden">
                                <button class="w-full px-6 py-4 bg-gray-50 hover:bg-gray-100 transition-colors text-left flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-2xl">🎨</span>
                                        <span class="text-lg font-medium text-gray-900">Polish Services</span>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div class="hidden px-6 py-4 space-y-3 bg-white">
                                    <?php $__currentLoopData = $services->where('category', 'polish'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-start space-x-4 p-4 border border-gray-200 rounded-lg hover:border-olive-300 hover:bg-olive-50 transition-all cursor-pointer group">
                                        <input type="checkbox" class="service-checkbox mt-1 w-4 h-4 text-olive-600 bg-gray-100 border-gray-300 rounded focus:ring-olive-500 focus:ring-2"
                                               data-service="<?php echo e($service->name); ?>"
                                               data-price="<?php echo e($service->price); ?>"
                                               data-duration="<?php echo e($service->duration); ?>">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-medium text-gray-900 group-hover:text-olive-600 transition-colors"><?php echo e($service->name); ?></h3>
                                            <p class="text-gray-600 text-sm mb-2"><?php echo e($service->description); ?></p>
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-500"><?php echo e($service->duration); ?> min</span>
                                                <span class="text-xl font-bold text-olive-600">€<?php echo e($service->price); ?></span>
                                            </div>
                                        </div>
                                    </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                            <!-- Add-ons Category -->
                            <div class="service-category border border-gray-200 rounded-xl overflow-hidden">
                                <button class="w-full px-6 py-4 bg-gray-50 hover:bg-gray-100 transition-colors text-left flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-2xl">✨</span>
                                        <span class="text-lg font-medium text-gray-900">Add-ons</span>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div class="hidden px-6 py-4 space-y-3 bg-white">
                                    <?php $__currentLoopData = $services->where('category', 'addon'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-start space-x-4 p-4 border border-gray-200 rounded-lg hover:border-olive-300 hover:bg-olive-50 transition-all cursor-pointer group">
                                        <input type="checkbox" class="service-checkbox mt-1 w-4 h-4 text-olive-600 bg-gray-100 border-gray-300 rounded focus:ring-olive-500 focus:ring-2"
                                               data-service="<?php echo e($service->name); ?>"
                                               data-price="<?php echo e($service->price); ?>"
                                               data-duration="<?php echo e($service->duration); ?>">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-medium text-gray-900 group-hover:text-olive-600 transition-colors"><?php echo e($service->name); ?></h3>
                                            <p class="text-gray-600 text-sm mb-2"><?php echo e($service->description); ?></p>
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-500"><?php echo e($service->duration); ?> min</span>
                                                <span class="text-xl font-bold text-olive-600">€<?php echo e($service->price); ?></span>
                                            </div>
                                        </div>
                                    </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cart Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-xl p-6 sticky top-6">
                        <h3 class="text-xl font-medium text-gray-900 mb-6">Your Selection</h3>

                        <!-- Selected Services -->
                        <div class="mb-6">
                            <div id="selectedServices" class="space-y-3">
                                <p class="text-gray-500 text-sm">Select services to begin</p>
                            </div>
                        </div>

                        <!-- Summary -->
                        <div class="border-t border-gray-200 pt-6">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-gray-600">Total Duration</span>
                                <span class="text-lg font-semibold text-gray-900" id="totalDuration">0 min</span>
                            </div>
                            <div class="flex justify-between items-center mb-6">
                                <span class="text-gray-600">Total Price</span>
                                <span class="text-2xl font-bold text-olive" id="totalPrice">€0</span>
                            </div>

                            <!-- CTA - Clean and Simple -->
                            <a href="#" id="bookNowBtn" class="w-full bg-olive hover:bg-olive-700 text-white font-semibold py-4 px-6 rounded-xl transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none inline-block text-center cursor-pointer" onclick="return handleBookingClick(event)" style="opacity: 0.5; pointer-events: none;" aria-disabled="true">
                                ✨ Book Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Form Section -->
    <section id="bookingForm" class="py-20 px-4 sm:px-6 lg:px-8 bg-white hidden">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-3xl font-light text-gray-900 mb-8 text-center">Complete Your Booking</h2>

                <form method="POST" action="<?php echo e(route('bookings.store')); ?>" class="space-y-8">
                    <?php echo csrf_field(); ?>

                    <!-- Selected Services Summary -->
                    <div class="bg-olive/5 rounded-xl p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Selected Services</h3>
                        <div id="bookingSelectedServices" class="space-y-2">
                            <!-- Services will be populated by JavaScript -->
                        </div>
                        <div class="border-t border-gray-200 mt-4 pt-4 flex justify-between items-center">
                            <span class="text-gray-600 font-medium">Total</span>
                            <span class="text-xl font-bold text-olive" id="bookingTotalPrice">€0</span>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="name" id="name" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-olive focus:border-transparent transition-colors"
                                   placeholder="Your full name">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="email" id="email" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-olive focus:border-transparent transition-colors"
                                   placeholder="your@email.com">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                            <input type="tel" name="phone" id="phone" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-olive focus:border-transparent transition-colors"
                                   placeholder="+353 XX XXX XXXX">
                        </div>
                        <div>
                            <label for="preferred_contact" class="block text-sm font-medium text-gray-700 mb-2">Preferred Contact Method</label>
                            <select name="preferred_contact" id="preferred_contact"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-olive focus:border-transparent transition-colors">
                                <option value="email">Email</option>
                                <option value="phone">Phone</option>
                                <option value="whatsapp">WhatsApp</option>
                            </select>
                        </div>
                    </div>

                    <!-- Date & Time Selection -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">Preferred Date *</label>
                            <input type="date" name="appointment_date" id="appointment_date" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-olive focus:border-transparent transition-colors"
                                   min="<?php echo e(\Carbon\Carbon::tomorrow()->format('Y-m-d')); ?>">
                        </div>
                        <div>
                            <label for="appointment_time" class="block text-sm font-medium text-gray-700 mb-2">Preferred Time *</label>
                            <select name="appointment_time" id="appointment_time" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-olive focus:border-transparent transition-colors">
                                <option value="">Select a time</option>
                                <!-- Time slots will be populated by JavaScript -->
                            </select>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Special Requests or Notes</label>
                        <textarea name="notes" id="notes" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-olive focus:border-transparent transition-colors resize-none"
                                  placeholder="Any special requests, allergies, or notes for your appointment..."></textarea>
                    </div>

                    <!-- Terms and Payment Info -->
                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="flex items-start space-x-3 mb-4">
                            <input type="checkbox" name="terms" id="terms" required
                                   class="mt-1 w-4 h-4 text-olive bg-gray-100 border-gray-300 rounded focus:ring-olive focus:ring-2">
                            <label for="terms" class="text-sm text-gray-700">
                                I agree to the <a href="<?php echo e(route('policies')); ?>" class="text-olive hover:underline">Terms & Conditions</a> and
                                <a href="<?php echo e(route('policies')); ?>" class="text-olive hover:underline">Privacy Policy</a> *
                            </label>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <h4 class="font-medium text-gray-900 mb-2">Payment Information</h4>
                            <p class="text-sm text-gray-600 mb-2">
                                A €15 deposit is required to confirm your appointment. The remaining balance will be collected at your appointment.
                            </p>
                            <p class="text-sm text-gray-600">
                                You will be redirected to our secure payment page after submitting this form.
                            </p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="bg-olive hover:bg-olive-700 text-white font-semibold py-4 px-12 rounded-xl transition-all transform hover:scale-105 inline-flex items-center space-x-2">
                            <span>Confirm Booking</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceCategoryButtons = document.querySelectorAll('.service-category');
    const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
    const selectedServicesDiv = document.getElementById('selectedServices');
    const totalDurationEl = document.getElementById('totalDuration');
    const totalPriceEl = document.getElementById('totalPrice');
    const bookNowBtn = document.getElementById('bookNowBtn');

    let selectedServices = {};

    // Toggle service category
    serviceCategoryButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const options = this.nextElementSibling;
            const icon = this.querySelector('svg');

            options.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        });
    });

    // Handle service selection
    serviceCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.closest('label');
            const serviceName = label.dataset.service;
            const price = parseFloat(label.dataset.price);
            const duration = parseInt(label.dataset.duration);

            if (this.checked) {
                selectedServices[serviceName] = { price, duration };
            } else {
                delete selectedServices[serviceName];
            }

            updateSummary();
        });
    });

    function updateSummary() {
        // Update selected services display
        if (Object.keys(selectedServices).length === 0) {
            selectedServicesDiv.innerHTML = '<p class="text-gray-500 text-sm">Select services to begin</p>';
            bookNowBtn.style.opacity = '0.5';
            bookNowBtn.style.pointerEvents = 'none';
            bookNowBtn.setAttribute('aria-disabled', 'true');
        } else {
            selectedServicesDiv.innerHTML = Object.entries(selectedServices).map(([name, data]) => `
                <div class="flex justify-between items-start gap-2 p-3 bg-olive/5 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900 text-sm">${name}</p>
                        <p class="text-xs text-gray-500">${data.duration} min</p>
                    </div>
                    <p class="font-semibold text-olive">€${data.price}</p>
                </div>
            `).join('');
            bookNowBtn.style.opacity = '1';
            bookNowBtn.style.pointerEvents = 'auto';
            bookNowBtn.setAttribute('aria-disabled', 'false');
        }

        // Calculate totals
        const totalDuration = Object.values(selectedServices).reduce((sum, s) => sum + s.duration, 0);
        const totalPrice = Object.values(selectedServices).reduce((sum, s) => sum + s.price, 0);

        totalDurationEl.textContent = `${totalDuration} min`;
        totalPriceEl.textContent = `€${totalPrice}`;
    }

    // Book button - show booking form
    window.handleBookingClick = function(e) {
        e.preventDefault();
        const selectedServices = {};

        document.querySelectorAll('.service-checkbox:checked').forEach(checkbox => {
            const label = checkbox.closest('label');
            if (label) {
                selectedServices[label.dataset.service] = {
                    price: label.dataset.price,
                    duration: label.dataset.duration
                };
            }
        });

        if (Object.keys(selectedServices).length === 0) {
            alert('Please select at least one service');
            return false;
        }

        // Populate booking form with selected services
        populateBookingForm(selectedServices);

        // Hide services section and show booking form
        document.querySelector('section.pb-20').style.display = 'none';
        document.getElementById('bookingForm').classList.remove('hidden');

        // Scroll to booking form
        document.getElementById('bookingForm').scrollIntoView({ behavior: 'smooth' });

        return false;
    };

    function populateBookingForm(services) {
        const bookingServicesDiv = document.getElementById('bookingSelectedServices');
        const bookingTotalPriceEl = document.getElementById('bookingTotalPrice');

        bookingServicesDiv.innerHTML = Object.entries(services).map(([name, data]) => `
            <div class="flex justify-between items-center">
                <span class="text-gray-700">${name}</span>
                <span class="font-medium text-olive">€${data.price}</span>
            </div>
        `).join('');

        const totalPrice = Object.values(services).reduce((sum, s) => sum + parseFloat(s.price), 0);
        bookingTotalPriceEl.textContent = `€${totalPrice}`;

        // Store services data for form submission
        const servicesInput = document.createElement('input');
        servicesInput.type = 'hidden';
        servicesInput.name = 'services';
        servicesInput.value = JSON.stringify(services);
        document.querySelector('form').appendChild(servicesInput);
    }

    // Handle date change to load available time slots
    document.getElementById('appointment_date').addEventListener('change', function() {
        const selectedDate = this.value;
        if (selectedDate) {
            loadAvailableSlots(selectedDate);
        }
    });

    function loadAvailableSlots(date) {
        const timeSelect = document.getElementById('appointment_time');
        timeSelect.innerHTML = '<option value="">Loading...</option>';

        fetch(`/api/appointments/available?date=${date}`)
            .then(response => response.json())
            .then(data => {
                timeSelect.innerHTML = '<option value="">Select a time</option>';

                if (data.slots && data.slots.length > 0) {
                    data.slots.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot.time;
                        option.textContent = slot.time;
                        timeSelect.appendChild(option);
                    });
                } else {
                    timeSelect.innerHTML = '<option value="">No slots available</option>';
                }
            })
            .catch(error => {
                console.error('Error loading slots:', error);
                timeSelect.innerHTML = '<option value="">Error loading slots</option>';
            });
    }
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\delphina\resources\views/booking/create.blade.php ENDPATH**/ ?>