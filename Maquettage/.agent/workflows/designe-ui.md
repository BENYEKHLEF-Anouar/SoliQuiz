---
description: Workflow d'exécution pour le skill Designer UI (/designe-ui)
---

# Workflow : Designer UI (`/designe-ui`)

**Objectif** : Exécuter les actions du skill `designer-ui` pour créer l'identité visuelle et les maquettes.
**Protocole** : Suivre le standard [`.agent/resources/protocoles-workflow.md`](.agent/resources/protocoles-workflow.md).

## Exécution

### 1. Détection & Menu
- **Analyser** la demande via `.agent/skills/designer-ui/SKILL.md`.
- **Afficher** le *Template A* (Confirmation) ou *Template B* (Menu) selon le protocole.
- **STOP** : Attendre validation du développeur.

### 2. Exécution Déléguée
- **Source** : Exécuter strictement l'Action choisie depuis le fichier Skill.
- **Trace** : Ajouter `Action exécutée : [Nom de l'action] (Skill: designer-ui)` en fin de réponse.
