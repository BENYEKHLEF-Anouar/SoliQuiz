@props([
    'name',
    'value' => null,
    'placeholder' => 'Sélectionner une date',
    'required' => false,
    'id' => null,
])

@php
    $id = $id ?? 'datepicker_' . uniqid();
@endphp

<div class="relative w-full" 
     x-data="datePickerComponent({
         value: '{{ $value }}'
     })"
     x-init="init(); $nextTick(() => {
         const observer = new MutationObserver(() => {
             value = $refs.hiddenInput.value;
             if (value) {
                 const date = new Date(value);
                 currentYear = date.getFullYear();
                 currentMonth = date.getMonth();
                 selectedDay = date.getDate();
             } else {
                 selectedDay = null;
             }
             updateDisplayValue();
         });
         observer.observe($refs.hiddenInput, { attributes: true, attributeFilter: ['value'] });
     })">
    
    <!-- Hidden Input for Form Submission -->
    <input type="hidden" 
           name="{{ $name }}" 
           x-ref="hiddenInput" 
           :value="value"
           {{ $attributes }}
           @if($required) required @endif
           id="{{ $id }}">

    <!-- Trigger Button -->
    <button type="button"
            x-ref="triggerButton"
            @click.stop="open = !open; if (open) $nextTick(() => positionDropdown())"
            class="w-full bg-slate-50 border-2 border-transparent hover:border-slate-200 rounded-lg py-3 px-4 flex items-center justify-between text-sm font-bold text-slate-800 transition-all outline-none focus:bg-white focus:border-slate-400">
        <span x-text="displayValue || '{{ $placeholder }}'"
              :class="displayValue ? 'text-slate-900' : 'text-slate-400'"></span>
        <svg class="size-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
        </svg>
    </button>

    <!-- Calendar Dropdown -->
    <template x-teleport="body">
        <div x-show="open"
             x-cloak
             @click.away="if (open) cancel()"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
             :style="dropdownStyle"
             class="w-80 flex flex-col bg-white border border-slate-200 shadow-xl rounded-2xl overflow-hidden z-[99999]">
            
            <!-- Calendar Header -->
            <div class="p-4 space-y-3">
                <div class="grid grid-cols-5 items-center gap-x-2">
                    <!-- Prev Button -->
                    <button type="button" 
                            @click="prevMonth()"
                            class="size-8 flex justify-center items-center text-slate-600 hover:bg-slate-50 hover:text-primary-600 rounded-full transition-colors">
                        <svg class="shrink-0 size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                    <!-- Month / Year Dropdowns -->
                    <div class="col-span-3 flex justify-center items-center gap-x-2">
                        <!-- Month Dropdown -->
                        <div class="relative" x-data="{ openM: false }">
                            <button type="button" @click.stop="openM = !openM" @click.away="openM = false"
                                    class="bg-slate-50 hover:bg-slate-100 border border-slate-100 rounded-lg py-1 px-2.5 pr-6 text-[11px] font-bold text-slate-700 transition-all outline-none flex items-center gap-1.5 relative">
                                <span x-text="months[currentMonth]"></span>
                                <svg class="size-3 text-slate-400 absolute right-2 transition-transform pointer-events-none" :class="openM ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="openM" 
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute top-full left-0 mt-1 max-h-48 overflow-y-auto w-32 bg-white border border-slate-100 rounded-xl shadow-lg py-1 z-[999999] custom-scrollbar"
                                 style="display: none;">
                                <template x-for="(monthName, index) in months" :key="index">
                                    <button type="button" 
                                            @click="currentMonth = index; openM = false; generateCalendar()"
                                            class="w-full px-3 py-1.5 text-left text-[11px] font-bold text-slate-600 hover:bg-slate-50 hover:text-primary-600 transition-colors"
                                            :class="currentMonth == index ? 'bg-primary-50 text-primary-600' : ''"
                                            x-text="monthName">
                                    </button>
                                </template>
                            </div>
                        </div>

                        <span class="text-slate-300">/</span>

                        <!-- Year Dropdown -->
                        <div class="relative" x-data="{ openY: false }">
                            <button type="button" @click.stop="openY = !openY" @click.away="openY = false"
                                    class="bg-slate-50 hover:bg-slate-100 border border-slate-100 rounded-lg py-1 px-2.5 pr-6 text-[11px] font-bold text-slate-700 transition-all outline-none flex items-center gap-1.5 relative">
                                <span x-text="currentYear"></span>
                                <svg class="size-3 text-slate-400 absolute right-2 transition-transform pointer-events-none" :class="openY ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="openY" 
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute top-full left-0 mt-1 max-h-48 overflow-y-auto w-24 bg-white border border-slate-100 rounded-xl shadow-lg py-1 z-[999999] custom-scrollbar"
                                 style="display: none;">
                                <template x-for="yr in years" :key="yr">
                                    <button type="button" 
                                            @click="currentYear = yr; openY = false; generateCalendar()"
                                            class="w-full px-3 py-1.5 text-left text-[11px] font-bold text-slate-600 hover:bg-slate-50 hover:text-primary-600 transition-colors"
                                            :class="currentYear == yr ? 'bg-primary-50 text-primary-600' : ''"
                                            x-text="yr">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Next Button -->
                    <button type="button" 
                            @click="nextMonth()"
                            class="size-8 flex justify-center items-center text-slate-600 hover:bg-slate-50 hover:text-primary-600 rounded-full transition-colors ml-auto">
                        <svg class="shrink-0 size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>

                <!-- Weeks -->
                <div class="grid grid-cols-7 text-center pb-1">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Lu</span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Ma</span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Me</span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Je</span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Ve</span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Sa</span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Di</span>
                </div>

                <!-- Days Grid -->
                <div class="grid grid-cols-7 gap-1">
                    <template x-for="(dayObj, index) in days" :key="index">
                        <button type="button"
                                @click="selectDate(dayObj)"
                                :disabled="dayObj.disabled"
                                :class="{
                                    'text-slate-300 pointer-events-none': !dayObj.isCurrentMonth,
                                    'text-slate-800 hover:bg-primary-50 hover:text-primary-600 hover:border-primary-200': dayObj.isCurrentMonth && selectedDay !== dayObj.day,
                                    'bg-primary-500 text-white font-black shadow-md shadow-primary-500/20': dayObj.isCurrentMonth && selectedDay === dayObj.day
                                }"
                                class="size-9 flex justify-center items-center text-xs font-bold rounded-xl border border-transparent transition-all outline-none"
                                x-text="dayObj.day">
                        </button>
                    </template>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="py-3 px-4 flex items-center justify-end gap-x-2 border-t border-slate-100 bg-slate-50/50">
                <button type="button"
                        @click="cancel()"
                        class="py-2 px-4 text-xs font-black uppercase tracking-widest text-slate-500 hover:bg-slate-100 hover:text-slate-700 rounded-lg transition-colors">
                    Annuler
                </button>
                <button type="button"
                        @click="apply()"
                        class="py-2 px-4 text-xs font-black uppercase tracking-widest bg-primary-500 text-white hover:bg-primary-600 rounded-lg shadow-sm shadow-primary-500/10 active:scale-95 transition-all">
                    Appliquer
                </button>
            </div>
        </div>
    </template>
