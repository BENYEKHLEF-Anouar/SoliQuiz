---
name: pedagogie-manager
description: Specialized skill for managing the pedagogical hierarchy (Seance, UA, Competence).
---

# 🌳 PÉDAGOGIE MANAGER SKILL

## Capabilities

### 1. Seance Management
- CRUD for Seances (training sessions).
- Fields: `titre`, `description`, `date_debut`, `date_fin`.
- List with expand/collapse for child UAs.

### 2. Unite d'Apprentissage (UA) Management
- CRUD for UAs within Seances.
- Fields: `code` (e.g., "C1"), `nom`.
- Ordered display within parent Seance.

### 3. Competence Management
- CRUD for Competences within UAs.
- Fields: `code` (e.g., "C1.1"), `libelle`, `description`.
- Link to QCMs (one-to-many).

### 4. Hierarchical Display
- Tree view: Seance → UA → Competence.
- Expand/collapse with Alpine.js.
- Visual indicators for each level.
- Quick actions per node (add child, edit, delete).

## Implementation Patterns

### Eloquent Relationships
```php
// Seance model
public function uniteApprentissages(): HasMany
{
    return $this->hasMany(UniteApprentissage::class)->orderBy('code');
}

// UniteApprentissage model
public function seance(): BelongsTo
{
    return $this->belongsTo(Seance::class);
}

public function competences(): HasMany
{
    return $this->hasMany(Competence::class)->orderBy('code');
}

// Competence model
public function uniteApprentissage(): BelongsTo
{
    return $this->belongsTo(UniteApprentissage::class);
}

public function qcms(): HasMany
{
    return $this->hasMany(Qcm::class);
}
```

### SeanceService Pattern
```php
class SeanceService
{
    public function getFullTree(): Collection
    {
        return Seance::with(['uniteApprentissages.competences'])
            ->orderBy('date_debut', 'desc')
            ->get();
    }
    
    public function createSeance(array $data): Seance
    {
        return Seance::create($data);
    }
    
    public function createUA(int $seanceId, array $data): UniteApprentissage
    {
        $seance = Seance::findOrFail($seanceId);
        return $seance->uniteApprentissages()->create($data);
    }
    
    public function createCompetence(int $uaId, array $data): Competence
    {
        $ua = UniteApprentissage::findOrFail($uaId);
        return $ua->competences()->create($data);
    }
    
    public function deleteCascade(int $seanceId): void
    {
        // Delete all related competences, UAs, then seance
        // Or use database cascading if configured
    }
}
```

### Alpine.js Tree Component
```javascript
x-data={
    tree: [],
    expanded: { seances: [], uas: [] },
    modals: { show: null, editing: null, parentId: null },
    
    async loadTree() {
        this.tree = await fetch('/api/pedagogie/tree').then(r => r.json());
    },
    
    toggleSeance(id) {
        if (this.expanded.seances.includes(id)) {
            this.expanded.seances = this.expanded.seances.filter(s => s !== id);
        } else {
            this.expanded.seances.push(id);
        }
    },
    
    openCreateModal(type, parentId = null) {
        this.modals = { show: type, editing: null, parentId };
    },
    
    async saveItem() {
        // API call to save
        await this.loadTree();
        this.modals.show = null;
    }
}
```

## Validation Rules
- **Seance:** `titre` required, `date_debut` <= `date_fin`.
- **UA:** `code` unique per seance, `nom` required.
- **Competence:** `code` unique per UA, `libelle` required.
- **Deletion:** Confirm if children exist. Option to cascade delete.

## Output
- Hierarchical tree interface.
- Full CRUD for Seance/UA/Competence.
- Alpine.js powered interactions.
- Eloquent relationship models.
