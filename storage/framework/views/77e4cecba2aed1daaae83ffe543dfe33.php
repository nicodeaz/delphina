<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('title', 'Nail Art Studio - Professional Nails in Dublin'); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('description', 'Professional nail studio with unique designs and premium treatments. Book your appointment online.'); ?>">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
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

    <!-- Heroicons -->
    <script src="https://unpkg.com/heroicons@2.0.18/24/outline/index.js" type="module"></script>
    <script src="https://unpkg.com/heroicons@2.0.18/24/solid/index.js" type="module"></script>

    <!-- Toast Notifications -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="font-sans antialiased bg-white text-gray-800" style="font-family: 'Poppins', sans-serif;">
    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-md border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="<?php echo e(route('home')); ?>" class="flex items-center group">
                    <svg viewBox="0 0 200 60" class="w-32 h-auto" xmlns="http://www.w3.org/2000/svg">
                        <!-- Gradient Definitions -->
                        <defs>
                            <linearGradient id="headerLogoGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:#FF6B9D;stop-opacity:1" />
                                <stop offset="50%" style="stop-color:#C77DFF;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#FFD700;stop-opacity:1" />
                            </linearGradient>
                            <linearGradient id="headerNailGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#FF6B9D;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#C77DFF;stop-opacity:1" />
                            </linearGradient>
                        </defs>

                        <!-- Nail Icon -->
                        <g transform="translate(5, 10)">
                            <path d="M10 3 L17 3 L17 10 Q17 14 13 14 L10 14 Q6 14 6 10 Z"
                                  fill="url(#headerNailGradient)"
                                  stroke="#FFD700"
                                  stroke-width="0.5"/>
                            <path d="M10 3 L13 1 L17 3"
                                  fill="#FFD700"/>
                            <rect x="8" y="5.5" width="7" height="1.5" fill="white" rx="0.5"/>
                        </g>

                        <!-- Text -->
                        <text x="30" y="18" font-family="Poppins, sans-serif" font-size="12" font-weight="700" fill="url(#headerLogoGradient)">
                            NAILS BY
                        </text>
                        <text x="30" y="32" font-family="Playfair Display, serif" font-size="16" font-weight="600" fill="#36454F">
                            DELPHINA
                        </text>
                    </svg>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="<?php echo e(route('home')); ?>" class="px-4 py-2 text-gray-700 hover:text-instagram-pink transition-colors font-medium">Home</a>
                    <a href="<?php echo e(route('home')); ?>#services" class="px-4 py-2 text-gray-700 hover:text-instagram-pink transition-colors font-medium">Services</a>
                    <a href="<?php echo e(route('booking.create')); ?>" class="px-4 py-2 text-gray-700 hover:text-instagram-pink transition-colors font-medium">Book</a>
                    <a href="<?php echo e(route('home')); ?>#portfolio" class="px-4 py-2 text-gray-700 hover:text-instagram-pink transition-colors font-medium">Portfolio</a>
                    <a href="<?php echo e(route('policies')); ?>" class="px-4 py-2 text-gray-700 hover:text-instagram-pink transition-colors font-medium">Policies</a>

                    <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->user()->isAdmin()): ?>
                            <a href="<?php echo e(route('admin.dashboard')); ?>" class="px-4 py-2 text-orange-600 font-semibold hover:text-orange-700 transition-colors">
                                <i class="fas fa-crown"></i> Admin
                            </a>
                        <?php endif; ?>

                        <div class="relative group">
                            <button class="px-4 py-2 flex items-center space-x-2 text-gray-700 hover:text-rose transition-colors">
                                <img src="https://ui-avatars.com/api/?name=<?php echo e(auth()->user()->name); ?>&background=E8C4D4&color=fff"
                                     alt="" class="w-8 h-8 rounded-full">
                                <span class="font-medium"><?php echo e(auth()->user()->name); ?></span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>

                            <div class="hidden group-hover:block absolute right-0 w-48 bg-white rounded-lg shadow-xl py-2 border border-gray-100">
                                <?php if(auth()->user()->isAdmin()): ?>
                                    <a href="<?php echo e(route('admin.appointments.index')); ?>" class="block px-4 py-2 text-gray-700 hover:bg-nude transition-colors">
                                        <i class="fas fa-calendar-alt mr-2"></i> Appointments
                                    </a>
                                    <a href="<?php echo e(route('admin.payments.index')); ?>" class="block px-4 py-2 text-gray-700 hover:bg-nude transition-colors">
                                        <i class="fas fa-credit-card mr-2"></i> Payments
                                    </a>
                                <?php endif; ?>
                                <hr class="my-2">
                                <form method="POST" action="<?php echo e(route('admin.logout')); ?>" class="w-full">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-nude transition-colors">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Admin-only system - no public login/register needed -->
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <button onclick="toggleMobileMenu()" class="md:hidden p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-bars text-2xl text-gray-700"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden pb-6 border-t border-gray-200">
                <div class="flex flex-col space-y-3 mt-4">
                    <a href="<?php echo e(route('home')); ?>" class="px-4 py-2 text-gray-700 hover:text-olive hover:bg-gray-50 rounded transition-colors">Home</a>
                    <a href="<?php echo e(route('home')); ?>#servicios" class="px-4 py-2 text-gray-700 hover:text-olive hover:bg-gray-50 rounded transition-colors">Services</a>
                    <a href="<?php echo e(route('book')); ?>" class="px-4 py-2 text-gray-700 hover:text-olive hover:bg-gray-50 rounded transition-colors">Book</a>
                    <a href="<?php echo e(route('policies')); ?>" class="px-4 py-2 text-gray-700 hover:text-olive hover:bg-gray-50 rounded transition-colors">Policies</a>

                    <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->user()->isAdmin()): ?>
                            <a href="<?php echo e(route('admin.dashboard')); ?>" class="px-4 py-2 text-orange-600 font-semibold hover:bg-gray-50 rounded transition-colors">Admin</a>
                        <?php endif; ?>
                        <?php if(auth()->user()->isAdmin()): ?>
                            <a href="<?php echo e(route('admin.appointments.index')); ?>" class="px-4 py-2 text-gray-700 hover:text-olive hover:bg-gray-50 rounded transition-colors">Appointments</a>
                            <a href="<?php echo e(route('admin.payments.index')); ?>" class="px-4 py-2 text-gray-700 hover:text-olive hover:bg-gray-50 rounded transition-colors">Payments</a>
                        <?php endif; ?>
                        <form method="POST" action="<?php echo e(route('admin.logout')); ?>" class="w-full">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:text-olive hover:bg-gray-50 rounded transition-colors">Logout</button>
                        </form>
                    <?php else: ?>
                        <!-- Admin-only system - no public login/register needed -->
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-[calc(100vh-140px)]">
        <?php if(session('success')): ?>
            <script>
                toastr.success("<?php echo e(session('success')); ?>", "Success!");
            </script>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <script>
                toastr.error("<?php echo e(session('error')); ?>", "Error!");
            </script>
        <?php endif; ?>
        
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-16 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <!-- About -->
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-rose to-pink-300 rounded-full flex items-center justify-center">
                            <i class="fas fa-sparkles text-white text-sm"></i>
                        </div>
                        <span class="text-lg font-serif font-bold text-white">NAIL ART</span>
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
                        <li><a href="<?php echo e(route('home')); ?>" class="text-gray-400 hover:text-rose transition-colors">Home</a></li>
                        <li><a href="<?php echo e(route('appointments.index')); ?>" class="text-gray-400 hover:text-rose transition-colors">Book Appointment</a></li>
                        <li><a href="#servicios" class="text-gray-400 hover:text-rose transition-colors">Services</a></li>
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
                            <a href="mailto:info@nailartstudio.ie" class="hover:text-rose transition-colors">info@nailartstudio.ie</a>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i class="fas fa-map-pin text-rose mt-1"></i>
                            <span>Main Street 123, Dublin</span>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="border-gray-800 my-8">

            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">&copy; <?php echo e(date('Y')); ?> Nail Art Studio. All rights reserved.</p>
                <ul class="flex space-x-6 text-gray-400 text-sm mt-4 md:mt-0">
                    <li><a href="#" class="hover:text-rose transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-rose transition-colors">Terms & Conditions</a></li>
                    <li><a href="<?php echo e(route('admin.login')); ?>" class="hover:text-rose transition-colors font-medium">Admin</a></li>
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
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\delphina\resources\views/layouts/app.blade.php ENDPATH**/ ?>