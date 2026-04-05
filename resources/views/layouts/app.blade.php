<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title', 'Nail Art Studio - Professional Nails in Dublin')</title>
    <meta name="description" content="@yield('description', 'Professional nail studio with unique designs and premium treatments. Book your appointment online and discover why we are the preferred nail studio in Dublin.')">
    <meta name="keywords" content="@yield('keywords', 'nails, manicure, pedicure, nail art, nail studio, online booking, premium treatments, Dublin')">
    <meta name="author" content="Nail Art Studio">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Nail Art Studio - Professional Nails in Dublin')">
    <meta property="og:description" content="@yield('description', 'Professional nail studio with unique designs and premium treatments. Book your appointment online and discover why we are the preferred nail studio in Dublin.')">
    <meta property="og:image" content="@yield('og-image', 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=1200&h=630&fit=crop')">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', 'Nail Art Studio - Professional Nails in Dublin')">
    <meta property="twitter:description" content="@yield('description', 'Professional nail studio with unique designs and premium treatments. Book your appointment online and discover why we are the preferred nail studio in Dublin.')">
    <meta property="twitter:image" content="@yield('og-image', 'https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=1200&h=630&fit=crop')">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Schema.org structured data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BeautySalon",
        "name": "Nail Art Studio",
        "description": "Professional nail studio with unique designs and premium treatments",
        "url": "{{ url('/') }}",
        "telephone": "+353-1-123-4567",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Main Street 123",
            "addressLocality": "Dublin",
            "addressRegion": "Dublin",
            "postalCode": "D01 1AA",
            "addressCountry": "IE"
        },
        "openingHours": "Mo-Sa 09:00-18:00",
        "priceRange": "€€",
        "image": "https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?w=1200&h=630&fit=crop",
        "sameAs": [
            "https://instagram.com/nailartstudio"
        ]
    }
    </script>
</head>
<body class="font-sans antialiased bg-white text-gray-900">
    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-sm border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-light text-gray-900 hover:text-pink-600 transition-colors">
                        NAIL ART
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-pink-600 transition-colors font-medium">Home</a>
                    <a href="{{ route('booking.index') }}" class="text-gray-700 hover:text-pink-600 transition-colors font-medium">Book</a>
                    <a href="{{ route('home') }}#about" class="text-gray-700 hover:text-pink-600 transition-colors font-medium">About</a>

                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-pink-600 transition-colors font-medium">Admin</a>
                        @endif
                        <div class="relative">
                            <button onclick="toggleDropdown()" class="flex items-center text-gray-700 hover:text-pink-600 transition-colors font-medium">
                                <span>{{ auth()->user()->name }}</span>
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div id="dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-100">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-pink-600 transition-colors font-medium">Sign In</a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-pink-500 to-purple-600 text-white px-6 py-2 rounded-full hover:from-pink-600 hover:to-purple-700 transition-all font-medium">
                            Sign Up
                        </a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button onclick="toggleMobileMenu()" class="text-gray-700 hover:text-pink-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="hidden md:hidden pb-4">
                <div class="flex flex-col space-y-4">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-pink-600 transition-colors font-medium">Home</a>
                    <a href="{{ route('booking.index') }}" class="text-gray-700 hover:text-pink-600 transition-colors font-medium">Book</a>
                    <a href="{{ route('home') }}#about" class="text-gray-700 hover:text-pink-600 transition-colors font-medium">About</a>

                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-pink-600 transition-colors font-medium">Admin</a>
                        @endif
                        <a href="{{ route('profile.edit') }}" class="text-gray-700 hover:text-pink-600 transition-colors font-medium">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-left text-gray-700 hover:text-pink-600 font-medium">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-pink-600 transition-colors font-medium">Sign In</a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-pink-500 to-purple-600 text-white px-6 py-2 rounded-full hover:from-pink-600 hover:to-purple-700 transition-all font-medium text-center">
                            Sign Up
                        </a>
                    @endauth
                </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-50 border-t border-gray-100 py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <h3 class="text-2xl font-light text-gray-900 mb-4">NAIL ART</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Professional nail studio specializing in unique designs and premium treatments.
                        Your destination for perfect nails.
                    </p>
                    <div class="flex space-x-4">
                        <a href="https://instagram.com/nailartstudio" target="_blank" class="text-gray-400 hover:text-pink-600 transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Links</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-pink-600 transition-colors">Home</a></li>
                        <li><a href="{{ route('booking.index') }}" class="text-gray-600 hover:text-pink-600 transition-colors">Book Appointment</a></li>
                        <li><a href="{{ route('home') }}#about" class="text-gray-600 hover:text-pink-600 transition-colors">About</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Contact</h4>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-center">
                            <span class="mr-2">📞</span>
                            <a href="tel:+353123456789" class="hover:text-pink-600 transition-colors">+353 (0)1 234 5678</a>
                        </li>
                        <li class="flex items-center">
                            <span class="mr-2">📧</span>
                            <a href="mailto:info@nailartstudio.ie" class="hover:text-pink-600 transition-colors">info@nailartstudio.ie</a>
                        </li>
                        <li class="flex items-center">
                            <span class="mr-2">📍</span>
                            <span>Main Street 123, Dublin</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-200 mt-8 pt-8 text-center">
                <p class="text-gray-500 text-sm">
                    &copy; {{ date('Y') }} Nail Art Studio. All rights reserved.
                    <span class="block mt-2">Crafted with ❤️ for demanding clients</span>
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdown');
            dropdown.classList.toggle('hidden');
        }

        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('dropdown');
            const button = event.target.closest('button');
            if (!button || !button.onclick || !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

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
    </script>

    @yield('scripts')
</body>
</html>
