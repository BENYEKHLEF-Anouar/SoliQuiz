---
description: Workflow for building QCM Taking Interface (Passation)
trigger: /student-passation
---

# ▶️ Student Passation Workflow

## Command
`/student-passation`

## Dependencies
- **Skill:** `passation-service`
- **Rules:** `rules/components/passation_interface.md`

## Execution Steps

### 1. Controller Methods
**File:** `app/Http/Controllers/Web/StudentController.php`

```php
public function passation(QcmPublicService $qcmService, PassationService $passationService, int $qcmId): View
{
    $student = auth()->user();
    $qcm = $qcmService->getQcmForPassation($qcmId);
    
    // Start or resume attempt
    $tentative = $passationService->demarrer($qcm, $student);
    
    return view('student.passation', [
        'qcm' => $qcm,
        'tentative' => $tentative,
        'questions' => $qcm->questions()->with('options')->get(),
        'timeRemaining' => $tentative->getTimeRemaining(),
    ]);
}

public function submitQcm(Request $request, PassationService $service, int $qcmId): RedirectResponse
{
    $student = auth()->user();
    $tentative = Tentative::where('user_id', $student->id)
        ->where('qcm_id', $qcmId)
        ->where('statut', 'en_cours')
        ->firstOrFail();
    
    $reponses = $request->input('reponses', []);
    $service->soumettre($tentative, $reponses);
    
    return redirect()->route('student.resultats', $qcmId)
        ->with('success', 'QCM soumis avec succès');
}
```

### 2. View
**File:** `resources/views/student/passation.blade.php`

**Required elements:**
- Header with QCM title and timer.
- Progress indicator.
- Question card with options.
- Navigation (Previous/Next).
- Submit button (last question).
- Auto-save indicator.

### 3. Alpine.js State
```javascript
x-data={
    qcm: @json($qcm),
    tentative: @json($tentative),
    questions: @json($questions),
    currentIndex: 0,
    reponses: {}, // { questionId: [selectedOptionIds] }
    timeRemaining: {{ $timeRemaining }},
    timerInterval: null,
    autoSaveInterval: null,
    saving: false,
    
    init() {
        // Restore saved responses
        const saved = localStorage.getItem(`tentative_${this.tentative.id}`);
        if (saved) {
            this.reponses = JSON.parse(saved);
        }
        
        // Start timer
        this.startTimer();
        
        // Auto-save every 30s
        this.autoSaveInterval = setInterval(() => this.saveResponses(), 30000);
    },
    
    get currentQuestion() {
        return this.questions[this.currentIndex];
    },
    
    get progress() {
        return ((this.currentIndex + 1) / this.questions.length) * 100;
    },
    
    get isFirst() {
        return this.currentIndex === 0;
    },
    
    get isLast() {
        return this.currentIndex === this.questions.length - 1;
    },
    
    get allAnswered() {
        return this.questions.every(q => this.reponses[q.id]?.length > 0);
    },
    
    get answeredCount() {
        return this.questions.filter(q => this.reponses[q.id]?.length > 0).length;
    },
    
    getTimerColor() {
        if (this.timeRemaining > 300) return 'text-slate-600'; // > 5 min
        if (this.timeRemaining > 120) return 'text-amber-500'; // > 2 min
        return 'text-rose-500 animate-pulse'; // < 2 min
    },
    
    formatTime(seconds) {
        const m = Math.floor(seconds / 60);
        const s = seconds % 60;
        return `${m}:${s.toString().padStart(2, '0')}`;
    },
    
    startTimer() {
        this.timerInterval = setInterval(() => {
            this.timeRemaining--;
            if (this.timeRemaining <= 0) {
                this.submitQcm();
            }
        }, 1000);
    },
    
    toggleOption(questionId, optionId, type) {
        if (!this.reponses[questionId]) {
            this.reponses[questionId] = [];
        }
        
        const idx = this.reponses[questionId].indexOf(optionId);
        
        if (type === 'unique') {
            // Single choice: replace selection
            this.reponses[questionId] = [optionId];
        } else {
            // Multiple choice: toggle
            if (idx > -1) {
                this.reponses[questionId].splice(idx, 1);
            } else {
                this.reponses[questionId].push(optionId);
            }
        }
        
        this.saveResponses();
    },
    
    isSelected(questionId, optionId) {
        return this.reponses[questionId]?.includes(optionId);
    },
    
    saveResponses() {
        this.saving = true;
        localStorage.setItem(`tentative_${this.tentative.id}`, JSON.stringify(this.reponses));
        setTimeout(() => this.saving = false, 500);
    },
    
    previous() {
        if (!this.isFirst) this.currentIndex--;
    },
    
    next() {
        if (!this.isLast) this.currentIndex++;
    },
    
    async submitQcm() {
        clearInterval(this.timerInterval);
        clearInterval(this.autoSaveInterval);
        
        // Submit form
        document.getElementById('passation-form').submit();
    }
}
```

