@extends('layouts.app')

@section('title', 'Tableau de Bord - Apprenant')

@section('page-title', 'Espace Personnel Apprenant')

@section('content')
<div class="space-y-8 fade-in">
    <!-- Header -->
    <div class="relative z-30 mb-10">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-8 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-4 mb-3">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Espace Apprenant</span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-900">Dashboard</span>
                </div>
                <h3 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                    Bon retour, {{ Auth::user()->prenom }}
                </h3>
                <p class="mt-2 text-sm text-slate-500 max-w-xl">
                    Suivez vos performances, accédez à vos QCMs et progressez à votre rythme.
                </p>
            </div>
            <a href="{{ route('student.bibliotheque') }}"
               class="h-14 px-8 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] italic shadow-2xl shadow-slate-900/20 hover:bg-primary-500 hover:-translate-y-1 transition-all flex items-center gap-3 shrink-0">
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
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Recent Activity -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900">Activité Récente</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Vos dernières évaluations</p>
                    </div>
                    <a href="{{ route('student.bibliotheque') }}" class="text-xs font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                        Voir tout
                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($historique as $tentative)
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
                                <div>
                                    <p class="font-bold text-slate-900 text-sm group-hover:text-primary-600 transition-colors">{{ $tentative->qcm->titre }}</p>
                                    <p class="text-xs text-slate-500">{{ $tentative->qcm->uniteApprentissage ? $tentative->qcm->uniteApprentissage->nom : 'Évaluation transverse' }}</p>
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
                                <a href="{{ $isEnCours ? route('student.passation', $tentative->qcm_id) : route('student.resultats', ['id' => $tentative->qcm_id]) }}" class="size-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 group-hover:bg-primary-500 group-hover:text-white transition-all">
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
                            <a href="{{ route('student.bibliotheque') }}" class="mt-2 text-xs font-bold text-primary-600 hover:text-primary-700">Commencer un QCM</a>
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
                            <a href="{{ route('student.passation', $active->qcm_id) }}" class="flex items-center justify-between p-3 bg-slate-50 hover:bg-primary-500 hover:text-white rounded-xl transition-all group/item">
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
                        <a href="{{ route('student.passation', ['id' => $u->id]) }}" class="bg-white/10 hover:bg-white/20 transition-all rounded-lg p-3 flex items-center justify-between group">
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
@endsection