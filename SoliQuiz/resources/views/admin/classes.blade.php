@extends('layouts.app')

@section('title', 'Gestion des Cohortes - SoliQuiz')

@section('content')
    <div class="fade-in space-y-8" x-data="{
        activeClasseId: null,
        activeClasseName: '',
        activeClassePromotion: '',
        activeClasseFormateur: '',
        search: '{{ $search ?? '' }}',
        classes: {{ Js::from($classes->items()) }},
        loading: false,
        searchTimer: null,
        totalCount: {{ $classes->total() }},
        viewMode: localStorage.getItem('classes_view_mode') || 'cards',
        setViewMode(mode) {
            this.viewMode = mode;
            localStorage.setItem('classes_view_mode', mode);
        },
        performSearch() {
            clearTimeout(this.searchTimer);
            this.searchTimer = setTimeout(() => {
                this.loading = true;
                fetch('{{ route('admin.classes.search') }}?search=' + encodeURIComponent(this.search), {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin'
                })
                .then(r => r.json())
                .then(data => { 
                    this.classes = data.data; 
                    this.totalCount = data.total;
                    this.loading = false; 

                    // Update URL
                    const browserUrl = new URL(window.location);
                    if (this.search) browserUrl.searchParams.set('search', this.search); else browserUrl.searchParams.delete('search');
                    history.pushState({}, '', browserUrl);
                })
                .catch(() => { this.loading = false; });
            }, 300);
        },
        submitting: false
    }">
        <!-- Header -->
        <div class="relative z-30 mb-10">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-8 border-b border-slate-200">
                <div>
                    <div class="flex items-center gap-4 mb-3">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Espace Admin</span>
                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-900">Cohortes</span>
                    </div>
                    <h3 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                        Gestion des Cohortes
                    </h3>
                    <p class="mt-2 text-sm text-slate-500 max-w-xl">
                        Planifiez et organisez structurellement les cohortes et les groupes d'apprentissage.
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-4 px-5 py-2.5 bg-slate-50 rounded-2xl border border-slate-100">
                        <div class="text-right">
                            <p class="text-xs font-black text-slate-900 leading-none mb-1" x-text="totalCount">
                                {{ $classes->total() }}</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Total</p>
                        </div>
                    </div>
                    <button @click="$dispatch('open-modal', 'create-classe-modal')"
                        class="px-8 py-4 bg-slate-900 text-white text-[11px] font-black uppercase tracking-widest rounded-xl flex items-center gap-2 shrink-0 hover:bg-primary-600 active:scale-95 transition-all">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path d="M12 4v16m8-8H4" />
                        </svg>
                        Nouvelle Cohorte
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="flex flex-col md:flex-row gap-4 mb-8 items-center justify-between">
            <div class="flex-1 w-full relative group">
                <div class="absolute inset-y-0 inset-s-0 flex items-center pointer-events-none ps-6">
                    <svg class="size-4 text-slate-400 group-focus-within:text-primary-500 transition-colors" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg>
                </div>
                <input x-model="search" @input="performSearch()"
                    class="w-full bg-white border border-slate-100 rounded-2xl py-3 ps-14 pe-14 font-bold text-sm text-slate-900 placeholder:text-slate-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 outline-none transition-all shadow-sm group-hover:shadow-md h-[52px]"
                    type="text" placeholder="Filtrer le répertoire des cohortes...">

                <!-- Live Search Loader -->
                <div x-show="loading" 
                     class="absolute right-5 top-1/2 -translate-y-1/2"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-50"
                     x-transition:enter-end="opacity-100 scale-100"
                     style="display: none;">
                    <div class="size-4 border-2 border-primary-200 border-t-primary-500 rounded-full animate-spin"></div>
                </div>
            </div>

            <!-- View Mode Switcher -->
            <div class="flex bg-white p-1 rounded-2xl border border-slate-100 shrink-0 shadow-sm gap-1 h-[52px] items-center">
                <button type="button" @click="setViewMode('cards')" 
                        :class="viewMode === 'cards' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-400 hover:text-slate-600'"
                        class="p-2.5 rounded-xl transition-all duration-200 flex items-center justify-center"
                        title="Affichage en Cartes">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </button>
                <button type="button" @click="setViewMode('table')" 
                        :class="viewMode === 'table' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-400 hover:text-slate-600'"
                        class="p-2.5 rounded-xl transition-all duration-200 flex items-center justify-center"
                        title="Affichage en Tableau">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Classes Grid Wrapper -->
        <div class="relative mt-6" :class="loading && classes.length === 0 ? 'min-h-[200px]' : ''">
            <!-- Loading Overlay -->
            <div x-show="loading"
                class="absolute inset-0 bg-white/40 backdrop-blur-[2px] z-20 flex flex-col items-center justify-center rounded-[2rem] min-h-[200px]"
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

            <!-- Cards View Mode -->
            <div x-show="viewMode === 'cards'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                :class="loading ? 'opacity-50 pointer-events-none transition-opacity duration-300' : 'transition-opacity duration-300'">
                <template x-for="classe in classes" :key="classe.id">
                    <div
                        class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 relative group flex flex-col">
                        <div class="absolute inset-0 rounded-[2rem] overflow-hidden pointer-events-none">
                            <div
                                class="absolute -right-8 -top-8 size-32 bg-primary-50 rounded-full group-hover:scale-150 transition-transform duration-700">
                            </div>
                        </div>

                        <div class="flex justify-between items-start mb-6 relative z-20">
                            <span
                                class="inline-flex py-1.5 px-3 rounded-full text-[9px] font-black uppercase tracking-widest bg-slate-900 text-white shadow-sm"
                                x-text="classe.promotion || 'Formation Régulière'"></span>

                            <!-- Top Actions: Edit & Options Dropdown -->
                            <div class="flex items-center gap-2" x-data="{ options: false }">
                                <button
                                    @click="activeClasseId = classe.id; activeClasseName = classe.nom; activeClassePromotion = classe.promotion || ''; activeClasseFormateur = classe.formateur_id || ''; $dispatch('open-modal', 'edit-classe-modal')"
                                    class="size-10 rounded-xl bg-slate-100 text-slate-400 hover:bg-primary-500 hover:text-white hover:shadow-lg hover:-translate-y-0.5 hover:shadow-primary-500/20 transition-all flex items-center justify-center group/edit"
                                    title="Modifier la Cohorte">
                                    <svg class="size-4 group-hover/edit:scale-110 transition-transform" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>

                                <div class="relative">
                                    <button @click="options = !options" @click.away="options = false" type="button"
                                        class="size-10 rounded-xl bg-slate-50 text-slate-400 hover:bg-slate-200 hover:text-slate-900 transition-all flex items-center justify-center group/opt">
                                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="3">
                                            <path
                                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </button>

                                    <div x-show="options"
                                        class="absolute top-full right-0 mt-2 w-48 bg-white rounded-2xl border border-slate-100 shadow-premium z-50 py-2 overflow-hidden"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                        x-transition:enter-end="opacity-100 translate-y-0 scale-100" style="display: none;">

                                        <button type="button" 
                                                @click.stop="activeClasseId = classe.id; activeClasseName = classe.nom; $dispatch('open-modal', 'add-student-modal')"
                                                class="w-full flex items-center gap-3 px-4 py-2.5 text-emerald-600 hover:bg-emerald-50 transition-colors text-[10px] font-black uppercase tracking-widest border-b border-slate-50">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                                            <span>Ajouter Apprenant</span>
                                        </button>

                                        <button type="button" @click.prevent="$dispatch('confirm', { 
                                                    title: 'Démanteler cette classe ?', 
                                                    message: 'Toutes les données associées seront archivées ou supprimées.', 
                                                    onConfirm: 'delete-classe-' + classe.id 
                                                })"
                                            class="w-full flex items-center gap-3 px-4 py-2.5 text-rose-500 hover:bg-rose-50 transition-colors text-[10px] font-black uppercase tracking-widest">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2.5">
                                                <path
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span>Supprimer</span>
                                        </button>
                                        <form :id="'delete-classe-' + classe.id"
                                            :action="'{{ url('/admin/classes') }}/' + classe.id" method="POST"
                                            class="hidden">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="_method" value="DELETE">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div @click="window.location.href = '{{ url('/admin/classes') }}/' + classe.id" class="relative z-10 mb-6 block cursor-pointer group/link">
                            <h3 class="text-2xl font-heading font-black text-slate-900 tracking-tight leading-none mb-3 group-hover/link:text-primary-600 transition-colors flex items-center gap-3"
                                x-text="classe.nom"></h3>
                            <div class="flex items-center gap-2">
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest group-hover/link:text-primary-400 transition-colors">Consulter le dossier de cohorte</span>
                                <svg class="size-3 text-slate-300 group-hover/link:translate-x-1 group-hover/link:text-primary-500 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                            </div>
                        </div>

                        <div class="relative z-10 pt-6 border-t border-slate-50 flex items-center justify-between mt-auto">
                            <div>
                                <span
                                    class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Tuteur
                                    Académique</span>
                                <template x-if="classe.formateur">
                                    <div class="flex items-center gap-2 group/tutor">
                                        <img class="size-6 rounded-md shadow-sm border border-white"
                                            :src="'https://ui-avatars.com/api/?name=' + encodeURIComponent(classe.formateur.nom_complet) + '&background=f8fafc&color=0f172a&bold=true'"
                                            alt="">
                                        <span class="text-[10px] font-black text-slate-900 uppercase leading-tight"
                                            x-text="classe.formateur.nom_complet"></span>
                                    </div>
                                </template>
                                <template x-if="!classe.formateur">
                                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Non
                                        assigné</span>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Table View Mode -->
            <div x-show="viewMode === 'table'" class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-visible" x-cloak
                :class="loading ? 'opacity-50 pointer-events-none transition-opacity duration-300' : 'transition-opacity duration-300'">
                <!-- Table Header -->
                <div class="grid grid-cols-12 gap-3 px-8 py-4 bg-slate-50 rounded-t-2xl border-b border-slate-100 text-[8px] font-black text-slate-400 uppercase tracking-widest">
                    <div class="col-span-5">Cohorte</div>
                    <div class="col-span-3">Tuteur Académique</div>
                    <div class="col-span-4 text-right">Actions</div>
                </div>
                
                <!-- Table Rows -->
                <div class="divide-y divide-slate-50">
                    <template x-for="classe in classes" :key="classe.id">
                        <div class="grid grid-cols-12 gap-3 px-8 py-4 hover:bg-slate-50/50 transition-colors items-center">
                            <!-- Cohorte Info -->
                            <div class="col-span-5 flex items-center gap-3">
                                <div class="size-9 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <a :href="`{{ url('/admin/classes') }}/${classe.id}`" class="text-sm font-black text-slate-800 hover:text-primary-500 transition-colors uppercase truncate block" x-text="classe.nom"></a>
                                    <p class="text-[9px] font-bold text-slate-400 truncate mt-0.5" 
                                        x-text="classe.promotion || 'Formation Régulière'"></p>
                                </div>
                            </div>
                            
                            <!-- Tuteur Académique -->
                            <div class="col-span-3 flex items-center gap-2">
                                <template x-if="classe.formateur">
                                    <div class="flex items-center gap-2">
                                        <img class="size-6 rounded-md shadow-sm border border-white"
                                            :src="'https://ui-avatars.com/api/?name=' + encodeURIComponent(classe.formateur.nom_complet) + '&background=f8fafc&color=0f172a&bold=true'"
                                            alt="">
                                        <span class="text-[10px] font-black text-slate-900 uppercase truncate" x-text="classe.formateur.nom_complet"></span>
                                    </div>
                                </template>
                                <template x-if="!classe.formateur">
                                    <span class="text-[9px] font-black text-slate-350 uppercase tracking-widest">Non assigné</span>
                                </template>
                            </div>
                            
                            <!-- Actions -->
                            <div class="col-span-4 flex justify-end gap-2">
                                <a :href="`{{ url('/admin/classes') }}/${classe.id}`"
                                    class="size-9 rounded-lg bg-slate-100 text-slate-400 hover:bg-slate-900 hover:text-white transition-all flex items-center justify-center"
                                    title="Consulter">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <button type="button"
                                    @click="activeClasseId = classe.id; activeClasseName = classe.nom; activeClassePromotion = classe.promotion || ''; activeClasseFormateur = classe.formateur_id || ''; $dispatch('open-modal', 'edit-classe-modal')"
                                    class="size-9 rounded-lg bg-slate-50 text-slate-400 hover:bg-slate-200 hover:text-slate-900 transition-all flex items-center justify-center"
                                    title="Modifier">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button type="button"
                                    @click.stop="activeClasseId = classe.id; activeClasseName = classe.nom; $dispatch('open-modal', 'add-student-modal')"
                                    class="size-9 rounded-lg bg-slate-50 text-slate-400 hover:bg-emerald-500 hover:text-white transition-all flex items-center justify-center"
                                    title="Gérer les apprenants">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </button>
                                <button type="button"
                                    @click.prevent="$dispatch('confirm', { 
                                        title: 'Démanteler cette classe ?', 
                                        message: 'Toutes les données associées seront archivées ou supprimées.', 
                                        onConfirm: 'delete-classe-' + classe.id 
                                    })"
                                    class="size-9 rounded-lg bg-slate-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center"
                                    title="Supprimer">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                                <form :id="'delete-classe-' + classe.id"
                                    :action="'{{ url('/admin/classes') }}/' + classe.id" method="POST"
                                    class="hidden">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Empty State -->
            <template x-if="!loading && classes.length === 0">
                <div class="p-20 text-center bg-white rounded-[3rem] border border-slate-100 shadow-sm mt-8">
                    <div
                        class="size-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8 text-slate-300 border border-slate-100 shadow-inner">
                        <svg class="size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-2">Aucune Séquence</h3>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Initialisez votre structure
                        pédagogique</p>
                </div>
            </template>
        </div>


        <!-- Modals Layer -->
        <template x-teleport="body">
            <div>
                <!-- Modal: Créer Classe -->
                <x-ui.modal name="create-classe-modal" title="Architecture Cohorte" maxWidth="md">
                    <!-- pb-32 ensures the select dropdown is never clipped by the modal's overflow-y-auto -->
                    <form action="{{ route('admin.classes.store') }}" method="POST" class="space-y-6 pb-32" x-on:submit="submitting = true">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Identifiant
                                de Groupe</label>
                            <input type="text" name="nom" required placeholder="Ex: Développement Fullstack"
                                class="w-full bg-slate-50 border border-transparent rounded-2xl py-3.5 px-5 text-sm font-bold text-slate-900 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 outline-none transition-all">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Promotion
                                <span class="text-slate-300 normal-case">(Optionnel)</span></label>
                            <input type="text" name="promotion" placeholder="Ex: P-2024 / Elite"
                                class="w-full bg-slate-50 border border-transparent rounded-2xl py-3.5 px-5 text-sm font-bold text-slate-900 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 outline-none transition-all">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Expert
                                Référent <span class="text-slate-300 normal-case">(Optionnel)</span></label>
                            <x-ui.select name="formateur_id" placeholder="Assigner un formateur..."
                                class="!rounded-2xl !py-4 !px-5" :options="$formateurs->map(fn($f) => ['value' => $f->id, 'label' => $f->nom_complet])->toArray()" />
                        </div>

                        <button type="submit" :disabled="submitting" class="w-full py-4 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-primary-500 transition-all shadow-xl shadow-slate-900/20 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!submitting">Enregistrer la Structure</span>
                            <span x-show="submitting" x-cloak>Traitement...</span>
                        </button>
                    </form>
                </x-ui.modal>

                <!-- Modal: Modifier Classe -->
                <x-ui.modal name="edit-classe-modal" title="Modifier Cohorte" maxWidth="md">
                    <form x-bind:action="`{{ url('/admin/classes') }}/${activeClasseId}`" method="POST"
                        class="space-y-6 pb-32" x-on:submit="submitting = true">
                        @csrf
                        @method('PUT')

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Identifiant
                                de Groupe</label>
                            <input type="text" name="nom" required x-model="activeClasseName"
                                class="w-full bg-slate-50 border border-transparent rounded-2xl py-3.5 px-5 text-sm font-bold text-slate-900 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 outline-none transition-all">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Promotion
                                <span class="text-slate-300 normal-case">(Optionnel)</span></label>
                            <input type="text" name="promotion" x-model="activeClassePromotion"
                                placeholder="Ex: P-2024 / Elite"
                                class="w-full bg-slate-50 border border-transparent rounded-2xl py-3.5 px-5 text-sm font-bold text-slate-900 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 outline-none transition-all">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Expert
                                Référent <span class="text-slate-300 normal-case">(Optionnel)</span></label>
                            <x-ui.select name="formateur_id" x-model="activeClasseFormateur" placeholder="Indépendant"
                                class="!rounded-2xl !py-4 !px-5" :options="array_merge([['value' => '', 'label' => 'Aucun']], $formateurs->map(fn($f) => ['value' => $f->id, 'label' => $f->nom_complet])->toArray())" />
                        </div>

                        <button type="submit" :disabled="submitting" class="w-full py-4 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-primary-500 transition-all shadow-xl shadow-slate-900/20 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!submitting">Consigner les Changements</span>
                            <span x-show="submitting" x-cloak>Traitement...</span>
                        </button>
                    </form>
                </x-ui.modal>

                <!-- Modal: Ajouter Étudiant -->
                <x-ui.modal name="add-student-modal" title="Inclusion Apprenant" maxWidth="lg">
                    <!-- pb-32 prevents dropdown clipping -->
                    <div x-data="{ 
                         tab: 'affectation',
                         searchQuery: '',
                         selectedStudents: [],
                         availableStudents: {{ Js::from($availableStudents->map(fn($s) => ['id' => $s->id, 'nom_complet' => $s->nom_complet, 'email' => $s->email])->toArray()) }},
                         
                         get filteredStudents() {
                             if (!this.searchQuery) return this.availableStudents;
                             const query = this.searchQuery.toLowerCase();
                             return this.availableStudents.filter(s => 
                                 s.nom_complet.toLowerCase().includes(query) || 
                                 s.email.toLowerCase().includes(query)
                             );
                         },
                         
                         toggleStudent(id) {
                             if (this.selectedStudents.includes(id)) {
                                 this.selectedStudents = this.selectedStudents.filter(x => x !== id);
                             } else {
                                 this.selectedStudents.push(id);
                             }
                         },
                         
                         toggleAll() {
                             const visibleIds = this.filteredStudents.map(s => s.id);
                             const allSelected = visibleIds.every(id => this.selectedStudents.includes(id));
                             if (allSelected) {
                                 this.selectedStudents = this.selectedStudents.filter(id => !visibleIds.includes(id));
                             } else {
                                 visibleIds.forEach(id => {
                                     if (!this.selectedStudents.includes(id)) {
                                         this.selectedStudents.push(id);
                                     }
                                 });
                             }
                         }
                     }">
                        
                        <!-- Header / Info Bar -->
                        <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 flex items-center justify-between gap-4 mb-6">
                            <div class="flex items-center gap-4">
                                <div class="size-12 bg-slate-900 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-slate-900/10">
                                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1 leading-none">Intégration Cohorte</p>
                                    <p class="text-lg font-black text-slate-900" x-text="activeClasseName"></p>
                                </div>
                            </div>
                            
                            <!-- Tab Switcher -->
                            <div class="flex bg-slate-100 p-1 rounded-xl border border-slate-200 shrink-0">
                                <button type="button" @click="tab = 'affectation'" 
                                        :class="tab === 'affectation' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-500 hover:text-slate-950'"
                                        class="px-3.5 py-2 rounded-lg text-[9px] font-black uppercase tracking-wider transition-all duration-200">
                                    Affecter
                                </button>
                                <button type="button" @click="tab = 'import'" 
                                        :class="tab === 'import' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-500 hover:text-slate-950'"
                                        class="px-3.5 py-2 rounded-lg text-[9px] font-black uppercase tracking-wider transition-all duration-200">
                                    Import
                                </button>
                            </div>
                        </div>

                        <!-- Error Messages directly in Modal -->
                        @if($errors->any())
                            <div class="mb-4 bg-rose-500/10 border border-rose-500/20 rounded-xl p-3 text-[10px] text-rose-500 font-bold space-y-1">
                                @foreach($errors->all() as $error)
                                    <p>• {!! $error !!}</p>
                                @endforeach
                            </div>
                        @endif

                        <!-- Tab 1: Affecter -->
                        <div x-show="tab === 'affectation'" class="space-y-6">
                            <template x-if="availableStudents.length > 0">
                                <div class="space-y-4">
                                    <!-- Search input -->
                                    <div class="relative">
                                        <input type="text" x-model="searchQuery" placeholder="Rechercher un apprenant..." 
                                               class="w-full bg-slate-50 border border-slate-100 rounded-xl py-3 pl-11 pr-4 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all font-bold">
                                        <svg class="absolute left-4 top-3.5 size-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>

                                    <!-- Actions (Select All) -->
                                    <div class="flex items-center justify-between text-[9px] font-black uppercase tracking-widest text-slate-400 px-1">
                                        <button type="button" @click="toggleAll" class="hover:text-primary-600 transition-colors">
                                            <span x-text="filteredStudents.every(s => selectedStudents.includes(s.id)) ? 'Tout désélectionner' : 'Tout sélectionner'"></span>
                                        </button>
                                        <span x-text="`${selectedStudents.length} sélectionné(s)`"></span>
                                    </div>

                                    <!-- Student Checklist Scrollable Area -->
                                    <div class="max-h-60 overflow-y-auto pr-1 space-y-2 custom-scrollbar">
                                        <template x-for="student in filteredStudents" :key="student.id">
                                            <div @click="toggleStudent(student.id)" 
                                                 :class="selectedStudents.includes(student.id) ? 'border-primary-500 bg-primary-50/50' : 'border-slate-100 bg-slate-50 hover:bg-slate-100/80'"
                                                 class="border rounded-xl p-3.5 flex items-center gap-3.5 cursor-pointer transition-all">
                                                <div class="relative shrink-0">
                                                    <img class="size-9 rounded-lg shadow-sm border border-white"
                                                         :src="`https://ui-avatars.com/api/?name=${encodeURIComponent(student.nom_complet)}&background=0f172a&color=fff&bold=true`"
                                                         alt="">
                                                    <div :class="selectedStudents.includes(student.id) ? 'bg-primary-500' : 'bg-white border border-slate-350'"
                                                         class="absolute -top-1 -right-1 size-4.5 rounded-full flex items-center justify-center transition-colors shadow-sm">
                                                         <svg x-show="selectedStudents.includes(student.id)" class="size-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                                             <path d="M5 13l4 4L19 7" />
                                                         </svg>
                                                    </div>
                                                </div>
                                                <div class="flex flex-col min-w-0">
                                                    <span class="text-xs font-black truncate text-slate-900" x-text="student.nom_complet"></span>
                                                    <span class="text-[9px] font-bold text-slate-400 font-mono truncate" x-text="student.email"></span>
                                                </div>
                                            </div>
                                        </template>
                                        <div x-show="filteredStudents.length === 0" class="text-center py-8 text-slate-400 text-[10px] font-bold uppercase tracking-widest bg-slate-50 rounded-xl border border-slate-100">
                                            Aucun apprenant trouvé
                                        </div>
                                    </div>

                                    <!-- Form for bulk submission -->
                                    <form x-bind:action="`{{ url('/admin/classes') }}/${activeClasseId}/etudiants/bulk`" method="POST" x-on:submit="submitting = true">
                                        @csrf
                                        <template x-for="id in selectedStudents" :key="id">
                                            <input type="hidden" name="user_ids[]" :value="id">
                                        </template>
                                        <button type="submit" :disabled="selectedStudents.length === 0 || submitting"
                                                class="w-full py-4 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-primary-500 transition-all shadow-xl shadow-slate-900/20 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <span x-show="!submitting">Intégrer les apprenants sélectionnés</span>
                                            <span x-show="submitting" x-cloak>Traitement...</span>
                                        </button>
                                    </form>
                                </div>
                            </template>
                            
                            <div x-show="availableStudents.length === 0" class="bg-slate-50 rounded-[2rem] p-8 text-center border border-dashed border-slate-200">
                                <svg class="size-10 text-slate-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-relaxed">
                                    Tous les apprenants du répertoire sont affectés.
                                </p>
                            </div>
                        </div>

                        <!-- Tab 2: Import -->
                        <div x-show="tab === 'import'" class="space-y-6" x-cloak>
                            <div class="bg-slate-50 p-5 rounded-[2rem] border border-slate-100 text-xs leading-relaxed text-slate-600">
                                <p class="font-black uppercase tracking-wider text-primary-500 mb-2">Instructions d'importation</p>
                                <p>Saisissez un e-mail ou un profil par ligne. Si l'étudiant n'existe pas, un compte sera créé.</p>
                                <div class="mt-3 font-mono bg-white p-3 rounded-xl border border-slate-200 text-[10px] text-slate-500 space-y-1 shadow-sm">
                                    <p class="text-slate-400">// Formats acceptés :</p>
                                    <p>prenom;nom;email@domain.com</p>
                                    <p>email@domain.com</p>
                                </div>
                            </div>

                            <form x-bind:action="`{{ url('/admin/classes') }}/${activeClasseId}/etudiants/import`" method="POST" class="space-y-4" x-on:submit="submitting = true">
                                @csrf
                                <div class="space-y-2">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none ml-1">Données des apprenants</label>
                                    <textarea name="import_data" required rows="5" placeholder="Jean;Dupont;jean.dupont@domain.com&#10;john.doe@example.com" 
                                              class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all font-mono resize-none"></textarea>
                                </div>

                                <button type="submit" :disabled="submitting"
                                        class="w-full py-4 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-primary-500 transition-all shadow-xl shadow-slate-900/20 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span x-show="!submitting">Lancer l'importation</span>
                                    <span x-show="submitting" x-cloak>Traitement...</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </x-ui.modal>
            </div>
        </template>
    </div>
@endsection