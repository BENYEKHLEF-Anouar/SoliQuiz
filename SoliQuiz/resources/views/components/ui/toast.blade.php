<div
    x-data="{
        messages: [],
        remove(id) {
            this.messages = this.messages.filter(m => m.id !== id)
        },
        add(message, type = 'success') {
            const id = Date.now()
            this.messages.push({ id, message, type })
            setTimeout(() => this.remove(id), 5000)
        }
    }"
    x-on:toast.window="add($event.detail.message, $event.detail.type)"
    x-init="
        @if(session('success')) add('{{ addslashes(session('success')) }}', 'success'); @endif
        @if(!request()->routeIs('login'))
            @if(session('error')) add('{{ addslashes(session('error')) }}', 'error'); @endif
            @if(session('info')) add('{{ addslashes(session('info')) }}', 'info'); @endif
            @if($errors->any()) add('{{ addslashes($errors->first()) }}', 'error'); @endif
        @endif
    "
    class="fixed bottom-10 right-10 z-[200] flex flex-col gap-4 pointer-events-none"
>
    <template x-for="msg in messages" :key="msg.id">
        <div
            x-show="true"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-12 scale-90"
            x-transition:enter-end="opacity-100 translate-x-0 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0 scale-100"
            x-transition:leave-end="opacity-0 translate-x-12 scale-90"
            class="pointer-events-auto min-w-[340px] max-w-md p-5 rounded-[2rem] shadow-premium bg-white/80 backdrop-blur-xl border border-white/40 flex items-center gap-5 transition-all group"
        >
            <!-- Icon Mapping -->
            <div class="flex-shrink-0 size-14 rounded-2xl flex items-center justify-center relative overflow-hidden"
                 :class="{ 'bg-emerald-50 text-emerald-500': msg.type === 'success',
                    'bg-rose-50 text-rose-500': msg.type === 'error',
                    'bg-sky-50 text-sky-500': msg.type === 'info',
                 }">
                <div class="absolute inset-0 border-2 border-white opacity-40 rounded-2xl"></div>
                <!-- Success -->
                <svg x-show="msg.type === 'success'" class="size-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                <!-- Error -->
                <svg x-show="msg.type === 'error'" class="size-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                <!-- Info -->
                <svg x-show="msg.type === 'info'" class="size-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>

            <div class="flex-grow">
                <p class="text-[8px] font-black uppercase tracking-[0.4em] mb-1 opacity-40" 
                   :class="{ 'text-emerald-600': msg.type === 'success',
                       'text-rose-600': msg.type === 'error',
                       'text-sky-600': msg.type === 'info',
                   }"
                   x-text="msg.type === 'success' ? 'Confirmation' : (msg.type === 'error' ? 'Anomalie' : 'Notification')"></p>
                <p class="text-xs font-black text-slate-900 uppercase leading-tight" x-text="msg.message"></p>
            </div>

            <button @click="remove(msg.id)" class="size-8 rounded-xl hover:bg-slate-50 flex items-center justify-center text-slate-300 hover:text-slate-900 transition-all active:scale-90">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </template>
</div>

