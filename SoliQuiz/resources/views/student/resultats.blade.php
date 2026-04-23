@extends('layouts.app')

@section('content')
<div class="bg-slate-50 min-h-screen pb-16">
    <main class="max-w-4xl mx-auto px-4 py-12">
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
                        <div class="bg-slate-50 px-4 py-2 rounded-2xl border border-slate-100 flex flex-col">
                            <span class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em]">Durée approx.</span>
                            <span class="text-sm font-bold text-slate-800">{{ max(1, $tentative->date_fin->diffInMinutes($tentative->date_debut)) }} min</span>
                        </div>
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
            <div class="bg-white border-2 {{ $question->isCorrect ? 'border-emerald-100' : 'border-rose-100' }} rounded-[2.5rem] p-8 md:p-10 shadow-[0_8px_30px_-4px_rgba(0,0,0,0.04)] relative group/card transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] active:scale-[0.98]">
                
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

                    @if($question->explication)
                    <div x-data="{ open: false }" class="relative z-20">
                        <button @mouseenter="open = true" @mouseleave="open = false" 
                                class="size-10 bg-primary-50 text-primary-500 rounded-[1.25rem] flex items-center justify-center hover:bg-primary-500 hover:text-white transition-all shadow-[0_8px_30px_-4px_rgba(0,0,0,0.04)]">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                        <!-- Creative Popover -->
                        <div x-show="open" 
                             x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute right-0 mt-3 w-72 bg-slate-900 text-white p-5 rounded-2xl shadow-2xl z-[60] text-xs font-medium leading-relaxed border border-white/10">
                            <div class="absolute -top-1.5 right-4 size-3 bg-slate-900 rotate-45 border-t border-l border-white/10"></div>
                            <p class="font-bold text-primary-300 uppercase tracking-widest text-[9px] mb-2 font-heading">L'expertise du formateur</p>
                            {{ $question->explication }}
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
                            
                            $cardStyle = "bg-slate-50/50 border-2 border-transparent p-5 rounded-2xl transition-all h-full opacity-60";
                            $statusColor = "text-slate-400";
                            $statusText = "Fausse";

                            if ($isUserCorrect) {
                                $cardStyle = "bg-emerald-50 border-emerald-200 p-5 rounded-2xl shadow-sm transition-all h-full relative overflow-hidden";
                                $statusColor = "text-emerald-600";
                                $statusText = "Ma réponse (Correcte)";
                            } elseif ($isUserWrong) {
                                $cardStyle = "bg-rose-50 border-rose-200 p-5 rounded-2xl shadow-sm transition-all h-full relative overflow-hidden";
                                $statusColor = "text-rose-600";
                                $statusText = "Ma réponse (Fausse)";
                            } elseif ($isMissed) {
                                $cardStyle = "bg-emerald-50/50 border-2 border-dashed border-emerald-200 p-5 rounded-2xl transition-all h-full";
                                $statusColor = "text-emerald-600";
                                $statusText = "Réponse attendue";
                            }
                        @endphp
                        
                        <div class="{{ $cardStyle }}">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-800 leading-tight">{{ $option->texte }}</span>
                                <span class="text-[9px] font-black uppercase tracking-widest {{ $statusColor }}">
                                    {{ $statusText }}
                                </span>
                            </div>

                            @if($option->isSelected && $option->feedback_specifique)
                                <div class="mt-3 pt-3 border-t border-slate-900/5 items-center flex gap-2">
                                    <span class="size-1 rounded-full {{ $isUserCorrect ? 'bg-emerald-400' : 'bg-rose-400' }}"></span>
                                    <p class="text-[10px] font-bold text-slate-500 italic leading-snug">
                                        {{ $option->feedback_specifique }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </section>

        <!-- CTA footer results -->
        <footer class="mt-16 flex flex-col md:flex-row items-center justify-center gap-4">
            <a href="{{ route('student.dashboard') }}" class="w-full md:w-auto inline-flex items-center justify-center gap-x-2 px-8 py-4 h-16 bg-slate-100 text-slate-700 font-black rounded-2xl hover:bg-slate-200 transition-all uppercase tracking-[0.2em] text-xs group active:scale-[0.98]">
                <svg class="size-4 transition-transform group-hover:-translate-x-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7" />
                    <path d="M19 12H5" />
                </svg>
                Tableau de bord
            </a>

            <!-- @if(!$isSuccess)
                <a href="{{ route('student.passation', $qcm->id) }}" class="w-full md:w-auto inline-flex items-center justify-center gap-x-3 px-10 py-4 h-16 bg-primary-600 text-white font-black rounded-2xl hover:bg-primary-700 transition-all shadow-xl shadow-primary-600/20 uppercase tracking-[0.2em] text-xs group active:scale-[0.98]">
                    <svg class="size-5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Retenter l'évaluation
                </a>
            @endif -->

            <!-- <button onclick="window.print()" class="w-full md:w-auto inline-flex items-center justify-center gap-x-2 px-8 py-4 h-16 bg-white border border-slate-200 text-slate-400 font-black rounded-2xl hover:bg-slate-50 hover:text-slate-900 transition-all uppercase tracking-[0.2em] text-xs active:scale-[0.98]">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4" />
                </svg>
                Imprimer mon bilan
            </button> -->
        </footer>
    </main>
</div>
@endsection
