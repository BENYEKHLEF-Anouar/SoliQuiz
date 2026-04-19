<!-- Student Header Component -->
<header class="bg-white/80 backdrop-blur-md px-5 pt-safe-top pb-4 border-b border-slate-100 shadow-[0_1px_3px_rgba(0,0,0,0.02)] shrink-0 sticky top-0 z-40">
    <div class="h-[44px] hidden ios:block"></div>
    <div class="flex justify-between items-center mt-2">
        <div class="flex items-center gap-3">
            <a class="flex items-center gap-2 group outline-none" href="{{ route('student.dashboard') }}" aria-label="SoliQuiz Accueil">
                <div class="size-9 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/20 transition-transform group-hover:scale-110 active:scale-95">
                    <svg class="text-white size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <path d="m9 15 2 2 4-4" />
                    </svg>
                </div>
                <div class="flex flex-col leading-none">
                    <span class="text-xl font-heading font-bold text-slate-900 tracking-tight">Soli<span class="text-primary-500">Quiz</span></span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-0.5">Apprenant</span>
                </div>
            </a>
        </div>

        <!-- Profile Dropdown -->
        <div class="hs-dropdown relative inline-flex">
            <button id="hs-dropdown-profile" type="button" class="hs-dropdown-toggle w-10 h-10 rounded-full border border-slate-200 bg-white shadow-sm overflow-hidden active:scale-95 transition-transform focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&auto=format&fit=facearea&facepad=2&w=300&h=300&q=80" alt="Avatar">
            </button>
            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-48 bg-white shadow-xl rounded-2xl p-2 mt-2 border border-slate-100 z-50" role="menu">
                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-xl text-sm text-slate-800 hover:bg-slate-50 font-medium" href="{{ route('student.profile') }}">Mon Profil</a>
                <button type="button" @click="Alpine.store('config').logout()" class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-xl text-sm text-semantic-error hover:bg-semantic-error/10 font-bold">Déconnexion</button>
            </div>
        </div>
    </div>
</header>