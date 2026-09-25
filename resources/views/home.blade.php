@extends('layouts.app')

@section('title', 'Nails by Delphina | BIAB, Gel Nails and Nail Art in Tallaght, Dublin 24')
@section('description', 'BIAB, gel nails, soft gel extensions and nail art by Delfi in a private studio in Tallaght, Dublin 24. See prices and book online in minutes.')

@php
    $faqs = [
        ['q' => 'How much is the deposit?', 'a' => 'A €15 deposit secures your appointment. It is paid online via Revolut when you book and is taken off your final price.'],
        ['q' => 'How do I pay the rest?', 'a' => 'The remaining balance is paid at the studio on the day of your appointment.'],
        ['q' => 'Can I change my appointment?', 'a' => 'Yes. Rescheduling is free with more than 24 hours’ notice — just message Delfi on WhatsApp. See the booking policy for cancellations.'],
        ['q' => 'Where is the studio?', 'a' => 'Nails by Delphina is a private, one-to-one studio in Tallaght, Dublin 24.'],
        ['q' => 'How long does an appointment take?', 'a' => 'It depends on the service — from 30 minutes for a removal to around 2 hours for a full set of gel nails. Each service shows its time on the menu.'],
        ['q' => 'Can I book more than one service?', 'a' => 'Of course. Choose as many services as you like in the booking form and the right amount of time is reserved for you.'],
    ];
@endphp

@push('styles')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'OfferCatalog',
            'name' => 'Nail services at Nails by Delphina',
            'url' => route('home').'#services',
            'itemListElement' => $services->map(fn ($s) => [
                '@type' => 'Offer',
                'price' => number_format((float) $s->price, 2, '.', ''),
                'priceCurrency' => 'EUR',
                'itemOffered' => ['@type' => 'Service', 'name' => $s->name, 'description' => $s->description],
            ])->values(),
        ],
        [
            '@type' => 'FAQPage',
            'mainEntity' => collect($faqs)->map(fn ($f) => [
                '@type' => 'Question',
                'name' => $f['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
            ])->values(),
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) !!}
</script>
@endpush


@push('styles')
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

    /* Spotlight effect: the darkening overlay fades out in a circle around the cursor,
       revealing the full-brightness photo underneath as you move the mouse. */
    .home-hero-spotlight-mask {
        --spot-x: 50%;
        --spot-y: 38%;
        -webkit-mask-image: radial-gradient(260px circle at var(--spot-x) var(--spot-y), transparent 0%, black 78%);
        mask-image: radial-gradient(260px circle at var(--spot-x) var(--spot-y), transparent 0%, black 78%);
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
        /* Clear the fixed menu bar; the hero content starts at the top on phones. */
        .home-hero-text {
            padding-top: 5.5rem;
            padding-bottom: 4rem;
        }

        .home-hero-glow-one,
        .home-hero-glow-two {
            display: none;
        }
    }
</style>
@endpush

@section('content')

