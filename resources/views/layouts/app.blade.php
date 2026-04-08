<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Nail Art Studio - Professional Nails in Dublin')</title>
    <meta name="description" content="@yield('description', 'Professional nail studio with unique designs and premium treatments. Book your appointment online.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // Instagram-inspired color palette
                        'instagram-pink': '#E4405F',
                        'instagram-purple': '#8134AF',
                        'instagram-blue': '#0095F6',
                        'instagram-gradient-start': '#F56040',
                        'instagram-gradient-middle': '#F77737',
                        'instagram-gradient-end': '#FCAF45',
                        // Brand colors
                        'brand-pink': '#FF6B9D',
                        'brand-purple': '#C77DFF',
                        'brand-gold': '#FFD700',
                        'brand-cream': '#FFF8DC',
                        'brand-charcoal': '#36454F',
                        'brand-green': '#554F13',
                        'brand-green-deep': '#413B0E',
                        'brand-green-soft': '#8D8540',
                        // Neutral tones
                        beige: {
                            50: '#FBF7F1',
                            100: '#F2EBDD',
                        },
                        nude: '#F5E6D3',
                        rose: '#E8C4D4',
                        olive: {
                            DEFAULT: '#554F13',
                            50: '#FAF8EF',
                            100: '#F1EDD7',
                            200: '#DDD6AA',
                            300: '#C3BA73',
                            400: '#9F9544',
                            500: '#7B7125',
                            600: '#554F13',
                            700: '#413B0E',
                            800: '#2F2A09',
                        },
                        green: {
                            DEFAULT: '#554F13',
                            50: '#FAF8EF',
                            100: '#F1EDD7',
                            200: '#DDD6AA',
                            300: '#C3BA73',
                            400: '#9F9544',
                            500: '#7B7125',
                            600: '#554F13',
                            700: '#413B0E',
                            800: '#2F2A09',
                        },
                    },
                    fontFamily: {
                        'serif': ['Ahsing', 'Poppins', 'serif'],
                        'sans': ['Poppins', 'sans-serif'],
                        'display': ['Ahsing', 'Poppins', 'serif'],
                        'instagram': ['Ahsing', 'Poppins', 'serif'],
                    },
                    backgroundImage: {
                        'instagram-gradient': 'linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%)',
                        'brand-gradient': 'linear-gradient(135deg, #554F13 0%, #7B7125 55%, #9F9544 100%)',
                    }
                }
            }
        }
    </script>

    <style>
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
    </style>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Toast Notifications -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-white text-gray-800">
    <!-- Navigation -->
    @unless(request()->routeIs('home'))
    <nav class="bg-white/95 backdrop-blur-md border-b border-olive-100 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center group">
                    <img src="{{ Vite::asset('resources/img/logo_green.png') }}" alt="Delfina logo" class="h-14 md:h-16 w-auto object-contain">
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('home') }}" class="site-nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a>
                    <a href="{{ route('booking.create') }}" class="site-nav-link {{ request()->routeIs('booking.create') ? 'is-active' : '' }}">Booking</a>
                    <a href="{{ route('policies') }}" class="site-nav-link {{ request()->routeIs('policies') ? 'is-active' : '' }}">Policies</a>

                    @auth
                        

                        <div class="relative group">
                               <button class="px-4 py-2 flex items-center space-x-2 text-brand-green-deep hover:text-olive-600 transition-colors font-semibold">
                                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=E8C4D4&color=fff"
                                     alt="" class="w-8 h-8 rounded-full">
                                <span class="font-medium">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>

                            <div class="hidden group-hover:block absolute right-0 w-52 bg-white rounded-2xl shadow-xl py-2 border border-olive-100">
                                @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-brand-green-deep font-semibold hover:bg-olive-50 transition-colors">
                                        Dashboard
                                    </a>
                                    <a href="{{ route('admin.services.index') }}" class="block px-4 py-2 text-brand-green-deep font-semibold hover:bg-olive-50 transition-colors">
                                         Services
                                    </a>
                                    <a href="{{ route('admin.available-dates.index') }}" class="block px-4 py-2 text-brand-green-deep font-semibold hover:bg-olive-50 transition-colors">
                                      Available Dates
                                    </a>
                                    <a href="{{ route('admin.appointments.index') }}" class="block px-4 py-2 text-brand-green-deep font-semibold hover:bg-olive-50 transition-colors">
                                     Appointments
                                    </a>
                                    <a href="{{ route('admin.payments.index') }}" class="block px-4 py-2 text-brand-green-deep font-semibold hover:bg-olive-50 transition-colors">
                                         Payments
                                    </a>
                                @endif
                                <hr class="my-2">
                                <form method="POST" action="{{ route('admin.logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-brand-green-deep font-semibold hover:bg-olive-50 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Admin-only system - no public login/register needed -->
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button onclick="toggleMobileMenu()" class="md:hidden p-2 hover:bg-olive-50 rounded-lg transition-colors">
                    <i class="fas fa-bars text-2xl text-gray-700"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-6 border-t border-olive-100">
                <div class="flex flex-col space-y-3 mt-4">
                    <a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a>
                    <a href="{{ route('home') }}#services" class="mobile-nav-link">Services</a>
                    <a href="{{ route('booking.create') }}" class="mobile-nav-link {{ request()->routeIs('booking.create') ? 'is-active' : '' }}">Book</a>
                    <a href="{{ route('policies') }}" class="mobile-nav-link {{ request()->routeIs('policies') ? 'is-active' : '' }}">Policies</a>

                    @auth
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-olive to-green-700 hover:from-olive/90 hover:to-green-700/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-olive-500 transition-all transform hover:scale-105 shadow-lg">
                        <i class="fas fa-tachometer-alt mr-2"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.services.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-olive to-green-700 hover:from-olive/90 hover:to-green-700/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-olive-500 transition-all transform hover:scale-105 shadow-lg">
                        <i class="fas fa-concierge-bell mr-2"></i>
                        Services
                    </a>
                    <a href="{{ route('admin.available-dates.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-olive to-green-700 hover:from-olive/90 hover:to-green-700/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-olive-500 transition-all transform hover:scale-105 shadow-lg">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Available Dates
                    </a>
                    <a href="{{ route('admin.appointments.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-olive to-green-700 hover:from-olive/90 hover:to-green-700/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-olive-500 transition-all transform hover:scale-105 shadow-lg">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Appointments
                    </a>
                    <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-olive to-green-700 hover:from-olive/90 hover:to-green-700/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-olive-500 transition-all transform hover:scale-105 shadow-lg">
                        <i class="fas fa-credit-card mr-2"></i>
                        Payments
                    </a>
                    
                        @endif
                        <form method="POST" action="{{ route('admin.logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="mobile-nav-link w-full text-left">Logout</button>
                        </form>
                    @else
                        <!-- Admin-only system - no public login/register needed -->
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    @endunless

    <!-- Main Content -->
    <main class="min-h-[calc(100vh-140px)]">
        @if(session('success'))
            <script>
                toastr.success("{{ session('success') }}", "Success!");
            </script>
        @endif
        @if(session('error'))
            <script>
                toastr.error("{{ session('error') }}", "Error!");
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
                    <div class="flex items-center space-x-2 mb-4">
                       
                        <img src="{{ Vite::asset('resources/img/logo_green.png') }}" alt="Nail Art Logo" class="h-12 md:h-14 w-auto object-contain">
                    </div>
                    <p class="text-[#5e5720] leading-relaxed">
                        Professional nail studio with unique designs and premium treatments. Your destination for perfect nails in Dublin.
                    </p>    
                    <div class="flex space-x-4 mt-6">
                        <a href="#" class="text-[#5e5720] hover:text-olive-700 transition-colors"><i class="fab fa-instagram text-lg"></i></a>
                        <a href="#" class="text-[#5e5720] hover:text-olive-700 transition-colors"><i class="fab fa-facebook text-lg"></i></a>
                        <a href="#" class="text-[#5e5720] hover:text-olive-700 transition-colors"><i class="fab fa-tiktok text-lg"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-brand-green-deep font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="footer-link">Home</a></li>
                        <li><a href="{{ route('booking.create') }}" class="footer-link">Booking</a></li>
                        <li><a href="{{ route('home') }}#services" class="footer-link">Services</a></li>
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
                            <a href="tel:+353123456789" class="footer-link">+353 (0)1 234 5678</a>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-envelope text-olive-600 mt-1"></i>
                            <a href="mailto:info@delphina.ie" class="footer-link">info@delphina.ie</a>
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
                <p class="text-[#5e5720] text-sm">&copy; {{ date('Y') }} Devnico. All rights reserved.</p>
                <ul class="flex space-x-6 text-[#5e5720] text-sm mt-4 md:mt-0">
                    <li><a href="{{ route('policies') }}" class="footer-link">Privacy Policy</a></li>
                    <li><a href="{{ route('admin.login') }}" class="footer-link font-medium">Admin</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <!-- FAQ Chatbot -->
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
                <a href="https://wa.me/353123456789" target="_blank" rel="noopener" class="inline-flex items-center justify-center w-full px-3 py-2 rounded-lg border border-olive-200 text-olive-700 text-sm font-semibold hover:bg-olive-50 transition-colors">
                    <i class="fab fa-whatsapp mr-2"></i> Need more help? Chat on WhatsApp
                </a>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // Toast notifications configuration
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
        };

        const whatsappNumber = '353123456789';
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

        function appendMessage(text, type = 'bot') {
            const msg = document.createElement('div');
            msg.className = `${type === 'user' ? 'chatbot-msg-user ml-10' : 'chatbot-msg-bot mr-10'} rounded-xl px-3 py-2 text-sm`;
            msg.innerHTML = text;
            chatbotMessages.appendChild(msg);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }

        function getFaqAnswer(question) {
            const normalized = question.toLowerCase();
            const match = faqRules.find(rule => rule.keys.some(key => normalized.includes(key)));
            if (match) return match.answer;
            return `I could not find an exact answer. For more details, message us on <a class="text-olive-700 font-semibold underline" href="${whatsappLink}" target="_blank" rel="noopener">WhatsApp (+353 (0)1 234 5678)</a>.`;
        }

        function submitChatbotQuestion(text) {
            const question = (text || '').trim();
            if (!question) return;
            appendMessage(question, 'user');
            const answer = getFaqAnswer(question);
            setTimeout(() => appendMessage(answer, 'bot'), 220);
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
    
    @stack('scripts')
</body>
</html>
