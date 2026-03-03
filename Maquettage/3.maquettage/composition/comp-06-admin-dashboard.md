# Composition - Page Espace Administrateur (Supervision Globale)

## Source
- **Wireframe** : `2.organisation-contenu/wireframes/06-admin-dashboard.md`
- **Mockup Final** : `3.maquettage/mockups/06-admin-dashboard.html`

## Structure de Composition

```
HEADER (Navigation Admin)
└── navbar-admin (Molécule) → components-lib/molecules/navbar-admin/
    ├── logo-admin (Atom) → components-lib/atoms/logo/
    ├── nav-menu-admin (Molécule) → components-lib/molecules/nav-menu/
    └── user-avatar-admin (Molécule) → components-lib/molecules/user-dropdown/

HERO (Aperçu Santé du Centre)
└── hero-admin (Molécule) → components-lib/molecules/hero-admin/
    ├── title (Atom h1) → components-lib/atoms/title/
    └── kpi-cards-grid (Molécule) → components-lib/molecules/kpi-cards-grid/
        ├── kpi-card-simple (Molécule) → components-lib/molecules/kpi-card/
        └── kpi-card-status (Molécule status) → components-lib/molecules/kpi-card/
            ├── text-value (Atom) → components-lib/atoms/text/
            └── status-badge (Atom badge) → components-lib/atoms/badge/

SECTION (Monitoring API & Systèmes)
└── section-monitoring (Molécule) → components-lib/molecules/section/
    ├── title (Atom h2) → components-lib/atoms/title/
    └── data-table (Molécule) → components-lib/molecules/data-table/
        ├── table-header (Molécule) → components-lib/molecules/table-row/
        └── table-row (Molécule) → components-lib/molecules/table-row/
            ├── cell-text (Atom) → components-lib/atoms/text/
            └── cell-badge (Atom) → components-lib/atoms/badge/
    └── button-sync (Atom button warning) → components-lib/atoms/button/

SECTION (Gestion des cohortes et succès)
└── section-charts (Molécule) → components-lib/molecules/section/
    ├── title (Atom h2) → components-lib/atoms/title/
    └── chart-placeholder (Molécule) → components-lib/molecules/chart-placeholder/
        ├── chart-image (Atom img) → components-lib/atoms/image/
        └── filter-select (Atom select) → components-lib/atoms/select/

SECTION (Outils Rapides)
└── section-tools (Molécule) → components-lib/molecules/section/
    ├── title (Atom h2) → components-lib/atoms/title/
    └── search-form (Molécule) → components-lib/molecules/search-form/
        ├── search-input (Atom input) → components-lib/atoms/input/
        └── search-button (Atom button) → components-lib/atoms/button/
```

## Composants Nécessaires

### Atoms
- [ ] `logo/` (Vu)
- [ ] `title/` (Vu)
- [ ] `text/` (Vu)
- [ ] `badge/` (Vu)
- [ ] `button/` (Vu, variantes)
- [ ] `img/` - Image générique/placeholder
- [ ] `select/` (Vu)
- [ ] `input/` (Vu)

### Molécules
- [ ] `navbar-admin/` (Variante type admin)
- [ ] `nav-menu/` (Vu)
- [ ] `user-dropdown/` (Vu)
- [ ] `hero-admin/`
- [ ] `kpi-cards-grid/`
- [ ] `kpi-card/` (Variantes simple / avec badge)
- [ ] `section/` (Vu)
- [ ] `data-table/`
- [ ] `table-row/`
- [ ] `chart-placeholder/`
- [ ] `search-form/`

## Statut
- [ ] Toutes les molécules créées
- [ ] Mockup assemblé
