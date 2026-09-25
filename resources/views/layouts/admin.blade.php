<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#554F13">
    <title>{{ trim($__env->yieldContent('title', 'Studio')) }} · Delphina Studio</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/apple-touch-icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Poppins', sans-serif; }
    </style>
    @stack('styles')
</head>
@php
    $isAdmin = (bool) auth()->user()?->isAdmin();

    $awaitingDepositCount = $isAdmin
        ? \App\Models\Payment::where('status', 'pending')
            ->whereHas('appointment', fn ($q) => $q->where('status', 'pending')->whereDate('date', '>=', today()))
            ->count()
        : 0;

    $adminNav = [
        ['route' => 'admin.agenda', 'match' => 'admin.agenda', 'icon' => 'fa-calendar-days', 'label' => 'Agenda'],
        ['route' => 'admin.appointments.index', 'match' => 'admin.appointments.*', 'icon' => 'fa-list-check', 'label' => 'Bookings', 'badge' => $awaitingDepositCount],
        ['route' => 'admin.services.index', 'match' => 'admin.services.*', 'icon' => 'fa-spa', 'label' => 'Services'],
        ['route' => 'admin.dashboard', 'match' => 'admin.dashboard', 'icon' => 'fa-chart-simple', 'label' => 'Stats'],
    ];
