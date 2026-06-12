@extends('layouts.app')

@section('title', 'Tableau de Bord - Apprenant')

@section('page-title', 'Espace Personnel Apprenant')

@section('content')
@php
    $allPersonalTentatives = Auth::user()->tentatives()
        ->whereNotNull('score_obtenu')
        ->with('qcm.uniteApprentissage')
        ->orderBy('date_debut', 'desc')
        ->limit(3)
        ->get()
        ->reverse()
        ->values()
        ->map(function($t) {
            return [
                'id' => $t->id,
                'qcm_titre' => $t->qcm->titre,
                'ua_nom' => $t->qcm->uniteApprentissage?->nom ?? 'Indépendant',
                'score' => $t->score_obtenu,
                'date' => $t->date_debut?->format('d/m/Y'),
            ];
        });
@endphp
<div class="space-y-8 fade-in">
    <!-- Header -->
    <div class="relative z-30 mb-10">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-8 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-4 mb-3">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Espace Apprenant</span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-900">Dashboard</span>
                    @if($formateur)
                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-primary-600">Formateur : {{ $formateur->nom_complet }}</span>
                    @endif
                </div>
                <h3 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                    Bon retour, {{ Auth::user()->prenom }}
                </h3>
                <p class="mt-2 text-sm text-slate-500 max-w-xl">
                    Suivez vos performances, accédez à vos QCMs et progressez à votre rythme.
                </p>
            </div>
            <a href="{{ route('etudiant.bibliotheque') }}"
               class="h-14 px-8 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-slate-900/20 hover:bg-primary-500 hover:-translate-y-1 transition-all flex items-center gap-3 shrink-0">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                Mes QCMs
            </a>
        </div>
    </div>

    <!-- Enhanced Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Tests Completed -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-2 mb-3">
                <div class="size-9 bg-primary-100 rounded-lg flex items-center justify-center text-primary-600">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tests complétés</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-black text-slate-900">{{ $metrics['nb_tentatives'] }}</span>
                <span class="text-xs text-slate-400">évaluations</span>
            </div>
            <div class="mt-3 h-10 flex items-end gap-1">
                @php $maxScore = count($lastScores) > 0 ? max(max($lastScores), 1) : 20; @endphp
                @forelse($lastScores as $score)
                    <div class="flex-1 bg-primary-100 border-x border-white rounded-t-sm hover:bg-primary-500 transition-colors group/bar relative" style="height: {{ ($score / $maxScore) * 100 }}%">
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 bg-slate-900 text-white text-[9px] font-black px-1.5 py-0.5 rounded opacity-0 group-hover/bar:opacity-100 transition-opacity whitespace-nowrap z-10">
                            {{ $score }}
                        </div>
                    </div>
                @empty
                    @foreach([20, 35, 45, 60, 55, 70, 85] as $h)
                        <div class="flex-1 bg-slate-50 rounded-t" style="height: {{ $h }}%"></div>
                    @endforeach
                @endforelse
            </div>
        </div>

        <!-- Success Rate -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-2 mb-3">
                <div class="size-9 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Taux de réussite</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-black text-emerald-600">{{ $metrics['taux_reussite'] }}%</span>
            </div>
            <div class="mt-3 w-full bg-slate-100 rounded-full h-2">
                <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $metrics['taux_reussite'] }}%"></div>
            </div>
            <p class="mt-2 text-xs text-slate-500">{{ $metrics['nb_reussies'] }} réussites sur {{ $metrics['nb_tentatives'] }}</p>
        </div>

        <!-- Best Score -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-2 mb-3">
                <div class="size-9 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Meilleur score</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-black text-slate-900">{{ $metrics['meilleur_score'] }}</span>
                <span class="text-sm text-slate-400">/20</span>
            </div>
            @if($metrics['meilleur_score'] >= 16)
                <span class="mt-2 inline-flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">
                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
                    Excellent
                </span>
            @endif
        </div>

        <!-- Average Score -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-2 mb-3">
                <div class="size-9 bg-rose-100 rounded-lg flex items-center justify-center text-rose-600">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Score moyen</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-black text-slate-900">{{ $metrics['score_moyen'] }}</span>
                <span class="text-sm text-slate-400">/20</span>
            </div>
            @php
                $comparison = $metrics['score_moyen'] - 12;
            @endphp
            @if($comparison > 0)
                <span class="mt-2 inline-flex items-center gap-1 text-xs font-bold text-emerald-600">
                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    +{{ number_format($comparison, 1) }} vs moyenne
                </span>
            @else
                <span class="mt-2 inline-flex items-center gap-1 text-xs font-bold text-amber-600">
                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    {{ number_format($comparison, 1) }} vs moyenne
                </span>
            @endif
        </div>
    </div>

    <!-- Main Dashboard Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <!-- Courbe de Progression Personnel -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900">Courbe de Progression</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Évolution de vos notes au fil des évaluations complétées</p>
                    </div>
                    <a href="{{ route('etudiant.progression') }}" class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                        Détails
                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                
                @if(count($allPersonalTentatives) > 0)
                    <div class="relative w-full h-[280px] mt-6">
                        <canvas id="studentPersonalProgressChart"></canvas>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="size-16 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center text-slate-300 shadow-sm mb-4">
                            <svg class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <h4 class="text-xs font-black text-slate-700 uppercase tracking-widest">Aucune donnée de progression</h4>
                        <p class="text-xs text-slate-400 max-w-xs mt-2 font-medium">Complétez vos premières évaluations pour voir votre courbe de progression personnelle s'afficher ici.</p>
                    </div>
                @endif
            </div>

            <!-- Activité Récente -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900">Activité Récente</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Vos 3 dernières évaluations</p>
                    </div>
                    <a href="{{ route('etudiant.progression') }}" class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                        Voir l'historique
                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($historique->take(3) as $tentative)
                        @php
                            $isSuccess = $tentative->statut === 'reussi';
                            $isEnCours = $tentative->statut === 'en_cours';
                        @endphp
                        <div class="p-4 hover:bg-slate-50 transition-all flex items-center justify-between group">
                            <div class="flex items-center gap-4">
                                <div class="size-10 rounded-xl flex items-center justify-center {{ $isEnCours ? 'bg-primary-100 text-primary-600 animate-pulse' : ($isSuccess ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600') }}">
                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        @if($isEnCours)
                                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @elseif($isSuccess)
                                            <path d="M5 13l4 4L19 7"/>
                                        @else
                                            <path d="M6 18L18 6M6 6l12 12"/>
                                        @endif
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 text-sm group-hover:text-primary-600 transition-colors truncate max-w-[220px] sm:max-w-xs">{{ $tentative->qcm->titre }}</p>
                                    <p class="text-xs text-slate-500 truncate max-w-[220px] sm:max-w-xs">{{ $tentative->qcm->uniteApprentissage ? $tentative->qcm->uniteApprentissage->nom : 'Évaluation transverse' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    @if($isEnCours)
                                        <p class="text-xs font-black text-primary-600 uppercase tracking-widest">En cours</p>
                                    @else
                                        <p class="text-lg font-black {{ $isSuccess ? 'text-emerald-600' : 'text-rose-600' }}">{{ $tentative->score_obtenu }}/20</p>
                                    @endif
                                    <p class="text-[10px] text-slate-400">{{ $tentative->date_fin ? $tentative->date_fin->diffForHumans() : ($tentative->date_debut ? $tentative->date_debut->diffForHumans() : 'Récemment') }}</p>
                                </div>
                                <a href="{{ $isEnCours ? route('etudiant.passation', $tentative->qcm_id) : route('etudiant.resultats', ['id' => $tentative->qcm_id]) }}" class="size-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 group-hover:bg-primary-500 group-hover:text-white transition-all">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <div class="size-12 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <svg class="size-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <p class="text-sm text-slate-500">Aucune activité récente</p>
                             <a href="{{ route('etudiant.bibliotheque') }}" class="mt-2 text-xs font-bold text-primary-600 hover:text-primary-700">Commencer un QCM</a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Progression par Objectif d'Apprentissage -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900">Progression par UA</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Votre score moyen par unité d'apprentissage</p>
                    </div>
                    <a href="{{ route('etudiant.progression') }}" class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                        Toutes les UA
                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="p-5">
                    @forelse($progressByUa->take(3) as $data)
                        <div class="mb-4 last:mb-0">
                            <div class="flex items-center justify-between text-xs font-bold text-slate-700 mb-1.5">
                                <div class="truncate max-w-[70%] flex flex-col">
                                    <span class="text-slate-900 font-bold text-sm">{{ $data['ua_nom'] }}</span>
                                    @if($data['session_nom'])
                                        <span class="text-[10px] text-slate-400 font-medium">Session : {{ $data['session_nom'] }}</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-indigo-600 font-black">{{ $data['score_moyen'] }}/20</span>
                                    <span class="text-slate-400">({{ $data['total_tentatives'] }} tentative(s))</span>
                                </div>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all duration-500 {{ $data['score_moyen'] >= 14 ? 'bg-emerald-500' : ($data['score_moyen'] >= 10 ? 'bg-amber-500' : 'bg-rose-500') }}"
                                     style="width: {{ ($data['score_moyen'] / 20) * 100 }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-400 text-xs font-bold">
                            Aucune donnée de progression disponible. Complétez vos QCM pour voir vos scores par objectif.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column - Next Steps & Achievements -->
        <div class="space-y-6">
            <!-- Active Sessions -->
            @if($activeSessions->count() > 0)
                <div class="bg-white rounded-xl border-2 border-primary-500 shadow-xl shadow-primary-500/5 p-5 relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 size-20 bg-primary-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="flex items-center gap-3 mb-4 relative z-10">
                        <div class="size-8 bg-primary-500 rounded-lg flex items-center justify-center text-white animate-pulse">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <span class="font-black text-slate-900 text-sm uppercase tracking-widest">En cours</span>
                    </div>
                    <div class="space-y-3 relative z-10">
                        @foreach($activeSessions as $active)
                            <a href="{{ route('etudiant.passation', $active->qcm_id) }}" class="flex items-center justify-between p-3 bg-slate-50 hover:bg-primary-500 hover:text-white rounded-xl transition-all group/item">
                                <div class="min-w-0">
                                    <p class="font-bold text-xs truncate">{{ $active->qcm->titre }}</p>
                                    <p class="text-[9px] font-bold uppercase tracking-widest opacity-60">Reprendre maintenant</p>
                                </div>
                                <svg class="size-4 group-hover/item:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M9 5l7 7-7 7" /></svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Upcoming QCMs -->
            <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl shadow-lg p-5 text-white">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="size-5 text-primary-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="font-bold">Prochaines évaluations</span>
                </div>
                <div class="space-y-3">
                    @forelse($upcoming as $u)
                        <a href="{{ route('etudiant.passation', ['id' => $u->id]) }}" class="bg-white/10 hover:bg-white/20 transition-all rounded-lg p-3 flex items-center justify-between group">
                            <div>
                                <p class="font-bold text-sm">{{ $u->titre }}</p>
                                <p class="text-xs text-primary-200">{{ $u->uniteApprentissage ? $u->uniteApprentissage->nom : 'Évaluation' }}</p>
                            </div>
                            <svg class="size-4 opacity-0 group-hover:opacity-100 transition-all transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @empty
                        <div class="py-4 text-center">
                            <p class="text-xs text-primary-200">Aucune évaluation prévue</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Achievements -->
            <!-- <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <h3 class="font-bold text-slate-900 mb-1">Vos accomplissements</h3>
                <p class="text-xs text-slate-500 mb-4">Badges récemment débloqués</p>
                <div class="space-y-3">
                    @if($metrics['nb_tentatives'] >= 5)
                        <div class="flex items-center gap-3 p-3 bg-amber-50 rounded-lg border border-amber-100">
                            <div class="size-10 bg-amber-500 rounded-lg flex items-center justify-center text-white">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            </div>
                            <div>
                                <p class="font-bold text-sm text-slate-900">Assiduité</p>
                                <p class="text-xs text-slate-500">5 tests complétés</p>
                            </div>
                        </div>
                    @endif
                    @if($metrics['meilleur_score'] >= 18)
                        <div class="flex items-center gap-3 p-3 bg-emerald-50 rounded-lg border border-emerald-100">
                            <div class="size-10 bg-emerald-500 rounded-lg flex items-center justify-center text-white">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            </div>
                            <div>
                                <p class="font-bold text-sm text-slate-900">Excellence</p>
                                <p class="text-xs text-slate-500">Score 18+ atteint</p>
                            </div>
                        </div>
                    @endif
                    @if($metrics['nb_tentatives'] < 5 && $metrics['meilleur_score'] < 18)
                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg border border-slate-200">
                            <div class="size-10 bg-slate-200 rounded-lg flex items-center justify-center text-slate-400">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            </div>
                            <div>
                                <p class="font-bold text-sm text-slate-500">Continuez vos efforts</p>
                                <p class="text-xs text-slate-400">Des badges à débloquer</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div> -->

            <!-- Classement Cohorte -->
            @if(isset($cohortPodiums) && $cohortPodiums->count() > 0)
            @php
                $podiumsJson = $cohortPodiums->map(fn($item) => [
                    'titre'  => $item['qcm_titre'],
                    'my_position' => $item['my_position'],
                    'my_score' => $item['my_score'],
                    'total_students' => $item['total_students'],
                    'podium' => collect($item['podium'])->sortBy('position')->values()->toArray(),
                ])->values()->toJson();
            @endphp
            <div
                class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md"
                x-data="{
                    items: {{ $podiumsJson }},
                    current: 0,
                    open: false,
                    get item() { return this.items[this.current]; },
                    prev() { this.current = this.current > 0 ? this.current - 1 : this.items.length - 1; },
                    next() { this.current = this.current < this.items.length - 1 ? this.current + 1 : 0; }
                }"
            >
                {{-- Header --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-2">
                        <div class="size-7 bg-amber-100 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="size-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path d="M8 21h8m-4-4v4M5 3h14l-1.5 9a5 5 0 01-4.97 4H11.47A5 5 0 016.5 12L5 3z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <span class="text-xs font-black uppercase tracking-widest text-slate-900 block">Classement</span>
                            <p class="text-[11px] text-slate-400 font-bold truncate max-w-[140px] mt-0.5" x-text="item.titre"></p>
                        </div>
                    </div>
                    {{-- Switcher arrows (only if multiple QCMs) --}}
                    @if($cohortPodiums->count() > 1)
                    <div class="flex items-center gap-1">
                        <button @click="prev(); open = false;" class="size-7 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition-all">
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <span class="text-[10px] font-black text-slate-400 tabular-nums w-8 text-center" x-text="(current + 1) + '/' + items.length"></span>
                        <button @click="next(); open = false;" class="size-7 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition-all">
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                    @else
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Cohorte</span>
                    @endif
                </div>

                {{-- Personal Rank Summary Block --}}
                <div class="p-5 flex flex-col items-center justify-center text-center border-b border-slate-100 bg-white">
                    <template x-if="item.my_position !== null">
                        <div class="flex flex-col items-center">
                            <div class="inline-flex items-center justify-center size-12 bg-indigo-50 text-indigo-600 rounded-full mb-3 shadow-sm border border-indigo-100/50">
                                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </div>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Votre Rang</span>
                            <div class="flex items-baseline justify-center gap-1 mt-2">
                                <h4 class="text-3xl font-black text-indigo-600 tracking-tight" x-text="item.my_position + (item.my_position === 1 ? 'er' : 'ème')"></h4>
                                <span class="text-xs font-bold text-slate-400" x-text="'/ ' + item.total_students + ' élèves'"></span>
                            </div>
                            <p class="text-xs font-bold text-slate-500 mt-2 font-medium" x-text="'Note obtenue : ' + item.my_score + '/20'"></p>
                        </div>
                    </template>
                    <template x-if="item.my_position === null">
                        <div class="flex flex-col items-center">
                            <div class="inline-flex items-center justify-center size-12 bg-slate-50 text-slate-400 rounded-full mb-3 border border-slate-100">
                                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Non classé</span>
                            <h4 class="text-base font-black text-slate-600 mt-2">Évaluation non tentée</h4>
                            <p class="text-[10px] text-slate-400 max-w-xs mt-1 font-medium">Vous devez terminer cette évaluation pour voir votre classement par rapport à la classe.</p>
                        </div>
                    </template>
                </div>

                {{-- Trigger Button to display class list --}}
                <button type="button" @click="open = true" 
                    class="w-full h-12 bg-slate-50 hover:bg-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-600 flex items-center justify-center gap-2 transition-all select-none">
                    Voir le classement complet de la classe
                    <svg class="size-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                {{-- Teleported Popup Modal --}}
                <template x-teleport="body">
                    <div x-show="open" 
                         class="fixed inset-0 z-[999] flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto"
                         x-cloak>
                        {{-- Backdrop Overlay --}}
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="fixed inset-0 bg-slate-955/40 backdrop-blur-xs transition-opacity"
                             @click="open = false"></div>

                        {{-- Modal Container --}}
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                             class="bg-white rounded-[2.5rem] border border-slate-100 shadow-2xl max-w-lg w-full overflow-hidden transform transition-all relative z-10">
                            
                            {{-- Header --}}
                            <div class="px-8 pt-7 pb-5 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                                <div>
                                    <h3 class="text-base font-black text-slate-900 uppercase tracking-tight">Classement Général</h3>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">Supervision de la Cohorte</p>
                                </div>
                                <button @click="open = false" class="size-8 rounded-lg bg-white border border-slate-100 text-slate-400 hover:text-slate-600 shadow-xs flex items-center justify-center transition-all">
                                    <svg class="size-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- QCM Switcher / Navigator inside Modal --}}
                            <div class="px-8 py-4 bg-slate-50/40 border-b border-slate-100/80 flex items-center justify-between gap-4 select-none">
                                <button @click="prev()" 
                                        :disabled="items.length <= 1"
                                        :class="items.length <= 1 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-slate-100 hover:text-slate-900 hover:scale-105 active:scale-95 shadow-sm'"
                                        class="size-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 transition-all">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                
                                <div class="min-w-0 text-center flex-1">
                                    <span class="inline-flex px-2.5 py-1 bg-white border border-slate-200/50 rounded-lg text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1" 
                                          x-text="'ÉVALUATION ' + (current + 1) + ' / ' + items.length"></span>
                                    <h4 class="text-sm font-black text-slate-800 uppercase tracking-tight truncate" x-text="item.titre"></h4>
                                </div>

                                <button @click="next()" 
                                        :disabled="items.length <= 1"
                                        :class="items.length <= 1 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-slate-100 hover:text-slate-900 hover:scale-105 active:scale-95 shadow-sm'"
                                        class="size-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 transition-all">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>

                            {{-- Personal rank summary in the pop-up --}}
                            <div class="px-8 py-4 bg-indigo-50/20 border-b border-indigo-100/30 flex items-center justify-between gap-4 select-none">
                                <div class="flex items-center gap-3">
                                    <div class="size-8 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block leading-none">Votre Statut</span>
                                        <span class="text-xs font-bold text-slate-700 mt-1 block font-heading" x-text="item.my_position !== null ? 'Note: ' + item.my_score + '/20' : 'Non tenté'"></span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <template x-if="item.my_position !== null">
                                        <span class="inline-flex px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-black" x-text="item.my_position + (item.my_position === 1 ? 'er' : 'ème') + ' / ' + item.total_students"></span>
                                    </template>
                                    <template x-if="item.my_position === null">
                                        <span class="inline-flex px-3 py-1 bg-slate-100 text-slate-400 rounded-full text-[9px] font-black uppercase tracking-widest">Pas de rang</span>
                                    </template>
                                </div>
                            </div>

                            {{-- Ranking list as beautiful distinct cards with margins --}}
                            <div class="overflow-y-auto max-h-[380px] custom-scrollbar bg-white py-4 pb-8 flex flex-col gap-3">
                                <template x-for="(place, index) in item.podium" :key="index">
                                    <div class="flex items-center gap-4 p-4 mx-6 rounded-2xl transition-all duration-300 border bg-white"
                                         :class="{
                                             'bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-transparent border-amber-200 shadow-xs shadow-amber-500/5': place.position === 1,
                                             'bg-gradient-to-r from-slate-100/50 via-slate-50/20 to-transparent border-slate-200': place.position === 2,
                                             'bg-gradient-to-r from-indigo-50/40 via-indigo-50/10 to-transparent border-indigo-100': place.position === 3,
                                             'bg-slate-50/20 border-slate-100/70': place.position > 3
                                         }">
                                        {{-- Rank Badge --}}
                                        <div class="size-8 rounded-xl flex items-center justify-center shrink-0 text-xs font-black leading-none shadow-xs"
                                             :class="{
                                                 'bg-amber-500 text-white shadow-md shadow-amber-500/20'       : place.position === 1,
                                                 'bg-slate-200 text-slate-600'   : place.position === 2,
                                                 'bg-indigo-100 text-indigo-700': place.position === 3,
                                                 'bg-slate-100 text-slate-500'   : place.position > 3
                                             }">
                                            <span x-text="place.position"></span>
                                        </div>

                                        {{-- Name --}}
                                        <div class="flex-1 min-w-0 text-left">
                                            <span class="text-sm font-bold text-slate-800 truncate block font-heading" x-text="place.etudiant_nom"></span>
                                            <span x-show="place.position === 1"
                                                  class="text-[9px] font-black uppercase tracking-widest text-amber-500 leading-none">Leader</span>
                                        </div>

                                        {{-- Score + mini bar --}}
                                        <div class="text-right shrink-0">
                                            <span class="text-sm font-black"
                                                  :class="place.position === 1 ? 'text-amber-600 font-black' : 'text-slate-500'">
                                                <span x-text="place.score"></span><span class="text-xs font-normal text-slate-400">/20</span>
                                            </span>
                                            <div class="mt-1.5 w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full transition-all duration-500"
                                                     :class="place.position === 1 ? 'bg-amber-400' : 'bg-slate-300'"
                                                     :style="'width:' + Math.round((place.score / 20) * 100) + '%'">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                {{-- Empty state if no podium --}}
                                <template x-if="item.podium.length === 0">
                                    <div class="px-8 py-12 text-center text-xs text-slate-400 font-medium">
                                        Aucun résultat disponible pour ce QCM.
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            @endif


            <!-- Study Tips -->
            <div class="bg-slate-50 rounded-xl border border-slate-200 p-5">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="size-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="font-bold text-slate-900">Conseil du jour</h3>
                </div>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Révisez les QCMs précédents pour identifier vos points faibles et améliorer votre score moyen.
                </p>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('studentPersonalProgressChart');
        if (!ctx) return;

        const rawAttempts = {{ Js::from($allPersonalTentatives) }};
        if (rawAttempts.length === 0) return;

        const labels = rawAttempts.map(a => {
            const title = a.qcm_titre;
            return title.length > 25 ? title.substring(0, 25) + '...' : title;
        });
        const data = rawAttempts.map(a => a.score);

        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 280);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.25)');
        gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Note obtenue (/20)',
                    data: data,
                    borderColor: '#6366f1',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#fff',
                        bodyColor: '#cbd5e1',
                        borderWidth: 1,
                        borderColor: '#334155',
                        padding: 10,
                        bodyFont: {
                            family: "'Plus Jakarta Sans', system-ui, sans-serif",
                            size: 11
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Évaluations (QCM)',
                            color: '#64748b',
                            font: { family: "'Plus Jakarta Sans', system-ui, sans-serif", size: 10, weight: 'bold' }
                        },
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: "'Plus Jakarta Sans', system-ui, sans-serif",
                                size: 9,
                                weight: 'bold'
                            },
                            color: '#64748b',
                            maxRotation: 15,
                            autoSkip: true,
                            maxTicksLimit: 6
                        }
                    },
                    y: {
                        min: 0,
                        max: 20,
                        title: {
                            display: true,
                            text: 'Note obtenue / 20',
                            color: '#64748b',
                            font: { family: "'Plus Jakarta Sans', system-ui, sans-serif", size: 10, weight: 'bold' }
                        },
                        ticks: {
                            stepSize: 2,
                            font: {
                                family: "'Plus Jakarta Sans', system-ui, sans-serif",
                                size: 10
                            },
                            color: '#64748b'
                        },
                        grid: {
                            color: '#f1f5f9'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection