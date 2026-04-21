@extends('layouts.app')

@section('title', 'Ingénierie Pédagogique - Formateur')

@section('content')
<div class="reveal active" x-data="{
    activeSeanceId: null,
    activeUaId: null,
    // Seance modal
    seanceMode: 'create',
    seanceId: null,
    seanceNom: '',
    seanceDate: '',
    // UA modal
    uaMode: 'create',
    uaId: null,
    uaNom: '',
    uaCode: '',
    // Comp modal
    compMode: 'create',
    compId: null,
    compNom: '',
    compCode: '',
    // Helpers
    openSeanceCreate() {
        this.seanceMode = 'create'; this.seanceId = null; this.seanceNom = ''; this.seanceDate = '';
        $dispatch('open-modal', 'seance-modal');
    },
    openSeanceEdit(id, nom, date) {
        this.seanceMode = 'edit'; this.seanceId = id; this.seanceNom = nom; this.seanceDate = date;
        $dispatch('open-modal', 'seance-modal');
    },
    openUaCreate(seanceId) {
        this.uaMode = 'create'; this.uaId = null; this.uaNom = ''; this.uaCode = ''; this.activeSeanceId = seanceId;
        $dispatch('open-modal', 'ua-modal');
    },
    openUaEdit(id, nom, code) {
        this.uaMode = 'edit'; this.uaId = id; this.uaNom = nom; this.uaCode = code;
        $dispatch('open-modal', 'ua-modal');
    },
    openCompCreate(uaId) {
        this.compMode = 'create'; this.compId = null; this.compNom = ''; this.compCode = ''; this.activeUaId = uaId;
        $dispatch('open-modal', 'comp-modal');
    },
    openCompEdit(id, nom, code) {
        this.compMode = 'edit'; this.compId = id; this.compNom = nom; this.compCode = code;
        $dispatch('open-modal', 'comp-modal');
    }
}">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 mb-12 pb-10 border-b border-slate-100">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">
                <span>Espace Formateur</span>
                <span class="size-1 rounded-full bg-slate-300"></span>
                <span class="text-slate-600">Ingénierie Pédagogique</span>
            </nav>
            <h1 class="text-4xl lg:text-5xl font-heading font-black text-slate-900 tracking-tight leading-none mb-4">
                Architecture <span class="text-transparent bg-clip-text bg-linear-to-r from-primary-600 to-primary-400">Pédagogique</span>
            </h1>
            <p class="text-slate-500 font-medium max-w-xl leading-relaxed">
                Organisez vos sessions, définissez vos unités d'apprentissage et fixez les compétences que vos étudiants devront maîtriser.
            </p>
        </div>
        
        <button @click="openSeanceCreate()"
            class="group btn-premium px-8 py-5 bg-slate-900 text-white rounded-3xl font-black text-xs uppercase tracking-[0.2em] hover:bg-primary-500 shadow-xl shadow-slate-900/10 hover:shadow-primary-500/20 active:scale-95 transition-all flex items-center justify-center gap-3">
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
            Nouvelle Session
        </button>
    </div>

    <!-- Header Section -->

    <!-- Hierarchy Tree -->
    <div class="space-y-6">
        @forelse($seances as $seance)
            <div x-data="{ expanded: false }" class="group" :key="{{ $seance->id }}">
                <div class="bg-white/90 backdrop-blur-md p-8 md:p-10 rounded-[48px] border border-slate-200/70 shadow-sm group-hover:shadow-premium transition-all duration-500 relative overflow-hidden"
                     @click="expanded = !expanded" :class="expanded ? 'border-primary-200 shadow-premium' : ''">
                    
                    <div class="flex items-center justify-between relative z-10 cursor-pointer">
                        <div class="flex items-center gap-10">
                            <div class="size-20 rounded-[28px] bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 transition-all duration-700"
                                 :class="expanded ? 'bg-primary-500 text-white rotate-180 scale-110 shadow-2xl shadow-primary-500/30' : ''">
                                <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 9l-7 7-7-7" /></svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-black text-primary-500 uppercase tracking-[0.4em] block mb-3">Session Pédagogique</span>
                                <h2 class="text-3xl font-heading font-black text-slate-900 tracking-tight leading-none group-hover:text-primary-600 transition-colors">
                                    {{ $seance->nom }}
                                </h2>
                                <div class="flex items-center gap-2 mt-4">
                                    <span class="size-1.5 rounded-full bg-slate-200"></span>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $seance->date->format('d F Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-10">
                            <div class="text-right hidden sm:block">
                                <span class="block text-2xl font-black font-heading text-slate-900 leading-none">{{ $seance->unitesApprentissage->count() }}</span>
                                <span class="text-[9px] uppercase font-black text-slate-400 tracking-widest">Séquences UA</span>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <button @click.stop="openSeanceEdit({{ $seance->id }}, '{{ addslashes($seance->nom) }}', '{{ $seance->date->format('Y-m-d') }}')"
                                        class="size-12 rounded-2xl bg-primary-50 text-primary-500 flex items-center justify-center hover:bg-primary-500 hover:text-white transition-all hover:rotate-12">
                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                                <form id="delete-seance-{{ $seance->id }}" action="{{ route('formateur.pedagogie.seance.destroy', $seance->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button" 
                                            @click.stop="$dispatch('confirm', { 
                                                title: 'Supprimer la session ?', 
                                                message: 'Toutes les UA et compétences liées seront également supprimées.', 
                                                onConfirm: 'delete-seance-{{ $seance->id }}' 
                                            })"
                                            class="size-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all hover:rotate-12">
                                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Expansion: UAs -->
                    <div x-show="expanded" x-collapse>
                        <div class="mt-12 space-y-6 pl-0 md:pl-12 border-l-2 border-slate-50 ml-0 md:ml-8">
                            <div class="flex justify-between items-center mb-8">
                                <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Unités d'Apprentissage</h3>
                                <button @click.stop="openUaCreate({{ $seance->id }})"
                                        class="bg-white px-5 py-2 rounded-xl border border-slate-100 text-[10px] font-black text-primary-500 uppercase tracking-widest hover:bg-primary-500 hover:text-white transition-all shadow-sm">
                                    + Ajouter UA
                                </button>
                            </div>

                            @forelse($seance->unitesApprentissage as $ua)
                                <div x-data="{ expUa: false }" class="group/ua">
                                    <div class="bg-slate-50/70 p-8 rounded-[40px] border-2 border-transparent hover:border-primary-100 hover:bg-white transition-all duration-500 cursor-pointer shadow-sm hover:shadow-premium"
                                         @click.stop="expUa = !expUa">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-6">
                                                <div class="size-12 rounded-2xl bg-white border-2 border-slate-100 flex items-center justify-center text-slate-400 transition-all duration-500" :class="expUa ? 'bg-slate-900 border-slate-900 text-white scale-110' : ''">
                                                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
                                                </div>
                                                <div>
                                                    <span class="text-[10px] font-black text-primary-500 uppercase tracking-[0.3em] mb-1 block">{{ $ua->code }}</span>
                                                    <h4 class="text-xl font-heading font-black text-slate-900 group-hover/ua:text-primary-600 transition-colors">{{ $ua->nom }}</h4>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <div class="text-right hidden sm:block">
                                                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em] block">Contenu</span>
                                                    <span class="text-xs font-black text-slate-900">{{ $ua->competences->count() }} Compétences</span>
                                                </div>
                                                <button @click.stop="openUaEdit({{ $ua->id }}, '{{ addslashes($ua->nom) }}', '{{ addslashes($ua->code) }}')"
                                                        class="size-10 rounded-xl bg-primary-50 text-primary-500 hover:bg-primary-500 hover:text-white transition-all flex items-center justify-center">
                                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                </button>
                                                <form id="delete-ua-{{ $ua->id }}" action="{{ route('formateur.pedagogie.ua.destroy', $ua->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="button" 
                                                            @click.stop="$dispatch('confirm', { 
                                                                title: 'Supprimer l\'UA ?', 
                                                                onConfirm: 'delete-ua-{{ $ua->id }}' 
                                                            })"
                                                            class="size-10 rounded-xl bg-rose-50 text-rose-400 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center">
                                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12" /></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Competences -->
                                        <div x-show="expUa" x-collapse>
                                            <div class="mt-8 pt-8 border-t border-slate-100 flex justify-between items-center mb-6">
                                                <span class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em]">Maîtrise & Piliers</span>
                                                <button @click.stop="openCompCreate({{ $ua->id }})"
                                                        class="text-[9px] font-black text-emerald-500 hover:text-emerald-700 uppercase tracking-widest">
                                                    + Fixer Compétence
                                                </button>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                @forelse($ua->competences as $comp)
                                                    <div class="bg-white p-5 rounded-3xl border border-slate-100 flex justify-between shadow-sm group/comp relative overflow-hidden">
                                                        <div class="relative z-10">
                                                            <div class="flex items-center gap-3 mb-2">
                                                                <span class="size-2 rounded-full bg-emerald-400"></span>
                                                                <span class="text-[9px] font-black bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-lg border border-emerald-100">{{ $comp->code }}</span>
                                                            </div>
                                                            <p class="font-bold text-slate-900 text-sm leading-snug">{{ $comp->libelle }}</p>
                                                        </div>
                                                        <div class="flex items-center gap-1 relative z-10 self-start">
                                                            <button @click.stop="openCompEdit({{ $comp->id }}, '{{ addslashes($comp->libelle) }}', '{{ addslashes($comp->code) }}')"
                                                                    class="text-primary-400 hover:text-primary-600 p-1 transition-colors">
                                                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                            </button>
                                                            <form id="delete-comp-{{ $comp->id }}" action="{{ route('formateur.pedagogie.competence.destroy', $comp->id) }}" method="POST">
                                                                @csrf @method('DELETE')
                                                                <button type="button" 
                                                                        @click.stop="$dispatch('confirm', { 
                                                                            title: 'Retirer la compétence ?', 
                                                                            onConfirm: 'delete-comp-{{ $comp->id }}' 
                                                                        })"
                                                                        class="text-slate-300 hover:text-rose-500 p-1 transition-colors">
                                                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12" /></svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p class="col-span-full text-center text-[10px] font-bold text-slate-300 italic uppercase py-4">Aucune compétence</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10 bg-slate-50/50 rounded-[32px] border-2 border-dashed border-slate-100">
                                    <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">Aucune UA enregistrée</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="glass p-20 rounded-[50px] border-2 border-dashed border-slate-200 text-center">
                <div class="size-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8 border border-slate-100 shadow-inner">
                    <svg class="size-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-2">Ossature Vierge</h3>
                <p class="text-slate-500 font-medium italic">Commencez par créer votre première session pédagogique.</p>
            </div>
        @endforelse
    </div>

    <!-- Modals -->

    <!-- Unified Modal: Seance (Create + Edit) -->
    <x-ui.modal name="seance-modal" maxWidth="md">
        <template x-if="seanceMode === 'create'">
            <h3 class="text-2xl font-bold text-slate-800 tracking-tight">Nouvelle Session</h3>
        </template>
        <template x-if="seanceMode === 'edit'">
            <h3 class="text-2xl font-bold text-slate-800 tracking-tight">Modifier Session</h3>
        </template>
        <form x-bind:action="seanceMode === 'create' ? '{{ route('formateur.pedagogie.seance.store') }}' : `{{ url('/formateur/pedagogie/seance') }}/${seanceId}`" method="POST" class="space-y-8">
            @csrf
            <template x-if="seanceMode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
            <div class="space-y-3">
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Identification</label>
                <input type="text" name="nom" x-model="seanceNom" required
                       :placeholder="seanceMode === 'create' ? 'Ex: Masterclass Laravel Architecture' : ''"
                       class="w-full bg-slate-100/50 border-transparent rounded-3xl py-5 px-8 font-bold text-slate-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 transition-all text-sm">
            </div>
            <div class="space-y-3">
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Planification</label>
                <input type="date" name="date" x-model="seanceDate" required
                       class="w-full bg-slate-100/50 border-transparent rounded-3xl py-5 px-8 font-bold text-slate-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 transition-all text-sm">
            </div>
            <button type="submit" class="w-full py-6 px-8 text-white font-black rounded-[28px] active:scale-95 transition-all uppercase tracking-[0.3em] text-xs"
                    :class="seanceMode === 'create' ? 'bg-slate-900 hover:bg-primary-500 shadow-2xl shadow-slate-900/10' : 'bg-primary-500 hover:bg-primary-600 shadow-2xl shadow-primary-500/10'"
                    x-text="seanceMode === 'create' ? 'Consigner la Session' : 'Mettre à jour la Session'"></button>
        </form>
    </x-ui.modal>

    <!-- Unified Modal: UA (Create + Edit) -->
    <x-ui.modal name="ua-modal" maxWidth="md">
        <template x-if="uaMode === 'create'">
            <h3 class="text-2xl font-bold text-slate-800 tracking-tight">Ajouter UA</h3>
        </template>
        <template x-if="uaMode === 'edit'">
            <h3 class="text-2xl font-bold text-slate-800 tracking-tight">Modifier UA</h3>
        </template>
        <form x-bind:action="uaMode === 'create' ? '{{ route('formateur.pedagogie.ua.store') }}' : `{{ url('/formateur/pedagogie/ua') }}/${uaId}`" method="POST" class="space-y-8">
            @csrf
            <template x-if="uaMode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
            <template x-if="uaMode === 'create'"><input type="hidden" name="seance_id" :value="activeSeanceId"></template>
            <div class="grid grid-cols-1 gap-8">
                <div class="space-y-3">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Architecture</label>
                    <input type="text" name="code" x-model="uaCode" required
                           :placeholder="uaMode === 'create' ? 'UA-XXX' : ''"
                           class="w-full bg-slate-100/50 border-transparent rounded-3xl py-5 px-8 font-bold text-slate-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 transition-all text-sm uppercase font-mono tracking-widest">
                </div>
                <div class="space-y-3">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Libellé Technique</label>
                    <input type="text" name="nom" x-model="uaNom" required
                           :placeholder="uaMode === 'create' ? 'Ex: Middlewares & Auth' : ''"
                           class="w-full bg-slate-100/50 border-transparent rounded-3xl py-5 px-8 font-bold text-slate-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 transition-all text-sm">
                </div>
            </div>
            <button type="submit" class="w-full py-6 px-8 text-white font-black rounded-[28px] active:scale-95 transition-all uppercase tracking-[0.3em] text-xs"
                    :class="uaMode === 'create' ? 'bg-slate-900 hover:bg-primary-500 shadow-2xl shadow-slate-900/10' : 'bg-primary-500 hover:bg-primary-600'"
                    x-text="uaMode === 'create' ? 'Fixer l\'Unité' : 'Mettre à jour l\'Unité'"></button>
        </form>
    </x-ui.modal>

    <!-- Unified Modal: Competence (Create + Edit) -->
    <x-ui.modal name="comp-modal" maxWidth="md">
        <template x-if="compMode === 'create'">
            <h3 class="text-2xl font-bold text-slate-800 tracking-tight">Nouvelle Compétence</h3>
        </template>
        <template x-if="compMode === 'edit'">
            <h3 class="text-2xl font-bold text-slate-800 tracking-tight">Modifier Compétence</h3>
        </template>
        <form x-bind:action="compMode === 'create' ? '{{ route('formateur.pedagogie.competence.store') }}' : `{{ url('/formateur/pedagogie/competence') }}/${compId}`" method="POST" class="space-y-8">
            @csrf
            <template x-if="compMode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
            <template x-if="compMode === 'create'"><input type="hidden" name="unite_apprentissage_id" :value="activeUaId"></template>
            <div class="grid grid-cols-1 gap-8">
                <div class="space-y-3">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Identification</label>
                    <input type="text" name="code" x-model="compCode" required
                           :placeholder="compMode === 'create' ? 'C-XXX' : ''"
                           class="w-full bg-slate-100/50 border-transparent rounded-3xl py-5 px-8 font-bold text-slate-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 transition-all text-sm uppercase font-mono tracking-widest">
                </div>
                <div class="space-y-3">
                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] ml-4">Désignation du Pilier</label>
                    <input type="text" name="nom" x-model="compNom" required
                           :placeholder="compMode === 'create' ? 'Ex: Implémenter un JWT' : ''"
                           class="w-full bg-slate-100/50 border-transparent rounded-3xl py-5 px-8 font-bold text-slate-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 transition-all text-sm">
                </div>
            </div>
            <button type="submit" class="w-full py-6 px-8 bg-emerald-500 text-white font-black rounded-[28px] hover:bg-emerald-600 shadow-2xl shadow-emerald-500/10 active:scale-95 transition-all uppercase tracking-[0.3em] text-xs"
                    x-text="compMode === 'create' ? 'Enregistrer le Pilier' : 'Mettre à jour la Compétence'"></button>
        </form>
    </x-ui.modal>
</div>
@endsection
