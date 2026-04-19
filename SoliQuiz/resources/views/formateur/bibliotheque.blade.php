@extends('components.layout.app')

@section('content')
<div class="bg-slate-50 min-h-screen">
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
                <a href="{{ route('formateur.dashboard') }}" class="font-bold text-slate-400 hover:text-slate-800 transition-colors focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm">Supervision</a>
                <a href="{{ route('formateur.bibliotheque') }}" class="font-bold text-primary-600 focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm" aria-current="page">Mes QCM</a>
            </div>

            <div class="flex items-center gap-x-3 md:gap-x-4 ms-auto md:col-span-3 justify-end relative">
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

    <main class="max-w-7xl mx-auto px-4 py-12">
        @if (session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-600 font-bold text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
            <div>
                <nav class="flex items-center gap-2 text-primary-500 text-xs font-bold uppercase tracking-widest mb-3">
                    <span class="size-1.5 rounded-full bg-primary-500"></span>
                    Gestionnaire de contenu
                </nav>
                <h1 class="text-4xl font-heading font-bold text-slate-900 tracking-tight leading-tight">Bibliothèque de QCM</h1>
                <p class="text-slate-500 mt-2 font-medium">Visualisez, éditez et archivez vos évaluations créées pour l'ensemble du centre.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($qcms as $qcm)
            <article class="bg-white border-2 border-slate-100 rounded-3xl p-6 shadow-sm hover:shadow-xl hover:border-primary-100 transition-all group flex flex-col relative overflow-hidden">
                <div class="absolute -top-12 -right-12 size-32 bg-emerald-50 rounded-full group-hover:bg-primary-50 transition-colors"></div>
                
                <div class="flex justify-between items-start mb-6 relative">
                    @if($qcm->est_publie)
                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-[10px] font-black uppercase tracking-widest border border-emerald-200/50">Publié</span>
                    @else
                    <span class="px-2.5 py-1 bg-slate-100 text-slate-500 rounded-lg text-[10px] font-bold uppercase tracking-widest border border-slate-200">Brouillon</span>
                    @endif
                    
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-400">{{ $qcm->tentatives_count }} Passages</span>
                    </div>
                </div>
                
                <h3 class="text-xl font-heading font-black text-slate-900 group-hover:text-primary-600 transition-colors mb-2">{{ $qcm->titre }}</h3>
                <p class="text-sm text-slate-500 leading-relaxed font-medium mb-8">Score de réussite requis : {{ $qcm->score_reussite }}% • Durée : {{ $qcm->duree_minutes }} min</p>

                <div class="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Créé le {{ $qcm->created_at->format('d/m/Y') }}</span>
                    <div class="flex gap-1.5">
                        <button type="button" class="size-9 inline-flex items-center justify-center bg-red-50 text-red-500 rounded-xl hover:bg-red-100 transition-all active:scale-90 shadow-sm" onclick="if(confirm('Supprimer ce QCM ?')) { document.getElementById('delete-qcm-{{ $qcm->id }}').submit() }">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                        <form id="delete-qcm-{{ $qcm->id }}" action="{{ route('formateur.qcm.destroy', $qcm->id) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </article>
            @endforeach

            <!-- New QCM Card (CTA) -->
            <a href="{{ route('formateur.qcm.create') }}" class="bg-primary-50/50 border-2 border-dashed border-primary-200 rounded-3xl p-6 flex flex-col items-center justify-center text-center group hover:bg-primary-500 hover:border-primary-500 transition-all duration-300 min-h-[250px]">
                <div class="size-16 rounded-full bg-primary-100 flex items-center justify-center text-primary-500 mb-6 group-hover:bg-white transition-all shadow-lg shadow-primary-500/10">
                    <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <h3 class="text-xl font-heading font-black text-primary-900 group-hover:text-white transition-colors">Créer un nouveau contenu</h3>
                <p class="text-xs font-bold text-primary-600 mt-2 group-hover:text-primary-100 uppercase tracking-widest font-sans">Ajouter une évaluation</p>
            </a>
        </div>
    </main>
</div>
@endsection
