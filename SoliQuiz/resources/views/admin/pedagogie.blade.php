@extends('layouts.app')

@section('title', 'Ingénierie Pédagogique - SoliQuiz')

@section('page-title', 'Ingénierie Pédagogique')

@section('content')
<div class="fade-in" x-data="{
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
    <!-- Header Control Section -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-10 border-b border-slate-100 mb-10">
        <div>
            <p class="text-label mb-1">Architecture</p>
            <h3 class="text-xl font-bold text-slate-900 tracking-tight italic uppercase">Structure <span class="text-transparent bg-clip-text bg-linear-to-r from-primary-600 to-primary-400">Pédagogique</span></h3>
            <p class="mt-2 text-xs text-slate-500 max-w-2xl">
                Centralisez les séances, les UA et les compétences. Vous pilotez ici l'ossature pédagogique globale.
            </p>
        </div>
        
        <button @click="openSeanceCreate()" type="button"
            class="btn-premium px-8 py-4 bg-slate-900 text-white text-[11px] uppercase tracking-widest">
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
            Nouvelle Séance
        </button>
    </div>



    <!-- Hierarchy Tree -->
    <div class="space-y-6">
        @forelse($seances as $seance)
            <div x-data="{ expanded: false }" class="group">
                <!-- Seance Card -->
                <div class="bg-white/90 backdrop-blur-md p-6 md:p-8 rounded-[40px] border border-slate-200/70 shadow-sm group-hover:shadow-premium transition-all relative overflow-hidden"
                     @click="expanded = !expanded" :class="expanded ? 'border-primary-200 shadow-premium' : ''">
                    
                    <div class="flex items-center justify-between relative z-10 cursor-pointer">
                        <div class="flex items-center gap-8">
                            <div class="size-16 rounded-3xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-500 transition-all duration-500"
                                 :class="expanded ? 'bg-primary-500 text-white rotate-90 scale-110 shadow-lg shadow-primary-500/20' : ''">
                                <svg class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7" /></svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-black text-primary-500 uppercase tracking-[0.3em] block mb-2">Entité Majeure</span>
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
                                        type="button"
                                        class="size-12 rounded-2xl bg-primary-50 text-primary-500 flex items-center justify-center hover:bg-primary-500 hover:text-white transition-all hover:rotate-12">
                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                                <form id="delete-seance-{{ $seance->id }}" action="{{ route('admin.pedagogie.seance.destroy', $seance->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button" 
                                            @click.stop="$dispatch('confirm', { 
                                                title: 'Archiver la séance ?', 
                                                message: 'Cette séance et tout son contenu ne seront plus accessibles.', 
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
                                        type="button"
                                        class="bg-white px-5 py-2 rounded-xl border border-slate-100 text-[10px] font-black text-primary-500 uppercase tracking-widest hover:bg-primary-500 hover:text-white transition-all shadow-sm">
                                    + Injecter UA
                                </button>
                            </div>

                            @forelse($seance->unitesApprentissage as $ua)
                                <div x-data="{ expUa: false }" class="group/ua">
                                    <div class="bg-slate-50/50 p-6 rounded-4xl border border-transparent hover:border-primary-100 hover:bg-white transition-all cursor-pointer"
                                         @click.stop="expUa = !expUa">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-5">
                                                <div class="size-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-600 transition-transform" :class="expUa ? 'rotate-90 bg-slate-900 text-white' : ''">
                                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7" /></svg>
                                                </div>
                                                <div>
                                                    <span class="text-[9px] font-black text-primary-500/60 uppercase tracking-widest">{{ $ua->code }}</span>
                                                    <h4 class="font-black text-slate-900 group-hover/ua:text-primary-600 transition-colors">{{ $ua->nom }}</h4>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-4">
                                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $ua->competences->count() }} Compétences</span>
                                                <button @click.stop="openUaEdit({{ $ua->id }}, '{{ addslashes($ua->nom) }}', '{{ addslashes($ua->code) }}')"
                                                        type="button"
                                                        class="text-primary-400 hover:text-primary-600 p-2 transition-colors">
                                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                </button>
                                                <form id="delete-ua-{{ $ua->id }}" action="{{ route('admin.pedagogie.ua.destroy', $ua->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="button" 
                                                            @click.stop="$dispatch('confirm', { 
                                                                title: 'Supprimer l\'UA ?', 
                                                                message: 'Toutes les compétences liées seront également retirées.', 
                                                                onConfirm: 'delete-ua-{{ $ua->id }}' 
                                                            })"
                                                            class="text-rose-300 hover:text-rose-500 p-2 transition-colors">
                                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12" /></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Competences -->
                                        <div x-show="expUa" x-collapse>
                                            <div class="mt-8 pt-8 border-t border-slate-100 flex justify-between items-center mb-6">
                                                <span class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em]">Piliers de Compétence</span>
                                                <button @click.stop="openCompCreate({{ $ua->id }})"
                                                        type="button"
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
                                                                    type="button"
                                                                    class="text-primary-400 hover:text-primary-600 p-1 transition-colors">
                                                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                            </button>
                                                            <form id="delete-comp-{{ $comp->id }}" action="{{ route('admin.pedagogie.competence.destroy', $comp->id) }}" method="POST">
                                                                @csrf @method('DELETE')
                                                                <button type="button" 
                                                                        @click.stop="$dispatch('confirm', { 
                                                                            title: 'Retirer le pilier ?', 
                                                                            onConfirm: 'delete-comp-{{ $comp->id }}' 
                                                                        })"
                                                                        class="text-slate-300 hover:text-rose-500 p-1 transition-colors">
                                                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12" /></svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p class="col-span-full text-center text-[10px] font-bold text-slate-300 italic uppercase py-4">Structure vide</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10 bg-slate-50/50 rounded-4xl border-2 border-dashed border-slate-100">
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
                <p class="text-slate-500 font-medium italic">Commencez par créer votre première séance pédagogique.</p>
            </div>
        @endforelse
    </div>

    <!-- Modals Layer -->

    <!-- Unified Modal: Seance (Create + Edit) -->
    <x-ui.modal name="seance-modal" maxWidth="md">
        <template x-if="seanceMode === 'create'">
            <h3 class="text-2xl font-bold text-slate-800 tracking-tight">Créer Séance</h3>
        </template>
        <template x-if="seanceMode === 'edit'">
            <h3 class="text-2xl font-bold text-slate-800 tracking-tight">Modifier Séance</h3>
        </template>
        <form x-bind:action="seanceMode === 'create' ? '{{ route('admin.pedagogie.seance.store') }}' : `{{ url('/admin/pedagogie/seance') }}/${seanceId}`" method="POST" class="space-y-6">
            @csrf
            <template x-if="seanceMode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Identifiant de Séance</label>
                <input type="text" name="nom" x-model="seanceNom" required
                       :placeholder="seanceMode === 'create' ? 'Ex: Fondamentaux du Cloud' : ''"
                       class="w-full bg-slate-50 border-transparent rounded-3xl py-4 px-6 font-bold text-slate-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 outline-none transition-all">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Date Programmée</label>
                <input type="date" name="date" x-model="seanceDate" required
                       class="w-full bg-slate-50 border-transparent rounded-3xl py-4 px-6 font-bold text-slate-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 outline-none transition-all">
            </div>
            <button type="submit" class="w-full btn-premium py-5 px-8 bg-slate-900 text-white font-black rounded-3xl hover:bg-primary-500 active:scale-95 transition-all uppercase tracking-widest text-sm mt-4"
                    x-text="seanceMode === 'create' ? 'Consigner l\'Entité' : 'Conserver Séquence'"></button>
        </form>
    </x-ui.modal>

    <!-- Unified Modal: UA (Create + Edit) -->
    <x-ui.modal name="ua-modal" maxWidth="md">
        <template x-if="uaMode === 'create'">
            <h3 class="text-2xl font-bold text-slate-800 tracking-tight">Injecter UA</h3>
        </template>
        <template x-if="uaMode === 'edit'">
            <h3 class="text-2xl font-bold text-slate-800 tracking-tight">Modifier UA</h3>
        </template>
        <form x-bind:action="uaMode === 'create' ? `{{ url('/admin/pedagogie/seance') }}/${activeSeanceId}/ua` : `{{ url('/admin/pedagogie/ua') }}/${uaId}`" method="POST" class="space-y-6">
            @csrf
            <template x-if="uaMode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
            <div class="grid grid-cols-1 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Code Structurel</label>
                    <input type="text" name="code" x-model="uaCode" required
                           :placeholder="uaMode === 'create' ? 'UA-XX' : ''"
                           class="w-full bg-slate-50 border-transparent rounded-3xl py-4 px-6 font-bold text-slate-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 outline-none transition-all uppercase font-mono">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Libellé Technique</label>
                    <input type="text" name="nom" x-model="uaNom" required
                           :placeholder="uaMode === 'create' ? 'Ex: Virtualisation avancée' : ''"
                           class="w-full bg-slate-50 border-transparent rounded-3xl py-4 px-6 font-bold text-slate-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 outline-none transition-all">
                </div>
            </div>
            <button type="submit" class="w-full btn-premium py-5 px-8 bg-slate-900 text-white font-black rounded-3xl hover:bg-primary-500 active:scale-95 transition-all uppercase tracking-widest text-sm mt-4"
                    x-text="uaMode === 'create' ? 'Fixer l\'Unité' : 'Mettre à jour l\'Unité'"></button>
        </form>
    </x-ui.modal>

    <!-- Unified Modal: Competence (Create + Edit) -->
    <x-ui.modal name="comp-modal" maxWidth="md">
        <template x-if="compMode === 'create'">
            <h3 class="text-2xl font-bold text-slate-800 tracking-tight">Fixer Compétence</h3>
        </template>
        <template x-if="compMode === 'edit'">
            <h3 class="text-2xl font-bold text-slate-800 tracking-tight">Modifier Pilier</h3>
        </template>
        <form x-bind:action="compMode === 'create' ? `{{ url('/admin/pedagogie/ua') }}/${activeUaId}/competence` : `{{ url('/admin/pedagogie/competence') }}/${compId}`" method="POST" class="space-y-6">
            @csrf
            <template x-if="compMode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
            <div class="grid grid-cols-1 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Identifiant</label>
                    <input type="text" name="code" x-model="compCode" required
                           :placeholder="compMode === 'create' ? 'C-XXXX' : ''"
                           class="w-full bg-slate-50 border-transparent rounded-3xl py-4 px-6 font-bold text-slate-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 outline-none transition-all uppercase font-mono">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Libellé de la Maîtrise</label>
                    <input type="text" name="libelle" x-model="compNom" required
                           class="w-full bg-slate-50 border-transparent rounded-3xl py-4 px-6 font-bold text-slate-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 outline-none transition-all">
                </div>
                <template x-if="compMode === 'create'">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Spécifications</label>
                        <textarea name="description" rows="3"
                                  class="w-full bg-slate-50 border-transparent rounded-3xl py-4 px-6 font-bold text-slate-900 focus:bg-white focus:ring-4 focus:ring-primary-500/10 outline-none transition-all resize-none"></textarea>
                    </div>
                </template>
            </div>
            <button type="submit" class="w-full btn-premium py-5 px-8 text-white font-black rounded-3xl active:scale-95 transition-all uppercase tracking-widest text-sm mt-4 shadow-xl"
                    :class="compMode === 'create' ? 'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/20' : 'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/20'"
                    x-text="compMode === 'create' ? 'Fixer le Pilier' : 'Fixation Structurelle'"></button>
        </form>
    </x-ui.modal>
</div>
@endsection