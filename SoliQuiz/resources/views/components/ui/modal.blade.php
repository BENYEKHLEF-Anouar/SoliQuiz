@props([
    'name',
    'title' => null,
    'maxWidth' => '2xl',
    'show' => false,
])

@php
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth];
@endphp

<div
    x-data="{
        show: @js($show),
        focusables() {
            let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'
            return [...$el.querySelectorAll(selector)]
                .filter(el => ! el.hasAttribute('disabled'))
        },
        firstFocusable() { return this.focusables()[0] },
        lastFocusable() { return this.focusables().slice(-1)[0] },
        nextFocusable() { return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable() },
        prevFocusable() { return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable() },
        nextFocusableIndex() { return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1) },
        prevFocusableIndex() { return Math.max(0, this.focusables().indexOf(document.activeElement)) - 1 },
    }"
    x-init="$watch('show', value => {
        if (value) {
            document.body.classList.add('overflow-y-hidden');
            {{ $attributes->has('focusable') ? 'setTimeout(() => firstFocusable().focus(), 100)' : '' }}
        } else {
            document.body.classList.remove('overflow-y-hidden');
        }
    })"
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:close-modal.window="$event.detail == '{{ $name }}' ? show = false : null"
    x-on:close.stop="show = false"
    x-on:keydown.escape.window="show = false"
    x-on:keydown.tab.prevent="$event.shiftKey ? prevFocusable().focus() : nextFocusable().focus()"
    x-show="show"
    class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6 md:p-10"
    x-cloak>
    
    <!-- Backdrop without Blur -->
    <div
        x-show="show"
        class="fixed inset-0 transform transition-all"
        x-on:click="show = false"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="absolute inset-0 bg-slate-900/40"></div>
    </div>

    <!-- Modal Content: Premium Container -->
    <div
        x-show="show"
        class="relative z-10 bg-white rounded-[3rem] shadow-2xl transform transition-all w-full {{ $maxWidth }} border border-white/40 overflow-hidden max-h-[90vh] flex flex-col"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
    >
        <!-- Decorative Header Gradient -->
        <div class="absolute top-0 left-0 w-full h-32 bg-linear-to-b from-slate-50 to-transparent -z-10"></div>
        
        @if($title)
            <div class="px-10 pt-10 pb-2 flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-black text-primary-500 uppercase tracking-[0.3em] mb-1 leading-none">Configuration</p>
                    <h3 class="text-3xl font-heading font-black text-slate-900 tracking-tight leading-none uppercase">{!! $title !!}</h3>
                </div>
                <button @click="show = false" class="group size-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-all active:scale-90 shadow-sm border border-slate-100">
                    <svg class="size-6 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        <div class="flex-1 overflow-y-auto custom-scrollbar">
            <div class="px-10 py-8 relative">
                {{ $slot }}
            </div>
        </div>

        @if(isset($footer))
            <div class="px-10 py-8 bg-slate-50/50 border-t border-slate-100 flex flex-col sm:flex-row justify-end gap-4">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>

