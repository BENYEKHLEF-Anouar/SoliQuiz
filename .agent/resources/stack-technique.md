# Stack Technique SoliQuiz

## Backend
- **PHP** : 8.4+
- **Framework** : Laravel 12.x
- **Base de données** : MySQL 8.0
- **Authentification** : Laravel Sanctum (API), Session (Web)
- **Autorisations** : Spatie Laravel Permission
- **Pattern** : Thin Controller / Rich Service Layer

## Frontend
- **CSS** : Tailwind CSS 3.x
- **JS** : Alpine.js 3.x (Vanilla JS)
- **Templating** : Blade
- **Build Tool** : Vite
- **UI Kit** : Custom components (no Preline UI)

## Architecture
- **Backend** : `App\Services` layer for all business logic
- **Frontend** : Blade + Alpine.js reactive components
- **API** : RESTful JSON for SoliQuiz-mobile (NativePHP)

## Outils
- **Git** : GitHub Flow
- **Design** : Maquettes HTML/CSS avec Tailwind

## Conventions

### Laravel
- Controllers : HTTP I/O only, delegate to Services
- Services : All business logic, transactions
- Models : Eloquent relationships, scopes, accessors
- FormRequests : Input validation

### Frontend
- Blade components for reusability
- Alpine.js for reactive UI (modals, forms, trees)
- Tailwind utility-first CSS
- Score colors: emerald (≥70%), amber (50-69%), rose (<50%)

## Interdictions Techniques

- ❌ Pas de frameworks SPA purs (React, Vue.js, Angular)
  - **Raison** : Ces frameworks remplacent le templating serveur
- ❌ Pas de jQuery
  - **Raison** : Alpine.js suffit pour la réactivité
- ❌ Logique métier dans les contrôleurs
  - **Raison** : Service Layer obligatoire

## Structure des Dossiers
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Web/          # Admin, Formateur, Student
│   │   └── Api/          # Mobile API
│   └── Requests/         # Form validation
├── Models/              # Eloquent + Relations
├── Services/            # Business logic
└── ...

resources/
├── views/
│   ├── layouts/         # App shell
│   ├── components/      # Reusable Blade
│   ├── admin/           # Admin views
│   ├── formateur/       # Formateur views
│   └── student/         # Student views
├── css/
└── js/