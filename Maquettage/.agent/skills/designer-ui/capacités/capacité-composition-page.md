# Capacité : Composition Page (Mockups)

## Objectif
Assembler des molécules et atoms pour créer des pages complètes (mockups haute fidélité).

## Principes Fondamentaux

### 1. Atomic Design - Niveau Page (Template/Mockup)
Une page est l'assemblage final de tous les composants (atoms + molécules) selon un wireframe validé.

### 2. Wireframe = Plan Architectural
- Le wireframe définit la structure et l'emplacement des composants
- Le mockup traduit cette structure avec les composants réels
- **Pixel Perfect** : Le mockup doit respecter fidèlement le wireframe

### 3. Layout Système
Utiliser CSS Grid ou Flexbox pour la structure globale :
- **Header** : Navigation
- **Main** : Contenu principal
- **Aside** : Contenu tangentiel (sidebar)
- **Footer** : Pied de page

## Règles d'Application

### ❌ Interdictions
1. Ne jamais créer de mockup sans wireframe validé
2. Ne jamais coder de nouveaux atoms/molécules dans le mockup (tout doit exister avant)
3. Ne jamais utiliser de styles inline
4. Ne jamais ignorer le responsive (mobile first)

### ✅ Bonnes Pratiques
1. **Structure HTML5 Sémantique** :
   ```html
   <header role="banner">...</header>
   <main role="main">...</main>
   <aside role="complementary">...</aside>
   <footer role="contentinfo">...</footer>
   ```

2. **Grid Layout Global** :
   ```css
   .page {
     display: grid;
     grid-template-areas:
       "header"
       "main"
       "footer";
     min-height: 100vh;
   }
   ```

3. **Import de Composants** :
   - Réutiliser le HTML exact des molécules/atoms
   - Ne modifier que les classes de layout si besoin

4. **Responsive Design** :
   - Mobile First (base)
   - Tablet (768px+)
   - Desktop (1024px+)

## Algorithme de Création

1. **Lire le Wireframe** : Identifier tous les composants nécessaires
2. **Vérifier Disponibilité** : S'assurer que tous les composants existent
3. **Créer Structure HTML** :
   - Squelette sémantique (header, main, aside, footer)
   - Zones grid/flex pour le layout
4. **Assembler les Composants** :
   - Copier/coller le HTML des molécules
   - Intégrer dans les zones appropriées
5. **Créer CSS de Layout** :
   - Grid/Flex global
   - Spacing entre sections
   - Responsive breakpoints
6. **Validation Visuelle** : Comparer avec le wireframe

## Exemple : Page Home (Mockup)

### Wireframe Analysé
- Header : Logo + Navigation
- Hero : Titre H1 + Texte + CTA
- Features : 3 Cards
- Footer : Liens + Copyright

### Composants Nécessaires
- Molécules : `navbar`, `hero`, `card`
- Atoms : `logo`, `nav-link`, `button`

### Structure HTML
```html
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accueil - Mon Projet</title>
  <link rel="stylesheet" href="../../charte-graphique/style.css">
  <link rel="stylesheet" href="home.css">
</head>
<body class="page">
  
  <!-- Header -->
  <header class="page__header" role="banner">
    <!-- Molécule : Navbar -->
    <nav class="navbar">
      <div class="navbar__logo">
        <!-- Atom : Logo -->
        <img src="../../atoms/logo/logo.svg" alt="Logo" class="logo">
      </div>
      <ul class="navbar__menu">
        <li><a href="#" class="nav-link">Accueil</a></li>
        <li><a href="#" class="nav-link">Services</a></li>
        <li><a href="#" class="nav-link">Contact</a></li>
      </ul>
    </nav>
  </header>
  
  <!-- Main -->
  <main class="page__main" role="main">
    
    <!-- Hero Section -->
    <section class="hero">
      <h1 class="title title--h1">Bienvenue sur Notre Site</h1>
      <p class="text text--lead">Découvrez nos services exceptionnels.</p>
      <button type="button" class="btn btn--primary btn--large">
        <span class="btn__label">Commencer</span>
      </button>
    </section>
    
    <!-- Features Section -->
    <section class="features">
      <div class="features__grid">
        <!-- Molécule : Card (x3) -->
        <article class="card">
          <h3 class="title title--h3">Service 1</h3>
          <p class="text">Description du service.</p>
          <button type="button" class="btn btn--secondary">En savoir plus</button>
        </article>
        
        <article class="card">
          <h3 class="title title--h3">Service 2</h3>
          <p class="text">Description du service.</p>
          <button type="button" class="btn btn--secondary">En savoir plus</button>
        </article>
        
        <article class="card">
          <h3 class="title title--h3">Service 3</h3>
          <p class="text">Description du service.</p>
          <button type="button" class="btn btn--secondary">En savoir plus</button>
        </article>
      </div>
    </section>
    
  </main>
  
  <!-- Footer -->
  <footer class="page__footer" role="contentinfo">
    <p class="text text--small">&copy; 2024 Mon Projet. Tous droits réservés.</p>
  </footer>
  
</body>
</html>
```

### CSS de Layout (home.css)
```css
/* Page Layout Global */
.page {
  display: grid;
  grid-template-areas:
    "header"
    "main"
    "footer";
  grid-template-rows: auto 1fr auto;
  min-height: 100vh;
}

.page__header {
  grid-area: header;
  position: sticky;
  top: 0;
  background: var(--color-neutral-100);
  box-shadow: var(--shadow-sm);
  z-index: 100;
}

.page__main {
  grid-area: main;
}

.page__footer {
  grid-area: footer;
  background: var(--color-neutral-900);
  color: var(--color-neutral-100);
  padding: var(--spacing-lg);
  text-align: center;
}

/* Hero Section */
.hero {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: var(--spacing-lg);
  padding: var(--spacing-xl) var(--spacing-md);
  text-align: center;
  min-height: 60vh;
  background: linear-gradient(135deg, var(--color-primary-100), var(--color-primary-200));
}

/* Features Section */
.features {
  padding: var(--spacing-xl) var(--spacing-md);
}

.features__grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: var(--spacing-lg);
  max-width: 1200px;
  margin: 0 auto;
}

/* Responsive */
@media (min-width: 768px) {
  .features__grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1024px) {
  .features__grid {
    grid-template-columns: repeat(3, 1fr);
  }
  
  .hero {
    padding: var(--spacing-2xl);
  }
}
```

## Checklist de Validation

- [ ] Wireframe validé avant création
- [ ] Tous les composants existent (atoms + molécules)
- [ ] Structure HTML5 sémantique respectée
- [ ] Layout responsive (Mobile First)
- [ ] Comparaison visuelle avec wireframe (Pixel Perfect)
- [ ] Accessibilité (landmarks ARIA, alt, labels)
- [ ] Performance (images optimisées, CSS minifié pour prod)
