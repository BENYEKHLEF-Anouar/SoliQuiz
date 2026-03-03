# Composition - Page Administration Globale & Synchronisation

## Source
- **Wireframe** : `2.organisation-contenu/wireframes/admin-global.md`
- **Mockup Final** : `3.maquettage/mockups/admin-global.html`

## Structure de Composition

```
Header
└── navbar (Molécule variant:admin) → components-lib/molecules/navbar/
    ├── logo (Atom) → components-lib/atoms/logo/
    └── 3x nav-link (Atom) → components-lib/atoms/nav-link/

Hero
└── hero-kpi (Molécule) → components-lib/molecules/hero-kpi/
    ├── title (Atom h1) → components-lib/atoms/title/
    └── 3x kpi-card (Molécule) → components-lib/molecules/kpi-card/

Main
├── Gestion des rôles
│   ├── title (Atom h2) → components-lib/atoms/title/
│   └── data-table-actions (Molécule) → components-lib/molecules/data-table-actions/
│       ├── text (Atom) → components-lib/atoms/text/
│       ├── button (Atom action-small) → components-lib/atoms/button/
│       └── button (Atom danger-small) → components-lib/atoms/button/
│
└── Synchronisation SoliLMS
    ├── title (Atom h2) → components-lib/atoms/title/
    ├── sync-status (Molécule) → components-lib/molecules/sync-status/
    │   ├── badge (Atom success) → components-lib/atoms/badge/
    │   └── text (Atom) → components-lib/atoms/text/
    ├── button (Atom primary large) → components-lib/atoms/button/
    └── log-console (Molécule) → components-lib/molecules/log-console/
        └── text (Atom mono) → components-lib/atoms/text/

Footer
└── footer-admin (Molécule) → components-lib/molecules/footer-admin/
    └── text (Atom small) → components-lib/atoms/text/
```

## Composants Nécessaires

### Atoms
- [ ] `logo/`
- [ ] `title/`
- [ ] `text/` (variants: normal, small, mono pour logs)
- [ ] `nav-link/`
- [ ] `button/` (variants: primary-large, action-small, danger-small)
- [ ] `badge/` (variant: success/error API)

### Molécules
- [ ] `navbar/` - Navigation Admin
- [ ] `hero-kpi/` - Récapitulatif global
- [ ] `kpi-card/`
- [ ] `data-table-actions/` - Tableau des utilisateurs avec actions rapides
- [ ] `sync-status/` - Bloc indiquant on/off pour API SoliLMS
- [ ] `log-console/` - Faux terminal / liste déroulante des actions serveurs
- [ ] `footer-admin/` - Variante de pied de page avec version

## Statut
- [ ] Charte Graphique validée
- [ ] Tous les atoms créés
- [ ] Toutes les molécules créées
- [ ] Mockup assemblé
