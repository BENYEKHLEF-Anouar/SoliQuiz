# Sitemap : SoliQuiz

## Arborescence Principale (Structure Siloing par Rôle)

- **[Authentification / Connexion]** (Page d'accueil publique)
    - Redirection conditionnelle selon le rôle de l'utilisateur.

- **[Espace Apprenant (Étudiant)]**
    - **[Tableau de Bord / Accueil]** : Vue d'ensemble (Score global, QCM en attente).
    - **[Évaluations]** :
        - [Liste des QCM à passer]
        - [Passation du QCM] (Interface de test avec Timer & Auto-sauvegarde)
        - [Feedback Post-Test] (Correction détaillée avec justifications)
    - **[Progression personnelle]** : Historique et scores par micro-objectifs pédagogiques.

- **[Espace Formateur]**
    - **[Tableau de Bord / Accueil]** : Supervision de la classe (Statistiques rapides).
    - **[Gestion Pédagogique (QCM)]** :
        - [Bibliothèque de QCM] (Liste et statuts)
        - [Création / Édition QCM] (Éditeur de questions et choix)
    - **[Suivi d'apprentissage]** : Tableau des scores de la classe ventilés par objectifs pédagogiques.

- **[Espace Administrateur]**
    - **[Tableau de Bord / Supervision]** : Taux de réussite globaux par cohorte / module.
    - **[Administration système]** :
        - [Gestion des Utilisateurs] (Attribution des rôles)
        - [Configuration API SoliLMS] (Endpoints et logs de synchronisation)

## Règles Appliquées
- **Architecture de contenu** : Séparation stricte des accès post-login par profils d'utilisateurs.
- **Règle des 3 clics respectée** : Les fonctionnalités vitales (Passer un test, Créer un QCM, Configurer) sont à 1 ou 2 clics du tableau de bord.
- **Cohérence des fonctionnalités** : Réponse directe aux exigences du cahier des charges (Timer, Feedback, SoliLMS, Progression par objectifs).
