# Manifeste des Mockups - SoliQuiz UI

## Liste des Mockups (7/12)

| # | Page | Fichier HTML | Rôle | Statut | Interactivité |
|---|------|--------------|------|--------|---------------|
| 01 | Authentification | `01-auth-login.html` | public | Terminé (#17B0CF) | - |
| 02 | Dashboard Apprenant | `02-student-dashboard.html` | etudiant | Terminé (#17B0CF) | - |
| 03 | Dashboard Formateur | `03-formateur-dashboard.html` | formateur | Terminé (#17B0CF) | - |
| 04 | Bibliothèque QCM | `04-bibliotheque-qcm.html` | formateur | Terminé (#17B0CF) | - |
| 05 | Passation QCM | `05-passation-qcm.html` | etudiant | Terminé (#17B0CF) | ✅ Navigation questions, Timer, Réponses |
| 06 | Dashboard Admin | `06-admin-dashboard.html` | admin | Terminé (#17B0CF) | ✅ Tabs (Overview, Users, Classes, Stats) |
| 07 | Résultats Apprenant | `07-apprenant-resultats.html` | etudiant | Terminé (#17B0CF) | ✅ Tabs (Tous/Réussis/Échoués), Expand détails |

## À Venir (5 mockups)

| # | Page | Rôle | Priorité | Interactivité prévue |
|---|------|------|----------|---------------------|
| 08 | Création QCM | formateur | Haute | Éditeur questions, Aperçu live |
| 09 | Résultats Cohorte | formateur | Moyenne | Filtres, Export |
| 10 | Gestion Utilisateurs | admin | Moyenne | CRUD, Modals |
| 11 | Landing Page | public | Basse | Scroll animations |
| 12 | Supervision Live | formateur | Haute | WebSocket, Temps réel |

## Notes

- **Couleur primaire**: `#17B0CF` (Digital Ocean Blue)
- **Icônes**: Lucide icons style (stroke-width: 2 ou 2.5)
- **Interactivité**: Alpine.js pour tabs, modals, navigation
- Ces mockups sont extraits des vues Blade Laravel
- Les données statiques remplacent les variables Blade (`{{ $variable }}`)
