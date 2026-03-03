# Composition - Page Authentification / Connexion

## Source
- **Wireframe** : `2.organisation-contenu/wireframes/01-auth-login.md`
- **Mockup Final** : `3.maquettage/mockups/01-auth-login.html`

## Structure de Composition

```
HEADER (Global)
└── logo (Atom) → components-lib/atoms/logo/

CENTER CONTAINER (Authentification)
└── login-form (Molécule) → components-lib/molecules/login-form/
    ├── title (Atom h1) → components-lib/atoms/title/
    ├── title (Atom h2) → components-lib/atoms/title/
    ├── input-email (Atom input) → components-lib/atoms/input/
    ├── input-password (Atom input) → components-lib/atoms/input/
    └── button (Atom primary) → components-lib/atoms/button/

FOOTER (Aide)
└── footer-simple (Molécule) → components-lib/molecules/footer-simple/
    └── link-support (Atom link) → components-lib/atoms/link/
```

## Composants Nécessaires

### Atoms
- [ ] `logo/` - Logo Solicode/OFPPT
- [ ] `title/` - Titres (variants h1, h2)
- [ ] `input/` - Champs de saisie form (text/email/password)
- [ ] `button/` - Bouton d'action principal
- [ ] `link/` - Lien texte simple

### Molécules
- [ ] `login-form/` - Formulaire complet de connexion avec titres et champs
- [ ] `footer-simple/` - Petit pied de page avec lien de support

## Statut
- [ ] Toutes les molécules créées
- [ ] Mockup assemblé
