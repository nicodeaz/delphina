@extends('layouts.app')

@section('title', 'Nail Art Studio - Professional Nails in Dublin')
@section('description', 'Professional nail studio with unique designs and premium treatments. Book your appointment online and discover why we are the preferred nail studio in Dublin.')
@section('keywords', 'nails, manicure, pedicure, nail art, nail studio, online booking, premium treatments, Dublin')
@section('og-image', 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=1200&h=630&fit=crop')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-pink-50 via-white to-purple-50">
    <div class="absolute inset-0 bg-black bg-opacity-10"></div>

    <!-- Instagram Grid Background -->
    <div class="absolute inset-0 opacity-5">
        <div class="grid grid-cols-6 gap-1 h-full">
            @for($i = 1; $i <= 36; $i++)
            <div class="bg-gray-200"></div>
            @endfor
        </div>
    </div>

    <div class="relative z-10 text-center px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        <!-- Logo/Brand -->
        <div class="mb-8">
            <h1 class="text-6xl md:text-8xl font-light text-gray-900 mb-4 tracking-wider">
                NAIL ART
            </h1>
            <div class="w-24 h-1 bg-gradient-to-r from-pink-400 to-purple-400 mx-auto mb-6"></div>
            <p class="text-xl md:text-2xl text-gray-600 font-light">
                Professional Nail Studio
            </p>
        </div>

        <!-- CTA Button -->
        <div class="mb-12">
            @auth
                <a href="{{ route('booking.index') }}" class="inline-block bg-gradient-to-r from-pink-500 to-purple-600 text-white px-12 py-4 rounded-full text-xl font-medium hover:from-pink-600 hover:to-purple-700 transition-all transform hover:scale-105 shadow-2xl hover:shadow-pink-200">
                    Book Now
                </a>
            @else
                <a href="{{ route('booking.index') }}" class="inline-block bg-gradient-to-r from-pink-500 to-purple-600 text-white px-12 py-4 rounded-full text-xl font-medium hover:from-pink-600 hover:to-purple-700 transition-all transform hover:scale-105 shadow-2xl hover:shadow-pink-200">
                    Book Now
                </a>
            @endauth
        </div>

        <!-- Instagram Preview -->
        <div class="grid grid-cols-3 md:grid-cols-6 gap-2 max-w-2xl mx-auto">
            @for($i = 1; $i <= 6; $i++)
            <div class="aspect-square bg-white rounded-lg shadow-lg overflow-hidden group cursor-pointer transform hover:scale-105 transition-transform">
                <img src="https://images.unsplash.com/photo-1610992015732-2449b76344bc?w=200&h=200&fit=crop&crop=center" alt="Nail art {{ $i }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </div>
            @endfor
        </div>

        <p class="text-gray-500 mt-6 text-sm">Follow us on Instagram @nailartstudio</p>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </div>
</section>

<!-- Booking Section -->
<section id="booking" class="py-24 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-light text-gray-900 mb-4">Book Your Appointment</h2>
            <p class="text-xl text-gray-600">Choose your service and preferred time</p>
        </div>

        <div class="bg-gray-50 rounded-3xl p-8 md:p-12 shadow-xl">
            <form action="{{ route('booking.store') }}" method="POST" class="space-y-8">
                @csrf

                <!-- Service Selection -->
                <div>
                    <label class="block text-lg font-medium text-gray-900 mb-6 text-center">Which service do you need?</label>
                    <div class="grid md:grid-cols-3 gap-6">
                        @foreach($services as $service)
                        <div class="service-card bg-white border-2 border-gray-200 rounded-2xl p-6 hover:border-pink-300 hover:shadow-lg transition-all cursor-pointer group" data-service-id="{{ $service->id }}">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-gradient-to-br from-pink-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:from-pink-200 group-hover:to-purple-200 transition-all">
                                    <span class="text-2xl">💅</span>
                                </div>
                                <h3 class="text-xl font-medium text-gray-900 mb-2">{{ $service->name }}</h3>
                                <p class="text-gray-600 text-sm mb-4 leading-relaxed">{{ $service->description }}</p>
                                <div class="text-2xl font-bold text-pink-600 mb-4">€{{ $service->price }}</div>
                                <div class="text-sm text-gray-500">{{ $service->duration }} min</div>
                            </div>
                            <input type="radio" name="service_id" value="{{ $service->id }}" class="hidden service-radio" required>
                        </div>
                        @endforeach
                    </div>
                    @error('service_id')
                        <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date & Time -->
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <label for="date" class="block text-lg font-medium text-gray-900 mb-4">Date</label>
                        <input type="date" name="date" id="date" min="{{ date('Y-m-d', strtotime('+1 day')) }}" max="{{ date('Y-m-d', strtotime('+30 days')) }}"
                               class="w-full border-2 border-gray-300 rounded-xl px-6 py-4 text-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all"
                               required>
                        @error('date')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="time" class="block text-lg font-medium text-gray-900 mb-4">Time</label>
                        <select name="time" id="time" class="w-full border-2 border-gray-300 rounded-xl px-6 py-4 text-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all" required>
                            <option value="">Select a time</option>
                        </select>
                        @error('time')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="bg-gradient-to-r from-pink-500 to-purple-600 text-white px-12 py-4 rounded-full text-xl font-medium hover:from-pink-600 hover:to-purple-700 transition-all transform hover:scale-105 shadow-2xl hover:shadow-pink-200">
                        Confirm Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-24 bg-gradient-to-br from-pink-50 to-purple-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <!-- About Content -->
            <div>
                <h2 class="text-4xl md:text-5xl font-light text-gray-900 mb-8">About Me</h2>
                <div class="space-y-6 text-lg text-gray-700 leading-relaxed">
                    <p>
                        I'm passionate about nails with over 8 years of experience creating unique designs and premium treatments. My goal is to make every client feel special and leave my studio with perfect nails that reflect their personality.
                    </p>
                    <p>
                        I work with the highest quality products and innovative techniques to ensure lasting and natural results. Every design is personalized, from classic French manicures to the most daring nail art.
                    </p>
                    <p>
                        My studio is a welcoming space where wellness and creativity come together. Here we not only care for your nails, but also offer you a moment of relaxation and self-care.
                    </p>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-8 mt-12">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-pink-600 mb-2">500+</div>
                        <div class="text-gray-600">Happy Clients</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600 mb-2">8+</div>
                        <div class="text-gray-600">Years Experience</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-pink-600 mb-2">100%</div>
                        <div class="text-gray-600">Premium Products</div>
                    </div>
                </div>
            </div>

            <!-- About Image -->
            <div class="relative">
                <div class="aspect-square bg-gradient-to-br from-pink-200 to-purple-200 rounded-3xl overflow-hidden shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1582095133179-bfd08e2fc6b3?w=600&h=600&fit=crop" alt="Nail artist at work" class="w-full h-full object-cover">
                </div>
                <!-- Floating Elements -->
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-white rounded-full shadow-lg flex items-center justify-center">
                    <span class="text-2xl">✨</span>
                </div>
                <div class="absolute -bottom-4 -left-4 w-20 h-20 bg-pink-100 rounded-full shadow-lg flex items-center justify-center">
                    <span class="text-2xl">💅</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Instagram Gallery -->
<section class="py-24 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-light text-gray-900 mb-4">Follow us on Instagram</h2>
            <p class="text-xl text-gray-600">@nailartstudio</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @for($i = 1; $i <= 12; $i++)
            <div class="aspect-square bg-gray-100 rounded-xl overflow-hidden group cursor-pointer transform hover:scale-105 transition-transform shadow-lg hover:shadow-xl">
                <img src="https://images.unsplash.com/photo-1610992015732-2449b76344bc?w=300&h=300&fit=crop&crop=center" alt="Instagram post {{ $i }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center pb-4">
                    <div class="text-white text-center">
                        <div class="text-2xl mb-1">❤️</div>
                        <div class="text-sm">See more</div>
                    </div>
                </div>
            </div>
            @endfor
        </div>

        <div class="text-center mt-12">
            <a href="https://instagram.com/nailartstudio" target="_blank" class="inline-flex items-center bg-gradient-to-r from-pink-500 to-purple-600 text-white px-8 py-4 rounded-full hover:from-pink-600 hover:to-purple-700 transition-all transform hover:scale-105 shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                </svg>
                Seguir en Instagram
            </a>
        </div>
    </div>
</section>

<!-- Contact CTA -->
<section class="py-24 bg-gradient-to-r from-pink-500 to-purple-600">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl md:text-5xl font-light text-white mb-6">Ready for perfect nails?</h2>
        <p class="text-xl text-pink-100 mb-12 max-w-2xl mx-auto">Book your appointment today and discover why hundreds of clients choose me</p>
        <div class="space-x-6">
            @auth
                <a href="{{ route('booking.index') }}" class="inline-block bg-white text-pink-600 px-8 py-4 rounded-full hover:bg-gray-50 transition-all transform hover:scale-105 shadow-lg font-medium">
                    Book Now
                </a>
            @else
                <a href="{{ route('register') }}" class="inline-block bg-white text-pink-600 px-8 py-4 rounded-full hover:bg-gray-50 transition-all transform hover:scale-105 shadow-lg font-medium">
                    Create Account
                </a>
            @endauth
            <a href="tel:+34123456789" class="inline-block border-2 border-white text-white px-8 py-4 rounded-full hover:bg-white hover:text-pink-600 transition-all transform hover:scale-105 font-medium">
                Call Now
            </a>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceCards = document.querySelectorAll('.service-card');
    const dateInput = document.getElementById('date');
    const timeSelect = document.getElementById('time');

    // Service selection
    serviceCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove selected class from all cards
            serviceCards.forEach(c => {
                c.classList.remove('border-pink-500', 'bg-pink-50');
                c.querySelector('.service-radio').checked = false;
            });

            // Add selected class
            this.classList.add('border-pink-500', 'bg-pink-50');
            this.querySelector('.service-radio').checked = true;

            // Load available times if date is selected
            if (dateInput.value) {
                loadAvailableTimes();
            }
        });
    });

    // Date change
    dateInput.addEventListener('change', function() {
        if (document.querySelector('.service-radio:checked')) {
            loadAvailableTimes();
        }
    });

    function loadAvailableTimes() {
        const selectedService = document.querySelector('.service-radio:checked');
        if (!selectedService || !dateInput.value) return;

        timeSelect.innerHTML = '<option value="">Loading...</option>';

        fetch(`/api/services/${selectedService.value}/available-slots?date=${dateInput.value}`)
            .then(response => response.json())
            .then(data => {
                timeSelect.innerHTML = '<option value="">Select time</option>';
                if (data.available_slots && data.available_slots.length > 0) {
                    data.available_slots.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot;
                        option.textContent = slot;
                        timeSelect.appendChild(option);
                    });
                } else {
                    timeSelect.innerHTML = '<option value="">No available time slots</option>';
                }
            })
            .catch(error => {
                console.error('Error loading times:', error);
                timeSelect.innerHTML = '<option value="">Error loading time slots</option>';
            });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endsection