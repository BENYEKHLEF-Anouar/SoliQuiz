@php
    $user = Auth::user();
    $role = $user->type_profil;
    $resetRequestsCount = 0;
    if ($role === 'admin') {
        $resetRequestsCount = \App\Models\PasswordResetRequest::where('status', 'pending')->count();
    }
@endphp

<!-- Mobile Header (Outside Aside but Fixed) -->
<div class="lg:hidden glass fixed top-0 left-0 right-0 z-50 px-6 h-16 flex justify-between items-center shadow-sm">
    <div class="flex items-center gap-2">
        <div class="size-8 bg-primary-500 rounded-lg flex items-center justify-center shadow-lg shadow-primary-500/20">
            <svg class="text-white size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <polyline points="14 2 14 8 20 8" />
                <path d="m9 15 2 2 4-4" />
            </svg>
        </div>
        <span class="font-heading font-bold text-lg text-slate-900 leading-none tracking-tight">Soli<span class="text-primary-500">Quiz</span></span>
    </div>
    <button @click.stop="sidebarOpen = !sidebarOpen" type="button" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 transition-colors active:scale-95">
        <svg class="size-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" />
        </svg>
    </button>
</div>

<!-- Backdrop (Mobile Only) -->
<div x-show="sidebarOpen"
     x-transition:enter="transition duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-60 lg:hidden"
     x-cloak></div>

