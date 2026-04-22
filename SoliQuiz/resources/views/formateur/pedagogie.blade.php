@extends('layouts.app')

@section('title', 'Ingénierie Pédagogique - Formateur')

@section('content')
<div class="relative" x-data="{
    activeSeanceId: null,
    activeUaId: null,
    seanceMode: 'create',
    seanceId: null,
    seanceNom: '',
    seanceDate: '',
    uaMode: 'create',
    uaId: null,
    uaNom: '',
    uaCode: '',
    compMode: 'create',
    compId: null,
    compNom: '',
    compCode: '',
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
    <!-- Animated Background -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none" x-data="{ animate: false }" x-init="setInterval(() => animate = !animate, 5000)">
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-gradient-to-br from-primary-100/30 to-emerald-100/30 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2 transition-transform duration-[5000ms]" :class="animate ? 'scale-110' : 'scale-100'"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-gradient-to-tr from-amber-100/20 to-primary-100/20 rounded-full blur-3xl transform -translate-x-1/2 translate-y-1/2 transition-transform duration-[7000ms]' :class="animate ? 'scale-125' : 'scale-100'"></div>
    </div>

    <!-- Header Section -->
    <div class="relative z-10 mb-12">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 pb-10 border-b border-slate-200/50">
            <div>
                <div class="flex items-center gap-4 mb-4">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Espace Formateur</span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-900">Architecture</span>
                </div>
                <h1 class="text-4xl lg:text-5xl font-black text-slate-900 tracking-tight leading-none mb-4">
                    Ingénierie Pédagogique
                </h1>
                <p class="text-slate-500 font-medium max-w-xl leading-relaxed">
                    Construisez votre framework pédagogique. Définissez les sessions, unités d'apprentissage et compétences.
                </p>
            </div>
            
            <button @click="openSeanceCreate()"
                class="group relative overflow-hidden px-8 py-5 bg-slate-900 text-white rounded-2xl font-bold text-xs uppercase tracking-[0.15em] hover:bg-primary-600 transition-all duration-500 shadow-2xl shadow-slate-900/20 hover:shadow-primary-500/30 flex items-center gap-3">
                <span class="relative z-10 flex items-center gap-3">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4" /></svg>
                    Nouvelle Session
                </span>
                <div class="absolute inset-0 bg-gradient-to-r from-primary-500 to-emerald-500 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            </button>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="relative z-10 flex flex-wrap gap-4 mb-10">
        <div class="flex items-center gap-3 px-6 py-3 bg-white/80 backdrop-blur-sm rounded-2xl border border-slate-100 shadow-sm">
            <div class="size-10 bg-primary-50 rounded-xl flex items-center justify-center">
                <svg class="size-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M8 7V3m8 4V3m-9 4h10a2 2 0 012 2v10a2 2 0 01-2 2H9a2 2 0 01-2-2V9a2 2 0 012-2z" /></svg>
            </div>
            <div>
                <span class="text-2xl font-black text-slate-900">{{ $seances->count() }}</span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-1">Sessions</span>
            </div>
        </div>
        <div class="flex items-center gap-3 px-6 py-3 bg-white/80 backdrop-blur-sm rounded-2xl border border-slate-100 shadow-sm">
            <div class="size-10 bg-emerald-50 rounded-xl flex items-center justify-center">
                <svg class="size-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            </div>
            <div>
                <span class="text-2xl font-black text-slate-900">{{ $seances->sum(fn($s) => $s->unitesApprentissage->count()) }}</span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-1">Unités</span>
            </div>
        </div>
        <div class="flex items-center gap-3 px-6 py-3 bg-white/80 backdrop-blur-sm rounded-2xl border border-slate-100 shadow-sm">
            <div class="size-10 bg-amber-50 rounded-xl flex items-center justify-center">
                <svg class="size-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
            </div>
            <div>
                <span class="text-2xl font-black text-slate-900">{{ $seances->sum(fn($s) => $s->unitesApprentissage->sum(fn($ua) => $ua->competences->count())) }}</span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider ml-1">Compétences</span>
            </div>
        </div>
    </div>

    <!-- Hierarchy Tree -->
    <div class="relative z-10 space-y-4">
        @forelse($seances as $index => $seance)
            <div x-data="{ expanded: false }" class="group" :key="{{ $seance->id }}">
                <div class="relative bg-white/90 backdrop-blur-md rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-primary-500/5 transition-all duration-500 overflow-hidden"
                     :class="expanded ? 'ring-2 ring-primary-200/50 border-primary-200' : ''">
                    
                    <!-- Progress indicator -->
                    <div class="absolute top-0 left-0 h-1 bg-gradient-to-r from-primary-500 to-emerald-500 transition-all duration-700" 
                         :style="'width: ' + (expanded ? '100%' : '0%') + ';'"></div>
                    
                    <!-- Card Content -->
                    <div class="p-6 md:p-8 cursor-pointer" @click="expanded = !expanded">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-6">
                                <div class="relative">
                                    <div class="size-16 rounded-2xl flex items-center justify-center transition-all duration-500"
                                         :class="expanded ? 'bg-primary-500 text-white shadow-lg shadow-primary-500/30' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-500'">
                                        <svg class="size-7 transition-transform duration-500" :class="expanded ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 9l-7 7-7-7" /></svg>
                                    </div>
                                    @if(!$seance->unitesApprentissage->isEmpty())
                                    <div class="absolute -top-1 -right-1 size-5 bg-emerald-500 text-white text-[8px] font-black rounded-full flex items-center justify-center shadow">{{ $index + 1 }}</div>
                                    @endif
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-[9px] font-black text-primary-600 uppercase tracking-[0.3em]">Session {{ $seance->date->format('Y') }}</span>
                                        <span class="w-1 h-1 bg-primary-300 rounded-full"></span>
                                        <span class="text-[9px] font-medium text-slate-400 uppercase tracking-wider">{{ $seance->date->format('d M') }}</span>
                                    </div>
                                    <h2 class="text-2xl font-black text-slate-900 tracking-tight group-hover:text-primary-600 transition-colors">{{ $seance->nom }}</h2>
                                </div>
                            </div>

                            <div class="flex items-center gap-8">
                                <div class="hidden md:flex items-center gap-6 text-right">
                                    <div class="bg-slate-50 px-4 py-2 rounded-xl">
                                        <span class="block text-xl font-black text-slate-900">{{ $seance->unitesApprentissage->count() }}</span>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">UA</span>
                                    </div>
                                    <div class="bg-emerald-50 px-4 py-2 rounded-xl">
                                        <span class="block text-xl font-black text-emerald-600">{{ $seance->unitesApprentissage->sum(fn($ua) => $ua->competences->count()) }}</span>
                                        <span class="text-[9px] font-bold text-emerald-400 uppercase tracking-wider">Skills</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <button @click.stop="openSeanceEdit({{ $seance->id }}, '{{ addslashes($seance->nom) }}', '{{ $seance->date->format('Y-m-d') }}')"
                                            class="size-11 rounded-xl bg-primary-50 text-primary-500 flex items-center justify-center hover:bg-primary-500 hover:text-white hover:scale-110 transition-all duration-300">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    <form id="delete-seance-{{ $seance->id }}" action="{{ route('formateur.pedagogie.seance.destroy', $seance->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button" 
                                                @click.stop="$dispatch('confirm', { 
                                                    title: 'Supprimer la session ?', 
                                                    message: 'Toutes les UA et compétences liées seront supprimées.', 
                                                    onConfirm: 'delete-seance-{{ $seance->id }}' 
                                                })"
                                                class="size-11 rounded-xl bg-rose-50 text-rose-400 flex items-center justify-center hover:bg-rose-500 hover:text-white hover:scale-110 transition-all duration-300">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Expanded Content -->
                    <div x-show="expanded" x-collapse class="border-t border-slate-50">
                        <div class="p-6 md:p-8 bg-slate-50/30">
                            <div class="flex justify-between items-center mb-6">
                                <div class="flex items-center gap-3">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Unités d'Apprentissage</span>
                                    <span class="px-2 py-0.5 bg-primary-100 text-primary-600 text-[9px] font-bold rounded-full">{{ $seance->unitesApprentissage->count() }}</span>
                                </div>
                                <button @click.stop="openUaCreate({{ $seance->id }})"
                                        class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-[10px] font-bold text-slate-600 uppercase tracking-wider hover:bg-primary-500 hover:text-white hover:border-primary-500 transition-all shadow-sm">
                                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
                                    Ajouter
                                </button>
                            </div>

                            <div class="space-y-3 pl-4 border-l-2 border-primary-100">
                                @forelse($seance->unitesApprentissage as $ua)
                                    <div x-data="{ expandedUa: false }" class="group/ua">
                                        <div class="bg-white p-5 rounded-2xl border border-slate-100 hover:border-primary-200 hover:shadow-lg hover:shadow-primary-500/10 transition-all cursor-pointer"
                                             @click.stop="expandedUa = !expandedUa">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-4">
                                                    <div class="size-9 rounded-lg flex items-center justify-center transition-all duration-300"
                                                         :class="expandedUa ? 'bg-primary-500 text-white rotate-90' : 'bg-slate-100 text-slate-500'">
                                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7" /></svg>
                                                    </div>
                                                    <div>
                                                        <span class="text-[9px] font-black text-primary-500 uppercase tracking-widest">{{ $ua->code }}</span>
                                                        <h4 class="text-sm font-bold text-slate-900 group-hover/ua:text-primary-600 transition-colors">{{ $ua->nom }}</h4>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-4">
                                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $ua->competences->count() }} skills</span>
                                                    <button @click.stop="openUaEdit({{ $ua->id }}, '{{ addslashes($ua->nom) }}', '{{ addslashes($ua->code) }}')"
                                                            class="size-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-primary-50 hover:text-primary-500 transition-all">
                                                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                    </button>
                                                    <form id="delete-ua-{{ $ua->id }}" action="{{ route('formateur.pedagogie.ua.destroy', $ua->id) }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        <button type="button" 
                                                                @click.stop="$dispatch('confirm', { 
                                                                    title: 'Supprimer l\'UA ?', 
                                                                    onConfirm: 'delete-ua-{{ $ua->id }}' 
                                                                })"
                                                                class="size-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-rose-50 hover:text-rose-500 transition-all">
                                                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12" /></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>

                                            <!-- Competences -->
                                            <div x-show="expandedUa" x-collapse class="mt-4 pt-4 border-t border-slate-50">
                                                <div class="flex justify-between items-center mb-3">
                                                    <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">Compétences</span>
                                                    <button @click.stop="openCompCreate({{ $ua->id }})"
                                                            class="text-[9px] font-bold text-emerald-500 hover:text-emerald-700 uppercase tracking-wider">
                                                        + Ajouter
                                                    </button>
                                                </div>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                    @forelse($ua->competences as $comp)
                                                        <div class="bg-emerald-50/50 p-3 rounded-xl border border-emerald-100 flex justify-between items-center group/comp">
                                                            <div class="flex items-center gap-2">
                                                                <span class="size-1.5 bg-emerald-400 rounded-full"></span>
                                                                <span class="text-[9px] font-bold text-emerald-600">{{ $comp->code }}</span>
                                                                <span class="text-xs font-medium text-slate-700">{{ $comp->libelle }}</span>
                                                            </div>
                                                            <div class="flex items-center gap-1 opacity-0 group-hover/comp:opacity-100 transition-opacity">
                                                                <button @click.stop="openCompEdit({{ $comp->id }}, '{{ addslashes($comp->libelle) }}', '{{ addslashes($comp->code) }}')"
                                                                        class="p-1 text-emerald-400 hover:text-emerald-600">
                                                                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                                </button>
                                                                <form id="delete-comp-{{ $comp->id }}" action="{{ route('formateur.pedagogie.competence.destroy', $comp->id) }}" method="POST">
                                                                    @csrf @method('DELETE')
                                                                    <button type="button" 
                                                                            @click.stop="$dispatch('confirm', { 
                                                                                title: 'Retirer la compétence ?', 
                                                                                onConfirm: 'delete-comp-{{ $comp->id }}' 
                                                                            })"
                                                                            class="p-1 text-slate-300 hover:text-rose-500">
                                                                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12" /></svg>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <p class="col-span-full text-center text-[9px] font-medium text-slate-400 italic py-2">Aucune compétence définie</p>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8 bg-white/50 rounded-2xl border-2 border-dashed border-slate-100">
                                        <p class="text-slate-400 text-xs font-medium">Aucune UA enregistrée</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="relative bg-gradient-to-br from-slate-50 to-white rounded-3xl border-2 border-dashed border-slate-200 p-16 text-center">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-32 h-32 bg-gradient-to-br from-primary-50 to-emerald-50 rounded-full opacity-50 blur-2xl"></div>
                <div class="relative">
                    <div class="size-20 bg-white rounded-3xl flex items-center justify-center mx-auto mb-6 border border-slate-100 shadow-lg">
                        <svg class="size-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-2">Architecture Vide</h3>
                    <p class="text-slate-500 font-medium mb-6">Commencez par créer votre première session pédagogique</p>
                    <button @click="openSeanceCreate()"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-primary-500 text-white rounded-xl font-bold text-sm hover:bg-primary-600 transition-all">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4" /></svg>
                        Créer une Session
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Modals -->
    <x-ui.modal name="seance-modal" maxWidth="md">
        <template x-if="seanceMode === 'create'">
            <div class="flex items-center gap-3 mb-6">
                <div class="size-10 bg-primary-100 rounded-xl flex items-center justify-center">
                    <svg class="size-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M8 7V3m8 4V3m-9 4h10a2 2 0 012 2v10a2 2 0 01-2 2H9a2 2 0 01-2-2V9a2 2 0 012-2z" /></svg>
                </div>
                <h3 class="text-xl font-black text-slate-800">Nouvelle Session</h3>
            </div>
        </template>
        <template x-if="seanceMode === 'edit'">
            <div class="flex items-center gap-3 mb-6">
                <div class="size-10 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="size-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                </div>
                <h3 class="text-xl font-black text-slate-800">Modifier Session</h3>
            </div>
        </template>
        <form x-bind:action="seanceMode === 'create' ? '{{ route('formateur.pedagogie.seance.store') }}' : `{{ url('/formateur/pedagogie/seance') }}/${seanceId}`" method="POST" class="space-y-5">
            @csrf
            <template x-if="seanceMode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 block">Nom de la Session</label>
                <input type="text" name="nom" x-model="seanceNom" required
                       :placeholder="seanceMode === 'create' ? 'Ex: Masterclass Laravel' : ''"
                       class="w-full bg-slate-50 border-2 border-transparent rounded-xl py-4 px-5 font-bold text-slate-800 focus:bg-white focus:border-primary-400 focus:ring-0 transition-all">
            </div>
            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 block">Date</label>
                <input type="date" name="date" x-model="seanceDate" required
                       class="w-full bg-slate-50 border-2 border-transparent rounded-xl py-4 px-5 font-bold text-slate-800 focus:bg-white focus:border-primary-400 focus:ring-0 transition-all">
            </div>
            <button type="submit" 
                    class="w-full py-4 rounded-xl font-bold text-sm uppercase tracking-wider transition-all hover:scale-[1.02] active:scale-[0.98]"
                    :class="seanceMode === 'create' ? 'bg-slate-900 text-white hover:bg-primary-500' : 'bg-primary-500 text-white'"
                    x-text="seanceMode === 'create' ? 'Créer la Session' : 'Mettre à jour'"></button>
        </form>
    </x-ui.modal>

    <x-ui.modal name="ua-modal" maxWidth="md">
        <template x-if="uaMode === 'create'">
            <div class="flex items-center gap-3 mb-6">
                <div class="size-10 bg-primary-100 rounded-xl flex items-center justify-center">
                    <svg class="size-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                </div>
                <h3 class="text-xl font-black text-slate-800">Nouvelle Unité</h3>
            </div>
        </template>
        <template x-if="uaMode === 'edit'">
            <div class="flex items-center gap-3 mb-6">
                <div class="size-10 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="size-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                </div>
                <h3 class="text-xl font-black text-slate-800">Modifier Unité</h3>
            </div>
        </template>
        <form x-bind:action="uaMode === 'create' ? '{{ route('formateur.pedagogie.ua.store') }}' : `{{ url('/formateur/pedagogie/ua') }}/${uaId}`" method="POST" class="space-y-5">
            @csrf
            <template x-if="uaMode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
            <template x-if="uaMode === 'create'"><input type="hidden" name="seance_id" :value="activeSeanceId"></template>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 block">Code</label>
                    <input type="text" name="code" x-model="uaCode" required
                           placeholder="UA-001"
                           class="w-full bg-slate-50 border-2 border-transparent rounded-xl py-4 px-5 font-bold text-slate-800 uppercase focus:bg-white focus:border-primary-400 focus:ring-0 transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 block">Nom</label>
                    <input type="text" name="nom" x-model="uaNom" required
                           placeholder="Nom de l'unité"
                           class="w-full bg-slate-50 border-2 border-transparent rounded-xl py-4 px-5 font-bold text-slate-800 focus:bg-white focus:border-primary-400 focus:ring-0 transition-all">
                </div>
            </div>
            <button type="submit" 
                    class="w-full py-4 rounded-xl font-bold text-sm uppercase tracking-wider transition-all hover:scale-[1.02] active:scale-[0.98]"
                    :class="uaMode === 'create' ? 'bg-slate-900 text-white hover:bg-primary-500' : 'bg-primary-500 text-white'"
                    x-text="uaMode === 'create' ? 'Créer l\'Unité' : 'Mettre à jour'"></button>
        </form>
    </x-ui.modal>

    <x-ui.modal name="comp-modal" maxWidth="md">
        <template x-if="compMode === 'create'">
            <div class="flex items-center gap-3 mb-6">
                <div class="size-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <svg class="size-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                </div>
                <h3 class="text-xl font-black text-slate-800">Nouvelle Compétence</h3>
            </div>
        </template>
        <template x-if="compMode === 'edit'">
            <div class="flex items-center gap-3 mb-6">
                <div class="size-10 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="size-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                </div>
                <h3 class="text-xl font-black text-slate-800">Modifier Compétence</h3>
            </div>
        </template>
        <form x-bind:action="compMode === 'create' ? '{{ route('formateur.pedagogie.competence.store') }}' : `{{ url('/formateur/pedagogie/competence') }}/${compId}`" method="POST" class="space-y-5">
            @csrf
            <template x-if="compMode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
            <template x-if="compMode === 'create'"><input type="hidden" name="unite_apprentissage_id" :value="activeUaId"></template>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 block">Code</label>
                    <input type="text" name="code" x-model="compCode" required
                           placeholder="C001"
                           class="w-full bg-slate-50 border-2 border-transparent rounded-xl py-4 px-5 font-bold text-slate-800 uppercase focus:bg-white focus:border-emerald-400 focus:ring-0 transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 block">Libellé</label>
                    <input type="text" name="nom" x-model="compNom" required
                           placeholder="Compétence à maîtriser"
                           class="w-full bg-slate-50 border-2 border-transparent rounded-xl py-4 px-5 font-bold text-slate-800 focus:bg-white focus:border-emerald-400 focus:ring-0 transition-all">
                </div>
            </div>
            <button type="submit" 
                    class="w-full py-4 bg-emerald-500 text-white rounded-xl font-bold text-sm uppercase tracking-wider hover:bg-emerald-600 transition-all hover:scale-[1.02] active:scale-[0.98]"
                    x-text="compMode === 'create' ? 'Créer la Compétence' : 'Mettre à jour'"></button>
        </form>
    </x-ui.modal>
</div>
@endsection