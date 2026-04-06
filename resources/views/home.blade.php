@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-brand-cream via-white to-brand-pink/10">
    <!-- Instagram-style background pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-20 left-20 w-32 h-32 rounded-full bg-instagram-pink"></div>
        <div class="absolute top-40 right-32 w-24 h-24 rounded-full bg-instagram-purple"></div>
        <div class="absolute bottom-32 left-1/4 w-20 h-20 rounded-full bg-instagram-blue"></div>
        <div class="absolute bottom-20 right-20 w-16 h-16 rounded-full bg-brand-gold"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 text-center px-4 sm:px-6 lg:px-8 max-w-5xl">
        <!-- Logo -->
        <div class="mb-8 flex justify-center">
            @include('partials.logo')
        </div>

        <!-- Instagram-style badges -->
        <div class="flex justify-center gap-6 mb-8 flex-wrap">
            <div class="flex items-center space-x-2 bg-white/80 backdrop-blur-sm px-4 py-2 rounded-full shadow-lg">
                <span class="text-2xl">💅</span>
                <span class="text-sm font-semibold text-brand-charcoal">Premium Quality</span>
            </div>
            <div class="flex items-center space-x-2 bg-white/80 backdrop-blur-sm px-4 py-2 rounded-full shadow-lg">
                <span class="text-2xl">⭐</span>
                <span class="text-sm font-semibold text-brand-charcoal">5★ Reviews</span>
            </div>
            <div class="flex items-center space-x-2 bg-white/80 backdrop-blur-sm px-4 py-2 rounded-full shadow-lg">
                <span class="text-2xl">👑</span>
                <span class="text-sm font-semibold text-brand-charcoal">Expert Technician</span>
            </div>
        </div>

        <!-- Main Heading -->
        <h1 class="text-5xl md:text-6xl lg:text-7xl font-instagram font-bold mb-6 leading-tight">
            <span class="bg-gradient-to-r from-instagram-pink via-instagram-purple to-instagram-blue bg-clip-text text-transparent">
                Perfect Nails
            </span>
            <br class="hidden md:block">
            <span class="text-brand-charcoal">Made Simple</span>
        </h1>

        <!-- Subheading -->
        <p class="text-lg md:text-xl text-gray-600 mb-12 max-w-2xl mx-auto leading-relaxed font-instagram">
            Transform your nails with premium gel extensions, BIAB, soft gel overlays, and stunning nail art.
            Dublin's most trusted nail technician in Tallaght.
        </p>

        <!-- Instagram-style CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
            <a href="{{ route('booking.create') }}" class="px-8 py-4 bg-gradient-to-r from-instagram-pink to-instagram-purple text-white rounded-full font-bold hover:shadow-xl transition-all transform hover:scale-105 text-lg inline-flex items-center justify-center shadow-lg">
                <i class="fas fa-sparkles mr-2"></i> Book Your Glow Up
            </a>

            <a href="#portfolio" class="px-8 py-4 border-2 border-brand-charcoal text-brand-charcoal rounded-full font-bold hover:bg-brand-charcoal hover:text-white transition-all text-lg inline-flex items-center justify-center">
                <i class="fab fa-instagram mr-2"></i> View My Work
            </a>
        </div>

        <!-- Social Proof -->
        <div class="flex justify-center items-center space-x-8 text-sm text-gray-500">
            <div class="flex items-center space-x-1">
                <i class="fab fa-instagram text-instagram-pink"></i>
                <span>@nailsbydelphina</span>
            </div>
            <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
            <div>500+ Happy Clients</div>
            <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
            <div>8+ Years Experience</div>
        </div>
    </div>

    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <i class="fas fa-chevron-down text-brand-charcoal text-2xl"></i>
    </div>
</section>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </div>
</section>

