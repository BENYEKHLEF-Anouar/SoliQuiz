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
        }
    }">
        <!-- Header Strategy Section -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-10 border-b border-slate-100 mb-10">
            <div>
                <p class="text-label mb-1">Architecture</p>
                <h1 class="text-3xl font-heading font-black text-slate-900 tracking-tight leading-none italic uppercase">
                    Planification <span
                        class="text-transparent bg-clip-text bg-linear-to-r from-primary-600 to-primary-400">Cohortes</span>
                </h1>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-2 italic">Organisation structurelle
                    des groupes d'apprentissage</p>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden sm:flex items-center gap-4 px-5 py-2.5 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="text-right">
                        <p class="text-xs font-black text-slate-900 italic leading-none mb-1" x-text="totalCount">
                            {{ $classes->total() }}</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Total</p>
                    </div>
                </div>
                <button @click="$dispatch('open-modal', 'create-classe-modal')"
                    class="px-8 py-4 bg-slate-900 text-white text-[11px] font-black uppercase tracking-widest italic rounded-xl flex items-center gap-2 hover:bg-primary-600 active:scale-95 transition-all">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path d="M12 4v16m8-8H4" />
                    </svg>
                    Nouvelle Cohorte
                </button>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="flex flex-col md:flex-row gap-4 mb-8">
            <div class="flex-1 relative group">
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

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
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
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic group-hover/link:text-primary-400 transition-colors">Consulter le dossier de cohorte</span>
                            <svg class="size-3 text-slate-300 group-hover/link:translate-x-1 group-hover/link:text-primary-500 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                        </div>
                    </div>

                    <div class="relative z-10 pt-6 border-t border-slate-50 flex items-center justify-between mt-auto">
                        <div>
                            <span
                                class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1.5 italic">Tuteur
                                Académique</span>
                            <template x-if="classe.formateur">
                                <div class="flex items-center gap-2 group/tutor">
                                    <img class="size-6 rounded-md shadow-sm border border-white"
                                        :src="'https://ui-avatars.com/api/?name=' + encodeURIComponent(classe.formateur.nom_complet) + '&background=f8fafc&color=0f172a&bold=true'"
                                        alt="">
                                    <span class="text-[10px] font-black text-slate-900 uppercase italic leading-tight"
                                        x-text="classe.formateur.nom_complet"></span>
                                </div>
                            </template>
                            <template x-if="!classe.formateur">
                                <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest italic">Non
                                    assigné</span>
                            </template>
                        </div>
                    </div>
                </template>
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
                    <h3 class="text-2xl font-black text-slate-900 mb-2 italic">Aucune Séquence</h3>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px] italic">Initialisez votre structure
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
                    <form action="{{ route('admin.classes.store') }}" method="POST" class="space-y-6 pb-32">
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

                        <button type="submit"
                            class="w-full py-4 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-primary-500 transition-all shadow-xl shadow-slate-900/20 active:scale-95">
                            Enregistrer la Structure
                        </button>
                    </form>
                </x-ui.modal>

                <!-- Modal: Modifier Classe -->
                <x-ui.modal name="edit-classe-modal" title="Modifier Cohorte" maxWidth="md">
                    <form x-bind:action="`{{ url('/admin/classes') }}/${activeClasseId}`" method="POST"
                        class="space-y-6 pb-32">
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

                        <button type="submit"
                            class="w-full py-4 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-primary-500 transition-all shadow-xl shadow-slate-900/20 active:scale-95">
                            Consigner les Changements
                        </button>
                    </form>
                </x-ui.modal>

                <!-- Modal: Ajouter Étudiant -->
                <x-ui.modal name="add-student-modal" title="Inclusion Apprenant" maxWidth="md">
                    <!-- pb-32 prevents dropdown clipping -->
                    <form x-bind:action="`{{ url('/admin/classes') }}/${activeClasseId}/etudiants`" method="POST"
                        class="space-y-8 pb-32">
                        @csrf

                        <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 flex items-center gap-4">
                            <div
                                class="size-12 bg-emerald-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path
                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <div>
                                <p
                                    class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1 leading-none">
                                    Intégration Cohorte</p>
                                <p class="text-lg font-black text-slate-900 italic" x-text="activeClasseName"></p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Candidats
                                Disponibles</label>
                            @if($availableStudents->count() > 0)
                                <x-ui.select name="user_id" required placeholder="Sélectionner un étudiant..."
                                    class="!rounded-2xl !py-4 !px-5" :options="$availableStudents->map(fn($s) => ['value' => $s->id, 'label' => $s->nom_complet])->toArray()" />

                                <button type="submit"
                                    class="w-full py-4 bg-slate-900 text-white font-black rounded-2xl hover:bg-primary-500 transition-all uppercase tracking-widest text-xs shadow-xl shadow-slate-900/20 active:scale-95">
                                    Intégrer à la Cohorte
                                </button>
                            @else
                                <div class="bg-slate-100/50 rounded-2xl p-6 text-center border border-dashed border-slate-200">
                                    <p
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-relaxed italic">
                                        Inventaire Clos : Tous les apprenants sont affectés.</p>
                                </div>
                            @endif
                        </div>
                    </form>
                </x-ui.modal>
            </div>
        </template>
    </div>
@endsection