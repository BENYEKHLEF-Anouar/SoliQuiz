@extends('components.layout.app')

@section('content')
<div x-data="formateurQcms" x-init="init()" class="h-full flex flex-col relative overflow-hidden font-sans bg-slate-50">
    <!-- Header -->
    @include('components.header.formateur-header')

    <main class="flex-1 overflow-y-auto w-full px-5 py-8 pb-32 hide-scrollbar relative">
        <!-- Global Loading State -->
        <div x-show="loading" 
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 z-50 bg-slate-50 flex items-center justify-center">
            <x-feedback.loader message="Gestion des sessions..." />
        </div>

        <div x-show="!loading" 
             x-transition:enter="transition ease-out duration-700 delay-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            <!-- Stats Overview Bar -->
            <div class="grid grid-cols-4 gap-2 bg-white p-4 rounded-3xl border border-slate-100 shadow-[0_8px_30px_-4px_rgba(0,0,0,0.02)] mb-6 text-center">
                <div>
                    <p class="text-[18px] font-black text-slate-900 leading-none" x-text="qcms.length"></p>
                    <p class="text-[7px] font-black text-slate-400 uppercase tracking-widest mt-1.5">QCMs</p>
                </div>
                <div class="border-l border-slate-100">
                    <p class="text-[18px] font-black text-primary-500 leading-none" x-text="qcms.filter(q => q.status === 'Actif').length"></p>
                    <p class="text-[7px] font-black text-slate-400 uppercase tracking-widest mt-1.5">Publiés</p>
                </div>
                <div class="border-l border-slate-100">
                    <p class="text-[18px] font-black text-amber-500 leading-none" x-text="qcms.filter(q => q.status === 'Brouillon').length"></p>
                    <p class="text-[7px] font-black text-slate-400 uppercase tracking-widest mt-1.5">Brouillons</p>
                </div>
                <div class="border-l border-slate-100">
                    <p class="text-[18px] font-black text-emerald-500 leading-none" x-text="qcms.reduce((sum, q) => sum + (q.resultsCount || 0), 0)"></p>
                    <p class="text-[7px] font-black text-slate-400 uppercase tracking-widest mt-1.5">Passages</p>
                </div>
            </div>

            <!-- Search & Filters Container -->
            <div class="flex flex-col gap-3 mb-6">
                <!-- Search Input -->
                <div class="relative group w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none transition-colors group-focus-within:text-primary-500">
                        <svg class="shrink-0 size-4 text-slate-400" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                    </div>
                    <input type="text" x-model="search" @input="applyFilters"
                        class="py-3.5 px-4 pl-11 block w-full border-slate-100 bg-white shadow-sm rounded-2xl text-xs font-bold focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 transition-all outline-none"
                        placeholder="Rechercher un QCM...">
                    <button x-show="search" x-cloak @click="search = ''; applyFilters()" class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600">
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 6 6 18" /><path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Filters Row -->
                <div class="flex gap-2">
                    <!-- Status Filter Dropdown -->
                    <div class="relative flex-1" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" type="button"
                            class="w-full h-11 px-4 bg-white border border-slate-100 rounded-2xl flex items-center justify-between text-[10px] font-black uppercase tracking-wider text-slate-600 shadow-sm outline-none transition-all hover:border-primary-300">
                            <span class="truncate pr-1" x-text="statusFilter === 'all' ? 'Statuts' : statusFilter">Statuts</span>
                            <svg class="size-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <!-- Dropdown Menu -->
                        <div x-show="open" style="display: none;" class="absolute top-full left-0 mt-2 bg-white border border-slate-100 rounded-2xl shadow-xl p-2 z-50 w-full min-w-40">
                            <button @click="statusFilter = 'all'; open = false; applyFilters()"
                                class="w-full flex items-center gap-3 py-2 px-3 rounded-xl text-[10px] font-bold transition-colors text-left"
                                :class="statusFilter === 'all' ? 'bg-slate-100 text-slate-800' : 'text-slate-600 hover:bg-slate-50'">
                                Tous les statuts
                            </button>
                            <button @click="statusFilter = 'Actif'; open = false; applyFilters()"
                                class="w-full flex items-center gap-3 py-2 px-3 rounded-xl text-[10px] font-bold transition-colors text-left"
                                :class="statusFilter === 'Actif' ? 'bg-primary-50 text-primary-600' : 'text-slate-600 hover:bg-slate-50'">
                                Actif
                            </button>
                            <button @click="statusFilter = 'Terminé'; open = false; applyFilters()"
                                class="w-full flex items-center gap-3 py-2 px-3 rounded-xl text-[10px] font-bold transition-colors text-left"
                                :class="statusFilter === 'Terminé' ? 'bg-slate-100 text-slate-700' : 'text-slate-600 hover:bg-slate-50'">
                                Terminé
                            </button>
                            <button @click="statusFilter = 'Brouillon'; open = false; applyFilters()"
                                class="w-full flex items-center gap-3 py-2 px-3 rounded-xl text-[10px] font-bold transition-colors text-left"
                                :class="statusFilter === 'Brouillon' ? 'bg-amber-50 text-amber-600' : 'text-slate-600 hover:bg-slate-50'">
                                Brouillon
                            </button>
                        </div>
                    </div>

                    <!-- Unite Filter Dropdown -->
                    <div class="relative flex-1" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" type="button"
                            class="w-full h-11 px-4 bg-white border border-slate-100 rounded-2xl flex items-center justify-between text-[10px] font-black uppercase tracking-wider text-slate-600 shadow-sm outline-none transition-all hover:border-primary-300">
                            <span class="truncate pr-1" x-text="uniteFilter === 'all' ? 'Toutes les UAs' : uniteLabel">Toutes les UAs</span>
                            <svg class="size-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <!-- Dropdown Menu -->
                        <div x-show="open" style="display: none;" class="absolute top-full right-0 mt-2 bg-white border border-slate-100 rounded-2xl shadow-xl p-2 z-50 w-full min-w-48 max-h-48 overflow-y-auto">
                            <button @click="uniteFilter = 'all'; uniteLabel = 'Toutes les UAs'; open = false; applyFilters()"
                                class="w-full flex items-center gap-3 py-2 px-3 rounded-xl text-[10px] font-bold transition-colors text-left"
                                :class="uniteFilter === 'all' ? 'bg-slate-100 text-slate-800' : 'text-slate-600 hover:bg-slate-50'">
                                Toutes les UAs
                            </button>
                            <template x-for="u in unitesList" :key="u.id">
                                <button @click="uniteFilter = u.id; uniteLabel = u.nom; open = false; applyFilters()"
                                    class="w-full flex items-center gap-3 py-2 px-3 rounded-xl text-[10px] font-bold transition-colors text-left truncate"
                                    :class="uniteFilter === u.id ? 'bg-primary-50 text-primary-600' : 'text-slate-600 hover:bg-slate-50'"
                                    x-text="u.nom">
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Count & Active Filters -->
                <div class="flex justify-between items-center px-1 mt-2">
                    <h2 class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]" x-text="'Mes Créations (' + filteredQcms.length + ')'"></h2>
                    <button x-show="search || statusFilter !== 'all' || uniteFilter !== 'all'" x-cloak @click="resetFilters"
                        class="text-[10px] font-bold text-primary-600 uppercase tracking-widest hover:text-primary-700 transition-colors flex items-center gap-1">
                        <svg class="size-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 12" /><path d="M3 3v9h9" /></svg>
                        Réinitialiser
                    </button>
                </div>
            </div>

            <div class="grid gap-6">
                <template x-for="qcm in filteredQcms" :key="qcm.id">
                    <div class="flex flex-col bg-white border border-slate-100 shadow-[0_8px_30px_-4px_rgba(0,0,0,0.04)] rounded-[2.5rem] overflow-hidden transition-all duration-300 hover:border-slate-200 active:scale-[0.98]">
                        <div class="p-7">
                            <div class="flex justify-between items-start mb-6">
                                <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3.5 rounded-xl text-[9px] font-black uppercase tracking-[0.2em] leading-none border"
                                    :class="qcm.status === 'Actif' ? 'bg-primary-50 text-primary-600 border-primary-100/50' : 'bg-slate-50 text-slate-500 border-slate-100'">
                                    <span class="size-1.5 rounded-full" :class="qcm.status === 'Actif' ? 'bg-primary-500 animate-pulse' : 'bg-slate-400'"></span>
                                    <span x-text="qcm.status"></span>
                                </span>


                            </div>
                            
                            <h3 class="text-2xl font-heading font-extrabold text-slate-900 leading-[1.1] tracking-tight mb-3" x-text="qcm.title"></h3>
                            <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-[10px] font-bold text-slate-400 uppercase tracking-tight">
                                <span x-text="qcm.questionsCount + ' Questions'"></span>
                                <span class="size-1 bg-slate-200 rounded-full"></span>
                                <span x-text="'Cohorte: ' + (qcm.assignedCohort || 'N/A')"></span>
                                <span class="size-1 bg-slate-200 rounded-full"></span>
                                <span class="text-primary-500 font-black" x-text="qcm.unite_nom"></span>
                            </div>

                            <div class="mt-8 flex gap-3">
                                <a :href="'/formateur/qcm/' + qcm.id + '/results'"
                                    class="flex-1 py-4 px-4 inline-flex justify-center items-center gap-x-2 text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl border border-transparent bg-slate-950 text-white hover:bg-slate-900 shadow-xl shadow-slate-900/10 active:scale-[0.97] transition-all">
                                    Suivi résultats (<span x-text="qcm.resultsCount"></span>)
                                </a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Empty state -->
            <div x-show="filteredQcms.length === 0" class="text-center py-32 flex flex-col items-center animate-in fade-in duration-1000">
                <div class="size-20 bg-slate-50 rounded-[2.5rem] flex items-center justify-center mb-6 text-slate-200 shadow-inner">
                    <svg class="size-10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4.5v15m7.5-7.5h-15" stroke-linecap="round"/></svg>
                </div>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-300">Aucun QCM détecté</p>
            </div>
        </div>
    </main>

    <!-- FAB Button -->
    <button type="button" data-hs-overlay="#hs-create-qcm-modal"
        class="fixed bottom-28 right-6 w-16 h-16 bg-primary-500 text-white rounded-[2rem] shadow-2xl shadow-primary-500/40 flex items-center justify-center hover:bg-primary-600 active:scale-90 transition-all z-40 outline-none">
        <svg class="shrink-0 size-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
    </button>

    <!-- Create Modal -->
    <div id="hs-create-qcm-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1">
        <div class="hs-overlay-open:mt-auto hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-10 opacity-0 transition-all ease-out flex items-end min-h-full">
            <div class="flex flex-col bg-white border shadow-2xl rounded-t-[3rem] pointer-events-auto w-full max-w-[430px] mx-auto border-t border-slate-100">
                <div class="p-5 flex justify-center">
                    <div class="w-12 h-1.5 bg-slate-100 rounded-full"></div>
                </div>
                <div class="flex justify-between items-center py-4 px-10">
                    <h3 class="text-2xl font-heading font-extrabold text-slate-900 tracking-tight">NOUVEAU QCM</h3>
                    <button type="button" class="size-9 inline-flex justify-center items-center rounded-2xl bg-slate-50 text-slate-400 hover:bg-slate-100 transition-colors" data-hs-overlay="#hs-create-qcm-modal">
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="px-10 pb-12 pt-4">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Titre de l'évaluation</label>
                            <input type="text" class="py-4.5 px-6 block w-full border-slate-100 bg-slate-50 rounded-2xl text-sm font-bold focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 transition-all outline-none" placeholder="Ex: Masterclass Javascript">
                        </div>
                        
                        <button type="button" class="w-full h-16 inline-flex justify-center items-center gap-x-2 text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl border border-transparent bg-primary-500 text-white shadow-2xl shadow-primary-500/20 hover:bg-primary-600 transition-all active:scale-[0.98]">
                            Créer la session
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    @include('components.nav.formateur-bottom-nav')

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        [x-cloak] { display: none !important; }
    </style>
</div>
@endsection
