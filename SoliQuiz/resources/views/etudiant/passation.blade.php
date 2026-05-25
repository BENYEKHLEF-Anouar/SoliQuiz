@extends('layouts.base')

@section('title', 'SoliQuiz - ' . $qcm->titre)

@section('body-class', 'bg-slate-50 min-h-screen font-sans antialiased')

@section('body')

<div x-data="qcmForm({{ $tempsRestant }}, {{ json_encode($initialAnswers) }}, {
    totalQuestions: {{ count($qcm->questions) }},
    saveUrl: '{{ route('etudiant.qcm.save', $qcm->id) }}',
    csrfToken: '{{ csrf_token() }}'
})" class="min-h-screen flex flex-col">
    
    <!-- Sticky Header -->
    <header class="sticky top-0 z-50 bg-white border-b border-slate-200 shadow-sm">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 py-2.5">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-4 min-w-0 flex-1">
                    <button type="button"
                            @click="$dispatch('confirm', { 
                                title: 'Suspendre l\'évaluation ?', 
                                message: 'Vos réponses sont enregistrées automatiquement. Vous pourrez reprendre ce QCM plus tard, mais le chronomètre continuera de s\'écouler.', 
                                onConfirm: () => { window.onbeforeunload = null; window.location.href = '{{ route('etudiant.bibliotheque') }}' },
                                type: 'warning'
                            })"
                            class="shrink-0 size-8 flex items-center justify-center rounded-lg hover:bg-slate-100 transition-colors text-slate-500">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="m15 18-6-6 6-6"/>
                        </svg>
                    </button>
                    <div class="min-w-0 flex items-center gap-3">
                        <h1 class="text-base font-bold text-slate-900 truncate">{{ $qcm->titre }}</h1>
                        
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
                     :class="timeRemaining < 0 ? 'bg-slate-100 text-slate-700' : (timeRemaining <= 60 ? 'bg-rose-50 text-rose-600 border border-rose-200' : 'bg-slate-100 text-slate-700')">
                    <svg class="size-4" :class="timeRemaining > 0 && timeRemaining <= 60 ? 'animate-pulse' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <span x-text="formattedTime"></span>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-2.5 h-1 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-primary-500 rounded-full transition-all duration-500"
                     :style="`width: ${completionPercentage}%`"></div>
            </div>
            <p class="mt-1.5 text-[11px] font-medium text-slate-400 text-center">
                <span x-text="answeredCount"></span> / {{ count($qcm->questions) }} questions répondues
            </p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 py-3 px-4 sm:px-6">
        <div class="max-w-3xl mx-auto space-y-4">
            
            <!-- QCM Header Card -->
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="size-8 bg-primary-500 rounded-lg flex items-center justify-center text-white shadow-md shadow-primary-500/20">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path d="M9 12h.01M15 12h.01M10 16c.5.3 1.2.5 2 .5s1.5-.2 2-.5M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2s10 4.477 10 10z"/>
                        </svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">QCM à compléter</span>
                </div>
                <h2 class="text-lg md:text-xl font-heading font-black text-slate-900 leading-tight mb-2">{{ $qcm->titre }}</h2>
                <div class="flex flex-wrap gap-3 text-xs">
                    <div class="flex items-center gap-2 text-slate-500">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                        </svg>
                        <span class="font-medium">{{ $qcm->duree_minutes > 0 ? $qcm->duree_minutes . ' minutes' : 'Temps illimité' }}</span>
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
            <form id="qcm-form" action="{{ route('etudiant.qcm.submit', $qcm->id) }}" method="POST" class="space-y-4">
                @csrf
                @foreach($qcm->questions as $index => $question)
                @php $qId = (string) $question->id; @endphp
                <div class="bg-white rounded-xl border-2 p-4 transition-all duration-300"
                     :class="isQuestionAnswered('{{ $qId }}') ? 'border-primary-200 shadow-sm' : 'border-slate-100 shadow-sm'">
                    
                    <div class="flex items-start gap-2.5 mb-3">
                        <div class="shrink-0 size-6 rounded-md flex items-center justify-center font-black text-xs shadow-sm transition-colors duration-300"
                             :class="isQuestionAnswered('{{ $qId }}') ? 'bg-primary-500 text-white' : 'bg-slate-900 text-white'">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 pt-0.5">
                            <h3 class="text-sm md:text-base font-bold text-slate-900 leading-snug">{{ $question->texte }}</h3>
                            <div class="flex items-center gap-3 mt-1.5">
                                <span class="inline-flex items-center gap-1.5 py-0.5 px-2 rounded bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-wider">
                                    {{ $question->type === 'unique' ? 'Choix unique' : 'Choix multiples' }}
                                </span>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider">{{ $question->points }} point{{ $question->points > 1 ? 's' : '' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5 pl-8">
                        @foreach($question->options as $option)
                        @php $oId = (string) $option->id; @endphp

                        <div class="group flex items-center gap-2.5 p-2 rounded-lg border-2 cursor-pointer transition-all duration-200"
                             :class="isSelected('{{ $qId }}', '{{ $oId }}') 
                                 ? 'border-primary-500 bg-primary-50 shadow-sm' 
                                 : 'border-slate-100 hover:border-primary-200 hover:bg-primary-50/30'"
                             @click="{{ $question->type === 'unique' ? 'selectAnswer' : 'toggleAnswer' }}('{{ $qId }}', '{{ $oId }}')">

                            <div class="shrink-0 size-5 {{ $question->type === 'unique' ? 'rounded-full' : 'rounded-md' }} border-2 flex items-center justify-center transition-all duration-200"
                                 :class="isSelected('{{ $qId }}', '{{ $oId }}') ? 'border-primary-500 bg-primary-500' : 'border-slate-300'">
                                @if($question->type === 'unique')
                                    <div class="size-2 rounded-full bg-white transition-transform duration-200"
                                         :class="isSelected('{{ $qId }}', '{{ $oId }}') ? 'scale-100' : 'scale-0'"></div>
                                @else
                                    <svg class="size-3 text-white transition-transform duration-200"
                                         :class="isSelected('{{ $qId }}', '{{ $oId }}') ? 'scale-100' : 'scale-0'"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path d="M5 13l4 4L19 7"/>
                                    </svg>
                                @endif
                            </div>

                            <span class="flex-1 text-sm font-medium transition-colors"
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

                    <!-- Clear Selection Button -->
                    <div class="flex justify-end mt-5 mb-1 pr-2">
                        <button type="button"
                                x-show="isQuestionAnswered('{{ $qId }}')"
                                x-cloak
                                @click="clearQuestionAnswer('{{ $qId }}')"
                                class="inline-flex items-center gap-1.5 py-1 px-3 rounded-lg text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 hover:text-rose-700 active:scale-95 transition-all outline-none">
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Effacer la sélection
                        </button>
                    </div>
                </div>
                @endforeach
            </form>
        </div>
    </main>

    <!-- Sticky Submit Footer -->
    <footer class="sticky bottom-0 z-40 bg-white/80 backdrop-blur-xl border-t border-slate-200 p-3 sm:p-4">
        <div class="max-w-3xl mx-auto flex items-center justify-between gap-4">
            <div class="hidden sm:block">
                <p class="text-xs font-medium text-slate-600">
                    <span x-text="answeredCount"></span> / {{ count($qcm->questions) }} répondue{{ count($qcm->questions) > 1 ? 's' : '' }}
                </p>
                <p class="text-[10px] text-slate-400">Vos réponses sont enregistrées lors de la soumission</p>
            </div>
            
            <button type="button"
                    class="flex-1 sm:flex-none h-9 px-5 bg-slate-900 text-white rounded-lg font-black text-[10px] uppercase tracking-[0.2em] hover:bg-primary-500 shadow-md shadow-slate-900/10 transition-all active:scale-[0.98] flex items-center justify-center gap-2"
                    @click="submitForm()">
                <span>Soumettre le QCM</span>
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
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
            <h3 class="text-xl font-black text-slate-900 uppercase mb-2">Le temps est écoulé !</h3>
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
</div>
@endsection
