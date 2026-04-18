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

        <a href="{{ route('formateur.class-notes') }}" class="flex flex-col items-center justify-center {{ request()->routeIs('formateur.class-notes') ? 'text-primary-400' : 'text-slate-500' }} gap-1.5 transition-all duration-300">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('formateur.class-notes') ? '2.5' : '2' }}">
                <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="text-[10px] font-black uppercase tracking-[0.2em]">Classe</span>
        </a>

        <a href="{{ route('formateur.profile') }}" class="flex flex-col items-center justify-center {{ request()->routeIs('formateur.profile') ? 'text-primary-400' : 'text-slate-500' }} gap-1.5 transition-all duration-300">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('formateur.profile') ? '2.5' : '2' }}">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="text-[10px] font-black uppercase tracking-[0.2em]">Profil</span>
        </a>
    </div>
</nav>