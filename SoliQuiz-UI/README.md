# SoliQuiz UI - Design System

Design system complet basé sur les vues Laravel de l'application SoliQuiz.

**Couleur primaire**: `#17B0CF` (Digital Ocean Blue)

## Structure

```
SoliQuiz-UI/
├── charte-graphique/         # Design charter
│   ├── charte.md            # Documentation des couleurs (#17B0CF), typos
│   └── index.html           # Visualisation interactive
├── components-lib/          # Bibliothèque de composants
│   ├── atoms/ (17)          # Éléments de base
│   │   ├── logo/            # Logo SoliQuiz
│   │   ├── text/            # Styles typographiques
│   │   ├── link/            # Liens navigation
│   │   ├── button/          # Boutons
│   │   ├── input/           # Champs de saisie
│   │   ├── textarea/        # Zones de texte
│   │   ├── select/          # Menus déroulants
│   │   ├── radio-check/     # Radio et checkboxes
│   │   ├── label/           # Étiquettes
│   │   ├── badge/           # Badges de statut
│   │   ├── progress-bar/    # Barres de progression
│   │   ├── avatar/          # Avatars utilisateur
│   │   ├── icon/            # Icônes SVG
│   │   ├── stat/            # Affichage statistiques
│   │   ├── toggle/          # Interrupteurs
│   │   ├── card/            # Cartes conteneurs
│   │   └── image/           # Images et placeholders
│   └── molecules/ (10)      # Composants complexes
│       ├── navbar/          # Barres navigation
│       ├── sidebar/         # Navigation latérale
│       ├── breadcrumb/      # Fil d'Ariane
│       ├── kpi-card/        # Cartes de métriques
│       ├── search-form/     # Formulaires recherche
│       ├── list-item/       # Éléments de liste
│       ├── card-qcm/        # Cartes QCM
│       ├── modal/           # Fenêtres modales
│       ├── alert-box/       # Alertes inline
│       └── pagination/      # Navigation paginée
├── mockups/ (7)            # Maquettes de pages complètes
│   ├── 01-auth-login.html              # Page de connexion
│   ├── 02-student-dashboard.html       # Dashboard apprenant
│   ├── 03-formateur-dashboard.html       # Dashboard formateur
│   ├── 04-bibliotheque-qcm.html        # Bibliothèque QCM
│   ├── 05-passation-qcm.html           # Passation interactive ✅
│   ├── 06-admin-dashboard.html         # Admin avec tabs ✅
│   └── 07-apprenant-resultats.html     # Résultats détaillés ✅
└── index.html              # Galerie interactive (viewer)

## Utilisation

Ouvrez `index.html` dans un navigateur pour accéder à la galerie interactive.

## Design Principles

### Couleurs
- **Primary**: Digital Ocean Blue (`#17B0CF`) - Apaisant, moderne
- **Neutral**: Slate palette - Interface claire et professionnelle
- **Semantic**: Success=emerald, Error=rose, Warning=amber, Info=primary (warning), Blue (info)

### Typographie
- **Police**: Plus Jakarta Sans (headings & body)
- **Style**: Beaucoup d'italique et uppercase pour les labels
- **Échelle**: 8px à 5xl selon l'importance

### Composants
- Bordures arrondies (rounded-2xl dominant)
- Ombres légères (shadow-sm, shadow-lg)
- Transitions douces (150-200ms)
- Glassmorphism pour les dropdowns modals

## Différences avec Maquettage/3.maquettage

| Aspect | Maquettage/3.maquettage | SoliQuiz-UI |
|--------|------------------------|-------------|
| Source | Maquettes conceptuelles | Vues Laravel réelles |
| Police | Outfit + Inter | Plus Jakarta Sans |
| Statut | Prototype | Production-ready |
| Composants | 17 atoms + 49 molecules | 5 atoms + 2 molecules (extensible) |

## Extension

Pour ajouter de nouveaux composants:

1. Créer un dossier dans `components-lib/atoms/` ou `molecules/`
2. Ajouter un `index.html` avec le composant
3. Mettre à jour `index.html` (galerie) pour l'ajouter à la navigation
