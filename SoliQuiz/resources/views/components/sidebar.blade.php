<aside class="flex-shrink-0 bg-[#0A0F1C] border-r border-white/5 transition-all duration-300 z-30"
       :class="sidebarOpen ? 'w-72' : 'w-20'">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="h-20 flex items-center px-6">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="size-9 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/20 shrink-0">
                    <x-lucide-icon name="graduation-cap" class="text-white" size="5" />
                </div>
                <span class="text-xl font-heading font-black text-white tracking-tighter" x-show="sidebarOpen" x-transition>Soli<span class="text-primary-500">Quiz</span></span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 space-y-1 py-4 overflow-y-auto custom-scrollbar">
            <!-- Dashboard (Shared) -->
            <button @click="$store.router.navigate('dashboard')"
                    class="w-full flex items-center gap-4 px-4 py-3 rounded-2xl transition-all group"
                    :class="$store.router.page === 'dashboard' ? 'bg-primary-500 text-white shadow-lg shadow-primary-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white'">
                <x-lucide-icon name="layout-dashboard" size="5" />
                <span class="text-[11px] font-black uppercase tracking-widest " x-show="sidebarOpen">Tableau de Bord</span>
            </button>

            @role('admin')
            <!-- Gestion Utilisateurs -->
            <button @click="$store.router.navigate('users')"
                    class="w-full flex items-center gap-4 px-4 py-3 rounded-2xl transition-all group"
                    :class="$store.router.page === 'users' ? 'bg-primary-500 text-white shadow-lg shadow-primary-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white'">
                <x-lucide-icon name="users" size="5" />
                <span class="text-[11px] font-black uppercase tracking-widest " x-show="sidebarOpen">Utilisateurs</span>
            </button>

            <!-- Structure Pédagogique -->
            <button @click="$store.router.navigate('pedagogie')"
                    class="w-full flex items-center gap-4 px-4 py-3 rounded-2xl transition-all group"
                    :class="$store.router.page === 'pedagogie' ? 'bg-primary-500 text-white shadow-lg shadow-primary-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white'">
                <x-lucide-icon name="book-open" size="5" />
                <span class="text-[11px] font-black uppercase tracking-widest " x-show="sidebarOpen">Pédagogie</span>
            </button>

            <!-- Supervision QCM -->
            <button @click="$store.router.navigate('qcms')"
                    class="w-full flex items-center gap-4 px-4 py-3 rounded-2xl transition-all group"
                    :class="$store.router.page === 'qcms' ? 'bg-primary-500 text-white shadow-lg shadow-primary-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white'">
                <x-lucide-icon name="file-check" size="5" />
                <span class="text-[11px] font-black uppercase tracking-widest " x-show="sidebarOpen">Supervision QCM</span>
            </button>
            @endrole

            @role('formateur')
            <!-- QCM Studio -->
            <button @click="$store.router.navigate('studio')"
                    class="w-full flex items-center gap-4 px-4 py-3 rounded-2xl transition-all group"
                    :class="$store.router.page === 'studio' ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white'">
                <x-lucide-icon name="pen-tool" size="5" />
                <span class="text-[11px] font-black uppercase tracking-widest " x-show="sidebarOpen">QCM Studio</span>
            </button>

            <!-- Résultats -->
            <button @click="$store.router.navigate('results')"
                    class="w-full flex items-center gap-4 px-4 py-3 rounded-2xl transition-all group"
                    :class="$store.router.page === 'results' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white'">
                <x-lucide-icon name="bar-chart-3" size="5" />
                <span class="text-[11px] font-black uppercase tracking-widest " x-show="sidebarOpen">Résultats</span>
            </button>
            @endrole
        </nav>

        <!-- Profile -->
        <div class="p-4 border-t border-white/5">
            <button @click="$store.router.navigate('profile')"
                    class="w-full flex items-center gap-4 px-4 py-3 rounded-2xl text-slate-400 hover:bg-white/5 hover:text-white transition-all group"
                    :class="$store.router.page === 'profile' ? 'bg-white/5 text-white' : ''">
                <div class="size-8 rounded-full bg-slate-800 border border-white/10 flex items-center justify-center overflow-hidden shrink-0">
                    <x-lucide-icon name="user" size="4" />
                </div>
                <div class="flex-1 text-left overflow-hidden" x-show="sidebarOpen">
                    <p class="text-[10px] font-black text-white truncate">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</p>
                    <p class="text-[8px] font-bold text-slate-500 uppercase tracking-widest  truncate">{{ Auth::user()->type_profil }}</p>
                </div>
            </button>
        </div>
    </div>
</aside>
