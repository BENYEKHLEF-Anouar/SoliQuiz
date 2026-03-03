---
description: Workflow d'exécution pour le skill Analyste Besoin
---

# Workflow : Analyste Besoin (`/analyse-besoin`)

**Objectif** : Exécuter les actions du skill Analyste Besoin (Cadrage, Cahier des charges).
**Protocole** : Suivre le standard [`.agent/resources/protocoles-workflow.md`](.agent/resources/protocoles-workflow.md).

## Exécution

### 1. Détection & Menu
- **Analyser** la demande via `.agent/skills/analyste-besoin/SKILL.md`.
- **Afficher** le *Template A* (Confirmation) ou *Template B* (Menu) selon le protocole.
- **STOP** : Attendre validation du développeur.

### 2. Exécution Déléguée
- **Source** : Exécuter strictement l'Action choisie depuis le fichier Skill.
- **Trace** : Ajouter `Action exécutée : [Nom de l'action] (Skill: analyste-besoin)` en fin de réponse.