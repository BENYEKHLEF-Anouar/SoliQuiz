@extends('layouts.app')

@section('title', 'Administration - SoliQuiz')

@section('page-title', "Vue d'ensemble Système")

@section('content')
<div class="space-y-8 fade-in" x-data="{ showQuickActions: false }">
    <!-- Quick Actions Control Bar -->
    <div class="flex items-center justify-between pb-6 border-b border-slate-100">
        <div>
            <p class="text-label mb-1">Supervision Globale</p>
            <h3 class="text-xl font-bold text-slate-900 tracking-tight italic uppercase">Mesure de Performance</h3>
        </div>
        
        <button @click="showQuickActions = !showQuickActions" 
                class="btn-premium px-6 py-3 bg-slate-900 text-white text-[11px] uppercase tracking-widest">
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4"/></svg>
            Actions Rapides
        </button>
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
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="size-10 bg-primary-100 rounded-lg flex items-center justify-center text-primary-600">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Formateurs</span>
                </div>
                <span class="flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">
                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    {{ $kpis['trend_users'] }}
                </span>
            </div>
            <div class="flex items-baseline gap-2 mb-2">
                <span class="text-3xl font-black text-slate-900">{{ $kpis['nb_formateurs'] }}</span>
                <span class="text-xs text-slate-400">actifs</span>
            </div>
            <div class="h-10 flex items-end gap-0.5">
                @foreach([40, 55, 45, 60, 50, 65, 70] as $h)
                    <div class="flex-1 bg-primary-200 rounded-t" style="height: {{ $h }}%"></div>
                @endforeach
            </div>
        </div>

        <!-- Apprenants Card -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="size-10 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Apprenants</span>
                </div>
                <span class="flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">
                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    {{ $kpis['trend_users'] }}
                </span>
            </div>
            <div class="flex items-baseline gap-2 mb-2">
                <span class="text-3xl font-black text-slate-900">{{ $kpis['nb_etudiants'] }}</span>
                <span class="text-xs text-slate-400">inscrits</span>
            </div>
            <div class="h-10 flex items-end gap-0.5">
                @foreach([30, 45, 40, 55, 65, 75, 85] as $h)
                    <div class="flex-1 bg-emerald-200 rounded-t" style="height: {{ $h }}%"></div>
                @endforeach
            </div>
        </div>

        <!-- Classes Card -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="size-10 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Classes</span>
                </div>
                <span class="text-xs font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded-lg">Stable</span>
            </div>
            <div class="flex items-baseline gap-2 mb-2">
                <span class="text-3xl font-black text-slate-900">{{ $kpis['nb_classes'] }}</span>
                <span class="text-xs text-slate-400">actives</span>
            </div>
            <div class="flex items-center gap-2 text-xs text-slate-500">
                <div class="flex -space-x-1">
                    <div class="size-5 rounded-full bg-slate-300 border border-white"></div>
                    <div class="size-5 rounded-full bg-slate-400 border border-white"></div>
                    <div class="size-5 rounded-full bg-slate-500 border border-white"></div>
                </div>
                <span>{{ $kpis['nb_formateurs'] ?? 0 }} formateurs assignés</span>
            </div>
        </div>

        <!-- Performance Card -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="size-10 bg-rose-100 rounded-lg flex items-center justify-center text-rose-600">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Moyenne Réseau</span>
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
                                    <p class="text-xs text-slate-500">Par {{ $qcm->formateur->nom_complet }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <p class="text-lg font-black text-slate-900">{{ $qcm->tentatives_count }}</p>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-wider">passations</p>
                                </div>
                                <div class="size-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 group-hover:bg-primary-500 group-hover:text-white transition-all">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <div class="size-12 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <svg class="size-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <p class="text-sm text-slate-500">Aucun QCM détecté</p>
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
                                <img class="size-10 rounded-xl border border-slate-200" src="https://ui-avatars.com/api/?name={{ urlencode($t->etudiant->nom_complet) }}&background=f8fafc&color=64748b&bold=true" alt="">
                                <div class="w-px flex-1 bg-slate-200 my-2"></div>
                            </div>
                            <div class="flex-1 pb-4">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="font-bold text-sm text-slate-900">{{ $t->etudiant->nom_complet }}</p>
                                        <p class="text-xs text-slate-500">{{ $t->statut === 'reussi' ? 'A réussi' : ($t->statut === 'echoue' ? 'A échoué' : 'A commencé') }} <span class="font-medium text-slate-700">{{ $t->qcm->titre }}</span></p>
                                    </div>
                                    <span class="text-[10px] text-slate-400">{{ $t->created_at ? $t->created_at->diffForHumans() : 'Date inconnue' }}</span>
                                </div>
                                @if($t->statut !== 'en_cours')
                                    <div class="mt-2 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold {{ $t->statut === 'reussi' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            @if($t->statut === 'reussi')
                                                <path d="M5 13l4 4L19 7"/>
                                            @else
                                                <path d="M6 18L18 6M6 6l12 12"/>
                                            @endif
                                        </svg>
                                        {{ $t->score_obtenu }}/20
                                    </div>
                                @else
                                    <span class="mt-2 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-100 text-amber-700">
                                        <span class="size-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                                        En cours
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
                    <span class="flex items-center gap-1.5 px-2 py-1 bg-emerald-500/20 text-emerald-400 rounded-lg text-xs font-bold">
                        <span class="size-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                        Opérationnel
                    </span>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-slate-800">
                        <span class="text-sm text-slate-400">Base de données</span>
                        <span class="text-sm font-bold text-emerald-400">Connectée</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-slate-800">
                        <span class="text-sm text-slate-400">File d'attente</span>
                        <span class="text-sm font-bold text-emerald-400">Vide</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-sm text-slate-400">Dernière sauvegarde</span>
                        <span class="text-sm font-bold text-slate-300">Il y a 2h</span>
                    </div>
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
                            <p class="text-xs text-slate-400 italic">Données insuffisantes</p>
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
@endsection