# Capacité : Composition UI (Molécules)

## Objectif
Assembler des Atoms pour créer des composants d'interface plus complexes, réutilisables et isolés en utilisant exclusivement Tailwind CSS.

## Principes d'Isolation & Structure

### 0. UI et UX 

les Molécules doit être plus UI et UX

### 1. Fichier Unique & Autonome
- Chaque molécule doit résider dans son propre fichier `index.html` (ex: `molecules/card/index.html`).
- **CDN Tailwind** : Chaque fichier doit être visualisable seul grâce au script `<script src="https://cdn.tailwindcss.com"></script>` dans le `<head>`.
- **Boilerplate HTML** : Chaque composant doit avoir une structure HTML complète (`<!DOCTYPE html>`, `<html>`, `<head>`, `<body>`).

### 2. Composition via Utility Classes
- **✅ Composer** : Importer le HTML des Atoms et gérer leur layout via les classes Flexbox et Grid de Tailwind.
- **❌ Dupliquer** : Éviter de recréer manuellement le style d'un Atom à l'intérieur d'une molécule.
- **Zéro CSS externe** : Aucun fichier `.css` ne doit être nécessaire pour visualiser une molécule.

## Règles d'Application

### ❌ Interdictions
1. **INTERDICTION** de créer des classes CSS personnalisées ou d'utiliser BEM.
2. **INTERDICTION** de créer des molécules sans wireframe de référence.
3. **INTERDICTION** de coder des molécules sans boilerplate HTML complet.

### ✅ Bonnes Pratiques
1. **Layout Tailwind** : Utiliser `flex`, `grid`, `gap`, `justify-*`, `items-*` pour l'assemblage.
2. **Atomic Design** : Une molécule groupe des Atoms pour une unité fonctionnelle (ex: Barre de recherche, Carte).
3. **Responsive** : Utiliser les préfixes de rupture `md:`, `lg:` directement sur le wrapper de la molécule.

## Exemple : Card (Molécule isolée avec Tailwind)

```html
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8 bg-neutral-50">
  <!-- Molécule : Card -->
  <article class="max-w-sm bg-white rounded-2xl shadow-lg border border-neutral-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
    <div class="h-48 bg-neutral-200">
        <!-- Atom : Placeholder Image -->
    </div>
    <div class="p-6">
      <!-- Atom : Title -->
      <h3 class="text-xl font-bold text-neutral-900 mb-2">Titre de la Card</h3>
      <!-- Atom : Text -->
      <p class="text-neutral-600 leading-relaxed mb-6">
        Description composée avec les classes utilitaires Tailwind pour un rendu moderne et responsive.
      </p>
      <!-- Atom : Button -->
      <div class="flex justify-end">
        <button type="button" class="px-6 py-2 bg-primary-500 text-white font-bold rounded-lg hover:bg-primary-600 transition-colors">
          Action
        </button>
      </div>
    </div>
  </article>
</body>
</html>
```

## Checklist de Validation
- [ ] Boilerplate HTML présent avec `<script src="https://cdn.tailwindcss.com"></script>`.
- [ ] Layout géré exclusivement par Tailwind (Flex/Grid).
- [ ] La molécule est testable de manière 100% isolée.
- [ ] Le `molecules-manifest.md` est mis à jour après création.
