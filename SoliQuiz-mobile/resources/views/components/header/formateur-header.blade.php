<!-- Formateur Header Component -->
<header class="bg-slate-900 px-5 pt-safe-top pb-4 border-b border-white/10 shadow-sm shrink-0 sticky top-0 z-40">
    <div class="h-[44px] hidden ios:block"></div>
    <div class="flex justify-between items-center mt-2">
        <div class="flex items-center gap-3">
            <a class="flex items-center gap-2 group outline-none" href="#" aria-label="SoliQuiz Accueil">
                <div class="size-9 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/20 transition-transform group-hover:scale-110">
                    <svg class="text-white size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <path d="m9 15 2 2 4-4" />
                    </svg>
                </div>
                <div class="flex flex-col leading-none">
                    <span class="text-xl font-heading font-bold text-white tracking-tight">Soli<span class="text-primary-400">Quiz</span></span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-0.5">Formateur</span>
                </div>
            </a>
        </div>

        <div class="hs-dropdown relative inline-flex">
            <button id="hs-dropdown-avatar" class="hs-dropdown-toggle size-[38px] rounded-full ring-2 ring-primary-500/20 border-2 border-slate-900 overflow-hidden active:scale-95 transition-transform">
                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="Avatar">
            </button>
            <div class="hs-dropdown-menu transition-[opacity,margin] hs-dropdown-open:opacity-100 opacity-0 hidden min-w-48 bg-white shadow-xl rounded-2xl p-2 mt-2 border border-slate-100" role="menu">
                <a class="flex items-center gap-x-3 py-2 px-3 rounded-xl text-sm text-slate-700 hover:bg-slate-50 font-medium" href="{{ route('formateur.profile') }}">Mon Profil</a>
                <div class="my-1 border-t border-slate-100"></div>
                <a class="flex items-center gap-x-3 py-2 px-3 rounded-xl text-sm text-semantic-error hover:bg-semantic-error/10 font-bold" href="{{ route('landing') }}">Déconnexion</a>
            </div>
        </div>
    </div>
</header>