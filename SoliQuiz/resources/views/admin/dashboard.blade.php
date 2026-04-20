@extends('layouts.app')

@section('title', 'Administration - SoliQuiz')

@section('content')
<div class="space-y-10 reveal active">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">
                <span>Supervision Centrale</span>
                <span class="size-1 rounded-full bg-slate-300"></span>
                <span class="text-slate-600">Console Admin</span>
            </nav>
            <h1 class="text-4xl lg:text-5xl font-heading font-black text-slate-900 tracking-tight leading-none mb-4">
                Tableau de <span class="text-primary-500">Bord</span>
            </h1>
            <p class="text-slate-500 font-medium max-w-xl">
                Surveillance globale de l'engagement et des performances pédagogiques sur l'ensemble des centres Solicode.
            </p>
        </div>
        
        <div class="bg-white p-2 rounded-[22px] border border-slate-100 shadow-sm flex items-center gap-4 pr-6">
            <div class="size-14 bg-slate-900 rounded-[16px] flex items-center justify-center shadow-lg shadow-slate-900/10">
                <svg class="text-white size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
            </div>
            <div>
                <span class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 block mb-0.5">Moyenne Réseau</span>
                <span class="text-2xl font-black text-slate-900 leading-none">{{ $kpis['score_moyen'] }}%</span>
            </div>
        </div>
    </div>

    <!-- KPI Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm group hover:shadow-premium transition-all">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 border-l-2 border-primary-500 pl-3">Formateurs</h3>
            <div class="flex items-baseline gap-2">
                <span class="text-5xl font-black text-slate-900 font-heading">{{ $kpis['nb_formateurs'] }}</span>
                <span class="text-xs font-bold text-slate-400 uppercase">comptes</span>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm group hover:shadow-premium transition-all">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 border-l-2 border-primary-500 pl-3">Apprenants</h3>
            <div class="flex items-baseline gap-2">
                <span class="text-5xl font-black text-slate-900 font-heading">{{ $kpis['nb_etudiants'] }}</span>
                <span class="text-xs font-bold text-slate-400 uppercase">actifs</span>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm group hover:shadow-premium transition-all">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 border-l-2 border-primary-500 pl-3">Classes</h3>
            <div class="flex items-baseline gap-2">
                <span class="text-5xl font-black text-slate-900 font-heading">{{ $kpis['nb_classes'] }}</span>
                <span class="text-xs font-bold text-slate-400 uppercase">ouvertes</span>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm group hover:shadow-premium transition-all">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 border-l-2 border-emerald-500 pl-3">QCM</h3>
            <div class="flex items-baseline gap-2">
                <span class="text-5xl font-black text-emerald-600 font-heading">{{ $kpis['nb_qcms_publie'] }}</span>
                <span class="text-xs font-bold text-emerald-500 uppercase tracking-widest">publiés</span>
            </div>
        </div>
    </div>

    <!-- Lists Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mt-12">
        <!-- Top QCMs -->
        <div class="bg-white p-10 rounded-[40px] border border-slate-100 shadow-sm">
            <h2 class="text-2xl font-heading font-black text-slate-900 tracking-tight mb-8">Top Évaluations</h2>
            <div class="space-y-4">
                @forelse($topQcms as $qcm)
                    <div class="p-6 rounded-[24px] border border-slate-50 bg-slate-50/50 hover:bg-white hover:shadow-lg transition-all group flex justify-between items-center">
                        <div class="flex flex-col">
                            <span class="font-black text-slate-900 text-lg group-hover:text-primary-600 transition-colors">{{ $qcm->titre }}</span>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1 italic">Par {{ $qcm->formateur->nom_complet }}</span>
                        </div>
                        <div class="text-right flex items-center gap-4">
                            <div>
                                <span class="block text-2xl font-black font-heading text-slate-900 leading-none">{{ $qcm->tentatives_count }}</span>
                                <span class="text-[9px] uppercase font-black text-slate-400 tracking-widest">Inscriptions</span>
                            </div>
                            <div class="size-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-primary-500">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-400 font-medium italic text-center py-10">Aucun QCM détecté.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white p-10 rounded-[40px] border border-slate-100 shadow-sm">
            <h2 class="text-2xl font-heading font-black text-slate-900 tracking-tight mb-8">Activités Récentes</h2>
            <div class="space-y-4">
                @forelse($recentTentatives as $t)
                    <div class="p-6 rounded-[24px] border border-slate-50 bg-slate-50/50 hover:bg-white hover:shadow-lg transition-all flex justify-between items-center group">
                        <div class="flex items-center gap-4">
                            <img class="size-12 rounded-xl" src="https://ui-avatars.com/api/?name={{ urlencode($t->etudiant->nom_complet) }}&background=f1f5f9&color=64748b&bold=true" alt="">
                            <div class="flex flex-col">
                                <span class="font-black text-slate-900">{{ $t->etudiant->nom_complet }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest truncate max-w-[150px]">{{ $t->qcm->titre }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                             @if($t->statut === 'reussi')
                                <div class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-widest mb-1 shadow-sm">{{ $t->score_obtenu }}% • Succès</div>
                            @elseif($t->statut === 'echoue')
                                <div class="px-3 py-1 rounded-full bg-rose-50 text-rose-600 text-[9px] font-black uppercase tracking-widest mb-1 shadow-sm">{{ $t->score_obtenu }}% • Échec</div>
                            @else
                                <div class="px-3 py-1 rounded-full bg-slate-100 text-slate-400 text-[9px] font-black uppercase tracking-widest mb-1 shadow-sm">En Cours</div>
                            @endif
                            <span class="text-[9px] font-bold text-slate-300">{{ $t->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-400 font-medium italic text-center py-10">Aucun passage récent.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Management Modules -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-12 pb-12">
        <a href="{{ route('admin.pedagogie') }}" class="group relative bg-slate-900 p-10 rounded-[40px] shadow-2xl overflow-hidden hover:-translate-y-1 transition-all duration-500">
            <div class="absolute -right-20 -top-20 size-60 bg-white/5 rounded-full group-hover:scale-110 transition-transform duration-700"></div>
            <div class="relative z-10">
                <div class="size-16 bg-primary-500 rounded-2xl flex items-center justify-center text-white mb-8 group-hover:rotate-12 transition-transform">
                    <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 2L2 7l10 5 10-5-10-5z" /><path d="M2 17l10 5 10-5" /><path d="M2 12l10 5 10-5" /></svg>
                </div>
                <h3 class="text-3xl font-black font-heading text-white tracking-tight mb-2">Ingénierie Pédagogique</h3>
                <p class="text-slate-400 font-medium text-lg leading-relaxed max-w-sm mb-10">Pilotez le catalogue de compétences, les UA et structurez vos Séances.</p>
                <div class="flex items-center gap-3 text-xs font-black text-primary-400 uppercase tracking-widest">
                    Administrer le cœur <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.classes') }}" class="group relative bg-white p-10 rounded-[40px] shadow-premium border border-slate-100 overflow-hidden hover:-translate-y-1 transition-all duration-500">
             <div class="absolute -right-20 -top-20 size-60 bg-primary-50 rounded-full group-hover:scale-110 transition-transform duration-700"></div>
             <div class="relative z-10">
                <div class="size-16 bg-slate-100 text-slate-900 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-primary-500 group-hover:text-white transition-all duration-500 group-hover:rotate-12">
                    <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M23 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /></svg>
                </div>
                <h3 class="text-3xl font-black font-heading text-slate-900 tracking-tight mb-2">Gestion des Cohortes</h3>
                <p class="text-slate-500 font-medium text-lg leading-relaxed max-w-sm mb-10">Organisez les promotions, assignez les formateurs et gérez les flux d'apprenants.</p>
                <div class="flex items-center gap-3 text-xs font-black text-primary-500 uppercase tracking-widest">
                    Gérer les équipes <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                </div>
            </div>
        </a>
    </section>
</div>
@endsection