# Capacité : Design System (Tailwind & Preline)

## Objectif
Définir une grammaire visuelle cohérente et compatible avec Tailwind CSS et Preline UI.

## 🎨 Configuration Tailwind (Design Tokens)

Pour chaque projet, définir les extensions du thème dans le fichier de référence `charte.md` :

### 1. Palette de Couleurs
- **Primary** : Nuancier (50-950) basé sur la couleur dominante.
- **Secondary** : Nuancier complémentaire.
- **Neutral** : Nuancier de gris (Généralement Slate ou Gray).
- **Semantics** : Success, Info, Warning, Error.

### 2. Typographie
- **Font-Heading** : Pour les titres (h1-h6).
- **Font-Body** : Pour le contenu textuel.
- **Règle** : Utiliser des polices modernes (Inter, Outfit, Poppins).

## 🛠️ Intégration Preline UI
Preline UI fournit des composants accessibles basés sur Tailwind. 

**Règles de compatibilité :**
- **Classes Utilitaires** : Prioriser les classes standards de Tailwind (`bg-primary-600`, `text-slate-800`).
- **Interactivité** : Utiliser les classes Preline pour les états (`hs-dropdown`, `hs-overlay`) si nécessaire.
- **Structure** : Suivre la sémantique HTML recommandée par Preline pour garantir l'accessibilité.

## 📝 Format du fichier `charte.md` (Référence IA)
Ce fichier doit lister :
- La liste des couleurs HEX et leurs équivalents Tailwind.
- Les choix typographiques.
- Les espacements personnalisés s'il y en a.

## 🚫 Interdictions
- **INTERDICTION** d'utiliser des couleurs HEX en dehors de la configuration du Design System.
- **INTERDICTION** de mélanger plusieurs frameworks CSS (ex: Bootstrap + Tailwind).
- **INTERDICTION** d'ignorer les contrastes d'accessibilité (WCAG).
