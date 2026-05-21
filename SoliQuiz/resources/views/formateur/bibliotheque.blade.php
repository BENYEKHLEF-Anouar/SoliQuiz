@extends('layouts.app')

@section('title', 'Bibliothèque de Contenus - SoliQuiz')

@section('content')
<div class="reveal active pb-48" x-data="qcmLibrary({{ Js::from($qcms->items()) }}, {{ Js::from($search ?? '') }}, {{ Js::from($status ?? '') }}, '')">
    
    <!-- Header -->
    <div class="mb-10">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-8 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-4 mb-3">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Espace Formateur</span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-900">Bibliothèque</span>
                </div>
                <h3 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                    Bibliothèque de QCM
                </h3>
                <p class="mt-2 text-sm text-slate-500 max-w-xl">
                    Gérez, publiez et organisez l'ensemble de vos contenus pédagogiques.
                </p>
            </div>
            <a href="{{ route('formateur.qcm.create') }}"
               class="h-12 px-6 bg-primary-500 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-primary-600 shadow-lg shadow-primary-500/20 active:scale-95 transition-all flex items-center justify-center gap-3 shrink-0">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
                Nouveau QCM
            </a>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-slate-100 p-4 flex items-center gap-4">
            <div class="size-10 rounded-lg bg-slate-900 flex items-center justify-center">
                <svg class="size-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 12h6m-6 4h6m-2-8a2 2 0 11-4 0 2 2 0 014 0zM19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
            </div>
            <div>
                <p class="text-xl font-black text-slate-900 leading-none">{{ $qcms->total() }}</p>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">QCMs</p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl border border-slate-100 p-4 flex items-center gap-4">
            <div class="size-10 rounded-lg bg-emerald-500 flex items-center justify-center">
                <svg class="size-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <p class="text-xl font-black text-slate-900 leading-none">{{ $qcms->where('statut', 'public')->count() }}</p>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Publiés</p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl border border-slate-100 p-4 flex items-center gap-4">
            <div class="size-10 rounded-lg bg-amber-500 flex items-center justify-center">
                <svg class="size-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div>
                <p class="text-xl font-black text-slate-900 leading-none">{{ $qcms->where('statut', 'brouillon')->count() }}</p>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Brouillons</p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl border border-slate-100 p-4 flex items-center gap-4">
            <div class="size-10 rounded-lg bg-primary-500 flex items-center justify-center">
                <svg class="size-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div>
                <p class="text-xl font-black text-slate-900 leading-none">{{ $qcms->sum('tentatives_count') }}</p>
                <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Passages</p>
            </div>
        </div>
    </div>

    <!-- Filters + Actions Bar -->
    <div class="flex flex-col sm:flex-row gap-3 mb-4">
        <!-- Search Input -->
        <div class="flex-1 relative group">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-slate-300 pointer-events-none group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" x-model="search" @input.debounce.300ms="applyFilters()"
                   placeholder="Rechercher un QCM..."
                   class="w-full h-12 bg-white border-2 border-slate-100 rounded-xl pl-12 pr-14 text-sm font-bold text-slate-900 placeholder:text-slate-300 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 outline-none transition-all shadow-xs group-hover:shadow-sm">
            
            <!-- Live Search Loader -->
            <div x-show="loading" 
                 class="absolute right-5 top-1/2 -translate-y-1/2"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-50"
                 x-transition:enter-end="opacity-100 scale-100"
                 style="display: none;">
                <div class="size-4 border-2 border-primary-200 border-t-primary-500 rounded-full animate-spin"></div>
            </div>

            <button type="button" x-show="search && !loading" @click="search = ''; applyFilters()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500 transition-colors">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <!-- UA Filter -->
        <div x-data="{ open: false }" class="relative w-full sm:w-56">
            <button type="button" @click="open = !open" @click.away="open = false"
                    class="w-full h-12 px-4 bg-white border-2 border-slate-100 rounded-xl flex items-center justify-between text-sm font-bold text-slate-600 hover:border-primary-300 transition-all">
                <span class="truncate pr-2" x-text="uaLabel"></span>
                <svg class="size-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open" style="display: none;" class="absolute top-full left-0 w-full mt-2 bg-white border border-slate-100 rounded-xl shadow-xl z-50 p-2 max-h-60 overflow-y-auto">
                <button type="button" @click="uaId = ''; uaLabel = 'Toutes les unités'; open = false; applyFilters()" class="w-full px-4 py-2 text-left text-xs font-bold text-slate-500 hover:bg-slate-50 rounded-lg">Toutes les unités</button>
                @foreach($unites as $unite)
                    <button type="button" @click="uaId = '{{ $unite->id }}'; uaLabel = '{{ $unite->nom }}'; open = false; applyFilters()" class="w-full px-4 py-2 text-left text-xs font-bold text-slate-900 hover:bg-primary-50 hover:text-primary-600 rounded-lg">{{ $unite->nom }}</button>
                @endforeach
            </div>
        </div>

        <!-- Status Filter -->
        <div x-data="{ open: false }" class="relative w-full sm:w-44">
            <button type="button" @click="open = !open" @click.away="open = false"
                    class="w-full h-12 px-4 bg-white border-2 border-slate-100 rounded-xl flex items-center justify-between text-sm font-bold text-slate-600 hover:border-primary-300 transition-all">
                <span x-text="statusLabel"></span>
                <svg class="size-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 9l-7 7-7-7"/></svg>
            </button>
            
            <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                 class="absolute z-50 w-full mt-2 bg-white border-2 border-slate-100 rounded-xl shadow-lg overflow-hidden" style="display: none;">
                <button type="button" @click="status = ''; open = false; applyFilters()" 
                        class="w-full px-4 py-3 text-left text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors flex items-center justify-between">
                    <span>Tous</span>
                    <span x-show="status === ''" class="size-2 bg-primary-500 rounded-full"></span>
                </button>
                <button type="button" @click="status = 'public'; open = false; applyFilters()" 
                        class="w-full px-4 py-3 text-left text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors flex items-center justify-between">
                    <span>Publiés</span>
                    <span x-show="status === 'public'" class="size-2 bg-primary-500 rounded-full"></span>
                </button>
                <button type="button" @click="status = 'brouillon'; open = false; applyFilters()" 
                        class="w-full px-4 py-3 text-left text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors flex items-center justify-between">
                    <span>Brouillons</span>
                    <span x-show="status === 'brouillon'" class="size-2 bg-primary-500 rounded-full"></span>
                </button>
                <button type="button" @click="status = 'termine'; open = false; applyFilters()" 
                        class="w-full px-4 py-3 text-left text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors flex items-center justify-between">
                    <span>Terminés</span>
                    <span x-show="status === 'termine'" class="size-2 bg-primary-500 rounded-full"></span>
                </button>
            </div>
        </div>
        
        <!-- Clear Filters -->
        <button x-show="hasFilters" @click="clearFilters()" 
                class="h-12 px-4 bg-rose-50 text-rose-500 rounded-xl font-bold text-xs hover:bg-rose-100 transition-colors flex items-center gap-2">
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
            Effacer
        </button>
    </div>

    <!-- Results Count -->
    <p class="text-sm font-bold text-slate-400 mb-3"><span x-text="filteredQcms.length"></span> résultat(s)</p>

    <!-- QCM List Wrapper -->
    <div class="relative mt-2" :class="loading && qcms.length === 0 ? 'min-h-[200px]' : ''">
        <!-- Loading Overlay -->
        <div x-show="loading"
            class="absolute inset-0 bg-white/40 backdrop-blur-[2px] z-20 flex flex-col items-center justify-center rounded-2xl min-h-[200px]"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            style="display: none;">
            <div class="flex flex-col items-center gap-3 scale-90">
                <div class="size-10 border-4 border-slate-100 border-t-primary-500 rounded-full animate-spin shadow-sm"></div>
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] animate-pulse">Indexation...
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-visible"
             :class="loading ? 'opacity-50 pointer-events-none transition-opacity duration-300' : 'transition-opacity duration-300'">
        <!-- Table Header -->
        <div class="grid grid-cols-12 gap-3 px-5 py-3 bg-slate-50 rounded-t-2xl border-b border-slate-100 text-[8px] font-black text-slate-400 uppercase tracking-widest">
            <div class="col-span-4">QCM</div>
            <div class="col-span-2 text-center">Statut</div>
            <div class="col-span-2 text-center">Questions</div>
            <div class="col-span-2 text-center">Passages</div>
            <div class="col-span-2 text-right">Actions</div>
        </div>
        
        <!-- Table Rows -->
        <template x-for="qcm in filteredQcms" :key="qcm.id">
            <div class="grid grid-cols-12 gap-3 px-5 py-4 border-b border-slate-50 hover:bg-slate-50/50 transition-colors items-center">
                <!-- QCM Info -->
                <div class="col-span-4 flex items-center gap-3">
                    <div class="size-9 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 12h6m-6 4h6m-2-8a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-slate-800 uppercase" x-text="qcm.titre"></h4>
                        <p class="text-[9px] font-bold text-slate-400" x-text="(qcm.duree_minutes > 0 ? qcm.duree_minutes + 'min' : 'Illimité') + ' · Seuil ' + qcm.score_reussite + '/20'"></p>
                    </div>
                </div>
                
                <!-- Status -->
                <div class="col-span-2 flex justify-center">
                    <template x-if="qcm.statut === 'public'">
                        <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full bg-emerald-50 text-emerald-600 text-[8px] font-black uppercase tracking-widest">
                            <span class="size-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Live
                        </span>
                    </template>
                    <template x-if="qcm.statut === 'termine'">
                        <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full bg-slate-100 text-slate-500 text-[8px] font-black uppercase tracking-widest">
                            <span class="size-1.5 rounded-full bg-slate-400"></span>
                            Terminé
                        </span>
                    </template>
                    <template x-if="qcm.statut === 'brouillon'">
                        <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full bg-amber-50 text-amber-600 text-[8px] font-black uppercase tracking-widest">
                            <span class="size-1.5 rounded-full bg-amber-500"></span>
                            Brouillon
                        </span>
                    </template>
                </div>
                
                <!-- Questions -->
                <div class="col-span-2 flex justify-center">
                    <span class="text-sm font-black text-slate-600" x-text="qcm.questions_count || 0"></span>
                </div>
                
                <!-- Passages -->
                <div class="col-span-2 flex justify-center">
                    <span class="text-sm font-black text-slate-600" x-text="qcm.tentatives_count || 0"></span>
                </div>
                
                <!-- Actions -->
                <div class="col-span-2 flex justify-end gap-2" x-data="{ menuOpen: false }">
                    <!-- Primary Action: Edit -->
                    <button @click.stop="window.location.href = '/formateur/qcm/' + qcm.id + '/edit'" 
                       class="size-9 rounded-lg bg-slate-100 text-slate-400 hover:bg-primary-500 hover:text-white transition-all flex items-center justify-center"
                       title="Modifier">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>

                    <!-- More Actions Dropdown -->
                    <div class="relative">
                        <button type="button" @click.stop="menuOpen = !menuOpen" @click.away="menuOpen = false"
                                class="size-9 rounded-lg bg-slate-50 text-slate-400 hover:bg-slate-200 transition-all flex items-center justify-center">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                        </button>

                        <div x-show="menuOpen" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             class="absolute right-0 mt-2 w-48 bg-white border border-slate-100 rounded-xl shadow-xl z-50 py-2 overflow-hidden"
                             style="display: none;">
                            
                            <!-- Toggle Publication -->
                            <template x-if="qcm.statut === 'brouillon'">
                                <button @click="document.getElementById('toggle-form-' + qcm.id).submit()" class="w-full px-4 py-2.5 text-left text-[10px] font-black uppercase text-emerald-600 hover:bg-emerald-50 transition-colors flex items-center gap-3">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
                                    Publier
                                </button>
                            </template>
                            <template x-if="qcm.statut === 'public'">
                                <button @click="document.getElementById('toggle-form-' + qcm.id).submit()" class="w-full px-4 py-2.5 text-left text-[10px] font-black uppercase text-amber-600 hover:bg-amber-50 transition-colors flex items-center gap-3">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                    Dépublier
                                </button>
                            </template>

                            <!-- Close QCM -->
                            <template x-if="qcm.statut === 'public'">
                                <button @click="document.getElementById('close-form-' + qcm.id).submit()" class="w-full px-4 py-2.5 text-left text-[10px] font-black uppercase text-slate-600 hover:bg-slate-50 transition-colors flex items-center gap-3 border-t border-slate-50">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    Fermer
                                </button>
                            </template>

                            <!-- Duplicate -->
                            <button @click="document.getElementById('duplicate-form-' + qcm.id).submit()" class="w-full px-4 py-2.5 text-left text-[10px] font-black uppercase text-indigo-600 hover:bg-indigo-50 transition-colors flex items-center gap-3 border-t border-slate-50">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" /></svg>
                                Dupliquer
                            </button>

                            <!-- Delete -->
                            <button @click="$dispatch('confirm', { title: 'Supprimer ?', message: 'Action irréversible', onConfirm: 'delete-qcm-' + qcm.id })" 
                                    class="w-full px-4 py-2.5 text-left text-[10px] font-black uppercase text-rose-600 hover:bg-rose-50 transition-colors flex items-center gap-3 border-t border-slate-50">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Supprimer
                            </button>
                        </div>
                    </div>

                    <!-- Hidden Forms -->
                    <form :id="'toggle-form-' + qcm.id" :action="'/formateur/qcm/' + qcm.id + '/toggle'" method="POST" class="hidden">@csrf @method('PATCH')</form>
                    <form :id="'close-form-' + qcm.id" :action="'/formateur/qcm/' + qcm.id + '/close'" method="POST" class="hidden">@csrf @method('PATCH')</form>
                    <form :id="'duplicate-form-' + qcm.id" :action="'/formateur/qcm/' + qcm.id + '/duplicate'" method="POST" class="hidden">@csrf</form>
                    <form :id="'delete-qcm-' + qcm.id" :action="'/formateur/qcm/' + qcm.id" method="POST" class="hidden">@csrf @method('DELETE')</form>
                </div>
            </div>
        </template>
        
        <!-- No Results -->
        <div x-show="filteredQcms.length === 0" class="p-12 text-center">
            <div class="size-12 bg-slate-50 rounded-xl flex items-center justify-center mx-auto mb-4">
                <svg class="size-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.987a2 2 0 01-1.992-2V7.965a2 2 0 011.992-1.996h2.979z"/></svg>
            </div>
            <h3 class="text-sm font-black text-slate-600 mb-1">Aucun résultat</h3>
            <p class="text-xs text-slate-400">Essayez avec d'autres filtres</p>
        </div>
    </div>
