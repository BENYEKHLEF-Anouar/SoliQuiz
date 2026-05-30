---
trigger: always_on
type: rule
id: atomic-design
---

# ⚛️ ATOMIC DESIGN PRINCIPLES — SOLIQUIZ

## 1. Atoms
- Smallest building block.
- Examples: `Button`, `Input`, `Badge`, `Avatar`, `Icon`.
- Dependency: None.
- **SoliQuiz Specific:**
  - `ScoreBadge` — Displays percentage with color coding (success/danger/warning).
  - `Timer` — Countdown display for QCM duration.
  - `StatusPill` — Published/Draft/Archived states.

## 2. Molecules
- Group of atoms.
- Examples: `FormGroup`, `SearchBar`, `UserCard`.
- Dependency: Must use Atoms.
- **SoliQuiz Specific:**
  - `QcmCard` — Title, status, score, action buttons.
  - `QuestionEditor` — Enoncé input + options list.
  - `AttemptRow` — Student name, score, date, actions.

## 3. Organisms (Components)
- Autonomous functional units.
- Examples: `Sidebar`, `QcmList`, `PassationInterface`.
- Dependency: Uses Molecules and Atoms.
- **SoliQuiz Specific:**
  - `PedagogieTree` — Seance → UA → Competence hierarchical tree.
  - `QcmBuilder` — Multi-step QCM creation wizard.
  - `ResultsDashboard` — Charts + stats for formateur.
  - `StudentDashboard` — KPI cards + recent attempts.

## Manifest Registration
- Every newly created component must be documented in the UI kit reference.
- Use consistent naming: `{Role}{ComponentName}` (e.g., `AdminUserTable`, `FormateurQcmList`).
