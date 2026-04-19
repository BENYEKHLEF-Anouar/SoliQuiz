@extends('components.layout.app')

@section('content')
<div class="bg-gray-50 h-full min-h-screen pb-16">
    <!-- Header / Navbar Formateur -->
    <header class="flex flex-wrap md:justify-start md:flex-nowrap z-50 w-full bg-white border-b border-slate-200 sticky top-0">
        <nav class="relative max-w-7xl w-full flex flex-wrap md:grid md:grid-cols-12 basis-full items-center px-4 md:px-6 mx-auto py-3">
            <div class="md:col-span-3">
                <a class="flex items-center gap-2 group outline-none" href="{{ route('formateur.dashboard') }}">
                    <div class="size-8 bg-primary-500 rounded-lg flex items-center justify-center shadow-lg shadow-primary-500/20 transition-transform group-hover:rotate-6">
                        <svg class="text-white size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <path d="m9 15 2 2 4-4" />
                        </svg>
                    </div>
                    <div class="flex flex-col leading-none">
                        <span class="text-xl font-heading font-bold text-slate-900 tracking-tight">Soli<span class="text-primary-500">Quiz</span></span>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-0.5">Formateur</span>
                    </div>
                </a>
            </div>

            <div class="hidden md:flex md:col-span-6 justify-center items-center gap-x-8">
                <a href="{{ route('formateur.dashboard') }}" class="font-bold text-primary-600 focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm" aria-current="page">Supervision</a>
                <a href="{{ route('formateur.bibliotheque') }}" class="font-bold text-slate-400 hover:text-slate-800 transition-colors focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm">Mes QCM</a>
                <a href="{{ route('formateur.resultats') }}" class="font-bold text-slate-400 hover:text-slate-800 transition-colors focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm">Résultats</a>
            </div>

            <div class="flex items-center gap-x-3 ms-auto md:col-span-3 justify-end relative">
                <a href="{{ route('formateur.qcm.create') }}" class="hidden sm:inline-flex items-center gap-1.5 py-2 px-3 text-xs font-bold rounded-xl border border-transparent bg-primary-500 text-white hover:bg-primary-600 transition-all shadow-md shadow-primary-500/20 active:scale-95 uppercase tracking-widest font-heading">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Créer QCM
                </a>

                <div class="hs-dropdown relative inline-flex">
                    <button id="hs-dropdown-avatar" type="button" class="hs-dropdown-toggle inline-flex items-center gap-x-2 text-sm font-semibold rounded-full border border-slate-200 bg-white text-slate-800 shadow-sm hover:bg-slate-50 focus:outline-none p-1 pr-3 transition-all active:scale-95">
                        <img class="inline-block size-8 rounded-full shadow-sm" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->prenom . ' ' . Auth::user()->nom) }}&background=0ea5e9&color=fff" alt="Avatar">
                        <span class="hidden md:inline-block text-slate-800 font-heading font-bold text-sm">{{ Auth::user()->prenom }}.{{ substr(Auth::user()->nom, 0, 1) }}</span>
                    </button>
                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-48 bg-white shadow-xl rounded-2xl p-2 mt-2 border border-slate-100 z-50">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-xl text-sm text-red-600 hover:bg-red-50 focus:outline-none font-bold">Déconnexion</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-12">
        <!-- Dashboard Header -->
        <div class="mb-12 relative overflow-hidden bg-white border border-slate-200 p-10 rounded-[2.5rem] shadow-sm">
            <div class="absolute -top-10 -right-10 size-64 bg-slate-50 rounded-full blur-3xl"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div>
                    <h1 class="text-4xl font-heading font-black text-slate-900 tracking-tight leading-tight uppercase italic mb-2">Mon Espace Formateur</h1>
                    <p class="text-slate-500 font-medium text-lg">Gérez vos QCMs et suivez vos cohortes.</p>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('formateur.qcm.create') }}" class="bg-primary-500 hover:bg-primary-600 p-4 px-6 rounded-2xl text-white flex flex-col items-center shadow-lg transition-transform hover:scale-105 active:scale-95">
                        <span class="text-2xl font-black">+</span>
                        <span class="text-[10px] font-black tracking-widest uppercase">Nouveau QCM</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- KPI Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Classes Gérées</h3>
                <div class="text-3xl font-black font-heading text-slate-900">{{ $metrics['nb_classes'] }}</div>
            </div>
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Étudiants Suivis</h3>
                <div class="text-3xl font-black font-heading text-slate-900">{{ $metrics['nb_etudiants'] }}</div>
            </div>
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">QCM Créés</h3>
                <div class="text-3xl font-black font-heading text-slate-900">{{ $metrics['nb_qcms'] }}</div>
            </div>
            <div class="bg-white border border-emerald-200 shadow-sm rounded-2xl p-6">
                <h3 class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-2 italic">QCM Publiés</h3>
                <div class="text-3xl font-black font-heading text-emerald-700">{{ $metrics['nb_qcms_publies'] }}</div>
            </div>
        </div>

        <!-- Classes -->
        <h2 class="text-xl font-heading font-black text-slate-900 uppercase tracking-tight italic mb-6">Vos Cohortes</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($classes as $classe)
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <span class="inline-block py-1 px-3 bg-slate-100 text-slate-600 text-[10px] font-black uppercase tracking-widest rounded-full">{{ $classe->promotion ?? 'Générale' }}</span>
                </div>
                <h3 class="text-2xl font-bold font-heading text-slate-900 tracking-tight leading-tight mb-4">{{ $classe->nom }}</h3>
                <div class="flex items-center justify-between mt-auto pt-4 border-t border-slate-50">
                    <span class="text-sm font-medium text-slate-500">
                        <strong class="text-slate-900">{{ $classe->etudiants_count }}</strong> Étudiants
                    </span>
                    <a href="{{ route('formateur.resultats') }}" class="text-primary-600 hover:text-primary-700 font-bold text-sm">Voir scores &rarr;</a>
                </div>
            </div>
            @empty
            <div class="col-span-full p-8 text-center bg-white border border-slate-200 rounded-3xl">
                <p class="text-slate-500 font-medium font-heading">Vous n'avez pas encore de classe assignée.</p>
            </div>
            @endforelse
        </div>
    </main>
</div>
@endsection
