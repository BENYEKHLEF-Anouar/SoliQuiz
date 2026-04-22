# SoliQuiz — AI Build Agent

> **Mission**: Build SoliQuiz from scratch as a single-page Laravel application using Alpine.js for all in-app navigation (no full page reloads). The UI must match the enhanced mockups in `Maquettage/3.maquettage/mockups/`.

---

## 1. Rules

### Architecture Rules
- **SPA via Alpine.js**: All authenticated pages load inside a single `app.blade.php` shell. Navigation swaps `x-show` / `x-transition` sections or uses `Alpine.store('router')` — NO full-page reloads for in-app links.
- **Blade Components**: Every reusable UI piece is a Blade component under `resources/views/components/`. Follow Atomic Design: atoms → molecules → organisms → pages.
- **Service Layer**: All business logic lives in `app/Services/`. Controllers are thin — they call a service and return a view or redirect.
- **Eloquent-Only Data Access**: Never use `DB::table()` or raw SQL for writes. Always use Eloquent models + relationships so observers and casts fire.
- **No Double-Hashing**: The User model uses `'password' => 'hashed'` cast. Never call `Hash::make()` before setting `password` — just assign the plain value.
- **Single Source of Truth for Roles**: Use `type_profil` column on `users` as the role discriminant. The Spatie `role` name MUST mirror `type_profil` (`admin`, `formateur`, `etudiant`). Never add a separate `role` column.
- **Decimal Scores**: All score columns (`score_obtenu`, `score_reussite`) are `decimal(5,1)` on a /20 scale. Model casts must be `'decimal:1'`.
- **Every List Has**: Pagination (cursor or page-based), a search input, and filter dropdowns. Never render unbounded lists or raw HTML tables.
- **Breadcrumbs**: Every page (except login & landing) renders a `<x-breadcrumbs>` component showing the navigation path.
- **Toasts**: All CUD operations dispatch a toast via `Alpine.store('toasts').add(...)`. Toasts auto-dismiss after 4s with a progress bar.
- **Authorization**: Every route is protected by `auth` middleware. Role-specific routes use `role:admin`, `role:formateur`, `role:etudiant`. The `EnsureUserHasRole` middleware checks `type_profil` and lets admin bypass.

### Code Style Rules
- **Imports at the top**: Every `use` statement at the file top. Never inline imports.
- **No emojis in code**: Unless the user explicitly requests them.
- **French comments**: The project domain is francophone; keep comments and UI labels in French.
- **Tailwind-first**: No custom CSS unless Tailwind cannot express it. Use the `primary` color scale (Ocean Teal) defined in `tailwind.config.js`.
- **Lucide icons**: Always use `<x-lucide-icon name="..." />` component. Never inline SVGs manually.
- **Alpine conventions**: Use `x-data`, `x-show`, `x-transition`, `$dispatch`, `Alpine.store()` for all interactivity. No jQuery.

### Data Integrity Rules
- **Foreign keys**: Every FK column must have `constrained()` + cascade/cascadeOnDelete in migrations.
- **Fillable arrays**: Every model declares `$fillable` explicitly. Never use `$guarded = []`.
- **Casts**: All enum-like columns (`type_profil`, `statut`, `type`) use Laravel casts or accessors. Date columns use `datetime` cast.
- **Observers**: `TentativeObserver` auto-closes QCMs when all students finish. Register in `AppServiceProvider`.

---

## 2. Skills

### Skill: scaffold-project
**Trigger**: Starting the project from zero.
**Steps**:
1. `composer create-project laravel/laravel SoliQuiz`
2. Install packages: `spatie/laravel-permission`, `laravel/sanctum`, `doctrine/dbal`
<!-- 2. Install packages: `spatie/laravel-permission`, `laravel/sanctum`, `doctrine/dbal` -->
3. Configure `tailwind.config.js` with `primary` color scale (Ocean Teal: `hsl(190, 80%, 45%)` base, 50-900 variants), fonts (Outfit for headings, Inter for body).
4. Install & configure Alpine.js + `@alpinejs/persist` + `@alpinejs/morph` via Vite.
5. Install Lucide icons: `npm install lucide` + create `x-lucide-icon` Blade component.
6. Create `.env.example` with placeholder secrets (never real passwords).
7. Create the SPA shell: `resources/views/layouts/app.blade.php` with sidebar, topbar, breadcrumbs slot, toast container, and `<main>` content area.