<!-- Services Section - Instagram Inspired Design -->
<section id="services" class="py-24 bg-gradient-to-b from-white via-brand-cream/30 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-16">
            <div class="inline-flex items-center gap-2 mb-4">
                <div class="h-1 w-12 bg-gradient-to-r from-instagram-pink to-instagram-purple"></div>
                <span class="text-sm font-semibold bg-gradient-to-r from-instagram-pink to-instagram-purple bg-clip-text text-transparent uppercase tracking-wider">Our Services</span>
                <div class="h-1 w-12 bg-gradient-to-r from-instagram-purple to-instagram-blue"></div>
            </div>
            <h2 class="text-4xl md:text-5xl font-instagram font-bold text-brand-charcoal mb-6">
                Premium Nail <span class="bg-gradient-to-r from-instagram-pink to-instagram-purple bg-clip-text text-transparent">Services</span>
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                From natural extensions to stunning nail art, discover the perfect treatment for your style
            </p>
        </div>
            </div>
            <h2 class="text-5xl md:text-6xl font-serif font-bold text-gray-900 mb-6">
                Professional Nail Services
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl">
                Curated selection of premium nail treatments designed for your comfort and satisfaction
            </p>
        </div>

        <!-- Services Grid - Minimal Design -->
        <div class="grid lg:grid-cols-2 gap-8 mb-16">
            <!-- Services List -->
            <div class="space-y-3">
                <!-- Gel Nails Services -->
                <div class="border border-gray-200 rounded-2xl overflow-hidden hover:border-olive/30 hover:shadow-lg transition-all duration-300">
                    <button class="service-category w-full px-6 py-4 bg-white hover:bg-gray-50 flex items-center justify-between font-semibold text-lg text-gray-900 cursor-pointer" data-category="gel">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">💅</span>
                            <span>Gel Nails</span>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </button>
                    <div class="service-options hidden bg-gray-50 divide-y divide-gray-100">
                        <label class="px-6 py-4 flex items-center gap-3 hover:bg-olive/5 cursor-pointer transition-colors" data-service="Gel Nails - Plain Colour" data-price="50" data-duration="120">
                            <input type="checkbox" class="service-checkbox w-5 h-5 rounded border-gray-300 text-olive focus:ring-olive" />
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">Plain Colour</div>
                                <div class="text-sm text-gray-500">120 min</div>
                            </div>
                            <span class="font-semibold text-olive">€50</span>
                        </label>
                        <label class="px-6 py-4 flex items-center gap-3 hover:bg-olive/5 cursor-pointer transition-colors" data-service="Gel Nails - French Tip / Ombre" data-price="55" data-duration="120">
                            <input type="checkbox" class="service-checkbox w-5 h-5 rounded border-gray-300 text-olive focus:ring-olive" />
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">French Tip / Ombre</div>
                                <div class="text-sm text-gray-500">120 min</div>
                            </div>
                            <span class="font-semibold text-olive">€55</span>
                        </label>
                        <label class="px-6 py-4 flex items-center gap-3 hover:bg-olive/5 cursor-pointer transition-colors" data-service="Gel Nails - Infills / Refills" data-price="45" data-duration="90">
                            <input type="checkbox" class="service-checkbox w-5 h-5 rounded border-gray-300 text-olive focus:ring-olive" />
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">Infills / Refills</div>
                                <div class="text-sm text-gray-500">90 min</div>
                            </div>
                            <span class="font-semibold text-olive">€45</span>
                        </label>
                    </div>
                </div>

                <!-- BIAB Services -->
                <div class="border border-gray-200 rounded-2xl overflow-hidden hover:border-olive/30 hover:shadow-lg transition-all duration-300">
                    <button class="service-category w-full px-6 py-4 bg-white hover:bg-gray-50 flex items-center justify-between font-semibold text-lg text-gray-900 cursor-pointer" data-category="biab">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">✨</span>
                            <span>BIAB</span>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </button>
                    <div class="service-options hidden bg-gray-50 divide-y divide-gray-100">
                        <label class="px-6 py-4 flex items-center gap-3 hover:bg-olive/5 cursor-pointer transition-colors" data-service="BIAB - Clear or Nude Base" data-price="30" data-duration="60">
                            <input type="checkbox" class="service-checkbox w-5 h-5 rounded border-gray-300 text-olive focus:ring-olive" />
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">Clear or Nude Base</div>
                                <div class="text-sm text-gray-500">60 min</div>
                            </div>
                            <span class="font-semibold text-olive">€30</span>
                        </label>
                        <label class="px-6 py-4 flex items-center gap-3 hover:bg-olive/5 cursor-pointer transition-colors" data-service="BIAB - With Colour" data-price="35" data-duration="60">
                            <input type="checkbox" class="service-checkbox w-5 h-5 rounded border-gray-300 text-olive focus:ring-olive" />
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">With Colour</div>
                                <div class="text-sm text-gray-500">60 min</div>
                            </div>
                            <span class="font-semibold text-olive">€35</span>
                        </label>
                        <label class="px-6 py-4 flex items-center gap-3 hover:bg-olive/5 cursor-pointer transition-colors" data-service="BIAB - With Nail Art" data-price="40" data-duration="75">
                            <input type="checkbox" class="service-checkbox w-5 h-5 rounded border-gray-300 text-olive focus:ring-olive" />
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">With Nail Art</div>
                                <div class="text-sm text-gray-500">75 min</div>
                            </div>
                            <span class="font-semibold text-olive">€40</span>
                        </label>
                    </div>
                </div>

                <!-- Soft Gel Extensions -->
                <div class="border border-gray-200 rounded-2xl overflow-hidden hover:border-olive/30 hover:shadow-lg transition-all duration-300">
                    <button class="service-category w-full px-6 py-4 bg-white hover:bg-gray-50 flex items-center justify-between font-semibold text-lg text-gray-900 cursor-pointer" data-category="softgel">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">💄</span>
                            <span>Soft Gel Extensions</span>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </button>
                    <div class="service-options hidden bg-gray-50 divide-y divide-gray-100">
                        <label class="px-6 py-4 flex items-center gap-3 hover:bg-olive/5 cursor-pointer transition-colors" data-service="Soft Gel - Plain Colour" data-price="40" data-duration="90">
                            <input type="checkbox" class="service-checkbox w-5 h-5 rounded border-gray-300 text-olive focus:ring-olive" />
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">Plain Colour</div>
                                <div class="text-sm text-gray-500">90 min</div>
                            </div>
                            <span class="font-semibold text-olive">€40</span>
                        </label>
                        <label class="px-6 py-4 flex items-center gap-3 hover:bg-olive/5 cursor-pointer transition-colors" data-service="Soft Gel - French Tip / Ombre" data-price="45" data-duration="90">
                            <input type="checkbox" class="service-checkbox w-5 h-5 rounded border-gray-300 text-olive focus:ring-olive" />
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">French Tip / Ombre</div>
                                <div class="text-sm text-gray-500">90 min</div>
                            </div>
                            <span class="font-semibold text-olive">€45</span>
                        </label>
                        <label class="px-6 py-4 flex items-center gap-3 hover:bg-olive/5 cursor-pointer transition-colors" data-service="Soft Gel - Infills / Refills" data-price="35" data-duration="75">
                            <input type="checkbox" class="service-checkbox w-5 h-5 rounded border-gray-300 text-olive focus:ring-olive" />
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">Infills / Refills</div>
                                <div class="text-sm text-gray-500">75 min</div>
                            </div>
                            <span class="font-semibold text-olive">€35</span>
                        </label>
                    </div>
                </div>

                <!-- Gel Polish -->
                <div class="border border-gray-200 rounded-2xl overflow-hidden hover:border-olive/30 hover:shadow-lg transition-all duration-300">
                    <button class="service-category w-full px-6 py-4 bg-white hover:bg-gray-50 flex items-center justify-between font-semibold text-lg text-gray-900 cursor-pointer" data-category="polish">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">🎨</span>
                            <span>Gel Polish</span>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </button>
                    <div class="service-options hidden bg-gray-50 divide-y divide-gray-100">
                        <label class="px-6 py-4 flex items-center gap-3 hover:bg-olive/5 cursor-pointer transition-colors" data-service="Gel Polish - On Natural Nails" data-price="25" data-duration="45">
                            <input type="checkbox" class="service-checkbox w-5 h-5 rounded border-gray-300 text-olive focus:ring-olive" />
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">On Natural Nails</div>
                                <div class="text-sm text-gray-500">45 min</div>
                            </div>
                            <span class="font-semibold text-olive">€25</span>
                        </label>
                        <label class="px-6 py-4 flex items-center gap-3 hover:bg-olive/5 cursor-pointer transition-colors" data-service="Gel Polish - Removal & Reapplication" data-price="30" data-duration="60">
                            <input type="checkbox" class="service-checkbox w-5 h-5 rounded border-gray-300 text-olive focus:ring-olive" />
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">Removal & Reapplication</div>
                                <div class="text-sm text-gray-500">60 min</div>
                            </div>
                            <span class="font-semibold text-olive">€30</span>
                        </label>
                        <label class="px-6 py-4 flex items-center gap-3 hover:bg-olive/5 cursor-pointer transition-colors" data-service="Gel Polish - Removal Only" data-price="12" data-duration="30">
                            <input type="checkbox" class="service-checkbox w-5 h-5 rounded border-gray-300 text-olive focus:ring-olive" />
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">Removal Only</div>
                                <div class="text-sm text-gray-500">30 min</div>
                            </div>
                            <span class="font-semibold text-olive">€12</span>
                        </label>
                    </div>
                </div>

                <!-- Add-ons & Extras -->
                <div class="border border-gray-200 rounded-2xl overflow-hidden hover:border-olive/30 hover:shadow-lg transition-all duration-300">
                    <button class="service-category w-full px-6 py-4 bg-white hover:bg-gray-50 flex items-center justify-between font-semibold text-lg text-gray-900 cursor-pointer" data-category="addons">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">✨</span>
                            <span>Add-ons & Extras</span>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </button>
                    <div class="service-options hidden bg-gray-50 divide-y divide-gray-100">
                        <label class="px-6 py-4 flex items-center gap-3 hover:bg-olive/5 cursor-pointer transition-colors" data-service="Add-on - Nail Art" data-price="10" data-duration="15">
                            <input type="checkbox" class="service-checkbox w-5 h-5 rounded border-gray-300 text-olive focus:ring-olive" />
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">Nail Art Design</div>
                                <div class="text-sm text-gray-500">+15 min</div>
                            </div>
                            <span class="font-semibold text-olive">€10</span>
                        </label>
                        <label class="px-6 py-4 flex items-center gap-3 hover:bg-olive/5 cursor-pointer transition-colors" data-service="Add-on - Cuticle Treatment" data-price="5" data-duration="15">
                            <input type="checkbox" class="service-checkbox w-5 h-5 rounded border-gray-300 text-olive focus:ring-olive" />
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">Cuticle Treatment</div>
                                <div class="text-sm text-gray-500">+15 min</div>
                            </div>
                            <span class="font-semibold text-olive">€5</span>
                        </label>
                        <label class="px-6 py-4 flex items-center gap-3 hover:bg-olive/5 cursor-pointer transition-colors" data-service="Add-on - Hand Massage" data-price="8" data-duration="15">
                            <input type="checkbox" class="service-checkbox w-5 h-5 rounded border-gray-300 text-olive focus:ring-olive" />
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">Mini Hand Massage</div>
                                <div class="text-sm text-gray-500">+15 min</div>
                            </div>
                            <span class="font-semibold text-olive">€8</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Selection Summary & Available Dates -->
            <div class="sticky top-8">
                <!-- Selection Cart -->
                <div class="bg-white rounded-2xl border border-gray-200 p-8 mb-8 shadow-sm">
                    <h3 class="font-semibold text-lg text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-olive" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"></path>
                        </svg>
                        Your Selection
                    </h3>
                    
                    <div id="selectedServices" class="space-y-3 mb-6">
                        <p class="text-gray-500 text-sm">Select services to begin</p>
                    </div>

                    <!-- Duration & Total -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Total Duration</span>
                            <span class="font-semibold text-gray-900" id="totalDuration">0 min</span>
                        </div>
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-gray-600">Total Price</span>
                            <span class="text-2xl font-bold text-olive" id="totalPrice">€0</span>
                        </div>

                        <!-- CTA - Simple and Clean -->
                        <button type="button" class="w-full mt-8 bg-olive hover:bg-olive-700 text-white font-semibold py-4 px-6 rounded-xl transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none shadow-lg" id="bookNowBtn" disabled>
                            ✨ Book Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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
            bookNowBtn.disabled = true;
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
            bookNowBtn.disabled = false;
        }

        // Calculate totals
        const totalDuration = Object.values(selectedServices).reduce((sum, s) => sum + s.duration, 0);
        const totalPrice = Object.values(selectedServices).reduce((sum, s) => sum + s.price, 0);

        totalDurationEl.textContent = `${totalDuration} min`;
        totalPriceEl.textContent = `€${totalPrice}`;
    }

    // Book button - send to booking page with selected services
    bookNowBtn.addEventListener('click', function() {
        if (Object.keys(selectedServices).length > 0) {
            const serviceNames = Object.keys(selectedServices).map(name => 
                encodeURIComponent(name)
            ).join(',');
            window.location.href = `{{ route('book') }}?services=${serviceNames}`;
        }
    });
});
</script>

