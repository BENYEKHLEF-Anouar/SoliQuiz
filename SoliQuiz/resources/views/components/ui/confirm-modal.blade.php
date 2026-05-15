<div
    x-data="{
        show: false,
        title: '',
        message: '',
        confirmAction: null,
        cancelText: 'Annuler',
        confirmText: 'Confirmer',
        type: 'danger',
        
        open(detail) {
            this.title = detail.title || 'Confirmation';
            this.message = detail.message || 'Êtes-vous sûr de vouloir continuer ?';
            this.confirmText = detail.confirmText || 'Confirmer';
            this.cancelText = detail.cancelText || 'Annuler';
            this.type = detail.type || 'danger';
            this.confirmAction = detail.onConfirm;
            this.show = true;
        },
        
        confirm() {
            if (typeof this.confirmAction === 'string') {
                const form = document.getElementById(this.confirmAction);
                if (form) form.submit();
            } else if (typeof this.confirmAction === 'function') {
                this.confirmAction();
            }
            this.show = false;
        }
    }"
    x-init="$watch('show', value => {
        if (value) {
            document.body.classList.add('overflow-y-hidden');
        } else {
            document.body.classList.remove('overflow-y-hidden');
        }
    })"
    x-on:confirm.window="open($event.detail)"
    x-show="show"
    class="fixed inset-0 z-[10000] flex items-center justify-center p-4 sm:p-6 md:p-10"
    x-cloak
>
    <!-- Backdrop without Blur -->
    <div x-show="show" class="fixed inset-0 transform transition-all" @click="show = false"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    >
        <div class="absolute inset-0 bg-slate-900/40"></div>
    </div>

    <div x-show="show"
         class="relative z-10 bg-white rounded-[2.5rem] shadow-[0_25px_60px_rgba(0,0,0,0.12)] transform transition-all w-full max-w-lg border border-slate-100 overflow-hidden"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        
        <div class="flex p-10 gap-8">
            <!-- Creative Left Icon Column -->
            <div class="flex-shrink-0">
                <div class="size-16 rounded-[1.5rem] flex items-center justify-center transition-colors duration-500 shadow-sm"
                     :class="type === 'danger' ? 'bg-rose-50 text-rose-500' : 'bg-amber-50 text-amber-500'">
                    <svg x-show="type === 'danger'" class="size-8" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    <svg x-show="type === 'warning'" class="size-8" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
            </div>

            <!-- Content Column -->
            <div class="flex-1 text-left">
                <div class="mb-5">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight leading-tight mb-1" x-text="title"></h3>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest italic" :class="type === 'danger' ? 'text-rose-400' : 'text-amber-400'">Attention nécessaire</p>
                </div>
                <p class="text-slate-500 text-base leading-relaxed font-medium mb-0" x-text="message"></p>
            </div>
        </div>

        <!-- Minimal Footer with subtle divider -->
        <div class="bg-slate-50/50 px-10 py-8 flex items-center justify-end gap-4 border-t border-slate-100">
            <button @click="show = false" 
                    type="button" 
                    class="px-6 py-3 rounded-2xl text-xs font-bold text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-all">
                <span x-text="cancelText"></span>
            </button>
            <button @click="confirm" 
                    type="button" 
                    class="px-8 py-3 rounded-2xl text-xs font-black uppercase tracking-widest transition-all active:scale-95"
                    :class="type === 'danger' ? 'bg-rose-500 text-white hover:bg-rose-600' : 'bg-slate-900 text-white hover:bg-slate-800'"
                    x-text="confirmText">
            </button>
        </div>
    </div>
</div>

