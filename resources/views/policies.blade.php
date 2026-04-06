@extends('layouts.app')

@section('title', 'Policies - Delfi Nail Technician')
@section('description', 'Booking policies, payment terms, and cancellation information for Delfi Nail Technician in Dublin 24, Tallaght.')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-nude via-white to-gray-50 py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-5xl md:text-6xl font-serif font-bold text-gray-900 mb-4">Our Policies</h1>
            <div class="flex justify-center mb-6">
                <div class="h-1 w-24 bg-gradient-to-r from-olive to-green-700 rounded"></div>
            </div>
            <p class="text-xl text-gray-600 font-light">
                Everything you need to know about booking and visiting our studio
            </p>
        </div>

        <div class="space-y-12">
            <!-- Booking Fee -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-olive to-green-700 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-euro-sign text-white font-bold"></i>
                    </div>
                    <h2 class="text-3xl font-serif font-bold text-gray-900">Booking Fee</h2>
                </div>
                <div class="prose prose-lg max-w-none text-gray-700">
                    <p class="mb-4">
                        A non-refundable booking fee of <strong class="text-olive font-bold">€15</strong> is required to secure your appointment.
                        This fee covers the reservation of your time slot and preparation materials.
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-gray-600">
                        <li>The booking fee is deducted from your final service cost</li>
                        <li>No-shows or cancellations within 24 hours forfeit the booking fee</li>
                        <li>Rescheduling is free if done more than 24 hours in advance</li>
                    </ul>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-olive to-green-700 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-credit-card text-white font-bold"></i>
                    </div>
                    <h2 class="text-3xl font-serif font-bold text-gray-900">Payment Methods</h2>
                </div>
                <div class="prose prose-lg max-w-none text-gray-700">
                    <p class="mb-4">We accept the following payment methods:</p>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-money-bill-wave text-olive text-xl"></i>
                            <span class="font-medium">Cash</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-university text-olive text-xl"></i>
                            <span class="font-medium">Bank Transfer</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fab fa-cc-visa text-olive text-xl"></i>
                            <span class="font-medium">Card Payment</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fab fa-paypal text-olive text-xl"></i>
                            <span class="font-medium">PayPal</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Punctuality Policy -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-olive to-green-700 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-clock text-white font-bold"></i>
                    </div>
                    <h2 class="text-3xl font-serif font-bold text-gray-900">Punctuality Policy</h2>
                </div>
                <div class="prose prose-lg max-w-none text-gray-700">
                    <p class="mb-4">
                        We value your time and ours. Please arrive 5-10 minutes early for your appointment.
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-gray-600">
                        <li>Late arrivals may result in shortened services</li>
                        <li>Arrivals more than 15 minutes late may forfeit the appointment</li>
                        <li>We reserve the right to reschedule if you're significantly late</li>
                        <li>We'll always try to accommodate you if you contact us in advance</li>
                    </ul>
                </div>
            </div>

            <!-- Cancellation Policy -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-olive to-green-700 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-calendar-times text-white font-bold"></i>
                    </div>
                    <h2 class="text-3xl font-serif font-bold text-gray-900">Cancellation Policy</h2>
                </div>
                <div class="prose prose-lg max-w-none text-gray-700">
                    <p class="mb-4">
                        We understand that plans can change. Please give us as much notice as possible.
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-gray-600">
                        <li>Cancellations more than 48 hours in advance: Full refund of booking fee</li>
                        <li>Cancellations 24-48 hours in advance: 50% refund of booking fee</li>
                        <li>Cancellations less than 24 hours in advance: No refund</li>
                        <li>No-shows: No refund and may affect future bookings</li>
                    </ul>
                </div>
            </div>

            <!-- Appointment Only -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-olive to-green-700 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-user-check text-white font-bold"></i>
                    </div>
                    <h2 class="text-3xl font-serif font-bold text-gray-900">Appointment Only</h2>
                </div>
                <div class="prose prose-lg max-w-none text-gray-700">
                    <p class="mb-4">
                        Due to high demand and to ensure the best service for all our clients, we operate on an appointment-only basis.
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-gray-600">
                        <li>Walk-ins are not accepted</li>
                        <li>All services require advance booking</li>
                        <li>Emergency appointments may be available - please call us</li>
                        <li>We recommend booking at least 1-2 weeks in advance</li>
                    </ul>
                </div>
            </div>

            <!-- Health Disclosure -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-olive to-green-700 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-heartbeat text-white font-bold"></i>
                    </div>
                    <h2 class="text-3xl font-serif font-bold text-gray-900">Health & Safety Disclosure</h2>
                </div>
                <div class="prose prose-lg max-w-none text-gray-700">
                    <p class="mb-4">
                        Your health and safety are our top priorities. Please inform us of any relevant health conditions.
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-gray-600">
                        <li>Please inform us if you are pregnant or have any medical conditions</li>
                        <li>Allergies to products must be disclosed before treatment</li>
                        <li>We maintain strict hygiene standards and sterilization protocols</li>
                        <li>Our products are professional-grade and suitable for all skin types</li>
                        <li>We follow all local health and safety regulations</li>
                    </ul>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-gradient-to-r from-olive to-green-700 rounded-2xl p-8 text-white text-center">
                <h2 class="text-3xl font-serif font-bold mb-4">Questions?</h2>
                <p class="text-xl mb-6 text-white/90">
                    If you have any questions about our policies or need to make changes to your booking, please don't hesitate to contact us.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="tel:+353123456789" class="inline-flex items-center px-6 py-3 bg-white text-olive rounded-full font-bold hover:shadow-xl transition-all">
                        <i class="fas fa-phone mr-2"></i> Call Us
                    </a>
                    <a href="mailto:info@delfinailtechnician.ie" class="inline-flex items-center px-6 py-3 bg-white text-olive rounded-full font-bold hover:shadow-xl transition-all">
                        <i class="fas fa-envelope mr-2"></i> Email Us
                    </a>
                </div>
                <p class="mt-6 text-white/80">
                    <strong>Delfi Nail Technician</strong><br>
                    Dublin 24, Tallaght<br>
                    Open: Monday-Saturday 9AM-7PM
                </p>
            </div>
        </div>
    </div>
</div>
@endsection