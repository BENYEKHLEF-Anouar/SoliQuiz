---
description: Orchestrateur de création de page (détecte et complète les étapes manquantes).
---

Ce workflow analyse l'état du projet pour la page spécifiée et lance la production à partir du premier livrable manquant.

**Usage** : `/create-page [nom-de-la-page]`

1. **Scan des livrables** :
   Vérifier l'existence des fichiers suivants dans l'ordre :
   - (1) `1.analyse-besoin/cahier-des-charges.md`
   - (2) `2.organisation-contenu/wireframes/$PAGE.md`
   - (3) `3.maquettage/composition/comp-$PAGE.md`
   - (4) `3.maquettage/mockups/$PAGE.html`
   - (5) `4.developpement/$PAGE.html`

2. **Identification du point de départ** :
   - SI (1) manque : Lancer `/analyse-besoin`
   - SINON SI (2) manque : Lancer `/architecture-contenu`
   - SINON SI (3) manque : Lancer `/designe-ui` (Action 0 : Analyser Wireframe & Créer Composition)
   - SINON SI (4) manque : 
     - Vérifier les composants dans `composition/comp-$PAGE.md`.
     - Créer les composants manquants via `/designe-ui` (Action B/C).
     - Assembler le mockup via `/designe-ui` (Action D : Assembler Mockup Final).
   - SINON SI (5) manque : Lancer `/develope-front` (Action B : Intégrer une Page depuis un Mockup)
   - SINON : Informer "✅ La page $PAGE est déjà complète."

3. **Exécution en cascade** :
   Continuer automatiquement les étapes suivantes jusqu'à la génération du code final.
