@extends('layouts.app')

@section('title', 'Banque de QCM - SoliQuiz')

@section('page-title', 'Banque de QCM Repository')

@section('content')
    <div class="fade-in space-y-12 pb-20" x-data="adminQcmBank({
            initialQcms: {{ Js::from($qcms->items()) }},
            initialSearch: '{{ $search }}',
            initialStatut: '{{ $statut }}'
         })">

        <!-- Header Strategy Section -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-10 border-b border-slate-100 mb-10">
            <div>
                <p class="text-label mb-1">Ressources Pédagogiques</p>
                <h1 class="text-3xl font-heading font-black text-slate-900 tracking-tight leading-none italic uppercase">
                    Global <span
                        class="text-transparent bg-clip-text bg-linear-to-r from-primary-600 to-primary-400">Library</span>
                </h1>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-2 italic">Gestion centralisée des
                    actifs d'évaluation</p>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden sm:flex items-center gap-4 px-5 py-2.5 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="text-right">
                        <p class="text-xs font-black text-slate-900 italic leading-none mb-1" x-text="totalCount">
                            {{ $qcms->total() }}</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Total QCM</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="flex flex-col md:flex-row gap-4 items-center">
            <div class="flex-1 w-full flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative group">
                    <label
                        class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-primary-500 transition-colors pointer-events-none">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </label>
                    <input type="text" x-model="search" @input.debounce.300ms="applyFilters()"
                        placeholder="Rechercher une évaluation, un module ou un auteur..."
                        class="w-full h-11 pl-11 pr-11 bg-white border border-slate-100 rounded-xl font-bold text-slate-900 text-sm placeholder:text-slate-300 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all shadow-sm group-hover:shadow-md outline-none">
                    
                    <!-- Live Search Loader -->
                    <div x-show="loading" 
                         class="absolute right-4 top-1/2 -translate-y-1/2"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-50"
                         x-transition:enter-end="opacity-100 scale-100"
                         style="display: none;">
                        <div class="size-4 border-2 border-primary-200 border-t-primary-500 rounded-full animate-spin"></div>
                    </div>
                </div>

                <div class="relative flex items-center self-stretch" x-data="{ open: false }">
                    <button type="button" @click="open = !open"
                        class="h-11 px-5 bg-white border border-slate-100 rounded-xl transition-all shadow-sm group flex items-center gap-2"
                        :class="statut ? 'text-primary-600 bg-primary-50/50 border-primary-200' : 'text-slate-400'">
                        <svg class="size-4 group-hover:rotate-180 transition-transform duration-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span class="text-[10px] font-black uppercase tracking-wider" x-text="statutLabel">Tous les
                            statuts</span>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="absolute top-full right-0 mt-4 w-64 bg-white rounded-[24px] shadow-premium border border-slate-100 p-2 z-50 overflow-hidden"
                        style="display: none;">
                        <button @click="statut = ''; open = false; applyFilters()"
                            class="w-full text-left px-4 py-3 rounded-xl text-xs font-black uppercase tracking-wider transition-colors"
                            :class="!statut ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-50'">
                            Tous les statuts
                        </button>
                        <button @click="statut = 'brouillon'; open = false; applyFilters()"
                            class="w-full text-left px-4 py-3 rounded-xl text-xs font-black uppercase tracking-wider transition-colors"
                            :class="statut === 'brouillon' ? 'bg-amber-100 text-amber-600' : 'text-slate-600 hover:bg-amber-50 hover:text-amber-600'">
                            Brouillon
                        </button>
                        <button @click="statut = 'public'; open = false; applyFilters()"
                            class="w-full text-left px-4 py-3 rounded-xl text-xs font-black uppercase tracking-wider transition-colors"
                            :class="statut === 'public' ? 'bg-emerald-100 text-emerald-600' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-600'">
                            Public
                        </button>
                        <button @click="statut = 'termine'; open = false; applyFilters()"
                            class="w-full text-left px-4 py-3 rounded-xl text-xs font-black uppercase tracking-wider transition-colors"
                            :class="statut === 'termine' ? 'bg-slate-100 text-slate-500' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-500'">
                            Terminé
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- QCM Grid -->
        <div class="relative mt-6" :class="loading && qcms.length === 0 ? 'min-h-[200px]' : ''">
            <!-- Loading Overlay -->
            <div x-show="loading"
                class="absolute inset-0 bg-white/40 backdrop-blur-[2px] z-20 flex flex-col items-center justify-center rounded-[40px] min-h-[200px]"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                style="display: none;">
                <div class="flex flex-col items-center gap-3 scale-90">
                    <div class="size-10 border-4 border-slate-100 border-t-primary-500 rounded-full animate-spin shadow-sm"></div>
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] animate-pulse">Synchronisation...
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 gap-8"
                :class="loading ? 'opacity-50 pointer-events-none' : ''">
                <template x-for="qcm in qcms" :key="qcm.id">
                    <div x-data="{ options: false }"
                        :class="options ? 'z-[100]' : 'z-10'"
                        class="group bg-white rounded-[40px] p-8 border border-slate-100 shadow-sm hover:shadow-premium hover:-translate-y-2 transition-all duration-500 relative flex flex-col h-full">
                        <div class="absolute inset-0 rounded-[40px] overflow-hidden pointer-events-none">
                            <div
                                class="absolute -right-12 -top-12 size-40 bg-slate-50/50 rounded-full group-hover:bg-primary-50/50 group-hover:scale-125 transition-all duration-700">
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="flex items-center justify-between mb-8 relative z-20">
                            <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest italic"
                                :class="qcm.statut === 'public' ? 'bg-emerald-100 text-emerald-600' : (qcm.statut === 'termine' ? 'bg-slate-100 text-slate-500' : 'bg-amber-100 text-amber-600')"
                                x-text="qcm.statut">
                            </span>
                            <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest font-mono"
                                x-text="'QCM-' + String(qcm.id).padStart(4, '0')"></span>
                        </div>

                        <div class="relative z-10 flex-1">
                            <h3 class="text-xl font-heading font-black text-slate-900 group-hover:text-primary-600 transition-colors leading-tight mb-4 uppercase"
                                x-text="qcm.titre"></h3>

                            <div class="space-y-4 mb-10">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="size-8 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-primary-500">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="3">
                                            <path
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p
                                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">
                                            Module</p>
                                        <p class="text-xs font-bold text-slate-600 truncate max-w-[200px]"
                                            x-text="qcm.unite_apprentissage ? qcm.unite_apprentissage.nom : 'Module Transversal'">
                                        </p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div
                                        class="bg-slate-50 rounded-2xl p-4 border border-slate-100 transition-colors group-hover:bg-white group-hover:shadow-sm">
                                        <p
                                            class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">
                                            Durée</p>
                                        <p class="text-lg font-black text-slate-900 italic leading-none"><span
                                                x-text="qcm.duree_minutes"></span><span
                                                class="text-[10px] uppercase ml-1">min</span></p>
                                    </div>
                                    <div
                                        class="bg-slate-50 rounded-2xl p-4 border border-slate-100 transition-colors group-hover:bg-white group-hover:shadow-sm">
                                        <p
                                            class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">
                                            Questions</p>
                                        <p class="text-lg font-black text-slate-900 italic leading-none"
                                            x-text="qcm.questions_count"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="pt-6 border-t border-slate-50 mt-auto flex items-center justify-between relative z-10">
                            <div class="flex items-center gap-3">
                                <img class="size-8 rounded-lg bg-slate-100 border border-slate-200"
                                    :src="'https://ui-avatars.com/api/?name=' + encodeURIComponent(qcm.formateur ? qcm.formateur.nom_complet : 'Unknown') + '&background=f8fafc&color=64748b&bold=true'"
                                    alt="">
                                <div class="flex flex-col">
                                    <p class="text-[10px] font-black text-slate-900 uppercase italic leading-none truncate max-w-[100px]"
                                        x-text="qcm.formateur ? qcm.formateur.prenom : '?'"></p>
                                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-[0.1em] mt-1">Formateur
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <button @click="window.location.href = '/formateur/qcm/' + qcm.id + '/edit'"
                                    class="size-10 bg-slate-900 text-white rounded-xl flex items-center justify-center hover:bg-primary-500 hover:shadow-lg hover:-translate-y-0.5 hover:shadow-primary-500/20 transition-all shadow-sm group">
                                    <svg class="size-4 group-hover:scale-110 transition-transform" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>

                                <div class="relative">
                                    <button @click="options = !options" type="button"
                                        class="size-10 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center hover:bg-slate-200 hover:text-slate-900 transition-all">
                                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="3">
                                            <path
                                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </button>

                                    <div x-show="options" @click.away="options = false"
                                        class="absolute bottom-full right-0 mb-3 w-56 bg-white rounded-[24px] border border-slate-100 shadow-premium p-2 z-[100]"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 translate-y-4"
                                        x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">

                                        <button @click="toggleStatus(qcm)"
                                            class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-[9px] font-black uppercase tracking-widest transition-colors group/opt"
                                            :class="qcm.statut === 'public' ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50'">
                                            <span x-text="qcm.statut === 'public' ? 'Dépublier' : 'Publier'"></span>
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="3">
                                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>

                                        <button type="button" @click.prevent="deleteQcm(qcm.id)"
                                            class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-rose-500 hover:bg-rose-50 transition-colors text-[9px] font-black uppercase tracking-widest">
                                            <span>Supprimer</span>
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="3">
                                                <path
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Empty State -->
            <div x-show="qcms.length === 0 && !loading"
                class="col-span-full glass p-20 text-center rounded-[50px] border-2 border-dashed border-slate-100"
                style="display: none;">
                <div
                    class="size-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8 border border-slate-100 shadow-inner">
                    <svg class="size-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-heading font-black text-slate-900 mb-2">Banque Vide</h3>
                <p class="text-slate-400 font-medium italic">Aucun actif d'évaluation ne correspond à votre recherche.</p>
            </div>
        </div>

        <!-- Pagination placeholder (static for now, will reload on page change) -->
        <div class="mt-12" x-show="qcms.length > 0"
             :class="loading ? 'opacity-50 pointer-events-none transition-opacity duration-300' : 'transition-opacity duration-300'">
            {{ $qcms->links() }}
        </div>
    </div>

    @push('scripts')
        <script>
            function adminQcmBank(config) {
                return {
                    qcms: config.initialQcms,
                    search: config.initialSearch,
                    statut: config.initialStatut,
                    loading: false,
                    totalCount: {{ $qcms->total() }},

                    get statutLabel() {
                        if (!this.statut) return 'Tous les statuts';
                        return this.statut.charAt(0).toUpperCase() + this.statut.slice(1);
                    },

                    async applyFilters() {
                        this.loading = true;
                        const url = new URL('{{ route('admin.qcms.search') }}');
                        if (this.search) url.searchParams.set('search', this.search);
                        if (this.statut) url.searchParams.set('statut', this.statut);

                        try {
                            const response = await fetch(url);
                            const data = await response.json();
                            this.qcms = data.data;
                            this.totalCount = data.total;

                            // Update URL for bookmarking
                            const browserUrl = new URL(window.location);
                            if (this.search) browserUrl.searchParams.set('search', this.search); else browserUrl.searchParams.delete('search');
                            if (this.statut) browserUrl.searchParams.set('statut', this.statut); else browserUrl.searchParams.delete('statut');
                            history.pushState({}, '', browserUrl);
                        } catch (error) {
                            console.error('Erreur de recherche:', error);
                        } finally {
                            this.loading = false;
                        }
                    },

                    async toggleStatus(qcm) {
                        try {
                            const response = await fetch(`/formateur/qcm/${qcm.id}/toggle`, {
                                method: 'PATCH',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json'
                                }
                            });
                            if (response.ok) {
                                const data = await response.json();
                                qcm.statut = data.statut;
                                window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Statut mis à jour', type: 'success' } }));
                            }
                        } catch (error) {
                            window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Erreur lors de la mise à jour', type: 'error' } }));
                        }
                    },

                    deleteQcm(id) {
                        window.dispatchEvent(new CustomEvent('confirm', {
                            detail: {
                                title: 'Supprimer ce QCM ?',
                                message: 'Cette action est irréversible et supprimera définitivement toutes les données et tentatives liées à cette évaluation.',
                                type: 'danger',
                                confirmText: 'Supprimer l\'actif',
                                onConfirm: () => this.executeDelete(id)
                            }
                        }));
                    },

                    async executeDelete(id) {
                        this.loading = true;
                        try {
                            const response = await fetch(`/formateur/qcm/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json'
                                }
                            });
                            if (response.ok) {
                                this.qcms = this.qcms.filter(q => q.id !== id);
                                this.totalCount--;
                                window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'QCM supprimé définitivement', type: 'success' } }));
                            }
                        } catch (error) {
                            window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Erreur lors de la suppression', type: 'error' } }));
                        } finally {
                            this.loading = false;
                        }
                    }
                }
            }
        </script>
    @endpush
@endsection