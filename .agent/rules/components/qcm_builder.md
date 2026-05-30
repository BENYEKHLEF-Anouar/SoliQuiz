---
trigger: on_demand
type: rule
id: qcm-builder
---

# 📝 SOLIQUIZ QCM BUILDER COMPONENT RULES

## Architecture
Multi-step wizard for QCM creation:
1. **Step 1:** QCM metadata (titre, durée, seuil, compétence, classe).
2. **Step 2:** Questions & Options (dynamic add/remove).
3. **Step 3:** Review & Publish.

## Alpine.js State
```javascript
x-data={
    step: 1,
    qcm: {
        titre: '',
        duree_minutes: 15,
        seuil_reussite: 70,
        competence_id: null,
        classe_id: null
    },
    questions: [],
    addQuestion() { /* ... */ },
    removeQuestion(index) { /* ... */ },
    addOption(questionIndex) { /* ... */ },
    removeOption(questionIndex, optionIndex) { /* ... */ }
}
```

## Question Types

### Unique Choice (QCU)
- Radio buttons for options.
- Only one `est_correcte` per question.

### Multiple Choice (QCM)
- Checkboxes for options.
- Multiple `est_correcte` allowed.
- Scoring: All correct must be selected, no incorrect selected.

## Validation Rules
- Minimum 1 question per QCM.
- Minimum 2 options per question.
- Exactly one correct answer for QCU.
- At least one correct answer for QCM.
- Feedback optional but recommended.

## UI Patterns
```html
<!-- Question Card -->
<div class="border rounded-lg p-4 mb-4" :class="{ 'border-indigo-500': active }">
    <input x-model="question.enonce" placeholder="Énoncé de la question...">
    
    <!-- Options -->
    <template x-for="(option, idx) in question.options">
        <div class="flex items-center gap-2 mt-2">
            <input type="checkbox" x-model="option.est_correcte">
            <input x-model="option.texte" placeholder="Texte de l'option...">
            <input x-model="option.feedback" placeholder="Feedback (optionnel)...">
            <button @click="removeOption(...)">×</button>
        </div>
    </template>
    
    <button @click="addOption(...)">+ Ajouter une option</button>
</div>
```
