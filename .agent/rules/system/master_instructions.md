---
trigger: always_on
type: rule
id: master-instructions
---

# SOLIQUIZ MASTER INSTRUCTIONS

## Architecture Rules

### SPA via Alpine.js
- All authenticated pages load inside a single `app.blade.php` shell
- Navigation swaps `x-show` / `x-transition` sections or uses `Alpine.store('router')`
- NO full-page reloads for in-app links

### Blade Components
- Every reusable UI piece is a Blade component under `resources/views/components/`
- Follow Atomic Design: atoms → molecules → organisms → pages

### Service Layer
- All business logic lives in `app/Services/`
- Controllers are thin — they call a service and return a view or redirect

### Eloquent-Only Data Access
- Never use `DB::table()` or raw SQL for writes
- Always use Eloquent models + relationships so observers and casts fire

### No Double-Hashing
- The User model uses `'password' => 'hashed'` cast
- Never call `Hash::make()` before setting `password` — just assign the plain value

### Single Source of Truth for Roles
- Use `type_profil` column on `users` as the role discriminant
- The Spatie `role` name MUST mirror `type_profil` (`admin`, `formateur`, `etudiant`)
- Never add a separate `role` column

### Decimal Scores
- All score columns (`score_obtenu`, `score_reussite`) are `decimal(5,1)` on a /20 scale
- Model casts must be `'decimal:1'`

### Every List Has
- Pagination (cursor or page-based)
- A search input
- Filter dropdowns
- Never render unbounded lists or raw HTML tables

### Breadcrumbs
- Every page (except login & landing) renders a `<x-breadcrumbs>` component

### Toasts
- All CUD operations dispatch a toast via `Alpine.store('toasts').add(...)`
- Toasts auto-dismiss after 4s with a progress bar

### Authorization
- Every route is protected by `auth` middleware
- Role-specific routes use `role:admin`, `role:formateur`, `role:etudiant`

## Code Style Rules

### Imports
- Every `use` statement at the file top
- Never inline imports

### No Emojis
- No emojis in code unless explicitly requested

### French Comments
- Keep comments and UI labels in French

### Tailwind-First
- No custom CSS unless Tailwind cannot express it
- Use the `primary` color scale (Ocean Teal) defined in `tailwind.config.js`

### Lucide Icons
- Always use `<x-lucide-icon name="..." />` component
- Never inline SVGs manually

### Alpine Conventions
- Use `x-data`, `x-show`, `x-transition`, `$dispatch`, `Alpine.store()` for all interactivity
- No jQuery

## Data Integrity Rules

### Foreign Keys
- Every FK column must have `constrained()` + cascade/cascadeOnDelete in migrations

### Fillable Arrays
- Every model declares `$fillable` explicitly
- Never use `$guarded = []`

### Casts
- All enum-like columns (`type_profil`, `statut`, `type`) use Laravel casts or accessors
- Date columns use `datetime` cast

### Observers
- `TentativeObserver` auto-closes QCMs when all students finish
- Register in `AppServiceProvider`
