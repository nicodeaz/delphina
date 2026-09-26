@extends('layouts.app')

@section('title', 'Nails by Delphina | BIAB, Gel Nails and Nail Art in Tallaght, Dublin 24')
@section('description', 'BIAB, gel nails, soft gel extensions and nail art by Delfi in a private studio in Tallaght, Dublin 24. See prices and book online in minutes.')
@section('nav_theme', 'light')

@php
    $faqs = [
        ['q' => 'How much is the deposit?', 'a' => 'A €15 deposit secures your appointment. It is paid online via Revolut when you book and is taken off your final price.'],
        ['q' => 'How do I pay the rest?', 'a' => 'The remaining balance is paid at the studio on the day of your appointment.'],
        ['q' => 'Can I change my appointment?', 'a' => 'Yes. Rescheduling is free with more than 24 hours’ notice — just message Delfi on WhatsApp. See the booking policy for cancellations.'],
        ['q' => 'Where is the studio?', 'a' => 'Nails by Delphina is a private, one-to-one studio in Tallaght, Dublin 24.'],
        ['q' => 'How long does an appointment take?', 'a' => 'It depends on the service — from 30 minutes for a removal to around 2 hours for a full set of gel nails. Each service shows its time on the menu.'],
        ['q' => 'Can I book more than one service?', 'a' => 'Of course. Choose as many services as you like in the booking form and the right amount of time is reserved for you.'],
    ];

    $marqueeItems = ['BIAB', 'Gel nails', 'Soft gel extensions', 'Nail art', 'Gel polish', 'French & ombré', 'Infills'];

    $steps = [
        ['icon' => 'fa-hand-sparkles', 'title' => 'Choose your service', 'text' => 'Pick one or more treatments from the menu.'],
        ['icon' => 'fa-calendar-check', 'title' => 'Pick a day & time', 'text' => 'Only real free times are shown, so there is no back-and-forth.'],
        ['icon' => 'fa-lock', 'title' => 'Pay the €15 deposit', 'text' => 'Secure your spot via Revolut. The rest is paid at the studio.'],
    ];
@endphp

@push('styles')
<link rel="preload" as="image" href="{{ asset('img/opt/hero-silk-1600.webp') }}" imagesrcset="{{ asset('img/opt/hero-silk-800.webp') }} 800w, {{ asset('img/opt/hero-silk-1600.webp') }} 1600w, {{ asset('img/opt/hero-silk-2400.webp') }} 2400w" imagesizes="100vw" media="(min-width: 768px)">
<link rel="preload" as="image" href="{{ asset('img/opt/hero-silk-portrait-900.webp') }}" media="(max-width: 767px)">
<script>
    // Hide reveal-on-scroll content only when motion is welcome, and never leave
    // it hidden: if the home script doesn't run, show everything after 2.5s.
    (function () {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        document.documentElement.classList.add('motion-ready');
        window.setTimeout(function () {
            if (window.__homeMotion) return;
            document.documentElement.classList.remove('motion-ready');
        }, 2500);
    })();
</script>
@vite('resources/js/home.js')
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

@section('content')
<div class="scroll-progress" aria-hidden="true"></div>

