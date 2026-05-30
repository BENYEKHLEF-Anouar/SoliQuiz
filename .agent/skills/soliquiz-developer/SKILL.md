---
name: soliquiz-developer
description: UI components, Blade templates, Alpine.js interactions, and frontend implementation.
---

# SOLIQUIZ DEVELOPER

## Domain
Frontend implementation: Blade templates, Tailwind CSS (Ocean Teal primary), Alpine.js SPA interactions, Lucide icons.

## Capabilities

### 1. Layout System

#### App Shell
```html
<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SoliQuiz')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 dark:bg-slate-900" x-data="{ toasts: [] }">
    <x-navbar :role="auth()->user()->type_profil" />
    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>
    <x-toast-container />
</body>
</html>
```

#### Role-Based Navbar
```html
<!-- Admin: Dark theme -->
<nav class="bg-slate-900 text-white">
    <!-- Links: Dashboard, Utilisateurs, Pédagogie, QCMs -->
</nav>

<!-- Formateur: Light theme -->
<nav class="bg-white border-b border-slate-200">
    <!-- Links: Dashboard, Bibliothèque, Créer QCM, Résultats -->
</nav>

<!-- Student: Light theme -->
<nav class="bg-white border-b border-slate-200">
    <!-- Links: Dashboard, Bibliothèque -->
</nav>
```

### 2. Form Components

```html
<!-- Input with validation -->
<div x-data="{ error: null }">
    <label class="block text-sm font-medium text-slate-700">Titre</label>
    <input type="text" 
           class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm 
                  focus:border-indigo-500 focus:ring-indigo-500"
           :class="{ 'border-rose-500': error }"
           x-model="value">
    <p x-show="error" x-text="error" class="mt-1 text-sm text-rose-500"></p>
</div>

<!-- Custom Select -->
<div class="relative" x-data="{ open: false, selected: null }">
    <button @click="open = !open" class="w-full border rounded-lg px-4 py-2 text-left">
        <span x-text="selected?.label || 'Sélectionner...'"></span>
    </button>
    <div x-show="open" @click.outside="open = false" 
         class="absolute z-10 w-full mt-1 bg-white border rounded-lg shadow-lg">
        <template x-for="option in options" :key="option.id">
            <div @click="selected = option; open = false" 
                 class="px-4 py-2 hover:bg-slate-50 cursor-pointer">
                <span x-text="option.label"></span>
            </div>
        </template>
    </div>
</div>
```

### 3. Data Display Components

```html
<!-- Data Table -->
<div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-medium text-slate-500">Nom</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-slate-500">Score</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-slate-500">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            <template x-for="item in items" :key="item.id">
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3" x-text="item.nom"></td>
                    <td class="px-4 py-3">
                        <span :class="scoreColor(item.score)" x-text="item.score + '%'"></span>
                    </td>
                    <td class="px-4 py-3">
                        <button @click="view(item.id)">Voir</button>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
</div>

<!-- Score Badge (on /20 scale) -->
<span :class="{
    'bg-emerald-100 text-emerald-700': score >= 14,    // >= 14/20 (70%)
    'bg-amber-100 text-amber-700': score >= 10 && score < 14,  // 10-13/20
    'bg-rose-100 text-rose-700': score < 10            // < 10/20
}" class="px-2 py-1 text-xs font-medium rounded-full">
    <span x-text="score + '/20'"></span>
</span>
```

### 4. Feedback Components

```html
<!-- Modal -->
<div x-show="open" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div x-show="open" x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             class="fixed inset-0 bg-black/50" @click="open = false"></div>
        <div x-show="open" x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             class="relative bg-white rounded-lg shadow-xl max-w-lg w-full p-6">
            {{ $slot }}
        </div>
    </div>
</div>

<!-- Toast Notifications -->
<div class="fixed bottom-4 right-4 z-50 space-y-2">
    <template x-for="toast in toasts" :key="toast.id">
        <div :class="{
            'bg-emerald-500': toast.type === 'success',
            'bg-rose-500': toast.type === 'error',
            'bg-amber-500': toast.type === 'warning'
        }" class="text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-2">
            <span x-text="toast.message"></span>
            <button @click="removeToast(toast.id)">×</button>
        </div>
    </template>
</div>
```

### 5. Specialized Components

