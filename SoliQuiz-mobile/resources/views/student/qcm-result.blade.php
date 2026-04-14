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
                        <svg class="text-white size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <path d="m9 15 2 2 4-4" />
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
        <div class="bg-white px-5 pt-8 pb-14 shadow-[0_4px_30px_-4px_rgba(0,0,0,0.04)] shrink-0 text-center rounded-b-[4rem] border-b border-slate-100 relative overflow-hidden">
            <!-- Background Bloom -->
            <div class="absolute -top-20 -left-20 size-80 bg-primary-100/20 rounded-full blur-3xl opacity-40"></div>
            
            <div class="relative">
                <h1 class="text-3xl font-heading font-extrabold text-slate-900 tracking-tighter leading-tight mb-10" x-text="result.title || 'Chargement...'"></h1>

            <!-- Circular Progress -->
            <div class="relative size-48 mx-auto group">
                <div class="absolute inset-4 bg-white rounded-full shadow-2xl shadow-slate-200/60 scale-95 group-hover:scale-100 transition-transform duration-700"></div>
                <svg class="size-full relative z-10" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="18" cy="18" r="16" fill="none" class="stroke-slate-50" stroke-width="2.5"></circle>
                    <circle cx="18" cy="18" r="16" fill="none" class="stroke-current transition-all duration-1000 cubic-bezier(0.4, 0, 0.2, 1)" 
                        :class="result.objectiveMet ? 'text-primary-500' : 'text-semantic-error'" 
                        stroke-width="2.5" 
                        :stroke-dasharray="(result.percentage || 0) + ', 100'" 
                        stroke-linecap="round" stroke-linejoin="round"
                        transform="rotate(-90 18 18)"></circle>
                </svg>
                <div class="absolute top-1/2 start-1/2 transform -translate-y-1/2 -translate-x-1/2 flex flex-col items-center z-20">
                    <span class="text-6xl font-heading font-extrabold text-slate-950 leading-none tracking-tighter" x-text="result.score">0</span>
                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em] mt-2 italic">Sur <span x-text="result.totalQuestions">0</span></span>
                </div>
            </div>

            <div class="mt-10 flex justify-center">
                <div class="inline-flex items-center gap-x-2 py-2 px-5 rounded-full text-[9px] font-black uppercase tracking-[0.25em] shadow-sm border transition-all duration-500"
                    :class="result.objectiveMet 
                        ? 'bg-primary-50 text-primary-600 border-primary-100/50' 
                        : 'bg-semantic-error/5 text-semantic-error border-semantic-error/10'">
                    <span class="size-1.5 rounded-full" :class="result.objectiveMet ? 'bg-primary-500 animate-pulse' : 'bg-semantic-error'"></span>
                    <span x-text="result.objectiveMet ? 'Objectif Atteint' : 'Objectif non atteint'"></span>
                </div>
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
                <div class="flex flex-col bg-white border border-slate-100 shadow-[0_4px_25px_-4px_rgba(0,0,0,0.03)] rounded-[2.5rem] overflow-hidden group transition-all hover:border-slate-200">
                    <div class="p-8 flex gap-x-6">
                        <div class="shrink-0 pt-0.5">
                            <div class="inline-flex justify-center items-center size-12 rounded-2xl transition-transform group-hover:scale-110 duration-500 shadow-sm" 
                                :class="question.isCorrect ? 'bg-primary-50 text-primary-600' : 'bg-semantic-error/5 text-semantic-error'">
                                <svg x-show="question.isCorrect" class="size-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                                <svg x-show="!question.isCorrect" class="size-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 6 6 18M6 6l12 12" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em]" x-text="'Question ' + (idx + 1)"></span>
                            </div>
                            <h3 class="font-extrabold text-slate-900 text-lg leading-tight mb-5" x-text="question.text"></h3>
                            
                            <div class="grid gap-3">
                                <template x-if="!question.isCorrect">
                                    <div class="grid gap-3">
                                        <div class="flex items-center gap-3 text-semantic-error p-4 bg-semantic-error/5 rounded-2xl border border-semantic-error/5">
                                            <div class="size-6 bg-semantic-error text-white rounded-lg flex items-center justify-center shrink-0 shadow-sm">
                                                <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="4.5" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg>
                                            </div>
                                            <span class="text-xs font-black tracking-tight leading-tight" x-text="formatAnswer(question.userAnswer, question)"></span>
                                        </div>
                                        <div class="flex items-center gap-3 text-primary-700 p-4 bg-primary-50/50 rounded-2xl border border-primary-100/30">
                                            <div class="size-6 bg-primary-500 text-white rounded-lg flex items-center justify-center shrink-0 shadow-sm shadow-primary-500/20">
                                                <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="4.5" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg>
                                            </div>
                                            <span class="text-xs font-black tracking-tight leading-tight" x-text="formatAnswer(question.correctAnswer, question)"></span>
                                        </div>
                                    </div>
                                </template>
                                
                                <template x-if="question.isCorrect">
                                    <div class="flex items-center gap-3 text-slate-900 p-4 bg-slate-50/80 rounded-2xl border border-slate-100">
                                        <div class="size-6 bg-slate-950 text-white rounded-lg flex items-center justify-center shrink-0 shadow-sm">
                                            <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="4.5" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg>
                                        </div>
                                        <span class="text-xs font-black tracking-tight leading-tight" x-text="formatAnswer(question.userAnswer, question)"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    
                    <template x-if="question.explanation">
                        <div class="px-8 pb-8 pt-0 animate-in fade-in duration-700">
                            <div class="bg-primary-50/30 border border-primary-100/30 rounded-[1.5rem] p-5 relative overflow-hidden">
                                <!-- Accent -->
                                <div class="absolute top-0 right-0 size-16 bg-primary-500/5 rounded-bl-[2rem]"></div>
                                
                                <span class="font-black uppercase tracking-[0.2em] text-[8px] text-primary-500 block mb-2 italic">DÉCRYPTAGE</span>
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
</div>

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
                    const response = await fetch(`${Alpine.store('config').apiBaseUrl}/qcm/${this.qcmId}/result`);
                    if (!response.ok) throw new Error('Result not found');
                    this.result = await response.json();
                } catch (e) {
                    console.error('Failed to load result', e);
                } finally {
                    setTimeout(() => { this.loading = false; }, 800);
                }
            },
            formatAnswer(optionIds, question) {
                if (!optionIds || optionIds.length === 0) return 'Aucune';
                const texts = optionIds.map(id => {
                    return 'Option ' + id;
                }).filter(Boolean);
                return texts.join(', ');
            }
        }));
    });
</script>
@endsection
