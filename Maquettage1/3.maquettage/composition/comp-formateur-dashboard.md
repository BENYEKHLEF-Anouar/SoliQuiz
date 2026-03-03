# Composition - Page Tableau de bord Formateur

## Source
- **Wireframe** : `2.organisation-contenu/wireframes/formateur-dashboard.md`
- **Mockup Final** : `3.maquettage/mockups/formateur-dashboard.html`

## Structure de Composition

```
Header
└── navbar (Molécule) → components-lib/molecules/navbar/
    ├── logo (Atom) → components-lib/atoms/logo/
    └── 3x nav-link (Atom) → components-lib/atoms/nav-link/

Hero
└── hero-stats (Molécule) → components-lib/molecules/hero-stats/
    ├── title (Atom h1) → components-lib/atoms/title/
    └── 2x kpi-card (Molécule) → components-lib/molecules/kpi-card/
        ├── title (Atom h3) → components-lib/atoms/title/
        └── text (Atom lead) → components-lib/atoms/text/

Main
├── Vos sessions du jour
│   ├── title (Atom h2) → components-lib/atoms/title/
│   ├── session-card (Molécule) → components-lib/molecules/session-card/
│   │   ├── title (Atom h3) → components-lib/atoms/title/
│   │   ├── badge (Atom status) → components-lib/atoms/badge/
│   │   └── text (Atom) → components-lib/atoms/text/
│   └── alert-banner (Molécule) → components-lib/molecules/alert-banner/
│       ├── icon (Atom warning) → components-lib/atoms/icon/
│       └── text (Atom strong) → components-lib/atoms/text/
│
└── Accès rapide
    └── button-group (Molécule) → components-lib/molecules/button-group/
        ├── button (Atom secondary) → components-lib/atoms/button/
        └── button (Atom primary) → components-lib/atoms/button/

Footer
└── footer-standard (Molécule) → components-lib/molecules/footer-standard/
```

## Composants Nécessaires

### Atoms
- [ ] `logo/` - Logo
- [ ] `title/` - Titres
- [ ] `text/` - Textes
- [ ] `badge/` - Étiquettes de statut
- [ ] `icon/` - Icône SVG simple
- [ ] `button/` - Boutons d'action
- [ ] `nav-link/` - Liens

### Molécules
- [ ] `navbar/` - Navigation formateur
- [ ] `hero-stats/` - En-tête avec les indicateurs
- [ ] `kpi-card/` - Petite carte affichant un chiffre clé
- [ ] `session-card/` - Aperçu résumé d'une session
- [ ] `alert-banner/` - Bannière d'avertissement rouge/orange
- [ ] `button-group/` - Groupe de boutons d'action rapide

## Statut
- [ ] Charte Graphique validée
- [ ] Tous les atoms créés
- [ ] Toutes les molécules créées
- [ ] Mockup assemblé