### Skill: create-model-and-migration
**Trigger**: Adding a new domain entity.
**Steps**:
1. `php artisan make:model X -m` (and `-c` / `-s` if needed).
2. Write the migration with correct column types, FK constraints, and defaults.
3. Define `$fillable`, `$casts`, and relationships on the model.
4. Add the Spatie `HasRoles` trait to User model.
5. Run `php artisan migrate` and verify.

### Skill: build-spa-page
**Trigger**: Creating a new page/view.
**Steps**:
1. Create a Blade file under `resources/views/{role}/page.blade.php`.
2. The file `@extends('layouts.app')` and `@section('content')` with an Alpine component (`x-data="pageName()"`).
3. All sub-views (modals, panels) use `x-show` + `x-transition` — no separate routes.
4. Add the route in `routes/web.php` with proper middleware.
5. Add breadcrumbs via `@section('breadcrumbs', ...)`.
6. Ensure pagination uses `{{ $items->links() }}` with a custom Tailwind pagination component.
7. Add search + filter inputs wired to Alpine state that debounces to the backend via `fetch()` or `$wire` calls.

### Skill: create-blade-component
**Trigger**: Need a reusable UI element.
**Steps**:
1. `php artisan make:component ui/ComponentName` (creates class + view).
2. Define props in the component class (`__construct`).
3. Build the Blade view with Tailwind classes matching the charte graphique.
4. Use `<x-ui.component-name>` throughout the app.
5. Document the component in a comment block at the top.

### Skill: implement-crud
**Trigger**: Need full CRUD for an entity.
**Steps**:
1. Create the Service class with `list()`, `create()`, `update()`, `delete()` methods.
2. Create the Controller (thin — delegates to Service).
3. Create the index view with search, filters, paginated cards (NOT tables).
4. Create a modal-based form for create/edit (Alpine `x-show`).
5. Add delete confirmation modal.
6. Wire all actions to return toast notifications.
7. Add routes with proper middleware and role guards.

### Skill: build-qcm-editor
**Trigger**: Implementing the QCM creation/editing interface.
**Steps**:
1. Create `qcmBuilder` Alpine data component.
2. Question type selector: `choix_unique` → radio-style correct answer (only 1); `choix_multiple` → checkbox-style (multiple correct).
3. When type switches from `choix_multiple` → `choix_unique`, call `normalizeCorrectOptions()` to keep only the first correct.
4. Options grid: responsive `grid-cols-1 md:grid-cols-2`, correct options highlighted with emerald border.
5. Points system: /20 scale, auto-equalize button, warning modal if total ≠ 20.
6. Competence selector: dynamic based on selected UA.
7. Submit: validate server-side (unique=1 correct, multiple≥1 correct), then redirect to bibliothèque with success toast.

### Skill: build-passation-engine
**Trigger**: Implementing the student quiz-taking experience.
**Steps**:
1. Full-screen focus mode: no sidebar, only timer + question + navigation.
2. Timer: countdown from `duree_minutes`, auto-submit on expiry.
3. Question navigation: prev/next buttons, progress indicator.
4. Answer selection: radio for `unique`, checkbox for `multiple`.
5. Submit: call `PassationService::submit()`, calculate score, redirect to results page.
6. Observer: `TentativeObserver` auto-closes QCM when all students in classe have submitted.

---

## 3. Workflows

### Workflow: fresh-install
**When to use**: Setting up the project on a new machine.
```
1. Clone repo
2. cp .env.example .env && composer install && npm install
3. php artisan key:generate
4. Create MySQL database and configure .env
5. php artisan migrate
6. php artisan db:seed
7. npm run build
8. php artisan serve
```

### Workflow: add-new-page
**When to use**: Adding a new screen to the app.
```
1. Create the Blade view under resources/views/{role}/
2. Add Alpine data component in a <script> block or separate JS file
3. Create the Controller method (thin, delegates to Service)
4. Add route in routes/web.php with middleware('auth') and role middleware
5. Add sidebar link in the navigation component
6. Add breadcrumb definition
7. Test: visit route, verify SPA navigation, check role guard
```

