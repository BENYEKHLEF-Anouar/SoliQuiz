@extends('layouts.app')

@section('title', 'Bibliothèque de Contenus - SoliQuiz')

@section('content')
<div class="reveal active">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 mb-12 pb-8 border-b border-slate-100">
        <div>
            <x-ui.breadcrumb :items="['Espace Formateur' => route('dashboard'), 'Bibliothèque' => null]" />
            <h1 class="text-4xl lg:text-5xl font-heading font-black text-slate-900 tracking-tight leading-none mb-4 uppercase italic">
                Content <span class="text-primary-600">Repository</span>
            </h1>
            
            <form action="{{ route('formateur.bibliotheque') }}" method="GET" class="mt-8 w-full lg:w-[450px] relative group">
                <input type="text" name="search" value="{{ $search ?? '' }}" 
                       placeholder="Filtrer vos QCMs par titre..." 
                       class="w-full bg-white border border-slate-200 rounded-[24px] py-5 pl-14 pr-4 text-sm focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 outline-none transition-all placeholder:text-slate-400 font-medium shadow-sm">
                <svg class="absolute left-5 top-1/2 -translate-y-1/2 size-6 text-slate-300 group-focus-within:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                @if($search)
                    <a href="{{ route('formateur.bibliotheque') }}" class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-300 hover:text-slate-600">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </form>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="glass bg-white p-6 rounded-[24px] border border-slate-100 flex flex-col items-center sm:items-end justify-center shadow-sm min-w-[180px]">
                <span class="text-[9px] font-black tracking-[0.2em] text-slate-400 uppercase mb-1">Passages Cumulés</span>
                <span class="text-3xl font-black text-slate-900 font-heading leading-none">{{ $qcms->sum('tentatives_count') }}</span>
            </div>
            
            <a href="{{ route('formateur.qcm.create') }}"
                class="group px-8 py-5 bg-slate-900 text-white rounded-[24px] font-black text-xs uppercase tracking-[0.2em] hover:bg-primary-500 shadow-xl shadow-slate-900/10 active:scale-95 transition-all flex items-center justify-center gap-3">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
                Générer QCM
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="glass border-emerald-100 bg-emerald-50/50 p-6 rounded-[24px] flex items-center gap-4 mb-10 animate-in slide-in-from-top duration-500">
            <div class="size-10 bg-emerald-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
            </div>
            <p class="text-sm font-bold text-slate-900">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Library Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($qcms as $qcm)
            @php
                $totalStudents = $qcm->classe ? $qcm->classe->etudiants()->count() : 0;
                $completedStudents = $qcm->classe ? $qcm->tentatives()->whereIn('statut', ['reussi', 'echoue', 'abandonne'])->distinct('etudiant_id')->count('etudiant_id') : 0;
                $completionRate = $totalStudents > 0 ? round(($completedStudents / $totalStudents) * 100) : 0;
                $isFullyCompleted = $completionRate >= 100 && $totalStudents > 0;
            @endphp
            <div class="group bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-[0_8px_30px_-4px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 flex flex-col relative overflow-hidden active:scale-[0.98]">
                <!-- Status & Stats -->
                <div class="flex justify-between items-start mb-6 relative z-10">
                    @if($qcm->statut === 'public')
                        <div class="flex flex-col gap-1">
                            <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-[0.2em] border border-emerald-100/50">
                                <span class="size-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Live & Actif
                            </span>
                            @if($qcm->classe)
                                <span class="text-[8px] font-bold text-slate-400 uppercase ps-1 tracking-widest">Cible: {{ $qcm->classe->nom }}</span>
                            @endif
                        </div>
                    @elseif($qcm->statut === 'termine')
                        <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full bg-slate-800 text-white text-[9px] font-black uppercase tracking-[0.2em] border border-slate-700">
                            <span class="size-1.5 rounded-full bg-slate-400"></span>
                            Terminé
                        </span>
                    @else
                        <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full bg-amber-50 text-amber-600 text-[9px] font-black uppercase tracking-[0.2em] border border-amber-100/50">
                            <span class="size-1.5 rounded-full bg-amber-500"></span>
                            Brouillon
                        </span>
                    @endif

                    <div class="size-12 bg-slate-50 rounded-[1.25rem] flex flex-col items-center justify-center border border-slate-100 group-hover:bg-primary-500 group-hover:text-white transition-colors shadow-[0_8px_30px_-4px_rgba(0,0,0,0.04)]">
                        <span class="text-xs font-black leading-none">{{ $qcm->tentatives_count }}</span>
                        <span class="text-[7px] font-black uppercase tracking-wide opacity-60">Passages</span>
                    </div>
                </div>

                <!-- Completion Progress (for public QCMs with class) -->
                @if($qcm->statut === 'public' && $qcm->classe_id && $totalStudents > 0)
                    <div class="mb-6 relative z-10">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Progression classe</span>
                            <span class="text-[10px] font-black {{ $isFullyCompleted ? 'text-emerald-500' : 'text-slate-600' }}">{{ $completedStudents }}/{{ $totalStudents }} ({{ $completionRate }}%)</span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $isFullyCompleted ? 'bg-emerald-500' : 'bg-primary-500' }} rounded-full transition-all duration-500" style="width: {{ $completionRate }}%"></div>
                        </div>
                        @if($isFullyCompleted)
                            <p class="text-[9px] font-black text-emerald-500 mt-1 uppercase tracking-widest">Tous les étudiants ont terminé</p>
                        @endif
                    </div>
                @endif

                <!-- Title & Meta -->
                <div class="relative z-10 mb-6">
                    <h3 class="text-2xl font-black font-heading text-slate-900 tracking-tight leading-tight mb-3 group-hover:text-primary-600 transition-colors uppercase italic">{{ $qcm->titre }}</h3>
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center gap-2">
                            <svg class="size-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2" /></svg>
                            <span class="text-xs font-bold text-slate-400">{{ $qcm->duree_minutes }} min</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="size-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 11l3 3L22 4m-2 6v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" /></svg>
                            <span class="text-xs font-bold text-slate-400">Seuil: {{ $qcm->score_reussite }}/20</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="mt-auto pt-6 border-t border-slate-50 flex flex-col gap-4 relative z-10">
                    <!-- Status Toggle Buttons -->
                    <div class="flex gap-2">
                        @if($qcm->statut === 'brouillon')
                            <form action="{{ route('formateur.qcm.toggle', $qcm->id) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-full h-12 bg-emerald-50 text-emerald-600 rounded-2xl font-black text-[10px] uppercase tracking-[0.15em] hover:bg-emerald-500 hover:text-white transition-all flex items-center justify-center gap-2">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
                                    Publier
                                </button>
                            </form>
                        @elseif($qcm->statut === 'public')
                            <form action="{{ route('formateur.qcm.toggle', $qcm->id) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-full h-12 bg-amber-50 text-amber-600 rounded-2xl font-black text-[10px] uppercase tracking-[0.15em] hover:bg-amber-500 hover:text-white transition-all flex items-center justify-center gap-2">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12" /></svg>
                                    Dépublier
                                </button>
                            </form>
                            <form action="{{ route('formateur.qcm.close', $qcm->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" onclick="return confirm('Fermer ce QCM ? Les étudiants ne pourront plus y accéder.')" class="h-12 px-4 bg-slate-800 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.15em] hover:bg-slate-900 transition-all flex items-center justify-center">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                </button>
                            </form>
                        @endif
                        
                        @if($qcm->statut !== 'termine')
                            <a href="{{ route('formateur.qcm.edit', $qcm->id) }}"
                               class="size-12 rounded-2xl bg-primary-50 text-primary-500 hover:bg-primary-500 hover:text-white transition-all shadow-[0_8px_30px_-4px_rgba(0,0,0,0.04)] flex items-center justify-center"
                               title="Modifier le QCM">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </a>
                        @endif
                        <button onclick="if(confirm('Supprimer définitivement cet item de la bibliothèque ?')) { document.getElementById('delete-qcm-{{ $qcm->id }}').submit() }"
                                class="size-12 rounded-2xl bg-rose-50 text-rose-400 hover:bg-rose-500 hover:text-white transition-all shadow-[0_8px_30px_-4px_rgba(0,0,0,0.04)] flex items-center justify-center"
                                title="Supprimer le QCM">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                        <form id="delete-qcm-{{ $qcm->id }}" action="{{ route('formateur.qcm.destroy', $qcm->id) }}" method="POST" class="hidden">
                            @csrf @method('DELETE')
                        </form>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Créé {{ $qcm->created_at->format('d M Y') }}</span>
                        @if($qcm->questions_count)
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $qcm->questions_count }} questions</span>
                        @endif
                    </div>
                </div>

                <!-- Decorative Layer -->
                <div class="absolute -right-16 -bottom-16 size-48 bg-slate-50 rounded-full group-hover:bg-primary-50 transition-colors duration-1000"></div>
            </div>
        @endforeach

        <!-- Modern CTA Card -->
        <a href="{{ route('formateur.qcm.create') }}"
           class="group bg-primary-500 rounded-[2.5rem] p-8 flex flex-col items-center justify-center text-center shadow-2xl shadow-primary-500/30 hover:-translate-y-2 active:scale-[0.98] transition-all duration-500 min-h-[340px] relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-white/10 to-transparent"></div>
            <div class="relative z-10">
                <div class="size-20 rounded-[2rem] bg-white flex items-center justify-center text-primary-500 mb-8 shadow-2xl group-hover:scale-110 transition-transform">
                    <svg class="size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
                </div>
                <h3 class="text-2xl font-black font-heading text-white tracking-tight uppercase italic mb-2">Ingénierie</h3>
                <p class="text-primary-100/80 font-bold text-xs uppercase tracking-[0.2em]">Concevoir un nouveau QCM</p>
            </div>
            <div class="absolute -bottom-10 -right-10 size-40 bg-white/5 rounded-full blur-2xl"></div>
        </a>
    </div>
</div>
@endsection