# Composition - Page Espace Formateur (Tableau de Bord)

## Source
- **Wireframe** : `2.organisation-contenu/wireframes/04-formateur-dashboard.md`
- **Mockup Final** : `3.maquettage/mockups/04-formateur-dashboard.html`

## Structure de Composition

```
HEADER (Navigation Formateur)
└── navbar-formateur (Molécule) → components-lib/molecules/navbar-formateur/
    ├── logo (Atom) → components-lib/atoms/logo/
    ├── nav-menu (Molécule) → components-lib/molecules/nav-menu/
    │   └── nav-link (Atom) → components-lib/atoms/link/
    ├── button-create (Atom primary) → components-lib/atoms/button/
    └── user-dropdown (Molécule) → components-lib/molecules/user-dropdown/
        ├── avatar (Atom) → components-lib/atoms/avatar/
        └── text (Atom) → components-lib/atoms/text/

HERO (Vue Globale)
└── hero-dashboard (Molécule) → components-lib/molecules/hero-dashboard/
    ├── title (Atom h1) → components-lib/atoms/title/
    ├── dropdown-select (Molécule) → components-lib/molecules/dropdown-select/
    │   └── select-input (Atom select) → components-lib/atoms/select/
    └── stats-banner (Molécule) → components-lib/molecules/stats-banner/
        └── stat-item (Atom stat) → components-lib/atoms/stat/

SECTION (Évaluations / Alertes)
└── section-alert (Molécule) → components-lib/molecules/section/
    ├── title (Atom h2) → components-lib/atoms/title/
    └── alert-box (Molécule) → components-lib/molecules/alert-box/
        ├── alert-icon (Atom icon) → components-lib/atoms/icon/
        ├── alert-text (Atom text) → components-lib/atoms/text/
        └── button-action (Atom button outline) → components-lib/atoms/button/

SECTION (Taux de réussite par objectif)
└── section-progress-cohort (Molécule) → components-lib/molecules/section/
    ├── title (Atom h2) → components-lib/atoms/title/
    └── progress-list (Molécule) → components-lib/molecules/progress-list/
        └── progress-item-cohort (Molécule) → components-lib/molecules/progress-item-cohort/
            ├── label-competence (Atom text) → components-lib/atoms/text/
            ├── progress-bar-cohort (Atom) → components-lib/atoms/progress-bar/
            └── percent-text (Atom text small) → components-lib/atoms/text/
```

## Composants Nécessaires

### Atoms
- [ ] `logo/` (Déjà vu Auth/Apprenant)
- [ ] `link/` (Déjà vu Auth/Apprenant)
- [ ] `button/` (Déjà vu Auth/Apprenant)
- [ ] `avatar/` (Déjà vu Apprenant)
- [ ] `text/` (Déjà vu Apprenant)
- [ ] `title/` (Déjà vu Apprenant)
- [ ] `select/` - Champ de sélection déroulant
- [ ] `stat/` - Affichage gros chiffre + libellé court
- [ ] `icon/` - Icône vectorielle
- [ ] `progress-bar/` (Déjà vu Apprenant)

### Molécules
- [ ] `navbar-formateur/` (Variante de navbar)
- [ ] `nav-menu/` (Déjà vu Apprenant)
- [ ] `user-dropdown/` (Déjà vu Apprenant)
- [ ] `hero-dashboard/` (Déjà vu Apprenant)
- [ ] `dropdown-select/`
- [ ] `stats-banner/`
- [ ] `section/` (Déjà vu Apprenant)
- [ ] `alert-box/` - Encadré d'alerte avec icône et action
- [ ] `progress-list/`
- [ ] `progress-item-cohort/` (Variante de progress-item)

## Statut
- [ ] Toutes les molécules créées
- [ ] Mockup assemblé
