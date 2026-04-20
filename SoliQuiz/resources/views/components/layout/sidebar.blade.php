@php
    $user = Auth::user();
    $role = $user->type_profil; // admin, formateur, etudiant

    $links = [
        'admin' => [
            ['name' => 'Tableau de Bord', 'route' => 'admin.dashboard', 'icon' => 'M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z'],
            ['name' => 'Utilisateurs', 'route' => 'admin.utilisateurs', 'icon' => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2 M9 7a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm14 14v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75'],
            ['name' => 'Ingénierie Péd.', 'route' => 'admin.pedagogie', 'icon' => 'M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5'],
            ['name' => 'Cohortes', 'route' => 'admin.classes', 'icon' => 'M19 11H5m14 0a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-6a2 2 0 0 1 2-2m14 0V9a2 2 0 0 0-2-2M5 11V9a2 2 0 0 1 2-2m0 0V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2M7 7h10'],
        ],
        'formateur' => [
            ['name' => 'Supervision', 'route' => 'formateur.dashboard', 'icon' => 'M12 20v-6M6 20V10M18 20V4'],
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

<!-- Mobile Header (Visible only on small screens) -->
<div class="lg:hidden bg-slate-900 text-white p-4 flex justify-between items-center z-[60] sticky top-0 shadow-md">
    <div class="flex items-center gap-2">
        <div class="size-8 bg-primary-500 rounded-lg flex items-center justify-center">
            <svg class="text-white size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path d="M14 2H6a2 2 0 0-2 2v16a2 2 0 0 2 2h12a2 2 0 0 2-2V8z" />
                <polyline points="14 2 14 8 20 8" />
                <path d="m9 15 2 2 4-4" />
            </svg>
        </div>
        <span class="font-heading font-bold text-lg tracking-tight">Soli<span
                class="text-primary-500">Quiz</span></span>
    </div>
    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-slate-800 transition-colors">
        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
</div>

<!-- Backdrop for mobile -->
<div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[70] lg:hidden" @click="sidebarOpen = false"
    style="display: none;"></div>

<!-- Sidebar Container -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed lg:static inset-y-0 left-0 w-72 bg-slate-900 text-slate-300 transition-transform duration-300 ease-in-out z-[80] border-r border-slate-800 flex flex-col shadow-2xl lg:shadow-none min-h-screen">

    <!-- Sidebar Header (Logo) -->
    <div class="p-8 pb-4 shrink-0">
        <a class="flex items-center gap-3 group outline-none" href="{{ route('dashboard') }}">
            <div
                class="size-10 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/20 transition-transform group-hover:scale-110">
                <svg class="text-white size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                    <path d="m9 15 2 2 4-4" />
                </svg>
            </div>
            <div class="flex flex-col leading-none">
                <span class="text-2xl font-heading font-black text-white tracking-tight">Soli<span
                        class="text-primary-500 font-black">Quiz</span></span>
                <span
                    class="text-[9px] font-black text-slate-500 uppercase tracking-[0.4em] ml-0.5 mt-0.5">PLATFORM</span>
            </div>
        </a>
    </div>

    <!-- User Profile Brief -->
    <div class="px-6 py-8">
        <div class="bg-slate-800/40 rounded-2xl p-4 border border-slate-800/50">
            <div class="flex items-center gap-3">
                <img class="size-10 rounded-xl border-2 border-primary-500/20 group-hover:border-primary-500/50 transition-colors"
                    src="https://ui-avatars.com/api/?name={{ urlencode($user->prenom . ' ' . $user->nom) }}&background=0ea5e9&color=fff"
                    alt="Avatar">
                <div class="flex flex-col min-w-0">
                    <span class="text-sm font-bold text-white truncate">{{ $user->prenom }} {{ $user->nom }}</span>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider truncate">
                        {{ $role === 'admin' ? 'Administrateur' : ($role === 'formateur' ? 'Formateur Exp.' : 'Apprenant Stage') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-6 space-y-1.5 overflow-y-auto custom-scrollbar">
        <div class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em] mb-4 mt-2 px-2">Navigation
            Principale</div>

        @foreach($currentLinks as $link)
            <a href="{{ route($link['route']) }}"
                class="flex items-center gap-3.5 px-4 py-3.5 rounded-xl transition-all duration-300 group {{ Route::is($link['route'] . '*') ? 'bg-primary-500 text-white shadow-lg shadow-primary-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <svg class="size-5 shrink-0 {{ Route::is($link['route'] . '*') ? 'text-white' : 'text-slate-500 group-hover:text-primary-400' }} transition-colors"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="{{ $link['icon'] }}" />
                </svg>
                <span class="text-sm font-bold tracking-wide">{{ $link['name'] }}</span>
                @if(Route::is($link['route'] . '*'))
                    <div class="size-1.5 rounded-full bg-white ms-auto shadow-[0_0_8px_rgba(255,255,255,0.8)]"></div>
                @endif
            </a>
        @endforeach
    </nav>

    <!-- Footer Sidebar -->
    <div class="p-6 mt-auto border-t border-slate-800/50 bg-slate-900/50 backdrop-blur-xl">
        <div class="space-y-2">
            <a href="{{ route('profile.edit') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all group">
                <svg class="size-5 transition-colors group-hover:text-primary-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                <span class="text-sm font-bold">Mon Profil</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-500/80 hover:bg-red-500/10 hover:text-red-500 transition-all font-bold group">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" y1="12" x2="9" y2="12" />
                    </svg>
                    <span class="text-sm font-black uppercase tracking-wider">Déconnexion</span>
                </button>
            </form>
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
        background: #1e293b;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #334155;
    }
</style>