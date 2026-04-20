@extends('layouts.app')

@section('title', 'Bibliothèque de Contenus - SoliQuiz')

@section('content')
<div class="reveal active">
    <!-- Header Hero -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 mb-12">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">
                <span>Espace Formateur</span>
                <span class="size-1 rounded-full bg-slate-300"></span>
                <span class="text-slate-600">Patrimoine Numérique</span>
            </nav>
            <h1 class="text-4xl lg:text-5xl font-heading font-black text-slate-900 tracking-tight leading-none mb-4">
                Content <span class="text-primary-500">Repository</span>
            </h1>
            <p class="text-slate-500 font-medium max-w-xl leading-relaxed">
                Centralisez vos évaluations interactives. Surveillez le statut de publication et analysez l'engagement des apprenants sur l'ensemble du catalogue.
            </p>
        </div>
        
        <div class="flex gap-4">
            <div class="glass bg-white/50 px-8 py-5 rounded-[24px] border border-slate-100 flex flex-col items-end">
                <span class="text-[9px] font-black tracking-[0.2em] text-slate-400 uppercase mb-1">Passages Cumulés</span>
                <span class="text-3xl font-black text-slate-900 font-heading leading-none">{{ $qcms->sum('tentatives_count') }}</span>
            </div>
            
            <a href="{{ route('formateur.qcm.create') }}"
                class="group btn-premium px-8 py-5 bg-slate-900 text-white rounded-[24px] font-black text-xs uppercase tracking-[0.2em] hover:bg-primary-500 shadow-xl shadow-slate-900/10 active:scale-95 transition-all flex items-center justify-center gap-3">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
                Générer un QCM
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
            <div class="group bg-white rounded-[40px] p-8 border border-slate-100 shadow-sm hover:shadow-premium transition-all flex flex-col relative overflow-hidden">
                <!-- Status & Stats -->
                <div class="flex justify-between items-start mb-10 relative z-10">
                    @if($qcm->est_publie)
                        <div class="flex flex-col gap-1">
                            <span class="inline-flex py-1 px-3 rounded-lg bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-widest border border-emerald-100">Live & Actif</span>
                            <span class="text-[8px] font-bold text-slate-300 uppercase ps-1">Visibilité: Centre entier</span>
                        </div>
                    @else
                        <span class="inline-flex py-1 px-3 rounded-lg bg-slate-100 text-slate-400 text-[9px] font-black uppercase tracking-widest border border-slate-200">Mode Archive / Brouillon</span>
                    @endif

                    <div class="size-12 bg-slate-50 rounded-2xl flex flex-col items-center justify-center border border-slate-100 group-hover:bg-primary-500 group-hover:text-white transition-colors">
                        <span class="text-xs font-black leading-none">{{ $qcm->tentatives_count }}</span>
                        <span class="text-[7px] font-black uppercase tracking-wide opacity-60">Hits</span>
                    </div>
                </div>

                <!-- Title & Meta -->
                <div class="relative z-10 mb-8">
                    <h3 class="text-2xl font-black font-heading text-slate-900 tracking-tight leading-tight mb-3 group-hover:text-primary-600 transition-colors uppercase italic">{{ $qcm->titre }}</h3>
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center gap-2">
                            <svg class="size-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2" /></svg>
                            <span class="text-xs font-bold text-slate-400">{{ $qcm->duree_minutes }} min</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="size-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 11l3 3L22 4m-2 6v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" /></svg>
                            <span class="text-xs font-bold text-slate-400">Target: {{ $qcm->score_reussite }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between relative z-10">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Date de création</span>
                        <span class="text-[10px] font-bold text-slate-500">{{ $qcm->created_at->format('d M, Y') }}</span>
                    </div>
                    
                    <div class="flex gap-2">
                        <button onclick="if(confirm('Supprimer définitivement cet item de la bibliothèque ?')) { document.getElementById('delete-qcm-{{ $qcm->id }}').submit() }"
                                class="size-11 rounded-2xl bg-rose-50 text-rose-400 hover:bg-rose-500 hover:text-white transition-all shadow-sm flex items-center justify-center">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                        <form id="delete-qcm-{{ $qcm->id }}" action="{{ route('formateur.qcm.destroy', $qcm->id) }}" method="POST" class="hidden">
                            @csrf @method('DELETE')
                        </form>
                    </div>
                </div>

                <!-- Decorative Layer -->
                <div class="absolute -right-16 -bottom-16 size-48 bg-slate-50 rounded-full group-hover:bg-primary-50 transition-colors duration-1000"></div>
            </div>
        @endforeach

        <!-- Modern CTA Card -->
        <a href="{{ route('formateur.qcm.create') }}"
           class="group bg-primary-500 rounded-[40px] p-8 flex flex-col items-center justify-center text-center shadow-xl shadow-primary-500/20 hover:-translate-y-2 active:scale-95 transition-all duration-500 min-h-[340px] relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-white/10 to-transparent"></div>
            <div class="relative z-10">
                <div class="size-20 rounded-[28px] bg-white flex items-center justify-center text-primary-500 mb-8 shadow-2xl group-hover:scale-110 transition-transform">
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