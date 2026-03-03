# Composition - Page Bibliothèque de QCM (Formateur)

## Source
- **Wireframe** : `2.organisation-contenu/wireframes/formateur-bibliotheque.md`
- **Mockup Final** : `3.maquettage/mockups/formateur-bibliotheque.html`

## Structure de Composition

```
Header
└── navbar (Molécule) → components-lib/molecules/navbar/
    ├── logo (Atom) → components-lib/atoms/logo/
    └── 3x nav-link (Atom) → components-lib/atoms/nav-link/

Hero
└── hero-action (Molécule) → components-lib/molecules/hero-action/
    ├── title (Atom h1) → components-lib/atoms/title/
    └── button (Atom primary) → components-lib/atoms/button/

Main
├── Gestion des QCM listés
│   ├── title (Atom h2) → components-lib/atoms/title/
│   └── data-table-actions (Molécule) → components-lib/molecules/data-table-actions/
│       ├── text (Atom normal) → components-lib/atoms/text/
│       └── icon-button (Atom) → components-lib/atoms/icon-button/
│
└── Modal Création (Caché par défaut)
    └── modal-form (Molécule) → components-lib/molecules/modal-form/
        ├── title (Atom h2) → components-lib/atoms/title/
        ├── input (Atom text) → components-lib/atoms/input/
        ├── select (Atom) → components-lib/atoms/select/
        └── button (Atom primary) → components-lib/atoms/button/

Footer
└── footer-standard (Molécule) → components-lib/molecules/footer-standard/
    └── text (Atom small) → components-lib/atoms/text/
```

## Composants Nécessaires

### Atoms
- [ ] `logo/` - Logo
- [ ] `nav-link/` - Liens haut
- [ ] `title/` - Titres (h1, h2)
- [ ] `text/` - Textes 
- [ ] `button/` - Boutons d'action
- [ ] `icon-button/` - Boutons d'édition (éditer, corbeille)
- [ ] `input/` - Champs texte
- [ ] `select/` - Menus déroulants (Session/Objectif)

### Molécules
- [ ] `navbar/` - Navigation formateur
- [ ] `hero-action/` - Titre principal + Bouton "+"
- [ ] `data-table-actions/` - Tableau avec colonne d'actions (éditer, supprimer)
- [ ] `modal-form/` - Fenêtre modale de création d'un QCM
- [ ] `footer-standard/` - Base

## Statut
- [ ] Charte Graphique validée
- [ ] Tous les atoms créés
- [ ] Toutes les molécules créées
- [ ] Mockup assemblé
