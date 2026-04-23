@extends('layouts.app')

@section('title', 'Gestion des Cohortes - SoliQuiz')

@section('content')
<div class="fade-in" x-data="{
    activeClasseId: null,
    activeClasseName: '',
    search: '{{ $search ?? '' }}',
    classes: {{ $classes->toJson() }},
    loading: false,
    searchTimer: null,
    performSearch() {
        clearTimeout(this.searchTimer);
        this.searchTimer = setTimeout(() => {
            this.loading = true;
            fetch('{{ route('admin.classes.search') }}?search=' + encodeURIComponent(this.search), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            })
            .then(r => r.json())
            .then(data => { this.classes = data; this.loading = false; })
            .catch(() => { this.loading = false; });
        }, 300);
    }
}">
    <!-- Header Strategy Section -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8 pb-8 border-b border-slate-100">
        <div>
            <p class="text-label mb-1">Architecture</p>
            <h1 class="text-3xl font-heading font-black text-slate-900 tracking-tight leading-none italic uppercase">
                Planification <span class="text-transparent bg-clip-text bg-linear-to-r from-primary-600 to-primary-400">Cohortes</span>
            </h1>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-2 italic">Organisation structurelle des groupes d'apprentissage</p>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-4">
            <div class="relative group w-full sm:w-72">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                    <svg class="size-4 text-slate-300 group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" x-model="search" @input="performSearch()"
                       placeholder="Filtrer le répertoire..." 
                       class="w-full bg-white border border-slate-100 rounded-xl py-2.5 pl-11 pr-4 text-sm font-bold text-slate-900 placeholder:text-slate-300 focus:ring-4 focus:ring-primary-500/5 focus:border-primary-500 outline-none transition-all shadow-sm">
            </div>
            
            <button @click="$dispatch('open-modal', 'create-classe-modal')"
                class="w-full sm:w-auto px-6 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-colors shadow-lg shadow-slate-900/20 flex items-center justify-center gap-2">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
                Nouvelle Cohorte
            </button>
        </div>
    </div>

    <!-- Classes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <template x-for="classe in classes" :key="classe.id">
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden group flex flex-col">
                <div class="absolute -right-8 -top-8 size-32 bg-primary-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                
                <div class="flex justify-between items-start mb-6 relative z-10">
                    <span class="inline-flex py-1.5 px-3 rounded-full text-[9px] font-black uppercase tracking-widest bg-slate-900 text-white shadow-sm"
                          x-text="classe.promotion || 'Formation Régulière'"></span>
                    
                    <div class="flex items-center gap-2">
                        <button type="button" 
                                @click.prevent="$dispatch('confirm', { 
                                    title: 'Démanteler cette classe ?', 
                                    message: 'Toutes les données associées seront archivées ou supprimées.', 
                                    onConfirm: 'delete-classe-' + classe.id 
                                })"
                                class="text-slate-300 hover:text-rose-500 transition-colors p-1">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                        <form :id="'delete-classe-' + classe.id" :action="'{{ url('/admin/classes') }}/' + classe.id" method="POST" class="hidden">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                        </form>
                    </div>
                </div>

                <div class="relative z-10 mb-6">
                    <h3 class="text-2xl font-heading font-black text-slate-900 tracking-tight leading-none mb-3 group-hover:text-primary-600 transition-colors" x-text="classe.nom"></h3>
                    <div class="flex items-center gap-2">
                        <div class="size-5 bg-primary-100 rounded flex items-center justify-center text-primary-600 text-[10px] font-black" x-text="classe.etudiants_count"></div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Effectif Total</span>
                    </div>
                </div>

                <div class="relative z-10 pt-6 border-t border-slate-50 flex items-center justify-between mt-auto">
                    <div>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1.5 italic">Tuteur Académique</span>
                        <template x-if="classe.formateur">
                            <div class="flex items-center gap-2">
                                <img class="size-6 rounded-md" :src="'https://ui-avatars.com/api/?name=' + encodeURIComponent(classe.formateur.nom_complet) + '&background=f8fafc&color=0f172a&bold=true'" alt="">
                                <span class="text-[10px] font-black text-slate-900 uppercase italic leading-tight" x-text="classe.formateur.nom_complet"></span>
                            </div>
                        </template>
                        <template x-if="!classe.formateur">
                            <button @click="activeClasseId = classe.id; activeClasseName = classe.nom; $dispatch('open-modal', 'assign-formateur-modal')" 
                                    class="group/assign inline-flex items-center gap-2 text-[9px] font-black text-primary-500 uppercase tracking-widest hover:text-primary-600 transition-colors">
                                <div class="size-5 bg-primary-50 rounded flex items-center justify-center group-hover/assign:bg-primary-500 group-hover/assign:text-white transition-all">
                                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
                                </div>
                                Assigner
                            </button>
                        </template>
                    </div>
                    
                    <a :href="'{{ url('/admin/classes') }}/' + classe.id" 
                       class="size-10 bg-slate-900 text-white rounded-xl flex items-center justify-center hover:bg-primary-500 shadow-md shadow-slate-900/10 transition-all group/go">
                        <svg class="size-5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            </div>
        </template>
    </div>

    <!-- Empty State -->
    <template x-if="classes.length === 0">
        <div class="p-20 text-center bg-white rounded-[50px] border border-slate-100 shadow-sm mt-8">
            <div class="size-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8 text-slate-300 border border-slate-100 shadow-inner">
                <svg class="size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
            <h3 class="text-2xl font-black text-slate-900 mb-2 italic">Aucune Séquence</h3>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px] italic">Initialisez votre structure pédagogique</p>
        </div>
    </template>

    <!-- Modals Layer -->
    <template x-teleport="body">
        <div>
            <!-- Modal: Créer Classe -->
            <x-ui.modal name="create-classe-modal" title="Architecture Cohorte" maxWidth="md">
                <form action="{{ route('admin.classes.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Identifiant de Groupe</label>
                        <input type="text" name="nom" required placeholder="Ex: Développement Fullstack"
                               class="w-full bg-slate-50 border border-transparent rounded-xl py-2.5 px-4 text-sm font-bold text-slate-900 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 outline-none transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Promotion <span class="text-slate-300 normal-case">(Optionnel)</span></label>
                        <input type="text" name="promotion" placeholder="Ex: P-2024 / Elite"
                               class="w-full bg-slate-50 border border-transparent rounded-xl py-2.5 px-4 text-sm font-bold text-slate-900 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 outline-none transition-all">
                    </div>
                    
                    <button type="submit" class="w-full py-3 bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-primary-500 transition-colors shadow-lg shadow-slate-900/20 mt-2">
                        Enregistrer la Structure
                    </button>
                </form>
            </x-ui.modal>

            <!-- Modal: Assigner Formateur -->
            <x-ui.modal name="assign-formateur-modal" title="Désignation Expert" maxWidth="md">
                <div class="mb-6 p-4 bg-primary-50 rounded-xl border border-primary-100">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-primary-600 mb-1 leading-none">Cohorte Ciblée</p>
                    <p class="text-sm font-black text-slate-900 italic" x-text="activeClasseName"></p>
                </div>

                <form x-bind:action="`{{ url('/admin/classes') }}/${activeClasseId}/formateur`" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Répertoire des Experts</label>
                        <x-ui.select 
                            name="formateur_id" 
                            required
                            placeholder="Sélectionner un formateur..."
                            class="!py-2.5 !px-4 !rounded-xl text-sm"
                            :options="$formateurs->map(fn($f) => ['value' => $f->id, 'label' => $f->nom_complet])->toArray()"
                        />
                    </div>
                    
                    <button type="submit" class="w-full py-3 bg-slate-900 text-white font-black rounded-xl hover:bg-primary-500 transition-colors uppercase tracking-widest text-xs shadow-lg shadow-slate-900/20">
                        Confirmer l'Assignation
                    </button>
                </form>
            </x-ui.modal>
        </div>
    </template>
</div>
@endsection