<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Nail Art Studio - Professional Nails in Dublin')</title>
    <meta name="description" content="@yield('description', 'Professional nail studio with unique designs and premium treatments. Book your appointment online.')">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
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
                        // Neutral tones
                        nude: '#F5E6D3',
                        rose: '#E8C4D4',
                        olive: '#8B9A7C',
                    },
                    fontFamily: {
                        'serif': ['Playfair Display', 'serif'],
                        'sans': ['Poppins', 'sans-serif'],
                        'instagram': ['Poppins', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    backgroundImage: {
                        'instagram-gradient': 'linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%)',
                        'brand-gradient': 'linear-gradient(135deg, #FF6B9D 0%, #C77DFF 50%, #FFD700 100%)',
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Toast Notifications -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-white text-gray-800" style="font-family: 'Poppins', sans-serif;">
    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-md border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center group">
                    <img src="{{ Vite::asset('resources/img/logo_green.png') }}" alt="Delfina logo" class="h-12 w-auto">
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('home') }}" class="px-4 py-2 text-gray-700 hover:text-instagram-pink transition-colors font-medium">Home</a>
                    <a href="{{ route('booking.create') }}" class="px-4 py-2 text-gray-700 hover:text-instagram-pink transition-colors font-medium">Booking
                         </a>

                    @auth
                        

                        <div class="relative group">
                            <button class="px-4 py-2 flex items-center space-x-2 text-gray-700 hover:text-rose transition-colors">
                                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&background=E8C4D4&color=fff"
                                     alt="" class="w-8 h-8 rounded-full">
                                <span class="font-medium">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>

                            <div class="hidden group-hover:block absolute right-0 w-48 bg-white rounded-lg shadow-xl py-2 border border-gray-100">
                                @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-nude transition-colors">
                                        Dashboard
                                    </a>
                                    <a href="{{ route('admin.services.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-nude transition-colors">
                                         Services
                                    </a>
                                    <a href="{{ route('admin.available-dates.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-nude transition-colors">
                                      Available Dates
                                    </a>
                                    <a href="{{ route('admin.appointments.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-nude transition-colors">
                                     Appointments
                                    </a>
                                    <a href="{{ route('admin.payments.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-nude transition-colors">
                                         Payments
                                    </a>
                                @endif
                                <hr class="my-2">
                                <form method="POST" action="{{ route('admin.logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-nude transition-colors">
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
                <button onclick="toggleMobileMenu()" class="md:hidden p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-bars text-2xl text-gray-700"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-6 border-t border-gray-200">
                <div class="flex flex-col space-y-3 mt-4">
                    <a href="{{ route('home') }}" class="px-4 py-2 text-gray-700 hover:text-olive hover:bg-gray-50 rounded transition-colors">Home</a>
                    <a href="{{ route('home') }}#services" class="px-4 py-2 text-gray-700 hover:text-olive hover:bg-gray-50 rounded transition-colors">Services</a>
                    <a href="{{ route('booking.create') }}" class="px-4 py-2 text-gray-700 hover:text-olive hover:bg-gray-50 rounded transition-colors">Book</a>
                    <a href="{{ route('policies') }}" class="px-4 py-2 text-gray-700 hover:text-olive hover:bg-gray-50 rounded transition-colors">Policies</a>

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
                            <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:text-olive hover:bg-gray-50 rounded transition-colors">Logout</button>
                        </form>
                    @else
                        <!-- Admin-only system - no public login/register needed -->
                    @endauth
                </div>
            </div>
        </div>
    </nav>

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
    <footer class="bg-gray-900 text-gray-300 py-16 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <!-- About -->
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                       
                        <img src="{{ Vite::asset('resources/img/logo.png') }}" alt="Nail Art Logo" class="h-8 w-auto">
                    </div>
                    <p class="text-gray-400 leading-relaxed">
                        Professional nail studio with unique designs and premium treatments. Your destination for perfect nails in Dublin.
                    </p>    
                    <div class="flex space-x-4 mt-6">
                        <a href="#" class="text-gray-400 hover:text-rose transition-colors"><i class="fab fa-instagram text-lg"></i></a>
                        <a href="#" class="text-gray-400 hover:text-rose transition-colors"><i class="fab fa-facebook text-lg"></i></a>
                        <a href="#" class="text-gray-400 hover:text-rose transition-colors"><i class="fab fa-tiktok text-lg"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-rose transition-colors">Home</a></li>
                        <li><a href="{{ route('booking.create') }}" class="text-gray-400 hover:text-rose transition-colors">Booking</a></li>
                        <li><a href="{{ route('home') }}#services" class="text-gray-400 hover:text-rose transition-colors">Services</a></li>
                    </ul>
                </div>

                    <!-- Hours -->
                    <div>
                        <h4 class="text-white font-semibold mb-4">Opening Hours</h4>
                        <ul class="space-y-2 text-gray-400">    
                            <li>Monday - Friday: 09:00 - 18:00</li>
                            <li>Saturday: 10:00 - 17:00</li>
                            <li>Sunday: Closed</li>
                        </ul>
                    </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Contact</h4>
                    <ul class="space-y-3 text-gray-400">
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-phone text-rose mt-1"></i>
                            <a href="tel:+353123456789" class="hover:text-rose transition-colors">+353 (0)1 234 5678</a>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-envelope text-rose mt-1"></i>
                            <a href="mailto:info@delphina.ie" class="hover:text-rose transition-colors">info@delphina.ie</a>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-map-pin text-rose mt-1"></i>
                            <span>The Square, Tallaght, Dublin</span>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="border-gray-800 my-8">

            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} Nail Art Studio. All rights reserved.</p>
                <ul class="flex space-x-6 text-gray-400 text-sm mt-4 md:mt-0">
                    <li><a href="{{ route('policies') }}" class="hover:text-rose transition-colors">Privacy Policy</a></li>
                    <li><a href="{{ route('admin.login') }}" class="hover:text-rose transition-colors font-medium">Admin</a></li>
                </ul>
            </div>
        </div>
    </footer>

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
    </script>
    
    @stack('scripts')
</body>
</html>
