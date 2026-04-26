---
description: Workflow for building QCM Creation Wizard
trigger: /formateur-create-qcm
---

# ✏️ Formateur Création QCM Workflow

## Command
`/formateur-create-qcm`

## Dependencies
- **Skill:** `qcm-engine`
- **Rules:** `rules/components/qcm_builder.md`

## Execution Steps

### 1. Controller Methods
**File:** `app/Http/Controllers/Web/FormateurController.php`

```php
public function createQcm(SeanceService $seanceService, ClasseService $classeService): View
{
    // Full pedagogical tree for competence selection
    $pedagogie = $seanceService->getFullTree();
    
    // Formateur's classes for assignment
    $classes = $classeService->getClassesForFormateur(auth()->id());
    
    return view('formateur.creation-qcm', compact('pedagogie', 'classes'));
}

public function storeQcm(Request $request, QcmService $service): RedirectResponse
{
    $validated = $request->validate([
        'titre' => 'required|string|max:255',
        'duree_minutes' => 'required|integer|min:5|max:180',
        'seuil_reussite' => 'required|integer|min:0|max:100',
        'competence_id' => 'required|exists:competences,id',
        'classe_id' => 'required|exists:classes,id',
        'questions' => 'required|array|min:1',
        'questions.*.enonce' => 'required|string',
        'questions.*.type' => 'required|in:unique,multiple',
        'questions.*.points' => 'required|integer|min:1',
        'questions.*.options' => 'required|array|min:2',
        'questions.*.options.*.texte' => 'required|string',
        'questions.*.options.*.est_correcte' => 'boolean',
    ]);
    
    $service->createQcm($validated);
    
    return redirect()->route('formateur.bibliotheque')
        ->with('success', 'QCM créé avec succès');
}
```

### 2. View
**File:** `resources/views/formateur/creation-qcm.blade.php`

**Required elements:**
- Step indicator (1. Infos → 2. Questions → 3. Récap).
- Step 1: Metadata form (titre, durée, seuil, compétence, classe).
- Step 2: Dynamic question builder.
- Step 3: Review before submit.

### 3. Alpine.js State
```javascript
x-data={
    step: 1,
    qcm: {
        titre: '',
        duree_minutes: 15,
        seuil_reussite: 70,
        competence_id: '',
        classe_id: ''
    },
    questions: [],
    pedagogie: @json($pedagogie),
    classes: @json($classes),
    
    addQuestion() {
        this.questions.push({
            enonce: '',
            type: 'unique',
            points: 1,
            options: [
                { texte: '', est_correcte: false, feedback: '' },
                { texte: '', est_correcte: false, feedback: '' }
            ]
        });
    },
    
    removeQuestion(index) {
        this.questions.splice(index, 1);
    },
    
    addOption(questionIndex) {
        this.questions[questionIndex].options.push({
            texte: '',
            est_correcte: false,
            feedback: ''
        });
    },
    
    removeOption(qIdx, oIdx) {
        this.questions[qIdx].options.splice(oIdx, 1);
    },
    
    // For unique choice, ensure only one correct option
    setCorrectOption(qIdx, oIdx) {
        if (this.questions[qIdx].type === 'unique') {
            this.questions[qIdx].options.forEach((opt, idx) => {
                opt.est_correcte = idx === oIdx;
            });
        }
    },
    
    canProceed() {
        if (this.step === 1) {
            return this.qcm.titre && this.qcm.competence_id && this.qcm.classe_id;
        }
        if (this.step === 2) {
            return this.questions.length > 0 && 
                   this.questions.every(q => q.enonce && q.options.length >= 2 && 
                   q.options.some(o => o.est_correcte));
        }
        return true;
    },
    
    nextStep() {
        if (this.canProceed() && this.step < 3) this.step++;
    },
    
    prevStep() {
        if (this.step > 1) this.step--;
    }
}
```

### 4. Question Builder Template
```html
<!-- Step 2: Questions -->
<div x-show="step === 2" class="space-y-4">
    <div class="flex justify-between items-center">
        <h2 class="text-lg font-semibold">Questions (<span x-text="questions.length"></span>)</h2>
        <button @click="addQuestion()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">
            + Ajouter une question
        </button>
    </div>
    
    <template x-for="(q, qIdx) in questions" :key="qIdx">
        <div class="border rounded-lg p-4 space-y-3">
            <div class="flex justify-between">
                <span class="font-medium">Question <span x-text="qIdx + 1"></span></span>
                <button @click="removeQuestion(qIdx)" class="text-rose-500">Supprimer</button>
            </div>
            
            <input x-model="q.enonce" placeholder="Énoncé de la question..."
                   class="w-full border rounded-lg px-3 py-2">
            
            <div class="flex gap-4">
                <select x-model="q.type" class="border rounded-lg px-3 py-2">
                    <option value="unique">Choix unique</option>
                    <option value="multiple">Choix multiple</option>
                </select>
                <input x-model="q.points" type="number" min="1" placeholder="Points"
                       class="border rounded-lg px-3 py-2 w-24">
            </div>
            
            <div class="space-y-2 ml-4">
                <template x-for="(opt, oIdx) in q.options" :key="oIdx">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" x-model="opt.est_correcte" 
                               @change="q.type === 'unique' && setCorrectOption(qIdx, oIdx)">
                        <input x-model="opt.texte" placeholder="Option..."
                               class="flex-1 border rounded-lg px-3 py-1">
                        <input x-model="opt.feedback" placeholder="Feedback (optionnel)..."
                               class="flex-1 border rounded-lg px-3 py-1 text-sm">
                        <button @click="removeOption(qIdx, oIdx)" class="text-rose-400">×</button>
                    </div>
                </template>
                <button @click="addOption(qIdx)" class="text-sm text-indigo-600">+ Ajouter option</button>
            </div>
        </div>
    </template>
</div>
```

## Validation Checklist
- [ ] 3-step wizard functioning.
- [ ] Competence selection from tree.
- [ ] Class selection from formateur's classes.
- [ ] Dynamic add/remove questions.
- [ ] Dynamic add/remove options.
- [ ] Question type toggle (unique/multiple).
- [ ] At least one correct option per question.
- [ ] Review step showing summary.
- [ ] Form submission with validation.

**Trace:** `Formateur Création QCM Workflow executed`
