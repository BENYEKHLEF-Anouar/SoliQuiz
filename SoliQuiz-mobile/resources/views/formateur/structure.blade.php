@extends('components.layout.app')

@section('content')
<div x-data="pedagogieStructure" x-init="init()" class="h-full flex flex-col relative overflow-hidden font-sans bg-slate-50">
    <!-- Header -->
    @include('components.header.formateur-header')

    <main class="flex-1 overflow-y-auto w-full px-5 py-8 pb-32 hide-scrollbar relative">
        <!-- Global Loading State -->
        <div x-show="loading" 
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 z-50 bg-slate-50 flex items-center justify-center">
            <x-feedback.loader message="Chargement de l'ossature..." />
        </div>

        <div x-show="!loading" 
             x-transition:enter="transition ease-out duration-700 delay-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0">
            
            <!-- Page Title -->
            <div class="mb-6">
                <h2 class="text-3xl font-heading font-extrabold text-slate-900 tracking-tight">PEDAGOGIE</h2>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">Structure Pédagogique</p>
            </div>

            <!-- Stats Overview Bar -->
            <div class="grid grid-cols-3 gap-3 bg-white p-4 rounded-3xl border border-slate-100 shadow-[0_8px_30px_-4px_rgba(0,0,0,0.02)] mb-6">
                <div class="text-center">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-wider mb-1">Sessions</p>
                    <p class="text-lg font-black text-slate-900" x-text="stats.seances"></p>
                </div>
                <div class="border-l border-slate-100 text-center">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-wider mb-1">Unités (UA)</p>
                    <p class="text-lg font-black text-primary-500" x-text="stats.unites"></p>
                </div>
                <div class="border-l border-slate-100 text-center">
                    <p class="text-[8px] font-black text-slate-400 uppercase tracking-wider mb-1">Compétences</p>
                    <p class="text-lg font-black text-emerald-500" x-text="stats.competences"></p>
                </div>
            </div>

            <!-- Filter Controls -->
            <div class="space-y-3 mb-6">
                <!-- Search Input -->
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="size-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" x-model="search" @input="filterStructure"
                           placeholder="Rechercher une UA, code ou skill..."
                           class="w-full h-12 bg-white border border-slate-100 rounded-2xl pl-11 pr-10 text-xs font-bold text-slate-950 placeholder:text-slate-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 outline-none transition-all shadow-sm">
                    <button x-show="search" @click="search = ''; filterStructure()" class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Creator Filter Dropdown -->
                <div class="flex gap-2" x-show="isAdmin()" style="display: none;" x-cloak>
                    <div x-data="{ open: false }" class="relative flex-1" @click.outside="open = false">
                        <button @click="open = !open"
                                class="w-full h-11 px-4 bg-white border border-slate-100 rounded-2xl flex items-center justify-between text-[10px] font-black uppercase tracking-wider text-slate-600 shadow-sm outline-none">
                            <span class="truncate pr-1" x-text="creatorLabel">Tous les créateurs</span>
                            <svg class="size-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" style="display: none;" class="absolute top-full left-0 w-full mt-2 bg-white border border-slate-100 rounded-2xl shadow-xl z-50 p-2 max-h-48 overflow-y-auto">
                            <button @click="selectCreator('', 'Tous les créateurs'); open = false" class="w-full px-4 py-2 text-left text-[10px] font-black uppercase text-slate-400 hover:bg-slate-50 rounded-lg">Tous les créateurs</button>
                            <template x-for="c in creators" :key="c.id">
                                <button @click="selectCreator(c.id, c.prenom + ' ' + c.nom); open = false" class="w-full px-4 py-2 text-left text-[10px] font-black uppercase text-slate-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg truncate" x-text="c.prenom + ' ' + c.nom"></button>
                            </template>
                        </div>
                    </div>

                    <!-- Clear Filter Button -->
                    <button x-show="search || selectedCreatorId" @click="clearFilters()" style="display: none;"
                            class="size-11 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center hover:bg-rose-100 active:scale-95 transition-all shadow-sm shrink-0">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- Hierarchy Tree (Sessions) -->
            <div class="space-y-4">
                <template x-for="seance in filteredSeances" :key="seance.id">
                    <div x-data="{ expanded: false }" class="bg-white border border-slate-100 shadow-[0_8px_30px_-4px_rgba(0,0,0,0.02)] rounded-[2rem] overflow-hidden transition-all duration-300">
                        
                        <!-- Session Header -->
                        <div @click="expanded = !expanded" class="p-5 flex items-center justify-between cursor-pointer active:bg-slate-50/50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="size-12 rounded-2xl flex items-center justify-center transition-colors shrink-0"
                                     :class="expanded ? 'bg-slate-900 text-white' : 'bg-slate-50 text-slate-400'">
                                    <svg class="size-5 transition-transform duration-300" :class="expanded ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7" /></svg>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5 mb-0.5">
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Session</span>
                                        <span x-show="seance.date_debut" class="size-1 bg-slate-200 rounded-full"></span>
                                        <span class="text-[8px] font-black text-primary-500 uppercase tracking-wider" x-text="seance.date_debut ? 'Du ' + formatDate(seance.date_debut) + ' au ' + formatDate(seance.date_fin) : ''"></span>
                                    </div>
                                    <h3 class="text-base font-heading font-extrabold text-slate-900 leading-tight truncate" x-text="seance.nom"></h3>
                                    <div class="flex items-center gap-1.5 mt-1">
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest" x-text="seance.creator_name"></span>
                                        <span class="px-1.5 py-0.5 rounded text-[7px] font-black uppercase tracking-wider"
                                              :class="seance.creator_role === 'admin' ? 'bg-slate-900 text-white' : 'bg-primary-50 text-primary-600 border border-primary-100'"
                                              x-text="seance.creator_role"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="bg-slate-100 text-slate-600 text-[10px] font-black px-2.5 py-1 rounded-xl" x-text="seance.unites.length + ' UA'"></span>
                            </div>
                        </div>

                        <!-- Session Details (Collapsible UA list) -->
                        <div x-show="expanded" x-collapse class="border-t border-slate-50 bg-slate-50/20">
                            <div class="p-5 space-y-3">
                                <template x-for="ua in seance.unites" :key="ua.id">
                                    <div x-data="{ expandedUa: false }" class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm transition-all duration-300">
                                        <!-- UA Header -->
                                        <div @click="expandedUa = !expandedUa" class="p-4 flex items-center justify-between cursor-pointer active:bg-slate-50/50 transition-colors">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="size-8 rounded-xl flex items-center justify-center transition-colors shrink-0"
                                                     :class="expandedUa ? 'bg-primary-500 text-white' : 'bg-slate-50 text-slate-400'">
                                                    <svg class="size-4 transition-transform duration-300" :class="expandedUa ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7" /></svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="flex items-center gap-1.5 mb-0.5">
                                                        <span class="text-[8px] font-black text-primary-600 uppercase tracking-widest" x-text="ua.code"></span>
                                                        <span x-show="ua.date_debut" class="size-1 bg-slate-200 rounded-full"></span>
                                                        <span class="text-[7px] font-bold text-slate-400" x-text="ua.date_debut ? '(' + formatDate(ua.date_debut) + ' - ' + formatDate(ua.date_fin) + ')' : ''"></span>
                                                    </div>
                                                    <h4 class="text-xs font-bold text-slate-800 leading-tight truncate" x-text="ua.nom"></h4>
                                                </div>
                                            </div>
                                            <div class="shrink-0 pl-2">
                                                <span class="bg-emerald-50 text-emerald-600 text-[8px] font-black px-2 py-0.5 rounded-lg uppercase" x-text="ua.competences.length + ' compétences'"></span>
                                            </div>
                                        </div>

                                        <!-- UA Competences list -->
                                        <div x-show="expandedUa" x-collapse class="border-t border-slate-50 bg-slate-50/20 p-4 space-y-2">
                                            <template x-for="comp in ua.competences" :key="comp.id">
                                                <div class="bg-white border border-slate-100 p-3.5 rounded-xl hover:border-emerald-200 transition-colors">
                                                    <div class="flex items-center gap-2 mb-1.5">
                                                        <span class="size-1.5 bg-emerald-500 rounded-full"></span>
                                                        <span class="text-[8px] font-black text-emerald-600 uppercase tracking-wider" x-text="comp.code"></span>
                                                        <span class="text-xs font-bold text-slate-900" x-text="comp.libelle"></span>
                                                    </div>
                                                    <p class="text-[10px] text-slate-500 font-medium leading-relaxed" x-text="comp.description || 'Aucune description fournie.'"></p>
                                                </div>
                                            </template>
                                            <div x-show="ua.competences.length === 0" class="text-center py-4">
                                                <p class="text-[9px] font-black uppercase text-slate-400">Aucune compétence définie</p>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="seance.unites.length === 0" class="text-center py-6">
                                    <p class="text-xs text-slate-400 font-bold">Aucune unité d'apprentissage enregistrée dans cette session.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Empty State -->
            <div x-show="filteredSeances.length === 0" class="text-center py-20">
                <div class="size-20 bg-slate-100 rounded-3xl flex items-center justify-center mx-auto mb-4 text-slate-300">
                    <svg class="size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Aucune session ne correspond</p>
            </div>
        </div>
    </main>

    <!-- Navigation -->
    @include('components.nav.formateur-bottom-nav')

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('pedagogieStructure', () => ({
            seances: [],
            filteredSeances: [],
            creators: [],
            loading: true,
            search: '',
            selectedCreatorId: '',
            creatorLabel: 'Tous les créateurs',

            isAdmin() {
                const profile = Alpine.store('config').profile;
                return profile && (profile.role === 'admin' || profile.role === 'Admin');
            },

            stats: {
                seances: 0,
                unites: 0,
                competences: 0
            },

            async init() {
                await this.fetchStructure();
            },

            async fetchStructure() {
                this.loading = true;
                try {
                    const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/formateur/pedagogie`);
                    const data = await response.json();

                    this.seances = data.seances || [];
                    this.creators = data.creators || [];
                    
                    this.calculateStats();
                    this.filterStructure();
                } catch (e) {
                    console.error('Failed to load pedagogical structure', e);
                } finally {
                    setTimeout(() => { this.loading = false; }, 400);
                }
            },

            calculateStats() {
                let uCount = 0;
                let cCount = 0;

                this.seances.forEach(s => {
                    uCount += s.unites.length;
                    s.unites.forEach(u => {
                        cCount += u.competences.length;
                    });
                });

                this.stats = {
                    seances: this.seances.length,
                    unites: uCount,
                    competences: cCount
                };
            },

            selectCreator(id, name) {
                this.selectedCreatorId = id;
                this.creatorLabel = name;
                this.filterStructure();
            },

            clearFilters() {
                this.search = '';
                this.selectedCreatorId = '';
                this.creatorLabel = 'Tous les créateurs';
                this.filterStructure();
            },

            filterStructure() {
                let filtered = JSON.parse(JSON.stringify(this.seances)); // Deep clone to filter safely

                // Filter by creator if specified
                if (this.selectedCreatorId) {
                    filtered = filtered.filter(s => s.user_id == this.selectedCreatorId);
                }

                // Filter by search query (UA name, UA code, Competence libelle, Session name)
                if (this.search.trim()) {
                    const q = this.search.toLowerCase().trim();
                    filtered = filtered.map(s => {
                        // Keep session if name matches OR if any child matches
                        const matchSession = s.nom.toLowerCase().includes(q);
                        
                        s.unites = s.unites.map(u => {
                            const matchUa = u.nom.toLowerCase().includes(q) || u.code.toLowerCase().includes(q);
                            
                            u.competences = u.competences.filter(c => {
                                return c.libelle.toLowerCase().includes(q) || c.code.toLowerCase().includes(q);
                            });

                            if (matchUa || u.competences.length > 0) {
                                return u;
                            }
                            return null;
                        }).filter(Boolean);

                        if (matchSession || s.unites.length > 0) {
                            return s;
                        }
                        return null;
                    }).filter(Boolean);
                }

                this.filteredSeances = filtered;
            },

            formatDate(dateStr) {
                if (!dateStr) return '';
                const parts = dateStr.split('-');
                if (parts.length !== 3) return dateStr;
                return `${parts[2]}/${parts[1]}`;
            }
        }));
    });
</script>
@endsection