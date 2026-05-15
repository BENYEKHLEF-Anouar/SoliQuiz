@extends('layouts.app')

@section('title', 'Bibliothèque - SoliQuiz')

@section('content')
<div class="space-y-10 reveal active" 
     x-data="studentLibrary({ 
         enCours: {{ Js::from($enCours->values()) }}, 
         aFaire: {{ Js::from($aFaire->values()) }}, 
         termines: {{ Js::from($termines->values()) }}, 
         search: '{{ $search ?? '' }}',
         uaId: '',
         uaLabel: 'Toutes les unités',
         statut: '',
         statutLabel: 'Tous les scores'
     })">
    <!-- Header Section -->
    <div class="relative z-30 mb-10">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-8 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-4 mb-3">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Mon Espace</span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-900">Bibliothèque</span>
                </div>
                <h3 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                    Catalogue des <span>évaluations</span>
                </h3>
                <p class="mt-2 text-sm text-slate-500 max-w-xl">
                    Accédez à vos tests assignés, reprenez vos sessions en cours et consultez vos résultats.
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="bg-white px-6 py-3 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Moyenne</p>
                        <p class="text-lg font-black text-slate-900 leading-none">{{ $moyenne }}/20</p>
                    </div>
                    <div class="w-px h-8 bg-slate-100"></div>
                    <div class="text-right">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Terminés</p>
                        <p class="text-lg font-black text-emerald-500 leading-none">{{ $termines->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Row -->
        <div class="mt-8 flex flex-col md:flex-row gap-4 items-center">
            <!-- Search -->
            <div class="flex-1 relative group w-full">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-slate-300 pointer-events-none group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" x-model="search" @input.debounce.300ms="applyFilters()"
                       placeholder="Rechercher une évaluation..."
                       class="w-full h-14 bg-white border-2 border-slate-100 rounded-2xl pl-12 pr-14 text-sm font-bold text-slate-900 placeholder:text-slate-300 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 outline-none transition-all shadow-xs group-hover:shadow-sm">
                
                <div x-show="loading" class="absolute right-5 top-1/2 -translate-y-1/2" style="display: none;">
                    <div class="size-4 border-2 border-primary-200 border-t-primary-500 rounded-full animate-spin"></div>
                </div>
            </div>

            <!-- UA Filter -->
            <div x-data="{ open: false }" class="relative w-full md:w-64">
                <button @click="open = !open" @click.away="open = false"
                        class="w-full h-14 px-5 bg-white border-2 border-slate-100 rounded-2xl flex items-center justify-between text-sm font-bold text-slate-600 hover:border-primary-300 transition-all">
                    <span class="truncate pr-2" x-text="uaLabel"></span>
                    <svg class="size-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" style="display: none;" class="absolute top-full left-0 w-full mt-2 bg-white border border-slate-100 rounded-2xl shadow-xl z-50 p-2 max-h-60 overflow-y-auto">
                    <button @click="uaId = ''; uaLabel = 'Toutes les unités'; open = false; applyFilters()" class="w-full px-4 py-2 text-left text-xs font-bold text-slate-500 hover:bg-slate-50 rounded-lg">Toutes les unités</button>
                    @foreach($unites as $unite)
                        <button @click="uaId = '{{ $unite->id }}'; uaLabel = '{{ $unite->nom }}'; open = false; applyFilters()" class="w-full px-4 py-2 text-left text-xs font-bold text-slate-900 hover:bg-primary-50 hover:text-primary-600 rounded-lg">{{ $unite->nom }}</button>
                    @endforeach
                </div>
            </div>

            <!-- Status Filter -->
            <div x-data="{ open: false }" class="relative w-full md:w-56">
                <button @click="open = !open" @click.away="open = false"
                        class="w-full h-14 px-5 bg-white border-2 border-slate-100 rounded-2xl flex items-center justify-between text-sm font-bold text-slate-600 hover:border-primary-300 transition-all">
                    <span x-text="statutLabel"></span>
                    <svg class="size-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" style="display: none;" class="absolute top-full left-0 w-full mt-2 bg-white border border-slate-100 rounded-2xl shadow-xl z-50 p-2">
                    <button @click="statut = ''; statutLabel = 'Tous les scores'; open = false; applyFilters()" class="w-full px-4 py-2 text-left text-xs font-bold text-slate-500 hover:bg-slate-50 rounded-lg">Tous les scores</button>
                    <button @click="statut = 'reussi'; statutLabel = 'Réussi (70%+)'; open = false; applyFilters()" class="w-full px-4 py-2 text-left text-xs font-bold text-emerald-600 hover:bg-emerald-50 rounded-lg">Réussi</button>
                    <button @click="statut = 'echoue'; statutLabel = 'Échoué (< 70%)'; open = false; applyFilters()" class="w-full px-4 py-2 text-left text-xs font-bold text-rose-600 hover:bg-rose-50 rounded-lg">Échoué</button>
                    <button @click="statut = 'a_faire'; statutLabel = 'À faire'; open = false; applyFilters()" class="w-full px-4 py-2 text-left text-xs font-bold text-slate-600 hover:bg-slate-50 rounded-lg">À faire</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
        <!-- Dashboard Main Stat -->
        <div class="bg-slate-900 p-5 sm:px-8 rounded-3xl shadow-xl relative overflow-hidden group lg:col-span-2 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="absolute -right-8 -top-8 size-48 bg-primary-500/10 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
            
            <div class="relative z-10 flex-1 flex items-center gap-6">
                <div>
                    <h3 class="text-[9px] font-black text-primary-400 uppercase tracking-[0.2em] mb-1.5 flex items-center gap-1.5">
                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        Ma Performance
                    </h3>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-black text-white font-heading tracking-tight">{{ $moyenne }}<span class="text-primary-500 text-2xl">/20</span></span>
                    </div>
                </div>
                <div class="h-10 w-px bg-white/10 hidden sm:block"></div>
                <div class="hidden sm:block">
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest max-w-[120px] leading-tight">Score Moyen Global Actuel</p>
                </div>
            </div>
            
            <div class="relative z-10 flex items-center md:justify-end gap-3 pt-4 md:pt-0 border-t md:border-t-0 border-white/5">
                <div class="text-left md:text-right">
                    <span class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-0.5">Évaluations</span>
                    <span class="text-2xl font-black text-white font-heading">{{ $termines->count() }} <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">achevées</span></span>
                </div>
            </div>
        </div>

        <!-- Pending Stat -->
        <div class="bg-white p-5 sm:px-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 flex items-center relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 size-20 bg-primary-50 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-500 group-hover:scale-150"></div>
            <div class="relative z-10 w-full flex items-center justify-between">
                <div>
                    <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1 border-l-2 border-primary-500 pl-2">À Réaliser</h3>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-black text-slate-900 font-heading leading-none">{{ $aFaire->count() }}</span>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">QCM Restants</span>
                    </div>
                </div>
                <!-- <div class="size-10 rounded-xl bg-primary-50 text-primary-500 flex items-center justify-center group-hover:bg-primary-500 group-hover:text-white transition-colors">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-linecap="round"/></svg>
                </div> -->
            </div>
        </div>
    </div>

    <!-- Dynamic List -->
    <section class="space-y-8 relative min-h-[400px]">
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
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] animate-pulse">Chargement du catalogue...
                </p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-[2.5rem] shadow-[0_8px_30px_-4px_rgba(0,0,0,0.04)] border border-slate-100 transition-all duration-300"
             x-show="!loading"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            <div class="bg-slate-50/50 rounded-[2rem] overflow-hidden">
                <!-- Empty State -->
                <div x-show="enCours.length === 0 && aFaire.length === 0 && termines.length === 0 && !loading" 
                     class="text-center py-20" style="display: none;">
                    <div class="size-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 shadow-inner">
                        <svg class="size-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-linecap="round"/></svg>
                    </div>
                    <h4 class="text-xl font-heading font-black text-slate-900 mb-2">Catalogue vide</h4>
                    <p class="text-slate-400 font-medium italic text-sm">Aucune évaluation ne correspond à votre recherche.</p>
                </div>

                {{-- Priority Items: En Cours / À Faire --}}
                <div class="p-8 border-b border-slate-100" x-show="enCours.length > 0 || aFaire.length > 0">
                    <div class="grid grid-cols-1 gap-4">
                        <template x-for="qcm in [...enCours, ...aFaire]" :key="qcm.id">
                            <div class="group relative bg-white border border-slate-200 hover:border-primary-300 rounded-[1.5rem] p-4 sm:p-6 transition-all duration-500 hover:shadow-lg flex flex-col sm:flex-row sm:items-center justify-between gap-6 overflow-hidden">
                                
                                <!-- Background accent -->
                                <div class="absolute -right-10 -top-10 size-40 bg-gradient-to-br from-primary-100 to-transparent rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>

                                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center gap-5 flex-1">
                                    <div class="size-14 rounded-2xl flex items-center justify-center shrink-0"
                                         :class="qcm.etat === 'en_cours' ? 'bg-gradient-to-br from-primary-500 to-primary-600 text-white shadow-lg shadow-primary-500/30' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-500 transition-colors'">
                                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                    
                                    <div>
                                        <div class="flex items-center gap-2 mb-1.5">
                                            <h3 class="text-lg font-heading font-black text-slate-900 tracking-tight leading-snug group-hover:text-primary-600 transition-colors" x-text="qcm.titre"></h3>
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-widest"
                                                  :class="qcm.etat === 'en_cours' ? 'bg-primary-50 text-primary-600 border border-primary-100' : 'bg-slate-100 text-slate-500'">
                                                <span class="size-1.5 rounded-full" :class="qcm.etat === 'en_cours' ? 'bg-primary-500 animate-pulse' : 'bg-slate-400'"></span>
                                                <span x-text="qcm.etat === 'en_cours' ? 'En cours' : 'Prêt'"></span>
                                            </span>
                                        </div>
                                        
                                        <div class="flex flex-wrap items-center gap-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                            <span class="truncate max-w-[200px] sm:max-w-none" x-text="qcm.unite_nom"></span>
                                            <span class="size-1 rounded-full bg-slate-300"></span>
                                            <span class="flex items-center gap-1"><svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg> <span x-text="qcm.duree_minutes"></span> min</span>
                                            <span class="size-1 rounded-full bg-slate-300"></span>
                                            <span class="flex items-center gap-1"><svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg> <span x-text="qcm.questions_count"></span> Qst</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="relative z-10 flex items-center justify-between sm:justify-end gap-6 sm:w-auto w-full border-t sm:border-t-0 border-slate-100 pt-4 sm:pt-0 mt-2 sm:mt-0 shrink-0">
                                    <div class="text-left sm:text-right hidden lg:block">
                                        <span class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-0.5">Objectif</span>
                                        <span class="text-sm font-black text-slate-700" x-text="qcm.score_reussite + '/20'"></span>
                                    </div>
                                    <div class="h-8 w-px bg-slate-200 hidden lg:block"></div>
                                    <button @click="window.location.href = qcm.url_passation"
                                        class="h-12 px-6 rounded-xl font-black text-[10px] uppercase tracking-[0.2em] transition-all duration-300 flex items-center justify-center gap-2 active:scale-[0.98] w-full sm:w-auto"
                                        :class="qcm.etat === 'en_cours' ? 'bg-slate-900 text-white hover:bg-slate-800 shadow-lg shadow-slate-900/20' : 'bg-primary-50 text-primary-600 hover:bg-primary-600 hover:text-white'">
                                        <span x-text="qcm.etat === 'en_cours' ? 'Reprendre' : 'Démarrer'"></span>
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Completed Items --}}
                <div class="p-8" x-show="termines.length > 0">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-3">
                            <span class="size-2 rounded-full bg-slate-300"></span>
                            Évaluations Terminées
                        </h3>
                        <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-md" x-text="termines.length + ' test(s)'"></span>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <template x-for="qcm in termines" :key="qcm.id">
                            <div @click="window.location.href = qcm.url_resultats" class="group block bg-white border border-slate-200 hover:border-slate-300 rounded-[1.5rem] p-4 sm:p-6 transition-all duration-300 hover:shadow-lg hover:shadow-slate-200/50 cursor-pointer">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="flex items-center gap-5">
                                        <div class="size-12 rounded-xl flex items-center justify-center transition-colors duration-300 shrink-0"
                                             :class="qcm.score >= qcm.score_reussite ? 'bg-emerald-50 text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white' : 'bg-rose-50 text-rose-500 group-hover:bg-rose-500 group-hover:text-white'">
                                            <svg x-show="qcm.score >= qcm.score_reussite" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                            <svg x-show="qcm.score < qcm.score_reussite" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-base font-black text-slate-900 tracking-tight leading-none mb-1.5" x-text="qcm.titre"></h4>
                                            <div class="flex items-center gap-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                                <span x-text="qcm.date_fin"></span>
                                                <span class="size-1 rounded-full bg-slate-300"></span>
                                                <span class="truncate max-w-[150px] sm:max-w-none" x-text="qcm.unite_nom"></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between sm:justify-end gap-6 sm:w-auto w-full border-t sm:border-t-0 border-slate-100 pt-4 sm:pt-0 mt-2 sm:mt-0">
                                        <div class="text-left sm:text-right">
                                            <span class="block text-[10px] font-black uppercase tracking-widest mb-1"
                                                  :class="qcm.score >= qcm.score_reussite ? 'text-emerald-500' : 'text-rose-500'"
                                                  x-text="qcm.score >= qcm.score_reussite ? 'Objectif atteint' : 'Non validé'"></span>
                                            <span class="text-2xl font-black font-heading text-slate-900" x-text="qcm.score"><span class="text-sm text-slate-400">/20</span></span>
                                        </div>
                                        <div class="size-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 group-hover:bg-slate-900 group-hover:border-slate-900 group-hover:text-white transition-all duration-300 shrink-0">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('studentLibrary', (config) => ({
            enCours: config.enCours,
            aFaire: config.aFaire,
            termines: config.termines,
            search: config.search,
            uaId: config.uaId,
            uaLabel: config.uaLabel,
            statut: config.statut,
            statutLabel: config.statutLabel,
            loading: false,

            async applyFilters() {
                this.loading = true;
                const url = new URL('{{ route('student.bibliotheque.search') }}');
                if (this.search) url.searchParams.set('search', this.search);
                if (this.uaId) url.searchParams.set('ua_id', this.uaId);
                if (this.statut) url.searchParams.set('statut', this.statut);

                try {
                    const response = await fetch(url.toString(), {
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await response.json();
                    this.enCours = data.enCours;
                    this.aFaire = data.aFaire;
                    this.termines = data.termines;
                } catch (error) {
                    console.error('Erreur recherche student:', error);
                } finally {
                    this.loading = false;
                }
            }
        }));
    });
    </script>
</div>
@endsection