---
trigger: always_on
type: rule
id: service-layer
---

# ⚙️ SOLIQUIZ SERVICE LAYER RULES

## Pattern: Thin Controller / Rich Service

### Controller Responsibility
- HTTP input validation (FormRequest).
- Delegate to services.
- HTTP response (redirect, view, JSON).
- **NO business logic in controllers.**

### Service Responsibility
- Business logic implementation.
- Database transactions.
- Complex queries.
- External API calls.

## Service Registry

| Service | Responsibility |
|---------|----------------|
| `UserService` | CRUD users, role assignment, password hashing |
| `DashboardService` | Admin KPIs: user counts, QCM stats, recent activity |
| `ClasseService` | Classe CRUD, formateur assignment, student listing |
| `SeanceService` | Full CRUD: Seances, UAs, Competences (hierarchical) |
| `QcmService` | Transactional QCM creation (QCM + Questions + Options) |
| `QcmPublicService` | Fetch published QCMs with student attempt context |
| `PassationService` | Attempt lifecycle: `demarrer()`, `soumettre()`, scoring |
| `EtudiantService` | Student dashboard KPIs, attempt history |
| `ResultatService` | Calculate scores, generate feedback, statistics |

## Transaction Pattern
```php
DB::transaction(function () {
    $qcm = Qcm::create([...]);
    foreach ($questions as $q) {
        $question = $qcm->questions()->create([...]);
        $question->options()->createMany([...]);
    }
});
```

## Scoring Algorithm
1. Calculate max possible score (sum of question points).
2. Calculate obtained score (sum of correct answers).
3. `score_obtenu = (obtained / max) * 100` (rounded integer).
4. Compare to `qcm->score_reussite` for pass/fail.
