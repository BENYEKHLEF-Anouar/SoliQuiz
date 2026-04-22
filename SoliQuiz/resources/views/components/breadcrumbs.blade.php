<nav class="flex items-center gap-4 text-[10px] font-black uppercase tracking-widest text-slate-400"
     x-data="{
        labels: {
            'dashboard': 'Tableau de Bord',
            'users': 'Gestion Utilisateurs',
            'pedagogie': 'Structure Pédagogique',
            'qcms': 'Supervision QCM',
            'profile': 'Mon Profil'
        }
     }">
    <div class="flex items-center gap-4 group cursor-pointer" @click="$store.router.navigate('dashboard')">
        <x-lucide-icon name="home" size="3.5" class="group-hover:text-primary-500 transition-colors" />
        <span>SoliQuiz</span>
    </div>
    
    <template x-if="$store.router.page !== 'dashboard'">
        <div class="flex items-center gap-4 animate-in slide-in-from-left-2 duration-300">
            <x-lucide-icon name="chevron-right" size="3" class="text-slate-200" />
            <span class="text-slate-900 bg-slate-50 px-3 py-1 rounded-lg border border-slate-100 shadow-sm" x-text="labels[$store.router.page] || $store.router.page"></span>
        </div>
    </template>
</nav>
