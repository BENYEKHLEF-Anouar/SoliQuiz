# Composition - Landing Page Publique

## Source
- **Mockup Final** : `3.maquettage/mockups/12-public-landing.html`

## Structure de Composition

```
NAVBAR (Molécule) → molecules/navbar-public/
└── logo (Atom)
└── nav-links (Atoms)
└── button-login (Atom)

MAIN
├── HERO (Molécule) → molecules/hero-public/
│   └── floating-card (Molecule/Atom)
├── FEATURES (Section)
│   └── feature-card x3 (Molécule) → molecules/feature-card/
├── ROLES (Section)
│   └── role-item x2 (Molecule partial)
│   └── cta-section-dark (Molécule) → molecules/cta-section-dark/

FOOTER (Molécule) → molecules/footer-landing/
└── logo-muted (Atom)
└── copyright (Atom)
```

## Composants Extraits

### Molécules
- [x] `navbar-public/` - Barre de navigation avec logo et liens.
- [x] `hero-public/` - Section d'introduction avec image et texte.
- [x] `feature-card/` - Carte d'argumentaire de vente.
- [x] `cta-section-dark/` - Bloc d'appel à l'action final.
- [x] `footer-landing/` - Pied de page complet.

## Statut
- [x] Toutes les molécules extraites
- [x] Composition documentée
