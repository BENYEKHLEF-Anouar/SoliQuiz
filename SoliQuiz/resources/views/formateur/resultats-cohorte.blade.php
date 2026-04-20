@extends('layouts.app')

@section('title', 'Supervision des Performances - SoliQuiz')

@section('content')
<div class="reveal active" x-data="{ selectedClasse: '' }">
    <!-- Header Hero -->
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 mb-12">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">
                <span>Espace Formateur</span>
                <span class="size-1 rounded-full bg-slate-300"></span>
                <span class="text-slate-600">Supervision Analytics</span>
            </nav>
            <h1 class="text-4xl lg:text-5xl font-heading font-black text-slate-900 tracking-tight leading-none mb-4">
                Performance <span class="text-primary-500">Monitor</span>
            </h1>
            <p class="text-slate-500 font-medium max-w-xl leading-relaxed">
                Analysez en profondeur les résultats de vos cohortes. Identifiez les points de blocage et célébrez les réussites de vos apprenants.
            </p>
        </div>
        
        <div class="flex items-center gap-4 bg-white p-3 rounded-[28px] border border-slate-100 shadow-sm pr-6 group">
            <div class="size-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-focus-within:bg-primary-500 group-focus-within:text-white transition-all">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
            </div>
            <div class="flex flex-col">
                <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest leading-none mb-1">Ciblage</span>
                <select x-model="selectedClasse" class="bg-transparent border-none p-0 text-sm font-black text-slate-900 outline-none cursor-pointer appearance-none pr-8">
                    <option value="">Toutes les cohortes</option>
                    @foreach($classes as $classe)
                        <option value="{{ $classe->nom }}">{{ $classe->nom }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    @foreach($qcms as $qcm)
    <div class="mb-20 animate-in fade-in slide-in-from-bottom-4 duration-700" x-show="true">
        <!-- QCM Label -->
        <div class="flex items-center gap-4 mb-8">
            <div class="h-px flex-1 bg-slate-100"></div>
            <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.4em] italic">{{ $qcm->titre }}</h2>
            <div class="h-px flex-1 bg-slate-100"></div>
        </div>

        @php
            $scores = $qcm->tentatives->whereNotNull('score_obtenu')->pluck('score_obtenu');
            $moyenne = $scores->count() > 0 ? round($scores->average(), 1) : 0;
            $reussite = $scores->count() > 0 ? round(($scores->filter(function($s) use ($qcm) { return $s >= $qcm->score_reussite; })->count() / $scores->count()) * 100) : 0;
        @endphp

        <!-- Metrics Bento -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="md:col-span-2 bg-slate-900 rounded-[40px] p-8 shadow-xl shadow-slate-900/10 flex flex-col justify-between group overflow-hidden relative">
                <div class="absolute -right-20 -bottom-20 size-64 bg-primary-500/10 rounded-full blur-3xl animate-pulse"></div>
                <div class="relative z-10">
                    <span class="text-[10px] font-black tracking-[0.2em] text-primary-400 uppercase mb-2 block">Moyenne Globale</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-6xl font-heading font-black text-white leading-none">{{ $moyenne }}</span>
                        <span class="text-2xl font-black text-primary-500">%</span>
                    </div>
                </div>
                <div class="relative z-10 flex items-center gap-3 mt-6">
                    <div class="flex -space-x-1">
                        @for($i=0; $i<3; $i++) <div class="size-6 bg-slate-800 rounded-full border border-slate-700"></div> @endfor
                    </div>
                    <span class="text-[10px] font-bold text-slate-500">{{ $qcm->tentatives->count() }} Interactions pédagogiques</span>
                </div>
            </div>

            <div class="bg-white rounded-[40px] p-8 border border-slate-100 shadow-sm flex flex-col justify-center">
                <span class="text-[10px] font-black tracking-[0.2em] text-slate-400 uppercase mb-4 block">Success Rate</span>
                <span class="text-4xl font-black font-heading text-emerald-500">{{ $reussite }}%</span>
                <div class="w-full h-1.5 bg-slate-50 rounded-full mt-4 overflow-hidden border border-slate-100">
                    <div class="h-full bg-emerald-500 rounded-full transition-all duration-1000" style="width: {{ $reussite }}%"></div>
                </div>
            </div>

            <div class="bg-white rounded-[40px] p-8 border border-slate-100 shadow-sm flex flex-col justify-center group hover:bg-amber-50/50 transition-colors">
                <span class="text-[10px] font-black tracking-[0.2em] text-slate-400 uppercase mb-4 block">Seuil de Validation</span>
                <span class="text-4xl font-black font-heading text-amber-500">{{ $qcm->score_reussite }}%</span>
                <p class="text-[10px] font-bold text-slate-400 mt-4 italic">Score minimum requis pour la certification UA</p>
            </div>
        </div>

        <!-- Result Table -->
        <div class="glass bg-white rounded-[40px] border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-50">
                            <th class="ps-10 pe-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Profil Apprenant</th>
                            <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Chronologie</th>
                            <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Qualification</th>
                            <th class="ps-6 pe-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Score</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($qcm->tentatives as $tentative)
                        <tr class="group hover:bg-slate-50/80 transition-colors" x-show="!selectedClasse || '{{ $tentative->etudiant->classe?->nom }}' === selectedClasse">
                            <td class="ps-10 pe-6 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <img class="size-12 rounded-2xl shadow-sm border-2 border-white group-hover:scale-110 transition-transform duration-500" 
                                             src="https://ui-avatars.com/api/?name={{ urlencode($tentative->etudiant->nom_complet) }}&background=f1f5f9&color=64748b&bold=true" alt="Avatar">
                                        @if($tentative->statut === 'reussi')
                                            <div class="absolute -bottom-1 -right-1 size-5 bg-emerald-500 rounded-full border-2 border-white flex items-center justify-center">
                                                <svg class="size-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-900">{{ $tentative->etudiant->nom_complet }}</span>
                                        <span class="text-[9px] font-black text-primary-500 uppercase tracking-[0.2em]">{{ $tentative->etudiant->classe?->nom ?? 'Hors Cohorte' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-500 leading-none">{{ $tentative->date_debut->format('d M, Y') }}</span>
                                    <span class="text-[10px] font-bold text-slate-300">{{ $tentative->date_debut->format('H:i') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-6">
                                @if($tentative->statut === 'reussi')
                                    <span class="inline-flex py-1 px-3 rounded-lg bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-widest border border-emerald-100">Compétence Validée</span>
                                @elseif($tentative->statut === 'echoue')
                                    <span class="inline-flex py-1 px-3 rounded-lg bg-rose-50 text-rose-500 text-[9px] font-black uppercase tracking-widest border border-rose-100">Échec Critique</span>
                                @else
                                    <span class="inline-flex py-1 px-3 rounded-lg bg-amber-50 text-amber-600 text-[9px] font-black uppercase tracking-widest border border-amber-100">Session Active</span>
                                @endif
                            </td>
                            <td class="ps-6 pe-10 py-6 text-right">
                                <span class="text-xl font-heading font-black {{ $tentative->score_obtenu >= $qcm->score_reussite ? 'text-slate-900' : 'text-slate-300' }}">
                                    {{ $tentative->score_obtenu !== null ? $tentative->score_obtenu . '%' : '--' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-20 text-center">
                                <div class="size-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                                    <svg class="size-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                </div>
                                <p class="text-slate-400 font-black text-xs uppercase tracking-widest italic">Aucun flux de données entrant</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