</div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('qcmLibrary', (qcms, initialSearch, initialStatus, initialUa) => ({
        qcms: qcms,
        search: initialSearch,
        status: initialStatus,
        uaId: initialUa,
        uaLabel: 'Toutes les unités',
        loading: false,
        
        get statusLabel() {
            if (!this.status) return 'Tous';
            const labels = { 'public': 'Publiés', 'brouillon': 'Brouillons', 'termine': 'Terminés' };
            return labels[this.status] || 'Tous';
        },
        
        get hasFilters() {
            return this.search || this.status;
        },
        
        get filteredQcms() {
            return this.qcms;
        },
        
        async applyFilters() {
            this.loading = true;
            const url = new URL(window.location);
            if (this.search) {
                url.searchParams.set('search', this.search);
            } else {
                url.searchParams.delete('search');
            }
            if (this.status) {
                url.searchParams.set('status', this.status);
            } else {
                url.searchParams.delete('status');
            }
            if (this.uaId) {
                url.searchParams.set('ua_id', this.uaId);
            } else {
                url.searchParams.delete('ua_id');
            }
            history.pushState({}, '', url);
            
            try {
                const response = await fetch('{{ route("formateur.bibliotheque.search") }}?' + url.searchParams.toString(), {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();
                this.qcms = data.data;
            } catch (error) {
                console.error('Erreur lors de la recherche:', error);
            } finally {
                this.loading = false;
            }
        },
        
        clearFilters() {
            this.search = '';
            this.status = '';
            this.uaId = '';
            this.uaLabel = 'Toutes les unités';
            this.applyFilters();
        }
    }));
});
</script>
@endsection