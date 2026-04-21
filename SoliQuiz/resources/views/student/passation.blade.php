@extends('layouts.base')

@section('title', 'SoliQuiz - ' . $qcm->titre)

@section('body-class', 'bg-slate-50 min-h-screen font-sans antialiased')

@section('body')

@php
    // CRITICAL: Initialize ALL questions (radio = null, checkbox = [])
    // DB stores 'unique' and 'multiple' (storeQcm converts choix_* before saving)
    $initialAnswers = [];
    foreach($qcm->questions as $q) {
        if ($q->type === 'multiple') {
            $initialAnswers[$q->id] = [];
        } else {
            $initialAnswers[$q->id] = null; // null for single-choice
        }
    }
@endphp

<div x-data="qcmForm({{ $tempsRestant }}, {{ Js::from($initialAnswers) }})" x-init="startTimer()" class="min-h-screen flex flex-col">
    
    <!-- Sticky Header -->
    <header class="sticky top-0 z-50 bg-white border-b border-slate-200 shadow-sm">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between gap-4">
                <!-- Back & Title -->
                <div class="flex items-center gap-4 min-w-0 flex-1">
                    <button type="button"
                            @click="$dispatch('confirm', { 
                                title: 'Quitter le QCM ?', 
                                message: 'Votre progression actuelle ne sera pas sauvegardée.', 
                                onConfirm: () => window.location.href = '{{ route('student.bibliotheque') }}',
                                type: 'warning'
                            })"
                            class="shrink-0 size-10 flex items-center justify-center rounded-xl hover:bg-slate-100 transition-colors text-slate-500">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="m15 18-6-6 6-6"/>
                        </svg>
                    </button>
                    <div class="min-w-0">
                        <h1 class="text-lg font-bold text-slate-900 truncate">{{ $qcm->titre }}</h1>
                        <p class="text-xs text-slate-500 truncate">
                            {{ $qcm->uniteApprentissage ? $qcm->uniteApprentissage->nom : 'Évaluation' }}
                        </p>
                    </div>
                </div>

                <!-- Timer -->
                <div class="shrink-0 flex items-center gap-2 py-2 px-3 rounded-xl font-mono text-sm font-bold"
                     :class="timeRemaining <= 60 ? 'bg-rose-50 text-rose-600 border border-rose-200' : 'bg-slate-100 text-slate-700'">
                    <svg class="size-4" :class="timeRemaining <= 60 ? 'animate-pulse' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <span x-text="formattedTime"></span>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-4 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-primary-500 rounded-full transition-all duration-500"
                     :style="`width: ${completionPercentage}%`"></div>
            </div>
            <p class="mt-2 text-xs font-medium text-slate-400 text-center">
                <span x-text="answeredCount"></span> / {{ count($qcm->questions) }} questions répondues
            </p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 py-8 px-4 sm:px-6">
        <div class="max-w-3xl mx-auto space-y-6">
            
            <!-- QCM Header Card -->
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-[0_8px_30px_-4px_rgba(0,0,0,0.04)] p-8 md:p-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="size-10 bg-primary-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary-500/30">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M9 12h.01M15 12h.01M10 16c.5.3 1.2.5 2 .5s1.5-.2 2-.5M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2s10 4.477 10 10z"/>
                        </svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">QCM à compléter</span>
                </div>
                <h2 class="text-2xl md:text-3xl font-heading font-black text-slate-900 leading-tight mb-3">{{ $qcm->titre }}</h2>
                <div class="flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center gap-2 text-slate-500">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                        </svg>
                        <span class="font-medium">{{ $qcm->duree_minutes }} minutes</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-500">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M9 11l3 3L22 4m-2 6v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                        </svg>
                        <span class="font-medium">{{ count($qcm->questions) }} questions</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-500">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span class="font-medium">Seuil: {{ $qcm->score_reussite }}/20</span>
                    </div>
                </div>
            </div>

            <!-- Questions Form -->
            <form id="qcm-form" action="{{ route('student.qcm.submit', $qcm->id) }}" method="POST" class="space-y-6">
                @csrf

                @foreach($qcm->questions as $index => $question)
                @php $qId = (string) $question->id; @endphp
                <div class="bg-white rounded-[2.5rem] border-2 shadow-[0_8px_30px_-4px_rgba(0,0,0,0.04)] p-8 md:p-10 transition-all duration-300"
                     :class="isQuestionAnswered('{{ $qId }}') ? 'border-primary-200 shadow-[0_8px_30px_-4px_rgba(20,100,200,0.08)]' : 'border-slate-100'">
                    
                    <!-- Question Header -->
                    <div class="flex items-start gap-4 mb-6">
                        <div class="shrink-0 size-10 rounded-xl flex items-center justify-center font-black text-sm shadow-lg transition-colors duration-300"
                             :class="isQuestionAnswered('{{ $qId }}') ? 'bg-primary-500 text-white shadow-primary-500/20' : 'bg-slate-900 text-white shadow-slate-900/10'">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 pt-1">
                            <h3 class="text-lg md:text-xl font-bold text-slate-900 leading-relaxed">{{ $question->texte }}</h3>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-lg bg-slate-100 text-slate-600 text-[10px] font-black uppercase tracking-wider">
                                    @if($question->type === 'unique')
                                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
                                        Choix unique
                                    @else
                                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                                        Choix multiples
                                    @endif
                                </span>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">{{ $question->points }} point{{ $question->points > 1 ? 's' : '' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Options -->
                    <div class="space-y-3 pl-14">
                        @foreach($question->options as $option)
                        @php $oId = (string) $option->id; @endphp

                        @if($question->type === 'unique')
                        {{-- ================================ --}}
                        {{-- SINGLE CHOICE: Click-handler based --}}
                        {{-- ================================ --}}
                        <div class="group flex items-center gap-4 p-4 rounded-2xl border-2 cursor-pointer transition-all duration-200"
                             :class="isSelected('{{ $qId }}', '{{ $oId }}') 
                                 ? 'border-primary-500 bg-primary-50 shadow-sm' 
                                 : 'border-slate-100 hover:border-primary-200 hover:bg-primary-50/30'"
                             @click="selectAnswer('{{ $qId }}', '{{ $oId }}')">

                            <!-- Custom Radio -->
                            <div class="shrink-0 size-6 rounded-full border-2 flex items-center justify-center transition-all duration-200"
                                 :class="isSelected('{{ $qId }}', '{{ $oId }}') ? 'border-primary-500 bg-primary-500' : 'border-slate-300'">
                                <div class="size-2.5 rounded-full bg-white transition-transform duration-200"
                                     :class="isSelected('{{ $qId }}', '{{ $oId }}') ? 'scale-100' : 'scale-0'"></div>
                            </div>

                            <!-- Option Text -->
                            <span class="flex-1 font-medium transition-colors"
                                  :class="isSelected('{{ $qId }}', '{{ $oId }}') ? 'text-slate-900' : 'text-slate-700'">
                                {{ $option->texte }}
                            </span>
                        </div>

                        @else
                        {{-- ================================ --}}
                        {{-- MULTIPLE CHOICE: Click-handler based --}}
                        {{-- ================================ --}}
                        <div class="group flex items-center gap-4 p-4 rounded-2xl border-2 cursor-pointer transition-all duration-200"
                             :class="isSelected('{{ $qId }}', '{{ $oId }}') 
                                 ? 'border-primary-500 bg-primary-50 shadow-sm' 
                                 : 'border-slate-100 hover:border-primary-200 hover:bg-primary-50/30'"
                             @click="toggleAnswer('{{ $qId }}', '{{ $oId }}')">

                            <!-- Custom Checkbox -->
                            <div class="shrink-0 size-6 rounded-lg border-2 flex items-center justify-center transition-all duration-200"
                                 :class="isSelected('{{ $qId }}', '{{ $oId }}') ? 'border-primary-500 bg-primary-500' : 'border-slate-300'">
                                <svg class="size-4 text-white transition-transform duration-200"
                                     :class="isSelected('{{ $qId }}', '{{ $oId }}') ? 'scale-100' : 'scale-0'"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>

                            <!-- Option Text -->
                            <span class="flex-1 font-medium transition-colors"
                                  :class="isSelected('{{ $qId }}', '{{ $oId }}') ? 'text-slate-900' : 'text-slate-700'">
                                {{ $option->texte }}
                            </span>
                        </div>
                        @endif

                        @endforeach

                        {{-- ================================ --}}
                        {{-- HIDDEN INPUTS FOR FORM SUBMIT    --}}
                        {{-- Reactive, driven by Alpine state --}}
                        {{-- ================================ --}}
                        @if($question->type === 'unique')
                            <input type="hidden" 
                                   name="answers[{{ $question->id }}]"
                                   :value="answers['{{ $qId }}'] ?? ''">
                        @else
                            {{-- For checkboxes we generate one hidden input per selected option --}}
                            <template x-for="selectedId in (answers['{{ $qId }}'] || [])" :key="selectedId">
                                <input type="hidden" 
                                       name="answers[{{ $question->id }}][]"
                                       :value="selectedId">
                            </template>
                            {{-- Sentinel: ensures empty array sends something so controller knows --}}
                            <input type="hidden" name="answers[{{ $question->id }}][]" value=""
                                   x-show="false"
                                   x-bind:disabled="(answers['{{ $qId }}'] || []).length > 0">
                        @endif
                    </div>

                    <!-- Hint (only if provided by formateur) -->
                    @if($question->explication_feedback)
                    <div class="mt-6 pt-6 border-t border-slate-100 pl-14">
                        <div class="flex items-start gap-3 bg-amber-50 rounded-xl p-4 border border-amber-100">
                            <div class="shrink-0 size-6 bg-amber-500 rounded-lg flex items-center justify-center text-white">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-black text-amber-600 uppercase tracking-wider block mb-1">Indice</span>
                                <p class="text-sm text-amber-800">{{ $question->explication_feedback }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach

            </form>
        </div>
    </main>

    <!-- Sticky Submit Footer -->
    <footer class="sticky bottom-0 z-40 bg-white/80 backdrop-blur-xl border-t border-slate-200 p-4 sm:p-6">
        <div class="max-w-3xl mx-auto flex items-center justify-between gap-4">
            <div class="hidden sm:block">
                <p class="text-sm font-medium text-slate-600">
                    <span x-text="answeredCount"></span> / {{ count($qcm->questions) }} répondues
                </p>
                <p class="text-xs text-slate-400">Vous pouvez modifier vos réponses avant de soumettre</p>
            </div>
            
            <button type="button"
                    class="flex-1 sm:flex-none h-14 px-8 bg-slate-900 text-white rounded-2xl font-black text-sm uppercase tracking-[0.2em] hover:bg-primary-500 shadow-xl shadow-slate-900/10 transition-all active:scale-[0.98] flex items-center justify-center gap-3"
                    :class="answeredCount < {{ count($qcm->questions) }} ? 'opacity-80' : ''"
                    @click="submitForm()">
                <span>Soumettre le QCM</span>
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M5 13l4 4L19 7"/>
                </svg>
            </button>
        </div>
    </footer>

    <!-- Incomplete Warning Modal -->
    <x-ui.modal name="incomplete-warning" title="Attention" x-model:show="showWarning" maxWidth="md">
        <div class="text-center">
            <div class="size-16 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="size-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <p class="text-slate-500 mb-6">Vous n'avez pas répondu à toutes les questions. Êtes-vous sûr de vouloir soumettre ?</p>
            <div class="flex gap-3">
                <button type="button" @click="showWarning = false" class="flex-1 h-12 rounded-xl border-2 border-slate-100 font-bold text-slate-700 hover:bg-slate-50 transition-colors text-xs uppercase tracking-widest">
                    Continuer
                </button>
                <button type="button" @click="document.getElementById('qcm-form').submit()" class="flex-1 h-12 rounded-xl bg-slate-900 text-white font-bold hover:bg-primary-500 transition-colors text-xs uppercase tracking-widest">
                    Terminer
                </button>
            </div>
        </div>
    </x-ui.modal>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('qcmForm', (timeRemainingSeconds, initialAnswers = {}) => ({
                answers: initialAnswers,
                timeRemaining: timeRemainingSeconds,
                showWarning: false,
                totalQuestions: {{ count($qcm->questions) }},

                /**
                 * Select a single answer (radio behaviour).
                 * Replaces any previous selection for this question.
                 */
                selectAnswer(questionId, optionId) {
                    this.answers[questionId] = String(optionId);
                    // Force Alpine reactivity by re-assigning the whole object
                    this.answers = { ...this.answers };
                },

                /**
                 * Toggle one option in a multi-choice answer array.
                 */
                toggleAnswer(questionId, optionId) {
                    const current = this.answers[questionId] || [];
                    const strId = String(optionId);
                    const idx = current.map(String).indexOf(strId);
                    if (idx === -1) {
                        this.answers[questionId] = [...current, strId];
                    } else {
                        this.answers[questionId] = current.filter((_, i) => i !== idx);
                    }
                    this.answers = { ...this.answers };
                },

                /**
                 * Check if a given option is selected for a question.
                 */
                isSelected(questionId, optionId) {
                    const answer = this.answers[questionId];
                    if (answer === null || answer === undefined) return false;
                    if (Array.isArray(answer)) {
                        return answer.map(String).includes(String(optionId));
                    }
                    return String(answer) === String(optionId);
                },

                /**
                 * Check if a question has at least one answer.
                 */
                isQuestionAnswered(questionId) {
                    const answer = this.answers[questionId];
                    if (answer === null || answer === undefined || answer === '') return false;
                    if (Array.isArray(answer)) return answer.length > 0;
                    return true;
                },

                get answeredCount() {
                    return Object.keys(this.answers).filter(id => this.isQuestionAnswered(id)).length;
                },

                get completionPercentage() {
                    return this.totalQuestions > 0 ? (this.answeredCount / this.totalQuestions) * 100 : 0;
                },

                get formattedTime() {
                    const m = Math.floor(this.timeRemaining / 60);
                    const s = this.timeRemaining % 60;
                    return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                },

                startTimer() {
                    if (this.timeRemaining <= 0) return;
                    const interval = setInterval(() => {
                        this.timeRemaining--;
                        if (this.timeRemaining <= 0) {
                            clearInterval(interval);
                            document.getElementById('qcm-form').submit();
                        }
                    }, 1000);
                },

                submitForm() {
                    if (this.answeredCount < this.totalQuestions) {
                        this.showWarning = true;
                    } else {
                        document.getElementById('qcm-form').submit();
                    }
                }
            }));
        });

        // Prevent accidental navigation
        window.onbeforeunload = function() {
            return "Vous êtes en train de passer un QCM. Êtes-vous sûr de vouloir quitter cette page ?";
        };
    </script>
</div>
@endsection
