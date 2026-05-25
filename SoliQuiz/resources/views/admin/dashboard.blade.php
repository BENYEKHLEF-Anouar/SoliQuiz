@extends('layouts.app')

@section('title', 'Administration - SoliQuiz')

@section('page-title', "Vue d'ensemble Système")

@section('content')
<div class="space-y-8 fade-in" x-data="{ showQuickActions: false }">
    <!-- Header -->
    <div class="relative z-30 mb-10">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-8 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-4 mb-3">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Espace Admin</span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-900">Dashboard</span>
                </div>
                <h3 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                    Supervision Globale
                </h3>
                <p class="mt-2 text-sm text-slate-500 max-w-xl">
                    Vue d'ensemble de la plateforme : utilisateurs, QCMs, cohortes et performances.
                </p>
            </div>
            <!-- <button @click="showQuickActions = !showQuickActions"
                    class="h-14 px-8 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-slate-900/20 hover:bg-primary-500 hover:-translate-y-1 transition-all flex items-center gap-3 shrink-0">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4"/></svg>
                Actions Rapides
            </button> -->
        </div>
    </div>


    <!-- Quick Actions Dropdown -->
    <div x-show="showQuickActions" 
         @click.away="showQuickActions = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xl"
         x-cloak>
        <a href="{{ route('admin.utilisateurs') }}" class="flex items-center gap-4 p-4 rounded-xl hover:bg-slate-50 transition-all group">
            <div class="size-12 bg-primary-100 rounded-xl flex items-center justify-center text-primary-600 group-hover:bg-primary-600 group-hover:text-white transition-all">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a7 7 0 0114 0v3H3v-3z"/></svg>
            </div>
            <div>
                <p class="font-bold text-slate-900">Nouvel Utilisateur</p>
                <p class="text-xs text-slate-500">Ajouter formateur ou étudiant</p>
            </div>
        </a>
        <a href="{{ route('admin.classes') }}" class="flex items-center gap-4 p-4 rounded-xl hover:bg-slate-50 transition-all group">
            <div class="size-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <div>
                <p class="font-bold text-slate-900">Nouvelle Classe</p>
                <p class="text-xs text-slate-500">Créer une cohorte</p>
            </div>
        </a>
        <a href="{{ route('admin.pedagogie') }}" class="flex items-center gap-4 p-4 rounded-xl hover:bg-slate-50 transition-all group">
            <div class="size-12 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-all">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            </div>
            <div>
                <p class="font-bold text-slate-900">Structure Pédagogique</p>
                <p class="text-xs text-slate-500">Gérer UA et compétences</p>
            </div>
        </a>
    </div>

    <!-- Enhanced KPI Grid with Trends -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Formateurs Card -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="size-9 bg-primary-100 rounded-lg flex items-center justify-center text-primary-600">
                        <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Formateurs</span>
                </div>
                <span class="flex items-center gap-1 text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">
                    <svg class="size-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    {{ $kpis['trend_users'] }}
                </span>
            </div>
            <div class="flex items-baseline gap-2 mb-2">
                <span class="text-2xl font-black text-slate-900 leading-none">{{ $kpis['nb_formateurs'] }}</span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Actifs</span>
            </div>
            <div class="h-8 flex items-end gap-0.5">
                @foreach([40, 55, 45, 60, 50, 65, 70] as $h)
                    <div class="flex-1 bg-primary-200 rounded-sm" style="height: {{ $h }}%"></div>
                @endforeach
            </div>
        </div>

        <!-- Apprenants Card -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="size-9 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600">
                        <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Apprenants</span>
                </div>
                <span class="flex items-center gap-1 text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">
                    <svg class="size-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    {{ $kpis['trend_users'] }}
                </span>
            </div>
            <div class="flex items-baseline gap-2 mb-2">
                <span class="text-2xl font-black text-slate-900 leading-none">{{ $kpis['nb_etudiants'] }}</span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Inscrits</span>
            </div>
            <div class="h-8 flex items-end gap-0.5">
                @foreach([30, 45, 40, 55, 65, 75, 85] as $h)
                    <div class="flex-1 bg-emerald-200 rounded-sm" style="height: {{ $h }}%"></div>
                @endforeach
            </div>
        </div>

        <!-- Classes Card -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="size-9 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600">
                        <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Classes</span>
                </div>
                <span class="text-[10px] font-black text-slate-400 bg-slate-100 px-2 py-1 rounded-lg">Stable</span>
            </div>
            <div class="flex items-baseline gap-2 mb-2">
                <span class="text-2xl font-black text-slate-900 leading-none">{{ $kpis['nb_classes'] }}</span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Actives</span>
            </div>
            <div class="flex items-center gap-2 text-[10px] text-slate-500 font-bold uppercase">
                <div class="flex -space-x-1">
                    <div class="size-4.5 rounded-full bg-slate-200 border-2 border-white"></div>
                    <div class="size-4.5 rounded-full bg-slate-300 border-2 border-white"></div>
                </div>
                <span>{{ $kpis['nb_formateurs'] ?? 0 }} experts</span>
            </div>
        </div>

        <!-- Performance Card -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="size-9 bg-rose-100 rounded-lg flex items-center justify-center text-rose-600">
                        <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Performance</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2 mb-3">
                <span class="text-3xl font-black text-slate-900">{{ $kpis['score_moyen'] }}</span>
                <span class="text-lg text-slate-400">/20</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2 mb-2">
                <div class="bg-rose-500 h-2 rounded-full" style="width: {{ ($kpis['score_moyen'] / 20) * 100 }}%"></div>
            </div>
            <p class="text-xs text-slate-500">{{ $kpis['nb_qcms_publie'] }} QCMs publiés</p>
        </div>
    </div>

    <!-- Main Dashboard Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - 2/3 width -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Classes Progress Chart Widget -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="font-bold text-slate-900">Suivi des Cohortes</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Taux de réussite et moyenne générale par classe</p>
                    </div>
                    <a href="{{ route('admin.resultats') }}" class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1 group/link">
                        Voir tout
                        <svg class="size-3 group-hover/link:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                
                <div class="relative w-full min-h-[280px] flex items-center justify-center">
                    @if(count($classes) > 0)
                        <canvas id="adminClassesChart"></canvas>
                    @else
                        <div class="text-center py-10">
                            <div class="size-12 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3 text-slate-400">
                                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <p class="text-xs text-slate-400 font-medium">Aucune donnée de cohorte</p>
                        </div>
                    @endif
                </div>

                @if(count($classes) > 0)
                    <div class="mt-6 flex items-center justify-center gap-6 text-[10px] font-bold uppercase tracking-wider border-t border-slate-100 pt-4">
                        <span class="flex items-center gap-2 text-indigo-500">
                            <span class="size-2.5 rounded-full bg-indigo-500 block"></span>
                            Moyenne / 20
                        </span>
                        <span class="flex items-center gap-2 text-emerald-500">
                            <span class="size-2.5 rounded-full bg-emerald-400 block"></span>
                            Réussite %
                        </span>
                    </div>
                @endif
            </div>

            <!-- Top QCMs Widget -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900">Top Évaluations</h3>
                        <p class="text-xs text-slate-500 mt-0.5">QCMs les plus passés ce mois</p>
                    </div>
                    <a href="{{ route('admin.qcms') }}" class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1 group/link">
                        Voir tout
                        <svg class="size-3 group-hover/link:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="divide-y divide-slate-100">
