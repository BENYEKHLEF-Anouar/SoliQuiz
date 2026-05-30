---
name: soliquiz-builder
description: Business logic, services, QCM engine, passation lifecycle, scoring, and analytics.
---

# 🔨 SOLIQUIZ BUILDER

## Domain
Business logic layer: services, QCM management, attempt lifecycle, scoring, and dashboard analytics.

## Capabilities

### 1. Core Services Registry

| Service | Responsibility |
|---------|----------------|
| `UserService` | User CRUD, role assignment, password hashing |
| `DashboardService` | Admin KPIs: user counts, QCM stats, recent activity |
| `ClasseService` | Classe CRUD, formateur assignment, student listing |
| `SeanceService` | Full CRUD for Seances, UA, Competences (hierarchical) |
| `QcmService` | Transactional QCM creation (QCM + Questions + Options), /20 scale |
| `QcmPublicService` | Fetch QCMs bucketed by status (a_faire, en_cours, termines) |
| `PassationService` | Attempt lifecycle: `demarrer()`, `soumettre()`, scoring |
| `EtudiantService` | Student dashboard KPIs, attempt history |
| `ResultatService` | Calculate scores, generate feedback, statistics |

### 2. QCM Engine

#### Transactional Creation
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

#### QCM State Management
- **Brouillon:** `statut = 'brouillon'`, editable by formateur
- **Publie:** `statut = 'publie'`, visible to students
- **Clos:** `statut = 'clos'`, no new attempts allowed

### 3. Passation Service

#### Attempt Lifecycle
```
[demarrer]          [soumettre]         [timeout]
en_cours ────────► termine ◄───────── en_cours
    ▲                                    │
    └──────────── [resume] ──────────────┘
```

```php
class PassationService {
    // Idempotent: returns existing en_cours attempt if exists
    public function demarrer(Qcm $qcm, User $user): Tentative {
        $existing = Tentative::where('user_id', $user->id)
            ->where('qcm_id', $qcm->id)
            ->where('statut', 'en_cours')
            ->first();
        
        if ($existing) return $existing;
        
        return Tentative::create([
            'user_id' => $user->id,
            'qcm_id' => $qcm->id,
            'statut' => 'en_cours',
            'date_debut' => now(),
            'date_fin' => now()->addMinutes($qcm->duree_minutes),
        ]);
    }
    
    public function soumettre(Tentative $tentative, array $reponses): void {
        // Save responses
        foreach ($reponses as $questionId => $optionIds) {
            $reponse = $tentative->reponses()->create(['question_id' => $questionId]);
            $reponse->choix()->attach($optionIds);
        }
        
        // Calculate score
        $tentative->score_obtenu = $this->calculerScore($tentative);
        $tentative->statut = 'termine';
        $tentative->save();
    }
    
    // Score on /20 scale, decimal(5,1)
    public function calculerScore(Tentative $tentative): float {
        $qcm = $tentative->qcm;
        $scoreTotal = 0;
        $pointsMax = 0;
        
        foreach ($qcm->questions as $question) {
            $pointsMax += $question->points;
            $reponse = $tentative->reponses()
                ->where('question_id', $question->id)
                ->first();
            
            if ($this->isReponseCorrecte($question, $reponse)) {
                $scoreTotal += $question->points;
            }
        }
        
        // Convert to /20 scale
        return $pointsMax > 0 ? round(($scoreTotal / $pointsMax) * 20, 1) : 0;
    }
}
```

#### Answer Validation
- **Choix Unique:** Only one correct option, student must select it
- **Choix Multiple:** All correct options must be selected, no incorrect options selected

#### TentativeObserver (Auto-close)
```php
class TentativeObserver {
    public function updated(Tentative $tentative): void {
        // Auto-close QCM when all students in classe have submitted
        if ($tentative->statut === 'termine') {
            $this->checkAndCloseQcm($tentative->qcm);
        }
    }
}
```

### 4. Dashboard Analytics

#### Admin KPIs
```php
public function getAdminKpis(): array {
    return [
        'total_users' => User::count(),
        'users_by_role' => User::selectRaw('type_profil, COUNT(*) as count')
            ->groupBy('type_profil')->pluck('count', 'type_profil'),
        'total_classes' => Classe::count(),
        'total_qcms' => Qcm::count(),
        'qcms_by_status' => Qcm::selectRaw('est_publie, COUNT(*) as count')
            ->groupBy('est_publie')->pluck('count', 'est_publie'),
        'attempts_this_month' => Tentative::whereMonth('created_at', now()->month)->count(),
        'average_score' => Tentative::where('statut', 'termine')->avg('score_obtenu'), // /20 scale
    ];
}
```

#### Formateur KPIs
- Classes managed (count + student total)
- QCMs created (total + published)
- Total attempts on own QCMs
- Average score on own QCMs

#### Student KPIs
- QCMs completed
- Average personal score
- QCMs in progress
- Success rate (% of passed QCMs)

### 5. Classe & Pédagogie Services

```php
class SeanceService {
    public function getFullTree(): Collection {
        return Seance::with(['uniteApprentissages.competences'])
            ->orderBy('date_debut', 'desc')->get();
    }
    
    public function createSeance(array $data): Seance { return Seance::create($data); }
    public function createUA(int $seanceId, array $data): UniteApprentissage { ... }
    public function createCompetence(int $uaId, array $data): Competence { ... }
}

class ClasseService {
    public function createClasse(array $data): Classe { ... }
    public function assignFormateur(int $classeId, int $formateurId): Classe { ... }
    public function addStudent(int $classeId, int $studentId): User { ... }
    public function getClasseStats(int $classeId): array { ... }
}
```

## Output
- Service layer with business logic
- Transactional database operations
- Scoring algorithms
- KPI calculations
- Role-based data access
