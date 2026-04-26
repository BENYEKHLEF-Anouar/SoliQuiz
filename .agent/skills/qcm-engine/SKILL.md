---
name: qcm-engine
description: Specialized skill for building QCM creation, management, and editing functionality.
---

# 📝 QCM ENGINE SKILL

## Capabilities

### 1. QCM CRUD Operations
- Create QCM with metadata (titre, durée, seuil, compétence, classe).
- Read QCM with full question/option hierarchy.
- Update QCM (draft vs published state management).
- Delete QCM with cascade handling.

### 2. Question Management
- Add/remove questions dynamically.
- Support multiple question types (unique, multiple).
- Reorder questions via drag-and-drop or buttons.
- Validate question integrity.

### 3. Option Management
- Add/remove options per question.
- Mark correct answers.
- Add optional feedback per option.
- Validate: min 2 options, at least 1 correct.

### 4. State Management
- Draft: `est_publie = false`, editable.
- Published: `est_publie = true`, visible to students.
- Archived: soft delete or `statut = 'archive'`.

## Implementation Patterns

### Transactional Creation
```php
// QcmService::createQcm(array $data): Qcm
DB::transaction(function () use ($data) {
    $qcm = Qcm::create([
        'titre' => $data['titre'],
        'duree_minutes' => $data['duree_minutes'],
        'seuil_reussite' => $data['seuil_reussite'],
        'competence_id' => $data['competence_id'],
        'classe_id' => $data['classe_id'],
        'user_id' => auth()->id(),
    ]);
    
    foreach ($data['questions'] as $qData) {
        $question = $qcm->questions()->create([
            'enonce' => $qData['enonce'],
            'type' => $qData['type'],
            'points' => $qData['points'] ?? 1,
            'ordre' => $qData['ordre'],
        ]);
        
        $question->options()->createMany($qData['options']);
    }
    
    return $qcm;
});
```

### Alpine.js Integration
- Use `x-data` for reactive QCM builder.
- Real-time validation feedback.
- Auto-save draft to `localStorage`.

## Output
- Fully functional QCM creation/editing interface.
- Transactional database consistency.
- Alpine.js powered UI.
