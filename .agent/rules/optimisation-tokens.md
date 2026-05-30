---
trigger: always_on
---

---
trigger: always_on
---

# Optimisation des Tokens

## Objectif

Réduire drastiquement la consommation de tokens dans les interactions avec l'agent, en optimisant l'**Input** (ce que l'agent lit), l'**Output** (ce que l'agent écrit) et la **Réflexion** (thinking process).

## Instructions

### 1. Directives de Communication (Output)

- **Supprimer** toutes introductions polies ("Bien sûr", "Avec plaisir", "Voici le code").
- **Passer** directement à la solution technique sans préambule.
- **Utiliser** le format `diff` ou indiquer uniquement les lignes à modifier (jamais de fichier complet si modification locale possible).
- **Limiter** les explications à une phrase de moins de 15 mots si nécessaire.
- **Répondre** par "OK" ou par le code pur quand c'est suffisant.
- **Ne pas ajouter** de commentaires explicatifs dans le code produit, sauf s'ils sont critiques pour la logique.

### 2. Gestion du Contexte (Input)

- **Lire** les fichiers uniquement si strictement nécessaire pour la tâche en cours (Lazy Reading).
- **Ignorer** les fichiers non liés au contexte de la tâche (Focus sélectif).
  - *Exemple* : Pour une modification Alpine.js, ignorer les fichiers CSS ou contrôleurs Laravel non liés.
- **Ne jamais** résumer ce qui vient d'être fait après une modification (No Summary).

### 3. Optimisation de la Réflexion (Thinking)

- **Limiter** les étapes de "pensée interne" (Thinking process) au strict minimum technique.
- **Ne pas reformuler** la demande de l'utilisateur dans le raisonnement interne.

## Interdictions

- **INTERDICTION** de faire des introductions ou conclusions polies.
- **INTERDICTION** de renvoyer un fichier complet si un diff suffit.
- **INTERDICTION** d'ajouter des résumés après les modifications.
- **INTERDICTION** de reformuler la demande dans le processus de réflexion interne.