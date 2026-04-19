# Technical Documentation: SoliQuiz Web (Laravel)

> **Last updated:** April 2026 — Full service-oriented integration complete.

---

## 📌 Architecture Overview

SoliQuiz is a **Laravel 12** platform serving two concurrent purposes:

| Channel | Implementation | Auth |
|---|---|---|
| **Web Interface (Blade)** | Role-based dashboards for Admin, Formateur & Student | Session-based (Laravel UI) |
| **RESTful API** | JSON data provider for `SoliQuiz-mobile` | Laravel Sanctum (token) |

The application follows a **thin-controller / rich-service** pattern. All business logic lives exclusively in `App\Services`. Controllers are responsible only for HTTP input/output and delegating to services.

---

## 🗄️ Database Schema & Models

The data layer is relational, using Eloquent ORM.

### Hierarchy of Entities

```
Seance
  └── UniteApprentissage (code, nom)
        └── Competence (code, libelle, description)
              └── QCM (est_publie, score_reussite, duree_minutes)
                    └── Question
                          └── Option (est_correcte, feedback_specifique)

Classe (nom, promotion)
  ├── formateur → BelongsTo(User[formateur])
  └── etudiants → HasMany(User[etudiant])

User (HasRoles via Spatie, HasApiTokens via Sanctum)
  └── Tentative → HasMany
        ├── Reponse → HasMany
        │     └── ChoixReponse
        └── score_obtenu (0–100%)
```

### Key Model Notes

- **`User`**: Roles managed by `Spatie\Permission`. Helper methods `isAdmin()`, `isFormateur()`, `isEtudiant()` used for redirects. Linked to `Classe` via `classe_id`.
- **`Classe`**: Uses `BelongsTo` for a single `formateur` and `HasMany` for `etudiants`.
- **`QCM`**: `score_reussite` is stored and compared as a **percentage (0–100)**. All displays use `%`.
- **`Tentative`**: `score_obtenu` is also a **percentage (0–100)**.

---

## 🔐 Security & Authentication

- **Web (Session)**: Standard Laravel `auth` middleware. All routes are inside `Route::middleware(['auth'])`.
- **Role Routing**: The `/dashboard` redirect route checks `isAdmin()`, `isFormateur()`, etc. using Spatie roles.
- **API (Sanctum)**: `AuthController@login` issues opaque personal access tokens. All API routes use `auth:sanctum`.

---

## ⚙️ Service Layer (`App\Services`)

All business logic is encapsulated in dedicated services. Controllers inject and delegate to them.

| Service | Responsibility |
|---|---|
| `UserService` | User CRUD, role assignment, password hashing |
| `DashboardService` | Admin-level platform KPIs (user counts, QCM stats, recent activity) |
| `ClasseService` | Classe CRUD, formateur assignment, student listing, classe stats |
| `SeanceService` | Full CRUD for Seances, UniteApprentissage, and Competences |
| `QcmService` | Transactional QCM creation (QCM + Questions + Options in a single DB transaction) |
| `QcmPublicService` | Fetches published QCMs with student-specific attempt context; serves passation payload |
| `PassationService` | Manages full attempt lifecycle: `demarrer()` (start/resume), `soumettre()` (score & finalize) |
| `EtudiantService` | Student dashboard KPIs (`getDashboard()`), paginated attempt history (`historique()`) |

---

## 🧭 Web Routing Structure (`routes/web.php`)

All routes are inside a single `auth` middleware group.

### Admin Module

| Method | URI | Name | Handler |
|---|---|---|---|
| `GET` | `/admin/dashboard` | `admin.dashboard` | `AdminController@dashboard` |
| `GET` | `/admin/utilisateurs` | `admin.utilisateurs` | `AdminController@gestionUtilisateurs` |
| `POST` | `/admin/utilisateurs` | `admin.utilisateurs.store` | `AdminController@storeUser` |
| `DELETE` | `/admin/utilisateurs/{id}` | `admin.utilisateurs.destroy` | `AdminController@destroyUser` |
| `GET` | `/admin/pedagogie` | `admin.pedagogie` | `AdminController@pedagogie` |
| `POST` | `/admin/pedagogie/seance` | `admin.pedagogie.seance.store` | `AdminController@storeSeance` |
| `DELETE` | `/admin/pedagogie/seance/{id}` | `admin.pedagogie.seance.destroy` | `AdminController@destroySeance` |
| `POST` | `/admin/pedagogie/seance/{id}/ua` | `admin.pedagogie.ua.store` | `AdminController@storeUA` |
| `DELETE` | `/admin/pedagogie/ua/{id}` | `admin.pedagogie.ua.destroy` | `AdminController@destroyUA` |
| `POST` | `/admin/pedagogie/ua/{id}/competence` | `admin.pedagogie.competence.store` | `AdminController@storeCompetence` |
| `DELETE` | `/admin/pedagogie/competence/{id}` | `admin.pedagogie.competence.destroy` | `AdminController@destroyCompetence` |
| `GET` | `/admin/classes` | `admin.classes` | `AdminController@gestionClasses` |
| `POST` | `/admin/classes` | `admin.classes.store` | `AdminController@storeClasse` |
| `DELETE` | `/admin/classes/{id}` | `admin.classes.destroy` | `AdminController@destroyClasse` |
| `POST` | `/admin/classes/{id}/formateur` | `admin.classes.assign` | `AdminController@assignFormateur` |

### Formateur Module

