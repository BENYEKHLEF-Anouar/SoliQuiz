---
trigger: always_on
type: rule
id: config-environnement
---
# Configuration et Environnement

## Gestion des Fichiers Ignorés
- **Respect du .gitignore** : L'agent DOIT consulter le fichier `.gitignore` à la racine du projet.
- **Interdiction de lecture** : L'agent NE DOIT PAS lire, analyser ou suggérer des modifications pour les fichiers ou dossiers listés dans `.gitignore` (ex: `vendor/`, `node_modules/`, `.env`, etc.), sauf demande explicite et justifiée de l'utilisateur.