@forelse($topQcms as $index => $qcm)
                        <div class="p-4 hover:bg-slate-50 transition-all flex items-center justify-between group">
                            <div class="flex items-center gap-4">
                                <span class="size-8 bg-slate-100 rounded-lg flex items-center justify-center text-sm font-black text-slate-500">{{ $index + 1 }}</span>
                                <div>
                                    <p class="font-bold text-slate-900 text-sm group-hover:text-primary-600 transition-colors">{{ $qcm->titre }}</p>
                                    <p class="text-xs text-slate-500">Par {{ optional($qcm->formateur)->nom_complet ?? 'Inconnu' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <p class="text-lg font-black text-slate-900">{{ $qcm->tentatives_count ?? 0 }}</p>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-wider">passations</p>
                                </div>
                                <!-- <div class="size-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 group-hover:bg-primary-500 group-hover:text-white transition-all">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
                                </div> -->
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <div class="size-12 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <svg class="size-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <p class="text-sm text-slate-500">Aucun QCM detecte</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Activity Timeline -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-bold text-slate-900">Activité Récente</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Dernières actions sur la plateforme</p>
                    </div>
                </div>
                <div class="space-y-4">
@forelse($recentTentatives->take(5) as $t)
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <img class="size-10 rounded-xl border border-slate-200" src="https://ui-avatars.com/api/?name={{ urlencode(optional($t->etudiant)->nom_complet ?? 'Unknown') }}&background=f8fafc&color=64748b&bold=true" alt="">
                                <div class="w-px flex-1 bg-slate-200 my-2"></div>
                            </div>
                            <div class="flex-1 pb-8">
                                <div class="flex items-start justify-between mb-1">
                                    <div>
                                        <p class="font-black text-sm text-slate-900 uppercase">{{ optional($t->etudiant)->nom_complet ?? 'Anonyme' }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                                            {{ $t->statut === 'reussi' ? 'A validé' : ($t->statut === 'echoue' ? 'Échec sur' : 'En cours sur') }} 
                                            <span class="text-primary-600">{{ optional($t->qcm)->titre ?? 'QCM' }}</span>
                                        </p>
                                    </div>
                                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">{{ $t->created_at ? $t->created_at->diffForHumans() : 'Récemment' }}</span>
                                </div>
                                
                                @if($t->statut !== 'en_cours')
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider {{ $t->statut === 'reussi' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            @if($t->statut === 'reussi')
                                                <path d="M5 13l4 4L19 7"/>
                                            @else
                                                <path d="M6 18L18 6M6 6l12 12"/>
                                            @endif
                                        </svg>
                                        Performance: {{ $t->score_obtenu }}/20
                                    </div>
                                @else
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider bg-amber-50 text-amber-600 border border-amber-100">
                                        <span class="size-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                                        Session Active
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="size-12 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <svg class="size-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="text-sm text-slate-500">Aucune activité récente</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column - 1/3 width -->
        <div class="space-y-6">
            <!-- System Status Widget -->
            <div class="bg-slate-900 rounded-2xl border border-slate-800 shadow-lg p-5 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold">Santé Système</h3>
                    <span class="flex items-center gap-1.5 px-2 py-1 {{ $systemStatus['is_operational'] ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' }} rounded-lg text-xs font-bold">
                        <span class="size-1.5 {{ $systemStatus['is_operational'] ? 'bg-emerald-400' : 'bg-rose-400' }} rounded-full {{ $systemStatus['is_operational'] ? 'animate-pulse' : '' }}"></span>
                        {{ $systemStatus['is_operational'] ? 'Opérationnel' : 'Alerte' }}
                    </span>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-slate-800">
                        <span class="text-sm text-slate-400">Base de données</span>
                        <span class="text-sm font-bold {{ $systemStatus['db_color'] }}">{{ $systemStatus['db_status'] }}</span>
                    </div>
                    <!-- <div class="flex items-center justify-between py-2 border-b border-slate-800">
                        <span class="text-sm text-slate-400">File d'attente</span>
                        <span class="text-sm font-bold {{ $systemStatus['queue_count'] > 0 ? 'text-amber-400' : 'text-slate-300' }}">
                            {{ $systemStatus['queue_count'] > 0 ? $systemStatus['queue_count'] . ' tâches' : 'Vide' }}
                        </span>
                    </div> -->
                    <!-- <div class="flex items-center justify-between py-2">
                        <span class="text-sm text-slate-400">Dernière sauvegarde</span>
                        <span class="text-sm font-bold text-slate-300">{{ $systemStatus['last_backup'] }}</span>
                    </div> -->
                </div>
            </div>

            <!-- Top Performers Widget -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <h3 class="font-bold text-slate-900 mb-1">Top Performers</h3>
                <p class="text-xs text-slate-500 mb-4">Meilleurs scores cette semaine</p>
                <div class="space-y-4">
                    @forelse($topPerformers as $index => $student)
                        <div class="flex items-center gap-4 p-3 rounded-2xl hover:bg-slate-50 transition-colors group">
                            <div class="size-10 rounded-xl bg-slate-100 flex items-center justify-center text-xs font-black text-slate-500 group-hover:bg-primary-500 group-hover:text-white transition-all">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <p class="font-black text-sm text-slate-900 leading-tight">{{ $student->nom_complet }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $student->classe->nom ?? 'Indépendant' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="font-black text-emerald-600 tabular-nums">{{ round($student->moyenne_score, 1) }}</span>
                                <span class="text-[10px] text-slate-400 font-bold">/20</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <p class="text-xs text-slate-400">Données insuffisantes</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Stats Widget -->
            <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl shadow-lg p-5 text-white">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="size-5 text-primary-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    <span class="font-bold">Performance Globale</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white/10 rounded-xl p-3">
                        <p class="text-2xl font-black">{{ $kpis['taux_reussite'] }}%</p>
                        <p class="text-xs text-primary-200">Taux de réussite</p>
                    </div>
                    <div class="bg-white/10 rounded-xl p-3">
                        <p class="text-2xl font-black">{{ $recentTentatives->count() ?? 12 }}</p>
                        <p class="text-xs text-primary-200">Passages 24h</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access Modules -->
    <section class="mt-8">
        <h3 class="font-bold text-slate-900 mb-4">Accès Rapide Administration</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.qcms') }}" class="group flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-200 hover:border-primary-300 hover:shadow-premium transition-all">
                <div class="size-12 bg-primary-50 rounded-xl flex items-center justify-center text-primary-600 group-hover:bg-primary-500 group-hover:text-white transition-all">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-black text-slate-900 text-sm tracking-tight truncate">Banque de QCM</p>
                    <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wider">Supervision Globale</p>
                </div>
            </a>

            <a href="{{ route('admin.pedagogie') }}" class="group flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-200 hover:border-amber-300 hover:shadow-premium transition-all">
                <div class="size-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-all">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-black text-slate-900 text-sm tracking-tight truncate">Ingénierie Péd.</p>
                    <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wider">Catalogue & UA</p>
                </div>
            </a>

            <a href="{{ route('admin.classes') }}" class="group flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-200 hover:border-emerald-300 hover:shadow-premium transition-all">
                <div class="size-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-black text-slate-900 text-sm tracking-tight truncate">Cohort Management</p>
                    <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wider">Classes & Flux</p>
                </div>
            </a>

            <a href="{{ route('admin.utilisateurs') }}" class="group flex items-center gap-4 p-5 bg-white rounded-2xl border border-slate-200 hover:border-rose-300 hover:shadow-premium transition-all">
                <div class="size-12 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-all">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2 M9 7a4 4 0 110-8 4 4 0 010 8zm14 14v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-black text-slate-900 text-sm tracking-tight truncate">Directory</p>
                    <p class="text-[10px] text-slate-500 font-medium uppercase tracking-wider">Comptes & Rôles</p>
                </div>
            </a>
        </div>
    </section>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('adminClassesChart');
        if (!ctx) return;

        const classNames = {!! json_encode($classes->pluck('nom')) !!};
        const averages = {!! json_encode($classes->pluck('moyenne')) !!};
        const successRates = {!! json_encode($classes->pluck('taux_reussite')) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: classNames,
                datasets: [
                    {
                        label: 'Moyenne (/20)',
                        data: averages,
                        backgroundColor: 'rgba(99, 102, 241, 0.85)',
                        borderColor: '#6366f1',
                        borderWidth: 2,
                        borderRadius: 6,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Taux de réussite (%)',
                        data: successRates,
                        backgroundColor: 'rgba(52, 211, 153, 0.85)',
                        borderColor: '#34d399',
                        borderWidth: 2,
                        borderRadius: 6,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#0f172a',
                        titleColor: '#fff',
                        bodyColor: '#cbd5e1',
                        borderWidth: 1,
                        borderColor: '#334155',
                        padding: 10,
                        bodyFont: {
                            family: 'Inter, system-ui, sans-serif',
                            size: 11
                        },
                        titleFont: {
                            family: 'Inter, system-ui, sans-serif',
                            size: 12,
                            weight: 'bold'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'Inter, system-ui, sans-serif',
                                size: 10,
                                weight: 'bold'
                            },
                            color: '#64748b'
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        max: 20,
                        min: 0,
                        grid: {
                            color: '#f1f5f9'
                        },
                        ticks: {
                            font: {
                                family: 'Inter, system-ui, sans-serif',
                                size: 9
                            },
                            color: '#64748b'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        max: 100,
                        min: 0,
                        grid: {
                            drawOnChartArea: false
                        },
                        ticks: {
                            font: {
                                family: 'Inter, system-ui, sans-serif',
                                size: 9
                            },
                            color: '#64748b'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection