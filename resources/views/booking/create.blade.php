@extends('layouts.app')

@section('title', 'Book a Nail Appointment in Tallaght | Nails by Delphina')
@section('description', 'Choose your nail service, pick a day and time, and secure your appointment with a €15 deposit via Revolut.')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-nude via-white to-beige-50">
    <section class="mx-auto max-w-2xl px-4 pb-16 pt-8 sm:px-6 md:pt-12">
        <div class="mb-6 text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-olive">Nails by Delphina</p>
            <h1 class="mt-3 text-3xl font-semibold text-brand-charcoal md:text-5xl">Book your appointment</h1>
            <p class="mx-auto mt-3 max-w-md text-sm text-gray-600 md:text-base">Three quick steps. You only pay a €15 deposit now via Revolut — the rest at the studio.</p>
        </div>

        @include('partials.booking-flow', ['mode' => 'page'])

        <div class="mt-6 grid grid-cols-3 gap-3 text-center text-xs text-gray-600">
            <div class="rounded-2xl bg-white/70 p-3"><i class="fas fa-lock mb-1 block text-olive"></i>Secure Revolut deposit</div>
            <div class="rounded-2xl bg-white/70 p-3"><i class="fas fa-rotate mb-1 block text-olive"></i>Free changes with 24h notice</div>
            <div class="rounded-2xl bg-white/70 p-3"><i class="fab fa-whatsapp mb-1 block text-olive"></i>Questions? <a href="https://wa.me/353899409670" class="font-semibold text-olive underline" target="_blank" rel="noopener">WhatsApp</a></div>
        </div>
    </section>
</div>
@endsection
