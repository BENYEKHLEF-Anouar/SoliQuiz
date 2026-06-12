@extends('components.layout.app')

@section('content')
<div x-data="qcmResult" x-init="init()" x-data-qcm-id="{{ $qcmId }}" class="h-full flex flex-col relative overflow-hidden font-sans bg-slate-50">
    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-md px-5 pt-safe-top pb-4 border-b border-slate-100 shadow-[0_1px_3px_rgba(0,0,0,0.02)] shrink-0 sticky top-0 z-40">
        <div class="h-[44px] hidden ios:block"></div>
        <div class="flex justify-between items-center mt-2">
            <div class="flex items-center gap-3">
                <a class="flex items-center gap-2 group outline-none" href="{{ route('student.bibliotheque') }}">
                    <div class="size-9 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/20 active:scale-95 transition-transform">
                        <svg class="text-white size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m15 18-6-6 6-6"/>
                        </svg>
                    </div>
                </a>
                <h1 class="text-xl font-heading font-extrabold text-slate-900 tracking-tight">Résultats</h1>
            </div>
            <div class="inline-flex items-center gap-x-2 py-1.5 px-3.5 rounded-xl bg-slate-50 text-slate-400 border border-slate-100">
                <span class="text-[9px] font-black uppercase tracking-[0.2em]">BILAN</span>
            </div>
        </div>
    </header>

    <div x-show="loading" 
         x-transition:leave="transition ease-in duration-500"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="absolute inset-0 z-[100] bg-slate-50 flex items-center justify-center">
        <x-feedback.loader message="Synthèse en cours..." />
    </div>

    <div x-show="!loading" 
         class="flex-1 flex flex-col overflow-hidden"
         x-transition:enter="transition ease-out duration-700 delay-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0">

        <!-- Compact Subheader -->
        <div class="bg-white px-5 py-4.5 shadow-[0_2px_15px_rgba(0,0,0,0.02)] shrink-0 rounded-b-[2rem] border-b border-slate-100 relative overflow-hidden">
            <div class="relative flex items-center justify-between gap-4">
                <div class="flex flex-col text-left min-w-0">
                    <h2 class="text-sm font-heading font-extrabold text-slate-900 uppercase tracking-tight truncate max-w-[260px]" x-text="result.title || 'Chargement...'"></h2>
                    <div class="mt-1 flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 py-0.5 px-2 rounded-full text-[8px] font-black uppercase tracking-wider"
                            :class="result.objectiveMet ? 'bg-primary-50 text-primary-600 border-primary-100/50' 
                                : 'bg-semantic-error/5 text-semantic-error border-semantic-error/10'">
                            <span class="size-1 rounded-full" :class="result.objectiveMet ? 'bg-primary-500 animate-pulse' : 'bg-semantic-error'"></span>
                            <span x-text="result.objectiveMet ? 'Objectif Atteint' : 'Objectif non atteint'"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <main class="flex-1 overflow-y-auto px-5 py-8 hide-scrollbar pb-40">
            <!-- Premium Score Circle Hero Section -->
            <section class="bg-white border border-slate-100 rounded-[2.5rem] p-6 flex flex-col items-center justify-center text-center shadow-[0_8px_30px_-4px_rgba(0,0,0,0.04)] mb-8">
                <!-- Score Circle -->
                <div class="relative size-44 flex-none flex flex-col items-center justify-center">
                    <svg class="absolute inset-0 size-full -rotate-90" viewBox="0 0 192 192">
                        <circle cx="96" cy="96" r="84" fill="none" stroke="currentColor" stroke-width="8" class="text-slate-50" :class="result.objectiveMet ? 'text-emerald-500/5' : 'text-rose-500/5'" />
                        <circle cx="96" cy="96" r="84" fill="none" stroke="currentColor" stroke-width="8"
                            :class="result.objectiveMet ? 'text-emerald-500' : 'text-rose-500'" stroke-dasharray="527.78" :stroke-dashoffset="527.78 - (527.78 * ((result.score || 0) / (result.maxScore || 20)))"
                            stroke-linecap="round" class="transition-all duration-1000 ease-out" />
                    </svg>
                    <div class="relative z-10 flex flex-col items-center">
                        <span class="text-3xl font-black font-heading text-slate-900 leading-tight">
                            <span x-text="parseFloat(result.score || 0)"></span><span class="text-lg text-slate-400">/</span><span class="text-lg text-slate-400" x-text="parseFloat(result.maxScore || 20)"></span>
                        </span>
                        <span class="text-[8px] font-black uppercase tracking-widest mt-1" :class="result.objectiveMet ? 'text-emerald-600' : 'text-rose-600'" x-text="result.objectiveMet ? 'Objectif Validé' : 'Non Validé'"></span>
                    </div>
                </div>

                <div class="mt-6">
                    <span class="inline-flex items-center gap-x-1 py-1 px-3 bg-slate-50 text-slate-500 border border-slate-100 rounded-full text-[8px] font-black uppercase tracking-[0.2em]">
                        <span class="size-1 rounded-full bg-slate-400"></span>
                        Évaluation Terminée
                    </span>
                    <h3 class="text-lg font-heading font-extrabold text-slate-900 mt-2 leading-tight" x-text="result.title"></h3>
                    <p class="mt-3 text-xs text-slate-400 font-medium leading-relaxed max-w-xs mx-auto" 
                       x-text="result.objectiveMet ? 'Félicitations ! Vous avez validé cette évaluation avec succès.' : 'L\'objectif n\'a pas été atteint. Prenez le temps de revoir vos erreurs ci-dessous.'">
                    </p>
                </div>
            </section>

            <div class="flex flex-col mb-6">
                <h2 class="text-sm font-heading font-extrabold text-slate-900 tracking-tight leading-none uppercase">Analyse du test</h2>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.15em] mt-1.5" x-text="result.questions ? result.questions.length + ' questions évaluées' : ''"></p>
            </div>

            <!-- List of Questions -->
            <div class="grid gap-6">
                <template x-for="(question, idx) in result.questions" :key="question.id">
                    <div class="flex flex-col bg-white border-2 rounded-[2.25rem] overflow-hidden group transition-all duration-300"
                        :class="question.isCorrect ? 'border-emerald-100/80 hover:shadow-emerald-500/[0.02]' : 'border-rose-100/80 hover:shadow-rose-500/[0.02]'">
                        
                        <div class="p-6">
                            <!-- Question Meta Info -->
                            <div class="flex items-center gap-3 mb-4">
                                <span class="inline-flex size-6 items-center justify-center rounded-lg font-black text-[9px] shadow-sm text-white"
                                    :class="question.isCorrect ? 'bg-emerald-500' : 'bg-rose-500'"
                                    x-text="idx + 1">
                                </span>
                                <span class="text-[8px] font-black uppercase tracking-[0.2em]"
                                    :class="question.isCorrect ? 'text-emerald-500' : 'text-rose-500'">
                                    <span x-text="question.isCorrect ? 'Succès' : 'Échec'"></span>
                                    <span x-text="' (' + question.points + ' pts)'"></span>
                                </span>
                            </div>
                            
                            <!-- Question Text -->
                            <h3 class="text-sm font-extrabold text-slate-800 leading-snug mb-5" x-text="question.text"></h3>
                            
                            <!-- Options Grid -->
                            <div class="grid grid-cols-1 gap-3">
                                <template x-for="option in question.options" :key="option.id">
                                    <div class="p-4 rounded-xl transition-all duration-300 h-full relative overflow-hidden"
                                        :class="question.userAnswer?.includes(option.id) && option.isCorrect ? 'bg-emerald-500/[0.03] border border-emerald-500/25' :
                                                (question.userAnswer?.includes(option.id) && !option.isCorrect ? 'bg-rose-500/[0.03] border border-rose-500/25' :
                                                (!question.userAnswer?.includes(option.id) && option.isCorrect ? 'bg-emerald-500/[0.01] border border-dashed border-emerald-500/20' :
                                                'bg-slate-50/20 border border-slate-200/50 opacity-60 hover:opacity-80'))">
                                        
                                        <div class="flex items-start gap-3">
                                            <!-- Checkbox/Radio Icon Box -->
                                            <div class="size-4.5 rounded-md flex items-center justify-center shrink-0 mt-0.5"
                                                 :class="question.userAnswer?.includes(option.id) && option.isCorrect ? 'bg-emerald-500 border-emerald-500 text-white shadow-xs' :
                                                         (question.userAnswer?.includes(option.id) && !option.isCorrect ? 'bg-rose-500 border-rose-500 text-white shadow-xs' :
                                                         (!question.userAnswer?.includes(option.id) && option.isCorrect ? 'border-2 border-emerald-500 text-emerald-500 bg-white' :
                                                         'border border-slate-200 bg-slate-50/50 text-transparent'))">
                                                <template x-if="question.userAnswer?.includes(option.id) && option.isCorrect">
                                                    <svg class="size-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </template>
                                                <template x-if="question.userAnswer?.includes(option.id) && !option.isCorrect">
                                                    <svg class="size-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </template>
                                                <template x-if="!question.userAnswer?.includes(option.id) && option.isCorrect">
                                                    <svg class="size-2.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </template>
                                            </div>

                                            <!-- Option Text & Badges -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between gap-3">
                                                    <span class="text-xs font-bold text-slate-800 leading-tight pr-1" x-text="option.text"></span>
                                                    
                                                    <div class="flex items-center gap-1 shrink-0">
                                                        <template x-if="question.userAnswer?.includes(option.id)">
                                                            <span class="px-1.5 py-0.5 rounded bg-primary-600 text-white text-[7px] font-black uppercase tracking-wider whitespace-nowrap shadow-xs shadow-primary-600/10">Mien</span>
                                                        </template>
                                                        <template x-if="question.userAnswer?.includes(option.id) && option.isCorrect">
                                                            <span class="px-1.5 py-0.5 rounded text-[7px] font-black uppercase tracking-wider shrink-0 bg-emerald-50 text-emerald-700 border border-emerald-100 whitespace-nowrap">Vrai</span>
                                                        </template>
                                                        <template x-if="question.userAnswer?.includes(option.id) && !option.isCorrect">
                                                            <span class="px-1.5 py-0.5 rounded text-[7px] font-black uppercase tracking-wider shrink-0 bg-rose-50 text-rose-700 border border-rose-100 whitespace-nowrap">Faux</span>
                                                        </template>
                                                        <template x-if="!question.userAnswer?.includes(option.id) && option.isCorrect">
                                                            <span class="px-1.5 py-0.5 rounded text-[7px] font-black uppercase tracking-wider shrink-0 bg-emerald-50/50 text-emerald-700 border border-emerald-100/50 whitespace-nowrap">Attendu</span>
                                                        </template>
                                                    </div>
                                                </div>

                                                <!-- Specific Option Feedback -->
                                                <template x-if="option.specificFeedback && (question.userAnswer?.includes(option.id) || option.isCorrect)">
                                                    <div class="mt-2.5 pt-2.5 border-t border-slate-900/5 flex gap-1.5">
                                                        <svg class="size-3 mt-0.5 shrink-0" :class="option.isCorrect ? 'text-emerald-500' : 'text-rose-500'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                            <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <p class="text-[9px] font-bold text-slate-500 leading-snug" x-text="option.specificFeedback"></p>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                         </template>
                        </div>
                        
                        <!-- Fixed Static Explanation/Décryptage if exists -->
                        <template x-if="question.explanation">
                            <div class="px-6 pb-6 pt-0">
                                <div class="bg-primary-50/20 border border-primary-100/20 rounded-2xl p-4 relative overflow-hidden text-left">
                                    <div class="absolute top-0 right-0 size-12 bg-primary-500/5 rounded-bl-xl"></div>
                                    <span class="font-black uppercase tracking-[0.2em] text-[8px] text-primary-500 block mb-1.5">DÉCRYPTAGE</span>
                                    <span class="text-xs text-slate-500 font-bold leading-relaxed" x-text="question.explanation"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </main>
    </div>

    <!-- Navigation bottom placeholder/bar -->
    @include('components.nav.student-bottom-nav')

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</div>
@endsection
