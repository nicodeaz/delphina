{{--
    Modal that behaves as a bottom sheet on phones and a centred dialog on
    tablet/desktop. `show` is the Alpine expression that opens it and `close`
    the one that closes it (both evaluated in the parent x-data scope).
--}}
@props(['show', 'close', 'maxWidth' => 'md:max-w-lg', 'label' => null])

<div
    x-show="{{ $show }}"
    x-cloak
    class="fixed inset-0 z-[80] flex items-end justify-center md:items-center md:p-6"
    @keydown.escape.window="if ({{ $show }}) { {{ $close }} }"
    role="dialog"
    aria-modal="true"
    @if($label) aria-label="{{ $label }}" @endif
>
    <div
        x-show="{{ $show }}"
        x-transition.opacity
        class="absolute inset-0 bg-black/50 backdrop-blur-[2px]"
        @click="{{ $close }}"
    ></div>

    <div
        x-show="{{ $show }}"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="translate-y-full md:translate-y-4 md:opacity-0"
        x-transition:enter-end="translate-y-0 md:opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-y-0 md:opacity-100"
        x-transition:leave-end="translate-y-full md:translate-y-4 md:opacity-0"
        {{ $attributes->merge(['class' => "relative w-full {$maxWidth} max-h-[92dvh] overflow-y-auto overscroll-contain rounded-t-[1.75rem] bg-white shadow-2xl md:rounded-[1.75rem]"]) }}
    >
        <div class="sticky top-0 z-10 flex justify-center bg-white pt-3 md:hidden">
            <span class="h-1.5 w-12 rounded-full bg-stone-200"></span>
        </div>
        {{ $slot }}
    </div>
</div>