| Method | URI | Name | Handler |
|---|---|---|---|
| `GET` | `/formateur/dashboard` | `formateur.dashboard` | `FormateurController@dashboard` |
| `GET` | `/formateur/bibliotheque` | `formateur.bibliotheque` | `FormateurController@bibliotheque` |
| `GET` | `/formateur/qcm/create` | `formateur.qcm.create` | `FormateurController@createQcm` |
| `POST` | `/formateur/qcm` | `formateur.qcm.store` | `FormateurController@storeQcm` |
| `DELETE` | `/formateur/qcm/{id}` | `formateur.qcm.destroy` | `FormateurController@destroyQcm` |
| `GET` | `/formateur/resultats` | `formateur.resultats` | `FormateurController@resultatsCohorte` |

### Student Module

| Method | URI | Name | Handler |
|---|---|---|---|
| `GET` | `/student/dashboard` | `student.dashboard` | `StudentController@dashboard` |
| `GET` | `/student/bibliotheque` | `student.bibliotheque` | `StudentController@bibliotheque` |
| `GET` | `/student/qcm/{id}` | `student.passation` | `StudentController@passation` |
| `POST` | `/student/qcm/{id}` | `student.qcm.submit` | `StudentController@submitQcm` |
| `GET` | `/student/qcm/{id}/resultats` | `student.resultats` | `StudentController@resultats` |

---

## 🖥️ Web Controllers (`App\Http\Controllers\Web`)

### `AdminController`
**Injected services:** `UserService`, `DashboardService`, `SeanceService`, `ClasseService`

| Method | View | Description |
|---|---|---|
| `dashboard()` | `admin/dashboard` | KPIs from `DashboardService`, top QCMs, recent attempts. Quick-access cards to Pédagogie & Classes modules. |
| `gestionUtilisateurs()` | `admin/gestion-utilisateurs` | Paginated user list with Alpine.js search filtering. Create/delete users with role assignment. |
| `storeUser()` | — | Creates user via `UserService`, assigns role via Spatie. |
| `pedagogie()` | `admin/pedagogie` | Loads full tree `Seance → UA → Competences`. Master-detail with Alpine.js expand/collapse. |
| `storeSeance()`, `destroySeance()` | — | Delegates to `SeanceService`. |
| `storeUA()`, `destroyUA()` | — | Delegates to `SeanceService`. |
| `storeCompetence()`, `destroyCompetence()` | — | Delegates to `SeanceService`. |
| `gestionClasses()` | `admin/classes` | Lists classes with assigned formateur (BelongsTo) and student count. |
| `storeClasse()`, `destroyClasse()` | — | Delegates to `ClasseService`. |
| `assignFormateur()` | — | Calls `ClasseService@assignFormateur`, updates `formateur_id` on Classe. |

### `FormateurController`
**Injected services:** `QcmService`, `ClasseService`

| Method | View | Description |
|---|---|---|
| `dashboard()` | `formateur/dashboard` | Shows classes with student counts + QCM metrics. |
| `bibliotheque()` | `formateur/bibliotheque` | QCMs with attempt count (tentatives_count). |
| `createQcm()` | `formateur/creation-qcm` | Blank form for the multi-step QCM builder. |
| `storeQcm()` | — | Delegates full creation to `QcmService` (transactional). |
| `destroyQcm()` | — | Delegates to `QcmService`. |
| `resultatsCohorte()` | `formateur/resultats-cohorte` | Per-QCM stats: moyenne %, taux réussite %, table of all attempts. |

### `StudentController`
**Injected services:** `PassationService`, `EtudiantService`, `QcmPublicService`

| Method | View | Description |
|---|---|---|
| `dashboard()` | `student/dashboard` | KPIs from `EtudiantService@getDashboard` + 5 most recent attempts. |
| `bibliotheque()` | `student/bibliotheque` | Published QCMs from `QcmPublicService@getQcmsDisponibles`, bucketed into À faire / En cours / Terminés. |
| `passation()` | `student/passation` | Starts or resumes an attempt via `PassationService@demarrer`. |
| `submitQcm()` | — | Saves all `ChoixReponse` records, then calls `PassationService@soumettre` to score and finalize. |
| `resultats()` | `student/resultats` | Shows gauge (%), per-question correction with answer highlighting. |

---

## 🎨 Frontend Conventions

- **Framework**: Blade templating + **Alpine.js** for all reactive UI (modals, search, filtering, toggles).
- **CSS**: **Tailwind CSS** (utility-first, configured via Vite).
- **Layout**: All views extend `@extends('components.layout.app')`.
- **Score Display**: All scores are stored and displayed as **percentages (0–100%)**. The field `score_reussite` on `QCM` is also a percentage threshold.
- **UI Pattern**: Every role has a cohesive sticky header navbar with active-link highlighting. Admin navbars use a dark `bg-slate-900` theme. Formateur/Student use a light `bg-white` theme.

---

## 🔗 Key Design Decisions

1. **Service-only business logic**: Controllers never contain queries or complex logic. All delegation goes through `App\Services`.
2. **Percentage scoring**: `score_obtenu` and `score_reussite` are both on a 0–100 scale. No `/20` conversion is performed anywhere in the web layer.
3. **Classe → Formateur**: A `Classe` has a **single** responsible formateur (`BelongsTo`), not a many-to-many. `assignFormateur()` updates `formateur_id` directly.
4. **Passation flow**: `PassationService@demarrer` is idempotent — it returns an existing `en_cours` attempt if one exists, preventing duplicate attempts.
5. **Alpine.js modals**: All create/assign interactions in the admin use Alpine.js `x-show` modals with dynamic form `action` attributes (e.g. `:action="\`/admin/classes/${activeClasseId}/formateur\`"`).
