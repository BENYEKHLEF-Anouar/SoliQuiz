# Composition - Page Création / Édition QCM (Formateur)

## Source
- **Wireframe** : `2.organisation-contenu/wireframes/05-formateur-creation-qcm.md`
- **Mockup Final** : `3.maquettage/mockups/05-formateur-creation-qcm.html`

## Structure de Composition

```
HEADER (Éditeur)
└── header-editor (Molécule) → components-lib/molecules/header-editor/
    ├── breadcrumbs (Molécule) → components-lib/molecules/breadcrumbs/
    │   └── link-crumb (Atom link) → components-lib/atoms/link/
    ├── editable-title (Molécule) → components-lib/molecules/editable-title/
    │   └── input-title (Atom h1-input) → components-lib/atoms/input/
    └── button-save (Atom outline) → components-lib/atoms/button/

PANNEAU LATÉRAL GAUCHE (Paramètres QCM)
└── sidebar-settings (Molécule) → components-lib/molecules/sidebar-settings/
    ├── title-section (Atom h2) → components-lib/atoms/title/
    ├── select-group (Molécule) → components-lib/molecules/select-group/
    │   ├── label (Atom label) → components-lib/atoms/label/
    │   └── select-input (Atom select) → components-lib/atoms/select/
    └── toggle-group (Molécule) → components-lib/molecules/toggle-group/
        ├── label (Atom label) → components-lib/atoms/label/
        └── switch (Atom toggle) → components-lib/atoms/toggle/

CORPS PRINCIPAL (Éditeur de Questions)
└── editor-main (Molécule) → components-lib/molecules/editor-main/
    └── question-card (Molécule complexe) → components-lib/molecules/question-card/
        ├── question-input (Atom textarea) → components-lib/atoms/textarea/
        ├── type-selector (Molécule) → components-lib/molecules/type-selector/
        ├── options-list (Molécule) → components-lib/molecules/options-list/
        │   └── option-item (Molécule) → components-lib/molecules/option-item/
        │       ├── input-text (Atom input) → components-lib/atoms/input/
        │       └── checkbox-correct (Atom checkbox) → components-lib/atoms/input-check/
        ├── feedback-input (Atom textarea) → components-lib/atoms/textarea/
        └── objective-select (Atom select-search) → components-lib/atoms/select/

FOOTER ÉDITEUR (Validation Globale)
└── footer-editor (Molécule) → components-lib/molecules/footer-editor/
    ├── button-add (Atom secondary) → components-lib/atoms/button/
    └── button-publish (Atom primary) → components-lib/atoms/button/
```

## Composants Nécessaires

### Atoms
- [ ] `link/` (Vu)
- [ ] `input/` (Vu, variantes title, text)
- [ ] `button/` (Vu)
- [ ] `title/` (Vu)
- [ ] `label/` (Vu)
- [ ] `select/` (Vu)
- [ ] `toggle/` - Interrupteur On/Off
- [ ] `textarea/` - Champ texte long multiligne
- [ ] `input-check/` (Vu, checkbox)

### Molécules
- [ ] `header-editor/`
- [ ] `breadcrumbs/` - Fil d'ariane
- [ ] `editable-title/`
- [ ] `sidebar-settings/`
- [ ] `select-group/`
- [ ] `toggle-group/`
- [ ] `editor-main/`
- [ ] `question-card/`
- [ ] `type-selector/`
- [ ] `options-list/`
- [ ] `option-item/`
- [ ] `footer-editor/`

## Statut
- [ ] Toutes les molécules créées
- [ ] Mockup assemblé
