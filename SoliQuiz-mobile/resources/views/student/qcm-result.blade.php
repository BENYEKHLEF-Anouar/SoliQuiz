@extends('layouts.app')

@section('content')
<div x-data="qcmResult()" x-init="init()">
    <!-- Header -->
    <header class="bg-white px-5 pt-safe-top pb-4 border-b border-slate-200 shadow-sm shrink-0 sticky top-0 z-40">
        <div class="h-[44px] hidden ios:block"></div>
        <div class="flex items-center justify-between mt-2">
            <a href="{{ route('student.dashboard') }}" class="text-slate-400 hover:text-slate-600">
                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div class="flex-1 text-center">
                <span class="text-sm font-bold text-slate-800">Résultat</span>
            </div>
            <div class="w-6"></div>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto w-full hide-scrollbar pb-24 px-5 py-6">
        <!-- Score summary -->
        <div class="text-center mb-8">
            <h2 class="text-2xl font-heading font-bold text-slate-900 mb-2" x-text="result.title"></h2>
            <div class="text-5xl font-heading font-bold text-primary-600 mb-2" x-text="result.score + '/' + result.totalQuestions"></div>
            <span class="text-lg font-bold" :class="result.objectiveMet ? 'text-success-500' : 'text-danger-500'"
                x-text="result.objectiveMet ? 'Objectif Atteint (' + result.percentage + '%)' : 'Objectif Non Atteint (' + result.percentage + '%)'"></span>
        </div>

        <!-- Questions detail -->
        <div class="space-y-4">
            <template x-for="(question, idx) in result.questions" :key="question.id">
                <div class="bg-white border rounded-xl p-4"
                    :class="question.isCorrect ? 'border-success-200' : 'border-danger-200'">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            <span class="inline-flex items-center justify-center size-6 rounded-full text-xs font-bold"
                                :class="question.isCorrect ? 'bg-success-100 text-success-700' : 'bg-danger-100 text-danger-700'"
                                x-text="idx + 1"></span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-slate-800 mb-2" x-text="question.text"></p>
                            <div class="text-xs space-y-1">
                                <div>
                                    <span class="font-bold text-slate-500">Votre réponse:</span>
                                    <span :class="question.isCorrect ? 'text-success-600' : 'text-danger-600'"
                                        x-text="formatAnswer(question.userAnswer, question)"></span>
                                </div>
                                <div x-show="!question.isCorrect">
                                    <span class="font-bold text-slate-500">Réponse correcte:</span>
                                    <span class="text-success-600" x-text="formatAnswer(question.correctAnswer, question)"></span>
                                </div>
                            </div>
                            <div x-show="question.explanation" class="mt-3 p-3 bg-slate-50 rounded-lg text-xs text-slate-600">
                                <span class="font-bold">Explication:</span> <span x-text="question.explanation"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Loading state -->
        <div x-show="loading" class="flex justify-center py-10">
            <svg class="animate-spin size-8 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </main>
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
                const opt = question.options.find(o => o.id === id);
                return opt ? opt.text : '';
            }).filter(Boolean);
            return texts.join(', ');
        }
    }
}
</script>
@endsection