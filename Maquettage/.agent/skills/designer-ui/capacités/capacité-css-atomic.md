# Capacité : CSS Utilitaires (Tailwind)

## Objectif
Styler les composants de manière ultra-rapide et cohérente en utilisant exclusivement les classes utilitaires de Tailwind CSS.

## Principes Fondamentaux

### 0. UI et UX 

les atome doit être plus UI et UX

### 1. Utility-First
- Appliquer les styles directement dans le HTML via les classes Tailwind.
- Utiliser la composition de classes (ex: `flex items-center justify-between`).

### 2. Design Tokens
- Utiliser les classes de couleurs configurées (ex: `text-primary-500`, `bg-neutral-900`).
- Respecter les échelles de spacing (`p-4`, `m-2`) et de typographie (`text-xl`, `font-bold`).

### 3. Responsive & States
- **Responsive** : Utiliser les préfixes `sm:`, `md:`, `lg:`, `xl:`.
- **Interactivité** : Utiliser `hover:`, `focus:`, `active:`, `group-hover:`.

## Règles d'Application

### ❌ Interdictions
1. **INTERDICTION** d'écrire des fichiers `.css` personnalisés (hors `style.css` global).
2. **INTERDICTION** d'utiliser la méthodologie BEM ou des classes CSS arbitraires.
3. **INTERDICTION** d'utiliser `!important` ou des styles inline `style=""`.

### ✅ Bonnes Pratiques
1. **Composition** : Préférer l'ajout de classes utilitaires à la création de classes spécifiques.
2. **Transitions** : Toujours ajouter `transition-all duration-300` pour les effets au survol.

## Exemple : Bouton Principal
```html
<a href="#" class="inline-block px-8 py-3 bg-primary-500 text-white font-bold rounded-xl shadow-lg hover:bg-primary-900 transition-all duration-300 transform hover:-translate-y-1">
    Bouton
</a>
```