<!-- Sidebar Container -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       @click.away="sidebarOpen = false"
       class="fixed top-0 left-0 w-72 transition-transform duration-300 ease-out z-70 h-screen shrink-0">
    
    <div class="h-full p-4"> <!-- Inner wrapper to maintain floating look while container hits the edges -->
        <div class="h-full bg-white rounded-3xl border border-slate-100 shadow-sm flex flex-col overflow-hidden relative">
        <!-- Logo Section -->
        <div class="p-6 border-b border-slate-50">
            <a class="flex items-center gap-3 transition-transform active:scale-95 outline-none" href="{{ route('dashboard') }}">
                <div class="size-9 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/20 text-white">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <path d="m9 15 2 2 4-4" />
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-lg font-heading font-black text-slate-900 tracking-tight leading-none uppercase">Soli<span class="text-primary-500">Quiz</span></span>
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">Espace {{ ucfirst($role) }}</span>
                </div>
            </a>
        </div>

        <!-- Navigation Section -->
        <nav class="flex-1 px-3 py-6 space-y-6 overflow-y-auto custom-scrollbar">
            @php
                $categories = [
                    'admin' => [
                        'Général' => [
                            ['name' => 'Tableau de Bord', 'route' => 'admin.dashboard', 'icon' => 'M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z'],
                            ['name' => 'Supervision', 'route' => 'admin.resultats', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                            ['name' => 'Classement', 'route' => 'admin.leaderboard', 'icon' => 'M6 9H4.5a2.5 2.5 0 0 1 0-5H6M18 9h1.5a2.5 2.5 0 0 0 0-5H18M4 22h16M10 14.66V17c0 .55-.45 1-1 1H4v2h16v-2h-5c-.55 0-1-.45-1-1v-2.34M12 2a6 6 0 0 1 6 6v3.5c0 2.5-2 4.5-4.5 4.5h-3C7.99 16 6 14 6 11.5V8a6 6 0 0 1 6-6z'],
                        ],
                        'Gestion' => [
                            ['name' => 'Utilisateurs', 'route' => 'admin.utilisateurs', 'icon' => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2 M9 7a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm14 14v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75'],
                            ['name' => 'Cohortes', 'route' => 'admin.classes', 'icon' => 'M19 11H5m14 0a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-6a2 2 0 0 1 2-2m14 0V9a2 2 0 0 0-2-2M5 11V9a2 2 0 0 1 2-2m0 0V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2M7 7h10'],
                        ],
                        'Contenu' => [
                            ['name' => 'Banque de QCM', 'route' => 'admin.qcms', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                            ['name' => 'Structure PÉD.', 'route' => 'admin.pedagogie', 'icon' => 'M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5'],
                        ]
                    ],
                    'formateur' => [
                        'Général' => [
                            ['name' => 'Supervision', 'route' => 'formateur.dashboard', 'icon' => 'M12 20v-6M6 20V10M18 20V4'],
                            ['name' => 'Classement', 'route' => 'formateur.leaderboard', 'icon' => 'M6 9H4.5a2.5 2.5 0 0 1 0-5H6M18 9h1.5a2.5 2.5 0 0 0 0-5H18M4 22h16M10 14.66V17c0 .55-.45 1-1 1H4v2h16v-2h-5c-.55 0-1-.45-1-1v-2.34M12 2a6 6 0 0 1 6 6v3.5c0 2.5-2 4.5-4.5 4.5h-3C7.99 16 6 14 6 11.5V8a6 6 0 0 1 6-6z'],
                        ],
                        'Pédagogie' => [
                            ['name' => 'Résultats', 'route' => 'formateur.resultats', 'icon' => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2 M9 7a4 4 0 1 0 0-8 4 4 0 0 0 0 8z'],
                            ['name' => 'Structure PÉD.', 'route' => 'formateur.pedagogie', 'icon' => 'M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5'],
                        ],
                        'Contenu' => [
                            ['name' => 'Bibliothèque QCM', 'route' => 'formateur.bibliotheque', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                        ]
                    ],
                    'etudiant' => [
                        'Espace Apprenant' => [
                            ['name' => 'Tableau de Bord', 'route' => 'etudiant.dashboard', 'icon' => 'M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z'],
                            ['name' => 'Bibliothèque QCM', 'route' => 'etudiant.bibliotheque', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                            ['name' => 'Ma Progression', 'route' => 'etudiant.progression', 'icon' => 'M12 20v-6M6 20V10M18 20V4'],
                            ['name' => 'Classement', 'route' => 'etudiant.leaderboard', 'icon' => 'M6 9H4.5a2.5 2.5 0 0 1 0-5H6M18 9h1.5a2.5 2.5 0 0 0 0-5H18M4 22h16M10 14.66V17c0 .55-.45 1-1 1H4v2h16v-2h-5c-.55 0-1-.45-1-1v-2.34M12 2a6 6 0 0 1 6 6v3.5c0 2.5-2 4.5-4.5 4.5h-3C7.99 16 6 14 6 11.5V8a6 6 0 0 1 6-6z'],
                        ]
                    ]
                ];

                $currentRoleCategories = $categories[$role] ?? [];
            @endphp

            @foreach($currentRoleCategories as $category => $categoryLinks)
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4 flex items-center gap-2 px-4 leading-none">
                        {{ $category }}
                    </p>
                    <div class="space-y-1">
                        @foreach($categoryLinks as $link)
                            @php $isActive = Route::is($link['route'] . '*'); @endphp
                            <a href="{{ route($link['route']) }}"
                                class="group flex items-center gap-3.5 px-5 py-3 rounded-xl transition-all duration-200 {{ $isActive ? 'bg-primary-500 text-white shadow-lg shadow-primary-500/20' : 'text-slate-500 hover:bg-primary-50 hover:text-primary-600' }}">
                                <svg class="size-4.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}" />
                                </svg>
                                <span class="text-[11px] font-bold uppercase tracking-wider">{{ $link['name'] }}</span>
                                @if($link['route'] === 'admin.dashboard' && $resetRequestsCount > 0)
                                    <span class="ml-auto flex items-center justify-center h-5 min-w-[20px] px-1.5 bg-rose-500 text-white text-[9px] font-black rounded-full shadow-sm shadow-rose-500/10">
                                        {{ $resetRequestsCount }}
                                    </span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </nav>

        <!-- Profile Section -->
        <div class="p-6 border-t border-slate-50 bg-slate-50/30" x-data="{ userOpen: false }">
            <div class="relative">
                <button @click.stop="userOpen = !userOpen" type="button"
                        class="w-full flex items-center justify-between p-2 rounded-2xl hover:bg-white hover:shadow-sm transition-all group active:scale-95 border border-transparent hover:border-slate-100">
                    <div class="flex items-center gap-3">
                        <img class="size-9 rounded-xl shadow-sm border border-white"
                                src="https://ui-avatars.com/api/?name={{ urlencode($user->nom_complet ?? 'User') }}&background=17a2b8&color=fff&bold=true"
                                alt="Avatar">
                        <div class="flex flex-col text-left min-w-0">
                            <span class="text-[11px] font-black text-slate-900 truncate leading-tight uppercase">{{ $user->nom_complet }}</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $role }}</span>
                        </div>
                    </div>
                    <svg class="size-4 text-slate-300 group-hover:text-slate-600 transition-transform" :class="userOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Menu: Premium Glassmorphism -->
                <div x-show="userOpen" 
                     @click.away="userOpen = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                     class="absolute bottom-full left-0 w-full mb-4 bg-white rounded-[2rem] border border-slate-200 shadow-premium p-3 z-90 overflow-hidden"
                     x-cloak>
                    
                    <a href="{{ url('/') }}" 
                        class="flex items-center gap-3 px-5 py-4 rounded-2xl text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-all text-[11px] font-black uppercase tracking-widest group/item">
                        <div class="size-8 bg-slate-100 text-slate-500 rounded-xl flex items-center justify-center group-hover/item:bg-slate-900 group-hover/item:text-white transition-all shadow-sm">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        </div>
                        Page d'accueil
                    </a>

                    <div class="h-px bg-slate-100/50 my-2 mx-4"></div>

                    <a href="{{ route('profile.edit') }}" 
                        class="flex items-center gap-3 px-5 py-4 rounded-2xl text-slate-600 hover:bg-primary-50 hover:text-primary-600 transition-all text-[11px] font-black uppercase tracking-widest group/item">
                        <div class="size-8 bg-primary-50 text-primary-500 rounded-xl flex items-center justify-center group-hover/item:bg-primary-500 group-hover/item:text-white transition-all shadow-sm">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </div>
                        Paramètres
                    </a>

                    <div class="h-px bg-slate-100/50 my-2 mx-4"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center gap-3 px-5 py-4 rounded-2xl text-rose-500 hover:bg-rose-50 transition-all text-[11px] font-black uppercase tracking-widest group/item">
                            <div class="size-8 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center group-hover/item:bg-rose-500 group-hover/item:text-white transition-all shadow-sm">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                            </div>
                            Déconnexion
                        </button>
                    </form>
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