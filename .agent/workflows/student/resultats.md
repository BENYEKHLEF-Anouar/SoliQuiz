---
description: Workflow for building Student Results Display
trigger: /student-resultats
---

# 🏆 Student Résultats Workflow

## Command
`/student-resultats`

## Dependencies
- **Skill:** `passation-service`
- **Rules:** `rules/visual/identity.md`

## Execution Steps

### 1. Controller Method
**File:** `app/Http/Controllers/Web/StudentController.php`

```php
public function resultats(int $qcmId): View
{
    $student = auth()->user();
    
    $tentative = Tentative::where('user_id', $student->id)
        ->where('qcm_id', $qcmId)
        ->where('statut', 'termine')
        ->with(['qcm.questions.options', 'reponses'])
        ->latest()
        ->firstOrFail();
    
    return view('student.resultats', compact('tentative'));
}
```

### 2. View
**File:** `resources/views/student/resultats.blade.php`

**Required elements:**
- Score gauge/card with color coding.
- Pass/Fail status.
- Time spent.
- Per-question breakdown with:
  - Correct answers highlighted (green).
  - Incorrect selections marked (red).
  - Feedback displayed.
- "Retour" button to bibliotheque.
- "Recommencer" option (if allowed).

### 3. Alpine.js State
```javascript
x-data={
    tentative: @json($tentative),
    
    get score() {
        return this.tentative.score_obtenu;
    },
    
    get isPassed() {
        return this.score >= this.tentative.qcm.seuil_reussite;
    },
    
    get scoreColor() {
        if (this.score >= 70) return 'text-emerald-500';
        if (this.score >= 50) return 'text-amber-500';
        return 'text-rose-500';
    },
    
    get scoreBg() {
        if (this.score >= 70) return 'bg-emerald-500';
        if (this.score >= 50) return 'bg-amber-500';
        return 'bg-rose-500';
    },
    
    isCorrectOption(question, option) {
        return option.est_correcte;
    },
    
    wasSelected(question, option) {
        return this.tentative.reponses.some(r => 
            r.question_id === question.id && 
            r.choix.some(c => c.option_id === option.id)
        );
    },
    
    getOptionClass(question, option) {
        const isCorrect = this.isCorrectOption(question, option);
        const wasSelected = this.wasSelected(question, option);
        
        if (isCorrect && wasSelected) return 'bg-emerald-100 border-emerald-500 text-emerald-800';
        if (isCorrect && !wasSelected) return 'bg-emerald-50 border-emerald-300 text-emerald-700';
        if (!isCorrect && wasSelected) return 'bg-rose-100 border-rose-500 text-rose-800';
        return 'bg-slate-50 border-slate-200 text-slate-600';
    },
    
    formatTime(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString('fr-FR');
    },
    
    getTimeSpent() {
        const debut = new Date(this.tentative.date_debut);
        const fin = new Date(this.tentative.date_fin);
        const diff = Math.round((fin - debut) / 1000 / 60);
        return diff + ' min';
    }
}
```

### 4. Results Template
```html
@extends('layouts.app')

@section('content')
<div x-data="resultatsData()" class="max-w-3xl mx-auto space-y-6">
    <!-- Score Card -->
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-8 text-center">
        <h1 class="text-xl font-semibold mb-2" x-text="tentative.qcm.titre"></h1>
        <p class="text-sm text-slate-500 mb-6" x-text="tentative.qcm.competence?.libelle"></p>
        
        <!-- Score Circle -->
        <div class="relative inline-flex items-center justify-center w-32 h-32 rounded-full border-4 mb-4"
             :class="isPassed ? 'border-emerald-500' : 'border-rose-500'">
            <div class="text-center">
                <p class="text-4xl font-bold" :class="scoreColor" x-text="score + '%'"></p>
                <p class="text-sm text-slate-500">Score obtenu</p>
            </div>
        </div>
        
        <!-- Pass/Fail Badge -->
        <div class="mb-4">
            <span :class="isPassed ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'"
                  class="px-4 py-2 rounded-full font-medium"
                  x-text="isPassed ? '✓ Réussi' : '✗ Non réussi'"></span>
        </div>
        
        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-slate-500">Seuil</p>
                <p class="font-medium" x-text="tentative.qcm.seuil_reussite + '%'"></p>
            </div>
            <div>
                <p class="text-slate-500">Temps</p>
                <p class="font-medium" x-text="getTimeSpent()"></p>
            </div>
            <div>
                <p class="text-slate-500">Date</p>
                <p class="font-medium" x-text="formatTime(tentative.date_fin)"></p>
            </div>
        </div>
    </div>
    
    <!-- Questions Breakdown -->
    <div class="space-y-4">
        <h2 class="font-semibold text-lg">Détail des réponses</h2>
        
        <template x-for="(question, idx) in tentative.qcm.questions" :key="question.id">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <p class="font-medium mb-1">
                    <span x-text="idx + 1"></span>. <span x-text="question.enonce"></span>
                    <span class="text-sm text-slate-500 font-normal">(<span x-text="question.points"></span> pts)</span>
                </p>
                
                <div class="space-y-2 mt-3">
                    <template x-for="option in question.options" :key="option.id">
                        <div :class="getOptionClass(question, option)"
                             class="border rounded-lg p-3 text-sm">
                            <div class="flex items-start gap-2">
                                <span x-show="isCorrectOption(question, option)">✓</span>
                                <span x-show="wasSelected(question, option) && !isCorrectOption(question, option)">✗</span>
                                <span x-text="option.texte"></span>
                            </div>
                            
                            <!-- Feedback -->
                            <p x-show="option.feedback && wasSelected(question, option)" 
                               class="mt-2 text-xs italic border-t pt-2"
                               :class="isCorrectOption(question, option) ? 'border-emerald-200' : 'border-rose-200'"
                               x-text="'💡 ' + option.feedback"></p>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>
    
    <!-- Actions -->
    <div class="flex justify-between">
        <a href="{{ route('student.bibliotheque') }}" 
           class="px-4 py-2 border rounded-lg hover:bg-slate-50">
            ← Retour à la bibliothèque
        </a>
        <a :href="`/student/qcm/${tentative.qcm.id}`" 
           class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            Refaire le QCM ↻
        </a>
    </div>
</div>
@endsection
```

## Validation Checklist
- [ ] Score displayed prominently with color.
- [ ] Pass/Fail badge correct.
- [ ] Stats (seuil, temps, date) showing.
- [ ] Per-question breakdown:
  - [ ] Correct answers highlighted green.
  - [ ] Wrong selections marked red.
  - [ ] Feedback displayed.
- [ ] Back button working.
- [ ] Retry option available.
- [ ] Responsive layout.

**Trace:** `Student Résultats Workflow executed`
