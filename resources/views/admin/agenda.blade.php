@extends('layouts.admin')

@section('title', 'Agenda')

@php
    $monthKey = $currentMonth->format('Y-m');
    $previousMonth = $currentMonth->copy()->subMonth()->format('Y-m');
    $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');
    $calendarStart = $currentMonth->copy()->startOfMonth()->startOfWeek(\Carbon\Carbon::MONDAY);
    $calendarEnd = $currentMonth->copy()->endOfMonth()->endOfWeek(\Carbon\Carbon::SUNDAY);

    $days = [];
    for ($day = $calendarStart->copy(); $day->lte($calendarEnd); $day->addDay()) {
        $key = $day->toDateString();
        $days[] = [
            'date' => $key,
            'day' => $day->day,
            'weekday' => $day->format('D'),
            'label' => $day->format('l j F'),
            'inMonth' => $day->format('Y-m') === $monthKey,
            'isToday' => $day->isToday(),
            'isPast' => $day->lt(today()),
            'hours' => $availabilityByDate->get($key, collect())->map(fn ($a) => [
                'id' => $a->id,
                'date' => $key,
                'start_time' => substr($a->start_time, 0, 5),
                'end_time' => substr($a->end_time, 0, 5),
                'label' => substr($a->start_time, 0, 5).'-'.substr($a->end_time, 0, 5),
                'notes' => $a->notes,
            ])->values(),
            'bookings' => $appointmentsByDate->get($key, collect())->values(),
        ];
    }

    $agendaConfig = [
        'days' => $days,
        'awaiting' => $awaitingDeposit,
        'services' => $services->map(fn ($s) => [
            'id' => $s->id,
            'name' => $s->name,
            'price' => (float) $s->price,
            'duration' => (int) $s->duration,
        ])->values(),
        'today' => today()->toDateString(),
        'tomorrow' => today()->addDay()->toDateString(),
        'defaultDate' => $currentMonth->isSameMonth(today()) ? today()->toDateString() : $currentMonth->toDateString(),
        'revolutLink' => config('services.revolut.payment_link'),
        'deposit' => \App\Models\Payment::AMOUNT,
    ];
@endphp

