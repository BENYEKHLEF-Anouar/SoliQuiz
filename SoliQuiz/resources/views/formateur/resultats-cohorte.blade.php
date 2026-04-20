@extends('components.layout.app')

@section('content')
<div class="bg-slate-50 min-h-screen pb-16" x-data="{ selectedClasse: '' }">
    <main class="max-w-7xl mx-auto px-4 py-12">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
            <div>
                <h1 class="text-4xl font-heading font-black text-slate-900 tracking-tight leading-tight uppercase">Supervision de Cohorte</h1>
                <p class="text-slate-500 font-medium italic">Analysez la progression et les performances de vos classes.</p>
            </div>
            
            <!-- Filter Bar -->
            <div class="flex items-center gap-4 bg-white p-3 rounded-2xl border border-slate-200 shadow-sm">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-2">Filtrer par Classe</span>
                <select x-model="selectedClasse" class="py-2 px-4 bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-primary-500/20">
                    <option value="">Toutes les classes</option>
                    @foreach($classes as $classe)
                        <option value="{{ $classe->nom }}">{{ $classe->nom }}</option>
                    @endforeach
                </select>
            </div>
        </div>

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
                            <tr class="hover:bg-slate-50 transition-colors group" x-show="!selectedClasse || '{{ $tentative->etudiant->classe?->nom }}' === selectedClasse">
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
