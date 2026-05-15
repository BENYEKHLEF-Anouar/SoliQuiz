# Technical Documentation: SoliQuiz Web (Laravel)

> **Last updated:** May 2026 — Alpine.js npm migration, PDF export, double-click fix.

---

## 📌 Architecture Overview

SoliQuiz is a **Laravel 12** platform serving two concurrent purposes:

| Channel | Implementation | Auth |
|---|---|---|
| **Web Interface (Blade)** | Role-based dashboards for Admin, Formateur & Student | Session-based (Laravel UI) |
| **RESTful API** | JSON data provider for `SoliQuiz-mobile` | Laravel Sanctum (token) |

The application follows a **thin-controller / rich-service** pattern. All business logic lives exclusively in `App\Services`. Controllers are responsible only for HTTP input/output and delegating to services.

---

## 🛠️ Tech Stack

### Backend

| Technology | Version | Role |
|---|---|---|
| **PHP** | `^8.2` | Runtime |
| **Laravel** | `^12.0` | Web framework |
| **Laravel Sanctum** | `^4.3` | API token authentication |
| **Laravel UI** | `^4.6` | Session auth scaffolding (web) |
| **Spatie Permission** | `^7.3` | Role-based access control (`admin`, `formateur`, `etudiant`) |
| **barryvdh/laravel-dompdf** | `^3.1` | Server-side PDF export for pedagogical reports |
| **Laravel Tinker** | `^2.10.1` | REPL for debugging |

### Dev-only (Backend)

| Package | Version | Role |
|---|---|---|
| **Faker** | `^1.23` | Seeder data generation |
| **Laravel Debugbar** | `^4.2` | Dev profiling toolbar |
| **Laravel Pail** | `^1.2.2` | Real-time log viewer (`php artisan pail`) |
| **Laravel Pint** | `^1.24` | PHP code style fixer |
| **Laravel Sail** | `^1.41` | Docker-based local environment |
| **PHPUnit** | `^11.5` | Unit & feature testing |

### Frontend

| Technology | Version | Role |
|---|---|---|
| **Vite** | `^7.0.7` | Asset bundler & dev server |
| **Tailwind CSS v4** | `^4.0.0` | Utility-first CSS framework |
| **Alpine.js** | `^3.15.12` | Reactive UI (modals, search, filtering, dropdowns) |
| **Axios** | `^1.11.0` | HTTP requests from the frontend |
| **@tailwindcss/vite** | `^4.0.0` | Tailwind v4 Vite plugin |
| **laravel-vite-plugin** | `^2.0.0` | Vite ↔ Laravel integration (hot reload, manifest) |
| **@popperjs/core** | `^2.11.6` | Dropdown positioning |
| **concurrently** | `^9.0.1` | Run multiple dev processes together (`composer dev`) |

### Typography

| Font | Weight | Usage |
|---|---|---|
| **Plus Jakarta Sans** | 200–800 (web) | UI — loaded via Google Fonts |
| **Plus Jakarta Sans Black** | 900 (local TTF) | PDF exports only — `public/fonts/PlusJakartaSans-Black.ttf` |

---

## 🚀 Post-Clone Setup

> **Project root** is `SoliQuiz/SoliQuiz/` (the Laravel app lives one level inside the repo root).

### ⚡ Quick Setup (Recommended)
If you have `composer`, `php`, and `npm` installed, you can run this single command to handle everything (install, env, key, migrate, build):

```bash
cd SoliQuiz/SoliQuiz
composer setup
```

---

### 📋 Step-by-Step Installation

If you prefer to run commands manually or need to troubleshoot, follow these steps:

#### 1. Initial Dependencies & Env
```bash
# Enter directory
cd SoliQuiz/SoliQuiz

# Install PHP packages
composer install

# Create .env file
cp .env.example .env

# Generate app key
php artisan key:generate
```

#### 2. Database & Assets
```bash
# Configure your DB in .env first, then:
php artisan migrate --seed

# Install JS packages
npm install

# Build assets (Vite)
npm run build
```

#### 3. Platform Prerequisites
```bash
# Link storage for avatars/public files
php artisan storage:link

# Publish & Migrate Spatie Permissions (if not seeded)
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### Running Locally

```bash
# Option A — All-in-one (recommended): starts Laravel, queue, logs watcher, and Vite
composer dev