@section('content')
<div class="py-6 md:py-8" x-data="agendaBoard(@js($agendaConfig))">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-olive">Delfi's workspace</p>
                <h1 class="mt-2 text-3xl font-semibold text-gray-900 md:text-4xl">Studio agenda</h1>
                <p class="mt-2 max-w-2xl text-sm text-gray-600">Open your working hours, then clients can only book inside them. Tap any booking to see it, edit it or confirm the deposit.</p>
            </div>
            <div class="grid grid-cols-3 gap-2 sm:flex sm:flex-wrap sm:gap-3">
                <button type="button" @click="openNewBooking()" class="flex flex-col items-center justify-center gap-1 rounded-2xl bg-olive px-3 py-3 text-xs font-semibold text-white shadow-lg transition hover:bg-green-700 sm:flex-row sm:gap-2 sm:px-5 sm:text-sm">
                    <i class="fas fa-user-plus"></i><span>New booking</span>
                </button>
                <button type="button" @click="openHours()" class="flex flex-col items-center justify-center gap-1 rounded-2xl border border-olive/30 bg-white px-3 py-3 text-xs font-semibold text-olive transition hover:border-olive sm:flex-row sm:gap-2 sm:px-5 sm:text-sm">
                    <i class="fas fa-clock"></i><span>Open hours</span>
                </button>
                <button type="button" @click="openBulk()" class="flex flex-col items-center justify-center gap-1 rounded-2xl border border-olive/30 bg-white px-3 py-3 text-xs font-semibold text-olive transition hover:border-olive sm:flex-row sm:gap-2 sm:px-5 sm:text-sm">
                    <i class="fas fa-calendar-week"></i><span>Set up weeks</span>
                </button>
            </div>
        </div>

        {{-- Deposits waiting for confirmation --}}
        <template x-if="awaiting.length">
            <section class="mb-6 rounded-[1.5rem] border border-amber-200 bg-amber-50 p-4 sm:p-5">
                <div class="mb-3 flex items-center justify-between gap-3">
                    <h2 class="text-sm font-bold text-amber-900"><i class="fas fa-hourglass-half mr-2"></i>Waiting for deposit (<span x-text="awaiting.length"></span>)</h2>
                    <p class="hidden text-xs text-amber-800 sm:block">Check Revolut, then tap “Confirm €15”.</p>
                </div>
                <div class="-mx-1 flex gap-3 overflow-x-auto px-1 pb-1">
                    <template x-for="item in awaiting" :key="item.id">
                        <div class="w-64 shrink-0 rounded-2xl bg-white p-3 shadow-sm ring-1 ring-amber-100">
                            <button type="button" class="w-full text-left" @click="openBooking(item)">
                                <p class="truncate text-sm font-bold text-gray-900" x-text="item.customer"></p>
                                <p class="text-xs text-gray-500" x-text="formatDate(item.date) + ' · ' + item.time"></p>
                                <p class="mt-1 truncate text-xs text-gray-600" x-text="item.services"></p>
                            </button>
                            <div class="mt-3 flex gap-2">
                                <button type="button" x-show="item.payment_id" @click="confirmDeposit(item)" class="flex-1 rounded-xl bg-amber-600 px-2 py-2 text-xs font-bold text-white hover:bg-amber-700">Confirm €15</button>
                                <a x-show="item.phone" :href="whatsappLink(item, true)" target="_blank" rel="noopener" class="flex items-center justify-center rounded-xl border border-green-200 px-3 text-green-700 hover:bg-green-50" aria-label="Remind on WhatsApp"><i class="fab fa-whatsapp"></i></a>
                            </div>
                        </div>
                    </template>
                </div>
            </section>
        </template>

        <section class="overflow-hidden rounded-[2rem] bg-white p-3 shadow-xl ring-1 ring-stone-100 sm:p-6">
            {{-- Month navigation --}}
            <div class="mb-4 flex items-center justify-between gap-2">
                <a href="{{ route('admin.agenda', ['month' => $previousMonth]) }}" class="flex h-11 w-11 items-center justify-center rounded-2xl border border-stone-200 text-gray-700 transition hover:border-olive hover:text-olive sm:w-auto sm:px-4" aria-label="Previous month">
                    <i class="fas fa-chevron-left"></i><span class="ml-2 hidden text-sm font-semibold sm:inline">Previous</span>
                </a>
                <div class="text-center">
                    <h2 class="text-xl font-semibold text-gray-900 sm:text-2xl">{{ $currentMonth->format('F Y') }}</h2>
                    @unless($currentMonth->isSameMonth(today()))
                        <a href="{{ route('admin.agenda') }}" class="text-xs font-semibold text-olive hover:underline">Back to today</a>
                    @endunless
                </div>
                <a href="{{ route('admin.agenda', ['month' => $nextMonth]) }}" class="flex h-11 w-11 items-center justify-center rounded-2xl border border-stone-200 text-gray-700 transition hover:border-olive hover:text-olive sm:w-auto sm:px-4" aria-label="Next month">
                    <span class="mr-2 hidden text-sm font-semibold sm:inline">Next</span><i class="fas fa-chevron-right"></i>
                </a>
            </div>

            {{-- Desktop: month grid --}}
            <div class="hidden md:block">
                <div class="mb-2 grid grid-cols-7 gap-2 text-center text-xs font-semibold uppercase tracking-[0.15em] text-gray-400">
                    <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span>
                </div>
                <div class="grid grid-cols-7 gap-2">
                    <template x-for="day in days" :key="day.date">
                        <article
                            class="group min-h-[140px] rounded-2xl border p-2 transition"
                            :class="[
                                day.inMonth ? 'border-stone-200 bg-white' : 'border-transparent bg-stone-50 opacity-60',
                                day.isToday ? 'ring-2 ring-olive/60' : '',
                                dragOverDate === day.date ? 'ring-2 ring-olive border-olive bg-olive/5' : ''
                            ]"
                            @dragover.prevent="dragOverDate = day.date"
                            @dragleave="dragOverDate = (dragOverDate === day.date) ? null : dragOverDate"
                            @drop.prevent="onDrop(day.date)"
                        >
                            <div class="mb-2 flex items-center justify-between">
                                <span class="text-sm font-bold" :class="day.isToday ? 'text-olive' : (day.isPast ? 'text-gray-400' : 'text-gray-800')" x-text="day.day"></span>
                                <button type="button" x-show="!day.isPast" @click="openDayMenu(day)" class="flex h-7 w-7 items-center justify-center rounded-full bg-olive/10 text-olive opacity-70 transition hover:bg-olive hover:text-white group-hover:opacity-100" :aria-label="'Add to ' + day.label">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>
                            <template x-for="block in day.hours" :key="block.id">
                                <button type="button" @click="openHours(block)" class="mb-1.5 block w-full rounded-lg bg-olive/10 px-2 py-1 text-left text-[11px] font-semibold text-olive hover:bg-olive/20">
                                    <i class="fas fa-clock mr-1"></i><span x-text="block.label"></span>
                                </button>
                            </template>
                            <template x-for="item in day.bookings" :key="item.id">
                                <div
                                    draggable="true"
                                    @dragstart="dragging = item"
                                    @dragend="dragging = null; dragOverDate = null"
                                    @click="openBooking(item)"
                                    class="mb-1.5 cursor-pointer rounded-lg border-l-4 px-2 py-1.5 text-[11px]"
                                    :class="chipClass(item)"
                                >
                                    <p class="truncate font-bold" x-text="item.time + ' · ' + item.customer"></p>
                                    <p class="truncate" x-text="item.services"></p>
                                    <p class="mt-0.5 text-[10px] font-semibold" x-text="depositLabel(item)"></p>
                                </div>
                            </template>
                            <p x-show="!day.hours.length && !day.bookings.length && day.inMonth && !day.isPast" class="pt-5 text-center text-[11px] text-gray-300">Closed</p>
                        </article>
                    </template>
                </div>
            </div>

            {{-- Mobile: day-by-day list --}}
            <div class="space-y-3 md:hidden">
                <template x-for="day in mobileDays" :key="day.date">
                    <article class="rounded-2xl border p-3" :class="day.isToday ? 'border-olive bg-olive/5' : 'border-stone-200 bg-white'">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 flex-col items-center justify-center rounded-xl" :class="day.isToday ? 'bg-olive text-white' : 'bg-stone-100 text-gray-700'">
                                    <span class="text-[10px] font-semibold uppercase leading-none" x-text="day.weekday"></span>
                                    <span class="text-base font-bold leading-tight" x-text="day.day"></span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900" x-text="day.isToday ? 'Today' : day.label"></p>
                                    <p class="text-xs text-gray-500" x-text="daySummary(day)"></p>
                                </div>
                            </div>
                            <button type="button" x-show="!day.isPast" @click="openDayMenu(day)" class="flex h-10 w-10 items-center justify-center rounded-full bg-olive/10 text-olive active:bg-olive active:text-white" :aria-label="'Add to ' + day.label">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div x-show="day.hours.length || day.bookings.length" class="mt-3 space-y-2">
                            <div class="flex flex-wrap gap-2">
                                <template x-for="block in day.hours" :key="block.id">
                                    <button type="button" @click="openHours(block)" class="rounded-full bg-olive/10 px-3 py-1.5 text-xs font-semibold text-olive">
                                        <i class="fas fa-clock mr-1"></i><span x-text="block.label"></span>
                                    </button>
                                </template>
                            </div>
                            <template x-for="item in day.bookings" :key="item.id">
                                <button type="button" @click="openBooking(item)" class="block w-full rounded-xl border-l-4 px-3 py-2 text-left" :class="chipClass(item)">
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="truncate text-sm font-bold" x-text="item.time + ' · ' + item.customer"></p>
                                        <span class="shrink-0 text-[10px] font-semibold" x-text="depositLabel(item)"></span>
                                    </div>
                                    <p class="truncate text-xs" x-text="item.services"></p>
                                </button>
                            </template>
                        </div>
                    </article>
                </template>
                <button type="button" x-show="hiddenPastDays > 0" @click="showPast = !showPast" class="w-full rounded-xl py-2 text-xs font-semibold text-gray-500" x-text="showPast ? 'Hide past days' : 'Show ' + hiddenPastDays + ' past days'"></button>
            </div>
        </section>

        <div class="mt-5 flex flex-wrap gap-x-5 gap-y-2 text-xs text-gray-600">
            <span class="inline-flex items-center"><i class="fas fa-clock mr-2 text-olive"></i>Open hours</span>
            <span class="inline-flex items-center"><i class="fas fa-circle mr-2 text-amber-500"></i>Deposit pending</span>
            <span class="inline-flex items-center"><i class="fas fa-circle mr-2 text-green-600"></i>Deposit paid</span>
            <span class="inline-flex items-center"><i class="fas fa-circle mr-2 text-sky-500"></i>Done</span>
            <span class="hidden items-center md:inline-flex"><i class="fas fa-arrows-alt mr-2 text-gray-400"></i>Drag a booking to another day to move it</span>
        </div>
    </div>

    {{-- ============ Day menu: what to add on a given day ============ --}}
    <x-sheet show="dayMenu.open" close="dayMenu.open = false" max-width="md:max-w-sm" label="Add to day">
        <div class="p-6 pt-3 md:pt-6">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-olive">Add to</p>
            <h3 class="mt-1 text-xl font-semibold text-gray-900" x-text="dayMenu.label"></h3>
            <div class="mt-5 space-y-3">
                <button type="button" @click="dayMenu.open = false; openHours({ date: dayMenu.date })" class="flex w-full items-center gap-4 rounded-2xl border border-stone-200 p-4 text-left transition hover:border-olive">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-olive/10 text-olive"><i class="fas fa-clock"></i></span>
                    <span><span class="block font-semibold text-gray-900">Open hours</span><span class="block text-xs text-gray-500">Let clients book this day</span></span>
                </button>
                <button type="button" @click="dayMenu.open = false; openNewBooking(dayMenu.date)" class="flex w-full items-center gap-4 rounded-2xl border border-stone-200 p-4 text-left transition hover:border-olive">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-olive/10 text-olive"><i class="fas fa-user-plus"></i></span>
                    <span><span class="block font-semibold text-gray-900">Add a booking</span><span class="block text-xs text-gray-500">A client who booked by DM, WhatsApp or phone</span></span>
                </button>
            </div>
        </div>
    </x-sheet>

    {{-- ============ Open hours (create / edit one block) ============ --}}
    <x-sheet show="hours.open" close="hours.open = false" label="Open hours">
        <form class="p-6 pt-3 md:pt-6" @submit.prevent="saveHours()">
            <div class="mb-5 flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-olive" x-text="hours.id ? 'Edit open hours' : 'Open hours'"></p>
                    <h3 class="mt-1 text-xl font-semibold text-gray-900" x-text="hours.date ? formatDate(hours.date, true) : 'Pick a day'"></h3>
                </div>
                <button type="button" @click="hours.open = false" class="flex h-9 w-9 items-center justify-center rounded-full text-gray-400 hover:bg-stone-100" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>

            <label class="mb-1 block text-sm font-medium text-gray-700" for="hours_date">Day</label>
            <input id="hours_date" type="date" x-model="hours.date" :min="today" required class="agenda-input">
            <p class="agenda-error" x-text="hours.errors.date?.[0]"></p>

            <p class="mb-2 mt-4 text-sm font-medium text-gray-700">Quick pick</p>
            <div class="flex flex-wrap gap-2">
                <template x-for="preset in hourPresets" :key="preset.label">
                    <button type="button" @click="hours.start_time = preset.start; hours.end_time = preset.end" class="rounded-full border px-3 py-1.5 text-xs font-semibold transition" :class="hours.start_time === preset.start && hours.end_time === preset.end ? 'border-olive bg-olive text-white' : 'border-stone-200 text-gray-600 hover:border-olive'" x-text="preset.label"></button>
                </template>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-3">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700" for="hours_start">From</label>
                    <input id="hours_start" type="time" step="900" x-model="hours.start_time" required class="agenda-input">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700" for="hours_end">Until</label>
                    <input id="hours_end" type="time" step="900" x-model="hours.end_time" required class="agenda-input">
                </div>
            </div>
            <p class="agenda-error" x-text="hours.errors.end_time?.[0] || hours.errors.start_time?.[0]"></p>

            <label class="mb-1 mt-4 block text-sm font-medium text-gray-700" for="hours_notes">Note (only you see it)</label>
            <input id="hours_notes" type="text" x-model="hours.notes" maxlength="1000" class="agenda-input" placeholder="Optional">

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                <button type="button" x-show="hours.id" @click="deleteHours()" class="rounded-2xl px-4 py-3 text-sm font-semibold text-red-600 hover:bg-red-50"><i class="fas fa-trash mr-2"></i>Close this day's hours</button>
                <button type="submit" :disabled="hours.saving" class="rounded-2xl bg-olive px-6 py-3 font-semibold text-white shadow-lg transition hover:bg-green-700 disabled:opacity-60 sm:ml-auto">
                    <span x-text="hours.saving ? 'Saving…' : 'Save hours'"></span>
                </button>
            </div>
        </form>
    </x-sheet>

    {{-- ============ Set up weeks (bulk) ============ --}}
    <x-sheet show="bulk.open" close="bulk.open = false" label="Set up weeks">
        <form class="p-6 pt-3 md:pt-6" @submit.prevent="saveBulk()">
            <div class="mb-5 flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-olive">Set up weeks</p>
                    <h3 class="mt-1 text-xl font-semibold text-gray-900">Open the same hours on many days</h3>
                </div>
                <button type="button" @click="bulk.open = false" class="flex h-9 w-9 items-center justify-center rounded-full text-gray-400 hover:bg-stone-100" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>

            <p class="mb-2 text-sm font-medium text-gray-700">Which days?</p>
            <div class="grid grid-cols-7 gap-1.5">
                <template x-for="wd in weekdayOptions" :key="wd.value">
                    <button type="button" @click="toggleWeekday(wd.value)" class="rounded-xl border py-2.5 text-xs font-bold transition" :class="bulk.weekdays.includes(wd.value) ? 'border-olive bg-olive text-white' : 'border-stone-200 text-gray-500'" x-text="wd.label"></button>
                </template>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-3">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700" for="bulk_from">From</label>
                    <input id="bulk_from" type="date" x-model="bulk.start_date" :min="today" required class="agenda-input">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700" for="bulk_to">Until</label>
                    <input id="bulk_to" type="date" x-model="bulk.end_date" :min="bulk.start_date" required class="agenda-input">
                </div>
            </div>
            <div class="mt-2 flex flex-wrap gap-2">
                <template x-for="weeks in [2, 4, 8, 12]" :key="weeks">
                    <button type="button" @click="setBulkWeeks(weeks)" class="rounded-full border border-stone-200 px-3 py-1 text-xs font-semibold text-gray-600 hover:border-olive" x-text="weeks + ' weeks'"></button>
                </template>
            </div>

            <p class="mb-2 mt-5 text-sm font-medium text-gray-700">Hours</p>
            <template x-for="(block, index) in bulk.blocks" :key="index">
                <div class="mb-2 flex items-center gap-2">
                    <input type="time" step="900" x-model="block.start_time" required class="agenda-input" aria-label="From">
                    <span class="text-gray-400">–</span>
                    <input type="time" step="900" x-model="block.end_time" required class="agenda-input" aria-label="Until">
                    <button type="button" x-show="bulk.blocks.length > 1" @click="bulk.blocks.splice(index, 1)" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-gray-400 hover:bg-red-50 hover:text-red-600" aria-label="Remove"><i class="fas fa-times"></i></button>
                </div>
            </template>
            <button type="button" x-show="bulk.blocks.length < 4" @click="bulk.blocks.push({ start_time: '14:00', end_time: '18:00' })" class="text-xs font-semibold text-olive hover:underline"><i class="fas fa-plus mr-1"></i>Add a break (second block)</button>

            <label class="mt-5 flex items-start gap-3 rounded-2xl bg-stone-50 p-3 text-sm text-gray-700">
                <input type="checkbox" x-model="bulk.replace_existing" class="mt-0.5 h-5 w-5 rounded border-stone-300 text-olive focus:ring-olive">
                <span>Replace hours I already opened on those days <span class="block text-xs text-gray-500">Bookings are never touched.</span></span>
            </label>

            <p class="mt-4 rounded-2xl bg-olive/5 px-4 py-3 text-sm text-olive" x-text="bulkPreview()"></p>
            <p class="agenda-error" x-text="bulk.error"></p>

            <button type="submit" :disabled="bulk.saving || !bulkDayCount()" class="mt-5 w-full rounded-2xl bg-olive px-6 py-3.5 font-semibold text-white shadow-lg transition hover:bg-green-700 disabled:opacity-50">
                <span x-text="bulk.saving ? 'Saving…' : 'Open these hours'"></span>
            </button>
        </form>
    </x-sheet>

    @include('admin.partials.booking-modal')

    {{-- ============ New booking (taken by Delfi) ============ --}}
    <x-sheet show="create.open" close="create.open = false" label="New booking">
        <form class="p-6 pt-3 md:pt-6" @submit.prevent="saveNewBooking()">
            <div class="mb-5 flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-olive">New booking</p>
                    <h3 class="mt-1 text-xl font-semibold text-gray-900">Add a client to the agenda</h3>
                </div>
                <button type="button" @click="create.open = false" class="flex h-9 w-9 items-center justify-center rounded-full text-gray-400 hover:bg-stone-100" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700" for="new_name">Client name</label>
                    <input id="new_name" type="text" x-model="create.form.name" required class="agenda-input" autocomplete="off">
                    <p class="agenda-error" x-text="create.errors.name?.[0]"></p>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700" for="new_phone">Phone / WhatsApp</label>
                        <input id="new_phone" type="tel" x-model="create.form.phone" class="agenda-input" placeholder="+353…">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700" for="new_email">Email <span class="text-gray-400">(optional)</span></label>
                        <input id="new_email" type="email" x-model="create.form.email" class="agenda-input">
                        <p class="agenda-error" x-text="create.errors.email?.[0]"></p>
                    </div>
                </div>

                <div>
                    <div class="mb-1 flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-700">Services</p>
                        <p class="text-xs font-semibold text-olive" x-show="create.form.service_ids.length" x-text="selectedSummary(create.form.service_ids)"></p>
                    </div>
                    <input type="search" x-model="create.search" placeholder="Search services…" class="agenda-input mb-2">
                    <div class="max-h-52 space-y-1 overflow-y-auto rounded-2xl border border-stone-200 p-1.5">
                        <template x-for="service in filteredServices()" :key="service.id">
                            <label class="flex cursor-pointer items-center justify-between gap-3 rounded-xl px-3 py-2.5 text-sm transition" :class="create.form.service_ids.includes(service.id) ? 'bg-olive/10' : 'hover:bg-stone-50'">
                                <span class="flex items-center gap-3">
                                    <input type="checkbox" :value="service.id" x-model.number="create.form.service_ids" class="h-5 w-5 rounded border-stone-300 text-olive focus:ring-olive">
                                    <span class="font-medium text-gray-800" x-text="service.name"></span>
                                </span>
                                <span class="shrink-0 text-xs text-gray-500" x-text="service.duration + ' min · €' + service.price.toFixed(0)"></span>
                            </label>
                        </template>
                    </div>
                    <p class="agenda-error" x-text="create.errors.service_ids?.[0]"></p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700" for="new_date">Day</label>
                        <input id="new_date" type="date" x-model="create.form.date" required class="agenda-input">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700" for="new_time">Time</label>
                        <input id="new_time" type="time" step="900" x-model="create.form.time" required class="agenda-input">
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700" for="new_notes">Notes</label>
                    <textarea id="new_notes" rows="2" x-model="create.form.notes" class="agenda-input" placeholder="Design ideas, allergies…"></textarea>
                </div>

                <label class="flex items-center gap-3 rounded-2xl bg-stone-50 p-3 text-sm text-gray-700">
                    <input type="checkbox" x-model="create.form.deposit_paid" class="h-5 w-5 rounded border-stone-300 text-olive focus:ring-olive">
                    <span>The €15 deposit is already paid</span>
                </label>

                <div x-show="create.conflict" class="rounded-2xl border border-amber-200 bg-amber-50 p-3 text-sm text-amber-900">
                    <p x-text="create.conflict"></p>
                    <button type="button" @click="saveNewBooking(true)" class="mt-2 text-xs font-bold underline">Save anyway</button>
                </div>
            </div>

            <button type="submit" :disabled="create.saving" class="mt-6 w-full rounded-2xl bg-olive px-6 py-3.5 font-semibold text-white shadow-lg transition hover:bg-green-700 disabled:opacity-60">
                <span x-text="create.saving ? 'Saving…' : 'Add booking'"></span>
            </button>
        </form>
    </x-sheet>

