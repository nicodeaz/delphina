{{--
    Three-step booking flow (services → day & time → details) that ends by
    creating the booking and sending the client to the €15 Revolut deposit.
    Rendered inline on /book ($mode = 'page') and inside the slide-up sheet on
    every other public page ($mode = 'sheet'). Data comes from the view
    composer in AppServiceProvider.
--}}
@php
    $mode = $mode ?? 'page';
    $flowConfig = $bookingConfig + [
        'preselect' => array_values(array_filter([(int) request('service_id')])),
    ];
@endphp

<div
    x-data="bookingFlow(@js($flowConfig))"
    @open-booking.window="onOpen($event.detail)"
    class="flex min-h-0 flex-1 flex-col {{ $mode === 'page' ? 'rounded-[2rem] bg-white shadow-xl ring-1 ring-stone-100' : '' }}"
>
    {{-- Header: back button, step title, progress --}}
    <div class="shrink-0 px-5 pb-3 {{ $mode === 'page' ? 'pt-5 md:px-8 md:pt-7' : 'pt-1' }}">
        <div class="flex items-center gap-3">
            <button type="button" x-show="step > 1" @click="back()" class="-ml-2 flex h-10 w-10 items-center justify-center rounded-full text-gray-600 hover:bg-stone-100" aria-label="Back">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div class="min-w-0 flex-1">
                <p class="text-[11px] font-semibold uppercase tracking-[0.25em] text-olive" x-text="'Step ' + step + ' of 3'"></p>
                <h2 class="truncate text-xl font-semibold text-gray-900 md:text-2xl" x-text="['Choose your service', 'Pick a day & time', 'Your details'][step - 1]"></h2>
            </div>
            @if($mode === 'sheet')
                <button type="button" @click="$dispatch('close-booking')" class="flex h-10 w-10 items-center justify-center rounded-full text-gray-500 hover:bg-stone-100" aria-label="Close booking">
                    <i class="fas fa-times"></i>
                </button>
            @endif
        </div>
        <div class="mt-3 grid grid-cols-3 gap-1.5">
            <template x-for="n in 3" :key="n">
                <span class="h-1 rounded-full transition-colors" :class="n <= step ? 'bg-olive' : 'bg-stone-200'"></span>
            </template>
        </div>
    </div>

    {{-- Scrollable body --}}
    <div x-ref="body" class="min-h-0 flex-1 {{ $mode === 'sheet' ? 'overflow-y-auto overscroll-contain' : '' }} px-5 pb-6 {{ $mode === 'page' ? 'md:px-8' : '' }}">

        {{-- STEP 1: services --}}
        <section x-show="step === 1">
            <div class="-mx-5 mb-4 flex gap-2 overflow-x-auto px-5 pb-1 [scrollbar-width:none]">
                <button type="button" @click="category = 'all'" class="shrink-0 rounded-full border px-4 py-2 text-sm font-semibold transition" :class="category === 'all' ? 'border-olive bg-olive text-white' : 'border-stone-200 text-gray-600'">All</button>
                <template x-for="cat in categories" :key="cat.key">
                    <button type="button" @click="category = cat.key" class="shrink-0 rounded-full border px-4 py-2 text-sm font-semibold transition" :class="category === cat.key ? 'border-olive bg-olive text-white' : 'border-stone-200 text-gray-600'" x-text="cat.label"></button>
                </template>
            </div>

            <div class="space-y-2.5">
                <template x-for="service in visibleServices()" :key="service.id">
                    <button type="button" @click="toggleService(service.id)" class="flex w-full items-start gap-3 rounded-2xl border p-4 text-left transition" :class="isSelected(service.id) ? 'border-olive bg-olive/5 ring-1 ring-olive' : 'border-stone-200 bg-white hover:border-olive/40'">
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-gray-400" x-text="service.categoryLabel"></p>
                            <p class="mt-0.5 font-semibold text-gray-900" x-text="service.displayName"></p>
                            <p x-show="service.description" class="mt-1 line-clamp-2 text-sm leading-snug text-gray-500" x-text="service.description"></p>
                            <p class="mt-2 text-sm">
                                <span class="font-bold text-olive" x-text="service.price > 0 ? '€' + formatPrice(service.price) : 'Free'"></span>
                                <span class="text-gray-400"> · </span>
                                <span class="text-gray-500" x-text="formatDuration(service.duration)"></span>
                            </p>
                        </div>
                        <span class="mt-1 flex h-7 w-7 shrink-0 items-center justify-center rounded-full border-2 transition" :class="isSelected(service.id) ? 'border-olive bg-olive text-white' : 'border-stone-300 text-transparent'">
                            <i class="fas fa-check text-xs"></i>
                        </span>
                    </button>
                </template>
            </div>
            <p x-show="!services.length" class="rounded-2xl bg-stone-50 p-6 text-center text-sm text-gray-500">Services will be listed here soon.</p>
        </section>

        {{-- STEP 2: day and time --}}
        <section x-show="step === 2" x-cloak>
            <template x-if="!dates.length">
                <div class="rounded-2xl bg-stone-50 p-6 text-center">
                    <p class="font-semibold text-gray-900">No online availability right now</p>
                    <p class="mt-1 text-sm text-gray-500">New dates open regularly. Message Delfi and she'll find you a spot.</p>
                    <a href="https://wa.me/353899409670" target="_blank" rel="noopener" class="mt-4 inline-flex items-center rounded-2xl bg-green-600 px-5 py-3 text-sm font-semibold text-white"><i class="fab fa-whatsapp mr-2"></i>WhatsApp Delfi</a>
                </div>
            </template>

            <template x-if="dates.length">
                <div>
                    <p class="mb-2 text-sm font-semibold text-gray-700" x-text="monthLabel()"></p>
                    <div x-ref="dateStrip" class="-mx-5 flex gap-2 overflow-x-auto px-5 pb-2 [scrollbar-width:none]">
                        <template x-for="d in dates" :key="d">
                            <button type="button" @click="selectDate(d)" :data-date="d" class="flex w-16 shrink-0 flex-col items-center rounded-2xl border py-2.5 transition" :class="date === d ? 'border-olive bg-olive text-white shadow-md' : 'border-stone-200 bg-white text-gray-800'">
                                <span class="text-[11px] font-semibold uppercase" :class="date === d ? 'text-white/80' : 'text-gray-400'" x-text="dayPart(d, 'weekday')"></span>
                                <span class="text-xl font-bold leading-tight" x-text="dayPart(d, 'day')"></span>
                                <span class="text-[11px]" :class="date === d ? 'text-white/80' : 'text-gray-400'" x-text="dayPart(d, 'month')"></span>
                            </button>
                        </template>
                    </div>

                    <div class="mt-5">
                        <template x-if="loadingSlots">
                            <div class="grid grid-cols-3 gap-2 sm:grid-cols-4">
                                <template x-for="n in 8" :key="n"><span class="h-12 animate-pulse rounded-xl bg-stone-100"></span></template>
                            </div>
                        </template>
                        <template x-if="!loadingSlots && date && !slots.length">
                            <p class="rounded-2xl bg-stone-50 p-5 text-center text-sm text-gray-500">This day is fully booked for <span x-text="formatDuration(totalDuration())"></span>. Please try another day.</p>
                        </template>
                        <template x-if="!loadingSlots && slots.length">
                            <div class="space-y-4">
                                <template x-for="group in slotGroups()" :key="group.label">
                                    <div>
                                        <p class="mb-2 text-xs font-semibold uppercase tracking-[0.2em] text-gray-400" x-text="group.label"></p>
                                        <div class="grid grid-cols-3 gap-2 sm:grid-cols-4">
                                            <template x-for="slot in group.slots" :key="slot">
                                                <button type="button" @click="selectTime(slot)" class="rounded-xl border py-3 text-sm font-semibold transition" :class="time === slot ? 'border-olive bg-olive text-white' : 'border-stone-200 bg-white text-gray-800 hover:border-olive'" x-text="slot"></button>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <p x-show="slotError" class="mt-3 text-sm text-red-600" x-text="slotError"></p>
                    </div>
                </div>
            </template>
        </section>

        {{-- STEP 3: details --}}
        <section x-show="step === 3" x-cloak>
            <div class="rounded-2xl bg-olive/5 p-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-900" x-text="selectedServices().map(s => s.displayName).join(' + ')"></p>
                        <p class="mt-0.5 text-sm text-gray-600" x-text="formatLongDate(date) + ' at ' + time"></p>
                    </div>
                    <button type="button" @click="step = 1" class="shrink-0 text-xs font-semibold text-olive underline">Change</button>
                </div>
                <div class="mt-3 space-y-1 border-t border-olive/10 pt-3 text-sm">
                    <div class="flex justify-between text-gray-600"><span>Total (<span x-text="formatDuration(totalDuration())"></span>)</span><span x-text="'€' + formatPrice(totalPrice())"></span></div>
                    <div class="flex justify-between font-semibold text-gray-900"><span>Deposit to pay now</span><span x-text="'€' + formatPrice(deposit)"></span></div>
                    <div class="flex justify-between text-gray-500"><span>Rest, paid at the studio</span><span x-text="'€' + formatPrice(Math.max(0, totalPrice() - deposit))"></span></div>
                </div>
            </div>

            <form x-ref="detailsForm" @submit.prevent="submit()" class="mt-5 space-y-4" novalidate>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700" :for="uid('name')">Full name</label>
                    <input :id="uid('name')" type="text" x-model="form.name" @input="errors.name = null" autocomplete="name" required class="booking-input" :class="errors.name && 'booking-input--error'">
                    <p class="booking-error" x-text="errors.name?.[0]"></p>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700" :for="uid('phone')">Phone (WhatsApp)</label>
                    <input :id="uid('phone')" type="tel" x-model="form.phone" @input="errors.phone = null" autocomplete="tel" required placeholder="+353 8x xxx xxxx" class="booking-input" :class="errors.phone && 'booking-input--error'">
                    <p class="booking-error" x-text="errors.phone?.[0]"></p>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700" :for="uid('email')">Email</label>
                    <input :id="uid('email')" type="email" x-model="form.email" @input="errors.email = null" autocomplete="email" required class="booking-input" :class="errors.email && 'booking-input--error'">
                    <p class="booking-error" x-text="errors.email?.[0]"></p>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700" :for="uid('notes')">Anything Delfi should know? <span class="text-gray-400">(optional)</span></label>
                    <textarea :id="uid('notes')" x-model="form.notes" rows="2" maxlength="1000" class="booking-input" placeholder="Design ideas, nail length, allergies…"></textarea>
                </div>
                <label class="flex items-start gap-3 text-sm text-gray-600">
                    <input type="checkbox" x-model="form.terms" @change="errors.terms = null" class="mt-0.5 h-5 w-5 shrink-0 rounded border-stone-300 text-olive focus:ring-olive">
                    <span>I accept the <a :href="policiesUrl" target="_blank" class="font-semibold text-olive underline">booking &amp; cancellation policy</a>. The €15 deposit secures my spot and is taken off the final price.</span>
                </label>
                <p class="booking-error" x-text="errors.terms?.[0]"></p>
                <p x-show="generalError" class="rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700" x-text="generalError"></p>
            </form>
        </section>
    </div>

    {{-- Footer action --}}
    <div class="shrink-0 border-t border-stone-100 bg-white/95 px-5 py-3 backdrop-blur {{ $mode === 'page' ? 'sticky bottom-0 rounded-b-[2rem] md:px-8' : '' }}" style="padding-bottom: max(0.75rem, env(safe-area-inset-bottom));">
        <div class="flex items-center gap-3">
            <div class="min-w-0 flex-1" x-show="selected.length">
                <p class="truncate text-sm font-semibold text-gray-900" x-text="selected.length + (selected.length > 1 ? ' services' : ' service') + ' · ' + formatDuration(totalDuration())"></p>
                <p class="text-xs text-gray-500" x-text="step === 3 ? 'Pay €' + formatPrice(deposit) + ' deposit via Revolut' : 'Total €' + formatPrice(totalPrice())"></p>
            </div>
            <p class="min-w-0 flex-1 text-sm text-gray-500" x-show="!selected.length">Select one or more services</p>

            <button type="button" x-show="step < 3" @click="next()" :disabled="!canContinue()" class="shrink-0 rounded-2xl bg-olive px-6 py-3.5 font-semibold text-white shadow-lg transition hover:bg-green-700 disabled:cursor-not-allowed disabled:opacity-40">
                Continue
            </button>
            <button type="button" x-show="step === 3" x-cloak @click="submit()" :disabled="submitting" class="shrink-0 rounded-2xl bg-olive px-5 py-3.5 font-semibold text-white shadow-lg transition hover:bg-green-700 disabled:opacity-60">
                <span x-show="!submitting">Book &amp; pay €<span x-text="formatPrice(deposit)"></span></span>
                <span x-show="submitting"><i class="fas fa-circle-notch fa-spin mr-2"></i>Booking…</span>
            </button>
        </div>
    </div>
