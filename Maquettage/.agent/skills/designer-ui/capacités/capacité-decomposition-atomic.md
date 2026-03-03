# Capacité : Décomposition Atomic Design

## Objectif
Analyser un wireframe et identifier les composants nécessaires selon la méthodologie Atomic Design.

## Principes Fondamentaux

### 1. Méthodologie Atomic Design
- **Atom** : Composant UI indivisible et réutilisable (bouton, input, titre, image)
- **Molécule** : Groupe d'atoms avec une fonction claire (card, navbar, search-bar)
- **Template/Page** : Page complète (mockup final)

### 2. Règles de Classification

**Comment identifier un ATOM ?**
- ✅ C'est un élément unique et réutilisable
- ✅ Il ne peut pas être décomposé davantage
- ✅ Il peut exister seul
- Exemples : Bouton, Input, Titre, Lien, Image, Label

**Comment identifier une MOLÉCULE ?**
- ✅ C'est un groupe d'atoms avec une fonction claire
- ✅ Elle peut être réutilisée dans plusieurs pages
- ✅ Elle a une responsabilité unique
- Exemples : Card (Titre + Texte + Bouton), Navbar (Logo + Menu), Search Bar (Input + Bouton)

**Quand un composant est-il trop complexe ?**
- ❌ Si une "molécule" contient plus de 5-6 atoms → Elle doit être divisée en plusieurs molécules
- ❌ Si elle fait plusieurs choses → Découper en plusieurs molécules

## Algorithme d'Analyse

### Étape 1 : Lecture du Wireframe
1. **Identifier les grandes sections** (Header, Main, Footer)
2. **Repérer les blocs répétés** (Card, Item de liste)
3. **Lister tous les éléments UI** (Boutons, Titres, Textes, Images)

### Étape 2 : Classification des Composants
Pour chaque bloc identifié :
1. **Est-ce un atom ?** → Élément simple et indivisible
2. **Est-ce une molécule ?** → Groupe d'atoms avec une fonction
3. **Est-ce déjà créé ?** → Vérifier le manifeste correspondant (`atoms/manifest.md` ou `molecules/manifest.md`)

### Étape 3 : Structuration Hiérarchique
Créer une arborescence qui montre :
- Les sections de la page
- Les molécules dans chaque section
- Les atoms dans chaque molécule

### Étape 4 : Génération du Fichier de Composition
Format du fichier `comp-[page].md` :
```markdown
# Composition - Page [Nom]

## Source
- **Wireframe** : `chemin/vers/wireframe.md`
- **Mockup Final** : `3.maquettage/mockups/[page].html`

## Structure de Composition
[Arborescence complète]

## Composants Nécessaires
### Atoms
- [ ] `atom-1` - Description
- [ ] `atom-2` - Description

### Molécules
- [ ] `molecule-1` - Description (Atoms utilisés: ...)

## Statut
- [ ] Tous les atoms créés
- [ ] Toutes les molécules créées
- [ ] Mockup assemblé
```

## Exemple Pratique

### Wireframe Analysé : Page Home

**Contenu du Wireframe** :
```
[Header]
  Logo + Navigation (Accueil, Services, Contact)

[Hero Section]
  Titre H1 : "Bienvenue sur Notre Site"
  Texte : "Découvrez nos services"
  Bouton : "Commencer"

[Features Section]
  3 Cards identiques :
    - Titre H3
    - Texte descriptif
    - Bouton "En savoir plus"

[Footer]
  Texte copyright
```

---

### Décomposition Atomic Design

#### Atoms Identifiés
1. **logo** → Image SVG du logo
2. **nav-link** → Lien de navigation
3. **title** → Titres (variants: h1, h2, h3)
4. **text** → Paragraphes (variants: lead, normal, small)
5. **button** → Boutons (variants: primary, secondary)

#### Molécules Identifiées
1. **navbar** → Logo + Liste de nav-links
2. **hero** → Titre H1 + Texte lead + Bouton primary
3. **card** → Titre H3 + Texte + Bouton secondary
4. **footer** → Texte small

---

### Fichier de Composition Généré : `comp-home.md`