# Option B — Two separate terminals
php artisan serve      # Terminal 1: Laravel dev server → http://127.0.0.1:8000
npm run dev            # Terminal 2: Vite HMR dev server
```

### Production Build

```bash
npm run build          # Compiles and fingerprints assets into public/build/
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## ⚠️ Critical Post-Clone Notes

### 1. `storage/fonts/` directory (DomPDF)

DomPDF writes font metric cache files to `storage/fonts/`. This directory **is now tracked by Git** (including the `Plus Jakarta Sans` metrics) to ensure PDF exports work immediately after cloning without manual configuration.

> [!IMPORTANT]
> If you add new fonts, ensure the newly generated `.ufm` files in this directory are committed to the repository.

### 3. Alpine.js loaded via npm (not CDN)

Alpine.js is imported in `resources/js/app.js` via the npm package. **Do not add a CDN `<script>` tag** for Alpine — this would create a double-instance conflict causing the double-click bug.

### 4. Environment variables

Key `.env` values to configure:

```ini
APP_NAME=SoliQuiz
APP_URL=http://localhost:8000

DB_CONNECTION=mysql        # or sqlite for quick local setup
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=soliquiz
DB_USERNAME=root
DB_PASSWORD=

SANCTUM_STATEFUL_DOMAINS=localhost:8000
```

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
        └── score_obtenu (decimal 0–20)
```

### Key Model Notes

- **`User`**: Roles managed by `Spatie\Permission`. Helper methods `isAdmin()`, `isFormateur()`, `isEtudiant()` used for redirects. Linked to `Classe` via `classe_id`.
- **`Classe`**: Uses `BelongsTo` for a single `formateur` and `HasMany` for `etudiants`.
- **`QCM`**: `score_reussite` stored as `decimal(5,1)` on a `/20` scale.
- **`Tentative`**: `score_obtenu` also `decimal(5,1)` on a `/20` scale.

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
| `ResultatService` | Score calculation, feedback generation, per-question correction data |

---

## 🧭 Web Routing Structure (`routes/web.php`)

All routes are inside a single `auth` middleware group.

### Admin Module

| Method | URI | Name | Handler |
|---|---|---|---|
| `GET` | `/admin/dashboard` | `admin.dashboard` | `AdminController@dashboard` |
| `GET` | `/admin/utilisateurs` | `admin.utilisateurs` | `AdminController@gestionUtilisateurs` |
| `POST` | `/admin/utilisateurs` | `admin.utilisateurs.store` | `AdminController@storeUser` |
| `PUT` | `/admin/utilisateurs/{id}` | `admin.utilisateurs.update` | `AdminController@updateUser` |
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
| `PUT` | `/admin/classes/{id}` | `admin.classes.update` | `AdminController@updateClasse` |
| `DELETE` | `/admin/classes/{id}` | `admin.classes.destroy` | `AdminController@destroyClasse` |
| `GET` | `/admin/classes/{id}` | `admin.classes.show` | `AdminController@showClasse` |
| `POST` | `/admin/classes/{id}/etudiants` | `admin.classes.etudiants.store` | `AdminController@addEtudiant` |
| `GET` | `/admin/qcms` | `admin.qcms` | `AdminController@qcms` |

### Formateur Module

| Method | URI | Name | Handler |
|---|---|---|---|
| `GET` | `/formateur/dashboard` | `formateur.dashboard` | `FormateurController@dashboard` |
| `GET` | `/formateur/bibliotheque` | `formateur.bibliotheque` | `FormateurController@bibliotheque` |
| `GET` | `/formateur/qcm/create` | `formateur.qcm.create` | `FormateurController@createQcm` |
| `POST` | `/formateur/qcm` | `formateur.qcm.store` | `FormateurController@storeQcm` |
| `GET` | `/formateur/qcm/{id}/edit` | `formateur.qcm.edit` | `FormateurController@editQcm` |
| `PUT` | `/formateur/qcm/{id}` | `formateur.qcm.update` | `FormateurController@updateQcm` |
| `DELETE` | `/formateur/qcm/{id}` | `formateur.qcm.destroy` | `FormateurController@destroyQcm` |
| `GET` | `/formateur/resultats` | `formateur.resultats` | `FormateurController@resultatsCohorte` |
| `GET` | `/formateur/resultats/export` | `formateur.resultats.export` | `FormateurController@exportResultats` |
| `GET` | `/formateur/resultats/tentative/{id}/export` | `formateur.resultats.tentative.export` | `FormateurController@exportTentative` |

### Student Module

| Method | URI | Name | Handler |
|---|---|---|---|
| `GET` | `/student/dashboard` | `student.dashboard` | `StudentController@dashboard` |
| `GET` | `/student/bibliotheque` | `student.bibliotheque` | `StudentController@bibliotheque` |
| `GET` | `/student/qcm/{id}` | `student.passation` | `StudentController@passation` |
| `POST` | `/student/qcm/{id}` | `student.qcm.submit` | `StudentController@submitQcm` |
| `GET` | `/student/qcm/{id}/resultats` | `student.resultats` | `StudentController@resultats` |

---

## 🎨 Frontend Conventions

- **Reactivity**: **Alpine.js v3** (loaded via npm, started in `resources/js/app.js`). **Never use CDN** — it creates a double-instance conflict.
- **CSS**: **Tailwind CSS v4** (configured via `@tailwindcss/vite` plugin, no `tailwind.config.js` needed).
- **Build**: **Vite 7** bundles `resources/css/app.css` + `resources/js/app.js`.
- **Layout**: All views extend `layouts.app` → `layouts.base`.
- **Score Display**: All scores are stored and displayed on a `/20` scale. `score_reussite` on `QCM` is also `/20`.
- **UI Pattern**: Every role has a cohesive sticky sidebar navbar. Admin sidebars use a dark `bg-slate-900` theme. Formateur/Student use a light `bg-white` theme.
- **Inline `alpine:init`**: Blade views that define Alpine components use `document.addEventListener('alpine:init', () => { Alpine.data(...) })` in an inline `<script>` block. This is safe with the npm loading strategy.

### Alpine.js Loading Strategy

```js
// resources/js/app.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;