### Workflow: build-entity-crud
**When to use**: Adding a full CRUD feature (e.g., Classes, Seances).
```
1. php artisan make:model Entity -msfc
2. Write migration (columns, FKs, constraints)
3. Define model (fillable, casts, relationships)
4. Create EntityService with list/create/update/delete methods
5. Create EntityController (index, store, update, destroy)
6. Build index view: search bar + filter dropdowns + paginated cards
7. Build create/edit modal (Alpine x-show, form validation)
8. Build delete confirmation modal
9. Add routes with role middleware
10. Add sidebar link + breadcrumb
11. Seed test data
12. Test full CRUD cycle
```

### Workflow: deploy
**When to use**: Pushing to production.
```
1. php artisan config:cache
2. php artisan route:cache
3. php artisan view:cache
4. npm run build
5. php artisan migrate --force
6. Verify with php artisan route:list && php artisan migrate:status
```

---

## 4. Domain Models

### Entity-Relationship Diagram

```
User (type_profil: admin|formateur|etudiant)
 ├── hasMany QCM (as formateur_id)           [formateur]
 ├── hasMany Seance (as user_id)             [formateur/admin]
 ├── hasMany UniteApprentissage (as user_id) [formateur/admin]
 ├── belongsTo Classe (as classe_id)         [etudiant]
 ├── belongsToMany Classe (pivot)            [formateur → classeGeree]
 └── hasMany Tentative (as etudiant_id)      [etudiant]

Classe
 ├── hasMany User (etudiants)
 ├── belongsTo User (formateur_id)
 └── hasMany QCM

Seance
 ├── belongsTo User (user_id)
 └── hasMany UniteApprentissage

UniteApprentissage
 ├── belongsTo Seance
 ├── belongsTo User (user_id)
 ├── hasMany Competence
 └── hasMany QCM

Competence
 ├── belongsTo UniteApprentissage
 └── belongsToMany QCM (pivot: competence_qcm)

QCM
 ├── belongsTo User (formateur_id)
 ├── belongsTo UniteApprentissage
 ├── belongsTo Classe
 ├── belongsToMany Competence (pivot)
 ├── hasMany Question
 └── hasMany Tentative

Question
 ├── belongsTo QCM
 ├── hasMany Option
 └── hasMany Reponse

Option
 ├── belongsTo Question
 └── hasMany ChoixReponse

Tentative
 ├── belongsTo User (etudiant)
 ├── belongsTo QCM
 └── hasMany Reponse

Reponse
 ├── belongsTo Tentative
 ├── belongsTo Question
 └── hasMany ChoixReponse

ChoixReponse
 ├── belongsTo Reponse
 └── belongsTo Option
```

### Migration Order
1. `users` — name, email, password, type_profil, matricule, code_etudiant, classe_id
2. `classes` — nom, description, formateur_id (FK users)
3. `seances` — nom, date, user_id (FK users)
4. `unites_apprentissage` — nom, code, seance_id (FK), user_id (FK users)
5. `competences` — libelle, code, unite_apprentissage_id (FK)
6. `qcms` — titre, duree_minutes, score_reussite (decimal 5,1), statut, formateur_id (FK), unite_apprentissage_id (FK), classe_id (FK)
7. `competence_qcm` — pivot (competence_id, qcm_id)
8. `questions` — texte, type (unique|multiple), points, explication_feedback, qcm_id (FK)
9. `options` — texte, est_correcte (boolean), feedback_specifique, question_id (FK)
10. `tentatives` — date_debut, date_fin, score_obtenu (decimal 5,1), statut, etudiant_id (FK), qcm_id (FK)
11. `reponses` — tentative_id (FK), question_id (FK)
12. `choix_reponses` — reponse_id (FK), option_id (FK)
13. Spatie permission tables (via `permission:tables` migration)
14. `personal_access_tokens` (Sanctum)

---

## 5. User Roles & Functionalities

### 5.1 Administrateur (`type_profil: admin`)

**Dashboard** (`/admin/dashboard`)
- KPI cards: total users, total QCMs, active QCMs, average score
- System health: recent activity feed
- Quick actions: create user, create seance

