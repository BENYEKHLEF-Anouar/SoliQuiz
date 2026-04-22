@extends('layouts.app')

@section('title', 'Supervision des Performances - SoliQuiz')

@section('content')
<div class="reveal active" x-data="resultsFilter({{ Js::from($qcms->pluck('id', 'titre')->toArray()) }}, {{ Js::from($classes->pluck('nom')->toArray()) }})">
    
    <!-- Header -->
    <div class="mb-10">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <h1 class="text-2xl font-black text-slate-900 uppercase italic">Résultats</h1>
                <p class="text-sm text-slate-400 mt-1">Analysez les performances de vos cohortes</p>
            </div>
            
            <!-- Search & Filters -->
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Search -->
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-slate-300 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" x-model="search" @input.debounce.300ms="applyFilters()"
                           placeholder="Rechercher un étudiant..."
                           class="w-full h-12 bg-white border-2 border-slate-100 rounded-xl pl-12 pr-10 text-sm font-bold text-slate-900 placeholder:text-slate-300 focus:bg-white focus:border-primary-500 outline-none transition-all">
                    <button x-show="search" @click="search = ''; applyFilters()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <!-- QCM Filter -->
                <div x-data="{ open: false }" class="relative w-full sm:w-48">
                    <button type="button" @click="open = !open" @click.away="open = false"
                            class="w-full h-12 px-4 bg-white border-2 border-slate-100 rounded-xl flex items-center justify-between text-sm font-bold text-slate-600 hover:border-primary-300">
                        <span x-text="selectedQcm || 'Tous les QCM'"></span>
                        <svg class="size-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="absolute z-50 w-full mt-2 bg-white border-2 border-slate-100 rounded-xl shadow-lg overflow-hidden" style="display: none;">
                        <button type="button" @click="selectedQcm = ''; open = false; applyFilters()" class="w-full px-4 py-3 text-left text-sm font-bold text-slate-600 hover:bg-slate-50">
                            Tous les QCM <span x-show="!selectedQcm" class="size-2 bg-primary-500 rounded-full inline-block ml-2"></span>
                        </button>
                        <template x-for="(id, titre) in qcmList" :key="id">
                            <button type="button" @click="selectedQcm = titre; open = false; applyFilters()" class="w-full px-4 py-3 text-left text-sm font-bold text-slate-600 hover:bg-slate-50">
                                <span x-text="titre"></span>
                                <span x-show="selectedQcm === titre" class="size-2 bg-primary-500 rounded-full inline-block ml-2"></span>
                            </button>
                        </template>
                    </div>
                </div>
                
                <!-- Classe Filter -->
                <div x-data="{ open: false }" class="relative w-full sm:w-44">
                    <button type="button" @click="open = !open" @click.away="open = false"
                            class="w-full h-12 px-4 bg-white border-2 border-slate-100 rounded-xl flex items-center justify-between text-sm font-bold text-slate-600 hover:border-primary-300">
                        <span x-text="selectedClasse || 'Toutes'"></span>
                        <svg class="size-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition class="absolute z-50 w-full mt-2 bg-white border-2 border-slate-100 rounded-xl shadow-lg overflow-hidden" style="display: none;">
                        <button type="button" @click="selectedClasse = ''; open = false; applyFilters()" class="w-full px-4 py-3 text-left text-sm font-bold text-slate-600 hover:bg-slate-50">
                            Toutes <span x-show="!selectedClasse" class="size-2 bg-primary-500 rounded-full inline-block ml-2"></span>
                        </button>
                        <template x-for="classe in classeList" :key="classe">
                            <button type="button" @click="selectedClasse = classe; open = false; applyFilters()" class="w-full px-4 py-3 text-left text-sm font-bold text-slate-600 hover:bg-slate-50">
                                <span x-text="classe"></span>
                                <span x-show="selectedClasse === classe" class="size-2 bg-primary-500 rounded-full inline-block ml-2"></span>
                            </button>
                        </template>
                    </div>
                </div>
                
                <!-- Clear -->
                <button x-show="hasFilters" @click="clearFilters()" class="h-12 px-4 bg-rose-50 text-rose-500 rounded-xl font-bold text-xs hover:bg-rose-100">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Results Info -->
    <div class="mb-6 flex items-center justify-between">
        <p class="text-sm font-bold text-slate-500">
            <span x-text="filteredCount"></span> résultat(s)
        </p>
    </div>

    <!-- QCM Results (Simplified Display) -->
    <div class="space-y-8">
        @forelse($qcms as $qcm)
        @php
            $allTentatives = $qcm->tentatives->map(function($t) {
                return [
                    'id' => $t->id,
                    'etudiant_nom' => $t->etudiant?->nom_complet ?? 'Unknown',
                    'etudiant_classe' => $t->etudiant?->classe?->nom ?? 'Hors cohorte',
                    'score' => $t->score_obtenu,
                    'statut' => $t->statut,
                    'date' => $t->date_debut?->format('d M Y'),
                ];
            });
        @endphp
        
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden"
             x-data="{ 
                 tentatives: {{ Js::from($allTentatives) }},
                 get filtered() {
                     return this.tentatives.filter(t => {
                         const matchesSearch = !search || t.etudiant_nom.toLowerCase().includes(search.toLowerCase());
                         const matchesClasse = !selectedClasse || t.etudiant_classe === selectedClasse;
                         return matchesSearch && matchesClasse;
                     });
                 },
                 get stats() {
                     const scores = this.filtered.map(t => t.score).filter(s => s !== null);
                     const count = scores.length;
                     const sum = scores.reduce((a, b) => a + b, 0);
                     const avg = count > 0 ? (sum / count).toFixed(1) : 0;
                     const reussis = this.filtered.filter(t => t.statut === 'reussi').length;
                     const rate = count > 0 ? Math.round((reussis / count) * 100) : 0;
                     return { avg, rate, count };
                 }
             }">
            
            <!-- QCM Header -->
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="size-10 rounded-lg bg-slate-200 flex items-center justify-center text-slate-600">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 12h6m-6 4h6m-2-8a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-800 uppercase italic">{{ $qcm->titre }}</h3>
                        <p class="text-xs text-slate-400">Seuil: {{ $qcm->score_reussite }}/20</p>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <div class="text-center">
                        <p class="text-lg font-black text-slate-800" x-text="stats.avg"></p>
                        <p class="text-[8px] font-black text-slate-400 uppercase">Moyenne</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-black text-emerald-600" x-text="stats.rate + '%'"></p>
                        <p class="text-[8px] font-black text-slate-400 uppercase">Réussite</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-black text-slate-600" x-text="stats.count"></p>
                        <p class="text-[8px] font-black text-slate-400 uppercase">Passages</p>
                    </div>
                </div>
            </div>
            
            <!-- Students Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-slate-100 text-[8px] font-black text-slate-400 uppercase">
                            <th class="px-6 py-3">Étudiant</th>
                            <th class="px-6 py-3">Classe</th>
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3 text-center">Statut</th>
                            <th class="px-6 py-3 text-right">Score</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <template x-for="t in filtered" :key="t.id">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-slate-800" x-text="t.etudiant_nom"></p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs text-slate-500" x-text="t.etudiant_classe"></p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs text-slate-400" x-text="t.date || '-'"></p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <template x-if="t.statut === 'reussi'">
                                        <span class="inline-flex px-2 py-1 rounded-lg bg-emerald-50 text-emerald-600 text-[8px] font-black uppercase">Réussi</span>
                                    </template>
                                    <template x-if="t.statut === 'echoue'">
                                        <span class="inline-flex px-2 py-1 rounded-lg bg-rose-50 text-rose-500 text-[8px] font-black uppercase">Échoué</span>
                                    </template>
                                    <template x-if="t.statut === 'abandonne'">
                                        <span class="inline-flex px-2 py-1 rounded-lg bg-amber-50 text-amber-600 text-[8px] font-black uppercase">Abandonné</span>
                                    </template>
                                    <template x-if="!t.statut">
                                        <span class="inline-flex px-2 py-1 rounded-lg bg-slate-100 text-slate-500 text-[8px] font-black uppercase">En cours</span>
                                    </template>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <p class="text-sm font-black" :class="t.score >= {{ $qcm->score_reussite }} ? 'text-slate-800' : 'text-slate-300'" x-text="t.score !== null ? t.score + '/20' : '-'"></p>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filtered.length === 0">
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400 text-sm">
                                Aucun résultat pour cette sélection
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-slate-100 p-12 text-center">
            <p class="text-slate-400 font-bold">Aucun QCM avec des résultats</p>
        </div>
        @endforelse
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('resultsFilter', (qcmList, classeList) => ({
        search: '',
        selectedQcm: '',
        selectedClasse: '',
        
        init() {
            const params = new URLSearchParams(window.location.search);
            this.search = params.get('q') || '';
            this.selectedClasse = params.get('classe') || '';
        },
        
        get hasFilters() {
            return this.search || this.selectedQcm || this.selectedClasse;
        },
        
        get filteredCount() {
            return this.search || this.selectedClasse ? '...' : '{{ $qcms->sum(fn($q) => $q->tentatives->count()) }}';
        },
        
        applyFilters() {
            const url = new URL(window.location);
            if (this.search) url.searchParams.set('q', this.search);
            else url.searchParams.delete('q');
            if (this.selectedClasse) url.searchParams.set('classe', this.selectedClasse);
            else url.searchParams.delete('classe');
            history.pushState({}, '', url);
        },
        
        clearFilters() {
            this.search = '';
            this.selectedQcm = '';
            this.selectedClasse = '';
            const url = new URL(window.location);
            url.searchParams.delete('q');
            url.searchParams.delete('classe');
            history.pushState({}, '', url);
        }
    }));
});
</script>
@endsection