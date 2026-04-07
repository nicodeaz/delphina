

<?php
    $oldServicesPayload = json_decode(old('services', '{}'), true);
    $oldServiceNames = is_array($oldServicesPayload) ? array_keys($oldServicesPayload) : [];
    $selectedServiceIds = $services
        ->filter(fn ($service) => in_array($service->name, $oldServiceNames, true))
        ->pluck('id')
        ->map(fn ($id) => (int) $id)
        ->values()
        ->all();

    if (empty($selectedServiceIds) && request()->filled('service_id')) {
        $selectedServiceIds = [(int) request('service_id')];
    }

    $selectedServiceId = (int) old('service_id', request('service_id', 0));
    $initialDate = old('appointment_date', request('date'));
    $initialTime = old('appointment_time');
?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-beige-50 via-white to-olive-50">
    <section class="px-4 pb-10 pt-16 sm:px-6 lg:px-8 lg:pt-20">
        <div class="mx-auto max-w-7xl">
            <div class="text-center">
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-olive">Delphina booking experience</p>
                <h1 class="mt-4 text-4xl font-semibold text-brand-charcoal md:text-6xl">Reserve your next nail appointment</h1>
                <p class="mx-auto mt-5 max-w-3xl text-base text-gray-600 md:text-lg">Choose your treatment, explore available dates in a visual calendar, tap a time slot, and confirm your details in one polished flow.</p>
            </div>

            <?php if($errors->any()): ?>
                <div class="mx-auto mt-8 max-w-3xl rounded-3xl border border-red-200 bg-red-50 px-6 py-5 text-sm text-red-700 shadow-sm">
                    <p class="font-semibold">Please review the highlighted fields.</p>
                    <ul class="mt-3 list-disc space-y-1 pl-5">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="mt-12 grid gap-8 xl:grid-cols-[minmax(0,1fr),360px]">
                <div class="rounded-[2rem] bg-white p-5 shadow-xl ring-1 ring-stone-100 md:p-8">
                    <div class="grid gap-3 border-b border-stone-100 pb-8 md:grid-cols-4">
                        <?php $__currentLoopData = [
                            1 => 'Service',
                            2 => 'Date',
                            3 => 'Time',
                            4 => 'Details',
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button type="button" class="js-step-indicator flex items-center gap-3 rounded-2xl border border-stone-200 px-4 py-4 text-left transition" data-step="<?php echo e($index); ?>">
                                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-stone-100 text-sm font-semibold text-gray-600"><?php echo e($index); ?></span>
                                <div>
                                    <p class="text-xs uppercase tracking-[0.2em] text-gray-400">Step <?php echo e($index); ?></p>
                                    <p class="text-sm font-semibold text-gray-900"><?php echo e($label); ?></p>
                                </div>
                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <form method="POST" action="<?php echo e(route('bookings.store')); ?>" id="bookingWizardForm" class="pt-8">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="service_id" id="service_id" value="<?php echo e($selectedServiceId ?: ''); ?>">
                        <input type="hidden" name="services" id="services_payload" value="<?php echo e(old('services')); ?>">
                        <input type="hidden" name="appointment_date" id="appointment_date" value="<?php echo e($initialDate); ?>">
                        <input type="hidden" name="appointment_time" id="appointment_time" value="<?php echo e($initialTime); ?>">

                        <div class="space-y-8">
                            <section class="js-step-panel" data-step-panel="1">
                                <div class="mb-6">
                                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-olive">Step 1</p>
                                    <h2 class="mt-2 text-2xl font-semibold text-gray-900">Select your services</h2>
                                    <p class="mt-2 text-sm text-gray-500">Choose one or more treatments to combine into a single booking session. Trial consultation is available in the list.</p>
                                </div>

                                <?php echo $__env->make('partials.service-selector', [
                                    'selectorId' => 'bookingServiceSelector',
                                    'inputName' => 'service_selector',
                                    'selectionMode' => 'multiple',
                                    'selectedServiceIds' => $selectedServiceIds,
                                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                                <div class="mt-8 flex justify-end">
                                    <button type="button" class="js-next-step inline-flex items-center rounded-2xl bg-olive px-6 py-3 font-semibold text-white transition hover:bg-green-700" data-next-step="2">
                                        Continue to date
                                    </button>
                                </div>
                            </section>

                            <section class="js-step-panel hidden" data-step-panel="2">
                                <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                                    <div>
                                        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-olive">Step 2</p>
                                        <h2 class="mt-2 text-2xl font-semibold text-gray-900">Pick your date</h2>
                                        <p class="mt-2 text-sm text-gray-500">Browse a monthly calendar and select one of the available days.</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <button type="button" id="calendarPrevMonth" class="rounded-2xl border border-stone-200 px-4 py-3 text-sm font-medium text-gray-700 transition hover:border-olive hover:text-olive">Previous</button>
                                        <div id="calendarMonthLabel" class="min-w-[160px] text-center text-sm font-semibold text-gray-900"></div>
                                        <button type="button" id="calendarNextMonth" class="rounded-2xl border border-stone-200 px-4 py-3 text-sm font-medium text-gray-700 transition hover:border-olive hover:text-olive">Next</button>
                                    </div>
                                </div>

                                <div class="rounded-[2rem] border border-stone-200 bg-stone-50 p-4 md:p-6">
                                    <div class="mb-4 grid grid-cols-7 gap-2 text-center text-xs font-semibold uppercase tracking-[0.2em] text-gray-400">
                                        <span>Mon</span>
                                        <span>Tue</span>
                                        <span>Wed</span>
                                        <span>Thu</span>
                                        <span>Fri</span>
                                        <span>Sat</span>
                                        <span>Sun</span>
                                    </div>
                                    <div id="calendarGrid" class="grid grid-cols-7 gap-2"></div>
                                </div>

                                <div class="mt-8 flex items-center justify-between">
                                    <button type="button" class="js-prev-step rounded-2xl border border-stone-200 px-6 py-3 font-semibold text-gray-700 transition hover:border-olive hover:text-olive" data-prev-step="1">Back</button>
                                    <button type="button" class="js-next-step inline-flex items-center rounded-2xl bg-olive px-6 py-3 font-semibold text-white transition hover:bg-green-700" data-next-step="3">Continue to time</button>
                                </div>
                            </section>

                            <section class="js-step-panel hidden" data-step-panel="3">
                                <div class="mb-6">
                                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-olive">Step 3</p>
                                    <h2 class="mt-2 text-2xl font-semibold text-gray-900">Choose a time slot</h2>
                                    <p class="mt-2 text-sm text-gray-500">Available times appear as soon as a valid date is selected.</p>
                                </div>

                                <div id="slotFeedback" class="mb-5 rounded-2xl bg-stone-50 px-4 py-3 text-sm text-gray-500">Select a service and a date to load your available times.</div>
                                <div id="timeSlotGrid" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3"></div>

                                <div class="mt-8 flex items-center justify-between">
                                    <button type="button" class="js-prev-step rounded-2xl border border-stone-200 px-6 py-3 font-semibold text-gray-700 transition hover:border-olive hover:text-olive" data-prev-step="2">Back</button>
                                    <button type="button" class="js-next-step inline-flex items-center rounded-2xl bg-olive px-6 py-3 font-semibold text-white transition hover:bg-green-700" data-next-step="4">Continue to details</button>
                                </div>
                            </section>

                            <section class="js-step-panel hidden" data-step-panel="4">
                                <div class="mb-6">
                                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-olive">Step 4</p>
                                    <h2 class="mt-2 text-2xl font-semibold text-gray-900">Your details and confirmation</h2>
                                    <p class="mt-2 text-sm text-gray-500">Confirm your contact information and review the appointment before paying the deposit.</p>
                                </div>

                                <div class="mb-8 rounded-[2rem] bg-olive/5 p-6">
                                    <div class="grid gap-4 md:grid-cols-3">
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.25em] text-gray-400">Service</p>
                                            <p id="confirmService" class="mt-2 text-base font-semibold text-gray-900">—</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.25em] text-gray-400">Date</p>
                                            <p id="confirmDate" class="mt-2 text-base font-semibold text-gray-900">—</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.25em] text-gray-400">Time</p>
                                            <p id="confirmTime" class="mt-2 text-base font-semibold text-gray-900">—</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid gap-6 md:grid-cols-2">
                                    <div>
                                        <label for="name" class="mb-2 block text-sm font-medium text-gray-700">Client name *</label>
                                        <input type="text" name="name" id="name" value="<?php echo e(old('name')); ?>" required class="w-full rounded-2xl border border-stone-300 px-4 py-3 focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20">
                                    </div>
                                    <div>
                                        <label for="email" class="mb-2 block text-sm font-medium text-gray-700">Email address *</label>
                                        <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" required class="w-full rounded-2xl border border-stone-300 px-4 py-3 focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20">
                                    </div>
                                    <div>
                                        <label for="phone" class="mb-2 block text-sm font-medium text-gray-700">Phone number *</label>
                                        <input type="tel" name="phone" id="phone" value="<?php echo e(old('phone')); ?>" required class="w-full rounded-2xl border border-stone-300 px-4 py-3 focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20">
                                    </div>
                                    <div>
                                        <label for="preferred_contact" class="mb-2 block text-sm font-medium text-gray-700">Preferred contact</label>
                                        <select name="preferred_contact" id="preferred_contact" class="w-full rounded-2xl border border-stone-300 px-4 py-3 focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20">
                                            <option value="email" <?php echo e(old('preferred_contact') === 'email' ? 'selected' : ''); ?>>Email</option>
                                            <option value="phone" <?php echo e(old('preferred_contact') === 'phone' ? 'selected' : ''); ?>>Phone</option>
                                            <option value="whatsapp" <?php echo e(old('preferred_contact') === 'whatsapp' ? 'selected' : ''); ?>>WhatsApp</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <label for="notes" class="mb-2 block text-sm font-medium text-gray-700">Notes</label>
                                    <textarea name="notes" id="notes" rows="4" class="w-full rounded-2xl border border-stone-300 px-4 py-3 focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20"><?php echo e(old('notes')); ?></textarea>
                                </div>

                                <div class="mt-6 rounded-[2rem] border border-stone-200 bg-stone-50 p-6">
                                    <div class="flex items-start gap-3">
                                        <input type="checkbox" name="terms" id="terms" value="1" <?php echo e(old('terms') ? 'checked' : ''); ?> required class="mt-1 h-4 w-4 rounded border-stone-300 text-olive focus:ring-olive">
                                        <label for="terms" class="text-sm text-gray-700">I agree to the <a href="<?php echo e(route('policies')); ?>" class="font-medium text-olive hover:underline">Terms & Conditions</a> and <a href="<?php echo e(route('policies')); ?>" class="font-medium text-olive hover:underline">Privacy Policy</a>. A €15 deposit is required to secure the appointment.</label>
                                    </div>
                                </div>

                                <div class="mt-8 flex items-center justify-between gap-4">
                                    <button type="button" class="js-prev-step rounded-2xl border border-stone-200 px-6 py-3 font-semibold text-gray-700 transition hover:border-olive hover:text-olive" data-prev-step="3">Back</button>
                                    <button type="submit" id="submitBookingButton" class="inline-flex items-center justify-center rounded-2xl bg-olive px-8 py-4 font-semibold text-white shadow-lg transition hover:bg-green-700 disabled:opacity-60">
                                        <span id="btnText">Confirm booking</span>
                                        <svg id="btnSpinner" class="ml-3 hidden h-5 w-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </section>
                        </div>
                    </form>
                </div>

                <aside class="rounded-[2rem] bg-white p-6 shadow-xl ring-1 ring-stone-100 lg:sticky lg:top-24 lg:self-start">
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-olive">Appointment summary</p>
                    <h2 class="mt-2 text-2xl font-semibold text-gray-900">Your session at a glance</h2>
                    <div class="mt-6 space-y-4">
                        <div class="rounded-3xl bg-stone-50 p-5">
                            <p class="text-xs uppercase tracking-[0.25em] text-gray-400">Service</p>
                            <p id="summaryServiceName" class="mt-2 text-lg font-semibold text-gray-900">Select a service</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="rounded-3xl bg-stone-50 p-5">
                                <p class="text-xs uppercase tracking-[0.25em] text-gray-400">Duration</p>
                                <p id="summaryDuration" class="mt-2 text-lg font-semibold text-gray-900">—</p>
                            </div>
                            <div class="rounded-3xl bg-stone-50 p-5">
                                <p class="text-xs uppercase tracking-[0.25em] text-gray-400">Price</p>
                                <p id="summaryPrice" class="mt-2 text-lg font-semibold text-gray-900">—</p>
                            </div>
                        </div>
                        <div class="rounded-3xl bg-stone-50 p-5">
                            <p class="text-xs uppercase tracking-[0.25em] text-gray-400">Date & time</p>
                            <p id="summaryDateTime" class="mt-2 text-lg font-semibold text-gray-900">Choose a date and time</p>
                        </div>
                        <div class="rounded-3xl border border-dashed border-stone-200 px-5 py-4 text-sm text-gray-500">
                            A €15 deposit will be requested after submission to secure your booking.
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const availableDates = <?php echo json_encode(array_values($availableDates ?? []), 15, 512) ?>;
    const selector = document.getElementById('bookingServiceSelector');
    const serviceInputs = selector ? selector.querySelectorAll('.js-service-input') : [];
    const serviceOptions = selector ? selector.querySelectorAll('.js-service-option') : [];
    const categoryToggles = selector ? selector.querySelectorAll('.js-service-category-toggle') : [];
    const stepPanels = document.querySelectorAll('.js-step-panel');
    const stepIndicators = document.querySelectorAll('.js-step-indicator');
    const nextButtons = document.querySelectorAll('.js-next-step');
    const prevButtons = document.querySelectorAll('.js-prev-step');
    const servicesPayloadInput = document.getElementById('services_payload');
    const serviceIdInput = document.getElementById('service_id');
    const appointmentDateInput = document.getElementById('appointment_date');
    const appointmentTimeInput = document.getElementById('appointment_time');
    const calendarGrid = document.getElementById('calendarGrid');
    const calendarMonthLabel = document.getElementById('calendarMonthLabel');
    const prevMonthButton = document.getElementById('calendarPrevMonth');
    const nextMonthButton = document.getElementById('calendarNextMonth');
    const timeSlotGrid = document.getElementById('timeSlotGrid');
    const slotFeedback = document.getElementById('slotFeedback');
    const submitButton = document.getElementById('submitBookingButton');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    const bookingForm = document.getElementById('bookingWizardForm');

    const summaryServiceName = document.getElementById('summaryServiceName');
    const summaryDuration = document.getElementById('summaryDuration');
    const summaryPrice = document.getElementById('summaryPrice');
    const summaryDateTime = document.getElementById('summaryDateTime');
    const confirmService = document.getElementById('confirmService');
    const confirmDate = document.getElementById('confirmDate');
    const confirmTime = document.getElementById('confirmTime');

    const monthKeys = [...new Set(availableDates.map((date) => date.slice(0, 7)))];
    const initialMonthKey = (appointmentDateInput.value || availableDates[0] || new Date().toISOString().slice(0, 7)).slice(0, 7);

    let currentStep = 1;
    let currentMonthIndex = Math.max(monthKeys.indexOf(initialMonthKey), 0);
    if (currentMonthIndex === -1) currentMonthIndex = 0;

    let state = {
        services: [],
        date: appointmentDateInput.value || null,
        time: appointmentTimeInput.value || null,
    };

    function formatDisplayDate(dateString) {
        if (!dateString) return '—';
        const date = new Date(`${dateString}T00:00:00`);
        return new Intl.DateTimeFormat('en-IE', { weekday: 'short', day: 'numeric', month: 'long' }).format(date);
    }

    function updateStepUI() {
        stepPanels.forEach((panel) => panel.classList.toggle('hidden', parseInt(panel.dataset.stepPanel, 10) !== currentStep));
        stepIndicators.forEach((indicator) => {
            const step = parseInt(indicator.dataset.step, 10);
            const active = step === currentStep;
            indicator.classList.toggle('border-olive', active);
            indicator.classList.toggle('bg-olive/5', active);
            indicator.querySelector('span').classList.toggle('bg-olive', active);
            indicator.querySelector('span').classList.toggle('text-white', active);
            indicator.querySelector('span').classList.toggle('bg-stone-100', !active);
            indicator.querySelector('span').classList.toggle('text-gray-600', !active);
        });
    }

    function updateSummary() {
        const totalDuration = state.services.reduce((sum, service) => sum + service.duration, 0);
        const totalPrice = state.services.reduce((sum, service) => sum + service.price, 0);
        const serviceNames = state.services.map((service) => service.displayName);

        summaryServiceName.textContent = serviceNames.length ? serviceNames.join(' + ') : 'Select one or more services';
        summaryDuration.textContent = serviceNames.length ? `${totalDuration} min` : '—';
        summaryPrice.textContent = serviceNames.length ? `€${totalPrice.toFixed(2)}` : '—';
        summaryDateTime.textContent = state.date ? `${formatDisplayDate(state.date)}${state.time ? ` · ${state.time}` : ''}` : 'Choose a date and time';

        confirmService.textContent = serviceNames.length ? serviceNames.join(' + ') : '—';
        confirmDate.textContent = state.date ? formatDisplayDate(state.date) : '—';
        confirmTime.textContent = state.time || '—';

        if (state.services.length) {
            const payload = state.services.reduce((carry, service) => {
                carry[service.name] = {
                    id: service.id,
                    price: service.price,
                    duration: service.duration,
                };
                return carry;
            }, {});
            servicesPayloadInput.value = JSON.stringify(payload);
            serviceIdInput.value = state.services[0].id;
        } else {
            servicesPayloadInput.value = '';
            serviceIdInput.value = '';
        }
    }

    function syncServicesFromInputs() {
        serviceOptions.forEach((option) => {
            option.classList.remove('border-l-olive', 'bg-olive/5');
            option.classList.add('border-l-transparent');
        });

        state.services = [];

        serviceInputs.forEach((input) => {
            if (!input.checked) return;
            const option = input.closest('.js-service-option');
            if (!option) return;

            option.classList.remove('border-l-transparent');
            option.classList.add('border-l-olive', 'bg-olive/5');

            state.services.push({
                id: parseInt(option.dataset.serviceId, 10),
                name: option.dataset.serviceName,
                displayName: option.dataset.displayName,
                price: parseFloat(option.dataset.price),
                duration: parseInt(option.dataset.duration, 10),
            });
        });

        updateSummary();
        if (state.date) {
            state.time = null;
            appointmentTimeInput.value = '';
            loadAvailableSlots(state.date);
        }
    }

    function renderCalendar() {
        const monthKey = monthKeys[currentMonthIndex] || initialMonthKey;
        const [year, month] = monthKey.split('-').map(Number);
        const firstDay = new Date(year, month - 1, 1);
        const daysInMonth = new Date(year, month, 0).getDate();
        const offset = (firstDay.getDay() + 6) % 7;
        calendarMonthLabel.textContent = new Intl.DateTimeFormat('en-IE', { month: 'long', year: 'numeric' }).format(firstDay);
        calendarGrid.innerHTML = '';

        for (let i = 0; i < offset; i++) {
            const filler = document.createElement('div');
            filler.className = 'aspect-square rounded-2xl bg-transparent';
            calendarGrid.appendChild(filler);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dateString = `${monthKey}-${String(day).padStart(2, '0')}`;
            const isAvailable = availableDates.includes(dateString);
            const isSelected = state.date === dateString;
            const button = document.createElement('button');
            button.type = 'button';
            button.className = `aspect-square rounded-2xl border text-sm font-semibold transition ${isAvailable ? 'border-stone-200 bg-white text-gray-900 hover:border-olive hover:bg-olive/5' : 'cursor-not-allowed border-transparent bg-stone-100 text-gray-300'} ${isSelected ? 'border-olive bg-olive text-white hover:bg-olive' : ''}`;
            button.textContent = day;
            button.disabled = !isAvailable;
            button.dataset.date = dateString;
            if (isAvailable) {
                button.addEventListener('click', function () {
                    state.date = dateString;
                    state.time = null;
                    appointmentDateInput.value = dateString;
                    appointmentTimeInput.value = '';
                    renderCalendar();
                    updateSummary();
                    loadAvailableSlots(dateString);
                });
            }
            calendarGrid.appendChild(button);
        }

        prevMonthButton.disabled = currentMonthIndex <= 0;
        nextMonthButton.disabled = currentMonthIndex >= monthKeys.length - 1;
        [prevMonthButton, nextMonthButton].forEach((button) => {
            button.classList.toggle('opacity-40', button.disabled);
            button.classList.toggle('cursor-not-allowed', button.disabled);
        });
    }

    function renderSlots(slots) {
        timeSlotGrid.innerHTML = '';
        if (!slots.length) {
            slotFeedback.textContent = 'No available times for this date. Please choose another day.';
            return;
        }

        slotFeedback.textContent = 'Tap your preferred time below.';
        slots.forEach((slot) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = `rounded-2xl border px-4 py-4 text-center font-semibold transition ${state.time === slot.time ? 'border-olive bg-olive text-white' : 'border-stone-200 bg-white text-gray-900 hover:border-olive hover:bg-olive/5'}`;
            button.textContent = slot.time;
            button.addEventListener('click', function () {
                state.time = slot.time;
                appointmentTimeInput.value = slot.time;
                renderSlots(slots);
                updateSummary();
            });
            timeSlotGrid.appendChild(button);
        });
    }

    function loadAvailableSlots(dateString) {
        if (!state.services.length || !dateString) {
            slotFeedback.textContent = 'Select a service and a date to load your available times.';
            timeSlotGrid.innerHTML = '';
            return;
        }

        const totalDuration = state.services.reduce((sum, service) => sum + service.duration, 0);

        slotFeedback.textContent = 'Loading available times...';
        timeSlotGrid.innerHTML = '';

        fetch(`/api/appointments/available?date=${encodeURIComponent(dateString)}&duration=${totalDuration}`)
            .then((response) => response.json())
            .then((data) => {
                const slots = Array.isArray(data.slots) ? data.slots.filter((slot) => slot.available) : [];
                renderSlots(slots);
            })
            .catch(() => {
                slotFeedback.textContent = 'Unable to load times right now. Please try again.';
                timeSlotGrid.innerHTML = '';
            });
    }

    function goToStep(step) {
        if (step === 2 && !state.services.length) return;
        if (step === 3 && (!state.services.length || !state.date)) return;
        if (step === 4 && (!state.services.length || !state.date || !state.time)) return;
        currentStep = step;
        updateStepUI();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    categoryToggles.forEach((toggle) => {
        toggle.addEventListener('click', function () {
            const panel = this.nextElementSibling;
            const icon = this.querySelector('svg');
            panel.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        });
    });

    serviceInputs.forEach((input) => {
        input.addEventListener('change', function () {
            syncServicesFromInputs();
        });
    });

    nextButtons.forEach((button) => {
        button.addEventListener('click', function () {
            goToStep(parseInt(this.dataset.nextStep, 10));
        });
    });

    prevButtons.forEach((button) => {
        button.addEventListener('click', function () {
            goToStep(parseInt(this.dataset.prevStep, 10));
        });
    });

    stepIndicators.forEach((indicator) => {
        indicator.addEventListener('click', function () {
            goToStep(parseInt(this.dataset.step, 10));
        });
    });

    prevMonthButton.addEventListener('click', function () {
        if (currentMonthIndex > 0) {
            currentMonthIndex -= 1;
            renderCalendar();
        }
    });

    nextMonthButton.addEventListener('click', function () {
        if (currentMonthIndex < monthKeys.length - 1) {
            currentMonthIndex += 1;
            renderCalendar();
        }
    });

    bookingForm.addEventListener('submit', function () {
        if (submitButton) submitButton.disabled = true;
        if (btnText) btnText.textContent = 'Booking...';
        if (btnSpinner) btnSpinner.classList.remove('hidden');
    });

    syncServicesFromInputs();

    if (!state.date && availableDates.length) {
        const requestedDate = <?php echo json_encode($initialDate, 15, 512) ?>;
        if (requestedDate && availableDates.includes(requestedDate)) {
            state.date = requestedDate;
            appointmentDateInput.value = requestedDate;
        }
    }

    updateStepUI();
    renderCalendar();
    updateSummary();

    if (state.services.length && state.date) {
        loadAvailableSlots(state.date);
    }

    if (state.services.length && state.date && state.time) {
        goToStep(4);
    } else if (state.services.length && state.date) {
        goToStep(3);
    } else if (state.services.length) {
        goToStep(2);
    }
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\delphina\resources\views/booking/create.blade.php ENDPATH**/ ?>