@extends('layouts.app')

@section('title', 'Bibliothèque de Contenus - SoliQuiz')

@section('content')
<div class="reveal active" x-data="qcmLibrary({{ Js::from($qcms->items()) }}, {{ Js::from($search ?? '') }}, {{ Js::from($status ?? '') }})">
    
    <!-- Header -->
    <div class="mb-10">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <!-- Search & Filter -->
            <div class="flex flex-col sm:flex-row gap-3 flex-1">
                <!-- Search Input -->
                <div class="flex-1 relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-slate-300 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" x-model="search" @input.debounce.300ms="applyFilters()"
                           placeholder="Rechercher un QCM..."
                           class="w-full h-12 bg-white border-2 border-slate-100 rounded-xl pl-12 pr-10 text-sm font-bold text-slate-900 placeholder:text-slate-300 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 outline-none transition-all">
                    <button type="button" x-show="search" @click="search = ''; applyFilters()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-500 transition-colors">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
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
            
            <!-- Create Button -->
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

    <!-- Results Count -->
    <div class="mb-4 flex items-center justify-between">
        <p class="text-sm font-bold text-slate-500">
            <span x-text="filteredQcms.length"></span> résultat(s)
        </p>
    </div>

    <!-- QCM List -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <!-- Table Header -->
        <div class="grid grid-cols-12 gap-3 px-5 py-3 bg-slate-50 border-b border-slate-100 text-[8px] font-black text-slate-400 uppercase tracking-widest">
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
                        <h4 class="text-sm font-black text-slate-800 uppercase italic" x-text="qcm.titre"></h4>
                        <p class="text-[9px] font-bold text-slate-400" x-text="qcm.duree_minutes + 'min · Seuil ' + qcm.score_reussite + '/20'"></p>
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
                <div class="col-span-2 flex justify-end gap-2">
                    <a :href="'/formateur/qcm/' + qcm.id + '/edit'" 
                       class="size-9 rounded-lg bg-slate-100 text-slate-400 hover:bg-primary-500 hover:text-white transition-all flex items-center justify-center"
                       title="Modifier">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                    
                    <template x-if="qcm.statut === 'brouillon'">
                        <form :action="'/formateur/qcm/' + qcm.id + '/toggle'" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="size-9 rounded-lg bg-emerald-50 text-emerald-500 hover:bg-emerald-500 hover:text-white transition-all flex items-center justify-center" title="Publier">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </form>
                    </template>
                    <template x-if="qcm.statut === 'public'">
                        <form :action="'/formateur/qcm/' + qcm.id + '/toggle'" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="size-9 rounded-lg bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition-all flex items-center justify-center" title="Dépublier">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                        <form :action="'/formateur/qcm/' + qcm.id + '/close'" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="size-9 rounded-lg bg-slate-100 text-slate-400 hover:bg-slate-800 hover:text-white transition-all flex items-center justify-center" title="Fermer">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </button>
                        </form>
                    </template>
                    
                    <template x-if="qcm.statut !== 'termine'">
                        <button @click="$dispatch('confirm', { title: 'Supprimer le QCM ?', message: 'Cette action est irréversible.', onConfirm: 'delete-qcm-' + qcm.id })" 
                                class="size-9 rounded-lg bg-rose-50 text-rose-400 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center"
                                title="Supprimer">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                        <form :id="'delete-qcm-' + qcm.id" :action="'/formateur/qcm/' + qcm.id" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </template>
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

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('qcmLibrary', (qcms, initialSearch, initialStatus) => ({
        qcms: qcms,
        search: initialSearch,
        status: initialStatus,
        
        get statusLabel() {
            if (!this.status) return 'Tous';
            const labels = { 'public': 'Publiés', 'brouillon': 'Brouillons', 'termine': 'Terminés' };
            return labels[this.status] || 'Tous';
        },
        
        get hasFilters() {
            return this.search || this.status;
        },
        
        get filteredQcms() {
            return this.qcms.filter(qcm => {
                const matchesSearch = !this.search || 
                    qcm.titre.toLowerCase().includes(this.search.toLowerCase());
                const matchesStatus = !this.status || qcm.statut === this.status;
                return matchesSearch && matchesStatus;
            });
        },
        
        applyFilters() {
            const url = new URL(window.location);
            if (this.search) {
                url.searchParams.set('q', this.search);
            } else {
                url.searchParams.delete('q');
            }
            if (this.status) {
                url.searchParams.set('status', this.status);
            } else {
                url.searchParams.delete('status');
            }
            history.pushState({}, '', url);
        },
        
        clearFilters() {
            this.search = '';
            this.status = '';
            const url = new URL(window.location);
            url.searchParams.delete('q');
            url.searchParams.delete('status');
            history.pushState({}, '', url);
        }
    }));
});
</script>
@endsection