<!-- Instagram Section -->
<section id="portfolio" class="py-24 bg-gradient-to-b from-brand-cream/50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <div class="inline-flex items-center gap-3 mb-6">
                <i class="fab fa-instagram text-3xl text-instagram-pink"></i>
                <span class="text-2xl font-bold text-instagram-pink">@nailsbydelphina</span>
                <i class="fab fa-instagram text-3xl text-instagram-pink"></i>
            </div>
            <h2 class="text-4xl md:text-5xl font-instagram font-bold text-brand-charcoal mb-6">
                Follow My <span class="bg-gradient-to-r from-instagram-pink to-instagram-purple bg-clip-text text-transparent">Journey</span>
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Get inspired by my latest creations, behind-the-scenes moments, and beauty tips
            </p>
        </div>

        <!-- Instagram Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-12">
            @php
                $instagramPosts = [
                    ['image' => '/img/instagram/gel-nails-floral.jpg', 'likes' => '127', 'comments' => '8', 'caption' => 'Floral gel nails for spring 🌸 #gelnails #nailart'],
                    ['image' => '/img/instagram/biab-natural.jpg', 'likes' => '89', 'comments' => '12', 'caption' => 'BIAB extensions with natural look 💅 #biab #naturalnails'],
                    ['image' => '/img/instagram/soft-gel-overlay.jpg', 'likes' => '156', 'comments' => '15', 'caption' => 'Soft gel overlay perfection ✨ #softgel #nailtech'],
                    ['image' => '/img/instagram/french-tips.jpg', 'likes' => '203', 'comments' => '22', 'caption' => 'Classic French tips never go out of style 💅 #frenchtips #classic'],
                    ['image' => '/img/instagram/nail-art-design.jpg', 'likes' => '178', 'comments' => '19', 'caption' => 'Custom nail art design 🎨 #nailart #customnails'],
                    ['image' => '/img/instagram/client-transformation.jpg', 'likes' => '145', 'comments' => '11', 'caption' => 'Before & after transformation! 🙌 #nailtransformation'],
                    ['image' => '/img/instagram/studio-setup.jpg', 'likes' => '92', 'comments' => '7', 'caption' => 'My cozy studio setup 🏠 #nailstudio #behindthescenes'],
                    ['image' => '/img/instagram/color-collection.jpg', 'likes' => '134', 'comments' => '16', 'caption' => 'New color collection arrived! 🌈 #nailpolish #colors'],
                ];
            @endphp
            @foreach($instagramPosts as $post)
            <div class="group relative overflow-hidden rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 cursor-pointer">
                <!-- Image -->
                <div class="aspect-square overflow-hidden">
                    <img src="{{ $post['image'] }}"
                         alt="Instagram Post" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>

                <!-- Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300">
                    <!-- Instagram-style overlay -->
                    <div class="absolute top-3 right-3 flex space-x-1">
                        <div class="bg-black/50 backdrop-blur-sm rounded-full p-2">
                            <i class="far fa-heart text-white text-sm"></i>
                        </div>
                        <div class="bg-black/50 backdrop-blur-sm rounded-full p-2">
                            <i class="far fa-comment text-white text-sm"></i>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="absolute bottom-0 left-0 right-0 p-4">
                        <div class="flex items-center space-x-4 text-white text-sm font-medium">
                            <span><i class="fas fa-heart mr-1"></i>{{ $post['likes'] }}</span>
                            <span><i class="fas fa-comment mr-1"></i>{{ $post['comments'] }}</span>
                        </div>
                        <p class="text-white text-xs mt-2 line-clamp-2">{{ $post['caption'] }}</p>
                    </div>
                </div>

                <!-- Hover effect border -->
                <div class="absolute inset-0 border-2 border-transparent group-hover:border-white/50 rounded-2xl transition-all duration-300"></div>
            </div>
            @endforeach
        </div>

        <!-- Follow CTA -->
        <div class="text-center">
            <div class="bg-gradient-to-r from-instagram-pink via-instagram-purple to-instagram-blue p-1 rounded-2xl inline-block">
                <a href="https://instagram.com/nailsbydelphina" target="_blank"
                   class="inline-flex items-center px-8 py-4 bg-white text-brand-charcoal rounded-xl font-bold hover:bg-gray-50 transition-all transform hover:scale-105 shadow-lg">
                    <i class="fab fa-instagram mr-3 text-instagram-pink"></i>
                    Follow @nailsbydelphina
                    <i class="fas fa-external-link-alt ml-2 text-sm"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-olive/95 to-green-700/95">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl md:text-5xl font-serif font-bold text-white mb-6">Ready for Perfect Nails?</h2>
        <p class="text-xl text-white/90 mb-12">
            Book your appointment today and let our professionals transform your nails.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('book') }}" class="px-12 py-4 bg-white text-olive rounded-lg font-bold text-lg hover:shadow-xl transition-all transform hover:scale-105">
                <i class="fas fa-calendar-alt mr-2"></i> Book Now
            </a>
            
            <a href="tel:+34123456789" class="px-12 py-4 border-2 border-white text-white rounded-lg font-bold text-lg hover:bg-white/10 transition-all">
                <i class="fas fa-phone mr-2"></i> Call Us
            </a>
        </div>
    </div>
</section>

@endsection

