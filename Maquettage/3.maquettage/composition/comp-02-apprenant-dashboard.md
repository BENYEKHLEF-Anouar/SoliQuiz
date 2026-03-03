# Composition - Page Espace Apprenant (Tableau de Bord)

## Source
- **Wireframe** : `2.organisation-contenu/wireframes/02-apprenant-dashboard.md`
- **Mockup Final** : `3.maquettage/mockups/02-apprenant-dashboard.html`

## Structure de Composition

```
HEADER (Navigation Étudiant)
└── navbar-apprenant (Molécule) → components-lib/molecules/navbar-apprenant/
    ├── logo-brand (Atom) → components-lib/atoms/logo/
    ├── nav-menu (Molécule) → components-lib/molecules/nav-menu/  
    │   └── nav-link (Atom) → components-lib/atoms/link/
    └── user-dropdown (Molécule) → components-lib/molecules/user-dropdown/
        ├── avatar (Atom) → components-lib/atoms/avatar/
        └── button-icon (Atom) → components-lib/atoms/button/

HERO (Vue Globale)
└── hero-dashboard (Molécule) → components-lib/molecules/hero-dashboard/
    ├── title (Atom h1) → components-lib/atoms/title/
    └── badge-kpi (Atom) → components-lib/atoms/badge/

SECTION (Test en attente - Urgent)
└── section-urgent (Molécule) → components-lib/molecules/section/
    ├── title (Atom h2) → components-lib/atoms/title/
    └── card-qcm (Molécule) → components-lib/molecules/card-qcm/
        ├── text-title (Atom h3) → components-lib/atoms/title/
        └── button (Atom) → components-lib/atoms/button/

SECTION (Progression par Compétence)
└── section-progress (Molécule) → components-lib/molecules/section/
    ├── title (Atom h2) → components-lib/atoms/title/
    ├── progress-item (Molécule) → components-lib/molecules/progress-item/
    │   ├── label (Atom text) → components-lib/atoms/text/
    │   └── progress-bar (Atom) → components-lib/atoms/progress-bar/
    └── progress-item (Molécule)

SECTION (Historique récent)
└── section-history (Molécule) → components-lib/molecules/section/
    ├── title (Atom h2) → components-lib/atoms/title/
    └── list-history (Molécule) → components-lib/molecules/list-history/
        └── list-item (Molécule) → components-lib/molecules/list-item/
            ├── text (Atom) → components-lib/atoms/text/
            └── badge (Atom) → components-lib/atoms/badge/
            └── link-action (Atom) → components-lib/atoms/link/
```

## Composants Nécessaires

### Atoms
- [ ] `logo/`
- [ ] `title/`
- [ ] `text/` 
- [ ] `link/`
- [ ] `button/` (inclus variantes icones)
- [ ] `avatar/` - Image de profil
- [ ] `badge/` - Élément visuel pour les scores/statuts
- [ ] `progress-bar/` - Jauge de progression

### Molécules
- [ ] `navbar-apprenant/` 
- [ ] `nav-menu/`
- [ ] `user-dropdown/`
- [ ] `hero-dashboard/`
- [ ] `section/` - Wrapper générique (Titre + Contenu)
- [ ] `card-qcm/` - Carte d'un test à passer
- [ ] `progress-item/` - Label + Jauge
- [ ] `list-history/` - Wrapper de liste
- [ ] `list-item/` - Ligne d'historique

## Statut
- [ ] Toutes les molécules créées
- [ ] Mockup assemblé
