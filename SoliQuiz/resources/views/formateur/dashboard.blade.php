@extends('layouts.app')

@section('title', 'Espace Formateur - SoliQuiz')

@section('page-title', 'Supervision Pédagogique')

@section('content')
<div class="space-y-8 fade-in" x-data="{ showSchedule: false }">
    <!-- Header -->
    <div class="relative z-30 mb-10">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-8 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-4 mb-3">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Espace Formateur</span>
                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-900">Dashboard</span>
                </div>
                <h3 class="text-2xl lg:text-3xl font-black text-slate-900 tracking-tight">
                    Bon retour, {{ Auth::user()->prenom }}
                </h3>
                <p class="mt-2 text-sm text-slate-500 max-w-xl">
                    Supervise tes cohortes, publie tes QCMs et suis les performances en temps réel.
                </p>
            </div>
            <a href="{{ route('formateur.qcm.create') }}"
               class="h-14 px-8 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-2xl shadow-slate-900/20 hover:bg-primary-500 hover:-translate-y-1 transition-all flex items-center gap-3 shrink-0">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4" /></svg>
                Nouveau QCM
            </a>
        </div>
    </div>


    <!-- Pending Actions Banner -->
    @if($metrics['nb_tentatives_actives'] > 0)
    <div class="bg-linear-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-4 flex items-center justify-between animate-in slide-in-from-right duration-700">
        <div class="flex items-center gap-3">
            <div class="size-10 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 animate-pulse">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="font-bold text-slate-900 uppercase text-[10px] tracking-widest mb-1">Passations en temps réel</p>
                <p class="text-xs text-slate-600 font-medium">Vous avez <span class="font-black text-amber-600">{{ $metrics['nb_tentatives_actives'] }}</span> apprenant(s) en cours de passation</p>
            </div>
        </div>
        <a href="{{ route('formateur.resultats') }}" class="px-5 py-2.5 bg-amber-500 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-amber-600 transition-all shadow-lg shadow-amber-500/20 active:scale-95">
            Superviser
        </a>
    </div>
    @endif

    <!-- Enhanced KPI Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Classes Card -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="size-9 bg-primary-100 rounded-lg flex items-center justify-center text-primary-600">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Mes Classes</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-black text-slate-900">{{ $metrics['nb_classes'] }}</span>
                <span class="text-xs text-slate-400">assignées</span>
            </div>
            <div class="mt-3 flex -space-x-1">
                <div class="size-6 rounded-full bg-slate-300 border-2 border-white"></div>
                <div class="size-6 rounded-full bg-slate-400 border-2 border-white"></div>
                <div class="size-6 rounded-full bg-slate-500 border-2 border-white"></div>
                @if($metrics['nb_etudiants'] > 0)
                    <div class="size-6 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center text-[8px] font-bold text-slate-600">+{{ $metrics['nb_etudiants'] }}</div>
                @endif
            </div>
        </div>

        <!-- Students Card -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="size-9 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Apprenants</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-black text-slate-900">{{ $metrics['nb_etudiants'] }}</span>
                <span class="text-xs text-slate-400">actifs</span>
            </div>
            <p class="mt-2 text-xs text-slate-500">Moyenne par classe: {{ $metrics['nb_classes'] > 0 ? round($metrics['nb_etudiants'] / $metrics['nb_classes']) : 0 }}</p>
        </div>

        <!-- Total QCM Card -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="size-9 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">QCMs créés</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-black text-slate-900">{{ $metrics['nb_qcms'] }}</span>
                <span class="text-xs text-slate-400">au total</span>
            </div>
            <div class="mt-2 flex items-center gap-1.5">
                <span class="flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">
                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    {{ $metrics['growth_suffix'] }}
                </span>
                <span class="text-xs text-slate-500">cette semaine</span>
            </div>
        </div>

        <!-- Published QCM Card -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="size-9 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Publiés</span>
                </div>
                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-[10px] font-bold">
                    {{ $metrics['nb_qcms'] > 0 ? round(($metrics['nb_qcms_publies'] / $metrics['nb_qcms']) * 100) : 0 }}%
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-black text-emerald-600">{{ $metrics['nb_qcms_publies'] }}</span>
                <span class="text-xs text-slate-400">en ligne</span>
            </div>
            <div class="mt-2 w-full bg-slate-100 rounded-full h-1.5">
                <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $metrics['nb_qcms'] > 0 ? ($metrics['nb_qcms_publies'] / $metrics['nb_qcms']) * 100 : 0 }}%"></div>
            </div>
        </div>
    </div>

    <!-- Classes & Performance Section -->
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Classes Cards -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Mes Cohortes</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Suivi des performances par classe</p>
                </div>
                <a href="{{ route('formateur.resultats') }}" class="text-sm font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                    Voir tous les résultats
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($classes as $classe)
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition-all group">
                        <div class="p-5">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $classe->promotion ?? 'Promotion 2026' }}</span>
                                    <h3 class="font-bold text-lg text-slate-900 mt-0.5 group-hover:text-primary-600 transition-colors">{{ $classe->nom }}</h3>
                                </div>
                                <div class="size-10 bg-slate-100 rounded-lg flex items-center justify-center text-slate-500 group-hover:bg-primary-500 group-hover:text-white transition-all">
                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                            </div>

                            <!-- Stats Grid -->
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="bg-slate-50 rounded-lg p-3">
                                    <p class="text-xl font-black text-slate-900">{{ $classe->etudiants_count }}</p>
                                    <p class="text-xs text-slate-500">Apprenants</p>
                                </div>
                                <div class="bg-slate-50 rounded-lg p-3">
                                    <p class="text-xl font-black text-emerald-600">{{ $classe->moyenne }}/20</p>
                                    <p class="text-xs text-slate-500">Moyenne classe</p>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <div class="flex items-center justify-between text-xs mb-1.5">
                                    <span class="text-slate-500">Taux de réussite</span>
                                    <span class="font-bold text-slate-700">{{ $classe->taux_reussite }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div class="bg-linear-to-r from-emerald-500 to-emerald-400 h-2 rounded-full" style="width: {{ $classe->taux_reussite }}%"></div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="flex gap-2">
                                <a href="{{ route('formateur.resultats') }}" class="flex-1 py-2 px-3 bg-slate-100 text-slate-700 rounded-lg font-bold text-xs text-center hover:bg-slate-200 transition-all">
                                    Résultats
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center bg-slate-50 rounded-xl border border-dashed border-slate-300">
                        <div class="size-12 bg-slate-200 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <svg class="size-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <p class="text-sm text-slate-600 font-medium">Aucune classe assignée</p>
                        <p class="text-xs text-slate-400 mt-1">Contactez l'administration</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right: Real Graph using Chart.js & Leaderboard Widget -->
        <div class="space-y-6 flex flex-col">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between h-[300px]">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Performances Globales</h3>
                    <p class="text-xs text-slate-500 mt-0.5 mb-6">Moyenne générale et taux de réussite par cohorte</p>
                </div>
                
                <div class="relative w-full flex-1 min-h-[160px] flex items-center justify-center">
                    @if(count($classes) > 0)
                        <canvas id="performanceChart"></canvas>
                    @else
                        <div class="text-center py-10">
                            <div class="size-12 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3 text-slate-400">
                                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <p class="text-xs text-slate-400 font-medium">Aucune donnée de performance</p>
                        </div>
                    @endif
                </div>

                @if(count($classes) > 0)
                    <div class="mt-4 space-y-2 border-t border-slate-100 pt-3">
                        <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-wider">
                            <span class="flex items-center gap-2 text-indigo-500">
                                <span class="size-2.5 rounded-full bg-indigo-500 block"></span>
                                Moyenne / 20
                            </span>
                            <span class="flex items-center gap-2 text-emerald-500">
                                <span class="size-2.5 rounded-full bg-emerald-400 block"></span>
                                Réussite %
                            </span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Leaderboard Widget -->
            @if(isset($cohortPodiums) && $cohortPodiums->count() > 0)
            @php
                $podiumsJson = $cohortPodiums->map(fn($item) => [
                    'qcm_id' => $item['qcm_id'],
                    'titre'  => $item['qcm_titre'],
                    'classe_nom' => $item['classe_nom'],
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
                        <a href="{{ route('formateur.leaderboard') }}" class="size-7 bg-amber-100 rounded-lg flex items-center justify-center shrink-0 hover:bg-amber-200 transition-colors" title="Accéder au classement complet">
                            <svg class="size-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path d="M8 21h8m-4-4v4M5 3h14l-1.5 9a5 5 0 01-4.97 4H11.47A5 5 0 016.5 12L5 3z"/>
                            </svg>
                        </a>
                        <div class="min-w-0">
                            <a href="{{ route('formateur.leaderboard') }}" class="text-xs font-black uppercase tracking-widest text-slate-900 block hover:text-primary-600 transition-colors">Classement ↗</a>
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

                {{-- Podium Summary Block --}}
                <div class="p-5 flex flex-col items-center justify-center text-center border-b border-slate-100 bg-white">
                    <div class="flex flex-col items-center">
                        <div class="inline-flex items-center justify-center size-12 bg-indigo-50 text-indigo-600 rounded-full mb-3 shadow-sm border border-indigo-100/50">
                            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z"/>
                            </svg>
                        </div>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none" x-text="'CLASSE : ' + item.classe_nom"></span>
                        <div class="flex items-baseline justify-center gap-1 mt-2">
                            <h4 class="text-xl font-black text-indigo-600 tracking-tight" x-text="item.podium[0] ? item.podium[0].etudiant_nom : 'Aucun'"></h4>
                        </div>
                        <p class="text-xs font-bold text-slate-500 mt-2 font-medium" x-text="item.podium[0] ? 'Score max : ' + item.podium[0].score + '/20' : ''"></p>
                    </div>
                </div>

                {{-- Trigger Button to display class list --}}
                <button type="button" @click="open = true" 
                    class="w-full h-12 bg-slate-50 hover:bg-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-600 flex items-center justify-center gap-2 transition-all select-none">
                    Voir le classement complet
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
                                    <h3 class="text-base font-black text-slate-900 uppercase tracking-tight">Classement QCM</h3>
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

                            {{-- Footer with link to full leaderboard --}}
                            <div class="px-8 py-5 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                                <a :href="'{{ route('formateur.leaderboard') }}?qcm_id=' + item.qcm_id" class="text-xs font-black uppercase tracking-wider text-primary-600 hover:text-primary-700 flex items-center gap-1.5 transition-colors">
                                    Classement Complet
                                    <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                                <button @click="open = false" class="h-10 px-5 border border-slate-200 hover:bg-slate-100 text-slate-500 hover:text-slate-800 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all outline-none">
                                    Fermer
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            @endif
        </div>

    </section>

    <!-- Quick Access Tools -->
    <section class="mt-8">
        <h3 class="font-bold text-slate-900 mb-4">Outils Rapides</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <a href="{{ route('formateur.pedagogie') }}" class="flex items-center gap-3 p-4 bg-white rounded-xl border border-slate-200 hover:border-primary-300 hover:shadow-sm transition-all group">
                <div class="size-10 bg-primary-50 rounded-lg flex items-center justify-center text-primary-600 group-hover:bg-primary-500 group-hover:text-white transition-all">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                </div>
                <span class="font-bold text-sm text-slate-700">Pédagogie</span>
            </a>
            <a href="{{ route('formateur.bibliotheque') }}" class="flex items-center gap-3 p-4 bg-white rounded-xl border border-slate-200 hover:border-emerald-300 hover:shadow-sm transition-all group">
                <div class="size-10 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <span class="font-bold text-sm text-slate-700">Bibliothèque</span>
            </a>
            <a href="{{ route('formateur.resultats') }}" class="flex items-center gap-3 p-4 bg-white rounded-xl border border-slate-200 hover:border-amber-300 hover:shadow-sm transition-all group">
                <div class="size-10 bg-amber-50 rounded-lg flex items-center justify-center text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition-all">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <span class="font-bold text-sm text-slate-700">Résultats</span>
            </a>
            <button @click="$dispatch('toast', { message: 'Fonctionnalité en développement', type: 'info' })" class="flex items-center gap-3 p-4 bg-white rounded-xl border border-slate-200 hover:border-rose-300 hover:shadow-sm transition-all group text-left">
                <div class="size-10 bg-rose-50 rounded-lg flex items-center justify-center text-rose-600 group-hover:bg-rose-500 group-hover:text-white transition-all">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <span class="font-bold text-sm text-slate-700">Planning</span>
            </button>
        </div>
    </section>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('performanceChart');
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
                        title: {
                            display: true,
                            text: 'Cohortes',
                            color: '#64748b',
                            font: { family: 'Inter, system-ui, sans-serif', size: 10, weight: 'bold' }
                        },
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
                        title: {
                            display: true,
                            text: 'Moyenne générale (/20)',
                            color: '#64748b',
                            font: { family: 'Inter, system-ui, sans-serif', size: 10, weight: 'bold' }
                        },
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
                        title: {
                            display: true,
                            text: 'Taux de réussite (%)',
                            color: '#64748b',
                            font: { family: 'Inter, system-ui, sans-serif', size: 10, weight: 'bold' }
                        },
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