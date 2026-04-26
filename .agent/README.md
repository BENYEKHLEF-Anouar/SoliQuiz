# SoliQuiz Agent Configuration

This `.agent` folder contains the complete configuration for building the **SoliQuiz** application — a Laravel-based QCM management system for Solicode training center.

## Structure

```
.agent/
├── rules/              # Technical rules and conventions
│   ├── system/         # Master instructions
│   ├── components/     # Component-specific rules
│   ├── data/           # Database schema, service layer rules
│   ├── roles/          # RBAC, access control
│   └── visual/         # Visual identity
├── skills/             # Specialized skill definitions (3 core skills)
│   ├── soliquiz-architect/   # Database & Models
│   ├── soliquiz-builder/     # Business Logic & Services
│   └── soliquiz-developer/   # UI Components & Frontend
└── workflows/          # Execution workflows by module
    ├── admin/          # Admin module workflows
    ├── formateur/      # Formateur module workflows
    ├── student/        # Student module workflows
    └── shared/         # Common/shared workflows
```

## Core Skills

| Skill | Domain | Contents |
|-------|--------|----------|
| **soliquiz-architect** | Database layer | Migrations order, Eloquent models, all relationships (Seance→UA→Competence→QCM), User/Classe models, decimal(5,1) scores |
| **soliquiz-builder** | Business logic | 9 services (User, Dashboard, Classe, Seance, Qcm, QcmPublic, Passation, Etudiant), scoring algorithm /20, KPIs |
| **soliquiz-developer** | Frontend | Blade components, Alpine.js SPA patterns, Lucide icons, Ocean Teal primary, Tailwind |

## Quick Commands

| Command | Description |
|---------|-------------|
| `/install` | Initial Laravel setup |
| `/phase1-auth` | Authentication & roles |
| `/admin-dashboard` | Build admin dashboard |
| `/admin-users` | User management module |
| `/admin-pedagogie` | Pédagogie management |
| `/admin-classes` | Class management |
| `/formateur-dashboard` | Formateur dashboard |
| `/formateur-biblio` | QCM library |
| `/formateur-create-qcm` | QCM creation wizard |
| `/formateur-resultats` | Results & analytics |
| `/student-dashboard` | Student dashboard |
| `/student-biblio` | Available QCMs |
| `/student-passation` | QCM taking interface |
| `/student-resultats` | Results display |
| `/api-mobile` | Mobile API endpoints |

## Application Overview

**SoliQuiz** is a comprehensive QCM (Quiz) management platform with three user roles:

- **Admin:** Full platform management (Users, Classes, Pédagogie tree, QCM supervision)
- **Formateur:** Create QCMs, manage classes, view cohort results
- **Student:** Take QCMs, view scores and progress

### Key Features

- Hierarchical pédagogie structure (Seance → UA → Compétence → QCM)
- SPA-like UI with Alpine.js (no full page reloads)
- QCM builder with /20 scale points system
- Timed attempts with auto-submit
- Real-time scoring on /20 scale
- Role-based dashboards with KPIs
- RESTful API for mobile app

### Tech Stack

- **Backend:** PHP 8.4, Laravel 12.x
- **Frontend:** Blade Templates, Tailwind CSS (Ocean Teal), Alpine.js
- **Build Tool:** Vite
- **Database:** MySQL 8.0
- **Auth:** Laravel Sanctum (API), Session (Web)
- **Permissions:** Spatie Laravel Permission
- **Icons:** Lucide

## Usage

1. Start with `/install` to set up the Laravel project
2. Run `/phase1-auth` for authentication system
3. Execute module workflows (`/admin-*`, `/formateur-*`, `/student-*`)
4. Use `/api-mobile` for mobile API endpoints

Each workflow contains:
- **Command:** Trigger command
- **Dependencies:** Required skills and rules
- **Execution Steps:** Detailed implementation guide
- **Validation Checklist:** Verification steps

## File References

### Rules
- `rules/system/master_instructions.md` — SPA architecture, decimal scores, French comments, Lucide icons
- `rules/data/qcm_schema.md` — Database ERD and relationships
- `resources/stack-technique.md` — Tech stack with Ocean Teal primary color

### Skills
- `skills/soliquiz-architect/SKILL.md` — Database architecture, migrations, models
- `skills/soliquiz-builder/SKILL.md` — Services, QCM engine, scoring /20, passation
- `skills/soliquiz-developer/SKILL.md` — Blade components, Alpine.js SPA patterns

### Workflows
- `workflows/admin/*.md` — Admin module workflows
- `workflows/formateur/*.md` — Formateur module workflows
- `workflows/student/*.md` — Student module workflows

---

**Project:** SoliQuiz  
**Author:** BENYEKHLEF Anouar  
