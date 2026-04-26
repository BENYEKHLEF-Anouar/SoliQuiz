---
description: Workflow for building Formateur QCM Library module
trigger: /formateur-biblio
---

# 📚 Formateur Bibliothèque Workflow

## Command
`/formateur-biblio`

## Dependencies
- **Skill:** `qcm-engine`
- **Rules:** `rules/components/qcm_builder.md`

## Execution Steps

### 1. Controller Methods
**File:** `app/Http/Controllers/Web/FormateurController.php`

```php
public function bibliotheque(QcmService $service): View
{
    $qcms = $service->getQcmsForFormateur(auth()->id());
    return view('formateur.bibliotheque', compact('qcms'));
}

public function destroyQcm(QcmService $service, int $id): RedirectResponse
{
    $qcm = Qcm::findOrFail($id);
    
    // Authorization: only own QCMs
    if ($qcm->user_id !== auth()->id()) {
        abort(403);
    }
    
    $service->deleteQcm($id);
    return redirect()->route('formateur.bibliotheque')->with('success', 'QCM supprimé');
}
```

### 2. View
**File:** `resources/views/formateur/bibliotheque.blade.php`

**Required elements:**
- Filter by status (Tous, Publiés, Brouillons).
- Search by title.
- QCM cards grid.
- Actions: Voir, Éditer, Supprimer, Publier/Dépublier.
- "Créer un QCM" CTA.

### 3. Alpine.js State
```javascript
x-data={
    qcms: @json($qcms),
    search: '',
    statusFilter: 'all', // 'all', 'published', 'draft'
    showDeleteModal: false,
    selectedQcm: null,
    
    filteredQcms() {
        return this.qcms.filter(q => {
            const matchesSearch = q.titre.toLowerCase().includes(this.search.toLowerCase());
            const matchesStatus = this.statusFilter === 'all' 
                || (this.statusFilter === 'published' && q.est_publie)
                || (this.statusFilter === 'draft' && !q.est_publie);
            return matchesSearch && matchesStatus;
        });
    },
    
    confirmDelete(qcm) {
        this.selectedQcm = qcm;
        this.showDeleteModal = true;
    },
    
    async togglePublish(qcm) {
        // POST to toggle publish status
        qcm.est_publie = !qcm.est_publie;
    }
}
```

### 4. QCM Card Template
```html
<template x-for="qcm in filteredQcms()" :key="qcm.id">
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex justify-between items-start">
            <div>
                <h3 class="font-semibold text-lg" x-text="qcm.titre"></h3>
                <p class="text-sm text-slate-500" x-text="qcm.competence?.libelle"></p>
                <p class="text-xs text-slate-400 mt-1">
                    <span x-text="qcm.questions_count"></span> questions • 
                    <span x-text="qcm.duree_minutes"></span> min • 
                    Seuil: <span x-text="qcm.seuil_reussite"></span>%
                </p>
            </div>
            <span :class="{
                'bg-emerald-100 text-emerald-700': qcm.est_publie,
                'bg-amber-100 text-amber-700': !qcm.est_publie
            }" class="px-2 py-1 text-xs font-medium rounded-full"
              x-text="qcm.est_publie ? 'Publié' : 'Brouillon'"></span>
        </div>
        
        <div class="mt-4 flex items-center justify-between">
            <div class="text-sm text-slate-500">
                <span x-text="qcm.tentatives_count || 0"></span> tentatives • 
                Moyenne: <span x-text="qcm.average_score || 'N/A'"></span>%
            </div>
            <div class="flex gap-2">
                <button @click="togglePublish(qcm)" 
                        class="px-3 py-1 text-sm rounded"
                        :class="qcm.est_publie ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700'"
                        x-text="qcm.est_publie ? 'Dépublier' : 'Publier'"></button>
                <a :href="`/formateur/qcm/${qcm.id}/edit`" class="px-3 py-1 text-sm bg-slate-50 text-slate-700 rounded">Éditer</a>
                <button @click="confirmDelete(qcm)" class="px-3 py-1 text-sm bg-rose-50 text-rose-700 rounded">Supprimer</button>
            </div>
        </div>
    </div>
</template>
```

## Validation Checklist
- [ ] QCM list displaying with all details.
- [ ] Search/filter working.
- [ ] Publish/unpublish toggle.
- [ ] Delete with confirmation.
- [ ] Stats showing (attempts, average score).
- [ ] Responsive grid.

**Trace:** `Formateur Bibliothèque Workflow executed`
