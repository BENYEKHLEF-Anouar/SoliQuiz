@extends('layouts.base')

@section('title', 'SoliQuiz - ' . $qcm->titre)

@section('body-class', 'bg-slate-50 min-h-screen font-sans antialiased')

@section('body')

<div x-data="qcmForm({{ $tempsRestant }}, {{ json_encode($initialAnswers) }})" class="min-h-screen flex flex-col">
    
    <!-- Sticky Header -->
    <header class="sticky top-0 z-50 bg-white border-b border-slate-200 shadow-sm">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-4 min-w-0 flex-1">
                    <button type="button"
                            @click="$dispatch('confirm', { 
                                title: 'Quitter le QCM ?', 
                                message: 'Votre progression actuelle ne sera pas sauvegardée.', 
                                onConfirm: () => { window.onbeforeunload = null; window.location.href = '{{ route('student.bibliotheque') }}' },
                                type: 'warning'
                            })"
                            class="shrink-0 size-10 flex items-center justify-center rounded-xl hover:bg-slate-100 transition-colors text-slate-500">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="m15 18-6-6 6-6"/>
                        </svg>
                    </button>
                    <div class="min-w-0 flex items-center gap-3">
                        <h1 class="text-lg font-bold text-slate-900 truncate">{{ $qcm->titre }}</h1>
                        
                        <!-- Auto-save Badge -->
                        <div class="flex items-center gap-1.5 px-2 py-1 rounded-lg transition-all duration-300"
                             :class="isSaving ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600'">
                            <div class="size-1.5 rounded-full" 
                                 :class="isSaving ? 'bg-amber-500 animate-pulse' : 'bg-emerald-500'"></div>
                            <span class="text-[9px] font-black uppercase tracking-widest" 
                                  x-text="isSaving ? 'Sauvegarde...' : 'Enregistré'"></span>
                        </div>
                    </div>
                </div>

                <!-- Timer -->
                <div class="shrink-0 flex items-center gap-2 py-2 px-3 rounded-xl font-mono text-sm font-bold transition-colors duration-300"
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
    <main class="flex-1 py-5 px-4 sm:px-6">
        <div class="max-w-3xl mx-auto space-y-4">
            
            <!-- QCM Header Card -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 md:p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="size-10 bg-primary-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary-500/30">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M9 12h.01M15 12h.01M10 16c.5.3 1.2.5 2 .5s1.5-.2 2-.5M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2s10 4.477 10 10z"/>
                        </svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">QCM à compléter</span>
                </div>
                <h2 class="text-xl md:text-2xl font-heading font-black text-slate-900 leading-tight mb-3">{{ $qcm->titre }}</h2>
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
                </div>
            </div>

            <!-- Questions Form -->
            <form id="qcm-form" action="{{ route('student.qcm.submit', $qcm->id) }}" method="POST" class="space-y-6">
                @csrf
                @foreach($qcm->questions as $index => $question)
                @php $qId = (string) $question->id; @endphp
                <div class="bg-white rounded-2xl border-2 p-5 md:p-6 transition-all duration-300"
                     :class="isQuestionAnswered('{{ $qId }}') ? 'border-primary-200 shadow-sm' : 'border-slate-100 shadow-sm'">
                    
                    <div class="flex items-start gap-3 mb-4">
                        <div class="shrink-0 size-8 rounded-lg flex items-center justify-center font-black text-sm shadow-sm transition-colors duration-300"
                             :class="isQuestionAnswered('{{ $qId }}') ? 'bg-primary-500 text-white' : 'bg-slate-900 text-white'">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 pt-1">
                            <h3 class="text-base md:text-lg font-bold text-slate-900 leading-snug">{{ $question->texte }}</h3>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-lg bg-slate-100 text-slate-600 text-[10px] font-black uppercase tracking-wider">
                                    {{ $question->type === 'unique' ? 'Choix unique' : 'Choix multiples' }}
                                </span>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">{{ $question->points }} point{{ $question->points > 1 ? 's' : '' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2 pl-11">
                        @foreach($question->options as $option)
                        @php $oId = (string) $option->id; @endphp

                        <div class="group flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all duration-200"
                             :class="isSelected('{{ $qId }}', '{{ $oId }}') 
                                 ? 'border-primary-500 bg-primary-50 shadow-sm' 
                                 : 'border-slate-100 hover:border-primary-200 hover:bg-primary-50/30'"
                             @click="{{ $question->type === 'unique' ? 'selectAnswer' : 'toggleAnswer' }}('{{ $qId }}', '{{ $oId }}')">

                            <div class="shrink-0 size-6 {{ $question->type === 'unique' ? 'rounded-full' : 'rounded-lg' }} border-2 flex items-center justify-center transition-all duration-200"
                                 :class="isSelected('{{ $qId }}', '{{ $oId }}') ? 'border-primary-500 bg-primary-500' : 'border-slate-300'">
                                @if($question->type === 'unique')
                                    <div class="size-2.5 rounded-full bg-white transition-transform duration-200"
                                         :class="isSelected('{{ $qId }}', '{{ $oId }}') ? 'scale-100' : 'scale-0'"></div>
                                @else
                                    <svg class="size-4 text-white transition-transform duration-200"
                                         :class="isSelected('{{ $qId }}', '{{ $oId }}') ? 'scale-100' : 'scale-0'"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path d="M5 13l4 4L19 7"/>
                                    </svg>
                                @endif
                            </div>

                            <span class="flex-1 font-medium transition-colors"
                                  :class="isSelected('{{ $qId }}', '{{ $oId }}') ? 'text-slate-900' : 'text-slate-700'">
                                {{ $option->texte }}
                            </span>
                        </div>
                        @endforeach

                        @if($question->type === 'unique')
                            <input type="hidden" name="answers[{{ $question->id }}]" :value="answers['{{ $qId }}'] ?? ''">
                        @else
                            <template x-for="selectedId in (answers['{{ $qId }}'] || [])" :key="selectedId">
                                <input type="hidden" name="answers[{{ $question->id }}][]" :value="selectedId">
                            </template>
                        @endif
                    </div>
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
                    <span x-text="answeredCount"></span> / {{ count($qcm->questions) }} répondue{{ count($qcm->questions) > 1 ? 's' : '' }}
                </p>
                <p class="text-xs text-slate-400">Vos réponses sont enregistrées lors de la soumission</p>
            </div>
            
            <button type="button"
                    class="flex-1 sm:flex-none h-11 px-6 bg-slate-900 text-white rounded-xl font-black text-[11px] uppercase tracking-[0.2em] hover:bg-primary-500 shadow-xl shadow-slate-900/10 transition-all active:scale-[0.98] flex items-center justify-center gap-3"
                    @click="submitForm()">
                <span>Soumettre le QCM</span>
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path d="M5 13l4 4L19 7"/>
                </svg>
            </button>
        </div>
    </footer>

    <!-- Time Up Modal -->
    <x-ui.modal name="time-up" title="Temps Épuisé" maxWidth="md">
        <div class="text-center py-4">
            <div class="size-20 bg-rose-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-rose-500">
                <svg class="size-10 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-black text-slate-900 uppercase italic mb-2">Le temps est écoulé !</h3>
            <p class="text-slate-500 text-sm mb-8">Votre tentative va être soumise automatiquement avec les réponses déjà fournies.</p>
            <button type="button" @click="finalSubmit()" class="w-full h-14 rounded-2xl bg-slate-900 text-white font-black text-xs uppercase tracking-widest hover:bg-primary-500 transition-all">
                Voir mes résultats
            </button>
        </div>
    </x-ui.modal>

    <!-- Incomplete Warning Modal -->
    <x-ui.modal name="incomplete-warning" title="Attention" maxWidth="md">
        <div class="text-center py-4">
            <div class="size-16 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="size-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <p class="text-slate-500 mb-8 font-medium">Vous n'avez pas répondu à toutes les questions.<br>Soumettre quand même ?</p>
            <div class="flex gap-3">
                <button type="button" @click="$dispatch('close-modal', 'incomplete-warning')" class="flex-1 h-14 rounded-2xl border-2 border-slate-100 font-bold text-slate-700 hover:bg-slate-50 transition-colors text-[10px] uppercase tracking-widest">
                    Continuer
                </button>
                <button type="button" @click="finalSubmit()" class="flex-1 h-14 rounded-2xl bg-slate-900 text-white font-bold hover:bg-primary-500 transition-colors text-[10px] uppercase tracking-widest shadow-lg shadow-slate-900/10">
                    Terminer
                </button>
            </div>
        </div>
    </x-ui.modal>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('qcmForm', (timeRemainingSeconds, initialAnswers = {}) => ({
                answers: initialAnswers,
                isSaving: false,
                lastSaved: null,
                saveTimeout: null,
                timeRemaining: timeRemainingSeconds,
                isSubmitting: false,
                totalQuestions: {{ count($qcm->questions) }},

                init() {
                    this.startTimer();
                    
                    // Watchers pour l'auto-sauvegarde
                    this.$watch('answers', () => {
                        this.debouncedSave();
                    });
                },

                debouncedSave() {
                    clearTimeout(this.saveTimeout);
                    this.saveTimeout = setTimeout(() => {
                        this.persistAnswers();
                    }, 2000); // Sauvegarde après 2s d'inactivité
                },

                async persistAnswers() {
                    if (this.isSubmitting) return;
                    this.isSaving = true;
                    
                    try {
                        await fetch("{{ route('student.qcm.save', $qcm->id) }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ answers: this.answers })
                        });
                        this.lastSaved = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    } catch (e) {
                        console.error('Auto-save failed', e);
                    } finally {
                        this.isSaving = false;
                    }
                },

                selectAnswer(questionId, optionId) {
                    this.answers[questionId] = String(optionId);
                    this.answers = { ...this.answers };
                },

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

                isSelected(questionId, optionId) {
                    const answer = this.answers[questionId];
                    if (!answer) return false;
                    if (Array.isArray(answer)) return answer.map(String).includes(String(optionId));
                    return String(answer) === String(optionId);
                },

                isQuestionAnswered(questionId) {
                    const answer = this.answers[questionId];
                    if (!answer) return false;
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
                    const s = Math.floor(this.timeRemaining % 60);
                    return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                },

                startTimer() {
                    if (this.timeRemaining <= 0) {
                        this.onTimeUp();
                        return;
                    }
                    const interval = setInterval(() => {
                        if (this.isSubmitting) {
                            clearInterval(interval);
                            return;
                        }
                        this.timeRemaining--;
                        if (this.timeRemaining <= 0) {
                            clearInterval(interval);
                            this.onTimeUp();
                        }
                    }, 1000);
                },

                onTimeUp() {
                    this.$dispatch('open-modal', 'time-up');
                    // Auto-submit after 5 seconds if no action
                    setTimeout(() => {
                        if (!this.isSubmitting) this.finalSubmit();
                    }, 5000);
                },

                submitForm() {
                    if (this.answeredCount < this.totalQuestions) {
                        this.$dispatch('open-modal', 'incomplete-warning');
                    } else {
                        this.finalSubmit();
                    }
                },

                finalSubmit() {
                    if (this.isSubmitting) return;
                    this.isSubmitting = true;
                    window.onbeforeunload = null;
                    this.persistAnswers().then(() => {
                        document.getElementById('qcm-form').submit();
                    });
                }
            }));
        });

        window.onbeforeunload = function() {
            return "Attention : quitter cette page pourrait entraîner la perte de vos réponses non soumises.";
        };
    </script>
</div>
@endsection