**User Management** (`/admin/users`)
- Paginated card list of all users with search (name/email) + filter (type_profil, classe)
- Create user modal: nom, prénom, email, password, type_profil, classe_id (if etudiant), matricule (if formateur)
- Edit user modal: same fields, password optional
- Delete user: confirmation modal → soft approach (warn if has tentatives)
- Bulk assign: change classe for multiple etudiants

**Pedagogical Structure** (`/admin/pedagogie`)
- Seances: paginated list with search, create/edit/delete
- Unites d'apprentissage: nested under seance, create/edit/delete
- Competences: nested under UA, create/edit/delete
- All entities have `user_id` set to the admin creating them

**QCM Supervision** (`/admin/qcms`)
- Read-only view of all QCMs with stats
- Can change statut (close a QCM manually)
- View results per QCM

**Workflow**:
```
Login → Admin Dashboard
  → Manage Users (search, filter, CRUD)
  → Manage Pedagogie (seances → UAs → competences)
  → Monitor QCMs (view stats, close manually)
  → Profile (edit name, email, password)
```

### 5.2 Formateur (`type_profil: formateur`)

**Dashboard** (`/formateur/dashboard`)
- KPIs: my QCMs count, active QCMs, average student score, pass rate
- Classe selector dropdown → filters all dashboard data
- Alert section: QCMs expiring soon, low-score students
- Competence progress bars for selected classe

**Bibliothèque QCM** (`/formateur/bibliotheque`)
- Paginated card grid of my QCMs with search (titre) + filter (statut, classe, UA)
- Each card shows: titre, statut badge, question count, tentative count, average score
- Actions: edit, toggle statut, close, duplicate, delete

**QCM Editor** (`/formateur/qcm/create`, `/formateur/qcm/{id}/edit`)
- SPA-style editor with Alpine.js `qcmBuilder`
- Header: titre input, statut selector, total points badge (/20)
- Settings row: UA selector → dynamic competence checkboxes, classe, durée, seuil
- Question cards: type selector (choix_unique/choix_multiple), énoncé, points
  - Options: radio (unique) or checkbox (multiple) for correct answer, highlighted emerald
  - Feedback per option + per question
- Add question button, floating finalize bar
- Points warning modal if total ≠ 20

**Résultats Cohorte** (`/formateur/resultats`)
- Select classe → see student performance table (paginated, searchable)
- Per-student: average score, pass rate, last attempt date
- Per-QCM: score distribution, pass/fail count
- Export capability (future)

**Workflow**:
```
Login → Formateur Dashboard (select classe)
  → Bibliothèque (search, filter, CRUD QCMs)
    → Create/Edit QCM (SPA editor)
  → Résultats Cohorte (student performance)
  → Profile (edit name, email, password)
```

### 5.3 Étudiant / Apprenant (`type_profil: etudiant`)

**Dashboard** (`/student/dashboard`)
- Hero: greeting + classe name
- Urgent section: QCMs pending (not yet attempted, with deadline)
- Competence progress bars (based on past attempts)
- Recent history: last 5 tentatives with score + badge (pass/fail)

**Bibliothèque** (`/student/bibliotheque`)
- Paginated card grid of available (public) QCMs
- Search (titre) + filter (UA, competence)
- Each card: titre, UA name, durée, question count, statut badge
- "Commencer" button → launches passation

**Passation QCM** (`/student/qcm/{id}`)
- Full-screen focus mode (no sidebar, no distractions)
- Timer: countdown from `duree_minutes`, red warning at 2min, auto-submit at 0
- Question display: énoncé + choice list (radio for unique, checkbox for multiple)
- Navigation footer: prev/next, progress "3/10", submit button
- Submit → score calculated → redirect to results

**Résultats** (`/student/resultats`)
- Paginated list of past tentatives with search + filter (QCM, statut)
- Each entry: QCM titre, score (/20), pass/fail badge, date
- Click → detailed view per question (correct/wrong, feedback)

**Workflow**:
```
Login → Student Dashboard
  → Bibliothèque (browse available QCMs)
    → Passation (take quiz, timer, submit)
      → Résultats (view score, details)
  → Mes Résultats (history of all attempts)
  → Profile (edit name, email, password)
```

### 5.4 Public (Unauthenticated)

