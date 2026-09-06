@props([
    'name' => null,
    'id' => null,
    'show' => false,
    'maxWidth' => 'md',
    'title' => null,
    'icon' => null,
    'focusable' => false
])

@php
$modalId = $id ?? ($name ?? 'tokobii-modal-' . md5(uniqid('', true)));
$modalName = $name ?? $modalId;

$maxWidthClasses = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    '3xl' => 'max-w-3xl',
    'full' => 'max-w-full',
][$maxWidth] ?? 'max-w-md';
@endphp

<div
    id="{{ $modalId }}"
    data-tokobii-modal="{{ $modalName }}"
    x-data="{
        show: @js((bool)$show),
        focusables() {
            let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])';
            return [...$el.querySelectorAll(selector)].filter(el => ! el.hasAttribute('disabled'));
        },
        firstFocusable() { return this.focusables()[0]; },
        lastFocusable() { return this.focusables().slice(-1)[0]; },
        nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable(); },
        prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable(); },
        nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1); },
        prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) - 1; },
        close() {
            this.show = false;
            $dispatch('close-modal', '{{ $modalName }}');
        }
    }"
    x-init="
        $watch('show', value => {
            if (value) {
                document.body.classList.add('tokobii-modal-open', 'modal-open');
                if (@js((bool)$focusable)) {
                    setTimeout(() => firstFocusable()?.focus(), 120);
                }
            } else {
                document.body.classList.remove('tokobii-modal-open', 'modal-open');
            }
        });
        if (show) {
            document.body.classList.add('tokobii-modal-open', 'modal-open');
        }
    "
    x-on:open-modal.window="$event.detail === '{{ $modalName }}' ? show = true : null"
    x-on:close-modal.window="$event.detail === '{{ $modalName }}' ? show = false : null"
    x-on:close.stop="close()"
    x-on:keydown.escape.window="if (show) close()"
    x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
    x-on:keydown.shift.tab.prevent="prevFocusable().focus()"
    x-show="show"
    x-cloak
    class="fixed inset-0 w-screen h-screen overflow-y-auto flex items-center justify-center p-4 z-[99995]"
    style="display: {{ $show ? 'flex' : 'none' }}; z-index: 99995 !important;"
    role="dialog"
    aria-modal="true"
>
    {{-- Full-Screen Dark Overlay Backdrop (z-[99990]) --}}
    <div
        x-show="show"
        class="fixed inset-0 w-screen h-screen bg-slate-950/75 backdrop-blur-md transition-opacity duration-300"
        style="z-index: 99990 !important;"
        x-on:click="close()"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        aria-hidden="true"
    ></div>

    {{-- Modal Box Container (z-[100000]) --}}
    <div
        x-show="show"
        class="relative z-[100000] w-full {{ $maxWidthClasses }} bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100 transition-all duration-300 transform my-auto"
        style="z-index: 100000 !important;"
        x-on:click.stop
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-90 translate-y-4"
    >
        @if($title)
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                    @if($icon)
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                            {!! $icon !!}
                        </div>
                    @endif
                    <h3 class="font-bold text-slate-900 text-lg leading-tight mb-0">
                        {{ $title }}
                    </h3>
                </div>
                <button
                    type="button"
                    class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition-colors focus:outline-none"
                    x-on:click="close()"
                    aria-label="Tutup"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Main Body Content --}}
        <div>
            {{ $slot }}
        </div>
    </div>
</div>
