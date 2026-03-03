# Cahier des Charges : SoliQuiz

## 1. Contexte & Enjeux
- **Pourquoi ce projet ?** : Éliminer la lourdeur logistique (Google Forms) et la double saisie des notes, tout en offrant une évaluation granulaire par micro-objectif connectée au système central (SoliLMS).
- **Cible** : 
  - Étudiants (pour qui l'expérience doit être mobile-first, stress-free avec auto-sauvegarde).
  - Formateurs (pour qui la création de QCM et le suivi doivent être rapides et centralisés).
  - Administrateurs (pour la supervision globale et la gestion des accès).
- **Tons & Style** : Professionnel, clair, pédagogique et rassurant (pour les étudiants).

## 2. Besoins Fonctionnels
- **Besoin Métier** : Automatiser l'évaluation quotidienne (création, passation, calcul de score analytique) pour libérer du temps aux formateurs et offrir un feedback constructif et détaillé aux étudiants, tout en permettant à l'administration de suivre les performances.
- **Fonctionnalités Clés** : 
    - Création de QCM (Questions à choix unique/multiple, paramétrage).
    - Passation de tests (Timer, auto-sauvegarde des réponses).
    - Calcul automatique de scores globaux et par objectif pédagogique.
    - Affichage de corrections détaillées (feedback).
    - Tableaux de bord de suivi (étudiant, formateur, et vue globale pour l'admin).
    - Synchronisation et interopérabilité avec SoliLMS.
- **Contenus Indispensables** : Énoncés de questions/choix, corrections détaillées, score global et ventilé par objectif, historiques de résultats, statistiques de cohorte.

## 3. Contraintes
- **Deadline** : Découpage Agile en 2 Sprints majeurs (Sprint 1 : MVP, Sprint 2 : Fonctionnalités Avancées).
- **Technique** : Site Statique (Imposé, approche Vanilla JS et Tailwind CSS).
- **Langue** : Français
