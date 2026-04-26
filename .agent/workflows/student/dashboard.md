---
description: Workflow for building Student Dashboard module
trigger: /student-dashboard
---

# 🎓 Student Dashboard Workflow

## Command
`/student-dashboard`

## Dependencies
- **Skill:** `dashboard-analytics`
- **Rules:** `rules/data/service_layer.md`

## Execution Steps

### 1. Controller Method
**File:** `app/Http/Controllers/Web/StudentController.php`

```php
public function dashboard(EtudiantService $service): View
{
    $student = auth()->user();
    
    $dashboardData = $service->getDashboard($student->id);
    
    return view('student.dashboard', [
        'kpis' => $dashboardData['kpis'],
        'recentAttempts' => $dashboardData['recent_attempts'],
        'inProgress' => $dashboardData['in_progress'],
        'availableQcms' => $dashboardData['available_qcms'],
    ]);
}
```

### 2. Service Method
**File:** `app/Services/EtudiantService.php`

```php
public function getDashboard(int $studentId): array
{
    $tentatives = Tentative::where('user_id', $studentId);
    
    $completed = (clone $tentatives)->where('statut', 'termine');
    
    return [
        'kpis' => [
            'completed_qcms' => $completed->count(),
            'average_score' => $completed->avg('score_obtenu') ?? 0,
            'in_progress' => (clone $tentatives)->where('statut', 'en_cours')->count(),
            'success_rate' => $completed->count() > 0
                ? $completed->whereColumn('score_obtenu', '>=', 'qcm.seuil_reussite')->count() / $completed->count() * 100
                : 0,
        ],
        'recent_attempts' => $completed->with('qcm')->latest()->limit(5)->get(),
        'in_progress' => (clone $tentatives)->where('statut', 'en_cours')->with('qcm')->get(),
        'available_qcms' => $this->getAvailableQcms($studentId),
    ];
}
```

### 3. View
**File:** `resources/views/student/dashboard.blade.php`

**Required elements:**
- KPI cards (Completed, Average, In Progress, Success Rate).
- "Continue" section for in-progress QCMs.
- Recent attempts list.
- "Start New QCM" CTA linking to bibliotheque.

### 4. Alpine.js State
```javascript
x-data={
    kpis: @json($kpis),
    recentAttempts: @json($recentAttempts),
    inProgress: @json($inProgress),
    
    getScoreColor(score) {
        if (score >= 70) return 'text-emerald-500';
        if (score >= 50) return 'text-amber-500';
        return 'text-rose-500';
    },
    
    formatDate(date) {
        return new Date(date).toLocaleDateString('fr-FR');
    }
}
```

### 5. Dashboard Template
```html
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- KPI Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-sm p-4 text-center">
            <p class="text-2xl font-bold" x-text="kpis.completed_qcms">0</p>
            <p class="text-xs text-slate-500">QCMs Complétés</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 text-center">
            <p class="text-2xl font-bold" :class="getScoreColor(kpis.average_score)"
               x-text="Math.round(kpis.average_score) + '%'">0%</p>
            <p class="text-xs text-slate-500">Moyenne</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 text-center">
            <p class="text-2xl font-bold" x-text="kpis.in_progress">0</p>
            <p class="text-xs text-slate-500">En Cours</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-4 text-center">
            <p class="text-2xl font-bold" :class="kpis.success_rate >= 70 ? 'text-emerald-500' : 'text-amber-500'"
               x-text="Math.round(kpis.success_rate) + '%'">0%</p>
            <p class="text-xs text-slate-500">Taux de Réussite</p>
        </div>
    </div>
    
    <!-- In Progress Section -->
    <div x-show="inProgress.length > 0" class="bg-amber-50 border border-amber-200 rounded-lg p-4">
        <h2 class="font-semibold text-amber-800 mb-3">QCMs en Cours</h2>
        <div class="space-y-2">
            <template x-for="t in inProgress" :key="t.id">
                <div class="flex justify-between items-center bg-white rounded p-3">
                    <div>
                        <p class="font-medium" x-text="t.qcm.titre"></p>
                        <p class="text-xs text-slate-500" x-text="t.qcm.duree_minutes + ' minutes'"></p>
                    </div>
                    <a :href="`/student/qcm/${t.qcm_id}`" 
                       class="px-4 py-2 bg-amber-500 text-white rounded-lg text-sm">
                        Continuer →
                    </a>
                </div>
            </template>
        </div>
    </div>
    
    <!-- Recent Attempts -->
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
        <h2 class="font-semibold mb-4">Tentatives Récentes</h2>
        <div class="space-y-3">
            <template x-for="attempt in recentAttempts" :key="attempt.id">
                <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                    <div>
                        <p class="font-medium" x-text="attempt.qcm.titre"></p>
                        <p class="text-xs text-slate-500" x-text="formatDate(attempt.date_fin)"></p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="font-bold" :class="getScoreColor(attempt.score_obtenu)"
                              x-text="attempt.score_obtenu + '%'"></span>
                        <a :href="`/student/qcm/${attempt.qcm_id}/resultats`"
                           class="text-sm text-indigo-600 hover:text-indigo-800">Voir</a>
                    </div>
                </div>
            </template>
            <p x-show="recentAttempts.length === 0" class="text-slate-500 text-center py-4">
                Aucune tentative récente
            </p>
        </div>
    </div>
    
    <!-- CTA -->
    <a href="{{ route('student.bibliotheque') }}" 
       class="block w-full text-center px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
        🎯 Accéder à la Bibliothèque de QCMs
    </a>
</div>
@endsection
```

## Validation Checklist
- [ ] KPI cards with correct values.
- [ ] Score color coding.
- [ ] In-progress section with "Continue" buttons.
- [ ] Recent attempts list.
- [ ] Link to bibliotheque.
- [ ] Empty states handled.

**Trace:** `Student Dashboard Workflow executed`
