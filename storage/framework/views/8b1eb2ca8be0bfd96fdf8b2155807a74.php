

<?php $__env->startSection('title', 'Book Appointment - Nail Art Studio'); ?>
<?php $__env->startSection('description', 'Book your appointment online at our nail studio. Choose your service and preferred time easily and intuitively.'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-pink-50 via-white to-purple-50 py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-5xl md:text-6xl font-light text-gray-900 mb-4">Book Your Appointment</h1>
            <p class="text-2xl text-gray-600 font-light">Choose your service and preferred time</p>
        </div>

        <div class="grid lg:grid-cols-3 gap-12">
            <!-- Services Selection -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-xl p-8 sticky top-8">
                    <h2 class="text-2xl font-medium text-gray-900 mb-6 text-center">Select Your Service</h2>

                    <div class="space-y-4">
                        <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="service-option border-2 border-gray-200 rounded-2xl p-6 hover:border-pink-300 hover:shadow-lg transition-all cursor-pointer group" data-service-id="<?php echo e($service->id); ?>">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-pink-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:from-pink-200 group-hover:to-purple-200 transition-all">
                                    <span class="text-2xl">💅</span>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2"><?php echo e($service->name); ?></h3>
                                <p class="text-gray-600 text-sm mb-3 leading-relaxed"><?php echo e($service->description); ?></p>
                                <div class="text-xl font-bold text-pink-600 mb-2">€<?php echo e($service->price); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($service->duration); ?> min</div>
                            </div>
                            <input type="radio" name="service_id" value="<?php echo e($service->id); ?>" class="hidden service-radio" required>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <?php $__errorArgs = ['service_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-4 text-center"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Calendar & Time Selection -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-xl p-8">
                    <h2 class="text-2xl font-medium text-gray-900 mb-8 text-center">Choose Date and Time</h2>

                    <form action="<?php echo e(route('bookings.store')); ?>" method="POST" id="bookingForm">
                        <?php echo csrf_field(); ?>

                        <!-- Date Selection -->
                        <div class="mb-8">
                            <label for="date" class="block text-xl font-medium text-gray-900 mb-6 text-center">Which day works best for you?</label>
                            <div class="grid grid-cols-7 gap-2 mb-6">
                                <?php for($i = 0; $i < 21; $i++): ?>
                                    <?php
                                        $date = \Carbon\Carbon::now()->addDays($i + 1);
                                        $isToday = $date->isToday();
                                        $isTomorrow = $date->isTomorrow();
                                        $isWeekend = $date->isWeekend();
                                    ?>
                                    <button type="button" class="date-option p-4 border-2 border-gray-200 rounded-xl hover:border-pink-300 hover:bg-pink-50 transition-all text-center <?php echo e($isWeekend ? 'bg-purple-50 border-purple-200' : ''); ?>"
                                            data-date="<?php echo e($date->format('Y-m-d')); ?>">
                                        <div class="text-sm font-medium text-gray-600"><?php echo e($date->locale('en')->isoFormat('ddd')); ?></div>
                                        <div class="text-lg font-bold text-gray-900"><?php echo e($date->format('d')); ?></div>
                                        <div class="text-xs text-gray-500">
                                            <?php if($isTomorrow): ?>
                                                Tomorrow
                                            <?php elseif($isToday): ?>
                                                Today
                                            <?php else: ?>
                                                <?php echo e($date->locale('en')->isoFormat('MMM')); ?>

                                            <?php endif; ?>
                                        </div>
                                    </button>
                                <?php endfor; ?>
                            </div>
                            <input type="hidden" name="date" id="selectedDate" required>
                            <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm mt-2 text-center"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Time Selection -->
                        <div class="mb-8">
                            <label class="block text-xl font-medium text-gray-900 mb-6 text-center">What time do you prefer?</label>
                            <div id="timeSlots" class="grid grid-cols-4 gap-3">
                                <div class="col-span-full text-center text-gray-500 py-8">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span class="text-2xl">📅</span>
                                    </div>
                                    <p>Select a service and date first</p>
                                </div>
                            </div>
                            <input type="hidden" name="time" id="selectedTime" required>
                            <?php $__errorArgs = ['time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-sm mt-2 text-center"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Booking Summary -->
                        <div id="bookingSummary" class="hidden bg-gradient-to-r from-pink-50 to-purple-50 rounded-2xl p-6 mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 text-center">Booking Summary</h3>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="text-center">
                                    <div class="text-sm text-gray-600 mb-1">Service</div>
                                    <div class="font-medium text-gray-900" id="summaryService">-</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-sm text-gray-600 mb-1">Date & Time</div>
                                    <div class="font-medium text-gray-900" id="summaryDateTime">-</div>
                                </div>
                            </div>
                            <div class="text-center mt-4 pt-4 border-t border-gray-200">
                                <div class="text-2xl font-bold text-pink-600" id="summaryPrice">€0</div>
                            </div>
                        <!-- Guest Information (only for non-authenticated users) -->
                        <?php if(auth()->guard()->guest()): ?>
                        <div class="bg-gray-50 rounded-2xl p-6 mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 text-center">Your Information</h3>
                            <div class="grid md:grid-cols-3 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                    <input type="text" name="name" id="name" value="<?php echo e(old('name')); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent" required>
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent" required>
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                    <input type="tel" name="phone" id="phone" value="<?php echo e(old('phone')); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent" required>
                                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" id="submitBtn" class="bg-gradient-to-r from-pink-500 to-purple-600 text-white px-12 py-4 rounded-full text-xl font-medium hover:from-pink-600 hover:to-purple-700 transition-all transform hover:scale-105 shadow-2xl disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                                <span id="btnText">Confirm Booking</span>
                                <svg id="btnSpinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceOptions = document.querySelectorAll('.service-option');
    const dateOptions = document.querySelectorAll('.date-option');
    const timeSlotsContainer = document.getElementById('timeSlots');
    const selectedDateInput = document.getElementById('selectedDate');
    const selectedTimeInput = document.getElementById('selectedTime');
    const bookingSummary = document.getElementById('bookingSummary');
    const bookingForm = document.getElementById('bookingForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');

    let selectedService = null;
    let selectedDate = null;
    let availableSlots = [];

    // Service selection
    serviceOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected class from all options
            serviceOptions.forEach(opt => {
                opt.classList.remove('border-pink-500', 'bg-pink-50');
                opt.querySelector('.service-radio').checked = false;
            });

            // Add selected class
            this.classList.add('border-pink-500', 'bg-pink-50');
            this.querySelector('.service-radio').checked = true;

            selectedService = this.dataset.serviceId;

            // Load available times if date is selected
            if (selectedDate) {
                loadAvailableTimes();
            }

            updateSummary();
        });
    });

    // Date selection
    dateOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove selected class from all options
            dateOptions.forEach(opt => opt.classList.remove('border-pink-500', 'bg-pink-100'));

            // Add selected class
            this.classList.add('border-pink-500', 'bg-pink-100');
            selectedDate = this.dataset.date;
            selectedDateInput.value = selectedDate;

            // Load available times if service is selected
            if (selectedService) {
                loadAvailableTimes();
            }

            updateSummary();
        });
    });

    // Load available time slots
    function loadAvailableTimes() {
        if (!selectedService || !selectedDate) return;

        // Show loading
        timeSlotsContainer.innerHTML = `
            <div class="col-span-full text-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-pink-600 mx-auto mb-4"></div>
                <p class="text-gray-500">Loading available times...</p>
            </div>
        `;

        fetch(`/api/appointments/available?service_id=${selectedService}&date=${selectedDate}`)
            .then(response => response.json())
            .then(data => {
                availableSlots = data.available_slots || [];
                renderTimeSlots();
            })
            .catch(error => {
                console.error('Error loading slots:', error);
                timeSlotsContainer.innerHTML = `
                    <div class="col-span-full text-center py-8 text-red-500">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">❌</span>
                        </div>
                        <p>Error loading available times</p>
                    </div>
                `;
            });
    }

    // Render time slots
    function renderTimeSlots() {
        if (availableSlots.length === 0) {
            timeSlotsContainer.innerHTML = `
                <div class="col-span-full text-center py-8 text-gray-500">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">⏰</span>
                    </div>
                    <p>No times available for this date</p>
                    <p class="text-sm mt-2">Try another date</p>
                </div>
            `;
            return;
        }

        timeSlotsContainer.innerHTML = '';
        availableSlots.forEach(slot => {
            const slotElement = document.createElement('button');
            slotElement.type = 'button';
            slotElement.className = 'time-slot p-4 border-2 border-gray-200 rounded-xl hover:border-pink-300 hover:bg-pink-50 transition-all text-center font-medium';
            slotElement.textContent = slot;
            slotElement.dataset.time = slot;

            slotElement.addEventListener('click', function() {
                // Remove selected class from all slots
                document.querySelectorAll('.time-slot').forEach(slot => {
                    slot.classList.remove('border-pink-500', 'bg-pink-100', 'text-pink-700');
                });

                // Add selected class
                this.classList.add('border-pink-500', 'bg-pink-100', 'text-pink-700');
                selectedTimeInput.value = this.dataset.time;

                updateSummary();
            });

            timeSlotsContainer.appendChild(slotElement);
        });
    }

    // Update booking summary
    function updateSummary() {
        if (!selectedService || !selectedDate || !selectedTimeInput.value) {
            bookingSummary.classList.add('hidden');
            return;
        }

        const selectedServiceOption = document.querySelector(`.service-option[data-service-id="${selectedService}"]`);
        if (!selectedServiceOption) return;

        const serviceName = selectedServiceOption.querySelector('h3').textContent;
        const servicePrice = selectedServiceOption.querySelector('.text-xl').textContent;

        // Format date
        const dateObj = new Date(selectedDate);
        const formattedDate = dateObj.toLocaleDateString('es-ES', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        document.getElementById('summaryService').textContent = serviceName;
        document.getElementById('summaryDateTime').textContent = `${formattedDate} a las ${selectedTimeInput.value}`;
        document.getElementById('summaryPrice').textContent = servicePrice;

        bookingSummary.classList.remove('hidden');
    }

    // Form submission
    bookingForm.addEventListener('submit', function(e) {
        if (!selectedService || !selectedDate || !selectedTimeInput.value) {
            e.preventDefault();
            alert('Please select a service, date and time');
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        btnText.textContent = 'Booking...';
        btnSpinner.classList.remove('hidden');
    });
});
</script>
<?php $__env->stopSection(); ?>
</content>
<parameter name="filePath">c:\xampp\htdocs\delphina\nombre-proyecto\resources\views\booking\index.blade.php
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\delphina\resources\views/booking/index.blade.php ENDPATH**/ ?>