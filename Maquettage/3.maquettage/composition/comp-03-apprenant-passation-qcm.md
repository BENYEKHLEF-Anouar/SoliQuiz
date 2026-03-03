# Composition - Page Passation du QCM (Apprenant)

## Source
- **Wireframe** : `2.organisation-contenu/wireframes/03-apprenant-passation-qcm.md`
- **Mockup Final** : `3.maquettage/mockups/03-apprenant-passation-qcm.html`

## Structure de Composition

```
HEADER (Test Focus Mode)
└── header-timer (Molécule) → components-lib/molecules/header-timer/
    ├── title-qcm (Atom h1) → components-lib/atoms/title/
    ├── timer-badge (Atom badge) → components-lib/atoms/badge/
    └── progress-text (Atom text) → components-lib/atoms/text/

BODY (Question Active)
└── question-container (Molécule) → components-lib/molecules/question-container/
    ├── question-text (Atom text lead) → components-lib/atoms/text/
    └── choice-list (Molécule) → components-lib/molecules/choice-list/
        └── choice-item (Molécule) → components-lib/molecules/choice-item/
            ├── radio-checkbox (Atom input-check) → components-lib/atoms/input-check/
            └── choice-label (Atom label) → components-lib/atoms/label/

FOOTER COLLANT (Navigation)
└── footer-navigation (Molécule) → components-lib/molecules/footer-navigation/
    ├── status-text (Atom text small) → components-lib/atoms/text/
    ├── button-prev (Atom button outline) → components-lib/atoms/button/
    └── button-next (Atom button primary) → components-lib/atoms/button/
```

## Composants Nécessaires

### Atoms
- [ ] `title/`
- [ ] `text/`
- [ ] `badge/` (variant timer)
- [ ] `button/`
- [ ] `input-check/` - Radio ou Checkbox
- [ ] `label/` - Texte associé à un input

### Molécules
- [ ] `header-timer/`
- [ ] `question-container/`
- [ ] `choice-list/`
- [ ] `choice-item/`
- [ ] `footer-navigation/`

## Statut
- [ ] Toutes les molécules créées
- [ ] Mockup assemblé
