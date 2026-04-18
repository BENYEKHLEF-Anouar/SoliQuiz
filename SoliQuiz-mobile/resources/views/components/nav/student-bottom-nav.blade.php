<!-- Student Bottom Navigation Component -->
<nav class="fixed bottom-0 w-full max-w-[430px] bg-white border-t border-slate-200 z-50">
    <div class="flex justify-around items-center h-[72px] px-4 pb-safe">
        <a href="{{ route('student.dashboard') }}"
            class="flex flex-col items-center justify-center {{ request()->routeIs('student.dashboard') ? 'text-primary-500' : 'text-slate-400' }} gap-1.5 transition-colors">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="{{ request()->routeIs('student.dashboard') ? '2.5' : '2' }}">
                <path
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-[10px] font-bold uppercase tracking-widest">Home</span>
        </a>
        <!-- <a href="#" class="flex flex-col items-center justify-center text-slate-400 gap-1.5 transition-colors">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <span class="text-[10px] font-bold uppercase tracking-widest">Library</span>
        </a> -->
        <a href="{{ route('student.history') }}"
            class="flex flex-col items-center justify-center {{ request()->routeIs('student.history') ? 'text-primary-500' : 'text-slate-400' }} gap-1.5 transition-colors">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="{{ request()->routeIs('student.history') ? '2.5' : '2' }}">
                <path
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span class="text-[10px] font-bold uppercase tracking-widest">Stats</span>
        </a>
        <a href="{{ route('student.profile') }}"
            class="flex flex-col items-center justify-center {{ request()->routeIs('student.profile') ? 'text-primary-500' : 'text-slate-400' }} gap-1.5 transition-colors">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="{{ request()->routeIs('student.profile') ? '2.5' : '2' }}">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="text-[10px] font-bold uppercase tracking-widest">Profil</span>
        </a>
    </div>
</nav>