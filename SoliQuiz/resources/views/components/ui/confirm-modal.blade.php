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
    class="fixed inset-0 z-[110] overflow-y-auto px-4 py-6 sm:px-0"
    x-cloak
>
    <!-- Backdrop with Immersive Blur -->
    <div x-show="show" class="fixed inset-0 transform transition-all" @click="show = false"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    >
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md"></div>
    </div>

    <div x-show="show"
         class="relative z-10 mb-6 bg-white rounded-[3rem] overflow-hidden shadow-2xl transform transition-all sm:w-full sm:max-w-lg sm:mx-auto border border-white/40 p-12 text-center"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 translate-y-8 scale-95">
        
        <!-- Premium Icon Container -->
        <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-[2rem] mb-8 relative"
             :class="type === 'danger' ? 'bg-rose-50 text-rose-500 shadow-xl shadow-rose-500/10' : 'bg-amber-50 text-amber-500 shadow-xl shadow-amber-500/10'">
            <div class="absolute inset-0 rounded-[2rem] border-4 border-white opacity-50"></div>
            <svg x-show="type === 'danger'" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.34c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            <svg x-show="type === 'warning'" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
        </div>

        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.4em] mb-2 italic">Action Requise</p>
        <h3 class="text-3xl font-heading font-black text-slate-900 tracking-tight mb-4 uppercase italic" x-text="title"></h3>
        <p class="text-slate-500 leading-relaxed mb-12 font-medium" x-text="message"></p>

        <div class="flex flex-col sm:flex-row-reverse gap-4">
            <button @click="confirm" 
                    type="button" 
                    class="flex-1 py-5 px-8 rounded-2xl font-black uppercase tracking-widest text-[11px] transition-all active:scale-95 shadow-2xl italic group"
                    :class="type === 'danger' ? 'bg-rose-600 text-white hover:bg-rose-700 shadow-rose-600/20' : 'bg-primary-500 text-white hover:bg-primary-600 shadow-primary-500/20'"
                    x-text="confirmText">
            </button>
            <button @click="show = false" 
                    type="button" 
                    class="flex-1 py-5 px-8 rounded-2xl font-black uppercase tracking-widest text-[11px] bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all active:scale-95 italic" 
                    x-text="cancelText">
            </button>
        </div>
    </div>
</div>

