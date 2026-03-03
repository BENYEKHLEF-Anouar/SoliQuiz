# Composition - Page Salle d'Évaluation (Test)

## Source
- **Wireframe** : `2.organisation-contenu/wireframes/etudiant-evaluation.md`
- **Mockup Final** : `3.maquettage/mockups/etudiant-evaluation.html`

## Structure de Composition

```
Header (Fixe top)
└── evaluation-header (Molécule) → components-lib/molecules/evaluation-header/
    ├── logo (Atom) → components-lib/atoms/logo/
    ├── title (Atom h1) → components-lib/atoms/title/
    ├── timer (Atom) → components-lib/atoms/timer/
    └── badge (Atom variant:status) → components-lib/atoms/badge/

Main
├── Progress Section
│   └── progress-stepper (Molécule) → components-lib/molecules/progress-stepper/
│       ├── text (Atom small) → components-lib/atoms/text/
│       └── progress-bar (Atom variant:thick) → components-lib/atoms/progress-bar/
│
├── Question Section
│   └── question-block (Molécule) → components-lib/molecules/question-block/
│       ├── text (Atom lead) → components-lib/atoms/text/
│       ├── title (Atom h2) → components-lib/atoms/title/
│       ├── radio (Atom) → components-lib/atoms/radio/
│       └── checkbox (Atom) → components-lib/atoms/checkbox/
│
└── Actions Section
    └── action-bar (Molécule) → components-lib/molecules/action-bar/
        ├── button (Atom secondary) → components-lib/atoms/button/
        ├── button (Atom primary) → components-lib/atoms/button/
        └── button (Atom danger) → components-lib/atoms/button/
```

## Composants Nécessaires

### Atoms
- [ ] `logo/` - Logo
- [ ] `title/` - Titres (h1, h2)
- [ ] `text/` - Textes (lead, small)
- [ ] `timer/` - Chronomètre textuel
- [ ] `badge/` - Étiquette statut
- [ ] `progress-bar/` - Jauge de pagination
- [ ] `radio/` - Bouton radio pour choix unique
- [ ] `checkbox/` - Case à cocher pour choix multiples
- [ ] `button/` - Boutons de navigation (primary, secondary, danger)

### Molécules
- [ ] `evaluation-header/` - En-tête d'évaluation épinglé
- [ ] `progress-stepper/` - Barre de progression des questions
- [ ] `question-block/` - Affichage des questions et options
- [ ] `action-bar/` - Barre des boutons Précédent/Suivant/Valider

## Statut
- [ ] Charte Graphique validée
- [ ] Tous les atoms créés
- [ ] Toutes les molécules créées
- [ ] Mockup assemblé
