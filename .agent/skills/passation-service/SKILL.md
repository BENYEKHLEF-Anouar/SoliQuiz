---
name: passation-service
description: Specialized skill for managing QCM attempt lifecycle, scoring, and results.
---

# ▶️ PASSATION SERVICE SKILL

## Capabilities

### 1. Attempt Lifecycle
- `demarrer(Qcm $qcm, User $user): Tentative`
  - Idempotent: Returns existing `en_cours` attempt if exists.
  - Creates new attempt with `statut = 'en_cours'`.
  - Calculates `date_fin` based on `Qcm::duree_minutes`.
  
- `soumettre(Tentative $tentative, array $reponses): void`
  - Validates all questions answered.
  - Calculates `score_obtenu` (0-100%).
  - Updates `statut = 'termine'`.
  - Records completion timestamp.

### 2. Scoring Algorithm
```php
public function calculerScore(Tentative $tentative): int
{
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
    
    return $pointsMax > 0 
        ? round(($scoreTotal / $pointsMax) * 100) 
        : 0;
}
```

### 3. Answer Validation
- **QCU (Unique):** Selected option must be the correct one.
- **QCM (Multiple):** All correct options selected, no incorrect selected.
- Partial credit: Optional (configurable per QCM).

### 4. Timer Management
- Server-side: Calculate `date_fin` on attempt start.
- Client-side: Countdown display (Alpine.js).
- Auto-submit when time expires.

### 5. Results Display
- Overall score with color coding.
- Per-question breakdown:
  - Correct answers highlighted (green).
  - Incorrect selections marked (red).
  - Feedback displayed per option.
- Time spent.
- Pass/Fail status based on `qcm->seuil_reussite`.

## Implementation Patterns

### Attempt State Machine
```
[demarrer]          [soumettre]         [timeout]
en_cours ────────► termine ◄───────── en_cours
    ▲                                    │
    └──────────── [resume] ──────────────┘
```

### Auto-save Pattern
```javascript
// Alpine.js
init() {
    // Restore from localStorage
    const saved = localStorage.getItem('tentative_' + this.tentativeId);
    if (saved) {
        this.reponses = JSON.parse(saved);
    }
    
    // Auto-save every 30s
    setInterval(() => {
        localStorage.setItem('tentative_' + this.tentativeId, 
            JSON.stringify(this.reponses));
    }, 30000);
}
```

## Output
- Robust attempt management service.
- Accurate scoring algorithm.
- Real-time timer with auto-submit.
- Detailed results interface.
