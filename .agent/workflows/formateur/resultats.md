---
description: Workflow for building Formateur Results/Analytics module
trigger: /formateur-resultats
---

# 📊 Formateur Résultats Workflow

## Command
`/formateur-resultats`

## Dependencies
- **Skill:** `passation-service`, `dashboard-analytics`
- **Rules:** `rules/data/service_layer.md`

## Execution Steps

### 1. Controller Method
**File:** `app/Http/Controllers/Web/FormateurController.php`

```php
public function resultatsCohorte(QcmService $qcmService, PassationService $passationService): View
{
    $formateur = auth()->user();
    
    // Get all QCMs with attempt statistics
    $qcms = $qcmService->getQcmsWithStats($formateur->id);
    
    // Get all attempts for formateur's QCMs
    $tentatives = $passationService->getTentativesForFormateur($formateur->id);
    
    return view('formateur.resultats-cohorte', compact('qcms', 'tentatives'));
}
```

### 2. Service Methods
```php
// QcmService::getQcmsWithStats(int $formateurId): Collection
public function getQcmsWithStats(int $formateurId): Collection
{
    return Qcm::where('user_id', $formateurId)
        ->withCount('tentatives')
        ->withAvg('tentatives', 'score_obtenu')
        ->with(['competence', 'classe'])
        ->get();
}

// PassationService::getTentativesForFormateur(int $formateurId): Collection
public function getTentativesForFormateur(int $formateurId): Collection
{
    return Tentative::whereHas('qcm', function ($q) use ($formateurId) {
            $q->where('user_id', $formateurId);
        })
        ->with(['user', 'qcm', 'reponses'])
        ->where('statut', 'termine')
        ->orderBy('date_fin', 'desc')
        ->get();
}
```

### 3. View
**File:** `resources/views/formateur/resultats-cohorte.blade.php`

**Required elements:**
- QCM selector dropdown.
- Stats cards (Total attempts, Average score, Pass rate).
- Attempts table with student names, scores, dates.
- Score distribution chart.
- Export to CSV option.

### 4. Alpine.js State
```javascript
x-data={
    qcms: @json($qcms),
    tentatives: @json($tentatives),
    selectedQcmId: '',
    
    get selectedQcm() {
        return this.qcms.find(q => q.id == this.selectedQcmId);
    },
    
    get filteredTentatives() {
        if (!this.selectedQcmId) return this.tentatives;
        return this.tentatives.filter(t => t.qcm_id == this.selectedQcmId);
    },
    
    get stats() {
        const attempts = this.filteredTentatives;
        if (attempts.length === 0) return { count: 0, avg: 0, passRate: 0 };
        
        const avg = attempts.reduce((sum, t) => sum + t.score_obtenu, 0) / attempts.length;
        const passed = attempts.filter(t => {
            const qcm = this.qcms.find(q => q.id === t.qcm_id);
            return t.score_obtenu >= (qcm?.seuil_reussite || 70);
        }).length;
        
        return {
            count: attempts.length,
            avg: Math.round(avg),
            passRate: Math.round((passed / attempts.length) * 100)
        };
    },
    
    getScoreColorClass(score) {
        if (score >= 70) return 'text-emerald-500';
        if (score >= 50) return 'text-amber-500';
        return 'text-rose-500';
    },
    
    exportCsv() {
        // Generate and download CSV
        const csv = this.convertToCsv(this.filteredTentatives);
        this.downloadCsv(csv, 'resultats.csv');
    }
}
```

### 5. Stats Template
```html
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <p class="text-sm text-slate-500">Total Tentatives</p>
        <p class="text-3xl font-bold" x-text="stats.count">0</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <p class="text-sm text-slate-500">Moyenne de la Cohorte</p>
        <p class="text-3xl font-bold" x-text="stats.avg + '%'">0%</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <p class="text-sm text-slate-500">Taux de Réussite</p>
        <p class="text-3xl font-bold" :class="stats.passRate >= 70 ? 'text-emerald-500' : 'text-amber-500'"
           x-text="stats.passRate + '%'">0%</p>
    </div>
</div>

<!-- QCM Selector -->
<div class="mb-6">
    <label class="block text-sm font-medium text-slate-700 mb-2">Filtrer par QCM</label>
    <select x-model="selectedQcmId" class="w-full md:w-96 border rounded-lg px-3 py-2">
        <option value="">Tous les QCMs</option>
        <template x-for="qcm in qcms" :key="qcm.id">
            <option :value="qcm.id" x-text="qcm.titre"></option>
        </template>
    </select>
</div>

<!-- Attempts Table -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-medium text-slate-500">Étudiant</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-slate-500">QCM</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-slate-500">Score</th>
                <th class="px-4 py-3 text-left text-sm font-medium text-slate-500">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            <template x-for="t in filteredTentatives" :key="t.id">
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3" x-text="t.user.nom + ' ' + t.user.prenom"></td>
                    <td class="px-4 py-3" x-text="t.qcm.titre"></td>
                    <td class="px-4 py-3">
                        <span class="font-bold" :class="getScoreColorClass(t.score_obtenu)"
                              x-text="t.score_obtenu + '%'"></span>
                    </td>
                    <td class="px-4 py-3 text-slate-500" x-text="new Date(t.date_fin).toLocaleDateString()"></td>
                </tr>
            </template>
        </tbody>
    </table>
</div>
```

## Validation Checklist
- [ ] QCM selector filtering results.
- [ ] Stats cards calculating correctly.
- [ ] Score color coding (green/amber/red).
- [ ] Attempts table with pagination.
- [ ] Export to CSV working.
- [ ] Responsive layout.

**Trace:** `Formateur Résultats Workflow executed`
