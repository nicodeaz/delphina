

<?php $__env->startPush('styles'); ?>
<style>
    .home-hero-media {
        animation: hero-pan-zoom 24s ease-in-out infinite alternate;
        transform-origin: center center;
        will-change: transform;
        object-fit: cover;
        object-position: center center;
        filter: contrast(1.06) saturate(1.06) brightness(0.96);
        image-rendering: -webkit-optimize-contrast;
        backface-visibility: hidden;
        transform: translateZ(0);
    }

    .home-hero-logo-white {
        filter: brightness(0) invert(1) drop-shadow(0 12px 28px rgba(0, 0, 0, 0.35));
    }

    .home-intro-badge {
        background: linear-gradient(90deg, rgba(255,255,255,0.22), rgba(255,255,255,0.12));
        border: 1px solid rgba(255,255,255,0.35);
        backdrop-filter: blur(6px);
    }

    .home-hero-glow-one,
    .home-hero-glow-two {
        will-change: transform;
        animation: hero-float 10s ease-in-out infinite;
    }

    .home-hero-text {
        text-shadow: 0 6px 24px rgba(0, 0, 0, 0.28);
    }

    .home-hero-grain {
        background-image: radial-gradient(rgba(255,255,255,0.12) 0.5px, transparent 0.5px);
        background-size: 4px 4px;
        mix-blend-mode: soft-light;
        opacity: 0.12;
    }

    .home-hero-glow-two {
        animation-duration: 14s;
        animation-delay: 1.5s;
    }

    @keyframes hero-pan-zoom {
        0% {
            transform: scale(1.03) translate3d(0, 0, 0);
        }
        50% {
            transform: scale(1.08) translate3d(-1.2%, -0.6%, 0);
        }
        100% {
            transform: scale(1.05) translate3d(1%, 0.8%, 0);
        }
    }

    @keyframes hero-float {
        0%, 100% {
            transform: translate3d(0, 0, 0);
        }
        50% {
            transform: translate3d(14px, -18px, 0);
        }
    }

    @media (max-width: 768px) {
        .home-hero-text {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }

        .home-hero-glow-one,
        .home-hero-glow-two {
            display: none;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-[#d8d2c6]">
    <img src="<?php echo e(asset('img/last.jpg')); ?>" alt="Studio background" class="home-hero-media absolute inset-0 h-full w-full" />
    <div class="absolute inset-0 bg-gradient-to-r from-black/15 via-black/24 to-black/50"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/15 via-transparent to-white/12"></div>
    <div class="home-hero-grain absolute inset-0"></div>
    <div class="home-hero-glow-one absolute -left-10 top-20 h-56 w-56 rounded-full bg-rose/20 blur-3xl"></div>
    <div class="home-hero-glow-two absolute bottom-16 right-8 h-72 w-72 rounded-full bg-olive/20 blur-3xl"></div>

    <!-- Content -->
    <div class="home-hero-text relative z-10 text-center px-4 sm:px-6 lg:px-8 max-w-5xl">
        <div class="mb-8 flex flex-col items-center gap-5">
            <span class="home-intro-badge inline-flex items-center rounded-full px-5 py-2 text-xs font-semibold uppercase tracking-[0.32em] text-white/95">
                Delphina Signature Studio
            </span>
            <img src="<?php echo e(asset('img/logo.png')); ?>" alt="Delphina logo" class="home-hero-logo-white h-24 md:h-28 lg:h-32 w-auto object-contain">
        </div>

        <!-- Main Heading -->
        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-display font-bold mb-6 leading-tight">
            <span class="bg-gradient-to-r from-white via-nude to-white bg-clip-text text-transparent drop-shadow-[0_6px_20px_rgba(0,0,0,0.35)]">
                Perfect Nails
            </span>
            <br class="hidden md:block">
            <span class="text-white drop-shadow-[0_6px_20px_rgba(0,0,0,0.45)]">Made Simple</span>
        </h1>

        <!-- Subheading -->
        <p class="text-lg md:text-xl text-white/90 mb-12 max-w-2xl mx-auto leading-relaxed font-sans drop-shadow-[0_4px_16px_rgba(0,0,0,0.35)]">
            Transform your nails with premium gel extensions, BIAB, soft gel overlays, and stunning nail art.
            Dublin's most trusted nail technician in Tallaght.
        </p>

        <!-- Instagram-style CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center mb-10 sm:mb-12">
            <a href="<?php echo e(route('booking.create')); ?>" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-olive to-green-700 text-white rounded-full font-bold hover:shadow-xl transition-all transform hover:scale-105 text-base sm:text-lg inline-flex items-center justify-center shadow-lg">
                <i class="fas fa-sparkles mr-2"></i> Book Your Glow Up
            </a>

            <a href="#portfolio" class="w-full sm:w-auto px-8 py-4 border-2 border-white/70 text-white rounded-full font-bold hover:bg-white hover:text-brand-charcoal transition-all text-base sm:text-lg inline-flex items-center justify-center backdrop-blur-sm">
                <i class="fab fa-instagram mr-2"></i> View My Work
            </a>
        </div>

        <!-- Social Proof -->
        <div class="flex flex-wrap justify-center items-center gap-x-5 gap-y-2 text-sm text-white/85">
            <div class="flex items-center space-x-1">
                <i class="fab fa-instagram text-nude"></i>
                <span>@nailsbydelphina</span>
            </div>
            <div class="hidden sm:block w-1 h-1 bg-white/60 rounded-full"></div>
            <div>500+ Happy Clients</div>
            <div class="hidden sm:block w-1 h-1 bg-white/60 rounded-full"></div>
            <div>8+ Years Experience</div>
        </div>
    </div>

    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <i class="fas fa-chevron-down text-white text-2xl drop-shadow-[0_4px_10px_rgba(0,0,0,0.35)]"></i>
    </div>
</section>

<!-- Instagram Section -->
<section id="portfolio" class="py-24 bg-gradient-to-b from-nude/50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16">
           
            <h2 class="text-4xl md:text-5xl font-display font-bold text-brand-charcoal mb-6">
                Follow My <span class="bg-gradient-to-r from-olive to-green-700 bg-clip-text text-transparent">Journey</span>
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Get inspired by my latest creations, behind-the-scenes moments, and beauty tips
            </p>
        </div>

        <!-- Instagram Grid -->
        <div class="mx-auto grid max-w-6xl grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3 mb-12">
            <?php $__currentLoopData = $instagramPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <article class="overflow-hidden rounded-2xl border border-[#dbdbdb] bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                <div class="flex items-center justify-between px-4 py-3 border-b border-[#efefef]">
                    <div class="flex items-center gap-3">
                        <img src="<?php echo e(asset('img/logo_green.png')); ?>" alt="nailsbydelphina" class="h-8 w-8 rounded-full object-cover ring-1 ring-[#dbdbdb]">
                        <div>
                            <p class="text-sm font-semibold text-[#262626]">nailsbydelphina</p>
                            <p class="text-[11px] text-[#8e8e8e]">Tallaght, Dublin</p>
                        </div>
                    </div>
                    <i class="fas fa-ellipsis-h text-[#8e8e8e]"></i>
                </div>

                <a href="https://www.instagram.com/nailsbydelphina/" target="_blank" rel="noopener" class="block overflow-hidden bg-black">
                    <img src="<?php echo e($post['image']); ?>" alt="Instagram Post" class="max-h-[520px] w-full object-contain mx-auto">
                </a>

                <div class="px-4 py-3">
                    <div class="flex items-center justify-between text-[#262626]">
                        <div class="flex items-center gap-4">
                            <i class="far fa-heart text-xl"></i>
                            <i class="far fa-comment text-xl"></i>
                            <i class="far fa-paper-plane text-xl"></i>
                        </div>
                        <i class="far fa-bookmark text-xl"></i>
                    </div>
                </div>

            </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Follow CTA -->
        <div class="text-center">
            <div class="bg-gradient-to-r from-olive via-green-700 to-olive p-1 rounded-2xl inline-block">
                     <a href="https://www.instagram.com/nailsbydelphina/" target="_blank"
                   class="inline-flex items-center px-8 py-4 bg-white text-brand-charcoal rounded-xl font-bold hover:bg-gray-50 transition-all transform hover:scale-105 shadow-lg">
                    <i class="fab fa-instagram mr-3 text-olive"></i>
                    Follow @nailsbydelphina
                    <i class="fas fa-external-link-alt ml-2 text-sm"></i>
                </a>
            </div>
        </div>
    </div>
</section>


<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\delphina\resources\views/home.blade.php ENDPATH**/ ?>