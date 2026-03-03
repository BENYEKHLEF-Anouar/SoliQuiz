---
description: Workflow d'exécution pour le skill Architecte Contenu (/architecte-contenu)
---

# Workflow : Architecte Contenu (`/architecture-contenu`)

**Objectif** : Exécuter les actions du skill Architecte Contenu (Sitemap, Stratégie Contenu, Wireframes).
**Protocole** : Suivre le standard [`.agent/resources/protocoles-workflow.md`](.agent/resources/protocoles-workflow.md).

## Exécution

### 1. Détection & Menu
- **Analyser** la demande via `.agent/skills/architecte-contenu/SKILL.md`.
- **Afficher** le *Template A* (Confirmation) ou *Template B* (Menu) selon le protocole.
- **STOP** : Attendre validation du développeur.

### 2. Exécution Déléguée
- **Source** : Exécuter strictement l'Action choisie depuis le fichier Skill.
- **Trace** : Ajouter `Action exécutée : [Nom de l'action] (Skill: architecte-contenu)` en fin de réponse.
