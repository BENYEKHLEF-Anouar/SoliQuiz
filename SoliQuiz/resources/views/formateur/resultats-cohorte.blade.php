@extends('layouts.app')

@section('title', 'Supervision des Performances - SoliQuiz')

@section('content')
<div class="reveal active" x-data="resultsFilter({{ Js::from($qcms->map(fn($q) => ['id' => $q->id, 'titre' => $q->titre])->toArray()) }}, {{ Js::from($classes->pluck('nom')->toArray()) }}, {{ Js::from($etudiants) }})">
    
    <!-- Navigation -->
    <div class="mb-6">
        <a href="javascript:history.back()" 
           class="inline-flex items-center gap-2 text-slate-400 hover:text-primary-600 transition-colors group">
            <div class="size-8 rounded-xl bg-white border border-slate-100 flex items-center justify-center group-hover:border-primary-200 group-hover:bg-primary-50 transition-all shadow-sm">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </div>
            <span class="text-[10px] font-black uppercase tracking-widest">Retour</span>
        </a>
    </div>
    <!-- Header Section -->
    <div class="relative z-30 mb-10">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-8 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-4 mb-3">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Espace Formateur</span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-900">Analytique</span>
                </div>
                <h3 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                    Supervision des Résultats
                </h3>
                <p class="mt-2 text-sm text-slate-500 max-w-xl">
                    Consultez et exportez les performances de vos cohortes en temps réel.
                </p>
            </div>
            
            <!-- Export Actions Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.away="open = false"
                        class="h-14 px-8 bg-emerald-600 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] italic shadow-2xl shadow-emerald-600/20 hover:bg-emerald-500 transition-all flex items-center gap-3 group">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Exporter
                    <svg class="size-4 text-emerald-200 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute right-0 mt-2 w-56 bg-white border border-slate-100 rounded-2xl shadow-premium overflow-hidden py-1 z-50">
                                       <button @click="exportData('csv')"
                       class="w-full px-5 py-4 text-left text-[10px] font-black uppercase tracking-widest text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 transition-colors flex items-center gap-3">
                        <div class="size-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center font-mono">CSV</div>
                        Format CSV (.csv)
                    </button>

                    <button @click="exportData('excel')"
                       class="w-full px-5 py-4 text-left text-[10px] font-black uppercase tracking-widest text-slate-600 hover:bg-teal-50 hover:text-teal-600 transition-colors flex items-center gap-3">
                        <div class="size-8 rounded-lg bg-teal-100 text-teal-600 flex items-center justify-center font-mono">XLS</div>
                        Format Excel (.xls)
                    </button>

                    <button @click="exportData('pdf')"
                       class="w-full px-5 py-4 text-left text-[10px] font-black uppercase tracking-widest text-slate-600 hover:bg-rose-50 hover:text-rose-600 transition-colors flex items-center gap-3">
                        <div class="size-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center font-mono">PDF</div>
                        Format PDF (.pdf)
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters Row -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4 items-center">
            <!-- Search / Student Dropdown -->
            <div x-data="{ open: false }" class="relative w-full sm:w-96">
                <div class="relative group">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-slate-300 pointer-events-none group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" x-model="search" @focus="open = true" @input.debounce.300ms="applyFilters(); open = true"
                           placeholder="Rechercher un étudiant..."
                           class="w-full h-14 bg-white border-2 border-slate-100 rounded-2xl pl-12 pr-14 text-sm font-bold text-slate-900 placeholder:text-slate-300 focus:bg-white focus:border-primary-500 outline-none transition-all shadow-xs group-hover:shadow-sm">
                    
                    <!-- Live Search Loader -->
                    <div x-show="loading" 
                         class="absolute right-12 top-1/2 -translate-y-1/2"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-50"
                         x-transition:enter-end="opacity-100 scale-100"
                         style="display: none;">
                        <div class="size-4 border-2 border-primary-200 border-t-primary-500 rounded-full animate-spin"></div>
                    </div>

                    <button x-show="search && !loading" @click="search = ''; applyFilters()" class="absolute right-12 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    <button @click="open = !open" type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500 transition-transform" :class="open ? 'rotate-180' : ''">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>
                
                <div x-show="open" @click.away="open = false" x-transition class="absolute z-60 w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden" style="display: none;">
                    <div class="max-h-60 overflow-y-auto custom-scrollbar">
                        <template x-for="name in studentList.filter(n => !search || n.toLowerCase().includes(search.toLowerCase()))" :key="name">
                            <button type="button" @click="search = name; open = false; applyFilters()" class="w-full px-5 py-4 text-left text-[11px] font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50 italic flex items-center justify-between">
                                <span x-text="name"></span>
                                <span x-show="search === name" class="size-2 bg-primary-500 rounded-full"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
            
            <!-- QCM Filter Dropdown (Restored & Scrollable) -->
            <div x-data="{ open: false }" class="relative w-full sm:w-80">
                <button type="button" @click="open = !open" @click.away="open = false"
                        class="w-full h-14 px-5 bg-white border-2 border-slate-100 rounded-2xl flex items-center justify-between text-[11px] font-black uppercase tracking-widest text-slate-600 hover:border-primary-300 transition-all shadow-xs">
                    <span class="truncate mr-2" x-text="selectedQcmTitle || 'Tous les QCM'"></span>
                    <svg class="size-4 text-slate-400 transition-transform shrink-0" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-transition class="absolute z-50 w-full mt-2 bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden" style="display: none;">
                    <div class="max-h-64 overflow-y-auto custom-scrollbar">
                        <button type="button" @click="selectedQcmId = ''; selectedQcmTitle = ''; open = false; applyFilters()" class="w-full px-5 py-4 text-left text-[11px] font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50 border-b border-slate-100">
                            Tous les QCM <span x-show="!selectedQcmId" class="size-2 bg-primary-500 rounded-full inline-block ml-2"></span>
                        </button>
                        <template x-for="qcm in qcmList" :key="qcm.id">
                            <button type="button" @click="selectedQcmId = qcm.id; selectedQcmTitle = qcm.titre; open = false; applyFilters()" class="w-full px-5 py-4 text-left text-[11px] font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50">
                                <span class="truncate" x-text="qcm.titre"></span>
                                <span x-show="selectedQcmId == qcm.id" class="size-2 bg-primary-500 rounded-full inline-block ml-2"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
            
            <!-- Clear -->
            <button x-show="hasFilters" @click="clearFilters()" class="size-14 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center hover:bg-rose-100 transition-all shadow-xs">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <!-- Results Display -->
    <div class="relative mt-10 min-h-[400px]">
        <!-- Loading Overlay -->
        <div x-show="loading"
            class="absolute inset-0 bg-white/40 backdrop-blur-[2px] z-20 flex flex-col items-center justify-center rounded-[3rem] min-h-[400px]"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            style="display: none;">
            <div class="flex flex-col items-center gap-3 scale-90">
                <div class="size-10 border-4 border-slate-100 border-t-primary-500 rounded-full animate-spin shadow-sm"></div>
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] animate-pulse">Filtrage en cours...
                </p>
            </div>
        </div>

        <div class="space-y-8" :class="loading ? 'opacity-50 pointer-events-none transition-opacity duration-300' : 'transition-opacity duration-300'">
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
        
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden"
             x-show="!selectedQcmId || selectedQcmId == {{ $qcm->id }}"
             x-data="{ 
                 tentatives: {{ Js::from($allTentatives) }},
                 get filtered() {
                     return this.tentatives.filter(t => {
                         return !search || t.etudiant_nom.toLowerCase().includes(search.toLowerCase());
                     });
                 },
                 get stats() {
                     const scores = this.filtered.map(t => parseFloat(t.score)).filter(s => !isNaN(s) && s !== null);
                     const count = scores.length;
                     const sum = scores.reduce((a, b) => a + b, 0);
                     const avg = count > 0 ? (sum / count).toFixed(1) : 0;
                     const reussis = this.filtered.filter(t => t.statut === 'reussi').length;
                     const rate = count > 0 ? Math.round((reussis / count) * 100) : 0;
                     return { avg, rate, count };
                 }
             }">
            
            <!-- QCM Header -->
            <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-5">
                    <div class="size-12 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-300">
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 12h6m-6 4h6m-2-8a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-slate-900 uppercase tracking-tight leading-none">{{ $qcm->titre }}</h3>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mt-2">Seuil de réussite: {{ $qcm->score_reussite }}/20</p>
                    </div>
                </div>
                <div class="flex items-center gap-8 bg-white p-4 rounded-2xl border border-slate-100 shadow-xs">
                    <div class="text-center">
                        <p class="text-xl font-black text-slate-900 leading-none" x-text="stats.avg"></p>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-1">Moyenne</p>
                    </div>
                    <div class="w-px h-8 bg-slate-100"></div>
                    <div class="text-center">
                        <p class="text-xl font-black text-emerald-500 leading-none" x-text="stats.rate + '%'"></p>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-1">Réussite</p>
                    </div>
                    <div class="w-px h-8 bg-slate-100"></div>
                    <div class="text-center">
                        <p class="text-xl font-black text-slate-600 leading-none" x-text="stats.count"></p>
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-1">Passages</p>
                    </div>
                    <div class="w-px h-8 bg-slate-200 ml-2"></div>
                    
                    <!-- Individual QCM Export Dropdown -->
                    <div x-data="{ open: false }" class="relative ml-2">
                        <button @click="open = !open" @click.away="open = false"
                           class="size-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-600 transition-all"
                           title="Exporter ce QCM">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </button>

                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-40 bg-white border border-slate-100 rounded-xl shadow-xl z-50 py-1 overflow-hidden"
                             style="display: none;">
                            
                            <button @click="window.location.href = '{{ route('formateur.resultats.export', ['qcm' => $qcm->id, 'format' => 'csv']) }}'" 
                               class="w-full px-4 py-3 text-left text-[9px] font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-colors flex items-center gap-2">
                                <div class="size-5 rounded bg-emerald-100 text-emerald-600 flex items-center justify-center text-[7px] font-mono">CSV</div>
                                CSV
                            </button>

                            <button @click="window.location.href = '{{ route('formateur.resultats.export', ['qcm' => $qcm->id, 'format' => 'excel']) }}'" 
                               class="w-full px-4 py-3 text-left text-[9px] font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-colors flex items-center gap-2">
                                <div class="size-5 rounded bg-teal-100 text-teal-600 flex items-center justify-center text-[7px] font-mono">XLS</div>
                                Excel
                            </button>

                            <button @click="window.location.href = '{{ route('formateur.resultats.export', ['qcm' => $qcm->id, 'format' => 'pdf']) }}'" 
                               class="w-full px-4 py-3 text-left text-[9px] font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-colors flex items-center gap-2">
                                <div class="size-5 rounded bg-rose-100 text-rose-600 flex items-center justify-center text-[7px] font-mono">PDF</div>
                                PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Students Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-slate-50 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                            <th class="px-8 py-5">Apprenant</th>
                            <th class="px-8 py-5">Classe</th>
                            <th class="px-8 py-5">Date</th>
                            <th class="px-8 py-5 text-center">Statut</th>
                            <th class="px-8 py-5 text-right">Note</th>
                            <th class="px-8 py-5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <template x-for="t in filtered" :key="t.id">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-5">
                                    <p class="text-sm font-bold text-slate-800" x-text="t.etudiant_nom"></p>
                                </td>
                                <td class="px-8 py-5">
                                    <p class="text-xs font-medium text-slate-500 italic" x-text="t.etudiant_classe"></p>
                                </td>
                                <td class="px-8 py-5">
                                    <p class="text-xs text-slate-400 font-bold" x-text="t.date || '-'"></p>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <template x-if="t.statut === 'reussi'">
                                        <span class="inline-flex px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-widest">Validé</span>
                                    </template>
                                    <template x-if="t.statut === 'echoue'">
                                        <span class="inline-flex px-3 py-1 rounded-full bg-rose-50 text-rose-500 text-[9px] font-black uppercase tracking-widest">Échec</span>
                                    </template>
                                    <template x-if="t.statut === 'abandonne'">
                                        <span class="inline-flex px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-[9px] font-black uppercase tracking-widest">Abandon</span>
                                    </template>
                                    <template x-if="!t.statut || t.statut === 'en_cours'">
                                        <span class="inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-[9px] font-black uppercase tracking-widest italic">En cours</span>
                                    </template>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <p class="text-sm font-black" :class="t.score >= {{ $qcm->score_reussite }} ? 'text-slate-900' : 'text-slate-300'" x-text="t.score !== null ? t.score + '/20' : '-'"></p>
                                </td>
                                 <td class="px-8 py-5 text-right relative">
                                     <template x-if="t.score !== null">
                                         <div x-data="{ open: false }" class="inline-block relative">
                                             <button @click="open = !open" @click.away="open = false"
                                                class="inline-flex size-8 rounded-lg bg-slate-50 text-slate-400 items-center justify-center hover:bg-primary-500 hover:text-white transition-all shadow-sm"
                                                title="Exporter le bilan">
                                                 <svg class="size-4 transition-transform duration-300" :class="open ? 'rotate-180 text-primary-500' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                             </button>

                                             <!-- Beautiful Horizontal Popout Menu -->
                                             <div x-show="open" 
                                                  x-transition:enter="transition ease-out duration-200"
                                                  x-transition:enter-start="opacity-0 -translate-x-4 scale-95"
                                                  x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                                                  class="absolute right-full top-1/2 -translate-y-1/2 mr-3 w-48 bg-white border border-slate-100 rounded-xl shadow-xl z-50 p-1.5 flex items-center gap-1"
                                                  style="display: none;">
                                                 
                                                 <button @click="window.location.href = '/formateur/resultats/tentative/' + t.id + '/export?format=pdf'" 
                                                    class="flex-1 py-1.5 text-center text-[8px] font-black uppercase tracking-wider text-rose-600 bg-rose-50/50 hover:bg-rose-500 hover:text-white rounded-md transition-all">
                                                     PDF
                                                 </button>

                                                 <button @click="window.location.href = '/formateur/resultats/tentative/' + t.id + '/export?format=excel'" 
                                                    class="flex-1 py-1.5 text-center text-[8px] font-black uppercase tracking-wider text-teal-600 bg-teal-50/50 hover:bg-teal-500 hover:text-white rounded-md transition-all">
                                                     XLS
                                                 </button>

                                                 <button @click="window.location.href = '/formateur/resultats/tentative/' + t.id + '/export?format=csv'" 
                                                    class="flex-1 py-1.5 text-center text-[8px] font-black uppercase tracking-wider text-emerald-600 bg-emerald-50/50 hover:bg-emerald-500 hover:text-white rounded-md transition-all">
                                                     CSV
                                                 </button>
                                             </div>
                                         </div>
                                     </template>
                                 </td>
                            </tr>
                        </template>
                        <tr x-show="filtered.length === 0">
                            <td colspan="6" class="px-8 py-12 text-center text-slate-400 text-xs italic font-bold">
                                Aucun matching pour cette sélection
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-[2rem] border border-slate-100 p-20 text-center">
            <div class="size-20 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-6 text-slate-200">
                <svg class="size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m-2-8a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <p class="text-slate-400 font-black uppercase tracking-widest italic">Aucun résultat consolidé</p>
        </div>
        @endforelse
    </div>
