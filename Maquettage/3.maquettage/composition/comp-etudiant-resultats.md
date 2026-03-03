# Composition - Page Mes Résultats & Corrections

## Source
- **Wireframe** : `2.organisation-contenu/wireframes/etudiant-resultats.md`
- **Mockup Final** : `3.maquettage/mockups/etudiant-resultats.html`

## Structure de Composition

```
Header
└── sub-header (Molécule) → components-lib/molecules/sub-header/
    └── nav-link (Atom back) → components-lib/atoms/nav-link/

Hero
└── result-summary (Molécule) → components-lib/molecules/result-summary/
    ├── title (Atom h1) → components-lib/atoms/title/
    ├── text (Atom huge) → components-lib/atoms/text/
    └── text (Atom lead success) → components-lib/atoms/text/

Main
├── Score Global
│   ├── title (Atom h2) → components-lib/atoms/title/
│   └── data-table (Molécule) → components-lib/molecules/data-table/
│       └── text (Atom normal) → components-lib/atoms/text/
│
└── Corrections Détaillées
    ├── title (Atom h2) → components-lib/atoms/title/
    └── 3x feedback-item (Molécule) → components-lib/molecules/feedback-item/
        ├── title (Atom h3) → components-lib/atoms/title/
        ├── badge (Atom status) → components-lib/atoms/badge/
        ├── text (Atom normal) → components-lib/atoms/text/
        └── blockquote (Atom) → components-lib/atoms/blockquote/

Footer
└── action-footer (Molécule) → components-lib/molecules/action-footer/
    └── button (Atom primary) → components-lib/atoms/button/
```

## Composants Nécessaires

### Atoms
- [ ] `nav-link/` - Lien de retour
- [ ] `title/` - Titres (h1, h2, h3)
- [ ] `text/` - Textes divers
- [ ] `badge/` - Badges justes/faux
- [ ] `blockquote/` - Bloc d'explication du formateur
- [ ] `button/` - Bouton retour tableau de bord

### Molécules
- [ ] `sub-header/` - En-tête secondaire (bouton retour)
- [ ] `result-summary/` - Résumé avec le score final
- [ ] `data-table/` - Tableau de score par objectif
- [ ] `feedback-item/` - Accordéon ou bloc d'explication pour chaque question
- [ ] `action-footer/` - Pied de page contenant le CTA final

## Statut
- [ ] Charte Graphique validée
- [ ] Tous les atoms créés
- [ ] Toutes les molécules créées
- [ ] Mockup assemblé
