@extends('components.layout.app')

@section('content')
<div x-data="qcmPassation" x-init="init()" x-data-qcm-id="{{ $qcmId }}" class="h-full flex flex-col relative overflow-hidden font-sans">
    
    <!-- Preline Header Pattern -->
    <header class="bg-white/80 backdrop-blur-md px-5 pt-safe-top pb-4 border-b border-slate-100 shadow-[0_1px_3px_rgba(0,0,0,0.02)] shrink-0 sticky top-0 z-40">
        <div class="h-[44px] hidden ios:block"></div>
        <div class="flex justify-between items-center mt-2">
            <div class="flex items-center gap-3">
                <div class="size-10 bg-slate-950 text-white rounded-xl flex items-center justify-center shadow-lg shadow-slate-950/20">
                    <span class="text-sm font-black" x-text="currentIndex + 1"></span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Progression</span>
                    <span class="text-xs font-black text-slate-600">Question <span x-text="currentIndex + 1"></span> <span class="text-slate-300">/</span> <span x-text="totalQuestions"></span></span>
                </div>
            </div>
            <div class="flex items-center gap-x-3">
                <!-- Timer Badge -->
                <div class="inline-flex items-center gap-x-2 py-2 px-4 rounded-[1.25rem] bg-semantic-error/5 text-semantic-error border border-semantic-error/10">
                    <svg class="size-4 animate-pulse" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                    <span class="text-[11px] font-black tracking-widest" x-text="timerDisplay">00:00</span>
                </div>
                
                <a href="{{ route('student.dashboard') }}" class="size-10 inline-flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:bg-slate-100 transition-colors active:scale-90 outline-none">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <!-- Progress Bar -->
    <div class="w-full h-1 bg-slate-50 shrink-0 relative overflow-hidden">
        <div class="h-1 bg-primary-500 transition-all duration-1000 cubic-bezier(0.4, 0, 0.2, 1)" :style="`width: ${((currentIndex + 1) / totalQuestions) * 100}%`"></div>
    </div>

    <!-- Main Workspace -->
    <main class="flex-1 overflow-y-auto w-full px-6 py-10 pb-40 hide-scrollbar">
        <!-- Global Loading State -->
        <div x-show="loading" 
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 z-50 bg-white flex items-center justify-center">
            <x-feedback.loader message="Extraction des données..." />
        </div>

        <template x-if="currentQuestion && !loading">
            <div x-show="!loading"
                 x-transition:enter="transition ease-out duration-700 delay-300"
                 x-transition:enter-start="opacity-0 translate-y-8"
                 x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex items-center gap-2 mb-6">
                    <span class="size-1.5 rounded-full bg-primary-500"></span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">ÉVALUATION EN COURS</span>
                </div>
                <h1 class="text-3xl font-heading font-extrabold text-slate-900 leading-tight mb-12" x-text="currentQuestion.text"></h1>

                <div class="grid space-y-5">
                    <template x-for="(option, index) in currentQuestion.options" :key="option.id">
                        <label class="flex items-center p-6 w-full border border-slate-100 rounded-[2rem] cursor-pointer transition-all active:scale-[0.98] group relative shadow-[0_4px_15px_-3px_rgba(0,0,0,0.02)]"
                            :class="selectedOptionIds.includes(option.id) ? 'bg-primary-50/30 border-primary-500/30 shadow-xl shadow-primary-500/5' 
                                : 'bg-white hover:border-slate-200'">
                            
                            <!-- Custom Radio Visual -->
                            <div class="flex items-center justify-center shrink-0">
                                <input type="radio" 
                                    :name="'question-' + currentQuestion.id" 
                                    :value="option.id"
                                    class="hidden"
                                    @change="selectOption(option.id)" 
                                    :checked="selectedOptionIds.includes(option.id)">
                                <div class="size-7 rounded-2xl border-2 flex items-center justify-center transition-all duration-500"
                                    :class="selectedOptionIds.includes(option.id) ? 'border-primary-500 bg-primary-500 shadow-lg shadow-primary-500/30' : 'border-slate-100 bg-slate-50 group-hover:border-slate-200'">
                                    <svg x-show="selectedOptionIds.includes(option.id)" class="size-4 text-white animate-in zoom-in duration-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4.5"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                            </div>
                            
                            <span class="text-lg ms-5 transition-all font-bold tracking-tight leading-tight" 
                                :class="selectedOptionIds.includes(option.id) ? 'text-primary-950' : 'text-slate-800'" 
                                x-text="option.text"></span>

                            <!-- Letter Badge (A, B, C...) -->
                            <span class="absolute top-1/2 -translate-y-1/2 right-6 text-[10px] font-black text-slate-200 group-hover:text-slate-300 transition-colors uppercase" x-text="String.fromCharCode(65 + index)"></span>
                        </label>
                    </template>
                </div>
            </div>
        </template>
    </main>

    <!-- Bottom Sticky Footer -->
    <div class="fixed inset-x-0 bottom-0 z-50 bg-white/80 backdrop-blur-2xl border-t border-slate-100 max-w-[430px] mx-auto rounded-b-[2.5rem]">
        <div class="p-6 flex gap-4 items-center justify-between pb-[env(safe-area-inset-bottom,32px)]">
            
            <button type="button" @click="prevQuestion()" :disabled="currentIndex === 0"
                class="size-16 inline-flex justify-center items-center rounded-2xl border border-slate-100 bg-slate-50 text-slate-400 hover:bg-slate-100 disabled:opacity-30 disabled:pointer-events-none transition-all active:scale-90 outline-none">
                <svg class="size-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6" />
                </svg>
            </button>

            <button type="button" @click="nextQuestion()" :disabled="!selectedOptionIds.length"
                class="flex-1 h-16 inline-flex justify-center items-center gap-x-2 text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl border border-transparent bg-slate-950 text-white shadow-2xl shadow-slate-950/20 hover:bg-slate-900 disabled:opacity-50 disabled:pointer-events-none active:scale-[0.98] transition-all outline-none">
                <span x-text="currentIndex === totalQuestions - 1 ? 'Soumettre l\'examen' : 'Suivante'"></span>
                <svg x-show="currentIndex !== totalQuestions - 1" class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </button>
        </div>
    </div>

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('qcmPassation', () => ({
            qcmId: null,
            qcm: {},
            questions: [],
            currentIndex: 0,
            loading: false,
            selectedOptions: {},
            timerMinutes: 0,
            timerSeconds: 0,
            timerInterval: null,
            get totalQuestions() { return this.questions.length; },
            get currentQuestion() { return this.questions[this.currentIndex]; },
            get selectedOptionIds() {
                if (!this.currentQuestion) return [];
                return this.selectedOptions[this.currentQuestion.id] || [];
            },
            get timerDisplay() {
                return `${this.timerMinutes.toString().padStart(2, '0')}:${this.timerSeconds.toString().padStart(2, '0')}`;
            },
            async init() {
                const el = document.querySelector('[x-data-qcm-id]');
                this.qcmId = el?.getAttribute('x-data-qcm-id') || null;
                if (!this.qcmId) {
                    console.error('QCM ID not found');
                    return;
                }
                await this.fetchQcm();
                await this.fetchQuestions();
                this.startTimer();
            },
            async fetchQcm() {
                try {
                    const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/qcm/${this.qcmId}/start`);
                    const data = await response.json();
                    this.qcm = data;
                    if (data.tempsRestant > 0) {
                        this.timerMinutes = Math.floor(data.tempsRestant / 60);
                        this.timerSeconds = data.tempsRestant % 60;
                    } else if (data.durationMinutes > 0) {
                        this.timerMinutes = data.durationMinutes;
                        this.timerSeconds = 0;
                    } else {
                        this.timerMinutes = 0;
                        this.timerSeconds = 0;
                    }
                    if (data.initialAnswers) {
                        this.selectedOptions = data.initialAnswers;
                    }
                } catch (e) {
                    console.error('Failed to start/load QCM', e);
                    this.timerMinutes = 5;
                    this.timerSeconds = 0;
                }
            },
            async fetchQuestions() {
                this.loading = true;
                try {
                    const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/qcm/${this.qcmId}/questions`);
                    this.questions = await response.json();
                    this.questions.forEach(q => {
                        if (!this.selectedOptions[q.id]) {
                            this.selectedOptions[q.id] = [];
                        }
                    });
                } catch (e) {
                    console.error('Failed to load questions', e);
                } finally {
                    this.loading = false;
                }
            },
            selectOption(optionId) {
                const q = this.currentQuestion;
                if (!q) return;
                if (q.type === 'unique') {
                    this.selectedOptions[q.id] = [optionId];
                } else {
                    const idx = this.selectedOptions[q.id].indexOf(optionId);
                    if (idx > -1) {
                        this.selectedOptions[q.id].splice(idx, 1);
                    } else {
                        this.selectedOptions[q.id].push(optionId);
                    }
                }
            },
            prevQuestion() {
                if (this.currentIndex > 0) this.currentIndex--;
            },
            async nextQuestion() {
                if (this.currentIndex < this.totalQuestions - 1) {
                    this.currentIndex++;
                } else {
                    await this.submitQcm();
                }
            },
            async submitQcm() {
                this.loading = true;
                try {
                    const response = await Alpine.store('config').authFetch(`${Alpine.store('config').apiBaseUrl}/qcm/${this.qcmId}/submit`, {
                        method: 'POST',
                        body: JSON.stringify({ answers: this.selectedOptions })
                    });
                    if (response.ok) {
                        window.location.href = `/student/qcm/${this.qcmId}/result`;
                    } else {
                        alert('Erreur lors de la soumission.');
                    }
                } catch (e) {
                    console.error('Failed to submit QCM', e);
                    alert('Erreur réseau lors de la soumission.');
                } finally {
                    this.loading = false;
                }
            },
            startTimer() {
                if (this.timerMinutes === 0 && this.timerSeconds === 0 && this.qcm.durationMinutes === 0) {
                    return;
                }
                this.timerInterval = setInterval(async () => {
                    if (this.timerSeconds === 0) {
                        if (this.timerMinutes === 0) {
                            clearInterval(this.timerInterval);
                            await this.submitQcm();
                            return;
                        }
                        this.timerMinutes--;
                        this.timerSeconds = 59;
                    } else {
                        this.timerSeconds--;
                    }
                }, 1000);
            }
        }));
    });
</script>
@endsection