{{-- ================================================================ --}}
{{-- Hero                                                              --}}
{{-- ================================================================ --}}
<section class="hero flex items-center" id="top">
    <div class="hero-media" aria-hidden="true">
        <picture>
            <source media="(max-width: 767px)" srcset="{{ asset('img/opt/hero-silk-portrait-900.webp') }}">
            <img src="{{ asset('img/opt/hero-silk-1600.webp') }}"
                 srcset="{{ asset('img/opt/hero-silk-800.webp') }} 800w, {{ asset('img/opt/hero-silk-1600.webp') }} 1600w, {{ asset('img/opt/hero-silk-2400.webp') }} 2400w"
                 sizes="100vw" width="1600" height="900" fetchpriority="high" decoding="async"
                 alt="">
        </picture>
    </div>
    <div class="hero-veil" aria-hidden="true"></div>
    <div class="hero-glow" aria-hidden="true"></div>
    <div class="hero-grain" aria-hidden="true"></div>

    <div class="relative z-10 mx-auto w-full max-w-7xl px-5 pb-20 pt-28 sm:px-8 md:py-32">
        <div class="max-w-2xl text-center md:text-left">
            <div data-reveal="blur" class="mb-7 flex justify-center md:justify-start">
                <span class="hero-badge"><span class="hero-badge-dot"></span>Nail artistry, tailored to you</span>
            </div>

            <h1 class="hero-title" data-split>Nails that feel <span class="font-accent">like you.</span></h1>

            <p data-reveal="up" style="--reveal-delay: 450ms" class="mx-auto mt-7 max-w-lg text-base leading-relaxed text-[#5e5720] sm:text-lg md:mx-0">
                BIAB, gel extensions and detailed nail art from a private studio in Tallaght. Relaxed one-to-one appointments and long-lasting finishes.
            </p>

            <div data-reveal="up" style="--reveal-delay: 600ms" class="mt-9 flex flex-col items-center gap-3 sm:flex-row sm:justify-center md:justify-start">
                <a href="{{ route('booking.create') }}" class="btn-magnetic btn-solid w-full sm:w-auto">
                    <span class="btn-label">Book your appointment</span>
                    <i class="fas fa-arrow-right text-sm btn-label"></i>
                </a>
                <a href="#portfolio" class="btn-magnetic btn-ghost w-full sm:w-auto">
                    <span class="btn-label">See the work</span>
                </a>
            </div>

            <div data-reveal-group class="mt-9 flex flex-wrap justify-center gap-2 md:justify-start">
                <span data-reveal="up" class="hero-chip"><i class="fas fa-heart text-[#b0707a]"></i>One-to-one appointments</span>
                <span data-reveal="up" class="hero-chip"><i class="fas fa-location-dot text-[#8d8540]"></i>Tallaght, Dublin 24</span>
                <span data-reveal="up" class="hero-chip"><i class="fas fa-lock text-[#c9a24d]"></i>€15 booking deposit</span>
            </div>
        </div>
    </div>

    <a href="#intro" class="hero-scroll hidden md:block" aria-label="Scroll down"></a>
</section>

{{-- ================================================================ --}}
{{-- Marquee                                                           --}}
{{-- ================================================================ --}}
<div class="marquee" aria-hidden="true">
    <div class="marquee-track">
        @for ($copy = 0; $copy < 2; $copy++)
            @foreach($marqueeItems as $item)
                <span class="marquee-item font-accent">{{ $item }} <span class="marquee-star">✦</span></span>
            @endforeach
        @endfor
    </div>
</div>

{{-- ================================================================ --}}
{{-- Statement                                                         --}}
{{-- ================================================================ --}}
<section id="intro" class="bg-[#fbf7f1] py-24 md:py-36">
    <div class="mx-auto max-w-5xl px-5 sm:px-8">
        <p data-reveal="up" class="mb-8 text-xs font-semibold uppercase tracking-[0.35em] text-[#8d8540]">The studio</p>
        <p class="statement" data-fill data-accent="details,yours,you">Every set is designed around you — your nails, your style, your everyday. A calm private studio, unhurried appointments and the small details that make a manicure feel truly yours.</p>
    </div>
</section>

