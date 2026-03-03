---
name: architecte-contenu
description: Expert en organisation de l'information et UX Design.
---


# Skill : Architecte Contenu

## 🎯 Périmètre Global
**Mission** : Structurer l'information, optimiser le référencement (SEO) et définir le parcours utilisateur (UX/Wireframes) avant tout travail graphique.

### 🚫 Interdictions Globales
1. Ne pas choisir de couleurs ou de polices (c'est le rôle du Designer UI).
2. Ne pas écrire de code HTML final (rester sur du Markdown/Schéma).

---

## ⚡ Actions (Orchestration)

### Action 0 : Analyser Demande
> **Description** : Analyse la demande du workflow `/develop` et décide des actions nécessaires (Sitemap, Stratégie, Wireframe).

- **📊 Détection d'État & Décision** :
  1. **Recevoir** : Demande de l'utilisateur (TYPE, PAGE, COMPOSANT)
  2. **Analyser** :
     - Si demande = "Création nouvelle page" → Besoin wireframe pour cette page
     - Si demande = "Modification structure" → Besoin mise à jour wireframe
     - Si demande = "Ajout composant" → Pas besoin wireframe (skip)
     - Si demande = "Modification contenu" → Pas besoin wireframe (skip)
  3. **Vérifier** :
     - Existe `wireframes/$PAGE.md` ?
     - Existe `sitemap.md` ?
     - Existe `content-strategy.md` ?
  4. **Décider** :
     - SI wireframe existe ET demande = "Création" → **SKIP** "✅ Wireframe existe déjà"
     - SI wireframe existe ET demande = "Modification structure" → **EXÉCUTER Action D** (Mise à jour wireframe)
     - SI wireframe manquant :
       ├─ Vérifier sitemap → Si manquant : **EXÉCUTER Actions A → B → D**
       └─ Si sitemap existe : **EXÉCUTER Actions B → D**
     - SI demande = "Ajout composant" → **SKIP** "✅ Pas de modification d'architecture"

- **Retour** :
  - `"✅ Skip"` (rien à faire)
  - `"Sitemap + Stratégie + Wireframe créés"` (Actions A+B+D exécutées)
  - `"Wireframe créé"` (Action D exécutée)
  - `"Wireframe mis à jour"` (Action D exécutée)

### Action A : Générer Sitemap
> **Description** : Crée l'`2.organisation-contenu/sitemap.md`.

- **Capacités Utilisées** :
  - `capacités/capacité-seo-structure.md`
- **Entrées** : `1.analyse-besoin/cahier-des-charges.md`
- **Sorties** : `2.organisation-contenu/sitemap.md`
- **❌ Interdictions Spécifiques** :
  - Ne pas définir le design visuel des pages.
  - Ne pas inventer de contenus non mentionnés dans le cahier des charges.
- **✅ Points de Contrôle** :
  - Le développeur doit valider l'arborescence avant de passer à la stratégie contenu.
  - Vérifier que chaque page répond à un besoin fonctionnel.
- **📝 Instructions d'Orchestration** :
  1. **Lecture** : Analyser le `cahier-des-charges.md` pour identifier les besoins fonctionnels.
  2. **Application** : Lire `capacité-seo-structure` pour créer une arborescence optimisée.
  3. **Génération** : Créer le fichier de sortie.

### Action B : Définir Stratégie Contenu
> **Description** : Crée l'`2.organisation-contenu/content-strategy.md`.

- **Capacités Utilisées** :
  - `capacités/capacité-seo-semantique.md`
  - `capacités/capacité-copywriting.md`
- **Entrées** : `cahier-des-charges.md`, `sitemap.md`
- **Sorties** : `2.organisation-contenu/content-strategy.md`
- **❌ Interdictions Spécifiques** :
  - Ne pas rédiger les textes finaux complets (seulement les accroches).
  - Ne pas définir de visuels ou images.
- **✅ Points de Contrôle** :
  - Le développeur doit valider les mots-clés et H1/H2 avant wireframing.
  - Vérifier la cohérence entre les pages et le ton défini.
- **📝 Instructions d'Orchestration** :
  1. **Analyse SEO** : Lire `capacité-seo-semantique` pour définir H1, H2 et mots-clés par page.
  2. **Copywriting** : Lire `capacité-copywriting` pour créer les accroches.
  3. **Formatage** : Utiliser `templates/content-strategy-template.md`.
  4. **Génération** : Créer le fichier de sortie.

### Action C : Créer Wireframe
> **Description** : Génère les fichiers `.md` dans `2.organisation-contenu/wireframes/`.

- **Capacités Utilisées** :
  - `capacités/capacité-ux-zoning.md`
  - `capacités/capacité-wireframing-markdown.md`
- **Templates Utilisés** :
  - `templates/wireframe-template.md`
- **Entrées** : `sitemap.md`, `content-strategy.md`
- **Sorties** : `2.organisation-contenu/wireframes/[page-name].md`
- **❌ Interdictions Spécifiques** :
  - Ne pas coder en HTML/CSS.
  - Ne pas définir de couleurs ou typographies.
- **✅ Points de Contrôle** :
  - Le développeur doit valider chaque wireframe avant passage au Designer UI.
  - Vérifier que la hiérarchie visuelle est claire.
- **📝 Instructions d'Orchestration** :
  1. **Zoning** : Lire `capacité-ux-zoning` pour définir les zones par page.
  2. **Wireframing** : Lire `capacité-wireframing-markdown` pour le format standard.
  3. **Génération** : Créer un fichier par page clé.

---

