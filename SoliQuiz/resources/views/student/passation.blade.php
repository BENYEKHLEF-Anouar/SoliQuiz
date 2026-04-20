<!DOCTYPE html>
<html lang="fr" class="bg-gray-50 h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoliQuiz - Passation QCM - {{ $qcm->titre }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

@php
    $initialAnswers = [];
    foreach($qcm->questions as $q) {
        if ($q->type === 'choix_multiple') {
            $initialAnswers[$q->id] = [];
        }
    }
@endphp

<body class="text-slate-800 flex flex-col h-full overflow-hidden font-sans antialiased" 
      x-data="qcmPassation({{ count($qcm->questions) }}, {{ $qcm->duree_minutes }}, {{ Js::from($initialAnswers) }})" 
      x-init="startTimer()">

    <!-- Header Timer (Sticky) -->
    <header class="w-full bg-white border-b border-slate-200 shadow-sm shrink-0 z-50">
        <div class="max-w-3xl mx-auto px-4 py-4 md:py-5 flex justify-between items-center w-full">
            <div class="flex items-center gap-4">
                <a href="{{ route('student.bibliotheque') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </a>
                <h1 class="text-lg md:text-xl font-bold font-heading text-slate-900 tracking-tight">{{ $qcm->titre }}</h1>
                <span class="hidden md:inline-flex bg-slate-100 text-slate-600 py-1.5 px-3 rounded-full text-xs font-semibold" x-text="'Question ' + (currentQuestion + 1) + ' / ' + totalQuestions"></span>
            </div>

            <!-- Timer -->
            <div class="flex items-center gap-2 py-1.5 px-3 rounded-xl transition-colors" :class="timeRemaining <= 60 ? 'bg-semantic-error/10 border border-semantic-error/30 text-semantic-error' : 'bg-semantic-warning/10 border border-semantic-warning/30 text-semantic-warning'">
                <svg class="shrink-0 size-4 animate-pulse" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
                <span class="text-sm font-bold font-mono" x-text="formattedTime"></span>
            </div>
        </div>
        <!-- Progress Bar -->
        <div class="h-1 bg-slate-100 w-full" role="progressbar">
            <div class="flex flex-col justify-center rounded-r-full overflow-hidden bg-primary-500 h-1 transition-all duration-500" :style="`width: ${((currentQuestion + 1) / totalQuestions) * 100}%`"></div>
        </div>
    </header>

    <!-- Main Content (Scrollable) -->
    <main class="flex-1 overflow-y-auto w-full max-w-3xl mx-auto px-4 py-8 md:py-12">
        <form id="qcm-form" action="{{ route('student.qcm.submit', $qcm->id) }}" method="POST">
            @csrf

            @foreach($qcm->questions as $index => $question)
            <div x-show="currentQuestion === {{ $index }}" x-cloak class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8 shrink-0">
                <div class="md:hidden inline-flex mb-4 bg-slate-100 text-slate-600 py-1.5 px-3 rounded-full text-[10px] uppercase font-bold tracking-wider">
                    Question {{ $index + 1 }} / {{ count($qcm->questions) }}
                </div>

                <h2 class="text-xl md:text-2xl font-semibold text-slate-900 leading-relaxed mb-8">
                    {{ $question->texte }}
                    <span class="block text-sm font-normal text-slate-500 mt-2">{{ $question->type === 'choix_unique' ? '(Choix unique)' : '(Choix multiples)' }}</span>
                </h2>

                <div class="grid gap-3">
                    @foreach($question->options as $option)
                    <label class="flex p-4 w-full bg-white border border-slate-200 rounded-xl text-base focus-within:ring-2 focus-within:ring-primary-500 hover:bg-primary-50 cursor-pointer transition-colors shadow-sm"
                           :class="isSelected('{{ $question->id }}', '{{ $option->id }}') ? 'bg-primary-50 border-primary-500 ring-1 ring-primary-500' : ''">
                        <input type="{{ $question->type === 'choix_unique' ? 'radio' : 'checkbox' }}" 
                               name="answers[{{ $question->id }}]{{ $question->type === 'choix_multiple' ? '[]' : '' }}" 
                               value="{{ $option->id }}"
                               x-model="answers['{{ $question->id }}']"
                               class="shrink-0 mt-1 size-4 rounded-{{ $question->type === 'choix_unique' ? 'full' : 'md' }} border-slate-300 text-primary-500 focus:ring-primary-500">
                        <span class="font-medium ms-4" :class="isSelected('{{ $question->id }}', '{{ $option->id }}') ? 'text-primary-900' : 'text-slate-800'">
                            {{ $option->texte }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach

        </form>
    </main>

    <!-- Footer Navigation (Sticky Fixed) -->
    <footer class="w-full bg-white border-t border-slate-200 p-4 shrink-0 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <div class="max-w-3xl mx-auto flex items-center justify-between">
            <span class="text-xs text-slate-500 hidden sm:inline-block">Ne quittez pas cette page.</span>
            <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">
                <button type="button" x-show="currentQuestion > 0" @click="currentQuestion--" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm hover:bg-slate-50 transition-colors group">
                    <svg class="size-4 transition-transform group-hover:-translate-x-1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                    Précédent
                </button>
                
                <button type="button" x-show="currentQuestion < totalQuestions - 1" @click="currentQuestion++" class="py-2.5 px-6 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-xl border border-transparent bg-primary-500 text-white shadow-sm hover:bg-primary-600 hover:-translate-y-0.5 transition-all w-full sm:w-auto">
                    Suivant
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </button>

                <button type="submit" form="qcm-form" x-show="currentQuestion === totalQuestions - 1" class="py-2.5 px-6 inline-flex justify-center items-center gap-x-2 text-sm font-black rounded-xl border border-transparent bg-emerald-500 text-white shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 hover:-translate-y-0.5 transition-all w-full sm:w-auto uppercase tracking-wider">
                    Terminer le QCM
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7" /></svg>
                </button>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('qcmPassation', (total, durationMinutes, initialAnswers = {}) => ({
                currentQuestion: 0,
                totalQuestions: total,
                answers: initialAnswers,
                timeRemaining: durationMinutes * 60, // in seconds

                isSelected(questionId, optionId) {
                    if (!this.answers[questionId]) return false;
                    if (Array.isArray(this.answers[questionId])) {
                        return this.answers[questionId].map(String).includes(String(optionId));
                    }
                    return String(this.answers[questionId]) === String(optionId);
                },

                get formattedTime() {
                    const m = Math.floor(this.timeRemaining / 60);
                    const s = this.timeRemaining % 60;
                    return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                },

                startTimer() {
                    if(this.timeRemaining <= 0) return;
                    
                    const interval = setInterval(() => {
                        this.timeRemaining--;
                        if (this.timeRemaining <= 0) {
                            clearInterval(interval);
                            this.autoSubmit();
                        }
                    }, 1000);
                },

                autoSubmit() {
                    document.getElementById('qcm-form').submit();
                }
            }));
        });
    </script>
</body>
</html>
