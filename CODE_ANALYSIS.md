# Analyse Complète du Code — SoliQuiz (Web & Mobile)

Ce document fournit une vue d'ensemble technique des projets web et mobile constituant l'écosystème de gestion d'évaluations QCM **SoliQuiz**.

---

## 1. Architecture Générale

SoliQuiz est divisé en deux applications distinctes mais coordonnées :

### A. SoliQuiz (Web / Backend API)
*   **Framework** : Laravel 12.x
*   **Rôle** : Fournisseur de l'interface Web d'administration et de gestion pédagogique, et API d'arrière-plan pour le client mobile.
*   **Conception** : Architecture de type **Thin-Controller / Rich-Service** :
    *   Les contrôleurs HTTP interceptent les requêtes et renvoient les réponses.
    *   La logique d'affaires (CRUD, notations, cycle de vie) est isolée dans la couche `app/Services/`.

### B. SoliQuiz-mobile (Mobile Android)
*   **Framework** : NativePHP pour Android enveloppant une application Laravel locale.
*   **Rôle** : Interface optimisée smartphone pour les apprenants (passation) et tableau de bord de supervision simplifié pour les formateurs.
*   **Conception** : 
    *   Les écrans sont rendus via Blade et dynamisés côté client avec Alpine.js.
    *   L'état d'authentification (`token`, `user`) est stocké localement et envoyé au serveur via un client API asynchrone utilisant Laravel Sanctum.

---

## 2. Structure Pédagogique & Données

Le système est relationnel (MySQL côté backend) et respecte la structure hiérarchique suivante :

```
Seance (Séances de formation)
  └── UniteApprentissage (UA)
        └── Competence (Compétences ciblées)
              └── QCM (Actif/Brouillon/Terminé, score de réussite /20, durée)
                    └── Question (Choix unique ou multiple, points)
                          └── Option (Texte, est_correcte, feedback)
```

### Modèles Clés et Rôles (RBAC)
*   **[User](file:///d:/WebProjects/SoliQuiz/SoliQuiz/app/Models/User.php)** : Représente les utilisateurs. Les profils sont segmentés en trois rôles Spatie : `admin`, `formateur`, `etudiant`.
*   **[Classe](file:///d:/WebProjects/SoliQuiz/SoliQuiz/app/Models/Classe.php)** : Regroupe des étudiants associés à un formateur responsable unique.
*   **[Tentative](file:///d:/WebProjects/SoliQuiz/SoliQuiz/app/Models/Tentative.php)** : Enregistre l'historique de passage d'un étudiant sur un QCM avec date de début/fin, statut (`en_cours`, `reussi`, `echoue`) et note finale sur 20.

---

## 3. Flux & Services d'Arrière-plan (Backend)

La couche service dans `app/Services/` assure l'encapsulation de la logique métier :

### [PassationService](file:///d:/WebProjects/SoliQuiz/SoliQuiz/app/Services/PassationService.php)
Gère le cycle de vie complet de l'évaluation :
*   `demarrer()` : Instancie ou reprend une tentative active.
*   `enregistrerReponses()` : Traite et met à jour les options sélectionnées.
*   `soumettre()` : Calcule le score final sur 20, clôture la tentative, et vérifie si la soumission doit automatiquement fermer le QCM à la classe complète.

### [ResultatService](file:///d:/WebProjects/SoliQuiz/SoliQuiz/app/Services/ResultatService.php)
Calcule les corrections détaillées et gère la compilation des statistiques des cohortes.

---

## 4. Intégrations d'Intelligence Artificielle

L'intelligence artificielle (Google Gemini) est intégrée de trois manières complémentaires dans [AiService](file:///d:/WebProjects/SoliQuiz/SoliQuiz/app/Services/AiService.php) :

1.  **Générateur de QCM** : Permet aux formateurs de générer des questions basées sur un sujet fourni. Requrait n8n sur le port `5678` avec un modèle Gemini configuré en retour JSON strict.
2.  **Explication Pédagogique** : Offre une correction interactive de question suite à une mauvaise réponse en s'appuyant sur un webhook n8n configuré pour guider l'étudiant.
3.  **SoliBot Chatbot** : Un assistant de chat interactif embarqué utilisant directement l'API Gemini 2.5 Flash avec support automatique du Français, de l'Anglais et du Darija (Moroccan Arabic).

---

## 5. Qualité du Code & Validation

L'application web dispose d'une couverture de tests solide avec **59 tests unitaires et fonctionnels** exécutables via :
```bash
php artisan test
```
Ces tests valident les fonctionnalités d'authentification, de gestion de classes, de calcul des notes, de structure des QCMs et les mocks d'API d'IA.
