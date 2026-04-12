@extends('components.layout.app')

@section('content')
<div x-data="qcmResult()" x-init="init()" class="h-[100dvh] flex flex-col relative overflow-hidden font-sans">
    <!-- Header App -->
    <header class="bg-white px-5 pt-safe-top pb-4 border-b border-slate-200 shadow-sm shrink-0 sticky top-0 z-40">
        <div class="flex justify-between items-center mt-2">
            <div class="flex items-center gap-3">
                <a class="flex items-center gap-2 group outline-none" href="{{ route('student.dashboard') }}" aria-label="SoliQuiz Accueil">
                    <div class="size-9 bg-primary-500 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/20 transition-transform group-hover:scale-110">
                        <svg class="text-white size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <path d="m9 15 2 2 4-4" />
                        </svg>
                    </div>
                    <div class="flex flex-col leading-none">
                        <span class="text-xl font-heading font-bold text-slate-900 tracking-tight">Soli<span class="text-primary-500">Quiz</span></span>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] ml-0.5">Apprenant</span>
                    </div>
                </a>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Résultats</span>
            </div>
        </div>
    </header>

    <div class="bg-white px-5 py-8 shadow-sm shrink-0 text-center rounded-b-3xl border-b border-slate-100">
        <h1 class="text-xl font-heading font-bold text-slate-800 leading-tight mb-6" x-text="result.title"></h1>

        <div class="relative size-32 mx-auto">
            <svg class="size-full text-primary-500" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                <circle cx="18" cy="18" r="16" fill="none" class="stroke-slate-100" stroke-width="3"></circle>
                <circle cx="18" cy="18" r="16" fill="none" class="stroke-current text-semantic-success" stroke-width="3" :stroke-dasharray="result.percentage + ', 100'" stroke-linecap="round" stroke-linejoin="round" transform="rotate(-90 18 18)"></circle>
            </svg>
            <div class="absolute top-1/2 start-1/2 transform -translate-y-1/2 -translate-x-1/2 flex flex-col items-center">
                <span class="text-4xl font-heading font-bold text-slate-800 leading-none" x-text="result.score"></span>
                <span class="text-sm font-bold text-slate-400">/ <span x-text="result.totalQuestions"></span></span>
            </div>
        </div>

        <div class="mt-6 flex justify-center gap-2">
            <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-bold uppercase tracking-widest shadow-sm"
                :class="result.objectiveMet ? 'bg-semantic-success/15 text-semantic-success' : 'bg-semantic-error/15 text-semantic-error'"
                x-text="result.objectiveMet ? 'Objectif Atteint (' + result.percentage + '%)' : 'Objectif Non Atteint (' + result.percentage + '%)'"></span>
        </div>
    </div>

    <main class="flex-1 overflow-y-auto px-4 py-6 pb-24">
        <h2 class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-4">Détail des réponses</h2>

        <div class="space-y-4">
            <template x-for="(question, idx) in result.questions" :key="question.id">
                <div class="flex flex-col bg-white border shadow-sm rounded-xl" :class="question.isCorrect ? 'border-semantic-success/30' : 'border-semantic-error/30'">
                    <div class="p-4 flex gap-x-4">
                        <div class="shrink-0">
                            <span class="inline-flex justify-center items-center size-[38px] rounded-full" :class="question.isCorrect ? 'bg-semantic-success/20 text-semantic-success' : 'bg-semantic-error/20 text-semantic-error'">
                                <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path x-show="question.isCorrect" d="M20 6 9 17l-5-5" />
                                    <path x-show="!question.isCorrect" d="M18 6 6 18" />
                                    <path x-show="!question.isCorrect" d="m6 6 12 12" />
                                </svg>
                            </span>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-sm" x-text="'Q' + (idx + 1) + '. ' + question.text"></h3>
                            <template x-if="!question.isCorrect">
                                <div>
                                    <p class="text-sm font-medium text-semantic-error line-through mt-1 font-mono text-xs" x-text="formatAnswer(question.userAnswer, question)"></p>
                                    <p class="text-sm font-bold text-primary-600 mt-1 font-mono text-xs">Correction: <span x-text="formatAnswer(question.correctAnswer, question)"></span></p>
                                </div>
                            </template>
                            <template x-if="question.isCorrect">
                                <p class="text-sm font-medium text-slate-500 mt-1 font-mono text-xs" x-text="formatAnswer(question.userAnswer, question)"></p>
                            </template>
                        </div>
                    </div>
                    <template x-if="question.explanation">
                        <div class="px-4 pb-4">
                            <div class="bg-semantic-info/10 border border-semantic-info/20 text-sm rounded-xl p-3" role="alert">
                                <span class="font-bold uppercase tracking-wider text-[10px] text-semantic-info block mb-1">Explication</span>
                                <span class="text-[11px] text-slate-600 font-medium leading-relaxed" x-text="question.explanation"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </main>

    <!-- Bottom Action CTA -->
    <div class="absolute bottom-4 w-full bg-white border-t border-slate-200 z-50 rounded-b-[2.5rem]">
        <div class="p-4 pb-[env(safe-area-inset-bottom,20px)]">
            <a href="{{ route('student.dashboard') }}" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-bold rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700">
                Retour au Tableau de Bord
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="m12 5 7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</div>

<script>
function qcmResult() {
    return {
        qcmId: {{ $qcmId }},
        result: {},
        loading: false,
        async init() {
            await this.fetchResult();
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
                this.loading = false;
            }
        },
        formatAnswer(optionIds, question) {
            if (!optionIds || optionIds.length === 0) return 'Aucune';
            const texts = optionIds.map(id => {
                return 'Option ' + id;
            }).filter(Boolean);
            return texts.join(', ');
        }
    }
}
</script>
@endsection