@php
    $user = Auth::user();
    $role = $user->type_profil;

    $links = [
        'admin' => [
            ['name' => 'Tableau de Bord', 'route' => 'admin.dashboard', 'icon' => 'M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z'],
            ['name' => 'Utilisateurs', 'route' => 'admin.utilisateurs', 'icon' => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2 M9 7a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm14 14v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75'],
            ['name' => 'Banque de QCM', 'route' => 'admin.qcms', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
            ['name' => 'Ingénierie Péd.', 'route' => 'admin.pedagogie', 'icon' => 'M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5'],
            ['name' => 'Cohortes', 'route' => 'admin.classes', 'icon' => 'M19 11H5m14 0a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-6a2 2 0 0 1 2-2m14 0V9a2 2 0 0 0-2-2M5 11V9a2 2 0 0 1 2-2m0 0V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2M7 7h10'],
        ],
        'formateur' => [
            ['name' => 'Supervision', 'route' => 'formateur.dashboard', 'icon' => 'M12 20v-6M6 20V10M18 20V4'],
            ['name' => 'Ingénierie Péd.', 'route' => 'formateur.pedagogie', 'icon' => 'M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5'],
            ['name' => 'Bibliothèque', 'route' => 'formateur.bibliotheque', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
            ['name' => 'Ma Classe', 'route' => 'formateur.resultats', 'icon' => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2 M9 7a4 4 0 1 0 0-8 4 4 0 0 0 0 8z'],
        ],
        'etudiant' => [
            ['name' => 'Dashboard', 'route' => 'student.dashboard', 'icon' => 'M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z'],
            ['name' => 'Bibliothèque', 'route' => 'student.bibliotheque', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
        ]
    ];

    $currentLinks = $links[$role] ?? [];
@endphp

<!-- Mobile Header -->
<div class="lg:hidden glass sticky top-0 z-[60] px-6 h-16 flex justify-between items-center shadow-sm">
    <div class="flex items-center gap-2">
        <div class="size-8 bg-primary-500 rounded-lg flex items-center justify-center shadow-lg shadow-primary-500/20">
            <svg class="text-white size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <polyline points="14 2 14 8 20 8" />
                <path d="m9 15 2 2 4-4" />
            </svg>
        </div>
        <span class="font-heading font-bold text-lg text-slate-900">Soli<span class="text-primary-500">Quiz</span></span>
    </div>
    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 transition-colors">
        <svg class="size-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" />
        </svg>
    </button>
</div>

<!-- Backdrop -->
<div x-show="sidebarOpen" 
    x-transition:enter="transition duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[70] lg:hidden" @click="sidebarOpen = false"
    style="display: none;"></div>

<!-- Sidebar Container -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed lg:sticky inset-y-0 left-0 w-80 p-6 transition-transform duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] z-[80] h-screen">
    
    <div class="h-full bg-white rounded-[32px] border border-slate-100 shadow-premium flex flex-col overflow-hidden relative">
        <!-- Logo Section -->
        <div class="p-8">
            <a class="flex items-center gap-3 transition-transform hover:scale-105 active:scale-95 outline-none" href="{{ route('dashboard') }}">
                <div class="size-11 bg-primary-500 rounded-[14px] flex items-center justify-center shadow-xl shadow-primary-500/25">
                    <svg class="text-white size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <path d="m9 15 2 2 4-4" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-2xl font-heading font-black text-slate-900 tracking-tight leading-none">Soli<span class="text-primary-500">Quiz</span></span>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em] mt-1">LMS Companion</span>
                </div>
            </a>
        </div>

        <!-- Navigation Section -->
        <nav class="flex-1 px-4 space-y-1 overflow-y-auto custom-scrollbar">
            <div class="px-4 py-3 mb-2 flex items-center justify-between">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Menu Principal</span>
            </div>

            @foreach($currentLinks as $link)
                <a href="{{ route($link['route']) }}"
                    class="group relative flex items-center gap-3.5 px-5 py-4 rounded-2xl transition-all duration-300 {{ Route::is($link['route'] . '*') ? 'bg-primary-50 text-primary-600' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                    @if(Route::is($link['route'] . '*'))
                        <div class="absolute left-0 w-1 h-6 bg-primary-500 rounded-full"></div>
                    @endif
                    <svg class="size-5 shrink-0 transition-transform {{ Route::is($link['route'] . '*') ? 'text-primary-500' : 'text-slate-400 group-hover:text-slate-900' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}" />
                    </svg>
                    <span class="text-sm font-bold tracking-tight">{{ $link['name'] }}</span>
                </a>
            @endforeach
        </nav>

        <!-- Profile Section with Modern Dropdown -->
        <div class="p-6 mt-auto border-t border-slate-50" x-data="{ userOpen: false }">
            <div class="relative">
                <button @click="userOpen = !userOpen" 
                        class="w-full flex items-center justify-between p-3 rounded-[24px] hover:bg-slate-50 transition-all group active:scale-95">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <img class="size-11 rounded-2xl shadow-sm border-2 border-white group-hover:border-primary-100 transition-all"
                                 src="https://ui-avatars.com/api/?name={{ urlencode($user->nom_complet) }}&background=17a2b8&color=fff&bold=true"
                                 alt="Avatar">
                            <div class="absolute -bottom-0.5 -right-0.5 size-3 bg-emerald-500 border-2 border-white rounded-full"></div>
                        </div>
                        <div class="flex flex-col text-left min-w-0">
                            <span class="text-sm font-black text-slate-900 truncate leading-tight">{{ $user->nom_complet }}</span>
                            <span class="text-[9px] font-black text-primary-500 uppercase tracking-widest">{{ $role }}</span>
                        </div>
                    </div>
                    <svg class="size-4 text-slate-300 group-hover:text-slate-900 transition-transform" :class="userOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="userOpen" 
                     @click.away="userOpen = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                     class="absolute bottom-full left-0 w-full mb-4 bg-white rounded-[32px] border border-slate-100 shadow-premium p-3 z-[90]"
                     x-cloak>
                    
                    <div class="px-5 py-3 border-b border-slate-50 mb-2">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Status du compte</p>
                        <div class="flex items-center gap-2">
                            <span class="size-2 rounded-full bg-emerald-500"></span>
                            <span class="text-[10px] font-bold text-slate-700">Identification Vérifiée</span>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <a href="{{ route('profile.edit') }}" 
                           class="flex items-center gap-3 px-4 py-3 rounded-2xl text-slate-600 hover:bg-slate-50 hover:text-primary-600 transition-all group/item">
                            <div class="size-8 bg-slate-50 rounded-xl flex items-center justify-center group-hover/item:bg-primary-500 group-hover/item:text-white transition-colors">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <span class="text-xs font-black uppercase tracking-widest">Mon Profil</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-rose-500 hover:bg-rose-50 transition-all group/item">
                                <div class="size-8 bg-rose-50 rounded-xl flex items-center justify-center group-hover/item:bg-rose-500 group-hover/item:text-white transition-colors">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                </div>
                                <span class="text-xs font-black uppercase tracking-widest">Déconnexion</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>