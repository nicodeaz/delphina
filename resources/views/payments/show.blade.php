@extends('layouts.app')

@section('title', 'Pay your deposit - Nails by Delphina')
@section('robots', 'noindex, nofollow')

@php
    $total = $bookingAppointments->sum(fn ($a) => (float) ($a->service->price ?? 0));
    $duration = $bookingAppointments->sum(fn ($a) => (int) ($a->service->duration ?? 0));
    $remaining = max(0, $total - $payment->amount);
    $whenLabel = $appointment->date->format('l j F').' at '.substr($appointment->time, 0, 5);
    $alreadyRedirected = $payment->payment_method === 'revolut';
    $whatsappText = rawurlencode("Hi Delfi! I just booked {$bookingAppointments->pluck('service.name')->filter()->join(' + ')} for {$whenLabel} and sent the €".number_format($payment->amount, 0)." deposit. Name: {$appointment->name}");
@endphp

@section('content')
<div class="min-h-screen bg-gradient-to-br from-nude via-white to-beige-50">
    <div class="mx-auto max-w-lg px-4 pb-16 pt-8 sm:px-6 md:pt-12">
        <div class="text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-olive text-white shadow-lg">
                <i class="fas fa-calendar-check text-xl"></i>
            </div>
            <h1 class="mt-4 text-2xl font-semibold text-gray-900 md:text-3xl">Your spot is held</h1>
            <p class="mx-auto mt-2 max-w-sm text-sm text-gray-600">Pay the €{{ number_format($payment->amount, 0) }} deposit to confirm it. You'll get a confirmation email once Delfi receives it.</p>
        </div>

        {{-- Booking summary --}}
        <div class="mt-6 rounded-[1.75rem] bg-white p-5 shadow-xl ring-1 ring-stone-100">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-olive">{{ $whenLabel }}</p>
            <ul class="mt-3 space-y-2">
                @foreach($bookingAppointments as $item)
                    <li class="flex items-start justify-between gap-3 text-sm">
                        <span class="font-medium text-gray-900">{{ $item->service->name ?? 'Service' }}</span>
                        <span class="shrink-0 text-gray-600">€{{ number_format($item->service->price ?? 0, 2) }}</span>
                    </li>
                @endforeach
            </ul>
            <div class="mt-4 space-y-1.5 border-t border-stone-100 pt-4 text-sm">
                <div class="flex justify-between text-gray-600"><span>Total · {{ $duration }} min</span><span>€{{ number_format($total, 2) }}</span></div>
                <div class="flex justify-between text-base font-bold text-gray-900"><span>Deposit now</span><span>€{{ number_format($payment->amount, 2) }}</span></div>
                <div class="flex justify-between text-gray-500"><span>Rest, at the studio</span><span>€{{ number_format($remaining, 2) }}</span></div>
            </div>
        </div>

        {{-- Pay --}}
        <div class="mt-5 rounded-[1.75rem] bg-white p-5 shadow-xl ring-1 ring-stone-100">
            @if($paymentConfigured)
                <ol class="space-y-3 text-sm text-gray-700">
                    <li class="flex gap-3"><span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-olive/10 text-xs font-bold text-olive">1</span><span>Tap the button — Revolut opens with <strong>€{{ number_format($payment->amount, 2) }}</strong>.</span></li>
                    <li class="flex gap-3"><span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-olive/10 text-xs font-bold text-olive">2</span><span>Add your name <strong>“{{ $appointment->name }}”</strong> in the note and send.</span></li>
                    <li class="flex gap-3"><span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-olive/10 text-xs font-bold text-olive">3</span><span>Delfi confirms it and you receive an email. Done!</span></li>
                </ol>

                <form action="{{ route('payments.process', $appointment) }}" method="POST" class="mt-5" x-data="{ sending: false }" @submit="sending = true">
                    @csrf
                    <button type="submit" :disabled="sending" class="flex w-full items-center justify-center gap-2 rounded-2xl bg-gray-900 px-6 py-4 text-base font-semibold text-white shadow-lg transition hover:bg-black disabled:opacity-70">
                        <span x-show="!sending">Pay €{{ number_format($payment->amount, 2) }} with Revolut</span>
                        <span x-show="sending" x-cloak><i class="fas fa-circle-notch fa-spin mr-2"></i>Opening Revolut…</span>
                        <i x-show="!sending" class="fas fa-arrow-up-right-from-square text-sm"></i>
                    </button>
                </form>
                <p class="mt-3 text-center text-xs text-gray-500">No Revolut app? The link also opens in your browser.</p>
            @else
                <p class="text-sm text-gray-700">Online payment isn't available right now. Please message Delfi on WhatsApp to pay your €{{ number_format($payment->amount, 2) }} deposit.</p>
            @endif

            @if($alreadyRedirected)
                <div class="mt-5 rounded-2xl border border-green-200 bg-green-50 p-4 text-sm text-green-900">
                    <p class="font-semibold"><i class="fas fa-check-circle mr-1"></i>Already paid?</p>
                    <p class="mt-1">Thank you! Delfi will confirm your deposit shortly and you'll get an email. Nothing else to do.</p>
                </div>
            @endif

            <a href="https://wa.me/353899409670?text={{ $whatsappText }}" target="_blank" rel="noopener" class="mt-4 flex w-full items-center justify-center gap-2 rounded-2xl border border-green-200 px-6 py-3 text-sm font-semibold text-green-700 transition hover:bg-green-50">
                <i class="fab fa-whatsapp text-lg"></i>Message Delfi
            </a>
        </div>

        <p class="mt-6 text-center text-xs text-gray-500">
            Your appointment is confirmed once the deposit is received. See our <a href="{{ route('policies') }}" class="font-semibold text-olive underline">booking policy</a>.
        </p>
        <a href="{{ route('home') }}" class="mt-3 block text-center text-sm font-medium text-gray-600 hover:text-gray-900">← Back to home</a>
    </div>
</div>
@endsection
