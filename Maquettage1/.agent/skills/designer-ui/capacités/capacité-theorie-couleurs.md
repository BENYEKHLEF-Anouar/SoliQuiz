# Capacité : Théorie des Couleurs

## Objectif
Créer des palettes harmonieuses et accessibles pour l'identité visuelle d'un projet.

## Principes Fondamentaux

### 1. Harmonie Chromatique
- **Complémentaires** : Couleurs opposées sur la roue chromatique (Ex: Bleu/Orange)
- **Analogues** : Couleurs adjacentes (Ex: Bleu, Bleu-Vert, Vert)
- **Triadiques** : 3 couleurs équidistantes (Ex: Rouge, Jaune, Bleu)

### 2. Format HSL (Hue, Saturation, Lightness)
- **Avantages** : Manipulation intuitive, création de nuances cohérentes
- **Usage** : Préférer HSL à Hex pour la génération de palettes

### 3. Accessibilité (WCAG)
- **Contraste Minimum** :
  - Texte normal : 4.5:1
  - Texte large (18pt+) : 3:1
  - Éléments interactifs : 3:1
- **Outil de Vérification** : Utiliser les outils de contraste en ligne

## Règles d'Application

### ❌ Interdictions
1. Ne jamais utiliser de couleurs primaires pures (rouge #FF0000, bleu #0000FF)
2. Ne jamais avoir moins de 3 niveaux de gris (variants)
3. Ne jamais ignorer le contraste pour les textes

### ✅ Bonnes Pratiques
1. **Palette Structurée** :
   - 1 couleur primaire (identité)
   - 1-2 couleurs secondaires (accents)
   - 1 palette de gris (5-7 niveaux)
   - 1 palette sémantique (success, warning, error, info)

2. **Génération de Variants** :
   - Lightness de 10 à 95 par pas de 10
   - Saturation constante pour les neutrals (0-10%)
   - Saturation élevée pour les accents (60-90%)

3. **Nommage des Tokens** :
   - `--color-primary-500` (base)
   - `--color-primary-600` (darker)
   - `--color-primary-400` (lighter)

## Algorithme de Création

1. **Analyse du Besoin** : Lire le cahier des charges pour identifier le ton (professionnel, ludique, etc.)
2. **Choix Base** : Sélectionner une couleur primaire en HSL
3. **Génération Variants** : Créer 9 nuances (de 100 à 900)
4. **Palette Gris** : Créer 7 niveaux de gris neutres
5. **Couleurs Sémantiques** : Définir success (vert), warning (orange), error (rouge), info (bleu)
6. **Vérification Contraste** : Valider les contrastes texte/fond

## Exemple de Palette

```css
/* Primary (Bleu professionnel) */
--color-primary-100: hsl(210, 90%, 95%);
--color-primary-300: hsl(210, 85%, 75%);
--color-primary-500: hsl(210, 80%, 55%); /* Base */
--color-primary-700: hsl(210, 75%, 35%);
--color-primary-900: hsl(210, 70%, 15%);

/* Neutrals (Gris) */
--color-neutral-100: hsl(0, 0%, 95%);
--color-neutral-500: hsl(0, 0%, 50%);
--color-neutral-900: hsl(0, 0%, 10%);

/* Semantic */
--color-success: hsl(140, 70%, 45%);
--color-warning: hsl(35, 90%, 55%);
--color-error: hsl(0, 75%, 55%);
```