{{-- ================================================================ --}}
{{-- Gallery                                                           --}}
{{-- ================================================================ --}}
<section id="portfolio" class="bg-white py-20 md:py-28" x-data="deliGallery(@js($galleryPhotos->values()))" @keydown.window="onKey($event)">
    <div class="mx-auto max-w-7xl px-5 sm:px-8">
        <div class="mb-12 flex flex-col items-start justify-between gap-6 md:mb-16 md:flex-row md:items-end">
            <div>
                <p data-reveal="up" class="text-xs font-semibold uppercase tracking-[0.35em] text-[#8d8540]">Portfolio</p>
                <h2 data-reveal="up" class="mt-3 text-4xl font-bold tracking-tight text-[#413b0e] md:text-6xl">The <span class="font-accent text-[#b0707a]">Delfi Edit</span></h2>
            </div>
            <p data-reveal="up" class="max-w-sm text-gray-600">A selection of custom sets, detailed finishes and nail art made with care in my Tallaght studio.</p>
        </div>

        @if ($galleryPhotos->isNotEmpty())
            <div data-reveal-group class="grid grid-flow-dense grid-cols-2 auto-rows-[12rem] gap-3 sm:auto-rows-[16rem] sm:gap-5 lg:grid-cols-4">
                @foreach($galleryPhotos as $index => $photo)
                    <button
                        type="button"
                        data-reveal="clip"
                        class="tilt group relative overflow-hidden rounded-3xl bg-[#e9dfd0] text-left focus:outline-none focus-visible:ring-4 focus-visible:ring-[#c9a24d]/50 {{ in_array($index, [0, 5]) ? 'col-span-2 row-span-2' : (in_array($index, [6, 7]) ? 'col-span-2' : '') }}"
                        @click="open({{ $index }})"
                        aria-label="View {{ $photo['caption'] }}"
                    >
                        <img src="{{ $photo['thumb'] }}" alt="{{ $photo['caption'] }} nails by Delphina, Tallaght" loading="lazy" decoding="async" width="600" height="800" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                        <span class="absolute inset-0 bg-gradient-to-t from-black/55 via-transparent to-transparent opacity-80 transition group-hover:opacity-100"></span>
                        <span class="tilt-shine"></span>
                        <span class="absolute bottom-0 left-0 p-4 text-sm font-semibold tracking-wide text-white sm:p-5">{{ $photo['caption'] }}</span>
                        <span class="absolute right-4 top-4 flex h-9 w-9 translate-y-2 items-center justify-center rounded-full bg-white/90 text-[#413b0e] opacity-0 transition duration-300 group-hover:translate-y-0 group-hover:opacity-100">
                            <i class="fas fa-expand-alt text-sm"></i>
                        </span>
                    </button>
                @endforeach
            </div>
        @else
            <div class="mx-auto max-w-xl rounded-2xl border border-[#554f13]/20 bg-white px-6 py-10 text-center shadow-sm">
                <p class="text-gray-600">New nail sets are being added to the gallery soon.</p>
            </div>
        @endif

        <div data-reveal="up" class="mt-14 text-center">
            <a href="https://www.instagram.com/nailsbydelphina/" target="_blank" rel="noopener" class="btn-magnetic btn-ghost">
                <span class="btn-label"><i class="fab fa-instagram mr-2"></i>More on Instagram</span>
            </a>
        </div>
    </div>

    {{-- Lightbox: arrows, keyboard and swipe --}}
    <div x-cloak x-show="index !== null" x-transition.opacity class="fixed inset-0 z-[100] flex items-center justify-center bg-[#1b1409]/90 p-4 backdrop-blur-sm"
         @click.self="close()" @touchstart.passive="touchStart($event)" @touchend.passive="touchEnd($event)" role="dialog" aria-modal="true" aria-label="Photo viewer">
        <button type="button" class="absolute right-5 top-5 flex h-11 w-11 items-center justify-center rounded-full bg-white text-[#413b0e] shadow-lg" @click="close()" aria-label="Close"><i class="fas fa-times"></i></button>
        <button type="button" class="absolute left-3 top-1/2 hidden h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-[#413b0e] shadow-lg sm:flex" @click="prev()" aria-label="Previous photo"><i class="fas fa-chevron-left"></i></button>
        <button type="button" class="absolute right-3 top-1/2 hidden h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-[#413b0e] shadow-lg sm:flex" @click="next()" aria-label="Next photo"><i class="fas fa-chevron-right"></i></button>
        <template x-if="index !== null">
            <figure class="max-h-full max-w-5xl">
                <img :src="photos[index].image" :alt="photos[index].caption + ' nails by Delphina'" class="max-h-[80vh] w-auto max-w-full rounded-2xl object-contain shadow-2xl">
                <figcaption class="pt-4 text-center text-white">
                    <span class="font-accent text-2xl" x-text="photos[index].caption"></span>
                    <span class="ml-3 text-sm text-white/60" x-text="(index + 1) + ' / ' + photos.length"></span>
                </figcaption>
            </figure>
        </template>
    </div>
