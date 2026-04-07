@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-nude via-white to-olive/10">
    <!-- Instagram-style background pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-20 left-20 w-32 h-32 rounded-full bg-olive"></div>
        <div class="absolute top-40 right-32 w-24 h-24 rounded-full bg-green-700"></div>
        <div class="absolute bottom-32 left-1/4 w-20 h-20 rounded-full bg-olive"></div>
        <div class="absolute bottom-20 right-20 w-16 h-16 rounded-full bg-nude"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 text-center px-4 sm:px-6 lg:px-8 max-w-5xl">
        <!-- Logo -->
        <div class=" flex justify-center">
           
        </div>

      

        <!-- Main Heading -->
        <h1 class="text-5xl md:text-6xl lg:text-7xl font-instagram font-bold mb-6 leading-tight">
            <span class="bg-gradient-to-r from-olive via-green-700 to-olive bg-clip-text text-transparent">
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
            <a href="{{ route('booking.create') }}" class="px-8 py-4 bg-gradient-to-r from-olive to-green-700 text-white rounded-full font-bold hover:shadow-xl transition-all transform hover:scale-105 text-lg inline-flex items-center justify-center shadow-lg">
                <i class="fas fa-sparkles mr-2"></i> Book Your Glow Up
            </a>

            <a href="#portfolio" class="px-8 py-4 border-2 border-brand-charcoal text-brand-charcoal rounded-full font-bold hover:bg-brand-charcoal hover:text-white transition-all text-lg inline-flex items-center justify-center">
                <i class="fab fa-instagram mr-2"></i> View My Work
            </a>
        </div>

        <!-- Social Proof -->
        <div class="flex justify-center items-center space-x-8 text-sm text-gray-500">
            <div class="flex items-center space-x-1">
                <i class="fab fa-instagram text-olive"></i>
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

<!-- Instagram Section -->
<section id="portfolio" class="py-24 bg-gradient-to-b from-nude/50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16">
           
            <h2 class="text-4xl md:text-5xl font-instagram font-bold text-brand-charcoal mb-6">
                Follow My <span class="bg-gradient-to-r from-olive to-green-700 bg-clip-text text-transparent">Journey</span>
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Get inspired by my latest creations, behind-the-scenes moments, and beauty tips
            </p>
        </div>

        <!-- Instagram Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-12">
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
            <div class="bg-gradient-to-r from-olive via-green-700 to-olive p-1 rounded-2xl inline-block">
                <a href="https://instagram.com/nailsbydelphina" target="_blank"
                   class="inline-flex items-center px-8 py-4 bg-white text-brand-charcoal rounded-xl font-bold hover:bg-gray-50 transition-all transform hover:scale-105 shadow-lg">
                    <i class="fab fa-instagram mr-3 text-olive"></i>
                    Follow @nailsbydelphina
                    <i class="fas fa-external-link-alt ml-2 text-sm"></i>
                </a>
            </div>
        </div>
    </div>
</section>


@endsection

