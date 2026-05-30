---
description: Workflow for building Pédagogie management (Seance, UA, Competence)
trigger: /admin-pedagogie
---

# 🌳 Pédagogie Management Workflow

## Command
`/admin-pedagogie`

## Dependencies
- **Skill:** `pedagogie-manager`
- **Rules:** `rules/components/pedagogie_tree.md`, `rules/data/qcm_schema.md`

## Execution Steps

### 1. Controller Methods
**File:** `app/Http/Controllers/Web/AdminController.php`

```php
public function pedagogie(SeanceService $service): View
{
    $tree = $service->getFullTree();
    return view('admin.pedagogie', compact('tree'));
}

public function storeSeance(Request $request, SeanceService $service): RedirectResponse
{
    $validated = $request->validate([
        'titre' => 'required|string|max:255',
        'description' => 'nullable|string',
        'date_debut' => 'required|date',
        'date_fin' => 'required|date|after:date_debut',
    ]);
    
    $service->createSeance($validated);
    return redirect()->route('admin.pedagogie')->with('success', 'Séance créée');
}

public function storeUA(Request $request, SeanceService $service, int $seanceId): RedirectResponse
{
    $validated = $request->validate([
        'code' => 'required|string|max:10',
        'nom' => 'required|string|max:255',
    ]);
    
    $service->createUA($seanceId, $validated);
    return redirect()->route('admin.pedagogie')->with('success', 'UA créée');
}

public function storeCompetence(Request $request, SeanceService $service, int $uaId): RedirectResponse
{
    $validated = $request->validate([
        'code' => 'required|string|max:10',
        'libelle' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);
    
    $service->createCompetence($uaId, $validated);
    return redirect()->route('admin.pedagogie')->with('success', 'Compétence créée');
}
```

### 2. View
**File:** `resources/views/admin/pedagogie.blade.php`

**Required elements:**
- Hierarchical tree display.
- Expand/collapse for Seances and UAs.
- "+" buttons for adding children.
- Edit/Delete actions per node.
- Modal forms for creation.

### 3. Alpine.js Tree Component
```javascript
x-data={
    tree: @json($tree),
    expandedSeances: [],
    expandedUAs: [],
    activeModal: null,
    formData: { type: '', parentId: null, code: '', nom: '', description: '' },
    
    toggleSeance(id) {
        const idx = this.expandedSeances.indexOf(id);
        if (idx > -1) this.expandedSeances.splice(idx, 1);
        else this.expandedSeances.push(id);
    },
    
    toggleUA(id) {
        const idx = this.expandedUAs.indexOf(id);
        if (idx > -1) this.expandedUAs.splice(idx, 1);
        else this.expandedUAs.push(id);
    },
    
    openModal(type, parentId = null) {
        this.formData = { type, parentId, code: '', nom: '', description: '' };
        this.activeModal = type;
    },
    
    getModalTitle() {
        return {
            'seance': 'Nouvelle Séance',
            'ua': 'Nouvelle Unité d\'Apprentissage',
            'competence': 'Nouvelle Compétence'
        }[this.formData.type];
    }
}
```

### 4. Tree Node Template
```html
<!-- Seance Node -->
<div class="border-l-2 border-slate-200 ml-2">
    <div class="flex items-center gap-2 py-2 px-2 hover:bg-slate-50 rounded cursor-pointer"
         @click="toggleSeance(seance.id)">
        <svg :class="{ 'rotate-90': expandedSeances.includes(seance.id) }" class="w-4 h-4 transition-transform">...</svg>
        <span class="font-medium" x-text="seance.titre"></span>
        <span class="text-xs text-slate-400" x-text="seance.unite_apprentissages.length + ' UAs'"></span>
        <button @click.stop="openModal('ua', seance.id)" class="ml-auto text-indigo-600 hover:text-indigo-800">+ UA</button>
    </div>
    
    <!-- UAs -->
    <div x-show="expandedSeances.includes(seance.id)" x-collapse class="ml-6">
        <template x-for="ua in seance.unite_apprentissages" :key="ua.id">
            <!-- UA Node (similar structure) -->
        </template>
    </div>
</div>
```

## Validation Checklist
- [ ] Full tree displaying correctly.
- [ ] Expand/collapse working.
- [ ] Create Seance/UA/Competence via modals.
- [ ] Delete with confirmation.
- [ ] Form validation.
- [ ] Visual hierarchy clear.

**Trace:** `Pédagogie Management Workflow executed`
