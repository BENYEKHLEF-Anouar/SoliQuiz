---
description: Workflow for building Student QCM Library (Available QCMs)
trigger: /student-biblio
---

# 📚 Student Bibliothèque Workflow

## Command
`/student-biblio`

## Dependencies
- **Skill:** `qcm-engine`
- **Rules:** `rules/data/qcm_schema.md`

## Execution Steps

### 1. Controller Method
**File:** `app/Http/Controllers/Web/StudentController.php`

```php
public function bibliotheque(QcmPublicService $service): View
{
    $student = auth()->user();
    
    // Get QCMs bucketed by status
    $qcms = $service->getQcmsDisponibles($student->id, $student->classe_id);
    
    return view('student.bibliotheque', [
        'aFaire' => $qcms['a_faire'],
        'enCours' => $qcms['en_cours'],
        'termines' => $qcms['termines'],
    ]);
}
```

### 2. Service Method
**File:** `app/Services/QcmPublicService.php`

```php
public function getQcmsDisponibles(int $studentId, ?int $classeId): array
{
    if (!$classeId) {
        return ['a_faire' => [], 'en_cours' => [], 'termines' => []];
    }
    
    // Published QCMs for this student's class
    $publishedQcms = Qcm::where('classe_id', $classeId)
        ->where('est_publie', true)
        ->with(['competence.uniteApprentissage.seance'])
        ->get();
    
    // Get all attempts for this student
    $attempts = Tentative::where('user_id', $studentId)
        ->whereIn('qcm_id', $publishedQcms->pluck('id'))
        ->get()
        ->keyBy('qcm_id');
    
    $aFaire = [];
    $enCours = [];
    $termines = [];
    
    foreach ($publishedQcms as $qcm) {
        $attempt = $attempts->get($qcm->id);
        
        if (!$attempt) {
            $aFaire[] = $qcm;
        } elseif ($attempt->statut === 'en_cours') {
            $qcm->tentative_en_cours = $attempt;
            $enCours[] = $qcm;
        } else {
            $qcm->derniere_tentative = $attempt;
            $termines[] = $qcm;
        }
    }
    
    return compact('aFaire', 'enCours', 'termines');
}
```

### 3. View
**File:** `resources/views/student/bibliotheque.blade.php`

**Required elements:**
- Tabs or sections: À Faire, En Cours, Terminés.
- QCM cards with metadata.
- Status badges and action buttons.
- Filter by competence/search.

### 4. Alpine.js State
```javascript
x-data={
    aFaire: @json($aFaire),
    enCours: @json($enCours),
    termines: @json($termines),
    activeTab: 'a-faire', // 'a-faire', 'en-cours', 'termines'
    search: '',
    filterCompetence: '',
    
    get currentList() {
        return this[this.activeTab.replace('-', '')] || [];
    },
    
    get filteredList() {
        return this.currentList.filter(q => {
            const matchesSearch = q.titre.toLowerCase().includes(this.search.toLowerCase()) ||
                                 q.competence.libelle.toLowerCase().includes(this.search.toLowerCase());
            const matchesComp = !this.filterCompetence || q.competence_id == this.filterCompetence;
            return matchesSearch && matchesComp;
        });
    },
    
    getAllCompetences() {
        const all = [...this.aFaire, ...this.enCours, ...this.termines];
        const unique = [...new Map(all.map(q => [q.competence_id, q.competence])).values()];
        return unique;
    },
    
    getScoreColor(score) {
        if (score >= 70) return 'text-emerald-500';
        if (score >= 50) return 'text-amber-500';
        return 'text-rose-500';
    }
}
```

### 5. Bibliothèque Template
```html
@extends('layouts.app')

@section('content')
<div x-data="{ ...qcmData(), activeTab: 'a-faire' }" class="space-y-4">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <h1 class="text-xl font-bold">Bibliothèque de QCMs</h1>
        
        <!-- Filters -->
        <div class="flex gap-2">
            <input x-model="search" placeholder="Rechercher..." 
                   class="border rounded-lg px-3 py-2 text-sm">
            <select x-model="filterCompetence" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">Toutes compétences</option>
                <template x-for="comp in getAllCompetences()" :key="comp.id">
                    <option :value="comp.id" x-text="comp.libelle"></option>
                </template>
            </select>
        </div>
    </div>
    
    <!-- Tabs -->
    <div class="flex border-b">
        <button @click="activeTab = 'a-faire'" 
                :class="{ 'border-b-2 border-indigo-600 text-indigo-600': activeTab === 'a-faire' }"
                class="px-4 py-2 font-medium">
            À Faire (<span x-text="aFaire.length"></span>)
        </button>
        <button @click="activeTab = 'en-cours'" 
                :class="{ 'border-b-2 border-indigo-600 text-indigo-600': activeTab === 'en-cours' }"
                class="px-4 py-2 font-medium">
            En Cours (<span x-text="enCours.length"></span>)
        </button>
        <button @click="activeTab = 'termines'" 
                :class="{ 'border-b-2 border-indigo-600 text-indigo-600': activeTab === 'termines' }"
                class="px-4 py-2 font-medium">
            Terminés (<span x-text="termines.length"></span>)
        </button>
    </div>
    
    <!-- QCM Cards -->
    <div class="grid gap-4">
        <template x-for="qcm in filteredList" :key="qcm.id">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-semibold" x-text="qcm.titre"></h3>
                        <p class="text-sm text-slate-500">
                            <span x-text="qcm.competence.libelle"></span> • 
                            <span x-text="qcm.competence.unite_apprentissage.seance.titre"></span>
                        </p>
                        <p class="text-xs text-slate-400 mt-1">
                            <span x-text="qcm.questions_count"></span> questions • 
                            <span x-text="qcm.duree_minutes"></span> min • 
                            Seuil: <span x-text="qcm.seuil_reussite"></span>%
                        </p>
                    </div>
                    
                    <!-- Status-specific actions -->
                    <div x-show="activeTab === 'a-faire'">
                        <a :href="`/student/qcm/${qcm.id}`" 
                           class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm">
                            Commencer
                        </a>
                    </div>
                    <div x-show="activeTab === 'en-cours'">
                        <a :href="`/student/qcm/${qcm.id}`" 
                           class="px-4 py-2 bg-amber-500 text-white rounded-lg text-sm">
                            Continuer
                        </a>
                        <p class="text-xs text-amber-600 mt-1">
                            Temps restant: <span x-text="qcm.tentative_en_cours?.time_remaining"></span>
                        </p>
                    </div>
                    <div x-show="activeTab === 'termines'">
                        <div class="text-right">
                            <p class="font-bold" :class="getScoreColor(qcm.derniere_tentative.score_obtenu)"
                               x-text="qcm.derniere_tentative.score_obtenu + '%'"></p>
                            <a :href="`/student/qcm/${qcm.id}/resultats`" 
                               class="text-sm text-indigo-600">Voir résultats</a>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        
        <p x-show="filteredList.length === 0" class="text-center text-slate-500 py-8">
            Aucun QCM dans cette catégorie
        </p>
    </div>
</div>
@endsection
```

## Validation Checklist
- [ ] QCMs bucketed correctly (À Faire/En Cours/Terminés).
- [ ] Tab switching working.
- [ ] Search and filter functional.
- [ ] Correct action buttons per status.
- [ ] Score display for completed QCMs.
- [ ] Empty states handled.
- [ ] Responsive layout.

**Trace:** `Student Bibliothèque Workflow executed`