@endphp
<body class="min-h-screen bg-[#f7f5ee] text-gray-800 antialiased">
    @if($isAdmin)
        {{-- Desktop sidebar --}}
        <aside x-data class="fixed inset-y-0 left-0 z-40 hidden w-60 flex-col border-r border-stone-200 bg-white md:flex">
            <a href="{{ route('admin.agenda') }}" class="flex items-center gap-3 px-6 pb-6 pt-7">
                <img src="{{ asset('img/logo_green.png') }}" alt="" class="h-10 w-auto">
                <span class="leading-tight">
                    <span class="block text-sm font-bold text-gray-900">Delphina</span>
                    <span class="block text-[11px] font-semibold uppercase tracking-[0.2em] text-olive">Studio</span>
                </span>
            </a>

            <nav class="flex-1 space-y-1 px-3" aria-label="Studio admin">
                @foreach($adminNav as $item)
                    @php $active = request()->routeIs($item['match']); @endphp
                    <a href="{{ route($item['route']) }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ $active ? 'bg-olive text-white shadow' : 'text-gray-600 hover:bg-olive/10 hover:text-olive' }}">
                        <i class="fas {{ $item['icon'] }} w-5 text-center"></i>
                        <span class="flex-1">{{ $item['label'] }}</span>
                        @if(! empty($item['badge']))
                            <span class="rounded-full px-2 py-0.5 text-[11px] font-bold {{ $active ? 'bg-white text-olive' : 'bg-amber-500 text-white' }}">{{ $item['badge'] }}</span>
                        @endif
                    </a>
                @endforeach
            </nav>

            <div class="space-y-1 border-t border-stone-100 p-3">
                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-500 hover:bg-stone-100">
                    <i class="fas fa-arrow-up-right-from-square w-5 text-center"></i>View website
                </a>
                <button type="button" @click="$dispatch('open-password')" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-500 hover:bg-stone-100">
                    <i class="fas fa-key w-5 text-center"></i>Change password
                </button>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-500 hover:bg-stone-100">
                        <i class="fas fa-right-from-bracket w-5 text-center"></i>Log out
                    </button>
                </form>
            </div>
        </aside>

        {{-- Phone top bar --}}
        <header class="sticky top-0 z-40 flex h-14 items-center justify-between border-b border-stone-200 bg-white/90 px-4 backdrop-blur md:hidden" x-data="{ menu: false }">
            <a href="{{ route('admin.agenda') }}" class="flex items-center gap-2">
                <img src="{{ asset('img/logo_green.png') }}" alt="" class="h-8 w-auto">
                <span class="text-sm font-bold text-gray-900">@yield('title', 'Studio')</span>
            </a>
            <button type="button" @click="menu = !menu" class="flex h-10 w-10 items-center justify-center rounded-full text-gray-500 hover:bg-stone-100" aria-label="Menu">
                <i class="fas fa-ellipsis-vertical"></i>
            </button>
            <div x-show="menu" x-cloak @click.outside="menu = false" x-transition class="absolute right-3 top-12 w-52 rounded-2xl border border-stone-200 bg-white p-2 shadow-xl">
                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-gray-700 hover:bg-stone-100">
                    <i class="fas fa-arrow-up-right-from-square w-5 text-center text-gray-400"></i>View website
                </a>
                <button type="button" @click="menu = false; $dispatch('open-password')" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-gray-700 hover:bg-stone-100">
                    <i class="fas fa-key w-5 text-center text-gray-400"></i>Change password
                </button>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-gray-700 hover:bg-stone-100">
                        <i class="fas fa-right-from-bracket w-5 text-center text-gray-400"></i>Log out
                    </button>
                </form>
            </div>
        </header>
    @endif

    <main class="{{ $isAdmin ? 'pb-24 md:pb-10 md:pl-60' : '' }}">
        @yield('content')
    </main>

    @if($isAdmin)
        {{-- Phone tab bar --}}
        <nav class="fixed inset-x-0 bottom-0 z-[60] border-t border-stone-200 bg-white/95 backdrop-blur md:hidden" style="padding-bottom: env(safe-area-inset-bottom);" aria-label="Studio admin">
            <div class="grid grid-cols-4">
                @foreach($adminNav as $item)
                    @php $active = request()->routeIs($item['match']); @endphp
                    <a href="{{ route($item['route']) }}" class="relative flex flex-col items-center gap-1 py-2.5 text-[11px] font-semibold {{ $active ? 'text-olive' : 'text-gray-400' }}">
                        <i class="fas {{ $item['icon'] }} text-lg"></i>{{ $item['label'] }}
                        @if(! empty($item['badge']))
                            <span class="absolute left-1/2 top-1.5 ml-2 rounded-full bg-amber-500 px-1.5 text-[10px] font-bold leading-4 text-white">{{ $item['badge'] }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </nav>

        {{-- Change password --}}
        <div x-data="passwordForm()" @open-password.window="open()">
            <x-sheet show="isOpen" close="isOpen = false" max-width="md:max-w-sm" label="Change password">
                <form class="p-6 pt-3 md:pt-6" @submit.prevent="save()">
                    <div class="mb-5 flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-olive">Account</p>
                            <h3 class="mt-1 text-xl font-semibold text-gray-900">Change password</h3>
                        </div>
                        <button type="button" @click="isOpen = false" class="flex h-9 w-9 items-center justify-center rounded-full text-gray-400 hover:bg-stone-100" aria-label="Close"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label for="pw_current" class="mb-1 block text-sm font-medium text-gray-700">Current password</label>
                            <input id="pw_current" type="password" x-model="form.current_password" autocomplete="current-password" required class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-base focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20">
                            <p class="mt-1 text-xs text-red-600" x-show="errors.current_password" x-text="errors.current_password?.[0]"></p>
                        </div>
                        <div>
                            <label for="pw_new" class="mb-1 block text-sm font-medium text-gray-700">New password</label>
                            <input id="pw_new" type="password" x-model="form.password" autocomplete="new-password" minlength="10" required class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-base focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20">
                            <p class="mt-1 text-xs text-gray-400">At least 10 characters. A short phrase is easiest to remember.</p>
                            <p class="mt-1 text-xs text-red-600" x-show="errors.password" x-text="errors.password?.[0]"></p>
                        </div>
                        <div>
                            <label for="pw_confirm" class="mb-1 block text-sm font-medium text-gray-700">Repeat new password</label>
                            <input id="pw_confirm" type="password" x-model="form.password_confirmation" autocomplete="new-password" required class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-base focus:border-olive focus:outline-none focus:ring-2 focus:ring-olive/20">
                        </div>
                    </div>
                    <button type="submit" :disabled="saving" class="mt-6 w-full rounded-2xl bg-olive px-6 py-3.5 font-semibold text-white shadow-lg transition hover:bg-green-700 disabled:opacity-60" x-text="saving ? 'Saving…' : 'Save new password'"></button>
                </form>
            </x-sheet>
        </div>
        <script>
            function passwordForm() {
                const empty = () => ({ current_password: '', password: '', password_confirmation: '' });
                return {
                    isOpen: false,
                    saving: false,
                    form: empty(),
                    errors: {},
                    open() {
                        this.form = empty();
                        this.errors = {};
                        this.isOpen = true;
                    },
                    async save() {
                        this.saving = true;
                        this.errors = {};
                        try {
                            await axios.put('/admin/password', this.form);
                            this.isOpen = false;
                            toastr.success('Password changed. Other devices will need to log in again.');
                        } catch (error) {
                            this.errors = error.response?.data?.errors || {};
                            toastr.error(error.response?.data?.message || 'Could not change the password.');
                        } finally {
                            this.saving = false;
                        }
                    },
                };
            }
        </script>
    @endif

    <script>
        toastr.options = { closeButton: true, progressBar: true, positionClass: 'toast-top-right', timeOut: 4000 };

        // Page scripts call axios with paths like '/admin/...'; resolve them
        // against the real base URL so a subfolder install works too.
        window.appBaseUrl = @json(url('/'));
        document.addEventListener('DOMContentLoaded', () => {
            if (window.axios) {
                window.axios.defaults.baseURL = window.appBaseUrl;
            }
        });
    </script>
    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => toastr.success(@json(session('success'))));</script>
    @endif
    @if(session('error'))
        <script>document.addEventListener('DOMContentLoaded', () => toastr.error(@json(session('error'))));</script>
    @endif

    @stack('scripts')
</body>
</html>
