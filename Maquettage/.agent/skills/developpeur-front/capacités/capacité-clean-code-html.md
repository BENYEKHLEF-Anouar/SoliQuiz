# Capacité : Clean Code HTML

## Objectif
Produire un code source lisible, maintenable et professionnel.

## Checklist Qualité
- [ ] **Indentation** : 2 espaces (pas de tabs).
- [ ] **Commentaires** : Baliser les grandes sections (`<!-- HEADER -->`).
- [ ] **Attributs** : Toujours utiliser des guillemets doubles. `class="btn"`.
- [ ] **Ordre** : `id` avant `class`, puis `data-*`.

## Exemple
```html
<!-- BAD -->
<div class='container' id=main>
  <button onclick="alert()">Click</button>
</div>

<!-- GOOD -->
<!-- Main Container -->
<div id="main" class="container">
  <button type="button" class="btn js-alert">Click me</button>
</div>
```
