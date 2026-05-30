---
name: classe-manager
description: Specialized skill for managing classes, student enrollment, and formateur assignment.
---

# 👥 CLASSE MANAGER SKILL

## Capabilities

### 1. Classe CRUD
- Create class with `nom` and `promotion`.
- Edit class details.
- Delete class (with constraints).

### 2. Formateur Assignment
- Assign single formateur to class (`formateur_id`).
- Reassign formateur.
- Display current formateur in class list.

### 3. Student Management
- View students in class (list/grid).
- Add student to class (`classe_id` on User).
- Remove student from class.
- Bulk import students (optional).

### 4. Class Statistics
- Student count.
- Average score across all QCMs for class.
- QCMs assigned to class.
- Recent activity.

## Implementation Patterns

### Classe Model
```php
class Classe extends Model
{
    protected $fillable = ['nom', 'promotion', 'formateur_id'];
    
    public function formateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'formateur_id');
    }
    
    public function etudiants(): HasMany
    {
        return $this->hasMany(User::class, 'classe_id')
            ->where('type_profil', 'etudiant');
    }
    
    public function qcms(): HasMany
    {
        return $this->hasMany(Qcm::class);
    }
}
```

### ClasseService
```php
class ClasseService
{
    public function createClasse(array $data): Classe
    {
        return Classe::create([
            'nom' => $data['nom'],
            'promotion' => $data['promotion'],
            'formateur_id' => $data['formateur_id'] ?? null,
        ]);
    }
    
    public function assignFormateur(int $classeId, int $formateurId): Classe
    {
        $classe = Classe::findOrFail($classeId);
        $formateur = User::findOrFail($formateurId);
        
        if (!$formateur->isFormateur()) {
            throw new InvalidArgumentException('User must be a formateur');
        }
        
        $classe->update(['formateur_id' => $formateurId]);
        return $classe->load('formateur');
    }
    
    public function addStudent(int $classeId, int $studentId): User
    {
        $student = User::findOrFail($studentId);
        $student->update(['classe_id' => $classeId]);
        return $student;
    }
    
    public function removeStudent(int $studentId): User
    {
        $student = User::findOrFail($studentId);
        $student->update(['classe_id' => null]);
        return $student;
    }
    
    public function getClasseStats(int $classeId): array
    {
        $classe = Classe::with(['etudiants', 'qcms'])->findOrFail($classeId);
        
        return [
            'student_count' => $classe->etudiants->count(),
            'qcm_count' => $classe->qcms->count(),
            'average_score' => Tentative::whereIn('user_id', $classe->etudiants->pluck('id'))
                ->where('statut', 'termine')
                ->avg('score_obtenu') ?? 0,
        ];
    }
}
```

### UI Components

#### Class Card
```html
<div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
    <div class="flex justify-between items-start">
        <div>
            <h3 class="text-lg font-semibold" x-text="classe.nom">DW-2024</h3>
            <p class="text-sm text-slate-500" x-text="classe.promotion">2024-2025</p>
        </div>
        <span class="px-2 py-1 text-xs rounded-full bg-slate-100">
            <span x-text="classe.etudiants_count">12</span> étudiants
        </span>
    </div>
    
    <div class="mt-4 pt-4 border-t border-slate-100">
        <p class="text-sm text-slate-600">
            Formateur: 
            <span x-text="classe.formateur?.nom || 'Non assigné'"></span>
        </p>
    </div>
    
    <div class="mt-4 flex gap-2">
        <button @click="editClasse(classe.id)">Éditer</button>
        <button @click="assignFormateur(classe.id)">Assigner Formateur</button>
        <button @click="viewStudents(classe.id)">Voir Étudiants</button>
    </div>
</div>
```

#### Student List Modal
```html
<div x-show="showStudentModal" class="fixed inset-0 bg-black/50 flex items-center justify-center">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[80vh] overflow-auto p-6">
        <h3 class="text-lg font-semibold mb-4">Étudiants - <span x-text="selectedClasse.nom"></span></h3>
        
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">Nom</th>
                    <th class="text-left py-2">Email</th>
                    <th class="text-left py-2">QCMs Passés</th>
                    <th class="text-left py-2">Moyenne</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="student in students" :key="student.id">
                    <tr class="border-b">
                        <td class="py-2" x-text="student.nom + ' ' + student.prenom"></td>
                        <td class="py-2" x-text="student.email"></td>
                        <td class="py-2" x-text="student.tentatives_count"></td>
                        <td class="py-2" x-text="student.average_score + '%'"></td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>
```

## Constraints
- One formateur per class.
- Student can belong to only one class.
- Cannot delete class if students are enrolled (reassign or remove first).

## Output
- Class management interface.
- Formateur assignment workflow.
- Student enrollment management.
- Class statistics dashboard.
