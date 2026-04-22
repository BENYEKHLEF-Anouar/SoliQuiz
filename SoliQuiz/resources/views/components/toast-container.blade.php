<div class="fixed bottom-8 right-8 z-[100] flex flex-col gap-4 pointer-events-none" 
     x-data="{ list: $store.toasts.list }">
    <template x-for="toast in $store.toasts.list" :key="toast.id">
        <div class="pointer-events-auto min-w-[320px] bg-white border border-slate-100 shadow-2xl rounded-[1.5rem] p-4 flex items-center gap-4 reveal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-12"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-12">
            
            <div class="size-10 rounded-xl flex items-center justify-center shrink-0"
                 :class="{
                    'bg-emerald-50 text-emerald-500': toast.type === 'success',
                    'bg-rose-50 text-rose-500': toast.type === 'error',
                    'bg-blue-50 text-blue-500': toast.type === 'info'
                 }">
                <template x-if="toast.type === 'success'"><x-lucide-icon name="check-circle" size="5" /></template>
                <template x-if="toast.type === 'error'"><x-lucide-icon name="alert-circle" size="5" /></template>
                <template x-if="toast.type === 'info'"><x-lucide-icon name="info" size="5" /></template>
            </div>

            <div class="flex-1">
                <p class="text-[11px] font-black text-slate-900 leading-tight" x-text="toast.message"></p>
                <div class="mt-3 h-1 bg-slate-50 rounded-full overflow-hidden">
                    <div class="h-full transition-all duration-100"
                         :class="{
                            'bg-emerald-500': toast.type === 'success',
                            'bg-rose-500': toast.type === 'error',
                            'bg-blue-500': toast.type === 'info'
                         }"
                         :style="`width: ${toast.progress}%` transition: 'width 0.1s linear'"></div>
                </div>
            </div>

            <button @click="$store.toasts.remove(toast.id)" class="text-slate-300 hover:text-slate-900 transition-colors">
                <x-lucide-icon name="x" size="4" />
            </button>
        </div>
    </template>
</div>