// Handles both cases: DOM already parsed (Vite module defer) or still loading
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => Alpine.start());
} else {
    Alpine.start();
}
```

### PDF Export Architecture

- **Library**: `barryvdh/laravel-dompdf` v3.
- **Templates**: `resources/views/exports/resultats-pdf.blade.php` and `tentative-pdf.blade.php`.
- **Font**: `Plus Jakarta Sans 900` loaded via local TTF (`public_path('fonts/PlusJakartaSans-Black.ttf')`) inside `@font-face` in the PDF template CSS. Remote font loading is unreliable in DomPDF.
- **Font cache**: DomPDF writes `.ufm` / `.ufm.json` metric files to `storage/fonts/`. This directory must exist.
- **Config**: `config/dompdf.php` — `enable_remote => true` (for logo image loading via `public_path()`).

---

## 🔗 Key Design Decisions

1. **Service-only business logic**: Controllers never contain queries or complex logic. All delegation goes through `App\Services`.
2. **`/20` scoring**: `score_obtenu` and `score_reussite` are both `decimal(5,1)` on a `/20` scale across all models and views.
3. **Classe → Formateur**: A `Classe` has a **single** responsible formateur (`BelongsTo`), not many-to-many. `formateur_id` is updated directly.
4. **Passation idempotency**: `PassationService@demarrer` returns an existing `en_cours` attempt if one exists, preventing duplicate attempts.
5. **Alpine.js modals**: All create/assign interactions use Alpine.js `x-show` modals with dynamic form `action` attributes (e.g. `:action="\`/admin/classes/${activeClasseId}/formateur\`"`).
6. **No CDN Alpine**: Alpine is loaded exclusively via npm to prevent the double-instance double-click bug. Any inline `alpine:init` listeners will fire correctly because they are registered before `Alpine.start()` is called by Vite's deferred module.
7. **Navigation pattern**: All dynamic links inside `x-for` loops use `@click="window.location.href = ..."` instead of `:href` bindings to guarantee single-click navigation regardless of Alpine's initialization state.
