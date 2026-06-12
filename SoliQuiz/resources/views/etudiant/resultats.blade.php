@extends('layouts.app')

@section('title', 'Résultats : ' . $qcm->titre . ' - SoliQuiz')

@section('content')
<div class="bg-transparent min-h-screen pb-16">
    <main class="max-w-4xl mx-auto px-4 py-12">
        
        <!-- Navigation -->
        <div class="mb-8">
            @php
                $referer = request()->headers->get('referer', '');
                $backUrl = route('etudiant.bibliotheque');
                if (str_contains($referer, 'progression')) {
                    $backUrl = route('etudiant.progression');
                } elseif (str_contains($referer, 'dashboard')) {
                    $backUrl = route('etudiant.dashboard');
                }
            @endphp
            <a href="{{ $backUrl }}" 
               class="inline-flex items-center gap-2 text-slate-400 hover:text-primary-600 transition-colors group">
                <div class="size-8 rounded-xl bg-white border border-slate-100 flex items-center justify-center group-hover:border-primary-200 group-hover:bg-primary-50 transition-all shadow-sm">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest">Retour</span>
            </a>
        </div>

        @php
            $isSuccess = ($tentative->score_obtenu ?? 0) >= $qcm->score_reussite;
        @endphp

        <!-- Hero Section: Résultats -->
        <section class="bg-white border border-slate-100 rounded-[2.5rem] p-8 md:p-12 mb-10 overflow-hidden relative shadow-[0_8px_30px_-4px_rgba(0,0,0,0.04)]">
            <div class="relative z-10 flex flex-col md:flex-row items-center gap-10">
                <!-- Score Circle -->
                <div class="relative size-48 flex-none flex flex-col items-center justify-center">
                    <svg class="absolute inset-0 size-full -rotate-90" viewBox="0 0 192 192">
                        <circle cx="96" cy="96" r="88" fill="none" stroke="currentColor" stroke-width="8" class="{{ $isSuccess ? 'text-emerald-500/10' : 'text-red-500/10' }}" />
                        <circle cx="96" cy="96" r="88" fill="none" stroke="currentColor" stroke-width="8"
                            class="{{ $isSuccess ? 'text-emerald-500' : 'text-red-500' }}" stroke-dasharray="552.92" stroke-dashoffset="{{ 552.92 - (552.92 * (($tentative->score_obtenu ?? 0) / 20)) }}"
                            stroke-linecap="round" />
                    </svg>
                    <div class="relative z-10 flex flex-col items-center">
                        <span class="text-4xl font-black font-heading text-slate-900 leading-tight">{{ $tentative->score_obtenu ?? 0 }}/20</span>
                        <span class="text-[10px] font-bold {{ $isSuccess ? 'text-emerald-600' : 'text-red-600' }} uppercase tracking-widest">{{ $isSuccess ? 'Objectif Validé' : 'Non Validé' }}</span>
                    </div>
                </div>

                <div class="flex-1 text-center md:text-left">
                    <span class="inline-flex items-center gap-x-1.5 px-3 py-1.5 bg-slate-100 text-slate-700 rounded-full text-[9px] font-black uppercase tracking-[0.2em] border border-slate-200">
                    <span class="size-1.5 rounded-full bg-slate-500"></span>
                    Évaluation Terminée
                </span>
                    <h1 class="text-3xl font-heading font-bold text-slate-900 mt-4 leading-tight">{{ $qcm->titre }}</h1>
                    <p class="mt-4 text-slate-500 leading-relaxed font-medium">
                        {{ $isSuccess ? 'Félicitations ' . Auth::user()->prenom . ' ! Vous avez validé '. ($qcm->uniteApprentissage ? $qcm->uniteApprentissage->nom : 'ce test') .' avec succès.' : 'Dommage ' . Auth::user()->prenom . ', l\'objectif n\'a pas été atteint. Revoyez vos erreurs ci-dessous.' }}
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4 justify-center md:justify-start">
                        <div class="bg-slate-50 px-4 py-2 rounded-2xl border border-slate-100 flex flex-col">
                            <span class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em]">Terminé le</span>
                            <span class="text-sm font-bold text-slate-800">{{ $tentative->date_fin->format('d M Y à H:i') }}</span>
                        </div>
                        <!-- <div class="bg-slate-50 px-4 py-2 rounded-2xl border border-slate-100 flex flex-col">
                            <span class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em]">Temps Passé</span>
                            <span class="text-sm font-bold text-slate-800">
                                @php
                                    $diffSeconds = $tentative->date_fin->diffInSeconds($tentative->date_debut);
                                    $min = floor($diffSeconds / 60);
                                    $sec = $diffSeconds % 60;
                                @endphp
                                {{ $min > 0 ? $min . ' min ' : '' }}{{ $sec }}s
                            </span>
                        </div> -->
                    </div>
                </div>
            </div>
        </section>

        <!-- Liste des Questions / Réponses -->
        <section class="space-y-8">
            <div class="flex items-center justify-between px-2">
                <h2 class="text-xl font-heading font-black text-slate-900 tracking-tight uppercase">Révision des réponses</h2>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ count($questionDetails) }} Questions évaluées</span>
            </div>

            @foreach($questionDetails as $index => $question)
            <div
                x-data="aiExplain({{ $question->id }}, {{ $tentative->id }})"
                class="bg-white border-2 {{ $question->isCorrect ? 'border-emerald-100' : 'border-rose-100' }} rounded-[2.5rem] p-8 md:p-10 shadow-[0_8px_30px_-4px_rgba(0,0,0,0.04)] relative group/card transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] active:scale-[0.98]">
                
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-8 mt-2">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-4">
                            <span class="inline-flex size-7 items-center justify-center rounded-lg font-black text-[10px] shadow-sm {{ $question->isCorrect ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }}">
                                {{ $index + 1 }}
                            </span>
                            <span class="text-[9px] font-black uppercase tracking-[0.2em] {{ $question->isCorrect ? 'text-emerald-500' : 'text-rose-500' }}">
                                {{ $question->isCorrect ? 'Succès' : 'Échec' }} ({{ $question->points }} pts)
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 leading-snug">{{ $question->texte }}</h3>
                    </div>

                </div>
                
                <div class="absolute top-8 right-8" x-data="{ showExplication: false }">
                    @if($question->explication)
                    <div class="relative">
                        <button @mouseenter="showExplication = true" @mouseleave="showExplication = false" 
                                class="size-10 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-primary-600 hover:text-white hover: transition-all duration-300 border border-slate-100 shadow-sm">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                        
                        <div x-show="showExplication" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2 scale-98"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             class="absolute right-0 top-full mt-3 w-[400px] max-w-[calc(100vw-4rem)] bg-white border border-slate-200 rounded-3xl shadow-2xl z-[110] overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-center gap-3 mb-4 pb-4 border-b border-slate-50">
                                    <div class="size-8 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center shrink-0">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-xs font-bold text-slate-900 tracking-tight">Analyse Pédagogique</h4>
                                </div>
                                <p class="text-[13px] text-slate-600 leading-relaxed break-words whitespace-normal font-medium">
                                    {{ $question->explication }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($question->options as $option)
                        @php
                            $isUserCorrect = $option->isSelected && $option->est_correcte;
                            $isUserWrong = $option->isSelected && !$option->est_correcte;
                            $isMissed = !$option->isSelected && $option->est_correcte;
                            $isJustFalse = !$option->isSelected && !$option->est_correcte;
                            
                            $cardStyle = "bg-slate-50/20 border border-slate-200/50 p-5 rounded-[1.25rem] transition-all duration-300 h-full opacity-50 hover:opacity-75";
                            $statusBadgeStyle = "";
                            $statusText = "";
                            $iconBoxStyle = "border border-slate-200 bg-slate-50/50 text-transparent";
                            $isSelectedAnswer = false;

                            if ($isUserCorrect) {
                                $cardStyle = "bg-emerald-500/[0.03] border border-emerald-500/25 p-5 rounded-[1.25rem] shadow-xs shadow-emerald-500/[0.02] transition-all duration-300 h-full relative overflow-hidden";
                                $statusBadgeStyle = "bg-emerald-50 text-emerald-700 border border-emerald-100";
                                $statusText = "Correcte";
                                $iconBoxStyle = "bg-emerald-500 border-emerald-500 text-white shadow-xs";
                                $isSelectedAnswer = true;
                            } elseif ($isUserWrong) {
                                $cardStyle = "bg-rose-500/[0.03] border border-rose-500/25 p-5 rounded-[1.25rem] shadow-xs shadow-rose-500/[0.02] transition-all duration-300 h-full relative overflow-hidden";
                                $statusBadgeStyle = "bg-rose-50 text-rose-700 border border-rose-100";
                                $statusText = "Fausse";
                                $iconBoxStyle = "bg-rose-500 border-rose-500 text-white shadow-xs";
                                $isSelectedAnswer = true;
                            } elseif ($isMissed) {
                                $cardStyle = "bg-emerald-500/[0.01] border-2 border-dashed border-emerald-500/20 p-5 rounded-[1.25rem] transition-all duration-300 h-full";
                                $statusBadgeStyle = "bg-emerald-50/50 text-emerald-700 border border-emerald-100/50";
                                $statusText = "Réponse attendue";
                                $iconBoxStyle = "border-2 border-emerald-500 text-emerald-500 bg-white";
                            }
                        @endphp
                        
                        <div class="{{ $cardStyle }}">
                            <div class="flex items-start gap-3">
                                <!-- Status Indicator Icon -->
                                <div class="size-5 rounded-lg flex items-center justify-center shrink-0 mt-0.5 {{ $iconBoxStyle }}">
                                    @if($isUserCorrect)
                                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @elseif($isUserWrong)
                                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    @elseif($isMissed)
                                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-4">
                                        <span class="text-xs font-bold text-slate-800 leading-tight">{{ $option->texte }}</span>
                                        <div class="flex items-center gap-1.5 shrink-0">
                                            @if($isSelectedAnswer)
                                                <span class="px-2 py-0.5 rounded-md bg-primary-600 text-white text-[8px] font-black uppercase tracking-wider whitespace-nowrap shadow-xs shadow-primary-600/10">Ma réponse</span>
                                            @endif
                                            @if($statusText)
                                                <span class="px-2 py-0.5 rounded-md text-[8px] font-black uppercase tracking-wider shrink-0 {{ $statusBadgeStyle }} whitespace-nowrap">
                                                    {{ $statusText }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($option->feedback_specifique && ($option->isSelected || $option->est_correcte))
                                        <div class="mt-3 pt-3 border-t border-slate-900/5 flex gap-2">
                                            <svg class="size-3 mt-0.5 shrink-0 {{ $option->est_correcte ? 'text-emerald-500' : 'text-rose-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-[10px] font-bold text-slate-500 leading-snug">
                                                {{ $option->feedback_specifique }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>



                {{-- AI Explanation Panel (only on failed questions) --}}
                @if(!$question->isCorrect)
                <div class="mt-8 pt-6 border-t border-slate-100">
                    <!-- Futuristic Glassmorphic AI Helper Widget -->
                    <div 
                        @click="explain()"
                        :class="explanation ? 'pointer-events-none' : 'cursor-pointer hover:border-primary-300 hover:shadow-[0_12px_40px_rgba(190,220,240,0.25)] active:scale-[0.99]'"
                        class="relative overflow-hidden rounded-3xl border-2 border-primary-100/70 bg-white p-6 transition-all duration-500 group/ai shadow-xs"
                    >
                        <!-- Shimmering neon light glow in the corner -->
                        <div class="absolute -right-20 -top-20 size-40 rounded-full bg-primary-400/10 blur-3xl group-hover/ai:bg-primary-400/20 transition-all duration-500"></div>
                        
                        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <!-- Left: AI Core Header -->
                            <div class="flex items-center gap-4">
                                <div class="relative size-12 rounded-2xl bg-primary-50 border border-primary-100 flex items-center justify-center shrink-0 shadow-inner-premium group-hover/ai:border-primary-200 transition-colors">
                                    <svg class="size-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                    <!-- AI Active Glow Ring -->
                                    <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-primary-500"></span>
                                    </span>
                                </div>
                                <div>
                                    <span class="text-[9px] font-black uppercase tracking-widest text-primary-600 leading-none">Assistant SoliBot</span>
                                    <h4 class="text-sm font-black text-slate-800 tracking-tight mt-1">Analyse pédagogique intelligente</h4>
                                    <p class="text-[11px] font-bold text-slate-400 mt-0.5 leading-none">Comprenez instantanément pourquoi votre réponse a échoué.</p>
                                </div>
                            </div>

                            <!-- Right: Action Trigger -->
                            <div class="shrink-0" x-show="!explanation">
                                <button 
                                    :disabled="loading"
                                    class="w-full md:w-auto px-5 h-12 bg-slate-900 hover:bg-slate-800 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all active:scale-98 flex items-center justify-center gap-2 group-hover/ai:bg-primary-600"
                                >
                                    <span x-show="loading" class="animate-spin size-4 border-2 border-white border-t-transparent rounded-full mr-2"></span>
                                    <span x-text="loading ? 'Analyse...' : 'Expliquer l\'erreur'"></span>
                                </button>
                            </div>
                        </div>

                        <!-- Smooth Expanding Analysis Panel -->
                        <div
                            x-show="explanation"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="mt-6 pt-6 border-t border-slate-100 flex flex-col gap-4"
                            style="display: none;"
                        >
                            <div class="rounded-2xl bg-linear-to-br from-primary-50/40 to-primary-100/10 border border-primary-100/40 p-5">
                                <p x-text="explanation" class="text-xs font-semibold text-slate-700 leading-relaxed whitespace-pre-line"></p>
                            </div>
                        </div>

                        <!-- Error Alert -->
                        <div
                            x-show="error"
                            x-transition
                            class="mt-4 rounded-xl bg-rose-50 border border-rose-100/60 p-4"
                            style="display: none;"
                        >
                            <div class="flex items-center gap-2.5 text-rose-700">
                                <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <p x-text="error" class="text-[11px] font-black uppercase tracking-wider"></p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </section>

        <!-- CTA footer results -->
        <footer class="mt-16 flex flex-col md:flex-row items-center justify-center gap-4">
            <a href="{{ route('etudiant.bibliotheque') }}" class="w-full md:w-auto inline-flex items-center justify-center gap-x-2 px-8 py-4 h-16 bg-slate-100 text-slate-700 font-black rounded-2xl hover:bg-slate-200 transition-all uppercase tracking-[0.2em] text-xs group active:scale-[0.98]">
                <svg class="size-4 transition-transform group-hover:-translate-x-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7" />
                    <path d="M19 12H5" />
                </svg>
                Bibliothèque
            </a>

            <!-- @if(!$isSuccess)
                <a href="{{ route('etudiant.passation', $qcm->id) }}" class="w-full md:w-auto inline-flex items-center justify-center gap-x-3 px-10 py-4 h-16 bg-primary-600 text-white font-black rounded-2xl hover:bg-primary-700 transition-all shadow-xl shadow-primary-600/20 uppercase tracking-[0.2em] text-xs group active:scale-[0.98]">
                    <svg class="size-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Retenter l'évaluation
                </a>
            @endif -->

            <a href="{{ route('etudiant.resultats.export', $qcm->id) }}" class="w-full md:w-auto inline-flex items-center justify-center gap-x-2 px-8 py-4 h-16 bg-white border border-slate-200 text-slate-400 font-black rounded-2xl hover:bg-slate-50 hover:text-slate-900 transition-all uppercase tracking-[0.2em] text-xs active:scale-[0.98]">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4" />
                </svg>
                Télécharger mon bilan PDF
            </a>
        </footer>
    </main>
</div>
@endsection
