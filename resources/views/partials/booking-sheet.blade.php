{{--
    Instagram-style booking: a bar pinned to the bottom of every public page
    that slides up (tap or swipe) into a sheet holding the booking flow.
    Links to /book anywhere on the page open the sheet instead of navigating,
    and ?book=1 in the URL (handy for Instagram bio/ad links) opens it on load.
--}}
<div
    x-data="bookingSheet()"
    @close-booking.window="close()"
    @keydown.escape.window="open && close()"
    class="booking-sheet-root"
>
    {{-- Collapsed bar --}}
    <div
        x-show="!open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full"
        x-transition:enter-end="translate-y-0"
        class="fixed inset-x-0 bottom-0 z-[65] md:inset-x-auto md:bottom-5 md:left-1/2 md:w-[440px] md:-translate-x-1/2"
        @touchstart.passive="barTouchStart($event)"
        @touchmove.passive="barTouchMove($event)"
    >
        <div class="rounded-t-[1.5rem] border-t border-stone-200 bg-white/95 px-4 pb-3 pt-2 shadow-[0_-10px_30px_rgba(65,59,14,0.14)] backdrop-blur md:rounded-[1.5rem] md:border md:pt-3" style="padding-bottom: max(0.75rem, env(safe-area-inset-bottom));">
            <button type="button" @click="show()" class="mx-auto mb-2 block h-1.5 w-10 rounded-full bg-stone-300 md:hidden" aria-label="Open booking"></button>
            <div class="flex items-center gap-3">
                <button type="button" @click="show()" class="min-w-0 flex-1 text-left">
                    <p class="truncate text-sm font-semibold text-gray-900">Book your appointment</p>
                    <p class="truncate text-xs text-gray-500"><i class="fas fa-chevron-up mr-1 text-[10px] text-olive md:hidden"></i>Choose service &amp; time · €15 deposit</p>
                </button>
                <button type="button" @click="show()" class="shrink-0 rounded-2xl bg-olive px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-green-700">
                    Book now
                </button>
            </div>
        </div>
    </div>

    {{-- Backdrop --}}
    <div x-show="open" x-cloak x-transition.opacity class="fixed inset-0 z-[90] bg-black/50" @click="close()"></div>

    {{-- Sheet --}}
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-full"
        x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        class="fixed inset-x-0 bottom-0 z-[95] mx-auto flex h-[92dvh] max-w-2xl flex-col rounded-t-[1.75rem] bg-white shadow-2xl"
        :style="dragY > 0 ? `transform: translateY(${dragY}px); transition: none;` : ''"
        role="dialog"
        aria-modal="true"
        aria-label="Book an appointment"
    >
        <div
            class="flex shrink-0 cursor-grab justify-center pb-2 pt-3 active:cursor-grabbing"
            @touchstart.passive="dragStart($event)"
            @touchmove.passive="dragMove($event)"
            @touchend="dragEnd()"
        >
            <span class="h-1.5 w-12 rounded-full bg-stone-300"></span>
        </div>

        @include('partials.booking-flow', ['mode' => 'sheet'])
    </div>
</div>

<script>
    function bookingSheet() {
        return {
            open: false,
            dragY: 0,
            startY: null,
            barStartY: null,

            init() {
                const params = new URLSearchParams(window.location.search);
                if (params.has('book')) {
                    this.$nextTick(() => this.show({ serviceId: params.get('service_id') }));
                }

                // Any link to the booking page opens the sheet instead.
                document.addEventListener('click', (event) => {
                    const link = event.target.closest('a[href]');
                    if (!link || event.metaKey || event.ctrlKey || event.shiftKey) return;
                    let url;
                    try { url = new URL(link.href, window.location.href); } catch (e) { return; }
                    const bookPaths = ['/book', '/booking'].map((path) => new URL(window.appBaseUrl + path).pathname);
                    if (url.origin !== window.location.origin || !bookPaths.includes(url.pathname)) return;
                    event.preventDefault();
                    this.show({ serviceId: url.searchParams.get('service_id') });
                });
            },

            show(detail = {}) {
                this.open = true;
                this.dragY = 0;
                document.documentElement.classList.add('overflow-hidden');
                this.$dispatch('open-booking', detail);
            },
            close() {
                this.open = false;
                this.dragY = 0;
                document.documentElement.classList.remove('overflow-hidden');
            },

            // Swipe up on the collapsed bar opens the sheet.
            barTouchStart(e) { this.barStartY = e.touches[0].clientY; },
            barTouchMove(e) {
                if (this.barStartY !== null && this.barStartY - e.touches[0].clientY > 25) {
                    this.barStartY = null;
                    this.show();
                }
            },

            // Drag the handle down to dismiss.
            dragStart(e) { this.startY = e.touches[0].clientY; },
            dragMove(e) {
                if (this.startY === null) return;
                this.dragY = Math.max(0, e.touches[0].clientY - this.startY);
            },
            dragEnd() {
                if (this.dragY > 120) this.close();
                this.dragY = 0;
                this.startY = null;
            },
        };
    }
</script>
