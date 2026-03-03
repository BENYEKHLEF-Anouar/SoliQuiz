# Capacité : HTML Sémantique

## Objectif
Produire du HTML propre, accessible et sémantique pour les composants UI.

## Principes Fondamentaux

### 1. Sémantique HTML5
Utiliser les balises appropriées selon le contenu :
- `<header>` : En-tête de section
- `<nav>` : Navigation principale
- `<main>` : Contenu principal
- `<article>` : Contenu autonome
- `<section>` : Section thématique
- `<aside>` : Contenu tangentiel
- `<footer>` : Pied de section
- `<button>` : Action interactive
- `<a>` : Lien de navigation

### 2. Accessibilité (ARIA)
- **Attributs ARIA** : Utiliser quand le HTML natif est insuffisant
- **Landmarks** : `role="banner"`, `role="navigation"`, etc.
- **Labels** : `aria-label`, `aria-labelledby`
- **States** : `aria-expanded`, `aria-selected`

### 3. Structure Atomique
Pour les composants Atomic Design :
- **Atom** : 1 seule balise racine + variants via classes
- **Molécule** : Plusieurs atoms assemblés
- **Mockup** : Structure de page complète

## Règles d'Application

### ❌ Interdictions
1. Ne jamais utiliser `<div>` ou `<span>` si une balise sémantique existe
2. Ne jamais omettre les attributs `alt` pour les images
3. Ne jamais créer de bouton avec `<div>` (utiliser `<button>`)
4. Ne jamais oublier `type="button"` pour les boutons non-submit

### ✅ Bonnes Pratiques
1. **Hiérarchie de Titres** :
   - Respecter l'ordre `h1` → `h6`
   - Un seul `h1` par page
   - Ne pas sauter de niveau

2. **Formulaires** :
   - Toujours associer `<label>` avec `<input>` via `for`/`id`
   - Grouper avec `<fieldset>` et `<legend>`
   - Utiliser `type` approprié (email, tel, number, etc.)

3. **Liens et Boutons** :
   - `<a>` pour navigation (change d'URL)
   - `<button>` pour actions (modifie l'état)

4. **Images** :
   - `alt` descriptif pour images informatives
   - `alt=""` pour images décoratives

## Algorithme de Création

1. **Identifier le Rôle** : Quelle est la fonction du composant ?
2. **Choisir la Balise Racine** : Utiliser la balise sémantique appropriée
3. **Structure Interne** : Organiser le contenu avec des balises sémantiques
4. **Accessibilité** : Ajouter ARIA si nécessaire
5. **Classes CSS** : Appliquer les classes selon BEM ou la convention du projet

## Exemples

### Atom : Bouton
```html
<button type="button" class="px-6 py-2 bg-primary-500 text-white font-semibold rounded-lg hover:bg-primary-600 transition-colors">
  Valider
</button>
```

### Atom : Input
```html
<div class="flex flex-col gap-1.5">
  <label for="email" class="text-sm font-medium text-neutral-700">Email</label>
  <input 
    type="email" 
    id="email" 
    name="email" 
    class="px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all"
    placeholder="exemple@email.com"
    required
  />
</div>
```

### Molécule : Card
```html
<article class="p-6 bg-white rounded-2xl shadow-lg border border-neutral-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
  <header class="mb-4">
    <h3 class="text-xl font-bold text-neutral-900">Titre de la Card</h3>
  </header>
  <div class="mb-6 text-neutral-600 leading-relaxed">
    <p>Description stylisée avec les classes utilitaires Tailwind pour un rendu moderne et responsive.</p>
  </div>
  <footer class="flex justify-end gap-2">
    <button type="button" class="px-4 py-2 text-primary-500 font-bold hover:bg-primary-50 rounded-lg transition-colors">Détails</button>
    <button type="button" class="px-4 py-2 bg-primary-500 text-white font-bold rounded-lg hover:bg-primary-600 transition-colors">Action</button>
  </footer>
</article>
```

### Mockup : Page Structure (Tailwind)
```html
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Titre de la Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-neutral-50 text-neutral-900 font-sans">
  <header role="banner" class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-neutral-100">
    <nav role="navigation" class="container mx-auto px-4 h-16 flex items-center justify-between">
      <!-- Logo & Navigation -->
    </nav>
  </header>
  
  <main role="main" class="container mx-auto px-4 py-12">
    <section class="max-w-4xl mx-auto">
      <!-- Contenu principal -->
    </section>
  </main>
  
  <footer role="contentinfo" class="bg-neutral-900 text-white py-12">
    <div class="container mx-auto px-4 text-center text-neutral-400 text-sm">
      <!-- Pied de page -->
    </div>
  </footer>
</body>
</html>
```
