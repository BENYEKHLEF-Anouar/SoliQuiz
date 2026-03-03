---
description: Workflow orchestrateur intelligent pour le développement de site web
---

# Workflow : Develop (`/develop`)

**Objectif** : Orchestrer le processus de développement en appelant séquentiellement les workflows appropriés.
**Principe** : Chaque skill gère sa propre détection d'état et décide s'il doit agir.

## Exécution

### 1. Recueillir l'Information
- Demander : "Quelle page souhaitez-vous développer ? (home, contact, about, etc.)"
- Stocker : `$PAGE`

### 2. Exécution Séquentielle

**Appeler les workflows dans l'ordre du processus** :

#### Étape 1 : Analyse Besoin
```
Appeler : /analyse-besoin
→ Le skill analyse si cahier-des-charges.md existe
→ Si manquant : Créer le cahier
→ Si existe : Passer à l'étape suivante
```

#### Étape 2 : Architecture Contenu
```
Appeler : /architecture-contenu avec $PAGE
→ Le skill vérifie si wireframes/$PAGE.md existe
→ Si manquant : Créer le wireframe + sitemap
→ Si existe : Passer à l'étape suivante
```

#### Étape 3 : Design UI
```
Appeler : /designe-ui avec $PAGE
→ Le skill détecte automatiquement l'action nécessaire :
  - Action 0 : Si comp-$PAGE.md manquant
  - Action A : Si charte-graphique manquante
  - Actions B+C : Si composants manquants
  - Action D : Si mockup manquant
  - Skip : Si tout existe déjà
```

#### Étape 4 : Développement Front
```
Appeler : /develope-front avec $PAGE
→ Le skill vérifie si mockups/$PAGE.html existe
→ Si manquant : Afficher erreur + suggérer /designe-ui
→ Si existe : Intégrer le mockup en code production
```

### 3. Résultat
- Afficher : "✅ Page $PAGE développée avec succès !"
- Lister les fichiers créés

---

## Principe de Fonctionnement

**Responsabilité Distribuée** :
- **Workflow** : Orchestre l'ordre d'exécution
- **Skills** : Détectent leur propre état et agissent en conséquence

**Optimisation des Dépendances** :
- Si `comp-$PAGE.md` existe → `wireframes/$PAGE.md` existe forcément
- Si `mockups/$PAGE.html` existe → `comp-$PAGE.md` existe forcément
- Les skills utilisent ces dépendances pour éviter les vérifications inutiles

**Gestion des Composants** :
- Chaque skill vérifie `components-lib/manifest.md` avant création
- Si composant existe → Réutiliser
- Si similaire existe → Proposer variant
- Si nouveau → Créer
