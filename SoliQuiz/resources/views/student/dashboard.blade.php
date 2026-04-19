@extends('components.layout.app')

@section('content')
<div class="bg-slate-50 min-h-screen pb-16">
    <!-- Header / Navbar Apprenant -->
    <header class="flex flex-wrap md:justify-start md:flex-nowrap z-50 w-full bg-white border-b border-slate-200 sticky top-0">
        <nav class="relative max-w-7xl w-full flex flex-wrap md:grid md:grid-cols-12 basis-full items-center px-4 md:px-6 mx-auto py-3">
            <div class="md:col-span-3">
                <a class="flex items-center gap-2 group outline-none" href="{{ route('student.dashboard') }}">
                    <div class="size-8 bg-primary-500 rounded-lg flex items-center justify-center shadow-lg shadow-primary-500/10 transition-transform group-hover:scale-110">
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

            <div class="hidden md:flex md:col-span-6 justify-center items-center gap-x-8">
                <a href="{{ route('student.dashboard') }}" class="font-bold text-primary-600 focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm" aria-current="page">Dashboard</a>
                <a href="{{ route('student.bibliotheque') }}" class="font-bold text-slate-400 hover:text-slate-800 transition-colors focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm">Bibliothèque</a>
            </div>

            <div class="flex items-center gap-x-3 ms-auto md:col-span-3 justify-end relative">
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
        <!-- Hero Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
            <div>
                <h1 class="text-3xl font-heading font-black text-slate-900 tracking-tight leading-tight uppercase italic mb-2">Bonjour, {{ Auth::user()->prenom }}</h1>
                <p class="text-slate-500 mt-1 font-medium text-lg">Voici votre progression pédagogique.</p>
            </div>
            <div class="inline-flex flex-col items-end bg-white p-4 px-6 rounded-2xl border border-slate-200 shadow-sm">
                <span class="text-[10px] font-black tracking-widest text-slate-400 uppercase">Score Moyen Global</span>
                <span class="text-3xl font-black text-primary-500">{{ $metrics['score_moyen'] }}%</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Tests Effectués</h3>
                <div class="text-3xl font-black font-heading text-slate-900">{{ $metrics['nb_tentatives'] }}</div>
            </div>
            <div class="bg-white border border-emerald-200 shadow-sm rounded-2xl p-6 relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-2 italic">Taux de Réussite</h3>
                    <div class="text-3xl font-black font-heading text-emerald-700">{{ $metrics['taux_reussite'] }}%</div>
                </div>
            </div>
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Meilleur Score</h3>
                <div class="text-3xl font-black font-heading text-slate-900">{{ $metrics['meilleur_score'] }}%</div>
            </div>
        </div>

        <h2 class="text-xl font-heading font-black text-slate-900 uppercase tracking-tight italic mb-6">Tests Récemment passés</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($historique as $tentative)
                @php
                    $isSuccess = $tentative->statut === 'reussi';
                @endphp
                <div class="bg-white rounded-[2rem] border border-slate-200 p-6 flex flex-col relative overflow-hidden transition-all hover:shadow-lg hover:shadow-slate-200 group">
                    <div class="flex justify-between items-start mb-6">
                        <span class="inline-flex py-1 px-2.5 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $isSuccess ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                            {{ $isSuccess ? 'Validé' : 'Non Validé' }}
                        </span>
                        <div class="size-12 rounded-full border-4 {{ $isSuccess ? 'border-emerald-100' : 'border-rose-100' }} flex items-center justify-center bg-white z-10 shrink-0">
                            <span class="text-xs font-black {{ $isSuccess ? 'text-emerald-600' : 'text-rose-600' }}">{{ $tentative->score_obtenu }}%</span>
                        </div>
                    </div>
                    
                    <h3 class="text-xl font-heading font-black text-slate-900 tracking-tight leading-tight mb-2 pr-4 relative z-10">{{ $tentative->qcm->titre }}</h3>
                    <p class="text-sm font-medium text-slate-500 mb-6 relative z-10">{{ $tentative->qcm->uniteApprentissage ? $tentative->qcm->uniteApprentissage->nom : 'Évaluation générale' }}</p>

                    <div class="mt-auto relative z-10">
                        <a href="{{ route('student.resultats', ['id' => $tentative->qcm_id]) }}" class="inline-flex w-full justify-center items-center gap-x-2 px-4 py-2.5 bg-slate-50 text-slate-700 font-bold rounded-xl hover:bg-slate-100 transition-colors border border-slate-200 text-sm">
                            Voir la correction
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white p-8 rounded-[2rem] border border-slate-200 text-center">
                    <p class="text-slate-500 font-medium">Vous n'avez passé aucun test pour le moment.</p>
                </div>
            @endforelse
        </div>
    </main>
</div>
@endsection
