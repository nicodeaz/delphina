@extends('layouts.admin')

@section('title', 'Get the phone app')

@section('content')
@php
    $iphoneSteps = [
        ['icon' => 'fa-arrow-up-from-bracket', 'title' => 'Tap the Share button', 'text' => 'It’s in Safari’s bar at the bottom of the screen. On the newest iPhones, tap ••• first and then Share.'],
        ['icon' => 'fa-square-plus', 'title' => 'Tap “Add to Home Screen”', 'text' => 'Scroll down the list a little if you don’t see it straight away.'],
        ['icon' => 'fa-check', 'title' => 'Tap “Add”', 'text' => 'If you see “Open as Web App”, leave it switched on.'],
    ];
    $androidSteps = [
        ['icon' => 'fa-ellipsis-vertical', 'title' => 'Tap the ⋮ menu', 'text' => 'Top right corner in Chrome.'],
        ['icon' => 'fa-mobile-screen-button', 'title' => 'Tap “Install app” or “Add to Home screen”', 'text' => 'The name changes a little from phone to phone.'],
        ['icon' => 'fa-check', 'title' => 'Tap “Install”', 'text' => 'The app appears with your other apps.'],
    ];
@endphp
<div class="mx-auto w-full max-w-md px-4 py-10" x-data="installGuide()">
    <div class="text-center">
        <img src="{{ asset('img/studio/icon-192.png') }}" alt="" class="mx-auto h-20 w-20 rounded-[1.4rem] shadow-xl">
        <p class="mt-5 text-xs font-semibold uppercase tracking-[0.3em] text-olive">Delphina Studio</p>
        <h1 class="mt-2 text-2xl font-semibold text-gray-900">Your studio, one tap away</h1>
        <p class="mt-2 text-sm text-gray-600">Put the agenda on your home screen like any other app. It takes 30 seconds.</p>
    </div>

    {{-- Already running as the installed app --}}
    <div x-show="installed" x-cloak class="mt-8 rounded-[1.75rem] bg-white p-6 text-center shadow-xl ring-1 ring-stone-100">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-green-50 text-green-700"><i class="fas fa-check text-xl"></i></div>
        <h2 class="mt-4 text-lg font-semibold text-gray-900">You’re all set</h2>
        <p class="mt-1 text-sm text-gray-600">You’re using the Delfi Studio app.</p>
        <a href="{{ route('admin.agenda') }}" class="mt-5 block rounded-2xl bg-olive px-6 py-3.5 font-semibold text-white shadow-lg hover:bg-green-700">Go to the agenda</a>
    </div>

    <div x-show="!installed">
        {{-- Android / Chrome can install with one button --}}
        <div x-show="canPrompt" x-cloak class="mt-8 rounded-[1.75rem] bg-white p-6 text-center shadow-xl ring-1 ring-stone-100">
            <button type="button" @click="install()" class="w-full rounded-2xl bg-olive px-6 py-4 text-lg font-semibold text-white shadow-lg hover:bg-green-700">
                <i class="fas fa-download mr-2"></i>Install Delfi Studio
            </button>
            <p class="mt-3 text-xs text-gray-500">Then confirm with “Install”.</p>
        </div>

        {{-- iPhone outside Safari (Gmail, Instagram, Chrome…): Add to Home Screen lives in Safari --}}
        <div x-show="device === 'iphone' && !isSafari" x-cloak class="mt-8 rounded-2xl bg-amber-50 p-4 text-sm text-amber-900 ring-1 ring-amber-200">
            <p class="font-semibold"><i class="fab fa-safari mr-1"></i>First, open this page in Safari</p>
            <p class="mt-1">Copy the link, open Safari and paste it in the address bar. Then follow the steps below.</p>
            <button type="button" @click="copyLink()" class="mt-3 w-full rounded-xl bg-white px-4 py-2.5 font-semibold text-amber-900 ring-1 ring-amber-200" x-text="copied ? 'Link copied ✓' : 'Copy link'">Copy link</button>
        </div>

        <div x-show="!canPrompt" class="mt-8">
            <div class="grid grid-cols-2 gap-1 rounded-2xl bg-stone-200/60 p-1 text-sm font-semibold" role="tablist">
                <button type="button" role="tab" :aria-selected="tab === 'iphone'" @click="tab = 'iphone'" :class="tab === 'iphone' ? 'bg-white text-gray-900 shadow' : 'text-gray-500'" class="rounded-xl py-2.5"><i class="fab fa-apple mr-1"></i>iPhone</button>
                <button type="button" role="tab" :aria-selected="tab === 'android'" @click="tab = 'android'" :class="tab === 'android' ? 'bg-white text-gray-900 shadow' : 'text-gray-500'" class="rounded-xl py-2.5"><i class="fab fa-android mr-1"></i>Android</button>
            </div>

            @foreach(['iphone' => $iphoneSteps, 'android' => $androidSteps] as $platform => $steps)
                <ol x-show="tab === '{{ $platform }}'" @if($platform !== 'iphone') x-cloak @endif class="mt-4 space-y-3">
                    @foreach($steps as $i => $step)
                        <li class="flex gap-4 rounded-2xl bg-white p-4 shadow-sm ring-1 ring-stone-100">
                            <div class="relative flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-olive/10 text-olive">
                                <i class="fas {{ $step['icon'] }} text-lg"></i>
                                <span class="absolute -left-1.5 -top-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-olive text-[11px] font-bold text-white">{{ $i + 1 }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $step['title'] }}</p>
                                <p class="mt-0.5 text-sm text-gray-600">{{ $step['text'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            @endforeach

            <div class="mt-3 flex items-center gap-4 rounded-2xl bg-olive p-4 text-white shadow-lg">
                <div class="flex shrink-0 flex-col items-center">
                    <img src="{{ asset('img/studio/icon-192.png') }}" alt="" class="h-12 w-12 rounded-xl ring-1 ring-white/20">
                    <span class="mt-1 text-[10px]">Delfi Studio</span>
                </div>
                <div>
                    <p class="font-semibold">Open it from your home screen</p>
                    <p class="mt-0.5 text-sm text-white/80">Log in once inside the app and it keeps you logged in.</p>
                </div>
            </div>
        </div>

        {{-- On a computer: the app is for the phone --}}
        <div x-show="device === 'desktop'" x-cloak class="mt-6 rounded-2xl bg-white p-4 text-sm text-gray-600 shadow-sm ring-1 ring-stone-100">
            <p class="font-semibold text-gray-900"><i class="fas fa-mobile-screen mr-1 text-olive"></i>Open this page on your phone</p>
            <p class="mt-1">Send yourself this link and open it on the phone:</p>
            <div class="mt-3 flex gap-2">
                <input type="text" readonly value="{{ route('admin.install') }}" class="min-w-0 flex-1 rounded-xl border border-stone-200 bg-stone-50 px-3 py-2 text-sm text-gray-700" @focus="$event.target.select()">
                <button type="button" @click="copyLink()" class="shrink-0 rounded-xl bg-olive px-4 py-2 font-semibold text-white" x-text="copied ? 'Copied ✓' : 'Copy'">Copy</button>
            </div>
        </div>
    </div>

    <div class="mt-8 rounded-2xl border border-dashed border-stone-300 p-4 text-sm text-gray-600">
        <p class="font-semibold text-gray-900"><i class="fas fa-key mr-1 text-olive"></i>Forgot your password?</p>
        <p class="mt-1">On the login screen tap <strong>Forgot your password?</strong> and we’ll email you a login link. It works for {{ config('auth.passwords.users.expire') }} minutes.</p>
    </div>

    @guest
        <a href="{{ route('admin.login') }}" class="mt-6 block text-center text-sm text-gray-500 hover:text-olive">← Back to log in</a>
    @endguest
</div>
@endsection

@push('scripts')
<script>
    function installGuide() {
        const ua = navigator.userAgent;
        const isIphone = /iPhone|iPad|iPod/.test(ua) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
        const isAndroid = /Android/.test(ua);

        return {
            installed: window.isStudioApp(),
            device: isIphone ? 'iphone' : (isAndroid ? 'android' : 'desktop'),
            tab: isAndroid ? 'android' : 'iphone',
            // Other iPhone browsers and in-app browsers add their own marker to the user agent.
            isSafari: /Safari/.test(ua) && !/CriOS|FxiOS|EdgiOS|OPiOS|GSA|Instagram|FBAN|FBAV|Line\//.test(ua),
            canPrompt: !!window.studioInstallPrompt,
            copied: false,
            init() {
                window.addEventListener('studio-installable', () => { this.canPrompt = true; });
                window.addEventListener('appinstalled', () => { this.installed = true; });
            },
            async install() {
                const prompt = window.studioInstallPrompt;
                if (!prompt) return;
                prompt.prompt();
                const { outcome } = await prompt.userChoice;
                window.studioInstallPrompt = null;
                this.canPrompt = false;
                if (outcome === 'accepted') this.installed = true;
            },
            async copyLink() {
                try {
                    await navigator.clipboard.writeText(@json(route('admin.install')));
                    this.copied = true;
                    setTimeout(() => { this.copied = false; }, 2500);
                } catch (e) {
                    window.prompt('Copy this link:', @json(route('admin.install')));
                }
            },
        };
    }
</script>
@endpush