<!-- Hero Section -->
<section id="heroSpotlight" class="relative flex min-h-screen items-start justify-center overflow-hidden bg-[#d8d2c6] md:items-center">
    <img src="{{ asset('img/opt/hero-rose-1600.webp') }}"
         srcset="{{ asset('img/opt/hero-rose-800.webp') }} 800w, {{ asset('img/opt/hero-rose-1600.webp') }} 1600w, {{ asset('img/opt/hero-rose-2400.webp') }} 2400w"
         sizes="100vw" width="1600" height="899" fetchpriority="high" decoding="async"
         alt="Hands with blush pink almond nails and fine gold details beside a vase of pink roses" class="home-hero-media absolute inset-0 h-full w-full" />
    <div class="home-hero-spotlight-mask absolute inset-0 bg-gradient-to-r from-[#1f1110]/60 via-[#1f1110]/30 to-[#1f1110]/70"></div>
    <div class="home-hero-spotlight-mask absolute inset-0 bg-gradient-to-t from-[#1f1110]/45 via-transparent to-[#1f1110]/10"></div>
    <div class="home-hero-grain absolute inset-0"></div>
    <div class="home-hero-glow-one absolute -left-10 top-20 h-56 w-56 rounded-full bg-rose/20 blur-3xl"></div>
    <div class="home-hero-glow-two absolute bottom-16 right-8 h-72 w-72 rounded-full bg-olive/20 blur-3xl"></div>

    <!-- Content -->
    <div class="home-hero-text relative z-10 max-w-5xl px-4 text-center sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col items-center gap-5">
            <span class="home-intro-badge inline-flex items-center whitespace-nowrap rounded-full px-4 py-1.5 text-[10px] font-semibold uppercase tracking-[0.2em] text-white/95 sm:px-5 sm:py-2 sm:text-xs sm:tracking-[0.32em]">
                Nail artistry, tailored to you
            </span>
            <img src="{{ asset('img/logo.png') }}" alt="Delphina logo" class="home-hero-logo-white h-24 md:h-28 lg:h-32 w-auto object-contain">
        </div>

        <!-- Main Heading -->
        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-display font-bold mb-6 leading-tight">
            <span class="bg-gradient-to-r from-white via-nude to-white bg-clip-text text-transparent drop-shadow-[0_6px_20px_rgba(0,0,0,0.35)]">
                Nails that feel
            </span>
            <br class="hidden md:block">
            <span class="text-white drop-shadow-[0_6px_20px_rgba(0,0,0,0.45)]">like you.</span>
        </h1>

        <!-- Subheading -->
        <p class="text-lg md:text-xl text-white/90 mb-12 max-w-2xl mx-auto leading-relaxed font-sans drop-shadow-[0_4px_16px_rgba(0,0,0,0.35)]">
            Thoughtfully designed BIAB, gel extensions and nail art from a private Tallaght studio.
            Relaxed appointments, long-lasting finishes, and every detail considered.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center mb-10 sm:mb-12">
            <a href="{{ route('booking.create') }}" class="btn-primary w-full sm:w-auto px-8 py-4 text-base sm:text-lg">
                <i class="fas fa-sparkles mr-2"></i> Book your appointment
            </a>

            <a href="https://www.instagram.com/nailsbydelphina/" target="_blank" rel="noopener" class="btn-outline w-full sm:w-auto px-8 py-4 text-base sm:text-lg">
                <i class="fab fa-instagram mr-2"></i> Follow on Instagram
            </a>
        </div>

        <!-- Social Proof -->
        <div class="flex flex-wrap justify-center items-center gap-x-5 gap-y-2 text-sm text-white/85">
            <div class="flex items-center space-x-1">
                <i class="fas fa-heart text-nude"></i>
                <span>One-to-one appointments</span>
            </div>
            <div class="hidden sm:block w-1 h-1 bg-white/60 rounded-full"></div>
            <div>Tallaght, Dublin</div>
            <div class="hidden sm:block w-1 h-1 bg-white/60 rounded-full"></div>
            <div>€15 booking deposit</div>
        </div>
    </div>

    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <i class="fas fa-chevron-down text-white text-2xl drop-shadow-[0_4px_10px_rgba(0,0,0,0.35)]"></i>
    </div>
</section>

