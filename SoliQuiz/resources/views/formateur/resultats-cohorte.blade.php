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
                <a href="{{ route('formateur.bibliotheque') }}" class="font-bold text-slate-400 hover:text-slate-800 transition-colors focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm">Mes QCM</a>
                <a href="{{ route('formateur.resultats') }}" class="font-bold text-primary-600 focus:outline-none flex items-center gap-x-2 font-heading uppercase tracking-wide text-sm" aria-current="page">Résultats</a>
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
        <h1 class="text-4xl font-heading font-black text-slate-900 tracking-tight leading-tight uppercase mb-8">Résultats par QCM</h1>

        @foreach($qcms as $qcm)
        <div class="mb-12">
            <!-- Header Info QCM -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-6">
                <div class="flex flex-col gap-2">
                    <h2 class="text-2xl font-heading font-bold text-slate-900 tracking-tight leading-tight">{{ $qcm->titre }}</h2>
                    <p class="text-slate-500 font-medium">{{ $qcm->uniteApprentissage ? $qcm->uniteApprentissage->nom : 'Général' }}</p>
                    <div class="flex items-center gap-4 mt-2">
                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-[10px] font-black uppercase tracking-widest border border-emerald-200/50">Tentatives : {{ $qcm->tentatives->count() }}</span>
                    </div>
                </div>
            </div>

            @php
                $scores = $qcm->tentatives->whereNotNull('score_obtenu')->pluck('score_obtenu');
                $moyenne = $scores->count() > 0 ? round($scores->average(), 1) : 0;
            $reussite = $scores->count() > 0 ? round(($scores->filter(function($s) use ($qcm) { return $s >= $qcm->score_reussite; })->count() / $scores->count()) * 100) : 0;
            @endphp

            <!-- KPIs Cohorte -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                <div class="p-6 bg-white border border-slate-200 rounded-[2rem] shadow-sm flex flex-col group hover:border-primary-100 transition-all">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Moyenne</span>
                    <span class="text-4xl font-black font-heading text-slate-900 group-hover:text-primary-500 transition-colors">{{ $moyenne }}<span class="text-slate-300">%</span></span>
                </div>
                <div class="p-6 bg-white border border-slate-200 rounded-[2rem] shadow-sm flex flex-col group hover:border-emerald-100 transition-all">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Taux de réussite</span>
                    <span class="text-4xl font-black font-heading text-emerald-500">{{ $reussite }}%</span>
                </div>
                <div class="p-6 bg-white border border-slate-200 rounded-[2rem] shadow-sm flex flex-col group hover:border-amber-100 transition-all">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Score Requis</span>
                    <span class="text-4xl font-black font-heading text-amber-500">{{ $qcm->score_reussite }}%</span>
                </div>
            </div>

            <!-- Detailed Results Table -->
            <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Apprenant</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Date / Heure</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Statut</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Score</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($qcm->tentatives as $tentative)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-8 py-5 flex items-center gap-3">
                                    <img class="size-10 rounded-xl" src="https://ui-avatars.com/api/?name={{ urlencode($tentative->etudiant->prenom . ' ' . $tentative->etudiant->nom) }}&background=0ea5e9&color=fff" alt="Avatar">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-900">{{ $tentative->etudiant->prenom }} {{ $tentative->etudiant->nom }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-sm font-semibold text-slate-500">{{ $tentative->date_debut->format('d/m/Y - H:i') }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    @if($tentative->statut === 'reussi')
                                        <span class="inline-flex items-center px-4 py-1.5 bg-emerald-100 text-emerald-700 rounded-full text-xs font-black shadow-sm ring-1 ring-emerald-500/20 italic">Réussi</span>
                                    @elseif($tentative->statut === 'echoue')
                                        <span class="inline-flex items-center px-4 py-1.5 bg-red-100 text-red-700 rounded-full text-xs font-black shadow-sm ring-1 ring-red-500/20 italic">Échoué</span>
                                    @else
                                        <span class="inline-flex items-center px-4 py-1.5 bg-amber-100 text-amber-700 rounded-full text-xs font-black shadow-sm ring-1 ring-amber-500/20 italic">En cours</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-sm font-bold text-slate-500">{{ $tentative->score_obtenu !== null ? $tentative->score_obtenu . '%' : '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-5 text-center text-sm text-slate-500 font-medium">Aucune tentative enregistrée pour ce QCM.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach

    </main>
</div>
@endsection
