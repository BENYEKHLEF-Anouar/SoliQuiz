@php
    $user = Auth::user();
@endphp

<header class="h-[70px] bg-white/80 backdrop-blur-md border-b border-slate-100 px-8 flex items-center justify-between z-40 shrink-0 sticky top-0 w-full">
    <!-- Page Title & Context -->
    <div class="flex items-center gap-4 min-w-0">
        <div class="size-2 rounded-full bg-primary-500 shadow-[0_0_10px_rgba(23,162,184,0.4)]"></div>
        <h2 class="text-xs font-black text-slate-900 uppercase italic tracking-[0.2em] truncate font-heading">
            @yield('page-title', 'Tableau de Bord')
        </h2>
    </div>

    <!-- Right Side Actions -->
    <div class="flex items-center gap-6">
        <!-- Date Display -->
        <div class="hidden md:flex flex-col text-right">
            <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest italic leading-none mb-1">Session Active</span>
            <span class="text-[11px] font-bold text-slate-600">{{ now()->translatedFormat('d F Y') }}</span>
        </div>

        <div class="h-8 w-px bg-slate-100 hidden md:block"></div>

        <!-- Quick Actions -->
        <div class="flex items-center gap-3">
            <button class="p-2.5 rounded-xl bg-slate-50 text-slate-400 hover:bg-primary-50 hover:text-primary-600 transition-all active:scale-95 border border-slate-100/50">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </button>
            <button class="hidden sm:flex py-2.5 px-6 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-800 transition-all active:scale-95 shadow-sm">
                Aide & Support
            </button>
        </div>
    </div>
</header>
