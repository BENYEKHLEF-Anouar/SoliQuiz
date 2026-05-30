---
trigger: on_demand
type: rule
id: pedagogie-tree
---

# 🌳 SOLIQUIZ PÉDAGOGIE TREE COMPONENT RULES

## Hierarchy Display
```
▼ 🗂️ Seance: Développement Web
  ├── ▼ 📚 UA: C1 - Backend PHP
  │     ├── 🎯 Compétence: Maîtrise de Laravel
  │     ├── 🎯 Compétence: Eloquent ORM
  │     └── 🎯 Compétence: Migrations
  ├── ▶ 📚 UA: C2 - Frontend JS
  └── ▶ 📚 UA: C3 - DevOps
```

## Alpine.js State
```javascript
x-data={
    seances: [...],
    expandedSeances: [],
    expandedUAs: [],
    activeModal: null, // 'create-seance', 'create-ua', 'create-competence'
    editingItem: null,
    
    toggleSeance(id) { /* expand/collapse */ },
    toggleUA(id) { /* expand/collapse */ },
    openCreateModal(type, parentId) { /* ... */ },
    deleteItem(type, id) { /* confirm + API call */ }
}
```

## UI Structure
```html
<!-- Seance Node -->
<div class="border-l-2 border-slate-200 pl-4">
    <div @click="toggleSeance(seance.id)" class="flex items-center gap-2 cursor-pointer py-2">
        <span x-text="expandedSeances.includes(seance.id) ? '▼' : '▶'"></span>
        <span class="font-medium" x-text="seance.titre"></span>
        <button @click.stop="openCreateModal('ua', seance.id)">+ UA</button>
        <button @click.stop="deleteItem('seance', seance.id)">🗑️</button>
    </div>
    
    <!-- UAs -->
    <div x-show="expandedSeances.includes(seance.id)" class="ml-4">
        <template x-for="ua in seance.uniteApprentissages">
            <!-- UA Node (similar structure) -->
        </template>
    </div>
</div>
```

## CRUD Actions
| Action | Button | Modal |
|--------|--------|-------|
| Add Seance | Global "+ Seance" | Form: titre, description, dates |
| Add UA | Per Seance "+ UA" | Form: code, nom |
| Add Competence | Per UA "+ Comp" | Form: code, libelle, description |
| Delete | 🗑️ icon | Confirmation dialog |

## Animations
- Expand/collapse: `x-transition: height`.
- New item highlight: `animate-pulse bg-indigo-50` for 2 seconds.
