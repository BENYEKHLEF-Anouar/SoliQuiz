<header class="h-20 bg-white border-b border-slate-100 flex items-center justify-between px-8 shrink-0">
    <div class="flex items-center gap-6">
        <button @click="sidebarOpen = !sidebarOpen" class="text-slate-400 hover:text-slate-900 transition-colors p-2 hover:bg-slate-50 rounded-xl">
            <x-lucide-icon name="menu" size="5" />
        </button>
        <x-breadcrumbs />
    </div>

    <div class="flex items-center gap-6">
        <!-- Search Quick Link -->
        <button @click="$store.router.navigate('users')" 
                class="hidden md:flex items-center gap-3 bg-slate-50 border border-slate-100 px-4 py-2 rounded-xl text-slate-400 hover:border-primary-200 transition-all group cursor-pointer">
            <x-lucide-icon name="search" size="4" class="group-hover:text-primary-500 transition-colors" />
            <span class="text-[10px] font-black uppercase tracking-widest ">Recherche rapide...</span>
        </button>

        <div class="flex items-center gap-3 pl-6 border-l border-slate-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all group">
                    <x-lucide-icon name="log-out" size="5" class="group-hover:translate-x-1 transition-transform" />
                </button>
            </form>
        </div>
    </div>
</header>