### 4. Passation Template
```html
@extends('layouts.app')

@section('content')
<div x-data="passationData()" class="max-w-3xl mx-auto" x-init="init()">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4 mb-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="font-semibold text-lg" x-text="qcm.titre"></h1>
                <p class="text-sm text-slate-500">
                    Question <span x-text="currentIndex + 1"></span> / <span x-text="questions.length"></span>
                </p>
            </div>
            <div :class="getTimerColor()" class="text-2xl font-mono font-bold">
                <span x-text="formatTime(timeRemaining)"></span>
            </div>
        </div>
        
        <!-- Progress Bar -->
        <div class="mt-3 h-2 bg-slate-200 rounded-full overflow-hidden">
            <div :style="`width: ${progress}%`" class="h-full bg-indigo-600 transition-all"></div>
        </div>
        <p class="text-xs text-slate-500 mt-1">
            <span x-text="answeredCount"></span> / <span x-text="questions.length"></span> répondues
            <span x-show="saving" class="text-amber-500 ml-2">Sauvegarde...</span>
        </p>
    </div>
    
    <!-- Question Card -->
    <form id="passation-form" method="POST" action="{{ route('student.qcm.submit', $qcm->id) }}" 
          class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
        @csrf
        
        <div x-show="currentQuestion">
            <p class="text-lg font-medium mb-4" x-text="currentQuestion.enonce"></p>
            <p class="text-sm text-slate-500 mb-4">
                <span x-show="currentQuestion.type === 'multiple'">(Plusieurs réponses possibles)</span>
                <span x-show="currentQuestion.type === 'unique'">(Une seule réponse)</span>
                • <span x-text="currentQuestion.points"></span> point(s)
            </p>
            
            <div class="space-y-3">
                <template x-for="option in currentQuestion.options" :key="option.id">
                    <div @click="toggleOption(currentQuestion.id, option.id, currentQuestion.type)"
                         :class="{ 
                             'border-indigo-500 bg-indigo-50': isSelected(currentQuestion.id, option.id),
                             'border-slate-200 hover:bg-slate-50': !isSelected(currentQuestion.id, option.id)
                         }"
                         class="border rounded-lg p-4 cursor-pointer transition-colors">
                        <div class="flex items-start gap-3">
                            <div :class="{
                                'w-5 h-5 rounded-full border-2 flex items-center justify-center': currentQuestion.type === 'unique',
                                'w-5 h-5 rounded border-2 flex items-center justify-center': currentQuestion.type === 'multiple'
                            }">
                                <div x-show="isSelected(currentQuestion.id, option.id)" 
                                     :class="{
                                         'w-2.5 h-2.5 rounded-full bg-indigo-600': currentQuestion.type === 'unique',
                                         'w-3 h-3 rounded-sm bg-indigo-600': currentQuestion.type === 'multiple'
                                     }"></div>
                            </div>
                            <span x-text="option.texte"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
        
        <!-- Hidden inputs for responses -->
        <template x-for="(questionId, options) in reponses" :key="questionId">
            <template x-for="optionId in options" :key="optionId">
                <input type="hidden" :name="`reponses[${questionId}][]`" :value="optionId">
            </template>
        </template>
        
        <!-- Navigation -->
        <div class="flex justify-between mt-6 pt-6 border-t">
            <button type="button" @click="previous" :disabled="isFirst"
                    class="px-4 py-2 border rounded-lg disabled:opacity-50">
                ← Précédent
            </button>
            
            <button type="button" x-show="!isLast" @click="next"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg">
                Suivant →
            </button>
            
            <button type="button" x-show="isLast" @click="submitQcm"
                    :disabled="!allAnswered"
                    class="px-6 py-2 bg-emerald-500 text-white rounded-lg disabled:opacity-50">
                Terminer ✓
            </button>
        </div>
    </form>
</div>
@endsection
```

## Validation Checklist
- [ ] Timer counting down correctly.
- [ ] Auto-save to localStorage every 30s.
- [ ] Question navigation working.
- [ ] Single/multiple choice toggle.
- [ ] Visual feedback for selected options.
- [ ] Progress indicator updating.
- [ ] Submit button on last question.
- [ ] Auto-submit when time expires.
- [ ] All answers preserved on submit.

**Trace:** `Student Passation Workflow executed`