@endsection

@push('styles')
<style>
    .agenda-input {
        width: 100%;
        border-radius: 1rem;
        border: 1px solid #d6d3d1;
        padding: 0.7rem 0.9rem;
        font-size: 16px; /* avoids iOS zoom on focus */
        background: #fff;
    }
    .agenda-input:focus {
        outline: none;
        border-color: #554F13;
        box-shadow: 0 0 0 3px rgba(85, 79, 19, 0.15);
    }
    .agenda-error:not(:empty) {
        margin-top: 0.35rem;
        font-size: 0.75rem;
        color: #dc2626;
    }
</style>
@endpush

@push('scripts')
<script>
    function agendaBoard(config) {
        const addDays = (dateString, n) => {
            const d = new Date(dateString + 'T12:00:00');
            d.setDate(d.getDate() + n);
            return d.toISOString().slice(0, 10);
        };

        const board = {
            days: config.days,
            awaiting: config.awaiting,
            services: config.services,
            today: config.today,
            showPast: false,
            dragging: null,
            dragOverDate: null,

            hourPresets: [
                { label: 'Morning 9–13', start: '09:00', end: '13:00' },
                { label: 'Afternoon 14–18', start: '14:00', end: '18:00' },
                { label: 'Full day 9–18', start: '09:00', end: '18:00' },
                { label: 'Evening 17–21', start: '17:00', end: '21:00' },
            ],
            weekdayOptions: [
                { value: 1, label: 'Mon' }, { value: 2, label: 'Tue' }, { value: 3, label: 'Wed' },
                { value: 4, label: 'Thu' }, { value: 5, label: 'Fri' }, { value: 6, label: 'Sat' }, { value: 0, label: 'Sun' },
            ],

            dayMenu: { open: false, date: '', label: '' },
            hours: { open: false, id: null, date: '', start_time: '09:00', end_time: '18:00', notes: '', errors: {}, saving: false },
            bulk: { open: false, start_date: '', end_date: '', weekdays: [1, 2, 3, 4, 5], blocks: [{ start_time: '09:00', end_time: '18:00' }], replace_existing: false, saving: false, error: '' },
            create: { open: false, search: '', form: { name: '', phone: '', email: '', service_ids: [], date: '', time: '10:00', notes: '', deposit_paid: false }, errors: {}, conflict: '', saving: false },

            init() {
                const open = new URLSearchParams(window.location.search).get('open');
                if (open) {
                    const item = this.days.flatMap((d) => d.bookings).concat(this.awaiting).find((b) => String(b.id) === open);
                    if (item) this.openBooking(item);
                }
            },

            get mobileDays() {
                return this.days.filter((d) => d.inMonth && (this.showPast || !d.isPast || d.bookings.length));
            },
            get hiddenPastDays() {
                return this.days.filter((d) => d.inMonth && d.isPast && !d.bookings.length).length;
            },

            // ---------- helpers ----------
            daySummary(day) {
                const parts = [];
                if (day.hours.length) parts.push('Open ' + day.hours.map((h) => h.label).join(', '));
                if (day.bookings.length) parts.push(day.bookings.length + ' booking' + (day.bookings.length > 1 ? 's' : ''));
                return parts.length ? parts.join(' · ') : (day.isPast ? '—' : 'Closed');
            },

            // ---------- day menu ----------
            openDayMenu(day) {
                this.dayMenu = { open: true, date: day.date, label: this.formatDate(day.date, true) };
            },

            // ---------- open hours ----------
            openHours(block = {}) {
                this.hours = {
                    open: true,
                    id: block.id || null,
                    date: block.date || config.defaultDate,
                    start_time: block.start_time || '09:00',
                    end_time: block.end_time || '18:00',
                    notes: block.notes || '',
                    errors: {},
                    saving: false,
                };
            },
            async saveHours() {
                this.hours.saving = true;
                this.hours.errors = {};
                const payload = { date: this.hours.date, start_time: this.hours.start_time, end_time: this.hours.end_time, notes: this.hours.notes };
                try {
                    if (this.hours.id) {
                        await axios.put(`/admin/available-dates/${this.hours.id}`, payload);
                    } else {
                        await axios.post('/admin/available-dates', payload);
                    }
                    this.reload('Hours saved.');
                } catch (error) {
                    this.hours.errors = error.response?.data?.errors || {};
                    toastr.error(this.errorMessage(error, 'Could not save these hours.'));
                    this.hours.saving = false;
                }
            },
            deleteHours() {
                const id = this.hours.id;
                this.hours.open = false;
                this.ask('Close these hours?', 'Clients will no longer be able to book in this time. Existing bookings stay in the agenda.', 'Close hours', async () => {
                    try {
                        await axios.delete(`/admin/available-dates/${id}`);
                        this.reload('Hours removed.');
                    } catch (error) {
                        toastr.error(this.errorMessage(error, 'Could not remove these hours.'));
                    }
                });
            },

            // ---------- bulk ----------
            openBulk() {
                this.bulk.open = true;
                this.bulk.error = '';
                this.bulk.start_date = this.bulk.start_date || config.tomorrow;
                this.bulk.end_date = this.bulk.end_date || addDays(this.bulk.start_date, 27);
            },
            setBulkWeeks(weeks) {
                this.bulk.end_date = addDays(this.bulk.start_date || config.tomorrow, weeks * 7 - 1);
            },
            toggleWeekday(value) {
                const i = this.bulk.weekdays.indexOf(value);
                i === -1 ? this.bulk.weekdays.push(value) : this.bulk.weekdays.splice(i, 1);
            },
            bulkDayCount() {
                if (!this.bulk.start_date || !this.bulk.end_date || this.bulk.end_date < this.bulk.start_date) return 0;
                let count = 0;
                for (let d = this.bulk.start_date; d <= this.bulk.end_date; d = addDays(d, 1)) {
                    if (this.bulk.weekdays.includes(new Date(d + 'T12:00:00').getDay())) count++;
                    if (count > 400) break;
                }
                return count;
            },
            bulkPreview() {
                const count = this.bulkDayCount();
                if (!count) return 'Choose at least one day of the week.';
                const hours = this.bulk.blocks.map((b) => `${b.start_time}–${b.end_time}`).join(' and ');
                return `You will open ${hours} on ${count} day${count > 1 ? 's' : ''}.`;
            },
            async saveBulk() {
                this.bulk.saving = true;
                this.bulk.error = '';
                try {
                    const { data } = await axios.post('/admin/available-dates/bulk', {
                        start_date: this.bulk.start_date,
                        end_date: this.bulk.end_date,
                        weekdays: this.bulk.weekdays,
                        blocks: this.bulk.blocks,
                        replace_existing: this.bulk.replace_existing,
                    });
                    this.reload(data.message || 'Hours opened.');
                } catch (error) {
                    this.bulk.error = this.errorMessage(error, 'Could not open these hours.');
                    this.bulk.saving = false;
                }
            },

            // ---------- drag & drop (desktop) ----------
            async onDrop(dateKey) {
                const moving = this.dragging;
                this.dragOverDate = null;
                this.dragging = null;
                if (!moving || moving.date === dateKey) return;
                try {
                    await axios.patch(`/admin/appointments/${moving.id}/reschedule`, { date: dateKey });
                    this.reload(`Moved ${moving.customer} to ${this.formatDate(dateKey)}.`);
                } catch (error) {
                    toastr.error(this.errorMessage(error, 'Could not move this booking.'));
                }
            },

            // ---------- new booking ----------
            openNewBooking(date = null) {
                this.create = {
                    open: true,
                    search: '',
                    form: { name: '', phone: '', email: '', service_ids: [], date: date || config.defaultDate, time: '10:00', notes: '', deposit_paid: false },
                    errors: {},
                    conflict: '',
                    saving: false,
                };
            },
            filteredServices() {
                const q = this.create.search.trim().toLowerCase();
                return q ? this.services.filter((s) => s.name.toLowerCase().includes(q)) : this.services;
            },
            selectedSummary(ids) {
                const chosen = this.services.filter((s) => ids.includes(s.id));
                const minutes = chosen.reduce((sum, s) => sum + s.duration, 0);
                const price = chosen.reduce((sum, s) => sum + s.price, 0);
                return `${chosen.length} · ${minutes} min · €${price.toFixed(0)}`;
            },
            async saveNewBooking(force = false) {
                this.create.saving = true;
                this.create.errors = {};
                try {
                    await axios.post('/admin/bookings', { ...this.create.form, force });
                    this.reload('Booking added.');
                } catch (error) {
                    this.create.saving = false;
                    if (error.response?.status === 409) {
                        this.create.conflict = error.response.data.message;
                        return;
                    }
                    this.create.errors = error.response?.data?.errors || {};
                    toastr.error(this.errorMessage(error, 'Could not add the booking.'));
                }
            },
        };

        // Shared booking modal state/actions (see admin/partials/booking-modal).
        return Object.assign(board, bookingManager(config));
    }
</script>
@endpush
