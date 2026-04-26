---
description: Workflow for building Class Management module
trigger: /admin-classes
---

# 👥 Gestion Classes Workflow

## Command
`/admin-classes`

## Dependencies
- **Skill:** `classe-manager`
- **Rules:** `rules/data/service_layer.md`, `rules/roles/access_control.md`

## Execution Steps

### 1. Controller Methods
**File:** `app/Http/Controllers/Web/AdminController.php`

```php
public function gestionClasses(): View
{
    $classes = Classe::with(['formateur', 'etudiants'])
        ->withCount('etudiants')
        ->get();
    
    $formateurs = User::role('formateur')->get();
    
    return view('admin.classes', compact('classes', 'formateurs'));
}

public function storeClasse(Request $request, ClasseService $service): RedirectResponse
{
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'promotion' => 'required|string|max:50',
    ]);
    
    $service->createClasse($validated);
    return redirect()->route('admin.classes')->with('success', 'Classe créée');
}

public function assignFormateur(Request $request, ClasseService $service, int $classeId): RedirectResponse
{
    $validated = $request->validate([
        'formateur_id' => 'required|exists:users,id',
    ]);
    
    $service->assignFormateur($classeId, $validated['formateur_id']);
    return redirect()->route('admin.classes')->with('success', 'Formateur assigné');
}
```

### 2. View
**File:** `resources/views/admin/classes.blade.php`

**Required elements:**
- Class cards grid.
- "Nouvelle Classe" modal.
- Assign Formateur modal (per class).
- Student count badge.
- Delete class action.

### 3. Alpine.js State
```javascript
x-data={
    classes: @json($classes),
    formateurs: @json($formateurs),
    showCreateModal: false,
    showAssignModal: false,
    selectedClasse: null,
    newClasse: { nom: '', promotion: '' },
    selectedFormateurId: '',
    
    openAssignModal(classe) {
        this.selectedClasse = classe;
        this.selectedFormateurId = classe.formateur?.id || '';
        this.showAssignModal = true;
    },
    
    assignFormateur() {
        // POST to /admin/classes/{id}/formateur
        this.showAssignModal = false;
    }
}
```

### 4. Class Card Template
```html
<div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
    <div class="flex justify-between items-start">
        <div>
            <h3 class="text-lg font-semibold text-slate-900" x-text="classe.nom"></h3>
            <p class="text-sm text-slate-500" x-text="classe.promotion"></p>
        </div>
        <span class="px-2 py-1 text-xs font-medium bg-slate-100 text-slate-700 rounded-full">
            <span x-text="classe.etudiants_count"></span> étudiants
        </span>
    </div>
    
    <div class="mt-4 pt-4 border-t border-slate-100">
        <p class="text-sm">
            <span class="text-slate-500">Formateur:</span>
            <span class="font-medium" x-text="classe.formateur?.nom_complet || 'Non assigné'"></span>
        </p>
    </div>
    
    <div class="mt-4 flex gap-2">
        <button @click="openAssignModal(classe)" 
                class="px-3 py-1.5 text-sm bg-indigo-50 text-indigo-700 rounded hover:bg-indigo-100">
            Assigner Formateur
        </button>
        <button @click="viewStudents(classe.id)" 
                class="px-3 py-1.5 text-sm bg-slate-50 text-slate-700 rounded hover:bg-slate-100">
            Voir Étudiants
        </button>
    </div>
</div>
```

## Validation Checklist
- [ ] Create class with nom/promotion.
- [ ] Display class cards with student count.
- [ ] Assign formateur via modal.
- [ ] List formateurs in dropdown.
- [ ] Delete class with confirmation.
- [ ] Responsive grid.

**Trace:** `Gestion Classes Workflow executed`