<!-- Gallery Section -->
<section id="portfolio" class="py-24 bg-gradient-to-b from-nude/50 to-white" x-data="{ selectedPhoto: null }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-display font-bold text-brand-charcoal mb-6">
                        The <span class="bg-gradient-to-r from-olive to-green-700 bg-clip-text text-transparent">Delfi Edit</span>
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        A selection of custom sets, detailed finishes, and nail art made with care in my Tallaght studio.
                    </p>
                </div>

                @if ($galleryPhotos->isNotEmpty())
                <div class="mx-auto grid max-w-6xl grid-flow-dense grid-cols-2 auto-rows-[12rem] gap-3 sm:auto-rows-[16rem] sm:gap-5 lg:grid-cols-4">
                    @foreach($galleryPhotos as $index => $photo)
                    <button
                        type="button"
                        class="group relative overflow-hidden rounded-2xl bg-brand-charcoal text-left shadow-md transition duration-300 hover:-translate-y-1 hover:shadow-2xl focus:outline-none focus:ring-4 focus:ring-olive/30 {{ in_array($index, [0, 5]) ? 'col-span-2 row-span-2' : (in_array($index, [6, 7]) ? 'col-span-2' : '') }}"
                        @click="selectedPhoto = {{ Js::from($photo) }}"
                    >
                        <img src="{{ $photo['thumb'] }}" alt="{{ $photo['caption'] }} nails by Delphina, Tallaght" loading="lazy" decoding="async" width="600" height="800" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                        <span class="absolute inset-0 bg-gradient-to-t from-black/65 via-transparent to-transparent opacity-80"></span>
                        <span class="absolute bottom-0 left-0 p-4 text-sm font-semibold tracking-wide text-white sm:p-5">{{ $photo['caption'] }}</span>
                        <span class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-brand-charcoal opacity-0 transition duration-300 group-hover:opacity-100">
                            <i class="fas fa-expand-alt text-sm"></i>
                        </span>
                    </button>
                    @endforeach
                </div>
                @else
                <div class="mx-auto max-w-xl rounded-2xl border border-olive/20 bg-white px-6 py-10 text-center shadow-sm">
                    <p class="text-gray-600">New nail sets are being added to the gallery soon.</p>
                </div>
                @endif

                <div class="mt-14 text-center">
                    <a href="https://www.instagram.com/nailsbydelphina/" target="_blank" rel="noopener"
                       class="btn-outline px-8 py-4">
                        <i class="fab fa-instagram mr-2"></i> Follow on Instagram
                    </a>
                </div>

                <div x-cloak x-show="selectedPhoto" x-transition.opacity class="fixed inset-0 z-[100] flex items-center justify-center bg-black/85 p-4" @click.self="selectedPhoto = null" @keydown.escape.window="selectedPhoto = null">
                    <button type="button" class="absolute right-5 top-5 flex h-11 w-11 items-center justify-center rounded-full bg-white text-brand-charcoal shadow-lg" @click="selectedPhoto = null" aria-label="Close image preview">
                        <i class="fas fa-times"></i>
                    </button>
                    <figure class="max-h-full max-w-5xl">
                        <img :src="selectedPhoto.image" :alt="selectedPhoto.caption + ' nail set by Delfi'" class="max-h-[82vh] w-auto max-w-full rounded-2xl object-contain shadow-2xl">
                        <figcaption class="pt-3 text-center font-display text-lg text-white" x-text="selectedPhoto.caption"></figcaption>
                    </figure>
                </div>
            </div>
</section>

<!-- Services & prices (server-rendered so search engines can read it) -->
<section id="services" class="bg-white py-20 md:py-24">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="mb-12 text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-olive">Services &amp; prices</p>
            <h2 class="mt-3 text-4xl font-display font-bold text-brand-charcoal md:text-5xl">Nail services in Tallaght</h2>
            <p class="mx-auto mt-4 max-w-2xl text-lg text-gray-600">BIAB, gel nails, soft gel extensions and gel polish in a private studio in Tallaght, Dublin 24. Book online with a €15 deposit that comes off your final price.</p>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            @foreach($menu as $key => $group)
                <article class="rounded-[1.75rem] border border-stone-200 bg-beige-50/60 p-6">
                    <h3 class="mb-4 text-xl font-display font-bold text-brand-charcoal">{{ $group['label'] }}</h3>
                    <ul class="divide-y divide-stone-200">
                        @foreach($group['services'] as $service)
                            @php
                                $displayName = str_contains($service->name, ' - ') ? trim(explode(' - ', $service->name, 2)[1]) : $service->name;
                            @endphp
                            <li class="flex items-center gap-4 py-3">
                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold text-gray-900">{{ $displayName }}</p>
                                    <p class="text-sm text-gray-500">{{ $service->duration }} min @if($service->description) · {{ $service->description }} @endif</p>
                                </div>
                                <p class="shrink-0 text-lg font-bold text-olive">{{ $service->price > 0 ? '€'.rtrim(rtrim(number_format($service->price, 2), '0'), '.') : 'Free' }}</p>
                                <a href="{{ route('booking.create', ['service_id' => $service->id]) }}" class="shrink-0 rounded-full border border-olive/30 px-3 py-1.5 text-xs font-semibold text-olive transition hover:bg-olive hover:text-white" aria-label="Book {{ $service->name }}">Book</a>
                            </li>
                        @endforeach
                    </ul>
                </article>
            @endforeach
        </div>
    </div>
