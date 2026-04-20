@php
    $user = Auth::user();
    $role = $user->type_profil; // admin, formateur, etudiant
@endphp

<header class="flex flex-wrap md:justify-start md:flex-nowrap z-50 w-full {{ $role === 'admin' ? 'bg-slate-900 border-slate-800' : 'bg-white border-slate-200' }} border-b sticky top-0 transition-colors duration-300">
    <nav class="relative max-w-7xl w-full flex flex-wrap md:grid md:grid-cols-12 basis-full items-center px-4 md:px-6 mx-auto py-3">
        <!-- Logo -->
        <div class="md:col-span-3">
            <a class="flex items-center gap-2 group outline-none" href="{{ route('dashboard') }}">
                <div class="size-8 bg-primary-500 rounded-lg flex items-center justify-center shadow-lg shadow-primary-500/20 transition-transform group-hover:scale-110">
                    <svg class="text-white size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <path d="m9 15 2 2 4-4" />
                    </svg>
                </div>
                <div class="flex flex-col leading-none">
                    <span class="text-xl font-heading font-bold {{ $role === 'admin' ? 'text-white' : 'text-slate-900' }} tracking-tight">Soli<span class="text-primary-500">Quiz</span></span>
                    <span class="text-[10px] font-black {{ $role === 'admin' ? 'text-slate-500' : 'text-slate-400' }} uppercase tracking-[0.3em] ml-0.5">
                        {{ $role === 'admin' ? 'Admin' : ($role === 'formateur' ? 'Formateur' : 'Apprenant') }}
                    </span>
                </div>
            </a>
        </div>

        <!-- Links Center -->
        <div class="hidden md:flex md:col-span-6 justify-center items-center gap-x-8">
            @if($role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="{{ Route::is('admin.dashboard') ? 'text-white' : 'text-slate-400 hover:text-white' }} font-bold transition-colors focus:outline-none font-heading uppercase tracking-wide text-sm">Opérations</a>
                <a href="{{ route('admin.utilisateurs') }}" class="{{ Route::is('admin.utilisateurs') ? 'text-white' : 'text-slate-400 hover:text-white' }} font-bold transition-colors focus:outline-none font-heading uppercase tracking-wide text-sm">Utilisateurs</a>
                <a href="{{ route('admin.pedagogie') }}" class="{{ Route::is('admin.pedagogie') ? 'text-white' : 'text-slate-400 hover:text-white' }} font-bold transition-colors focus:outline-none font-heading uppercase tracking-wide text-sm">Pédagogie</a>
                <a href="{{ route('admin.classes') }}" class="{{ Route::is('admin.classes') ? 'text-white' : 'text-slate-400 hover:text-white' }} font-bold transition-colors focus:outline-none font-heading uppercase tracking-wide text-sm">Classes</a>
            @elseif($role === 'formateur')
                <a href="{{ route('formateur.dashboard') }}" class="{{ Route::is('formateur.dashboard') ? 'text-primary-600' : 'text-slate-400 hover:text-slate-800' }} font-bold transition-colors focus:outline-none font-heading uppercase tracking-wide text-sm">Stats</a>
                <a href="{{ route('formateur.bibliotheque') }}" class="{{ Route::is('formateur.bibliotheque') ? 'text-primary-600' : 'text-slate-400 hover:text-slate-800' }} font-bold transition-colors focus:outline-none font-heading uppercase tracking-wide text-sm">Bibliothèque</a>
                <a href="{{ route('formateur.resultats') }}" class="{{ Route::is('formateur.resultats') ? 'text-primary-600' : 'text-slate-400 hover:text-slate-800' }} font-bold transition-colors focus:outline-none font-heading uppercase tracking-wide text-sm">Ma Classe</a>
            @else
                <a href="{{ route('student.dashboard') }}" class="{{ Route::is('student.dashboard') ? 'text-primary-600' : 'text-slate-400 hover:text-slate-800' }} font-bold transition-colors focus:outline-none font-heading uppercase tracking-wide text-sm">Dashboard</a>
                <a href="{{ route('student.bibliotheque') }}" class="{{ Route::is('student.bibliotheque') ? 'text-primary-600' : 'text-slate-400 hover:text-slate-800' }} font-bold transition-colors focus:outline-none font-heading uppercase tracking-wide text-sm">Bibliothèque</a>
            @endif
        </div>

        <!-- User Dropdown & Logout -->
        <div class="flex items-center gap-x-3 md:gap-x-4 ms-auto md:col-span-3 justify-end relative">
            <div class="hs-dropdown relative inline-flex" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" type="button" class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-full border {{ $role === 'admin' ? 'border-slate-700 bg-slate-800 text-white' : 'border-slate-200 bg-white text-slate-800' }} shadow-sm hover:opacity-80 p-1 pr-3 transition-all active:scale-95">
                    <img class="inline-block size-8 rounded-full shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode($user->prenom . ' ' . $user->nom) }}&background=0ea5e9&color=fff" alt="Avatar">
                    <span class="hidden md:inline-block font-heading font-bold text-sm">{{ $user->prenom }}.{{ substr($user->nom, 0, 1) }}</span>
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                
                <div x-show="open" x-transition class="absolute right-0 top-full mt-2 min-w-48 {{ $role === 'admin' ? 'bg-slate-900 border-slate-800 text-slate-300' : 'bg-white border-slate-100 text-slate-600' }} shadow-2xl rounded-2xl p-2 border z-50">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-x-3.5 py-2 px-3 rounded-xl text-sm font-bold hover:bg-slate-100 {{ $role === 'admin' ? 'hover:bg-slate-800' : 'hover:bg-slate-50' }}">
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Mon Profil
                    </a>
                    <div class="h-px {{ $role === 'admin' ? 'bg-slate-800' : 'bg-slate-100' }} my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-xl text-sm text-red-500 hover:bg-red-500/10 font-bold transition-colors">
                            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</header>
