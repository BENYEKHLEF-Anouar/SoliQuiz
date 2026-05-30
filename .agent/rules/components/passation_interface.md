---
trigger: on_demand
type: rule
id: passation-interface
---

# ▶️ SOLIQUIZ PASSATION INTERFACE RULES

## Screen Layout

```
┌─────────────────────────────────────────────────────────────┐
│  SoliQuiz Logo    TITRE DU QCM           Timer: 14:32 🔋     │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  Question 3/10                                              │
│  ────────────────────────────────────────────────────────  │
│                                                             │
│  Quelle est la syntaxe correcte pour une migration Laravel? │
│                                                             │
│  ○ php artisan make:migration create_users_table            │
│  ● php artisan migrate:fresh                                │
│  ○ php artisan db:seed                                      │
│                                                             │
│  [Feedback spécifique s'affichera après validation]        │
│                                                             │
├─────────────────────────────────────────────────────────────┤
│  [← Précédent]              [Question 3/10]     [Suivant →]│
└─────────────────────────────────────────────────────────────┘
```

## Alpine.js State
```javascript
x-data={
    tentative: {...},
    currentQuestionIndex: 0,
    reponses: {}, // question_id -> selected_options[]
    timer: null,
    timeRemaining: 0,
    
    init() {
        this.startTimer();
    },
    
    selectOption(questionId, optionId) { /* ... */ },
    nextQuestion() { /* ... */ },
    previousQuestion() { /* ... */ },
    submitQcm() { /* ... */ },
    
    startTimer() {
        // Countdown logic
    }
}
```

## Timer Behavior
- Display: `MM:SS` format.
- Color: `text-slate-600` (> 5 min), `text-amber-500` (2-5 min), `text-rose-500` (< 2 min).
- Auto-submit when timer reaches 0.

## Navigation
- Previous: Disabled on first question.
- Next: Disabled on last question (shows "Terminer").
- Progress indicator: `Question X/Y` or progress bar.

## Answer Selection
```html
<!-- Unique choice -->
<div @click="selectOption(q.id, opt.id)" 
     :class="{ 'border-indigo-500 bg-indigo-50': isSelected(q.id, opt.id) }"
     class="border rounded-lg p-4 cursor-pointer hover:bg-slate-50">
    <div class="flex items-center gap-3">
        <div class="w-5 h-5 rounded-full border"
             :class="{ 'bg-indigo-500 border-indigo-500': isSelected(q.id, opt.id) }"></div>
        <span x-text="opt.texte"></span>
    </div>
</div>
```

## Auto-save
- Save responses to `localStorage` every 30 seconds.
- Restore on page reload (if attempt still `en_cours`).