**Landing Page** (`/`)
- Hero section with brand pitch
- Feature cards (3 key benefits)
- Role descriptions (formateur / apprenant)
- CTA: "Se connecter" button
- Footer with copyright

**Login** (`/login`)
- Clean centered form: email + password
- Brand logo + tagline
- "Mot de passe oublié?" link
- Submit → redirects to role-appropriate dashboard

---

## 6. Page Inventory (from Maquettage)

| # | Page | Mockup File | Role | SPA Route |
|---|------|-------------|------|-----------|
| 01 | Login | `01-auth-login.html` | public | `/login` |
| 02 | Apprenant Dashboard | `02-apprenant-dashboard.html` | etudiant | `/student/dashboard` |
| 03 | Passation QCM | `03-apprenant-passation-qcm.html` | etudiant | `/student/qcm/{id}` |
| 04 | Formateur Dashboard | `04-formateur-dashboard.html` | formateur | `/formateur/dashboard` |
| 05 | Création QCM | `05-formateur-creation-qcm.html` | formateur | `/formateur/qcm/create` |
| 06 | Admin Dashboard | `06-admin-dashboard.html` | admin | `/admin/dashboard` |
| 07 | Résultats Apprenant | `07-apprenant-resultats.html` | etudiant | `/student/resultats` |
| 08 | Bibliothèque Apprenant | `08-apprenant-bibliotheque.html` | etudiant | `/student/bibliotheque` |
| 09 | Bibliothèque Formateur | `09-formateur-bibliotheque.html` | formateur | `/formateur/bibliotheque` |
| 10 | Résultats Cohorte | `10-formateur-resultats-cohorte.html` | formateur | `/formateur/resultats` |
| 11 | Gestion Utilisateurs | `11-admin-gestion-utilisateurs.html` | admin | `/admin/users` |
| 12 | Landing Publique | `12-public-landing.html` | public | `/` |

---

## 7. UI / UX Specifications

### Color System (Tailwind Config)
```js
primary: {
    50:  '#f0fdfa',
    100: '#ccfbf1',
    200: '#99f6e4',
    300: '#5eead4',
    400: '#2dd4bf',
    500: '#14b8a6',  // Ocean Teal base
    600: '#0d9488',
    700: '#0f766e',
    800: '#115e59',
    900: '#134e4a',
}
// Semantic: success=emerald, error=rose, warning=amber, info=blue
```

### Typography
- Headings: `font-heading` → Outfit (geometric, modern)
- Body: `font-body` → Inter (readable, clean)
- Labels: `text-[10px] font-black uppercase tracking-widest italic`
- Values: `text-xl font-black italic`

### Component Patterns
- **Cards**: `rounded-3xl border border-slate-100 shadow-sm p-6 md:p-8`
- **Modals**: `rounded-[2rem]` with backdrop blur, `x-show` + `x-transition`
- **Buttons Primary**: `bg-primary-500 text-white rounded-2xl font-black uppercase tracking-widest`
- **Buttons Secondary**: `bg-white border border-slate-100 rounded-2xl font-bold`
- **Inputs**: `bg-slate-50 border-none rounded-2xl py-4 px-6 focus:bg-white focus:ring-4 focus:ring-primary-500/10`
- **Badges/Statut**: `rounded-xl px-3 py-1 text-[10px] font-black uppercase tracking-widest`
- **Pagination**: Custom component with prev/next + page numbers, no raw table-style

### Toast System
```js
Alpine.store('toasts', {
    items: [],
    add(message, type = 'success') {
        const id = Date.now();
        this.items.push({ id, message, type, progress: 100 });
        setTimeout(() => this.remove(id), 4000);
    },
    remove(id) {
        this.items = this.items.filter(t => t.id !== id);
    }
});
```
Toast component: fixed top-right, color-coded (success=emerald, error=rose, warning=amber), auto-dismiss with shrinking progress bar.

### Breadcrumb Component
```html
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'route' => 'formateur.dashboard'],
    ['label' => 'Bibliothèque', 'route' => 'formateur.bibliotheque'],
    ['label' => 'Créer QCM'],  // last item = current page (no link)
]" />
```
Rendered as: `Dashboard / Bibliothèque / Créer QCM` with chevron separators.

