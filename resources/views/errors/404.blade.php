@extends('layouts.app')

@section('title', 'Page Not Found - Nails by Delphina')
@section('robots', 'noindex')
@section('description', 'The page you are looking for could not be found. Head back to Nails by Delphina to book your appointment.')

@section('content')
<div class="flex min-h-[70vh] items-center justify-center bg-gradient-to-br from-nude via-white to-gray-50 px-4 py-20">
    <div class="mx-auto max-w-xl text-center">
        <p class="font-display text-8xl font-bold text-olive/20 sm:text-9xl">404</p>
        <h1 class="mt-4 text-3xl font-display font-bold text-brand-charcoal sm:text-4xl">
            This page has wandered off
        </h1>
        <p class="mx-auto mt-4 max-w-md text-lg text-gray-600">
            We couldn't find what you were looking for, but your next set is just a click away.
        </p>

        <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
            <a href="{{ route('home') }}" class="btn-primary px-8 py-4">
                <i class="fas fa-home mr-2"></i> Back to home
            </a>
            <a href="{{ route('booking.create') }}" class="btn-outline px-8 py-4">
                <i class="fas fa-calendar-check mr-2"></i> Book an appointment
            </a>
        </div>

        <div class="mt-8">
            <img src="{{ asset('img/logo_green.png') }}" alt="Nails by Delphina" class="mx-auto h-14 w-auto object-contain opacity-70">
        </div>
    </div>
</div>
@endsection
