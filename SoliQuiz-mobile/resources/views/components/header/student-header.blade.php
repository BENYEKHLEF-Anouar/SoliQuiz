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
        <div class="relative" x-data="{ open: false }" x-init="$store.config.studentProfile || $store.config.fetchStudentProfile()">
            <button @click="open = !open" class="size-[38px] rounded-full ring-2 ring-primary-500/20 overflow-hidden active:scale-95 transition-transform bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-primary-500/30">
                <template x-if="$store.config.studentProfile">
                    <span x-text="$store.config.getStudentInitials()"></span>
                </template>
                <template x-if="!$store.config.studentProfile">
                    <svg class="size-4 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </template>
            </button>
            <div x-show="open"
                 x-cloak
                 style="display: none;"
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 top-full z-50 min-w-48 bg-white shadow-xl rounded-2xl p-2 mt-2 border border-slate-100"
                 role="menu">
                <div class="px-3 py-2 border-b border-slate-100 mb-1">
                    <p class="text-sm font-bold text-slate-900" x-text="$store.config.getStudentFullName()"></p>
                    <p class="text-xs text-slate-500" x-text="$store.config.getStudentEmail()"></p>
                </div>
                <a class="flex items-center gap-x-3 py-2 px-3 rounded-xl text-sm text-slate-700 hover:bg-slate-50 font-medium" href="{{ route('student.profile') }}">Mon Profil</a>
                <div class="my-1 border-t border-slate-100"></div>
                <button @click="$store.config.logout()" class="w-full flex items-center gap-x-3 py-2 px-3 rounded-xl text-sm text-semantic-error hover:bg-semantic-error/10 font-bold text-left">Déconnexion</button>
            </div>
        </div>
    </div>
</header>