### SPA Router Pattern
```js
Alpine.store('router', {
    currentPath: window.location.pathname,
    navigate(url) {
        // Use fetch to get HTML fragment, swap into #spa-content
        // Update URL via history.pushState
        // Dispatch 'spa:navigated' event for sidebar active-state
    }
});
```
All internal links use `@click.prevent="$store.router.navigate(url)"` instead of `<a href>`.

---

## 8. Build Order (Phases)

### Phase 1: Foundation
- [ ] Scaffold Laravel project + all npm/composer packages
- [ ] Configure Tailwind (primary color, fonts) + Vite
- [ ] Create `app.blade.php` SPA shell (sidebar, topbar, toast container, breadcrumbs slot)
- [ ] Create Blade components: `<x-ui.button>`, `<x-ui.input>`, `<x-ui.select>`, `<x-ui.modal>`, `<x-ui.badge>`, `<x-ui.card>`, `<x-ui.pagination>`, `<x-ui.toast>`, `<x-breadcrumbs>`, `<x-lucide-icon>`
- [ ] Alpine stores: `router`, `toasts`, `sidebar`

### Phase 2: Auth + Roles
- [ ] Login page (mockup 01)
- [ ] Spatie roles/permissions seeder (admin, formateur, etudiant)
- [ ] `EnsureUserHasRole` middleware
- [ ] Landing page (mockup 12)
- [ ] Profile page (edit name/email/password)

### Phase 3: Domain Models + Migrations
- [ ] All 12 migrations in correct FK order
- [ ] All Eloquent models with fillable, casts, relationships
- [ ] User model: `type_profil`, `hashed` password cast, `HasRoles` trait
- [ ] TentativeObserver registered

### Phase 4: Admin Pages
- [ ] Admin dashboard (mockup 06)
- [ ] User management with CRUD + search/filter/pagination (mockup 11)
- [ ] Pedagogical structure management (seances → UAs → competences)

### Phase 5: Formateur Pages
- [ ] Formateur dashboard (mockup 04)
- [ ] Bibliothèque QCM with search/filter/pagination (mockup 09)
- [ ] QCM editor with responsive correct-answer UI (mockup 05)
- [ ] Résultats cohorte (mockup 10)

### Phase 6: Étudiant Pages
- [ ] Student dashboard (mockup 02)
- [ ] Bibliothèque with search/filter/pagination (mockup 08)
- [ ] Passation engine with timer + focus mode (mockup 03)
- [ ] Résultats page (mockup 07)

### Phase 7: Seeders + Polish
- [ ] CSV seed data with all FKs correct (user_id on seances/UAs)
- [ ] UserSeeder uses Eloquent (not DB::insert) + assigns Spatie roles
- [ ] DatabaseSeeder truncates all tables including Spatie tables
- [ ] Run `php artisan migrate:fresh --seed` and verify all pages
- [ ] Cross-browser test, responsive test, role-gate test

---

## 9. Key Files Reference

| Purpose | Path |
|---------|------|
| SPA Shell | `resources/views/layouts/app.blade.php` |
| Sidebar | `resources/views/components/layout/sidebar.blade.php` |
| Toast Container | `resources/views/components/ui/toast.blade.php` |
| Breadcrumbs | `resources/views/components/breadcrumbs.blade.php` |
| Lucide Icon | `resources/views/components/lucide-icon.blade.php` |
| QCM Builder JS | Inline in `creation-qcm.blade.php` / `edit-qcm.blade.php` |
| Alpine Stores | `resources/js/alpine-stores.js` (router, toasts, sidebar) |
| User Model | `app/Models/User.php` |
| QCM Model | `app/Models/QCM.php` |
| Passation Service | `app/Services/PassationService.php` |
| QCM Service | `app/Services/QcmService.php` |
| Dashboard Service | `app/Services/DashboardService.php` |
| Tentative Observer | `app/Observers/TentativeObserver.php` |
| Role Middleware | `app/Http/Middleware/EnsureUserHasRole.php` |
| Routes (Web) | `routes/web.php` |
| Routes (API) | `routes/api.php` |
| Tailwind Config | `tailwind.config.js` |
| Charte Graphique | `Maquettage/3.maquettage/charte-graphique/charte.md` |
| Mockup HTMLs | `Maquettage/3.maquettage/mockups/*.html` |
