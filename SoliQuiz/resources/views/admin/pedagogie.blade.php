@extends('layouts.app')

@section('title', 'Ingénierie Pédagogique - Admin')

@section('page-title', 'Structure Pédagogique')

@section('content')
<div class="relative" x-data="{
    activeSeanceId: null,
    activeUaId: null,
    seanceMode: 'create',
    seanceId: null,
    seanceNom: '',
    seanceDateDebut: '',
    seanceDateFin: '',
    seanceFormateurId: '',
    uaMode: 'create',
    uaId: null,
    uaNom: '',
    uaCode: '',
    uaDateDebut: '',
    uaDateFin: '',
    uaFormateurId: '',
    compMode: 'create',
    compId: null,
    compNom: '',
    compCode: '',
    compDesc: '',
    openSeanceCreate() {
        this.seanceMode = 'create'; this.seanceId = null; this.seanceNom = '';
        this.seanceDateDebut = ''; this.seanceDateFin = ''; this.seanceFormateurId = '';
        $dispatch('open-modal', 'seance-modal');
    },
    openSeanceEdit(id, nom, debut, fin, formateurId) {
        this.seanceMode = 'edit'; this.seanceId = id; this.seanceNom = nom;
        this.seanceDateDebut = debut; this.seanceDateFin = fin; this.seanceFormateurId = formateurId;
        $dispatch('open-modal', 'seance-modal');
    },
    openUaCreate(seanceId) {
        this.uaMode = 'create'; this.uaId = null; this.uaNom = ''; this.uaCode = ''; 
        this.uaDateDebut = ''; this.uaDateFin = ''; this.uaFormateurId = '';
        this.activeSeanceId = seanceId;
        $dispatch('open-modal', 'ua-modal');
    },
    openUaEdit(id, nom, code, debut, fin, formateurId) {
        this.uaMode = 'edit'; this.uaId = id; this.uaNom = nom; this.uaCode = code;
        this.uaDateDebut = debut; this.uaDateFin = fin; this.uaFormateurId = formateurId;
        $dispatch('open-modal', 'ua-modal');
    },
    openCompCreate(uaId) {
        this.compMode = 'create'; this.compId = null; this.compNom = ''; this.compCode = ''; this.compDesc = ''; this.activeUaId = uaId;
        $dispatch('open-modal', 'comp-modal');
    },
    openCompEdit(id, nom, code, desc) {
        this.compMode = 'edit'; this.compId = id; this.compNom = nom; this.compCode = code; this.compDesc = desc || '';
        $dispatch('open-modal', 'comp-modal');
    }
}">
    <!-- Ambient Background -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 right-1/4 w-125 h-125 bg-linear-to-bl from-slate-100 to-transparent rounded-full blur-3xl opacity-50"></div>
        <div class="absolute bottom-0 left-1/4 w-75 h-75 bg-linear-to-tr from-primary-50 to-transparent rounded-full blur-3xl opacity-40"></div>
    </div>

    @if(session('code_warning'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bottom-6 right-6 z-[100] bg-white border border-amber-200 rounded-2xl p-4 shadow-xl shadow-amber-500/10 flex items-start gap-4 max-w-sm">
        <div class="size-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
            <svg class="size-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        </div>
        <div>
            <h4 class="text-sm font-black text-slate-900 mb-1">Avertissement</h4>
            <p class="text-xs font-bold text-slate-500">{{ session('code_warning') }}</p>
        </div>
        <button @click="show = false" class="text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>
    @endif


    <!-- Header Control Section -->
    <div class="relative z-30 mb-10">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-8 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-4 mb-3">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Espace Admin</span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-900">Structure</span>
                </div>
                <h3 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                    Ingénierie Pédagogique
                </h3>
                <p class="mt-2 text-sm text-slate-500 max-w-xl">
                    Pilotez l'ossature pédagogique globale. Gérez les sessions, UA et compétences.
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                {{-- Creator Filter Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" type="button"
                            class="flex items-center gap-2.5 px-4 py-3.5 bg-white border border-slate-200 rounded-xl text-xs font-black text-slate-600 uppercase tracking-wider hover:border-slate-400 hover:text-slate-900 transition-all shadow-sm"
                            :class="open ? 'border-primary-400 text-primary-600 ring-2 ring-primary-500/10' : ''">
                        <svg class="size-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                        </svg>
                        @if($creatorFilter && ($selectedCreator = $creators->find($creatorFilter)))
                            {{ $selectedCreator->prenom }} {{ $selectedCreator->nom }}
                        @else
                            Tous les créateurs
                        @endif
                        <svg class="size-3 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open"
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 top-full mt-2 w-56 bg-white border border-slate-100 rounded-2xl shadow-2xl shadow-slate-200/60 z-50 overflow-hidden"
                         x-cloak>
                        <div class="p-2">
                            <a href="{{ route('admin.pedagogie') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ !$creatorFilter ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                                <div class="size-7 rounded-lg {{ !$creatorFilter ? 'bg-white/20' : 'bg-slate-100' }} flex items-center justify-center text-[9px] font-black">ALL</div>
                                Tous les créateurs
                            </a>
                            @foreach($creators as $creator)
                                <a href="{{ route('admin.pedagogie', ['creator' => $creator->id]) }}"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ $creatorFilter == $creator->id ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-50' }}">
                                    <img class="size-7 rounded-lg border {{ $creatorFilter == $creator->id ? 'border-white/30' : 'border-slate-100' }}"
                                         src="https://ui-avatars.com/api/?name={{ urlencode($creator->prenom . ' ' . $creator->nom) }}&background={{ $creator->type_profil === 'admin' ? '0f172a' : '0ea5e9' }}&color=fff&bold=true"
                                         alt="">
                                    <div>
                                        <p class="leading-tight">{{ $creator->prenom }} {{ $creator->nom }}</p>
                                        <p class="text-[9px] font-black uppercase tracking-widest {{ $creatorFilter == $creator->id ? 'opacity-60' : 'text-slate-400' }}">{{ ucfirst($creator->type_profil) }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <button @click="openSeanceCreate()" type="button"
                    class="h-14 px-8 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-slate-900/20 hover:bg-primary-500 hover:-translate-y-1 transition-all flex items-center gap-3">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4" /></svg>
                    Nouvelle Session
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="relative z-10 grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="size-10 bg-slate-900 rounded-xl flex items-center justify-center">
                    <svg class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M8 7V3m8 4V3m-9 4h10a2 2 0 012 2v10a2 2 0 01-2 2H9a2 2 0 01-2-2V9a2 2 0 012-2z" /></svg>
                </div>
                <div>
                    <span class="text-2xl font-black text-slate-900">{{ $seances->count() }}</span>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider ml-1">Sessions</span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="size-10 bg-primary-500 rounded-xl flex items-center justify-center">
                    <svg class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                </div>
                <div>
                    <span class="text-2xl font-black text-slate-900">{{ $seances->sum(fn($s) => $s->unitesApprentissage->count()) }}</span>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider ml-1">Unités</span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="size-10 bg-emerald-500 rounded-xl flex items-center justify-center">
                    <svg class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                </div>
                <div>
                    <span class="text-2xl font-black text-slate-900">{{ $seances->sum(fn($s) => $s->unitesApprentissage->sum(fn($ua) => $ua->competences->count())) }}</span>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider ml-1">Skills</span>
                </div>
            </div>
        </div>
        <!-- <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="size-10 bg-amber-500 rounded-xl flex items-center justify-center">
                    <svg class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <span class="text-2xl font-black text-slate-900">{{ $seances->count() > 0 ? \Carbon\Carbon::now()->diffInDays($seances->first()->date) : 0 }}</span>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider ml-1">Jours</span>
                </div>
            </div>
        </div> -->
    </div>

    <!-- Hierarchy Tree -->
    <div class="relative z-10 space-y-4">
        @forelse($seances as $seance)
            <div x-data="{ 
                expanded: localStorage.getItem('seance_expanded_' + {{ $seance->id }}) === 'true',
                options: false,
                toggle() {
                    this.expanded = !this.expanded;
                    localStorage.setItem('seance_expanded_' + {{ $seance->id }}, this.expanded);
                }
            }" class="group" :class="options ? 'z-[100]' : 'z-10'">
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:border-slate-200 transition-all duration-300 overflow-hidden">
                    
                    <!-- Main Content -->
                    <div class="p-5 md:p-6 cursor-pointer" @click="toggle()">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-5">
                                <div class="relative">
                                    <div class="size-14 rounded-2xl flex items-center justify-center transition-all duration-300"
                                         :class="expanded ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-primary-50 group-hover:text-primary-500'">
                                        <svg class="size-6 transition-transform duration-300" :class="expanded ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7" /></svg>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-[0.2em]">Session Majeure</span>
                                        @if($seance->date_debut && $seance->date_fin)
                                            <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                            <span class="text-[8px] font-bold text-primary-600 uppercase tracking-widest">Du {{ $seance->date_debut->format('d/m') }} au {{ $seance->date_fin->format('d/m') }}</span>
                                        @endif
                                    </div>
                                    <h2 class="text-xl font-black text-slate-900 group-hover:text-primary-600 transition-colors">{{ $seance->nom }}</h2>
                                    {{-- Creator badge --}}
                                    @if($seance->user)
                                    <div class="flex items-center gap-2 mt-1.5">
                                        <!-- <img class="size-5 rounded-md border border-slate-100"
                                             src="https://ui-avatars.com/api/?name={{ urlencode($seance->user->prenom . ' ' . $seance->user->nom) }}&background={{ $seance->user->type_profil === 'admin' ? '0f172a' : '0ea5e9' }}&color=fff&bold=true&size=32"
                                             alt=""> -->
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $seance->user->prenom }} {{ $seance->user->nom }}</span>
                                        <span class="text-[8px] font-black px-1.5 py-0.5 rounded-md {{ $seance->user->type_profil === 'admin' ? 'bg-slate-900 text-white' : 'bg-primary-50 text-primary-600 border border-primary-100' }} uppercase tracking-widest">{{ ucfirst($seance->user->type_profil) }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-6">
                                <div class="hidden md:flex items-center gap-4">
                                    <div class="bg-slate-50 px-3 py-1.5 rounded-lg">
                                        <span class="text-lg font-black text-slate-800">{{ $seance->unitesApprentissage->count() }}</span>
                                        <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider ml-1">UA</span>
                                    </div>
                                    <div class="bg-emerald-50 px-3 py-1.5 rounded-lg">
                                        <span class="text-lg font-black text-emerald-600">{{ $seance->unitesApprentissage->sum(fn($ua) => $ua->competences->count()) }}</span>
                                        <span class="text-[8px] font-bold text-emerald-400 uppercase tracking-wider ml-1">Skills</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <button @click.stop="openSeanceEdit({{ $seance->id }}, '{{ addslashes($seance->nom) }}', '{{ $seance->date_debut ? $seance->date_debut->format('Y-m-d') : '' }}', '{{ $seance->date_fin ? $seance->date_fin->format('Y-m-d') : '' }}', '{{ $seance->user_id }}')"
                                            type="button"
                                            class="size-10 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-primary-50 hover:text-primary-500 transition-all">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>

                                    <button @click.stop="$dispatch('confirm', { 
                                                title: 'Archiver la session ?', 
                                                message: 'Cette session et tout son contenu seront retirés.', 
                                                onConfirm: 'delete-seance-{{ $seance->id }}' 
                                            })"
                                            type="button"
                                            class="size-10 rounded-xl bg-slate-50 text-slate-300 flex items-center justify-center hover:bg-rose-50 hover:text-rose-500 transition-all">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                    <form id="delete-seance-{{ $seance->id }}" action="{{ route('admin.pedagogie.seance.destroy', $seance->id) }}" method="POST" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Expanded Details -->
                    <div x-show="expanded" x-collapse class="border-t border-slate-50 bg-slate-50/30">
                        <div class="p-5 md:p-6">
                            <div class="flex justify-between items-center mb-5">
                                <div class="flex items-center gap-2">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.15em]">Unités d'Apprentissage</span>
                                    <span class="px-2 py-0.5 bg-slate-900 text-white text-[8px] font-bold rounded-full">{{ $seance->unitesApprentissage->count() }}</span>
                                </div>
                                <button @click.stop="openUaCreate({{ $seance->id }})"
                                        type="button"
                                        class="flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-[9px] font-bold text-slate-600 uppercase tracking-wider hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all">
                                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
                                    Injecter
                                </button>
                            </div>

                            <div class="space-y-2 pl-3 border-l-2 border-slate-200">
                                @forelse($seance->unitesApprentissage as $ua)
                                    <div x-data="{ 
                                        expandedUa: localStorage.getItem('ua_expanded_' + {{ $ua->id }}) === 'true',
                                        optionsUa: false,
                                        toggleUa() {
                                            this.expandedUa = !this.expandedUa;
                                            localStorage.setItem('ua_expanded_' + {{ $ua->id }}, this.expandedUa);
                                        }
                                    }" class="group/ua" :class="optionsUa ? 'z-[50]' : 'z-10'">
                                        <div class="bg-white p-4 rounded-xl border border-slate-100 hover:border-primary-200 hover:shadow-md transition-all cursor-pointer"
                                             @click.stop="toggleUa()">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <div class="size-8 rounded-lg flex items-center justify-center transition-all duration-200"
                                                         :class="expandedUa ? 'bg-slate-900 text-white rotate-90' : 'bg-slate-50 text-slate-400'">
                                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7" /></svg>
                                                    </div>
                                                    <div>
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-[8px] font-black text-primary-600 uppercase tracking-widest">{{ $ua->code }}</span>
                                                            @if($ua->date_debut && $ua->date_fin)
                                                                <span class="text-[7px] font-bold text-slate-400 uppercase tracking-tighter">({{ $ua->date_debut->format('d/m') }} -> {{ $ua->date_fin->format('d/m') }})</span>
                                                            @endif
                                                        </div>
                                                        <h4 class="text-sm font-bold text-slate-800">{{ $ua->nom }}</h4>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <button @click.stop="openUaEdit({{ $ua->id }}, '{{ addslashes($ua->nom) }}', '{{ addslashes($ua->code) }}', '{{ $ua->date_debut ? $ua->date_debut->format('Y-m-d') : '' }}', '{{ $ua->date_fin ? $ua->date_fin->format('Y-m-d') : '' }}', '{{ $ua->user_id }}')"
                                                            type="button"
                                                            class="size-8 rounded-lg bg-slate-50 text-slate-400 hover:bg-primary-50 hover:text-primary-500 transition-all flex items-center justify-center">
                                                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                    </button>
                                                    <form id="delete-ua-{{ $ua->id }}" action="{{ route('admin.pedagogie.ua.destroy', $ua->id) }}" method="POST" class="hidden">
                                                        @csrf @method('DELETE')
                                                    </form>
                                                    <button type="button" 
                                                            @click.stop="$dispatch('confirm', { 
                                                                title: 'Supprimer l\'UA ?', 
                                                                onConfirm: 'delete-ua-{{ $ua->id }}' 
                                                            })"
                                                            class="size-8 rounded-lg bg-slate-50 text-slate-300 hover:bg-rose-50 hover:text-rose-500 transition-all flex items-center justify-center">
                                                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12" /></svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Competences -->
                                            <div x-show="expandedUa" x-collapse class="mt-3 pt-3 border-t border-slate-50">
                                                <div class="flex justify-between items-center mb-2">
                                                    <span class="text-[8px] font-black text-emerald-600 uppercase tracking-widest">Compétences</span>
                                                    <button @click.stop="openCompCreate({{ $ua->id }})"
                                                            type="button"
                                                            class="text-[8px] font-bold text-emerald-500 hover:text-emerald-700 uppercase tracking-wider">
                                                        + Fixer
                                                    </button>
                                                </div>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                    @forelse($ua->competences as $comp)
                                                        <div class="bg-emerald-50 p-2.5 rounded-lg border border-emerald-100 flex justify-between items-center group/comp relative">
                                                            <div class="flex items-center gap-2">
                                                                <span class="size-1.5 bg-emerald-400 rounded-full"></span>
                                                                <span class="text-[8px] font-bold text-emerald-600">{{ $comp->code }}</span>
                                                                <span class="text-xs font-medium text-slate-700">{{ $comp->libelle }}</span>
                                                            </div>
                                                            <div class="flex items-center gap-1 opacity-0 group-hover/comp:opacity-100 transition-opacity">
                                                                <button @click.stop="openCompEdit({{ $comp->id }}, '{{ addslashes($comp->libelle) }}', '{{ addslashes($comp->code) }}', '{{ addslashes($comp->description) }}')"
                                                                        type="button"
                                                                        class="p-1 text-emerald-400 hover:text-emerald-600">
                                                                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                                </button>
                                                                <form id="delete-comp-{{ $comp->id }}" action="{{ route('admin.pedagogie.competence.destroy', $comp->id) }}" method="POST" class="hidden">
                                                                    @csrf @method('DELETE')
                                                                </form>
                                                                <button type="button" 
                                                                        @click.stop="$dispatch('confirm', { 
                                                                            title: 'Retirer le pilier ?', 
                                                                            onConfirm: 'delete-comp-{{ $comp->id }}' 
                                                                        })"
                                                                        class="p-1 text-slate-300 hover:text-rose-500">
                                                                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12" /></svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <p class="col-span-full text-center text-[8px] font-medium text-slate-400 py-2">Aucun pilier défini</p>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6 bg-white/50 rounded-xl border-2 border-dashed border-slate-100">
                                        <p class="text-slate-400 text-xs font-medium">Aucune UA enregistrée</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-gradient-to-br from-slate-50 to-white rounded-2xl border-2 border-dashed border-slate-200 p-12 text-center">
                <div class="size-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100 shadow-sm">
                    <svg class="size-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                </div>
                <h3 class="text-lg font-black text-slate-900 mb-1">Ossature Vierge</h3>
                <p class="text-slate-500 text-sm mb-4">Créez votre première session pédagogique</p>
                <button @click="openSeanceCreate()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 text-white rounded-lg font-bold text-xs uppercase hover:bg-primary-600 transition-all">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 4v16m8-8H4" /></svg>
                    Ajouter Session
                </button>
            </div>
        @endforelse
    </div>

    <!-- Modals -->
    <template x-teleport="body">
        <div>
            <x-ui.modal name="seance-modal" maxWidth="md">
                <template x-if="seanceMode === 'create'">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="size-10 bg-slate-900 rounded-xl flex items-center justify-center">
                            <svg class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M8 7V3m8 4V3m-9 4h10a2 2 0 012 2v10a2 2 0 01-2 2H9a2 2 0 01-2-2V9a2 2 0 012-2z" /></svg>
                        </div>
                        <h3 class="text-lg font-black text-slate-800" x-text="seanceMode === 'create' ? 'Inaugurer une Session' : 'Réviser la Session'"></h3>
                    </div>
                </template>
                <template x-if="seanceMode === 'edit'">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="size-10 bg-amber-500 rounded-xl flex items-center justify-center">
                            <svg class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </div>
                        <h3 class="text-lg font-black text-slate-800" x-text="seanceMode === 'create' ? 'Inaugurer une Session' : 'Réviser la Session'"></h3>
                    </div>
                </template>
                <form x-bind:action="seanceMode === 'create' ? '{{ route('admin.pedagogie.seance.store') }}' : `{{ url('/admin/pedagogie/seance') }}/${seanceId}`" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="seanceMode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 block">Titre de la Session</label>
                        <input type="text" name="nom" x-model="seanceNom" required
                               placeholder="Ex: Session Automne 2024"
                               class="w-full bg-slate-50 border-2 border-transparent rounded-lg py-3 px-4 font-bold text-slate-800 focus:bg-white focus:border-slate-400 focus:ring-0 transition-all text-sm @error('nom') border-rose-500 @enderror">
                        @error('nom') <p class="text-[10px] text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex flex-col gap-4">
                        <div>
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 block">Date Début</label>
                            <x-ui.date-picker name="date_debut" x-model="seanceDateDebut" />
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 block">Date Fin</label>
                            <x-ui.date-picker name="date_fin" x-model="seanceDateFin" />
                        </div>
                    </div>
                    
                    <div x-data="{ openDropdown: false, label: 'Sélectionner un formateur' }" 
                         x-init="$watch('seanceFormateurId', value => {
                            if(!value) { label = 'Sélectionner un formateur'; return; }
                            const opt = $el.querySelector(`[data-id='${value}']`);
                            if(opt) label = opt.innerText;
                         })"
                         class="relative">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 block">Assigner à un Formateur</label>
                        <input type="hidden" name="user_id" x-model="seanceFormateurId">
                        
                        <button type="button" @click="openDropdown = !openDropdown" @click.away="openDropdown = false"
                                class="w-full bg-slate-50 border-2 border-transparent rounded-lg py-3 px-4 font-bold text-slate-800 flex items-center justify-between hover:bg-white hover:border-slate-200 transition-all text-sm">
                            <span x-text="label"></span>
                            <svg class="size-4 text-slate-400 transition-transform" :class="openDropdown ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="openDropdown" 
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute bottom-full left-0 w-full mb-2 bg-white border border-slate-100 rounded-xl shadow-premium overflow-hidden py-1"
                             style="display: none;">
                            @foreach($formateurs as $formateur)
                                <button type="button" 
                                        data-id="{{ $formateur->id }}"
                                        @click="seanceFormateurId = '{{ $formateur->id }}'; label = '{{ $formateur->prenom }} {{ $formateur->nom }}'; openDropdown = false"
                                        class="w-full px-4 py-2.5 text-left text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-primary-600 transition-colors flex items-center gap-3">
                                    <div class="size-6 rounded bg-slate-100 flex items-center justify-center text-[9px] font-black text-slate-400">
                                        {{ substr($formateur->prenom, 0, 1) }}{{ substr($formateur->nom, 0, 1) }}
                                    </div>
                                    {{ $formateur->prenom }} {{ $formateur->nom }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" 
                            class="w-full py-3 rounded-lg font-bold text-xs uppercase tracking-wider transition-all shadow-lg active:scale-95"
                            :class="seanceMode === 'create' ? 'bg-slate-900 text-white hover:bg-primary-500 shadow-slate-900/10' : 'bg-primary-500 text-white hover:bg-primary-600 shadow-primary-500/10'"
                            x-text="seanceMode === 'create' ? 'Inaugurer la Session' : 'Conserver'"></button>
                </form>
            </x-ui.modal>

            <x-ui.modal name="ua-modal" maxWidth="md">
                <template x-if="uaMode === 'create'">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="size-10 bg-primary-100 rounded-xl flex items-center justify-center">
                            <svg class="size-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <h3 class="text-lg font-black text-slate-800">Injecter UA</h3>
                    </div>
                </template>
                <template x-if="uaMode === 'edit'">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="size-10 bg-amber-500 rounded-xl flex items-center justify-center">
                            <svg class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </div>
                        <h3 class="text-lg font-black text-slate-800">Modifier UA</h3>
                    </div>
                </template>
                <form x-bind:action="uaMode === 'create' ? `{{ url('/admin/pedagogie/seance') }}/${activeSeanceId}/ua` : `{{ url('/admin/pedagogie/ua') }}/${uaId}`" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="uaMode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
                    <div class="grid grid-cols-2 gap-3">
                        <div x-data="{ codeExists: false, checkCode() {
                            if(!uaCode) { this.codeExists = false; return; }
                            fetch(`/api/check-code?type=ua&code=${uaCode}`).then(r => r.json()).then(d => this.codeExists = d.exists);
                        } }" class="relative">
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 block">Code</label>
                            <input type="text" name="code" x-model="uaCode" @input.debounce.500ms="checkCode" required
                                   placeholder="UA-XX"
                                   class="w-full bg-slate-50 border-2 border-transparent rounded-lg py-3 px-4 font-bold text-slate-800 uppercase focus:bg-white focus:border-primary-400 focus:ring-0 transition-all text-sm @error('code') border-rose-500 @enderror">
                            @error('code') <p class="text-[10px] text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                            <div x-show="codeExists" x-transition class="absolute bottom-full left-0 mb-2 w-full bg-amber-50 border border-amber-200 rounded-lg p-2 shadow-lg z-50 flex items-start gap-2" style="display: none;">
                                <svg class="size-4 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <p class="text-[10px] font-bold text-amber-700 leading-tight">Ce code existe déjà, mais vous pouvez continuer.</p>
                            </div>
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 block">Libellé</label>
                            <input type="text" name="nom" x-model="uaNom" required
                                   placeholder="Nom de l'unité"
                                   class="w-full bg-slate-50 border-2 border-transparent rounded-lg py-3 px-4 font-bold text-slate-800 focus:bg-white focus:border-primary-400 focus:ring-0 transition-all text-sm @error('nom') border-rose-500 @enderror">
                            @error('nom') <p class="text-[10px] text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div x-data="{ openDropdown: false, label: 'Sélectionner un formateur' }" 
                         x-init="$watch('uaFormateurId', value => {
                            if(!value) { label = 'Sélectionner un formateur'; return; }
                            const opt = $el.querySelector(`[data-id='${value}']`);
                            if(opt) label = opt.innerText;
                         })"
                         class="relative">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 block">Assigner à un Formateur</label>
                        <input type="hidden" name="user_id" x-model="uaFormateurId">
                        
                        <button type="button" @click="openDropdown = !openDropdown" @click.away="openDropdown = false"
                                class="w-full bg-slate-50 border-2 border-transparent rounded-lg py-3 px-4 font-bold text-slate-800 flex items-center justify-between hover:bg-white hover:border-primary-200 transition-all text-sm">
                            <span x-text="label"></span>
                            <svg class="size-4 text-slate-400 transition-transform" :class="openDropdown ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="openDropdown" 
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute bottom-full left-0 w-full mb-2 bg-white border border-slate-100 rounded-xl shadow-premium overflow-hidden py-1 z-50"
                             style="display: none;">
                            @foreach($formateurs as $formateur)
                                <button type="button" 
                                        data-id="{{ $formateur->id }}"
                                        @click="uaFormateurId = '{{ $formateur->id }}'; label = '{{ $formateur->prenom }} {{ $formateur->nom }}'; openDropdown = false"
                                        class="w-full px-4 py-2.5 text-left text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-primary-600 transition-colors flex items-center gap-3">
                                    <div class="size-6 rounded bg-slate-100 flex items-center justify-center text-[9px] font-black text-slate-400">
                                        {{ substr($formateur->prenom, 0, 1) }}{{ substr($formateur->nom, 0, 1) }}
                                    </div>
                                    {{ $formateur->prenom }} {{ $formateur->nom }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" 
                            class="w-full py-3 rounded-lg font-bold text-xs uppercase tracking-wider transition-all shadow-lg active:scale-95"
                            :class="uaMode === 'create' ? 'bg-slate-900 text-white hover:bg-primary-500 shadow-slate-900/10' : 'bg-primary-500 text-white hover:bg-primary-600 shadow-primary-500/10'"
                            x-text="uaMode === 'create' ? 'Fixer l\'Unité' : 'Mettre à jour'"></button>
                </form>
            </x-ui.modal>

            <x-ui.modal name="comp-modal" maxWidth="md">
                <template x-if="compMode === 'create'">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="size-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <svg class="size-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                        </div>
                        <h3 class="text-lg font-black text-slate-800">Fixer Compétence</h3>
                    </div>
                </template>
                <template x-if="compMode === 'edit'">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="size-10 bg-amber-500 rounded-xl flex items-center justify-center">
                            <svg class="size-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </div>
                        <h3 class="text-lg font-black text-slate-800">Modifier Compétence</h3>
                    </div>
                </template>
                <form x-bind:action="compMode === 'create' ? `{{ url('/admin/pedagogie/ua') }}/${activeUaId}/competence` : `{{ url('/admin/pedagogie/competence') }}/${compId}`" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="compMode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
                    <div class="grid grid-cols-2 gap-3">
                        <div x-data="{ codeExists: false, checkCode() {
                            if(!compCode) { this.codeExists = false; return; }
                            fetch(`/api/check-code?type=competence&code=${compCode}`).then(r => r.json()).then(d => this.codeExists = d.exists);
                        } }" class="relative">
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 block">Code</label>
                            <input type="text" name="code" x-model="compCode" @input.debounce.500ms="checkCode" required
                                   placeholder="C-XXX"
                                   class="w-full bg-slate-50 border-2 border-transparent rounded-lg py-3 px-4 font-bold text-slate-800 uppercase focus:bg-white focus:border-emerald-400 focus:ring-0 transition-all text-sm @error('code') border-rose-500 @enderror">
                            @error('code') <p class="text-[10px] text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                            <div x-show="codeExists" x-transition class="absolute bottom-full left-0 mb-2 w-full bg-amber-50 border border-amber-200 rounded-lg p-2 shadow-lg z-50 flex items-start gap-2" style="display: none;">
                                <svg class="size-4 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <p class="text-[10px] font-bold text-amber-700 leading-tight">Ce code existe déjà, mais vous pouvez continuer.</p>
                            </div>
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 block">Libellé</label>
                            <input type="text" name="libelle" x-model="compNom" required
                                   placeholder="Compétence à maîtriser"
                                   class="w-full bg-slate-50 border-2 border-transparent rounded-lg py-3 px-4 font-bold text-slate-800 focus:bg-white focus:border-emerald-400 focus:ring-0 transition-all text-sm @error('libelle') border-rose-500 @enderror">
                            @error('libelle') <p class="text-[10px] text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 block">Description</label>
                        <textarea name="description" rows="2" x-model="compDesc"
                                  placeholder="Description de la compétence..."
                                  class="w-full bg-slate-50 border-2 border-transparent rounded-lg py-3 px-4 font-bold text-slate-800 focus:bg-white focus:border-emerald-400 focus:ring-0 transition-all text-sm resize-none"></textarea>
                    </div>
                    <button type="submit" 
                            class="w-full py-3 bg-emerald-500 text-white rounded-lg font-bold text-xs uppercase tracking-wider transition-all shadow-lg active:scale-95 shadow-emerald-500/10 hover:bg-emerald-600"
                            :class="compMode === 'create' ? 'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/10' : 'bg-amber-500 hover:bg-amber-600 shadow-amber-500/10'"
                            x-text="compMode === 'create' ? 'Consigner le Pilier' : 'Conserver'"></button>
                </form>
            </x-ui.modal>
        </div>
    </template>
</div>
@endsection