</section>

{{-- ================================================================ --}}
{{-- Services & prices (server-rendered, indexable)                    --}}
{{-- ================================================================ --}}
<section id="services" class="bg-[#fbf7f1] py-20 md:py-28">
    <div class="mx-auto max-w-6xl px-5 sm:px-8">
        <div class="mb-12 text-center md:mb-16">
            <p data-reveal="up" class="text-xs font-semibold uppercase tracking-[0.35em] text-[#8d8540]">Services &amp; prices</p>
            <h2 data-reveal="up" class="mt-3 text-4xl font-bold tracking-tight text-[#413b0e] md:text-6xl">Nail services <span class="font-accent text-[#b0707a]">in Tallaght</span></h2>
            <p data-reveal="up" class="mx-auto mt-5 max-w-2xl text-lg text-gray-600">BIAB, gel nails, soft gel extensions and gel polish in a private studio in Tallaght, Dublin 24. Book online with a €15 deposit that comes off your final price.</p>
        </div>

        <div data-reveal-group class="grid gap-5 md:grid-cols-2">
            @foreach($menu as $key => $group)
                <article data-reveal="up" class="spotlight rounded-[1.75rem] p-6 shadow-[0_20px_50px_-35px_rgba(65,59,14,0.5)] md:p-7">
                    <h3 class="mb-3 flex items-center justify-between text-2xl font-bold text-[#413b0e]">
                        {{ $group['label'] }}
                        <span class="text-xs font-semibold uppercase tracking-[0.2em] text-[#8d8540]">{{ $group['services']->count() }} {{ $group['services']->count() === 1 ? 'option' : 'options' }}</span>
                    </h3>
                    <ul class="divide-y divide-stone-200/80">
                        @foreach($group['services'] as $service)
                            @php
                                $displayName = str_contains($service->name, ' - ') ? trim(explode(' - ', $service->name, 2)[1]) : $service->name;
                            @endphp
                            <li class="service-row -mx-3 flex items-center gap-4 rounded-2xl px-3 py-3">
                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold text-gray-900">{{ $displayName }}</p>
                                    <p class="text-sm text-gray-500">{{ $service->duration }} min @if($service->description) · {{ $service->description }} @endif</p>
                                </div>
                                <p class="shrink-0 text-lg font-bold text-[#554f13]">{{ $service->price > 0 ? '€'.rtrim(rtrim(number_format($service->price, 2), '0'), '.') : 'Free' }}</p>
                                <a href="{{ route('booking.create', ['service_id' => $service->id]) }}" class="shrink-0 rounded-full border border-[#554f13]/25 px-3 py-1.5 text-xs font-semibold text-[#554f13] transition hover:bg-[#554f13] hover:text-white" aria-label="Book {{ $service->name }}">Book</a>
                            </li>
                        @endforeach
                    </ul>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ================================================================ --}}
{{-- How it works                                                      --}}
{{-- ================================================================ --}}
<section class="bg-white py-20 md:py-28">
    <div class="mx-auto max-w-5xl px-5 sm:px-8">
        <div class="mb-14 text-center">
            <p data-reveal="up" class="text-xs font-semibold uppercase tracking-[0.35em] text-[#8d8540]">Booking made easy</p>
            <h2 data-reveal="up" class="mt-3 text-4xl font-bold tracking-tight text-[#413b0e] md:text-6xl">How it <span class="font-accent text-[#b0707a]">works</span></h2>
        </div>

        <ol class="relative grid gap-10 md:grid-cols-3 md:gap-6" data-steps>
            <span class="steps-line" aria-hidden="true"></span>
            @foreach($steps as $i => $step)
                <li class="step relative flex flex-col items-center text-center" data-reveal="up" style="--reveal-delay: {{ $i * 120 }}ms">
                    <span class="step-dot text-[#554f13]"><i class="fas {{ $step['icon'] }} text-lg"></i></span>
                    <p class="mt-5 text-xs font-semibold uppercase tracking-[0.25em] text-gray-400">Step {{ $i + 1 }}</p>
                    <h3 class="mt-1 text-xl font-semibold text-gray-900">{{ $step['title'] }}</h3>
                    <p class="mt-2 max-w-xs text-sm text-gray-600">{{ $step['text'] }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- ================================================================ --}}
{{-- FAQ                                                               --}}
{{-- ================================================================ --}}
<section id="faq" class="bg-[#fbf7f1] py-20 md:py-28">
    <div class="mx-auto grid max-w-6xl gap-10 px-5 sm:px-8 md:grid-cols-[1fr,1.5fr] md:gap-16">
        <div>
            <p data-reveal="up" class="text-xs font-semibold uppercase tracking-[0.35em] text-[#8d8540]">Good to know</p>
            <h2 data-reveal="up" class="mt-3 text-4xl font-bold tracking-tight text-[#413b0e] md:text-5xl">Questions, <span class="font-accent text-[#b0707a]">answered</span></h2>
            <p data-reveal="up" class="mt-5 text-gray-600">Anything else? Message Delfi on <a href="https://wa.me/353899409670" target="_blank" rel="noopener" class="font-semibold text-[#554f13] underline decoration-[#c9a24d] underline-offset-4">WhatsApp</a>.</p>
        </div>
        <div data-reveal-group class="space-y-3">
            @foreach($faqs as $faq)
                <details data-reveal="up" class="faq-item group rounded-2xl bg-white p-5 shadow-[0_14px_40px_-30px_rgba(65,59,14,0.6)] ring-1 ring-stone-100">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-gray-900">
                        {{ $faq['q'] }}
                        <span class="faq-icon flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#554f13]/10 text-[#554f13]"><i class="fas fa-plus text-xs"></i></span>
                    </summary>
                    <div class="faq-body"><div><p class="pt-3 text-gray-600">{{ $faq['a'] }}</p></div></div>
                </details>
            @endforeach
        </div>
    </div>
