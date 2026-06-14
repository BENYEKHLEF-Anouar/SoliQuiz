@extends('layouts.app')

@section('title', 'Classement - Formateur')

@section('content')
<div class="space-y-8 fade-in" x-data="leaderboardFilter()" id="leaderboard-content">
    <!-- Header -->
    <div class="relative z-30 mb-10">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-8 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-4 mb-3">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Classement</span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-900">
                        @if($selectedClasse)
                            {{ $selectedClasse->nom }}
                        @else
                            Toutes vos Classes
                        @endif
                    </span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-900">
                        @if($selectedQcm)
                            {{ $selectedQcm->titre }}
                        @else
                            Classement Général
                        @endif
                    </span>
                </div>
                <h3 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                    Suivi des Performances Cohorte
                </h3>
                <p class="mt-2 text-sm text-slate-500 max-w-xl">
                    Comparez l'engagement et les résultats globaux ou spécifiques de vos apprenants.
                </p>
            </div>

            <!-- Filter Form -->
            <div class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="w-full sm:w-56 relative">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Classe</label>
                    <div class="relative">
                        <button type="button" @click="openClasse = !openClasse" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-xs font-bold text-slate-800 flex items-center justify-between outline-none focus:border-slate-900 transition-all shadow-xs text-left">
                            <span>{{ $selectedClasse ? $selectedClasse->nom : 'Toutes vos classes' }}</span>
                            <svg class="size-4.5 text-slate-500 transition-transform duration-200" :class="{ 'rotate-180': openClasse }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                        <div x-show="openClasse" @click.away="openClasse = false" x-transition class="absolute z-[100] mt-2 w-full bg-white border border-slate-200 rounded-xl shadow-lg max-h-60 overflow-y-auto py-1" x-cloak>
                            <div class="p-2 border-b border-slate-100 sticky top-0 bg-white z-10">
                                <input type="text" x-model="searchClasse" placeholder="Rechercher..." class="w-full px-3 py-1.5 text-xs border border-slate-200 rounded-lg outline-none focus:border-slate-900 font-bold">
                            </div>
                            <button type="button" @click="openClasse = false; updateFilters({ classe_id: '', qcm_id: '' })" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-slate-900 flex items-center justify-between">
                                Toutes vos classes
                                @if(!$classeId)
                                    <span>
                                        <svg class="size-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                    </span>
                                @endif
                            </button>
                            @foreach($classes as $c)
                                <button type="button" 
                                        x-show="searchClasse === '' || '{{ addslashes(strtolower($c->nom)) }}'.includes(searchClasse.toLowerCase())"
                                        @click="openClasse = false; updateFilters({ classe_id: '{{ $c->id }}', qcm_id: '' })" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-slate-900 flex items-center justify-between">
                                    {{ $c->nom }}
                                    @if($classeId == $c->id)
                                        <span>
                                            <svg class="size-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                        </span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="w-full sm:w-64 relative">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">QCM</label>
                    <div class="relative">
                        <button type="button" @click="openQcm = !openQcm" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-xs font-bold text-slate-800 flex items-center justify-between outline-none focus:border-slate-900 transition-all shadow-xs text-left">
                            <span class="truncate pr-2">{{ $selectedQcm ? $selectedQcm->titre . ($selectedQcm->classe ? ' (' . $selectedQcm->classe->nom . ')' : ' (Transverse)') : 'Classement Général (Moyenne)' }}</span>
                            <svg class="size-4.5 text-slate-500 transition-transform duration-200 shrink-0" :class="{ 'rotate-180': openQcm }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                        <div x-show="openQcm" @click.away="openQcm = false" x-transition class="absolute z-[100] mt-2 w-full bg-white border border-slate-200 rounded-xl shadow-lg max-h-60 overflow-y-auto py-1" x-cloak>
                            <div class="p-2 border-b border-slate-100 sticky top-0 bg-white z-10">
                                <input type="text" x-model="searchQcm" placeholder="Rechercher..." class="w-full px-3 py-1.5 text-xs border border-slate-200 rounded-lg outline-none focus:border-slate-900 font-bold">
                            </div>
                            <button type="button" @click="openQcm = false; updateFilters({ classe_id: '{{ $classeId }}', qcm_id: '' })" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-slate-900 flex items-center justify-between">
                                Classement Général (Moyenne)
                                @if(!$qcmId)
                                    <span>
                                        <svg class="size-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                    </span>
                                @endif
                            </button>
                            @foreach($qcms as $q)
                                <button type="button" 
                                        x-show="searchQcm === '' || '{{ addslashes(strtolower($q->titre)) }}'.includes(searchQcm.toLowerCase())"
                                        @click="openQcm = false; updateFilters({ classe_id: '{{ $classeId }}', qcm_id: '{{ $q->id }}' })" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-slate-900 flex items-center justify-between">
                                    <span class="truncate pr-2">{{ $q->titre }} ({{ $q->classe?->nom ?? 'Transverse' }})</span>
                                    @if($qcmId == $q->id)
                                        <span>
                                            <svg class="size-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                        </span>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
                @if($classeId || $qcmId)
                    <button type="button" @click="updateFilters({ classe_id: '', qcm_id: '' })" class="size-11 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center hover:bg-rose-100 transition-all shadow-xs shrink-0" title="Réinitialiser les filtres">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Podium and Table Wrapper with Loading State -->
    <div class="relative" 
         @click="if ($event.target.closest('.pagination a, [role=navigation] a')) { $event.preventDefault(); updateFilters(null, $event.target.closest('.pagination a, [role=navigation] a').getAttribute('href')); }"
         :class="loading ? 'opacity-50 pointer-events-none transition-all duration-300' : 'transition-all duration-300'">
        <!-- Loading Overlay -->
        <div x-show="loading"
            class="absolute inset-0 bg-white/40 backdrop-blur-[2px] z-50 flex flex-col items-center justify-center rounded-3xl min-h-[200px]"
            x-transition
            style="display: none;">
            <div class="flex flex-col items-center gap-3 scale-90">
                <div class="size-10 border-4 border-slate-100 border-t-primary-500 rounded-full animate-spin shadow-sm"></div>
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] animate-pulse">Chargement...</p>
            </div>
        </div>

    <!-- Podium for Top 3 -->
    @if($rankings->count() > 0)
        @php
            $top1 = $rankings->where('rank', 1)->first();
            $top2 = $rankings->where('rank', 2)->first();
            $top3 = $rankings->where('rank', 3)->first();

            $student1 = $top1 ? ($selectedQcm ? $top1->etudiant : $top1) : null;
            $initials1 = $student1 ? strtoupper(substr($student1->prenom, 0, 1) . substr($student1->nom, 0, 1)) : '';

            $student2 = $top2 ? ($selectedQcm ? $top2->etudiant : $top2) : null;
            $initials2 = $student2 ? strtoupper(substr($student2->prenom, 0, 1) . substr($student2->nom, 0, 1)) : '';

            $student3 = $top3 ? ($selectedQcm ? $top3->etudiant : $top3) : null;
            $initials3 = $student3 ? strtoupper(substr($student3->prenom, 0, 1) . substr($student3->nom, 0, 1)) : '';
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end max-w-4xl mx-auto mb-10 pt-6">
            <!-- Second Place -->
            <div class="order-2 md:order-1">
                @if($top2)
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col items-center text-center shadow-sm relative overflow-hidden h-64 justify-center hover:-translate-y-1 transition-all">
                        <div class="absolute top-0 inset-x-0 h-1.5 bg-slate-300"></div>
                        <div class="relative mb-4">
                            <div class="size-20 bg-slate-200 text-slate-600 rounded-2xl flex items-center justify-center text-2xl font-black relative">
                                {{ $initials2 }}
                                <div class="absolute -bottom-1 -right-1 bg-slate-300 text-slate-700 size-6.5 rounded-full border-2 border-white flex items-center justify-center text-[10px] font-black shadow-xs">
                                    2
                                </div>
                            </div>
                        </div>
                        <h4 class="font-black text-slate-900 text-sm leading-tight">
                            {{ $selectedQcm ? $top2->etudiant->nom_complet : $top2->nom_complet }}
                        </h4>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">2ème Place</p>
                        
                        <span class="inline-block px-2 py-0.5 bg-indigo-50/50 text-indigo-600 border border-indigo-100/30 rounded text-[9px] font-black mt-1 uppercase tracking-wider">
                            {{ $selectedQcm ? ($top2->etudiant->classe?->nom ?? '-') : ($top2->classe?->nom ?? '-') }}
                        </span>

                        <div class="mt-3">
                            <span class="text-2xl font-black text-slate-800">
                                @if($selectedQcm)
                                    {{ $top2->score_obtenu }}<span class="text-xs font-normal text-slate-400">/20</span>
                                @else
                                    {{ $top2->average_score }}<span class="text-xs font-normal text-slate-400">/20</span>
                                @endif
                            </span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- First Place -->
            <div class="order-1 md:order-2">
                @if($top1)
                    <div class="bg-white rounded-3xl border border-amber-200 p-8 flex flex-col items-center text-center shadow-md relative overflow-hidden h-72 justify-center hover:-translate-y-1 transition-all scale-105">
                        <div class="absolute top-0 inset-x-0 h-2 bg-amber-500"></div>
                        <div class="absolute top-2 right-2 size-10 bg-amber-50 rounded-full opacity-60"></div>
                        <div class="relative mb-4">
                            <div class="size-20 bg-amber-500 text-white rounded-2xl flex items-center justify-center text-2xl font-black relative">
                                {{ $initials1 }}
                                <div class="absolute -bottom-1 -right-1 bg-amber-500 text-white size-6.5 rounded-full border-2 border-white flex items-center justify-center text-[10px] font-black shadow-xs">
                                    1
                                </div>
                            </div>
                        </div>
                        <h4 class="font-black text-slate-900 text-base leading-tight">
                            {{ $selectedQcm ? $top1->etudiant->nom_complet : $top1->nom_complet }}
                        </h4>
                        
                        <span class="inline-block px-2 py-0.5 bg-indigo-50/50 text-indigo-600 border border-indigo-100/30 rounded text-[9px] font-black mt-1 uppercase tracking-wider">
                            {{ $selectedQcm ? ($top1->etudiant->classe?->nom ?? '-') : ($top1->classe?->nom ?? '-') }}
                        </span>

                        <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mt-2 flex items-center gap-1">
                            LEADER
                        </p>
                        <div class="mt-2">
                            <span class="text-3xl font-black text-amber-600">
                                @if($selectedQcm)
                                    {{ $top1->score_obtenu }}<span class="text-xs font-normal text-amber-400">/20</span>
                                @else
                                    {{ $top1->average_score }}<span class="text-xs font-normal text-amber-400">/20</span>
                                @endif
                            </span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Third Place -->
            <div class="order-3 md:order-3">
                @if($top3)
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col items-center text-center shadow-sm relative overflow-hidden h-64 justify-center hover:-translate-y-1 transition-all">
                        <div class="absolute top-0 inset-x-0 h-1.5 bg-amber-600/60"></div>
                        <div class="relative mb-4">
                            <div class="size-20 bg-amber-700 text-white rounded-2xl flex items-center justify-center text-2xl font-black relative">
                                {{ $initials3 }}
                                <div class="absolute -bottom-1 -right-1 bg-amber-700 text-white size-6.5 rounded-full border-2 border-white flex items-center justify-center text-[10px] font-black shadow-xs">
                                    3
                                </div>
                            </div>
                        </div>
                        <h4 class="font-black text-slate-900 text-sm leading-tight">
                            {{ $selectedQcm ? $top3->etudiant->nom_complet : $top3->nom_complet }}
                        </h4>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">3ème Place</p>
                        
                        <span class="inline-block px-2 py-0.5 bg-indigo-50/50 text-indigo-600 border border-indigo-100/30 rounded text-[9px] font-black mt-1 uppercase tracking-wider">
                            {{ $selectedQcm ? ($top3->etudiant->classe?->nom ?? '-') : ($top3->classe?->nom ?? '-') }}
                        </span>

                        <div class="mt-3">
                            <span class="text-2xl font-black text-slate-800">
                                @if($selectedQcm)
                                    {{ $top3->score_obtenu }}<span class="text-xs font-normal text-slate-400">/20</span>
                                @else
                                    {{ $top3->average_score }}<span class="text-xs font-normal text-slate-400">/20</span>
                                @endif
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Leaderboard Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h4 class="text-xs font-black uppercase tracking-widest text-slate-900">
                @if($selectedQcm)
                    Résultats du QCM
                @else
                    Moyennes Générales de vos Classes
                @endif
            </h4>
            <span class="px-3 py-1 bg-slate-100 rounded-lg text-[10px] font-black text-slate-600 uppercase tracking-wider">
                {{ $rankings->count() }} apprenant(s)
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 w-20">Rang</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Apprenant</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Classe</th>
                        @if($selectedQcm)
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Score</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Statut</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Soumission</th>
                        @else
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">QCMs Complétés</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Taux de Réussite</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Moyenne</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rankings as $row)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4">
                                <div class="size-8 rounded-lg flex items-center justify-center text-xs font-black
                                    @if($row->rank == 1) bg-amber-100 text-amber-800
                                    @elseif($row->rank == 2) bg-slate-200 text-slate-700
                                    @elseif($row->rank == 3) bg-amber-500/20 text-amber-900
                                    @else bg-slate-100 text-slate-500 @endif">
                                    {{ $row->rank }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img class="size-8 rounded-lg border border-slate-100 shadow-sm shrink-0" 
                                         src="https://ui-avatars.com/api/?name={{ urlencode($selectedQcm ? $row->etudiant->nom_complet : $row->nom_complet) }}&background=f1f5f9&color=475569&bold=true" 
                                         alt="Avatar">
                                    <span class="text-xs font-bold text-slate-800">
                                        {{ $selectedQcm ? $row->etudiant->nom_complet : $row->nom_complet }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-slate-100 border border-slate-200/50 rounded-lg text-[10px] font-black text-slate-600 uppercase tracking-wide">
                                    {{ $selectedQcm ? ($row->etudiant->classe?->nom ?? '-') : ($row->classe?->nom ?? '-') }}
                                </span>
                            </td>

                            @if($selectedQcm)
                                <td class="px-6 py-4">
                                    <span class="text-sm font-black text-slate-800">{{ $row->score_obtenu }}/20</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($row->statut === 'reussi')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            <span class="size-1.5 bg-emerald-500 rounded-full"></span>
                                            Réussi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                            <span class="size-1.5 bg-rose-500 rounded-full"></span>
                                            Échoué
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-400 font-bold">
                                    {{ $row->date_fin ? $row->date_fin->format('d/m/Y H:i') : '-' }}
                                </td>
                            @else
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold text-slate-600">{{ $row->completed_qcms }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-800">{{ $row->success_rate }}%</span>
                                        <div class="w-16 bg-slate-100 rounded-full h-1.5 shrink-0">
                                            <div class="h-1.5 rounded-full bg-emerald-500" style="width: {{ $row->success_rate }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-black text-indigo-600">{{ $row->average_score }}/20</span>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center">
                                <div class="size-12 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                    <svg class="size-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M11.25 11.25l.041-.02a.75.75 0 11.518 1.397l-.041.02-.041.02a.75.75 0 01-.518-1.397l.041-.02zm.75 6.75a.75.75 0 100-1.5.75.75 0 000 1.5zM22.5 12c0 5.799-4.701 10.5-10.5 10.5S1.5 17.799 1.5 12 6.201 1.5 12 1.5 22.5 6.201 22.5 12z"/></svg>
                                </div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Aucun résultat</p>
                                <p class="text-xs text-slate-400 mt-1">Aucun étudiant n'a encore complété d'évaluation pour ce filtre.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($rankings instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $rankings->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-center">
                <nav class="flex items-center gap-2">
                    @if($rankings->previousPageUrl())
                        <button type="button" @click.prevent="updateFilters({}, '{{ $rankings->previousPageUrl() }}')" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-primary-50 hover:text-primary-600 transition-all flex items-center justify-center">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                        </button>
                    @endif
                    
                    <span class="px-4 py-2 text-sm font-bold text-slate-500">
                        Page {{ $rankings->currentPage() }} sur {{ $rankings->lastPage() }}
                    </span>
                    
                    @if($rankings->nextPageUrl())
                        <button type="button" @click.prevent="updateFilters({}, '{{ $rankings->nextPageUrl() }}')" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-primary-50 hover:text-primary-600 transition-all flex items-center justify-center">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
                        </button>
                    @endif
                </nav>
            </div>
        @endif
    </div>
    </div>
</div>
@endsection
