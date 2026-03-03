# Charte Graphique - SoliQuiz

**Concept Visuel** : Apaisant, pédagogique, clair.
L'objectif est d'éliminer le stress de l'évaluation avec des teintes dominantes aquatiques (Ocean Teal) et un gris doux (Slate) pour minimiser la fatigue visuelle.

## 1. Couleurs (Preline / Tailwind Maps)

### Primaire : Ocean Teal
- Nom Tailwind : `primary`
- Base (500) : `hsl(190, 80%, 45%)` => Focus state et boutons principaux.
- Variantes : de 50 à 900.
*(Le Teal inspire la concentration, la fluidité et le calme)*

### Neutre : Slate (Pré-existant)
- Nom Tailwind : `slate` (mapped sur nos CSS vars `color-neutral-*` ou direct tailwind slate).
- Base (50) : Background des pages.
- Base (800) : Texte de contenu.

### Sémantique :
- **Success** : `hsl(140, 65%, 45%)` (Bonne réponse, synchronisation réussie)
- **Error** : `hsl(0, 75%, 55%)` (Erreur, suppression QCM)
- **Warning** : `hsl(35, 95%, 55%)` (Reste peu de temps, alerte niveau classe)
- **Info** : `hsl(220, 80%, 55%)` (Indication d'aide formateur)

## 2. Typographie
- **Titres (Headings)** : *Outfit* (Moderne, géométrique, lisible)
- **Corps de texte (Body)** : *Inter* (Ergonomie de lecture et clarté des interfaces web)

## 3. Formes & Composants (Preline UI UI)
- **Bordures** : Arrondies (`rounded-xl` et `rounded-2xl` prédominants) pour l'aspect amical et non rigide.
- **Ombres (Shadows)** : Légères et larges (`shadow-sm`, `shadow-md` avec une opacité très faible) pour donner de la profondeur sans alourdir.
- **Micro-interactions** : Transitions douces de `150ms` ou `200ms` sur les hovers des inputs et boutons.

## 4. Spécificités d'Accessibilité (WCAG)
- Contraste validé sur tous les boutons `bg-primary-500` avec texte `text-white`.
- Le texte secondaire (ex. Timer, Indications) utilise `text-slate-500` minimum sur `bg-slate-50`.
