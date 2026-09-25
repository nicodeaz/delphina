<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $pageTitle = trim($__env->yieldContent('title', 'Nails by Delphina - Nail Technician in Tallaght, Dublin'));
        $pageDescription = trim($__env->yieldContent('description', 'Personalised BIAB, gel extensions and detailed nail art from a private studio in Tallaght, Dublin. Book your appointment online.'));
        $pageImage = trim($__env->yieldContent('og_image', asset('img/og-share.jpg')));
        $canonicalUrl = url()->current();
    @endphp

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="robots" content="{{ trim($__env->yieldContent('robots', 'index, follow')) }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    @if(config('services.google.site_verification'))
        <meta name="google-site-verification" content="{{ config('services.google.site_verification') }}">
    @endif
    <meta name="theme-color" content="#5e5720">

    <!-- Favicons -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon-16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('img/favicon-48.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Nails by Delphina">
    <meta property="og:locale" content="en_IE">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:image" content="{{ $pageImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Nails by Delphina - nail studio in Tallaght, Dublin">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $pageImage }}">

    <!-- Structured data (Local Business) -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "NailSalon",
        "@@id": "{{ url('/') }}#business",
        "name": "Nails by Delphina",
        "description": "Personalised BIAB, gel nails, soft gel extensions and nail art in a private studio in Tallaght, Dublin 24.",
        "image": "{{ asset('img/og-share.jpg') }}",
        "logo": "{{ asset('img/logo_green.png') }}",
        "areaServed": ["Tallaght", "Dublin 24", "South Dublin"],
        "currenciesAccepted": "EUR",
        "url": "{{ url('/') }}",
        "telephone": "+353899409670",
        "email": "d.mariamendonca@gmail.com",
        "priceRange": "€€",
        "address": {
            "@@type": "PostalAddress",
            "addressLocality": "Tallaght",
            "addressRegion": "Dublin",
            "addressCountry": "IE"
        },
        "sameAs": [
            "https://www.instagram.com/nailsbydelphina/"
        ],
        "openingHoursSpecification": [
            { "@@type": "OpeningHoursSpecification", "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday"], "opens": "09:00", "closes": "18:00" },
            { "@@type": "OpeningHoursSpecification", "dayOfWeek": ["Saturday"], "opens": "10:00", "closes": "17:00" }
        ]
    }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    

    <style>
        [x-cloak] {
            display: none !important;
        }

        @font-face {
            font-family: 'Ahsing';
            src: local('Ahsing');
            font-style: normal;
            font-weight: 400;
            font-display: swap;
        }

        :root {
            --brand-green: #554f13;
            --brand-green-deep: #413b0e;
            --brand-green-soft: #8d8540;
            --brand-surface: #fcfbf6;
            --brand-footer: #ece4d5;
            --brand-text: #2f2a09;
            --brand-muted: #5e5720;
        }

        body {
            font-family: 'Poppins', sans-serif;
            font-size: 17px;
            line-height: 1.65;
            color: var(--brand-text);
            background-color: var(--brand-surface);
        }

        h1, h2, h3, h4, .font-display, .font-instagram {
            font-family: 'Ahsing', 'Poppins', serif;
            letter-spacing: 0.02em;
        }

        p, li, a, button, input, select, textarea, label {
            font-family: 'Poppins', sans-serif;
        }

        .site-nav-link {
            position: relative;
            display: inline-flex;
            align-items: center;
            padding: 0.65rem 1rem;
            font-size: 1.02rem;
            color: var(--brand-text);
            font-weight: 700;
            letter-spacing: 0.02em;
            transition: color 0.2s ease;
        }

        .site-nav-link:hover {
            color: var(--brand-green);
        }

        .site-nav-link.is-active::after,
        .site-nav-link:hover::after {
            content: '';
            position: absolute;
            left: 1rem;
            right: 1rem;
            bottom: 0.35rem;
            height: 2px;
            border-radius: 9999px;
            background: linear-gradient(90deg, var(--brand-green), var(--brand-green-soft));
        }

        /* Transparent-on-load nav that solidifies on scroll (home page only) */
        .site-nav {
            transition: background-color 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease, backdrop-filter 0.35s ease;
            border-bottom: 1px solid transparent;
        }

        .site-nav--solid {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-bottom-color: var(--olive-100, #F1EDD7);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .site-nav--transparent {
            background-color: transparent;
            box-shadow: none;
        }

        .site-nav--transparent .site-nav-link {
            color: #fff;
            text-shadow: 0 1px 6px rgba(0, 0, 0, 0.35);
        }

        .site-nav--transparent .site-nav-link:hover,
        .site-nav--transparent .site-nav-link.is-active {
            color: #fff;
        }

        .site-nav--transparent .site-nav-link.is-active::after,
        .site-nav--transparent .site-nav-link:hover::after {
            background: #fff;
        }

        .site-nav--transparent .mobile-menu-btn i,
        .site-nav--transparent .site-nav-account-btn {
            color: #fff;
            text-shadow: 0 1px 6px rgba(0, 0, 0, 0.35);
        }

        .site-nav--transparent.is-scrolled {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-bottom-color: var(--olive-100, #F1EDD7);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
        }

        .site-nav--transparent.is-scrolled .site-nav-link,
        .site-nav--transparent.is-scrolled .mobile-menu-btn i,
        .site-nav--transparent.is-scrolled .site-nav-account-btn {
            color: var(--brand-text);
            text-shadow: none;
        }

        .site-nav--transparent.is-scrolled .site-nav-link.is-active::after,
        .site-nav--transparent.is-scrolled .site-nav-link:hover::after {
            background: linear-gradient(90deg, var(--brand-green), var(--brand-green-soft));
        }

        .mobile-nav-link {
            padding: 0.8rem 1rem;
            border-radius: 1rem;
            font-size: 1.02rem;
            color: var(--brand-text);
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .mobile-nav-link:hover,
        .mobile-nav-link.is-active {
            color: var(--brand-green);
            background: rgba(85, 79, 19, 0.08);
        }

        .footer-link {
            color: var(--brand-muted);
            transition: color 0.2s ease;
        }

        .footer-link:hover {
            color: var(--brand-green-deep);
        }

        html {
            scroll-behavior: smooth;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.65rem 1.5rem;
            border-radius: 9999px;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(90deg, var(--brand-green), var(--brand-green-soft));
            box-shadow: 0 10px 24px rgba(85, 79, 19, 0.28);
            transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 30px rgba(85, 79, 19, 0.34);
            opacity: 0.95;
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.65rem 1.5rem;
            border-radius: 9999px;
            font-weight: 700;
            border: 2px solid rgba(255, 255, 255, 0.7);
            color: #fff;
            backdrop-filter: blur(4px);
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .btn-outline:hover {
            background: #fff;
            color: var(--brand-charcoal, #36454F);
        }

        .admin-menu-link {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.55rem 1rem;
            font-size: 0.92rem;
            color: var(--brand-green-deep);
            font-weight: 600;
            border-radius: 0.75rem;
            transition: background-color 0.2s ease;
        }

        .admin-menu-link:hover {
            background: rgba(85, 79, 19, 0.08);
        }

        .page-loader {
            position: fixed;
            inset: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fcfbf6;
            opacity: 1;
            transition: opacity 0.35s ease, visibility 0.35s ease;
        }

        .page-loader.is-hidden {
            visibility: hidden;
            opacity: 0;
        }

        .page-loader-mark {
            animation: loader-pulse 1.8s ease-in-out infinite;
        }

        @keyframes loader-pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.06);
                opacity: 0.85;
            }
        }

        .page-loader-track {
            position: relative;
            width: 220px;
            height: 4px;
            border-radius: 9999px;
            background: rgba(94, 87, 32, 0.15);
            overflow: hidden;
        }

        .page-loader-bar {
            position: absolute;
            inset: 0 auto 0 0;
            width: 0%;
            border-radius: 9999px;
            background: linear-gradient(90deg, var(--brand-green-soft, #7a7328), var(--brand-green, #5e5720));
            transition: width 0.4s ease;
        }

        .page-loader-phrase {
            min-height: 1.2em;
            transition: opacity 0.25s ease;
        }

        .chatbot-launcher {
            position: fixed;
            right: 1.25rem;
            bottom: 1.25rem;
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 9999px;
            border: none;
            background: linear-gradient(135deg, var(--brand-green), var(--brand-green-soft));
            color: #fff;
            box-shadow: 0 12px 30px rgba(65, 59, 14, 0.28);
            z-index: 60;
            cursor: pointer;
        }

        .chatbot-panel {
            position: fixed;
            right: 1.25rem;
            bottom: 5.5rem;
            width: min(380px, calc(100vw - 2rem));
            background: #ffffff;
            border: 1px solid #e8e1d2;
            border-radius: 1rem;
            box-shadow: 0 20px 45px rgba(65, 59, 14, 0.2);
            overflow: hidden;
            z-index: 60;
        }

        .chatbot-msg-user {
            background: #f1edd7;
            color: var(--brand-green-deep);
        }

        .chatbot-msg-bot {
            background: #faf8ef;
            color: #2f2a09;
        }

        @media (max-width: 768px) {
            body {
                font-size: 16px;
                line-height: 1.55;
            }

            .mobile-nav-link {
                min-height: 48px;
                display: flex;
                align-items: center;
            }

            .chatbot-launcher {
                right: 0.9rem;
                bottom: 0.9rem;
                width: 3.25rem;
                height: 3.25rem;
            }

            .chatbot-panel {
                right: 0.5rem;
                bottom: 4.9rem;
                width: calc(100vw - 1rem);
                max-height: 75vh;
            }
        }
        /* Room for the slide-up booking bar / admin tab bar on phones */
        @media (max-width: 767px) {
            body.has-bottom-bar footer {
                padding-bottom: 7rem;
            }

            body.has-bottom-bar .chatbot-launcher {
                bottom: 6.25rem;
            }

            body.has-bottom-bar .chatbot-panel {
                bottom: 10.25rem;
                max-height: 60vh;
            }
        }
    </style>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Toast Notifications -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    @stack('styles')
</head>
@php
    $showBookingSheet = ! request()->routeIs('admin.*', 'booking.create', 'book', 'payments.*');
@endphp
<body class="font-sans antialiased bg-white text-gray-800 {{ ($showBookingSheet || request()->routeIs('booking.create', 'book')) ? 'has-bottom-bar' : '' }}">
    <div id="page-loader" class="page-loader" role="status" aria-live="polite">
        <div class="text-center">
            <div class="page-loader-mark mx-auto mb-5 flex h-16 w-16 items-center justify-center">
                <img src="{{ asset('img/logo_green.png') }}" alt="" class="h-12 w-auto object-contain">
            </div>
            <div class="page-loader-track mx-auto">
                <div id="pageLoaderBar" class="page-loader-bar"></div>
            </div>
            <p id="pageLoaderPhrase" class="page-loader-phrase mt-4 text-xs font-semibold uppercase tracking-[0.32em] text-brand-green-deep">Loading</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav id="siteNav" class="site-nav {{ request()->routeIs('home') ? 'site-nav--transparent' : 'site-nav--solid' }} fixed inset-x-0 top-0 z-50 w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-center items-center h-16 md:h-20">
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('home') }}" class="site-nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a>
                    <a href="{{ route('booking.create') }}" class="site-nav-link {{ request()->routeIs('booking.create') ? 'is-active' : '' }}">Services</a>
                    <a href="{{ route('policies') }}" class="site-nav-link {{ request()->routeIs('policies') ? 'is-active' : '' }}">Policies</a>

                    <a href="{{ route('booking.create') }}" class="btn-primary ml-3">
                        Book an appointment
                    </a>

                    @if(auth()->user()?->isAdmin())
                        <a href="{{ route('admin.agenda') }}" class="site-nav-account-btn ml-2 inline-flex items-center gap-2 rounded-full border border-olive/20 px-3 py-1.5 text-sm font-semibold text-olive transition hover:bg-olive hover:text-white">
                            <i class="fas fa-calendar-days"></i> Studio
                        </a>
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <button onclick="toggleMobileMenu()" class="mobile-menu-btn md:hidden p-2 hover:bg-olive-50 rounded-lg transition-colors">
                    <i class="fas fa-bars text-2xl text-gray-700"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-6 border-t border-olive-100 bg-white/95 backdrop-blur-md rounded-b-2xl">
                <div class="flex flex-col space-y-2 mt-4">
                    <a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a>
                    <a href="{{ route('booking.create') }}" class="mobile-nav-link {{ request()->routeIs('booking.create') ? 'is-active' : '' }}">Services</a>
                    <a href="{{ route('policies') }}" class="mobile-nav-link {{ request()->routeIs('policies') ? 'is-active' : '' }}">Policies</a>

                    <a href="{{ route('booking.create') }}" class="btn-primary mt-2 justify-center">
                        <i class="fas fa-sparkles mr-2"></i> Book an appointment
                    </a>

                    @if(auth()->user()?->isAdmin())
                        <a href="{{ route('admin.agenda') }}" class="mobile-nav-link"><i class="fas fa-calendar-days mr-2"></i> Studio admin</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-[calc(100vh-140px)] {{ request()->routeIs('home') ? '' : 'pt-16 md:pt-20' }}">
        @if(session('success'))
            <script>
                toastr.success(@json(session('success')), "Success!");
            </script>
        @endif
        @if(session('error'))
            <script>
                toastr.error(@json(session('error')), "Error!");
            </script>
        @endif
        
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#ece4d5] text-[#413b0e] py-16 border-t border-olive-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <!-- About -->
                <div>
                    <div class="mb-6 inline-flex items-center">
                        <img src="{{ asset('img/logo_green.png') }}" alt="Nails by Delphina" class="h-20 w-auto object-contain md:h-24">
                    </div>
                    <p class="max-w-xs text-[#5e5720] leading-relaxed">
                        Personalised BIAB, gel extensions and detailed nail art from a private studio in Tallaght.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-brand-green-deep font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="footer-link">Home</a></li>
                        <li><a href="{{ route('booking.create') }}" class="footer-link">Booking</a></li>
                        <li><a href="{{ route('booking.create') }}" class="footer-link">Services</a></li>
                    </ul>
                </div>

                    <!-- Hours -->
                    <div>
                        <h4 class="text-brand-green-deep font-semibold mb-4">Opening Hours</h4>
                        <ul class="space-y-2 text-[#5e5720]">    
                            <li>Monday - Friday: 09:00 - 18:00</li>
                            <li>Saturday: 10:00 - 17:00</li>
                            <li>Sunday: Closed</li>
                        </ul>
                    </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-brand-green-deep font-semibold mb-4">Contact</h4>
                    <ul class="space-y-3 text-[#5e5720]">
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-phone text-olive-600 mt-1"></i>
                            <a href="tel:+353899409670" class="footer-link">+353 89 940 9670</a>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-envelope text-olive-600 mt-1"></i>
                            <a href="mailto:d.mariamendonca@gmail.com" class="footer-link">d.mariamendonca@gmail.com</a>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-map-pin text-olive-600 mt-1"></i>
                            <span>The Square, Tallaght, Dublin</span>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="border-olive-100 my-8">

            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-[#5e5720] text-sm">&copy; {{ date('Y') }} Nails by Delphina · Site by Devnico</p>
                <ul class="flex space-x-6 text-[#5e5720] text-sm mt-4 md:mt-0">
                    <li><a href="{{ route('policies') }}" class="footer-link">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
    </footer>

    @if($showBookingSheet)
        @include('partials.booking-sheet')
    @endif

    @unless(request()->routeIs('booking.create', 'book', 'payments.*'))
    <!-- FAQ Chatbot (hidden on booking/payment pages, which have their own WhatsApp link) -->
    <button id="chatbot-launcher" class="chatbot-launcher" aria-label="Open support chat">
        <i class="fas fa-comments"></i>
    </button>

    <section id="chatbot-panel" class="chatbot-panel hidden" aria-live="polite">
        <div class="px-4 py-3 bg-olive text-white flex items-center justify-between">
            <div>
                <p class="font-semibold">Delphina Assistant</p>
                <p class="text-xs text-white/90">FAQ + quick support</p>
            </div>
            <button id="chatbot-close" class="text-white/90 hover:text-white" aria-label="Close chat">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div id="chatbot-messages" class="p-3 h-72 overflow-y-auto space-y-2 bg-white">
            <div class="chatbot-msg-bot rounded-xl px-3 py-2 text-sm">
                Hi! I can help with booking, deposits, opening hours and policies. If you need more details, use the Help button for WhatsApp.
            </div>
        </div>

        <div class="px-3 pb-2 flex flex-wrap gap-2 bg-white">
            <button class="chatbot-chip text-xs px-2 py-1 rounded-full bg-olive-50 text-olive-700" data-question="How do I book an appointment?">How to book</button>
            <button class="chatbot-chip text-xs px-2 py-1 rounded-full bg-olive-50 text-olive-700" data-question="What is the deposit?">Deposit</button>
            <button class="chatbot-chip text-xs px-2 py-1 rounded-full bg-olive-50 text-olive-700" data-question="What are your opening hours?">Opening hours</button>
            <button class="chatbot-chip text-xs px-2 py-1 rounded-full bg-olive-50 text-olive-700" data-question="How can I cancel or reschedule?">Cancel/Reschedule</button>
        </div>

        <div class="p-3 border-t border-olive-100 bg-white">
            <div class="flex gap-2">
                <input id="chatbot-input" type="text" class="flex-1 rounded-lg border border-olive-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-olive-300" placeholder="Type your question...">
                <button id="chatbot-send" class="px-3 py-2 rounded-lg bg-olive text-white text-sm font-semibold hover:bg-olive-700">Send</button>
            </div>
            <div class="mt-2">
                <a href="https://wa.me/353899409670" target="_blank" rel="noopener" class="inline-flex items-center justify-center w-full px-3 py-2 rounded-lg border border-olive-200 text-olive-700 text-sm font-semibold hover:bg-olive-50 transition-colors">
                    <i class="fab fa-whatsapp mr-2"></i> Need more help? Chat on WhatsApp
                </a>
            </div>
        </div>
    </section>
    @endunless

    <!-- Scripts -->
    <script>
        (function () {
            const loader = document.getElementById('page-loader');
            const bar = document.getElementById('pageLoaderBar');
            const phraseEl = document.getElementById('pageLoaderPhrase');
            if (!loader || !bar) return;

            const phrases = ['Loading', 'Preparing your studio', 'Almost ready', 'Loading your nail inspo'];
            let phraseIndex = 0;
            let progress = 12;
            bar.style.width = progress + '%';

            const phraseTimer = window.setInterval(() => {
                if (!phraseEl) return;
                phraseIndex = (phraseIndex + 1) % phrases.length;
                phraseEl.style.opacity = 0;
                window.setTimeout(() => {
                    phraseEl.textContent = phrases[phraseIndex];
                    phraseEl.style.opacity = 1;
                }, 250);
            }, 900);

            const progressTimer = window.setInterval(() => {
                progress = Math.min(progress + Math.random() * 12, 90);
                bar.style.width = progress + '%';
            }, 350);

            // Reveal the page as soon as the HTML is ready (images keep loading
            // in the background); never keep the loader up longer than 1.5s.
            let finished = false;
            const finish = () => {
                if (finished) return;
                finished = true;
                window.clearInterval(progressTimer);
                window.clearInterval(phraseTimer);
                bar.style.width = '100%';
                if (phraseEl) {
                    phraseEl.style.opacity = 0;
                    window.setTimeout(() => {
                        phraseEl.textContent = 'Ready!';
                        phraseEl.style.opacity = 1;
                    }, 200);
                }

                window.setTimeout(() => {
                    loader.classList.add('is-hidden');
                    window.setTimeout(() => loader.remove(), 400);
                }, 250);
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', finish);
            } else {
                finish();
            }
            window.setTimeout(finish, 1500);
        })();

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // Transparent-on-load nav that solidifies once the visitor scrolls (home page only).
        (function () {
            const siteNav = document.getElementById('siteNav');
            if (!siteNav || !siteNav.classList.contains('site-nav--transparent')) {
                return;
            }

            const SCROLL_THRESHOLD = 40;

            const updateNavState = () => {
                siteNav.classList.toggle('is-scrolled', window.scrollY > SCROLL_THRESHOLD);
            };

            updateNavState();
            window.addEventListener('scroll', updateNavState, { passive: true });
        })();

        // Toast notifications configuration
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
        };

        const whatsappNumber = '353899409670';
        const whatsappLink = `https://wa.me/${whatsappNumber}`;
        const chatbotLauncher = document.getElementById('chatbot-launcher');
        const chatbotPanel = document.getElementById('chatbot-panel');
        const chatbotClose = document.getElementById('chatbot-close');
        const chatbotMessages = document.getElementById('chatbot-messages');
        const chatbotInput = document.getElementById('chatbot-input');
        const chatbotSend = document.getElementById('chatbot-send');

        const faqRules = [
            {
                keys: ['book', 'booking', 'appointment', 'reserve', 'reserva', 'cita'],
                answer: 'You can book from the Booking page: choose service, date, time, then complete your details. A deposit is required to confirm your slot.'
            },
            {
                keys: ['deposit', 'fee', 'depósito', 'deposito'],
                answer: 'The booking deposit is €15. It is deducted from your final service total and secures your appointment.'
            },
            {
                keys: ['hours', 'opening', 'open', 'horario'],
                answer: 'Opening hours: Monday-Friday 09:00-18:00, Saturday 10:00-17:00, Sunday closed.'
            },
            {
                keys: ['cancel', 'cancellation', 'reschedule', 'change appointment', 'cancelar', 'reprogramar'],
                answer: 'You can reschedule free of charge with more than 24 hours notice. Late cancellations may lose the deposit according to our policy.'
            },
            {
                keys: ['location', 'address', 'where', 'ubicacion', 'dirección'],
                answer: 'We are located at The Square, Tallaght, Dublin.'
            }
        ];

        // Text is always inserted as text (never HTML) so nothing a visitor
        // types can run as markup; an optional link is appended as an element.
        function appendMessage(text, type = 'bot', link = null) {
            const msg = document.createElement('div');
            msg.className = `${type === 'user' ? 'chatbot-msg-user ml-10' : 'chatbot-msg-bot mr-10'} rounded-xl px-3 py-2 text-sm`;
            msg.textContent = text;
            if (link) {
                const a = document.createElement('a');
                a.href = link.href;
                a.target = '_blank';
                a.rel = 'noopener';
                a.className = 'ml-1 font-semibold text-olive-700 underline';
                a.textContent = link.label;
                msg.appendChild(a);
            }
            chatbotMessages.appendChild(msg);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }

        function getFaqAnswer(question) {
            const normalized = question.toLowerCase();
            const match = faqRules.find(rule => rule.keys.some(key => normalized.includes(key)));
            if (match) return { text: match.answer };
            return { text: 'I could not find an exact answer. For more details, message us on', link: { href: whatsappLink, label: 'WhatsApp (+353 89 940 9670)' } };
        }

        function submitChatbotQuestion(text) {
            const question = (text || '').trim();
            if (!question) return;
            appendMessage(question, 'user');
            const answer = getFaqAnswer(question);
            setTimeout(() => appendMessage(answer.text, 'bot', answer.link), 220);
        }

        chatbotLauncher?.addEventListener('click', () => chatbotPanel.classList.toggle('hidden'));
        chatbotClose?.addEventListener('click', () => chatbotPanel.classList.add('hidden'));
        chatbotSend?.addEventListener('click', () => {
            submitChatbotQuestion(chatbotInput.value);
            chatbotInput.value = '';
        });
        chatbotInput?.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                submitChatbotQuestion(chatbotInput.value);
                chatbotInput.value = '';
            }
        });
        document.querySelectorAll('.chatbot-chip').forEach(chip => {
            chip.addEventListener('click', () => submitChatbotQuestion(chip.dataset.question || ''));
        });
    </script>
    
    <script>
        // Page scripts call axios with paths like '/admin/...'. Resolve them
        // against the app's real base URL so they also work when the site
        // lives in a subfolder (e.g. http://localhost/delphina/public).
        // The Vite bundle that creates window.axios runs before this event.
        window.appBaseUrl = @json(url('/'));
        document.addEventListener('DOMContentLoaded', () => {
            if (window.axios) {
                window.axios.defaults.baseURL = window.appBaseUrl;
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