</section>

{{-- ================================================================ --}}
{{-- Final call to action                                              --}}
{{-- ================================================================ --}}
<section class="cta-band py-24 text-center md:py-32">
    <span class="cta-blob one" aria-hidden="true"></span>
    <span class="cta-blob two" aria-hidden="true"></span>
    <span class="cta-blob three" aria-hidden="true"></span>
    <div class="relative mx-auto max-w-3xl px-5">
        <h2 data-reveal="blur" class="text-4xl font-bold leading-tight tracking-tight text-[#fbf7f1] md:text-6xl">Ready for nails that feel <span class="font-accent text-[#f3d6d6]">like you?</span></h2>
        <p data-reveal="up" class="mx-auto mt-5 max-w-xl text-lg text-[#f4ecd9]/80">Choose your service, pick a time and secure it with a €15 deposit — it only takes a minute.</p>
        <div data-reveal="up" class="mt-9">
            <a href="{{ route('booking.create') }}" class="btn-magnetic inline-flex bg-[#fbf7f1] text-[#413b0e] shadow-[0_20px_50px_-20px_rgba(0,0,0,0.6)] hover:bg-white">
                <span class="btn-label">Book your appointment</span>
                <i class="fas fa-arrow-right text-sm btn-label"></i>
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Gallery lightbox (defined before Alpine starts).
    function deliGallery(photos) {
        return {
            photos,
            index: null,
            startX: null,
            open(i) { this.index = i; document.documentElement.classList.add('overflow-hidden'); },
            close() { this.index = null; document.documentElement.classList.remove('overflow-hidden'); },
            next() { this.index = (this.index + 1) % this.photos.length; },
            prev() { this.index = (this.index - 1 + this.photos.length) % this.photos.length; },
            onKey(e) {
                if (this.index === null) return;
                if (e.key === 'Escape') this.close();
                if (e.key === 'ArrowRight') this.next();
                if (e.key === 'ArrowLeft') this.prev();
            },
            touchStart(e) { this.startX = e.changedTouches[0].clientX; },
            touchEnd(e) {
                if (this.startX === null) return;
                const dx = e.changedTouches[0].clientX - this.startX;
                if (Math.abs(dx) > 50) (dx < 0 ? this.next() : this.prev());
                this.startX = null;
            },
        };
    }
</script>
@endpush
