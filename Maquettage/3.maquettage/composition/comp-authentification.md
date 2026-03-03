# Composition - Page Authentification

## Source
- **Wireframe** : `2.organisation-contenu/wireframes/authentification.md`
- **Mockup Final** : `3.maquettage/mockups/authentification.html`

## Structure de Composition

```
Main (Center Layout)
└── auth-card (Molécule) → components-lib/molecules/auth-card/
    ├── logo (Atom) → components-lib/atoms/logo/
    ├── title (Atom h1) → components-lib/atoms/title/
    ├── title (Atom h2) → components-lib/atoms/title/
    ├── input (Atom email) → components-lib/atoms/input/
    ├── input (Atom password) → components-lib/atoms/input/
    ├── button (Atom primary) → components-lib/atoms/button/
    └── nav-link (Atom) → components-lib/atoms/nav-link/

Footer
└── footer-simple (Molécule) → components-lib/molecules/footer-simple/
    └── text (Atom small) → components-lib/atoms/text/
```

## Composants Nécessaires

### Atoms
- [ ] `logo/` - Logo SoliQuiz (Image/SVG)
- [ ] `title/` - Titres avec variants (h1, h2)
- [ ] `input/` - Champs de saisie (variants: email, password, text)
- [ ] `button/` - Boutons d'action (variant: primary)
- [ ] `nav-link/` - Liens textuels
- [ ] `text/` - Paragraphes (variant: small)

### Molécules
- [ ] `auth-card/` - Carte du formulaire de connexion
  - **Atoms utilisés** : logo, title(h1, h2), input(email, password), button, nav-link
  - **Réutilisée dans** : authentification
- [ ] `footer-simple/` - Pied de page basique
  - **Atoms utilisés** : text(small)
  - **Réutilisée dans** : authentification, etudiant-evaluation

## Statut
- [ ] Charte Graphique validée
- [ ] Tous les atoms créés
- [ ] Toutes les molécules créées
- [ ] Mockup assemblé
