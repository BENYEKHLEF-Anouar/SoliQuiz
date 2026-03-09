# Idéation — Planification Agile & Cas d'Utilisation

> **Phase Design Thinking : Idéation**  
> Chaque cas d'utilisation est une réponse directe à un problème ou besoin validé lors de la phase d'empathie (Traçabilité).

---

## 1. Traçabilité Empathie → Solution

| Problème validé (Empathie) | Persona source | Solution (Cas d'utilisation) |
|:---|:---|:---|
| Double saisie entre Google Forms et SoliLMS | Fatine, Youssef | UC1-1, UC1-2 — Création de QCM intégrée |
| Aucune correction automatique des scores | Fatine, Youssef | UC1-4 — Calcul automatique du score |
| Outils non-officiels, accès éparpillés | Fouad | UC1-5 — Auth unifiée + UC1-6 — Gestion des rôles |
| Aucune granularité par objectif pédagogique | Youssef | UC2-1, UC2-2 — Liaison session/objectif + score par objectif |
| Score brut sans explication ni feedback | Soufiane | UC2-3 — Feedback détaillé et correction |
| Aucun suivi de progression dans le temps | Soufiane, Youssef, Fouad | UC2-4 — Historique et tableau de bord |
| Interface inadaptée au mobile, perte de données | Mehdi | UC_TIMER — Timer + sauvegarde auto + mobile-first |
| Remontée manuelle des notes dans SoliLMS | Fouad, Youssef | UC_EXPORT, UC_API_CONFIG — Connexion SoliLMS |
| Aucune vue globale de l'activité du centre | Fouad | UC_DASH_ADMIN — Tableau de bord administrateur |

---

## 2. Sprint 1 — MVP : Éliminer la friction de base

**Objectif** : Remplacer Google Forms dès le premier sprint en permettant aux formateurs de créer un QCM et aux étudiants de le passer avec un score automatique.

### Backlog Sprint 1

| Catégorie | ID | Cas d'Utilisation | Description | Persona |
| :--- | :--- | :--- | :--- | :--- |
| **Authentification** | UC1-5 | Se connecter | Accès sécurisé selon le rôle (Admin / Formateur / Étudiant). | Tous |
| **Administration** | UC1-6 | Gérer les utilisateurs | Activer, attribuer les rôles et révoquer les accès. | Fouad |
| **QCM** | UC1-1 | Créer un QCM | Titre, description, lien à une session et un formateur. | Youssef, Fatine |
| **QCM** | UC1-2 | Ajouter questions & choix | Types : choix unique (radio) ou choix multiple (checkbox). | Youssef, Fatine |
| **Passation** | UC1-3 | Passer le QCM | Interface de test dédiée sur le Web pour l'étudiant. | Mehdi, Soufiane |
| **Résultats** | UC1-4 | Score automatique | Correction et calcul du score global immédiatement à la soumission. | Fatine, Soufiane |
| **Mobile V1** | UC1-MOB | Consultation mobile | Scores et QCM en lecture seule depuis l'APK (NativePHP). | Mehdi |

**Résultat Attendu Sprint 1 :** Le formateur peut abandonner Google Forms. Il crée son QCM en quelques minutes et l'étudiant obtient son score dès la fin du test sans correction manuelle.

---

## 3. Sprint 2 — Avancé : Pédagogie, Analyse et Intégration

**Objectif** : Transformer SoliQuiz en un écosystème pédagogique complet avec granularité analytique, feedback détaillé et intégration SoliLMS.

### Backlog Sprint 2

| Axe | ID | Cas d'Utilisation | Description | Persona |
| :--- | :--- | :--- | :--- | :--- |
| **Pédagogie** | UC_LINK | Lier QCM à un objectif | Chaque QCM est associé à un micro-objectif et une session. | Youssef |
| **Analyse** | UC_SCORE | Score par objectif | Score ventilé par compétence (ex: Logique 80%, Syntaxe 20%). | Youssef, Fouad |
| **Feedback** | UC_FEEDBACK | Correction détaillée | Affichage de la correction complète + explications justifiées. | Soufiane |
| **Ergonomie** | UC_TIMER | Timer + auto-sauvegarde | Compte à rebours visible et sauvegarde en temps réel (Anti-stress). | Mehdi |
| **Progression** | UC_HISTORY | Historique étudiant | Suivi personnel de la montée en compétences par objectif. | Soufiane |
| **Pilotage** | UC_DASH | Dashboard Admin | Supervision des taux de réussite par cohorte et par module. | Fouad |
| **Intégration** | UC_EXPORT | Export vers SoliLMS | Notes finalisées envoyées via API sans saisie manuelle. | Fouad, Youssef |
| **Intégration** | UC_API | Config API SoliLMS | L'admin configure les endpoints de synchronisation. | Fouad |

**Résultat Final Sprint 2 :** SoliQuiz devient un véritable pont numérique. Le formateur analyse les lacunes précises ; l'étudiant apprend de ses erreurs ; l'administrateur supervise le centre avec transparence.
