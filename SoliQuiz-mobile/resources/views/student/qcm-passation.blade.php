@extends('components.layout.app')

@section('content')
<div x-data="qcmPassation()" x-init="init()" x-data-qcm-id="{{ $qcmId }}" class="h-[100dvh] flex flex-col relative overflow-hidden font-sans">
    <!-- Header -->
    <header class="flex flex-wrap w-full bg-white text-sm py-4 border-b border-slate-200 z-40 shrink-0">
        <nav class="max-w-[85rem] w-full mx-auto px-4 flex items-center justify-between">
            <div class="flex items-center gap-x-2">
                <span class="inline-flex items-center justify-center size-8 rounded-full bg-slate-800 text-white text-xs font-semibold leading-none" x-text="currentIndex + 1"></span>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-widest">/ <span x-text="totalQuestions"></span></span>
            </div>
            <div class="flex items-center gap-x-2">
                <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-semantic-error/20 text-semantic-error">
                    <svg class="size-3 animate-pulse" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                    <span x-text="timerDisplay">03:45</span>
                </span>
                <a href="{{ route('student.dashboard') }}" class="size-8 inline-flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 focus:outline-none transition-colors ml-1">
                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </a>
            </div>
        </nav>
    </header>

    <!-- Progress Bar -->
    <div class="w-full h-1.5 bg-slate-200 shrink-0">
        <div class="h-1.5 bg-primary-600 transition-all duration-500" :style="`width: ${((currentIndex + 1) / totalQuestions) * 100}%`"></div>
    </div>

    <!-- Main Workspace -->
    <main class="flex-1 overflow-y-auto w-full px-4 py-8 hide-scrollbar">
        <template x-if="currentQuestion">
            <div>
                <h1 class="text-xl font-bold text-slate-800 leading-snug mb-8 font-heading" x-text="currentQuestion.text"></h1>

                <div class="grid space-y-3 pb-24">
                    <template x-for="option in currentQuestion.options" :key="option.id">
                        <label class="flex p-4 w-full border rounded-xl cursor-pointer transition-all"
                            :class="selectedOptionIds.includes(option.id) ? 'bg-primary-50/80 border-2 border-primary-600 shadow-sm' : 'bg-white border-slate-200 hover:bg-slate-50'">
                            <input type="radio" :name="'question-' + currentQuestion.id" :value="option.id"
                                class="shrink-0 mt-0.5 border-slate-300 rounded-full text-primary-600 focus:ring-primary-500"
                                @change="selectOption(option.id)" :checked="selectedOptionIds.includes(option.id)">
                            <span class="text-sm ms-3 font-medium" :class="selectedOptionIds.includes(option.id) ? 'text-primary-800 font-bold' : 'text-slate-800'" x-text="option.text"></span>
                        </label>
                    </template>
                </div>
            </div>
        </template>

        <!-- Loading state -->
        <div x-show="loading" class="flex justify-center py-10">
            <svg class="animate-spin size-8 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </main>

    <!-- Bottom Sticky Footer -->
    <div class="fixed inset-x-0 bottom-8 z-50 bg-white border-t border-slate-200 max-w-[430px] mx-auto rounded-b-[2.5rem]">
        <div class="p-4 flex gap-3 items-center justify-between pb-[env(safe-area-inset-bottom,20px)]">
            <button type="button" @click="prevQuestion()" :disabled="currentIndex === 0"
                class="py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-slate-200 text-slate-500 hover:border-primary-600 hover:text-primary-600 disabled:opacity-50 disabled:pointer-events-none">
                <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6" />
                </svg>
            </button>

            <button type="button" @click="nextQuestion()" :disabled="currentIndex === totalQuestions - 1"
                class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700 disabled:opacity-50 disabled:pointer-events-none">
                Question Suivante
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </button>
        </div>
    </div>

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</div>
@endsection
