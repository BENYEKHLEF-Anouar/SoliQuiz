@extends('layouts.app')

@section('title', 'Espace Formateur - SoliQuiz')

@section('page-title', 'Supervision Pédagogique')

@section('content')
<div class="space-y-8 fade-in" x-data="{ showSchedule: false }">
    <!-- Quick Stats Summary -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-6 border-b border-slate-100">
        <div>
            <p class="text-label mb-1">Espace Formateur</p>
            <h3 class="text-xl font-bold text-slate-900 tracking-tight italic uppercase">Vue d'ensemble {{ Auth::user()->prenom }}</h3>
        </div>
        
        <div class="flex items-center gap-4">
            <div class="hidden lg:flex items-center gap-4 px-5 py-2.5 bg-slate-50 rounded-2xl border border-slate-100">
                <div class="text-right">
                    <p class="text-xs font-black text-slate-900 italic leading-none mb-1">{{ $metrics['nb_qcms'] }} QCM</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $metrics['nb_qcms_publies'] }} publiés</p>
                </div>
                <div class="w-px h-8 bg-slate-200"></div>
                <div class="text-right">
                    <p class="text-xs font-black text-slate-900 italic leading-none mb-1">{{ $metrics['nb_etudiants'] }} Appr.</p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $metrics['nb_classes'] }} classes</p>
                </div>
            </div>
            <a href="{{ route('formateur.qcm.create') }}" class="btn-premium px-6 py-3 bg-primary-600 text-white text-[11px] uppercase tracking-widest">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M12 4v16m8-8H4"/></svg>
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
                <p class="text-xs text-slate-600 font-medium italic">Vous avez <span class="font-black text-amber-600">{{ $metrics['nb_tentatives_actives'] }}</span> apprenant(s) en cours de passation</p>
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
    <section>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Mes Cohortes</h2>
                <p class="text-sm text-slate-500 mt-0.5">Suivi des performances par classe</p>
            </div>
            <a href="{{ route('formateur.resultats') }}" class="text-sm font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                Voir tous les résultats
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
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
                            <!-- <button class="py-2 px-3 bg-primary-100 text-primary-700 rounded-lg font-bold text-xs hover:bg-primary-200 transition-all">
                                Message
                            </button> -->
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
@endsection