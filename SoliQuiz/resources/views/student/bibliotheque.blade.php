@extends('layouts.app')

@section('title', 'Bibliothèque - SoliQuiz')

@section('content')
<div class="space-y-10 reveal active">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-6 border-b border-slate-200">
        <div class="flex-1">
            <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">
                <a href="{{ route('dashboard') }}" class="hover:text-primary-500 transition-colors">Plateforme</a>
                <span class="size-1 rounded-full bg-slate-300"></span>
                <span class="text-slate-600">Bibliothèque</span>
            </nav>
            <h1 class="text-3xl lg:text-4xl font-heading font-black text-slate-900 tracking-tight leading-tight">
                Catalogue des <span class="text-primary-600">Évaluations</span>
            </h1>
        </div>
        
        <!-- <form action="{{ route('student.bibliotheque') }}" method="GET" class="w-full lg:w-[400px]">
            <div class="relative group">
                <input type="text" name="search" value="{{ $search ?? '' }}" 
                       placeholder="Rechercher un QCM..." 
                       class="w-full bg-white border border-slate-200 rounded-2xl py-4 pl-12 pr-4 text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all placeholder:text-slate-400 font-medium">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-slate-400 group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                @if($search)
                    <a href="{{ route('student.bibliotheque') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </div>
        </form> -->
    </div>

    <!-- Top Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
        <!-- Dashboard Main Stat -->
        <div class="bg-slate-900 p-5 sm:px-8 rounded-3xl shadow-xl relative overflow-hidden group lg:col-span-2 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="absolute -right-8 -top-8 size-48 bg-primary-500/10 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
            
            <div class="relative z-10 flex-1 flex items-center gap-6">
                <div>
                    <h3 class="text-[9px] font-black text-primary-400 uppercase tracking-[0.2em] mb-1.5 flex items-center gap-1.5">
                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        Ma Performance
                    </h3>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-black text-white font-heading tracking-tight">{{ $moyenne }}<span class="text-primary-500 text-2xl">/20</span></span>
                    </div>
                </div>
                <div class="h-10 w-px bg-white/10 hidden sm:block"></div>
                <div class="hidden sm:block">
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest max-w-[120px] leading-tight">Score Moyen Global Actuel</p>
                </div>
            </div>
            
            <div class="relative z-10 flex items-center md:justify-end gap-3 pt-4 md:pt-0 border-t md:border-t-0 border-white/5">
                <div class="text-left md:text-right">
                    <span class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-0.5">Évaluations</span>
                    <span class="text-2xl font-black text-white font-heading">{{ $termines->count() }} <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">achevées</span></span>
                </div>
            </div>
        </div>

        <!-- Pending Stat -->
        <div class="bg-white p-5 sm:px-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 flex items-center relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 size-20 bg-primary-50 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-500 group-hover:scale-150"></div>
            <div class="relative z-10 w-full flex items-center justify-between">
                <div>
                    <h3 class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1 border-l-2 border-primary-500 pl-2">À Réaliser</h3>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-black text-slate-900 font-heading leading-none">{{ $aFaire->count() }}</span>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">QCM Restants</span>
                    </div>
                </div>
                <!-- <div class="size-10 rounded-xl bg-primary-50 text-primary-500 flex items-center justify-center group-hover:bg-primary-500 group-hover:text-white transition-colors">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-linecap="round"/></svg>
                </div> -->
            </div>
        </div>
    </div>

    <!-- Dynamic List -->
    <section class="space-y-8">
            <div class="bg-white p-4 rounded-[2.5rem] shadow-[0_8px_30px_-4px_rgba(0,0,0,0.04)] border border-slate-100">
                <div class="bg-slate-50/50 rounded-[2rem] overflow-hidden">
                    @if($enCours->count() === 0 && $aFaire->count() === 0 && $termines->count() === 0)
                        <div class="text-center py-20">
                            <div class="size-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 shadow-inner">
                                <svg class="size-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-linecap="round"/></svg>
                            </div>
                            <h4 class="text-xl font-heading font-black text-slate-900 mb-2">Catalogue vide</h4>
                            <p class="text-slate-400 font-medium italic text-sm">Patientez le temps que votre formateur déploie un test.</p>
                        </div>
                    @endif

                    {{-- Priority Items: En Cours / À Faire --}}
                    @if($enCours->count() > 0 || $aFaire->count() > 0)
                    <div class="p-8 border-b border-slate-100">
                        <div class="grid grid-cols-1 gap-4">
                            @foreach(collect()->merge($enCours)->merge($aFaire) as $qcm)
                                <div class="group relative bg-white border border-slate-200 hover:border-primary-300 rounded-[1.5rem] p-4 sm:p-6 transition-all duration-500 hover:shadow-lg flex flex-col sm:flex-row sm:items-center justify-between gap-6 overflow-hidden">
                                    
                                    <!-- Background accent -->
                                    <div class="absolute -right-10 -top-10 size-40 bg-gradient-to-br {{ $qcm->etat === 'en_cours' ? 'from-primary-100 to-transparent' : 'from-slate-100 to-transparent' }} rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
    
                                    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center gap-5 flex-1">
                                        <div class="size-14 rounded-2xl {{ $qcm->etat === 'en_cours' ? 'bg-gradient-to-br from-primary-500 to-primary-600 text-white shadow-lg shadow-primary-500/30 animate-pulse-slow' : 'bg-slate-50 text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-500 transition-colors' }} flex items-center justify-center shrink-0">
                                            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                        
                                        <div>
                                            <div class="flex items-center gap-2 mb-1.5">
                                                <h3 class="text-lg font-heading font-black text-slate-900 tracking-tight leading-snug group-hover:text-primary-600 transition-colors">{{ $qcm->titre }}</h3>
                                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-widest {{ $qcm->etat === 'en_cours' ? 'bg-primary-50 text-primary-600 border border-primary-100' : 'bg-slate-100 text-slate-500' }}">
                                                    <span class="size-1.5 rounded-full {{ $qcm->etat === 'en_cours' ? 'bg-primary-500 animate-pulse' : 'bg-slate-400' }}"></span>
                                                    {{ $qcm->etat === 'en_cours' ? 'En cours' : 'Prêt' }}
                                                </span>
                                            </div>
                                            
                                            <div class="flex flex-wrap items-center gap-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                                <span class="truncate max-w-[200px] sm:max-w-none">{{ $qcm->uniteApprentissage ? $qcm->uniteApprentissage->nom : 'Évaluation transverse' }}</span>
                                                <span class="size-1 rounded-full bg-slate-300"></span>
                                                <span class="flex items-center gap-1"><svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg> {{ $qcm->duree_minutes }} min</span>
                                                <span class="size-1 rounded-full bg-slate-300"></span>
                                                <span class="flex items-center gap-1"><svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg> {{ $qcm->questions_count }} Qst</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="relative z-10 flex items-center justify-between sm:justify-end gap-6 sm:w-auto w-full border-t sm:border-t-0 border-slate-100 pt-4 sm:pt-0 mt-2 sm:mt-0 shrink-0">
                                        <div class="text-left sm:text-right hidden lg:block">
                                            <span class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-0.5">Objectif</span>
                                            <span class="text-sm font-black text-slate-700">{{ $qcm->score_reussite }}/20</span>
                                        </div>
                                        <div class="h-8 w-px bg-slate-200 hidden lg:block"></div>
                                        <a href="{{ route('student.passation', $qcm->id) }}"
                                            class="h-12 px-6 {{ $qcm->etat === 'en_cours' ? 'bg-slate-900 text-white hover:bg-slate-800 shadow-lg shadow-slate-900/20' : 'bg-primary-50 text-primary-600 hover:bg-primary-600 hover:text-white' }} rounded-xl font-black text-[10px] uppercase tracking-[0.2em] transition-all duration-300 flex items-center justify-center gap-2 active:scale-[0.98] w-full sm:w-auto">
                                            {{ $qcm->etat === 'en_cours' ? 'Reprendre' : 'Démarrer' }}
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Completed Items --}}
                    @if($termines->count() > 0)
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-3">
                                <span class="size-2 rounded-full bg-slate-300"></span>
                                Évaluations Terminées
                            </h3>
                            <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-md">{{ $termines->count() }} test(s)</span>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-4">
                            @foreach($termines as $qcm)
                                @php
                                    $isSuccess = ($qcm->score ?? 0) >= ($qcm->score_reussite ?? 10);
                                @endphp
                                <a href="{{ route('student.resultats', $qcm->id) }}" class="group block bg-white border border-slate-200 hover:border-slate-300 rounded-[1.5rem] p-4 sm:p-6 transition-all duration-300 hover:shadow-lg hover:shadow-slate-200/50">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                        <div class="flex items-center gap-5">
                                            <div class="size-12 rounded-xl {{ $isSuccess ? 'bg-emerald-50 text-emerald-500 group-hover:bg-emerald-500 group-hover:text-white' : 'bg-rose-50 text-rose-500 group-hover:bg-rose-500 group-hover:text-white' }} flex items-center justify-center transition-colors duration-300 shrink-0">
                                                @if($isSuccess)
                                                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                                @else
                                                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                                @endif
                                            </div>
                                            <div>
                                                <h4 class="text-base font-black text-slate-900 tracking-tight leading-none mb-1.5">{{ $qcm->titre }}</h4>
                                                <div class="flex items-center gap-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                                    <span>{{ $qcm->date_fin->format('d M Y') }}</span>
                                                    <span class="size-1 rounded-full bg-slate-300"></span>
                                                    <span class="truncate max-w-[150px] sm:max-w-none">{{ $qcm->uniteApprentissage ? $qcm->uniteApprentissage->nom : 'Évaluation' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center justify-between sm:justify-end gap-6 sm:w-auto w-full border-t sm:border-t-0 border-slate-100 pt-4 sm:pt-0 mt-2 sm:mt-0">
                                            <div class="text-left sm:text-right">
                                                <span class="block text-[10px] font-black uppercase tracking-widest mb-1 {{ $isSuccess ? 'text-emerald-500' : 'text-rose-500' }}">{{ $isSuccess ? 'Objectif atteint' : 'Non validé' }}</span>
                                                <span class="text-2xl font-black font-heading text-slate-900">{{ $qcm->score }}<span class="text-sm text-slate-400">/20</span></span>
                                            </div>
                                            <div class="size-10 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 group-hover:bg-slate-900 group-hover:border-slate-900 group-hover:text-white transition-all duration-300 shrink-0">
                                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </section>
</div>
@endsection