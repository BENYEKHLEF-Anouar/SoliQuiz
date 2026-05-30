# Cahier des Charges : SoliQuiz

## 1. Contexte & Enjeux
- **Pourquoi ce projet ?** : Digitaliser l'évaluation quotidienne à Solicode, centraliser les outils (remplacer Google Forms/Excel), éliminer la double saisie manuelle et offrir un suivi détaillé des compétences.
- **Cible** : Étudiants (apprenants), Formateurs, et Administrateur de Solicode limitant l'opacité et permettant un suivi granulaire de la progression.
- **Tons & Style** : Professionnel, clair, pédagogique et apaisant (expérience sans stress pour les évaluations).

## 2. Besoins Fonctionnels
- **Besoin Métier** : Automatiser la création, la passation et la correction de QCM en les rattachant à des micro-objectifs précis, et injecter les résultats automatiquement dans un outil externe (SoliLMS).
- **Fonctionnalités Clés** : 
    - Authentification sécurisée par rôles.
    - Création de QCM (choix unique/multiple) par les formateurs.
    - Passation du test avec timer intégré, sauvegarde automatique en temps réel et correction immédiate justifiée.
    - Segmentation des scores par micro-objectifs pédagogiques.
    - Tableau de bord avec suivi de la progression.
    - Synchronisation des résultats finale avec SoliLMS.
- **Contenus Indispensables** : Énoncés des QCM, choix de réponses, feedbacks pédagogiques, historiques de tests, statistiques individuelles et globales.

## 3. Contraintes
- **Deadline** : Non précisée (Itératif : Sprints 1 et 2)
- **Technique** : Interfaces statiques Mobile-First obligatoires (HTML5, CSS, JS vanilla), design system clair.
- **Accessibilité** : Tolérance aux coupures réseau (Mode dégradé/Local storage).
- **Langue** : Français