</div>

@once
    <style>
        .booking-input {
            width: 100%;
            border-radius: 1rem;
            border: 1px solid #d6d3d1;
            background: #fff;
            padding: 0.8rem 1rem;
            font-size: 16px; /* keeps iOS from zooming into the field */
        }
        .booking-input:focus {
            outline: none;
            border-color: #554F13;
            box-shadow: 0 0 0 3px rgba(85, 79, 19, 0.15);
        }
        .booking-input--error { border-color: #dc2626; }
        .booking-error:not(:empty) { margin-top: 0.35rem; font-size: 0.8rem; color: #dc2626; }
    </style>

    @push('scripts')
    <script>
        function bookingFlow(config) {
            const STORAGE_KEY = 'delphinaBookingDetails';
            let savedDetails = {};
            try { savedDetails = JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}'); } catch (e) {}

            return {
                services: config.services,
                categories: config.categories,
                dates: config.dates,
                deposit: Number(config.deposit),
                policiesUrl: config.policiesUrl,
                idPrefix: 'bf' + Math.random().toString(36).slice(2, 7),

                step: 1,
                category: 'all',
                selected: [...config.preselect].filter((id) => config.services.some((s) => s.id === id)),
                date: null,
                time: null,
                slots: [],
                loadingSlots: false,
                slotError: '',
                slotsKey: null,
                form: {
                    name: savedDetails.name || '',
                    phone: savedDetails.phone || '',
                    email: savedDetails.email || '',
                    notes: '',
                    terms: false,
                },
                errors: {},
                generalError: '',
                submitting: false,

                uid(name) { return `${this.idPrefix}-${name}`; },

                onOpen(detail) {
                    const id = Number(detail?.serviceId);
                    if (id && this.services.some((s) => s.id === id) && !this.selected.includes(id)) {
                        this.selected = [id];
                        this.step = 1;
                        this.time = null;
                    }
                },

                // ----- services -----
                visibleServices() {
                    return this.category === 'all' ? this.services : this.services.filter((s) => s.category === this.category);
                },
                isSelected(id) { return this.selected.includes(id); },
                toggleService(id) {
                    this.selected = this.isSelected(id) ? this.selected.filter((x) => x !== id) : [...this.selected, id];
                    this.time = null;
                },
                selectedServices() { return this.services.filter((s) => this.selected.includes(s.id)); },
                totalDuration() { return this.selectedServices().reduce((sum, s) => sum + s.duration, 0); },
                totalPrice() { return this.selectedServices().reduce((sum, s) => sum + s.price, 0); },

                // ----- dates & slots -----
                selectDate(d) {
                    if (this.date === d) return;
                    this.date = d;
                    this.time = null;
                    this.loadSlots();
                },
                selectTime(slot) {
                    this.time = slot;
                },
                async loadSlots() {
                    if (!this.date) return;
                    const key = `${this.date}|${this.totalDuration()}`;
                    if (key === this.slotsKey && this.slots.length) return;
                    this.slotsKey = key;
                    this.loadingSlots = true;
                    this.slotError = '';
                    try {
                        const params = new URLSearchParams({ date: this.date, duration: Math.max(this.totalDuration(), 15) });
                        const response = await fetch(`${config.slotsUrl}?${params}`, { headers: { Accept: 'application/json' } });
                        const data = await response.json();
                        if (key !== this.slotsKey) return; // a newer request superseded this one
                        this.slots = (data.slots || []).filter((s) => s.available).map((s) => s.time);
                        if (!this.slots.includes(this.time)) this.time = null;
                    } catch (e) {
                        this.slots = [];
                        this.slotError = 'We could not load the times. Please check your connection and try again.';
                    } finally {
                        if (key === this.slotsKey) this.loadingSlots = false;
                    }
                },
                slotGroups() {
                    const groups = [
                        { label: 'Morning', slots: this.slots.filter((t) => t < '12:00') },
                        { label: 'Afternoon', slots: this.slots.filter((t) => t >= '12:00' && t < '17:00') },
                        { label: 'Evening', slots: this.slots.filter((t) => t >= '17:00') },
                    ];
                    return groups.filter((g) => g.slots.length);
                },

                // ----- navigation -----
                canContinue() {
                    if (this.step === 1) return this.selected.length > 0;
                    if (this.step === 2) return !!(this.date && this.time);
                    return true;
                },
                next() {
                    if (!this.canContinue()) return;
                    this.step += 1;
                    if (this.step === 2) {
                        this.slotsKey = null;
                        if (!this.date && this.dates.length) {
                            this.date = this.dates[0];
                        }
                        this.loadSlots();
                        this.$nextTick(() => {
                            const chip = this.$refs.dateStrip?.querySelector(`[data-date="${this.date}"]`);
                            chip?.scrollIntoView({ inline: 'center', block: 'nearest' });
                        });
                    }
                    this.scrollTop();
                },
                back() {
                    if (this.step > 1) this.step -= 1;
                    this.scrollTop();
                },
                scrollTop() {
                    this.$nextTick(() => {
                        const body = this.$refs.body;
                        if (body && body.scrollHeight > body.clientHeight) body.scrollTop = 0;
                        else if (this.$root.getBoundingClientRect().top < 0) this.$root.scrollIntoView({ behavior: 'smooth' });
                    });
                },

                // ----- submit -----
                validate() {
                    const errors = {};
                    if (!this.form.name.trim()) errors.name = ['Please enter your name.'];
                    if (this.form.phone.replace(/\D/g, '').length < 7) errors.phone = ['Please enter a phone number we can reach you on.'];
                    if (!/^\S+@\S+\.\S+$/.test(this.form.email.trim())) errors.email = ['Please enter a valid email.'];
                    if (!this.form.terms) errors.terms = ['Please accept the booking policy to continue.'];
                    this.errors = errors;
                    return !Object.keys(errors).length;
                },
                async submit() {
                    this.generalError = '';
                    if (!this.validate()) return;
                    this.submitting = true;

                    try { localStorage.setItem(STORAGE_KEY, JSON.stringify({ name: this.form.name, phone: this.form.phone, email: this.form.email })); } catch (e) {}

                    const services = {};
                    this.selectedServices().forEach((s) => { services[s.name] = { id: s.id, price: s.price, duration: s.duration }; });

                    try {
                        const response = await fetch(config.storeUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                Accept: 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            },
                            body: JSON.stringify({
                                services: JSON.stringify(services),
                                appointment_date: this.date,
                                appointment_time: this.time,
                                name: this.form.name.trim(),
                                email: this.form.email.trim(),
                                phone: this.form.phone.trim(),
                                preferred_contact: 'whatsapp',
                                notes: this.form.notes.trim() || null,
                                terms: this.form.terms ? 1 : 0,
                            }),
                        });
                        const data = await response.json().catch(() => ({}));

                        if (response.ok && data.redirect) {
                            window.location.href = data.redirect;
                            return;
                        }

                        if (response.status === 419) {
                            this.generalError = 'Your session expired. Please refresh the page and try again.';
                        } else if (data.errors?.appointment_time || data.errors?.appointment_date) {
                            // The slot was taken meanwhile: send them back to choose another time.
                            this.step = 2;
                            this.slotsKey = null;
                            this.loadSlots();
                            this.slotError = (data.errors.appointment_time || data.errors.appointment_date)[0];
                        } else {
                            this.errors = data.errors || {};
                            this.generalError = data.message || 'Something went wrong. Please try again.';
                        }
                    } catch (e) {
                        this.generalError = 'We could not reach the server. Please check your connection and try again.';
                    }
                    this.submitting = false;
                },

                // ----- formatting -----
                formatPrice(value) {
                    return Number(value) % 1 === 0 ? Number(value).toFixed(0) : Number(value).toFixed(2);
                },
                formatDuration(minutes) {
                    if (minutes < 60) return `${minutes} min`;
                    const h = Math.floor(minutes / 60), m = minutes % 60;
                    return m ? `${h}h ${m}min` : `${h}h`;
                },
                dayPart(d, part) {
                    const date = new Date(d + 'T12:00:00');
                    if (part === 'day') return date.getDate();
                    return new Intl.DateTimeFormat('en-IE', part === 'weekday' ? { weekday: 'short' } : { month: 'short' }).format(date);
                },
                formatLongDate(d) {
                    if (!d) return '';
                    return new Intl.DateTimeFormat('en-IE', { weekday: 'long', day: 'numeric', month: 'long' }).format(new Date(d + 'T12:00:00'));
                },
                monthLabel() {
                    const d = this.date || this.dates[0];
                    return d ? new Intl.DateTimeFormat('en-IE', { month: 'long', year: 'numeric' }).format(new Date(d + 'T12:00:00')) : '';
                },
            };
        }
    </script>
    @endpush
@endonce
