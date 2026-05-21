@extends('components.layout.app')

@section('content')
<div x-data="qcmResult" x-init="init()" x-data-qcm-id="{{ $qcmId }}" class="h-full flex flex-col relative overflow-hidden font-sans bg-slate-50">
    <!-- Header -->
    <header class="bg-white/80 backdrop-blur-md px-5 pt-safe-top pb-4 border-b border-slate-100 shadow-[0_1px_3px_rgba(0,0,0,0.02)] shrink-0 sticky top-0 z-40">
        <div class="h-[44px] hidden ios:block"></div>
        <div class="flex justify-between items-center mt-2">
            <div class="flex items-center gap-3">
                <a class="flex items-center gap-2 group outline-none" href="{{ route('student.dashboard') }}">
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
                <div class="text-right flex items-baseline gap-1 shrink-0 bg-slate-50 border border-slate-100 px-3.5 py-1.5 rounded-2xl shadow-sm">
                    <span class="text-2xl font-heading font-extrabold text-slate-950 leading-none tracking-tighter" x-text="parseFloat(result.score || 0)">0</span>
                    <span class="text-[10px] font-bold text-slate-400">/ <span x-text="parseFloat(result.maxScore || 0)">0</span></span>
                </div>
            </div>
        </div>

    <main class="flex-1 overflow-y-auto px-6 py-12 hide-scrollbar pb-40">
        <div class="flex flex-col mb-8">
            <h2 class="text-xl font-heading font-extrabold text-slate-900 tracking-tight leading-none">ANALYSE DU TEST</h2>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-2">Détails de vos réponses</p>
        </div>

        <div class="grid gap-6">
            <template x-for="(question, idx) in result.questions" :key="question.id">
                <div class="flex flex-col bg-white border-2 rounded-[2.5rem] overflow-hidden group transition-all"
                    :class="question.isCorrect ? 'border-emerald-100' : 'border-rose-100'">
                    
                    <div class="p-8">
                        <div class="flex items-center gap-4 mb-4">
                            <span class="inline-flex size-7 items-center justify-center rounded-lg font-black text-[10px] shadow-sm text-white"
                                :class="question.isCorrect ? 'bg-emerald-500' : 'bg-rose-500'"
                                x-text="idx + 1">
                            </span>
                            <span class="text-[9px] font-black uppercase tracking-[0.2em]"
                                :class="question.isCorrect ? 'text-emerald-500' : 'text-rose-500'">
                                <span x-text="question.isCorrect ? 'Succès' : 'Échec'"></span>
                                <span x-text="' (' + question.points + ' pts)'"></span>
                            </span>
                        </div>
                        
                        <h3 class="text-base font-extrabold text-slate-900 leading-snug mb-6" x-text="question.text"></h3>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <template x-for="option in question.options" :key="option.id">
                                <div :class="getOptionState(option, question).cardClass">
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="text-xs font-bold leading-tight" :class="getOptionState(option, question).textColor" x-text="option.text"></span>
                                        <span class="text-[9px] font-black uppercase tracking-widest shrink-0" :class="getOptionState(option, question).statusClass" x-text="getOptionState(option, question).statusText"></span>
                                    </div>
                                    
                                    <template x-if="option.specificFeedback && getOptionState(option, question).showFeedback">
                                        <div class="mt-3 pt-3 border-t border-slate-900/5 flex gap-2">
                                            <svg class="size-3 mt-0.5 shrink-0" :class="option.isCorrect ? 'text-emerald-500' : 'text-rose-500'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-[10px] font-bold text-slate-500 leading-snug" x-text="option.specificFeedback"></p>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                    
                    <template x-if="question.explanation">
                        <div class="px-8 pb-8 pt-0">
                            <div class="bg-primary-50/30 border border-primary-100/30 rounded-[1.5rem] p-5 relative overflow-hidden">
                                <div class="absolute top-0 right-0 size-16 bg-primary-500/5 rounded-bl-[2rem]"></div>
                                <span class="font-black uppercase tracking-[0.2em] text-[8px] text-primary-500 block mb-2">DÉCRYPTAGE</span>
                                <span class="text-xs text-slate-600 font-bold leading-relaxed" x-text="question.explanation"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </main>

        </div>
    </div>

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('qcmResult', () => ({
            qcmId: null,
            result: {},
            loading: false,
            async init() {
                const el = document.querySelector('[x-data-qcm-id]');
                this.qcmId = el?.getAttribute('x-data-qcm-id') || null;
                if (this.qcmId) {
                    await this.fetchResult();
                }
            },
            async fetchResult() {
                this.loading = true;
                try {
                    const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/qcm/${this.qcmId}/result`);
                    if (!response.ok) throw new Error('Result not found');
                    this.result = await response.json();
                } catch (e) {
                    console.error('Failed to load result', e);
                } finally {
                    setTimeout(() => { this.loading = false; }, 800);
                }
            },
            getOptionState(option, question) {
                const isSelected = question.userAnswer?.includes(option.id);
                const isCorrect = question.correctAnswer?.includes(option.id);
                
                if (isSelected && isCorrect) {
                    return {
                        cardClass: 'bg-emerald-50 border border-emerald-200 p-5 rounded-2xl shadow-sm relative overflow-hidden',
                        textColor: 'text-emerald-800',
                        statusClass: 'text-emerald-600',
                        statusText: 'Ma réponse (Correcte)',
                        showFeedback: true
                    };
                } else if (isSelected && !isCorrect) {
                    return {
                        cardClass: 'bg-rose-50 border border-rose-200 p-5 rounded-2xl shadow-sm relative overflow-hidden',
                        textColor: 'text-rose-800',
                        statusClass: 'text-rose-600',
                        statusText: 'Ma réponse (Fausse)',
                        showFeedback: true
                    };
                } else if (!isSelected && isCorrect) {
                    return {
                        cardClass: 'bg-emerald-50/50 border-2 border-dashed border-emerald-200 p-5 rounded-2xl',
                        textColor: 'text-emerald-800',
                        statusClass: 'text-emerald-600',
                        statusText: 'Réponse attendue',
                        showFeedback: true
                    };
                } else {
                    return {
                        cardClass: 'bg-slate-50/50 border-2 border-transparent p-5 rounded-2xl opacity-60',
                        textColor: 'text-slate-700',
                        statusClass: 'text-slate-400',
                        statusText: 'Fausse',
                        showFeedback: false
                    };
                }
            }
        }));
    });
</script>
@endsection