</div>

<script>
if (typeof window.datePickerComponent === 'undefined') {
    window.datePickerComponent = function(config) {
        return {
            open: false,
            dropdownStyle: {},
            value: config.value || '',
            displayValue: '',
            currentYear: null,
            currentMonth: null,
            days: [],
            selectedDay: null,
            months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            years: [],

            init() {
                const initialDate = this.value ? new Date(this.value) : new Date();
                this.currentYear = initialDate.getFullYear();
                this.currentMonth = initialDate.getMonth();
                
                const startYear = this.currentYear - 5;
                this.years = [];
                for (let i = 0; i < 11; i++) {
                    this.years.push(startYear + i);
                }
                
                if (this.value) {
                    this.selectedDay = new Date(this.value).getDate();
                    this.updateDisplayValue();
                }
                
                this.generateCalendar();
                
                this.$watch('currentMonth', () => this.generateCalendar());
                this.$watch('currentYear', () => {
                    const yr = parseInt(this.currentYear);
                    if (!this.years.includes(yr)) {
                        const startYear = yr - 5;
                        this.years = [];
                        for (let i = 0; i < 11; i++) {
                            this.years.push(startYear + i);
                        }
                    }
                    this.generateCalendar();
                });

                window.addEventListener('resize', () => { if (this.open) this.positionDropdown(); });
                window.addEventListener('scroll', () => { if (this.open) this.positionDropdown(); }, true);
            },

            positionDropdown() {
                if (!this.$refs.triggerButton) return;
                const rect = this.$refs.triggerButton.getBoundingClientRect();
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
                
                const dropdownWidth = 320;
                const dropdownHeight = 380;
                
                let left = rect.right + scrollLeft + 8;
                if (rect.right + 8 + dropdownWidth > window.innerWidth) {
                    left = rect.left + scrollLeft - dropdownWidth - 8;
                }
                
                let top = rect.top + scrollTop - 166;
                
                this.dropdownStyle = {
                    position: 'absolute',
                    top: `${top}px`,
                    left: `${left}px`
                };
            },

            generateCalendar() {
                const yr = parseInt(this.currentYear);
                const mo = parseInt(this.currentMonth);
                
                // Get first day of current month (0 is Sunday)
                let firstDayIndex = new Date(yr, mo, 1).getDay();
                // Monday-based (0: Mon, 1: Tue ... 6: Sun)
                let startPadding = firstDayIndex === 0 ? 6 : firstDayIndex - 1;
                
                const totalDays = new Date(yr, mo + 1, 0).getDate();
                const prevMonthTotalDays = new Date(yr, mo, 0).getDate();
                
                let days = [];
                
                // Previous Month padding days
                for (let i = startPadding - 1; i >= 0; i--) {
                    days.push({
                        day: prevMonthTotalDays - i,
                        isCurrentMonth: false,
                        disabled: true
                    });
                }
                
                // Current Month days
                for (let i = 1; i <= totalDays; i++) {
                    days.push({
                        day: i,
                        isCurrentMonth: true,
                        disabled: false
                    });
                }
                
                // Next Month padding days to complete standard 6-week view (42 days)
                const nextMonthPadding = 42 - days.length;
                for (let i = 1; i <= nextMonthPadding; i++) {
                    days.push({
                        day: i,
                        isCurrentMonth: false,
                        disabled: true
                    });
                }
                
                this.days = days;
            },

            selectDate(dayObj) {
                if (dayObj.disabled) return;
                this.selectedDay = dayObj.day;
            },

            prevMonth() {
                let mo = parseInt(this.currentMonth);
                let yr = parseInt(this.currentYear);
                if (mo === 0) {
                    this.currentMonth = 11;
                    this.currentYear = yr - 1;
                } else {
                    this.currentMonth = mo - 1;
                }
            },

            nextMonth() {
                let mo = parseInt(this.currentMonth);
                let yr = parseInt(this.currentYear);
                if (mo === 11) {
                    this.currentMonth = 0;
                    this.currentYear = yr + 1;
                } else {
                    this.currentMonth = mo + 1;
                }
            },

            apply() {
                if (this.selectedDay) {
                    const yr = parseInt(this.currentYear);
                    const mo = parseInt(this.currentMonth);
                    const date = new Date(yr, mo, this.selectedDay);
                    
                    const yyyy = date.getFullYear();
                    const mm = String(date.getMonth() + 1).padStart(2, '0');
                    const dd = String(date.getDate()).padStart(2, '0');
                    
                    this.value = `${yyyy}-${mm}-${dd}`;
                    this.updateDisplayValue();
                    
                    this.$refs.hiddenInput.value = this.value;
                    this.$refs.hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                    this.$refs.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
                }
                this.open = false;
            },

            cancel() {
                if (this.value) {
                    const date = new Date(this.value);
                    this.currentYear = date.getFullYear();
                    this.currentMonth = date.getMonth();
                    this.selectedDay = date.getDate();
                } else {
                    this.selectedDay = null;
                }
                this.open = false;
            },

            updateDisplayValue() {
                if (!this.value) {
                    this.displayValue = '';
                    return;
                }
                const date = new Date(this.value);
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                this.displayValue = `${day}/${month}/${year}`;
            }
        };
    };
}
</script>
