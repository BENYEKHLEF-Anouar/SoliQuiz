<!-- Formateur Bottom Navigation Component -->
<nav class="fixed bottom-0 w-full max-w-[430px] bg-slate-950 border-t border-white/5 z-50">
    <div class="flex justify-around items-center h-[72px] px-4 pb-safe">
        <a href="{{ route('formateur.qcms') }}" class="flex flex-col items-center justify-center {{ request()->routeIs('formateur.qcms') || request()->routeIs('formateur.qcm-results') ? 'text-primary-400' : 'text-slate-500' }} gap-1.5 transition-all duration-300">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('formateur.qcms') || request()->routeIs('formateur.qcm-results') ? '2.5' : '2' }}">
                <rect x="3" y="3" width="7" height="9" />
                <rect x="14" y="3" width="7" height="5" />
                <rect x="14" y="12" width="7" height="9" />
                <rect x="3" y="16" width="7" height="5" />
            </svg>
            <span class="text-[10px] font-black uppercase tracking-[0.2em]">QCMs</span>
        </a>

        <a href="{{ route('formateur.results') }}" class="flex flex-col items-center justify-center {{ request()->routeIs('formateur.results') ? 'text-primary-400' : 'text-slate-500' }} gap-1.5 transition-all duration-300">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('formateur.results') ? '2.5' : '2' }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2" />
            </svg>
            <span class="text-[10px] font-black uppercase tracking-[0.2em]">Résultats</span>
        </a>

        <a href="{{ route('formateur.structure') }}" class="flex flex-col items-center justify-center {{ request()->routeIs('formateur.structure') ? 'text-primary-400' : 'text-slate-500' }} gap-1.5 transition-all duration-300">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('formateur.structure') ? '2.5' : '2' }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <span class="text-[10px] font-black uppercase tracking-[0.2em]">Structure</span>
        </a>

        <a href="{{ route('formateur.profile') }}" class="flex flex-col items-center justify-center {{ request()->routeIs('formateur.profile') ? 'text-primary-400' : 'text-slate-500' }} gap-1.5 transition-all duration-300">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('formateur.profile') ? '2.5' : '2' }}">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="text-[10px] font-black uppercase tracking-[0.2em]">Profil</span>
        </a>
    </div>
</nav>