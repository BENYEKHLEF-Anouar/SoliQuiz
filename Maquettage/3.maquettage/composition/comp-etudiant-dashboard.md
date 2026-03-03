# Composition - Page Tableau de bord Étudiant

## Source
- **Wireframe** : `2.organisation-contenu/wireframes/etudiant-dashboard.md`
- **Mockup Final** : `3.maquettage/mockups/etudiant-dashboard.html`

## Structure de Composition

```
Header
└── navbar (Molécule) → components-lib/molecules/navbar/
    ├── logo (Atom) → components-lib/atoms/logo/
    └── 3x nav-link (Atom) → components-lib/atoms/nav-link/

Hero
└── hero-banner (Molécule) → components-lib/molecules/hero-banner/
    ├── title (Atom h1) → components-lib/atoms/title/
    └── text (Atom lead) → components-lib/atoms/text/

Main
├── QCM en attente Section
│   ├── title (Atom h2) → components-lib/atoms/title/
│   └── 2x card-qcm (Molécule) → components-lib/molecules/card-qcm/
│       ├── title (Atom h3) → components-lib/atoms/title/
│       ├── badge (Atom) → components-lib/atoms/badge/
│       ├── text (Atom) → components-lib/atoms/text/
│       └── button (Atom primary) → components-lib/atoms/button/
│
└── Dernière progression Section
    ├── title (Atom h2) → components-lib/atoms/title/
    ├── progress-bars (Molécule) → components-lib/molecules/progress-bars/
    │   ├── title (Atom h3) → components-lib/atoms/title/
    │   └── progress-bar (Atom) → components-lib/atoms/progress-bar/
    └── history-list (Molécule) → components-lib/molecules/history-list/
        ├── text (Atom normal) → components-lib/atoms/text/
        └── button (Atom secondary small) → components-lib/atoms/button/

Footer
└── footer-standard (Molécule) → components-lib/molecules/footer-standard/
    └── 2x nav-link (Atom) → components-lib/atoms/nav-link/
```

## Composants Nécessaires

### Atoms
- [ ] `logo/` - Logo
- [ ] `nav-link/` - Liens
- [ ] `title/` - Titres (h1, h2, h3)
- [ ] `text/` - Textes (normal, lead)
- [ ] `button/` - Boutons (primary, secondary)
- [ ] `badge/` - Étiquettes de module
- [ ] `progress-bar/` - Jauge de progression horizontale

### Molécules
- [ ] `navbar/` - En-tête de navigation
- [ ] `hero-banner/` - Bannière d'introduction textuelle
- [ ] `card-qcm/` - Carte d'un QCM à passer
- [ ] `progress-bars/` - Graphique des compétences
- [ ] `history-list/` - Liste des résultats
- [ ] `footer-standard/` - Pied de page global

## Statut
- [ ] Charte Graphique validée
- [ ] Tous les atoms créés
- [ ] Toutes les molécules créées
- [ ] Mockup assemblé
