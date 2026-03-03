# Charte Graphique : SoliQuiz

## 1. Identité Visuelle
- **Nom du projet** : SoliQuiz
- **Ton** : Professionnel, clair, pédagogique et rassurant.
- **Style Visuel** : Interface claire, moderne, étudiée pour réduire la fatigue cognitive lors des évaluations (mobile-first).

## 2. Typographie
- **Police Principale (Body & UI)** : `Inter` (sans-serif) - Excellente lisibilité sur mobile.
- **Police Secondaire (Titres / Accents)** : `Outfit` (sans-serif) - Apporte une touche moderne et dynamique.

## 3. Palette de Couleurs (Couleurs Tailwind)

### Primary (Indigo Pédagogique - Rassurant et Focus)
- `primary-50` : `#eef2ff`
- `primary-100`: `#e0e7ff`
- `primary-500`: `#6366f1` (Base de la marque, boutons principaux)
- `primary-600`: `#4f46e5` (Hover)
- `primary-900`: `#312e81`

### Secondary (Teal - Doux et Rafraîchissant)
- `secondary-50`: `#f0fdfa`
- `secondary-500`: `#14b8a6` (Accents, illustrations)
- `secondary-600`: `#0d9488`

### Neutrals (Slate - Typographie et contours)
- `slate-50` : `#f8fafc` (Background général)
- `slate-100`: `#f1f5f9` (Background cartes/composants)
- `slate-200`: `#e2e8f0` (Bordures)
- `slate-500`: `#64748b` (Textes secondaires, placeholders)
- `slate-800`: `#1e293b` (Textes principaux)
- `slate-900`: `#0f172a` (Titres)

### Sémantique (Feedback direct)
- **Success** : `#22c55e` (green-500) - Pour les bonnes réponses.
- **Error** : `#ef4444` (red-500) - Pour les mauvaises réponses.
- **Warning** : `#f59e0b` (amber-500) - Pour les alertes formateur.

## 4. Configuration Tailwind (Extrait tailwind.config.js - Référence)
```javascript
module.exports = {
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe', 300: '#a5b4fc', 400: '#818cf8',
          500: '#6366f1', 600: '#4f46e5', 700: '#4338ca', 800: '#3730a3', 900: '#312e81',
        },
        secondary: {
          50: '#f0fdfa', 500: '#14b8a6', 600: '#0d9488'
        }
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
        heading: ['Outfit', 'sans-serif'],
      }
    }
  }
}
```
