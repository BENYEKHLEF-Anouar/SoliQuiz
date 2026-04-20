@extends('layouts.app')

@section('title', 'Tableau de Bord - Apprenant')

@section('content')
<div class="space-y-10 reveal active">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">
                <a href="#" class="hover:text-primary-500 transition-colors">Plateforme</a>
                <span class="size-1 rounded-full bg-slate-300"></span>
                <span class="text-slate-600">Apprenant</span>
            </nav>
            <h1 class="text-4xl lg:text-5xl font-heading font-black text-slate-900 tracking-tight leading-none mb-4">
                Bonjour, <span class="text-primary-500">{{ Auth::user()->prenom }}</span> 👋
            </h1>
            <p class="text-slate-500 font-medium max-w-lg">
                Votre progression est excellente. Voici un aperçu de vos performances et de vos derniers résultats pédagogiques.
            </p>
        </div>
        
        <div class="flex items-center gap-4 bg-white p-2 rounded-[20px] border border-slate-100 shadow-sm pr-6">
            <div class="size-14 bg-primary-500 rounded-[16px] flex items-center justify-center shadow-lg shadow-primary-500/20">
                <svg class="text-white size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <div>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-0.5">Score Moyen</span>
                <span class="text-2xl font-black text-slate-900 leading-none">{{ $metrics['score_moyen'] }}%</span>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm group hover:shadow-premium transition-all relative overflow-hidden">
            <div class="absolute -right-4 -top-4 size-24 bg-slate-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-6 border-l-2 border-primary-500 pl-3">Tests Effectués</h3>
                <div class="flex items-baseline gap-2">
                    <span class="text-5xl font-heading font-black text-slate-900">{{ $metrics['nb_tentatives'] }}</span>
                    <span class="text-sm font-bold text-slate-400">évaluations</span>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm group hover:shadow-premium transition-all relative overflow-hidden">
            <div class="absolute -right-4 -top-4 size-24 bg-emerald-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-6 border-l-2 border-emerald-500 pl-3">Taux de Réussite</h3>
                <div class="flex items-baseline gap-2">
                    <span class="text-5xl font-heading font-black text-emerald-600">{{ $metrics['taux_reussite'] }}%</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm group hover:shadow-premium transition-all relative overflow-hidden">
            <div class="absolute -right-4 -top-4 size-24 bg-primary-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-6 border-l-2 border-primary-500 pl-3">Meilleur Score</h3>
                <div class="flex items-baseline gap-2">
                    <span class="text-5xl font-heading font-black text-slate-900">{{ $metrics['meilleur_score'] }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <section>
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-heading font-black text-slate-900 tracking-tight">Activité Récente</h2>
            <a href="{{ route('student.bibliotheque') }}" class="text-sm font-bold text-primary-600 hover:text-primary-700 transition-colors flex items-center gap-2">
                Voir tout le catalogue
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($historique as $tentative)
                @php $isSuccess = $tentative->statut === 'reussi'; @endphp
                <div class="bg-white rounded-[32px] border border-slate-100 p-8 shadow-sm hover:shadow-premium transition-all group flex flex-col">
                    <div class="flex justify-between items-start mb-8">
                        <div class="inline-flex py-1.5 px-3.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $isSuccess ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                            {{ $isSuccess ? 'Validé' : 'Échec' }}
                        </div>
                        <div class="size-16 rounded-[22px] bg-slate-50 flex items-center justify-center group-hover:bg-primary-500 transition-colors duration-500 group-hover:rotate-6">
                            <span class="text-xl font-black {{ $isSuccess ? 'text-emerald-600' : 'text-rose-600' }} group-hover:text-white">{{ $tentative->score_obtenu }}%</span>
                        </div>
                    </div>

                    <h3 class="text-xl font-heading font-black text-slate-900 tracking-tight leading-tight mb-2">
                        {{ $tentative->qcm->titre }}
                    </h3>
                    <p class="text-sm font-medium text-slate-400 mb-8 line-clamp-2">
                        {{ $tentative->qcm->uniteApprentissage ? $tentative->qcm->uniteApprentissage->nom : 'Module d\'évaluation transverse' }}
                    </p>

                    <div class="mt-auto">
                        <a href="{{ route('student.resultats', ['id' => $tentative->qcm_id]) }}"
                            class="inline-flex w-full justify-center items-center gap-3 px-6 py-4 bg-slate-900 border border-transparent text-white font-bold rounded-2xl hover:bg-primary-500 hover:shadow-xl hover:shadow-primary-500/25 transition-all text-sm group/btn active:scale-95">
                            Voir le détail
                            <svg class="size-4 transition-transform group-hover/btn:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white/50 backdrop-blur-sm border-2 border-dashed border-slate-200 p-12 rounded-[32px] text-center">
                    <div class="size-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="size-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 mb-2">Aucune activité</h4>
                    <p class="text-slate-500 font-medium">Commencez votre premier test pour voir vos statistiques ici.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection