@props([
    'placeholder' => 'Rechercher...',
    'options' => [], // [{value: 'brouillon', label: 'Brouillons', count: 5}]
])

<div x-data="{
    search: '',
    filter: '',
    showClear: false,
    
    init() {
        const url = new URLSearchParams(window.location.search);
        this.search = url.get('q') || '';
        this.filter = url.get('filter') || '';
        this.showClear = this.search || this.filter;
    },
    
    clear() {
        this.search = '';
        this.filter = '';
        this.showClear = false;
        this.$dispatch('filter-change', { q: '', filter: '' });
    },
    
    apply() {
        this.showClear = this.search || this.filter;
        this.$dispatch('filter-change', { q: this.search, filter: this.filter });
    }
}" class="flex flex-col sm:flex-row gap-3">
    
    <!-- Search Input -->
    <div class="flex-1 relative">
        <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-slate-300 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" x-model="search" @input="apply()" @keydown.enter="apply()"
               placeholder="{{ $placeholder }}"
               class="w-full h-12 bg-white border-2 border-slate-100 rounded-xl pl-12 pr-10 text-sm font-bold text-slate-900 placeholder:text-slate-300 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 outline-none transition-all">
        <button type="button" x-show="showClear" @click="clear()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500 transition-colors">
            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    
    <!-- Filter Dropdown -->
    @if(count($options) > 0)
    <div x-data="{ open: false }" class="relative w-full sm:w-48">
        <button type="button" @click="open = !open" @click.away="open = false"
                class="w-full h-12 px-4 bg-white border-2 border-slate-100 rounded-xl flex items-center justify-between text-sm font-bold text-slate-600 hover:border-primary-300 transition-all">
            <span x-text="filter ? filter : 'Tous'"></span>
            <svg class="size-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 9l-7 7-7-7"/></svg>
        </button>
        
        <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
             class="absolute z-50 w-full mt-2 bg-white border-2 border-slate-100 rounded-xl shadow-lg overflow-hidden" style="display: none;">
            <button type="button" @click="filter = ''; open = false; apply()" 
                    class="w-full px-4 py-3 text-left text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors flex items-center justify-between">
                <span>Tous</span>
                <span x-show="!filter" class="size-2 bg-primary-500 rounded-full"></span>
            </button>
            @foreach($options as $opt)
            <button type="button" @click="filter = '{{ $opt['value'] }}'; open = false; apply()" 
                    class="w-full px-4 py-3 text-left text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors flex items-center justify-between">
                <span>{{ $opt['label'] }}</span>
                <span x-show="filter === '{{ $opt['value'] }}'" class="size-2 bg-primary-500 rounded-full"></span>
            </button>
            @endforeach
        </div>
    </div>
    @endif
</div>