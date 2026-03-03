# Composition - Page Suivi & Analyses Classe

## Source
- **Wireframe** : `2.organisation-contenu/wireframes/formateur-suivi.md`
- **Mockup Final** : `3.maquettage/mockups/formateur-suivi.html`

## Structure de Composition

```
Header
└── sub-header (Molécule) → components-lib/molecules/sub-header/
    └── nav-link (Atom back) → components-lib/atoms/nav-link/

Hero
└── hero-chart (Molécule) → components-lib/molecules/hero-chart/
    ├── title (Atom h1) → components-lib/atoms/title/
    └── chart-bar (Atom visuel) → components-lib/atoms/chart-bar/

Main
├── Statistiques de réussite par objectif
│   ├── title (Atom h2) → components-lib/atoms/title/
│   └── data-table (Molécule) → components-lib/molecules/data-table/
│       ├── text (Atom normal) → components-lib/atoms/text/
│       └── badge (Atom danger) → components-lib/atoms/badge/
│
└── Vue par étudiant
    ├── title (Atom h2) → components-lib/atoms/title/
    └── accordion-list (Molécule) → components-lib/molecules/accordion-list/
        ├── accordion-item (Molécule) → components-lib/molecules/accordion-item/
        │   ├── title (Atom h3) → components-lib/atoms/title/
        │   ├── text (Atom) → components-lib/atoms/text/
        │   └── icon-button (Atom expand) → components-lib/atoms/icon-button/
        └── details-panel (Molécule) → components-lib/molecules/details-panel/

Footer
└── action-footer (Molécule) → components-lib/molecules/action-footer/
    └── button (Atom primary) → components-lib/atoms/button/
```

## Composants Nécessaires

### Atoms
- [ ] `title/`
- [ ] `text/`
- [ ] `nav-link/`
- [ ] `badge/`
- [ ] `chart-bar/` - Placeholder graphique (image SVG ou barres HTML proxy)
- [ ] `icon-button/` - Chevrons d'expansion
- [ ] `button/` - Bouton exporter CSV

### Molécules
- [ ] `sub-header/` - Retour dashboard
- [ ] `hero-chart/` - Grand titre + Visualisation globale
- [ ] `data-table/` - Tableau analytique (moyennes par objectif)
- [ ] `accordion-list/` - Liste déroulante des étudiants
- [ ] `accordion-item/` - En-tête de l'étudiant à ouvrir
- [ ] `action-footer/` - Pied de page "Exporter"

## Statut
- [ ] Charte Graphique validée
- [ ] Tous les atoms créés
- [ ] Toutes les molécules créées
- [ ] Mockup assemblé
