---
description: Workflow for building Formateur Dashboard module
trigger: /formateur-dashboard
---

# 👨‍🏫 Formateur Dashboard Workflow

## Command
`/formateur-dashboard`

## Dependencies
- **Skill:** `dashboard-analytics`
- **Rules:** `rules/data/service_layer.md`

## Execution Steps

### 1. Controller Method
**File:** `app/Http/Controllers/Web/FormateurController.php`

```php
public function dashboard(ClasseService $classeService, QcmService $qcmService): View
{
    $formateur = auth()->user();
    
    // Classes managed by this formateur
    $classes = $classeService->getClassesForFormateur($formateur->id);
    
    // QCMs created by this formateur
    $qcms = $qcmService->getQcmsForFormateur($formateur->id);
    
    // Stats
    $stats = [
        'classes_count' => $classes->count(),
        'total_students' => $classes->sum('etudiants_count'),
        'qcms_count' => $qcms->count(),
        'published_qcms' => $qcms->where('est_publie', true)->count(),
    ];
    
    return view('formateur.dashboard', compact('classes', 'qcms', 'stats'));
}
```

### 2. View
**File:** `resources/views/formateur/dashboard.blade.php`

**Required elements:**
- KPI cards (Classes, Students, QCMs, Published).
- Classes list with student counts.
- Recent QCMs with status.
- Quick action: "Créer un QCM" button.

### 3. Alpine.js State
```javascript
x-data={
    classes: @json($classes),
    qcms: @json($qcms),
    stats: @json($stats),
    
    getStatusBadgeClass(status) {
        return {
            'true': 'bg-emerald-100 text-emerald-700',
            'false': 'bg-amber-100 text-amber-700'
        }[status];
    }
}
```

### 4. Dashboard Template
```html
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-kpi-card title="Mes Classes" :value="$stats['classes_count']" icon="users" />
        <x-kpi-card title="Étudiants" :value="$stats['total_students']" icon="academic-cap" />
        <x-kpi-card title="Mes QCMs" :value="$stats['qcms_count']" icon="document-text" />
        <x-kpi-card title="Publiés" :value="$stats['published_qcms']" icon="check-circle" />
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Classes Section -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Mes Classes</h2>
                <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800">Voir tout</a>
            </div>
            <div class="space-y-3">
                <template x-for="classe in classes" :key="classe.id">
                    <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                        <div>
                            <p class="font-medium" x-text="classe.nom"></p>
                            <p class="text-sm text-slate-500" x-text="classe.promotion"></p>
                        </div>
                        <span class="text-sm text-slate-600">
                            <span x-text="classe.etudiants_count"></span> étudiants
                        </span>
                    </div>
                </template>
            </div>
        </div>
        
        <!-- Recent QCMs -->
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">QCMs Récents</h2>
                <a href="{{ route('formateur.bibliotheque') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Bibliothèque</a>
            </div>
            <div class="space-y-3">
                <template x-for="qcm in qcms.slice(0, 5)" :key="qcm.id">
                    <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                        <div>
                            <p class="font-medium" x-text="qcm.titre"></p>
                            <p class="text-sm text-slate-500" x-text="qcm.competence.libelle"></p>
                        </div>
                        <span :class="getStatusBadgeClass(qcm.est_publie)" 
                              class="px-2 py-1 text-xs rounded-full"
                              x-text="qcm.est_publie ? 'Publié' : 'Brouillon'"></span>
                    </div>
                </template>
            </div>
            <a href="{{ route('formateur.qcm.create') }}" 
               class="mt-4 w-full block text-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                + Créer un QCM
            </a>
        </div>
    </div>
</div>
@endsection
```

## Validation Checklist
- [ ] KPI cards showing correct stats.
- [ ] Classes list with student counts.
- [ ] Recent QCMs with status badges.
- [ ] Quick action button working.
- [ ] Responsive layout.

**Trace:** `Formateur Dashboard Workflow executed`
