@props([
    'name',
    'id' => null,
    'options' => [],
    'jsOptions' => '[]',
    'selected' => null,
    'placeholder' => 'Sélectionner une option',
    'required' => false,
])

<div x-data="{
    open: false,
    selected: @js($selected),
    options: {{ $jsOptions !== '[]' ? $jsOptions : Js::from($options) }},
    placeholder: @js($placeholder),
    
    get selectedLabel() {
        if (!this.selected) return this.placeholder;
        const option = this.options.find(opt => opt.value == this.selected);
        return option ? option.label : this.placeholder;
    },
    
    select(value) {
        this.selected = value;
        this.open = false;
    }
}" 
x-modelable="selected"
{{ $attributes->only(['x-model', 'x-model.defer']) }}
class="relative w-full">
    <!-- Hidden Input for Form Submission -->
    <input type="hidden" :name="`{{ $name }}`" :value="selected" {{ $required ? 'required' : '' }} id="{{ $id ?? $name }}">

    <!-- Dropdown Trigger -->
    <button type="button" 
            @click="open = !open" 
            @click.away="open = false"
            {{ $attributes->except(['x-model', 'x-model.defer'])->merge(['class' => 'w-full bg-slate-50 border-2 border-transparent rounded-[24px] py-4 px-6 font-bold text-slate-900 flex items-center justify-between hover:bg-slate-100 focus:bg-white focus:border-primary-500 transition-all outline-none group']) }}>
        <span :class="!selected ? 'text-slate-400' : 'text-slate-900'" class="uppercase text-xs tracking-widest truncate mr-2" x-text="selectedLabel"></span>
        <svg class="size-4 text-slate-400 group-hover:text-primary-500 transition-transform duration-300 shrink-0" 
             :class="open ? 'rotate-180' : ''" 
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
            <path d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         class="absolute z-50 w-full mt-3 bg-white border border-slate-200 rounded-[2rem] shadow-premium overflow-hidden p-2"
         x-cloak>
        
        <div class="max-h-60 overflow-y-auto custom-scrollbar">
            <template x-for="option in options" :key="option.value">
                <button type="button" 
                        @click="select(option.value)"
                        class="w-full text-left px-5 py-3.5 rounded-2xl text-xs font-black uppercase tracking-widest transition-all"
                        :class="selected == option.value ? 'bg-primary-500 text-white shadow-lg shadow-primary-500/20' : 'text-slate-600 hover:bg-primary-50 hover:text-primary-600'">
                    <span x-text="option.label"></span>
                </button>
            </template>
        </div>
    </div>
</div>