</section>

<!-- How booking works + FAQ -->
<section id="faq" class="bg-gradient-to-b from-white to-nude/40 py-20 md:py-24">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <div class="mb-12 text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-olive">Booking made easy</p>
            <h2 class="mt-3 text-4xl font-display font-bold text-brand-charcoal md:text-5xl">How it works</h2>
        </div>

        <ol class="mb-16 grid gap-4 md:grid-cols-3">
            @foreach([
                ['icon' => 'fa-hand-sparkles', 'title' => 'Choose your service', 'text' => 'Pick one or more treatments from the menu.'],
                ['icon' => 'fa-calendar-check', 'title' => 'Pick a day & time', 'text' => 'Only real free times are shown, so there is no back-and-forth.'],
                ['icon' => 'fa-lock', 'title' => 'Pay the €15 deposit', 'text' => 'Secure your spot via Revolut. The rest is paid at the studio.'],
            ] as $i => $step)
                <li class="rounded-[1.75rem] bg-white p-6 text-center shadow-sm ring-1 ring-stone-100">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-olive/10 text-olive"><i class="fas {{ $step['icon'] }}"></i></span>
                    <p class="mt-4 text-xs font-semibold uppercase tracking-[0.25em] text-gray-400">Step {{ $i + 1 }}</p>
                    <h3 class="mt-1 text-lg font-semibold text-gray-900">{{ $step['title'] }}</h3>
                    <p class="mt-2 text-sm text-gray-600">{{ $step['text'] }}</p>
                </li>
            @endforeach
        </ol>

        <h2 class="mb-6 text-center text-3xl font-display font-bold text-brand-charcoal">Questions</h2>
        <div class="mx-auto max-w-3xl space-y-3">
            @foreach($faqs as $faq)
                <details class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-stone-100">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-gray-900">
                        {{ $faq['q'] }}
                        <i class="fas fa-chevron-down text-xs text-olive transition group-open:rotate-180"></i>
                    </summary>
                    <p class="mt-3 text-gray-600">{{ $faq['a'] }}</p>
                </details>
            @endforeach
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('booking.create') }}" class="btn-primary px-8 py-4 text-base">Book your appointment</a>
        </div>
    </div>
</section>


@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const heroSpotlight = document.getElementById('heroSpotlight');
        if (!heroSpotlight) {
            return;
        }

        const setSpotlight = (xPercent, yPercent) => {
            heroSpotlight.style.setProperty('--spot-x', xPercent + '%');
            heroSpotlight.style.setProperty('--spot-y', yPercent + '%');
        };

        heroSpotlight.addEventListener('mousemove', function (event) {
            const rect = heroSpotlight.getBoundingClientRect();
            const x = ((event.clientX - rect.left) / rect.width) * 100;
            const y = ((event.clientY - rect.top) / rect.height) * 100;
            setSpotlight(x, y);
        });

        heroSpotlight.addEventListener('mouseleave', function () {
            setSpotlight(50, 38);
        });
    });
</script>
@endpush