#### QCM Builder Alpine.js (SPA Editor)
```javascript
x-data={
    step: 1,
    qcm: { 
        titre: '', 
        duree_minutes: 15, 
        score_reussite: 10,  // on /20 scale
        statut: 'brouillon',
        unite_apprentissage_id: '', 
        classe_id: '',
        competence_ids: []
    },
    questions: [],
    totalPoints: 0,
    
    init() {
        this.watchTotalPoints();
    },
    
    watchTotalPoints() {
        this.totalPoints = this.questions.reduce((sum, q) => sum + parseFloat(q.points || 0), 0);
    },
    
    addQuestion() {
        this.questions.push({
            texte: '', 
            type: 'choix_unique', 
            points: 1,
            explication_feedback: '',
            options: [
                { texte: '', est_correcte: false, feedback_specifique: '' },
                { texte: '', est_correcte: false, feedback_specifique: '' }
            ]
        });
        this.watchTotalPoints();
    },
    removeQuestion(index) { this.questions.splice(index, 1); },
    addOption(qIdx) { this.questions[qIdx].options.push({ texte: '', est_correcte: false, feedback: '' }); },
    removeOption(qIdx, oIdx) { this.questions[qIdx].options.splice(oIdx, 1); },
    
    setCorrectOption(qIdx, oIdx) {
        // For choix_unique: only one correct option
        if (this.questions[qIdx].type === 'choix_unique') {
            this.questions[qIdx].options.forEach((opt, idx) => opt.est_correcte = idx === oIdx);
        }
    },
    
    normalizeCorrectOptions(qIdx) {
        // When switching from multiple to unique, keep only first correct
        if (this.questions[qIdx].type === 'choix_unique') {
            const firstCorrect = this.questions[qIdx].options.findIndex(o => o.est_correcte);
            this.questions[qIdx].options.forEach((opt, idx) => {
                opt.est_correcte = idx === firstCorrect;
            });
        }
    },
    
    validatePoints() {
        // Warning if total points != 20
        return this.totalPoints === 20;
    }
}
```

#### Passation Interface Alpine.js (Full-screen focus mode)
```javascript
x-data={
    questions: @json($questions),
    tentative: @json($tentative),
    currentIndex: 0,
    reponses: {},
    timeRemaining: {{ $timeRemaining }},
    
    init() {
        // Restore saved responses from localStorage
        const saved = localStorage.getItem(`tentative_${this.tentative.id}`);
        if (saved) this.reponses = JSON.parse(saved);
        
        // Start countdown timer
        setInterval(() => {
            this.timeRemaining--;
            if (this.timeRemaining <= 0) this.submitQcm();
        }, 1000);
        
        // Auto-save every 30 seconds
        setInterval(() => this.saveResponses(), 30000);
    },
    
    toggleOption(qId, optId, type) {
        if (!this.reponses[qId]) this.reponses[qId] = [];
        const idx = this.reponses[qId].indexOf(optId);
        
        if (type === 'unique') {
            this.reponses[qId] = [optId];
        } else {
            idx > -1 ? this.reponses[qId].splice(idx, 1) : this.reponses[qId].push(optId);
        }
        this.saveResponses();
    },
    
    saveResponses() {
        localStorage.setItem(`tentative_${this.tentative.id}`, JSON.stringify(this.reponses));
    }
}
```

#### Pédagogie Tree Alpine.js
```javascript
x-data={
    tree: [],
    expanded: { seances: [], uas: [] },
    modals: { show: null, editing: null, parentId: null },
    
    toggleSeance(id) {
        this.expanded.seances.includes(id) 
            ? this.expanded.seances = this.expanded.seances.filter(s => s !== id)
            : this.expanded.seances.push(id);
    },
    openModal(type, parentId = null) {
        this.modals = { show: type, editing: null, parentId };
    }
}
```

### 6. Styling Conventions

```css
/* Score Colors (on /20 scale) */
.success { @apply text-emerald-500 bg-emerald-100; }    /* >= 14/20 */
.warning { @apply text-amber-500 bg-amber-100; }       /* 10-13/20 */
.danger { @apply text-rose-500 bg-rose-100; }          /* < 10/20 */

/* Primary: Ocean Teal */
.primary { @apply text-teal-600 bg-teal-50; }

/* Cards */
.card { @apply bg-white rounded-lg shadow-sm border border-slate-200 p-6; }

/* Buttons */
.btn-primary { @apply bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg; }
.btn-secondary { @apply bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 px-4 py-2 rounded-lg; }
.btn-danger { @apply bg-rose-500 hover:bg-rose-600 text-white px-4 py-2 rounded-lg; }
```

## Output
- Blade layout templates with SPA shell
- Reusable UI components (atomic design)
- Alpine.js controllers for reactive UI
- Tailwind CSS with Ocean Teal primary
- Lucide icon components
- Responsive, accessible patterns
- No full page reloads for in-app navigation