```markdown
# Composition - Page Home

## Source
- **Wireframe** : `2.organisation-contenu/wireframes/home.md`
- **Mockup Final** : `3.maquettage/mockups/home.html`

## Structure de Composition

```
Header
└── navbar (Molécule) → components-lib/molecules/navbar/
    ├── logo (Atom) → components-lib/atoms/logo/
    └── 3x nav-link (Atom) → components-lib/atoms/nav-link/

Main
├── hero (Molécule) → components-lib/molecules/hero/
│   ├── title (Atom h1) → components-lib/atoms/title/
│   ├── text (Atom lead) → components-lib/atoms/text/
│   └── button (Atom primary) → components-lib/atoms/button/
│
└── Features Section
    └── 3x card (Molécule) → components-lib/molecules/card/
        ├── title (Atom h3) → components-lib/atoms/title/
        ├── text (Atom) → components-lib/atoms/text/
        └── button (Atom secondary) → components-lib/atoms/button/

Footer
└── footer (Molécule) → components-lib/molecules/footer/
    └── text (Atom small) → components-lib/atoms/text/
```

## Composants Nécessaires

### Atoms (dans components-lib/atoms/)
- [ ] `logo/` - Logo du site (SVG)
- [ ] `nav-link/` - Lien de navigation avec états (normal, active, hover)
- [ ] `title/` - Titres avec variants (h1, h2, h3)
- [ ] `text/` - Paragraphes avec variants (lead, normal, small)
- [ ] `button/` - Boutons avec variants (primary, secondary, large, small)

### Molécules (dans components-lib/molecules/)
- [ ] `navbar/` - Barre de navigation (logo + nav-links)
  - **Atoms utilisés** : logo, nav-link
  - **Réutilisée dans** : home, about, contact
- [ ] `hero/` - Section héro (titre + texte + CTA)
  - **Atoms utilisés** : title (h1), text (lead), button (primary)
  - **Réutilisée dans** : home
- [ ] `card/` - Carte de contenu
  - **Atoms utilisés** : title (h3), text, button (secondary)
  - **Réutilisée dans** : home, about
- [ ] `footer/` - Pied de page simple
  - **Atoms utilisés** : text (small)
  - **Réutilisée dans** : home, about, contact

## Vérification Manifest

### Atoms déjà existants
*(Vérifier dans components-lib/atoms/manifest.md)*
- [x] button - Déjà créé
- [ ] logo - À créer
- ... etc

### Molécules déjà existantes
*(Vérifier dans components-lib/molecules/manifest.md)*
- [ ] navbar - À créer
- [ ] hero - À créer
- ... etc

## Statut de Création
- [ ] Charte Graphique validée
- [ ] Tous les atoms créés dans components-lib/atoms/
- [ ] Toutes les molécules créées dans components-lib/molecules/
- [ ] Mockup assemblé dans mockups/home.html

## Notes
- **Navbar et Footer** sont réutilisables sur toutes les pages → Créer en priorité
- **Card** est réutilisable sur home et about → Créer après navbar/footer
- **Hero** est spécifique à home → Créer si besoin uniquement
```

## Règles d'Application

### ❌ Interdictions
1. Ne jamais inventer de composants non présents dans le wireframe
2. Ne jamais créer de molécule trop complexe (> 5-6 atoms)
3. Ne jamais oublier de vérifier le manifest avant de lister les composants

### ✅ Bonnes Pratiques
1. **Prioriser la réutilisabilité** : Si un composant apparaît dans plusieurs pages, le créer en priorité
2. **Documenter l'utilisation** : Indiquer dans quelles pages chaque composant sera utilisé
3. **Respecter la hiérarchie** : Atoms d'abord, molécules ensuite, mockup à la fin
4. **Maintenir les manifestes** : Toujours vérifier les composants existants pour éviter les doublons

## Checklist de Validation

**Avant de générer le fichier de composition** :
- [ ] Le wireframe a été lu en entier
- [ ] Tous les blocs du wireframe sont identifiés
- [ ] Les composants sont classés correctement (Atom vs Molécule)
- [ ] Les manifestes ont été consultés pour détecter les doublons
- [ ] L'arborescence est claire et complète
- [ ] Les chemins vers components-lib/ sont corrects