</div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('resultsFilter', (qcmList, classeList, studentList) => ({
        search: '',
        selectedQcmId: '',
        selectedQcmTitle: '',
        selectedClasse: '',
        loading: false,
        qcmList: qcmList,
        classeList: classeList,
        studentList: studentList,
        
        init() {
            const params = new URLSearchParams(window.location.search);
            this.search = params.get('q') || '';
            this.selectedClasse = params.get('classe') || '';
            const qid = params.get('qcm');
            if (qid) {
                const found = this.qcmList.find(q => q.id == qid);
                if (found) {
                    this.selectedQcmId = found.id;
                    this.selectedQcmTitle = found.titre;
                }
            }
        },
        
        get hasFilters() {
            return this.search || this.selectedQcmId;
        },
        
        applyFilters() {
            this.loading = true;
            const url = new URL(window.location);
            if (this.search) url.searchParams.set('q', this.search);
            else url.searchParams.delete('q');
            if (this.selectedQcmId) url.searchParams.set('qcm', this.selectedQcmId);
            else url.searchParams.delete('qcm');
            history.pushState({}, '', url);
            
            setTimeout(() => {
                this.loading = false;
            }, 300);
        },
        
        clearFilters() {
            this.search = '';
            this.selectedQcmId = '';
            this.selectedQcmTitle = '';
            const url = new URL(window.location);
            url.searchParams.delete('q');
            url.searchParams.delete('qcm');
            history.pushState({}, '', url);
        },
 
        exportData(format) {
            const baseUrl = "{{ route('formateur.resultats.export') }}";
            const params = new URLSearchParams({
                format: format,
                q: this.search,
                qcm: this.selectedQcmId,
                classe: this.selectedClasse
            });
            window.location.href = `${baseUrl}?${params.toString()}`;
        }
    }));
});
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
@endsection