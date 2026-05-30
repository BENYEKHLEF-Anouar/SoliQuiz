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
| Interface inadaptée au mobile, perte de données | Mehdi | UC_TIMER_F, UC_AUTOSAVE — Timer personnalisé + sauvegarde auto + mobile-first |
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

## 3. Sprint 2 — Avancé : Pédagogie et Analyse

**Objectif** : Transformer SoliQuiz en un écosystème pédagogique complet avec granularité analytique et feedback détaillé.

### Backlog Sprint 2

| Axe | ID | Cas d'Utilisation | Description | Persona |
| :--- | :--- | :--- | :--- | :--- |
| **Pédagogie** | UC_LINK | Lier QCM à un objectif | Chaque QCM est associé à un micro-objectif et une session. | Youssef |
| **Analyse** | UC_SCORE | Score par objectif | Score ventilé par compétence (ex: Logique 80%, Syntaxe 20%). | Youssef, Fouad |
| **Feedback** | UC_FEEDBACK | Correction détaillée | Affichage de la correction complète + explications justifiées. | Soufiane |
| **Ergonomie** | UC_TIMER_F | Personnaliser le Timer | Le formateur définit une durée spécifique pour le QCM. | Youssef |
| **Ergonomie** | UC_AUTOSAVE | Auto-sauvegarde | Sauvegarde en temps réel des réponses de l'étudiant (Anti-stress). | Mehdi |
| **Progression** | UC_HISTORY | Historique étudiant | Suivi personnel de la montée en compétences par objectif. | Soufiane |
| **Pilotage** | UC_DASH | Dashboard Admin | Supervision des taux de réussite par cohorte et par module. | Fouad |
| **Export** | UC_EXPORT_FILE | Export manuel des notes | Téléchargement des notes au format Excel, PDF ou CSV. | Fouad, Youssef |
**Résultat Final Sprint 2 :** SoliQuiz offre une analyse approfondie. Le formateur repère les lacunes précises ; l'étudiant apprend de ses erreurs ; l'administrateur supervise le centre avec transparence.

---

## 4. Sprint 3 — IA, Sécurité & Notifications

**Objectif** : Intégrer l'intelligence artificielle comme levier d'automatisation (génération de QCM, chatbot d'accueil) et renforcer la sécurité du cycle d'authentification (mot de passe oublié + alerte admin).

### Backlog Sprint 3

| Axe | ID | Cas d'Utilisation | Description | Issue GitHub | Persona |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **IA** | UC_CHATBOT | Chatbot IA Landing Page | Concierge IA interactif sur la page d'accueil pour guider les visiteurs et répondre aux questions fréquentes. | [#13](https://github.com/BENYEKHLEF-Anouar/SoliQuiz/issues/13) | Visiteur, Utilisateur |
| **IA** | UC_AI_QCM | Générateur IA de QCM | Le formateur décrit un sujet en langage naturel ; l'IA génère automatiquement les questions, options et correction. | [#14](https://github.com/BENYEKHLEF-Anouar/SoliQuiz/issues/14) | Youssef, Fatine |
| **IA** | UC_AI_REVIEW | Validation du QCM généré | Le formateur révise, modifie et valide le QCM proposé par l'IA avant publication. | [#14](https://github.com/BENYEKHLEF-Anouar/SoliQuiz/issues/14) | Youssef, Fatine |
| **Sécurité** | UC_FORGOT_PW | Signaler un mot de passe oublié | L'utilisateur signale son mot de passe oublié à l'administrateur. | [#15](https://github.com/BENYEKHLEF-Anouar/SoliQuiz/issues/15) | Tous |
| **Sécurité** | UC_RESET_PW | Réinitialiser manuellement le mot de passe | L'administrateur réinitialise les identifiants et fournit de nouveaux accès sécurisés. | [#15](https://github.com/BENYEKHLEF-Anouar/SoliQuiz/issues/15) | Fouad |
| **Notification** | UC_ADMIN_ALERT | Recevoir l'alerte de mot de passe oublié | L'administrateur reçoit une alerte instantanée de la demande de réinitialisation. | [#15](https://github.com/BENYEKHLEF-Anouar/SoliQuiz/issues/15) | Fouad |

**Résultat Attendu Sprint 3 :** SoliQuiz intègre l'IA comme collaborateur actif : les formateurs gagnent du temps en génération de QCM, les visiteurs bénéficient d'une aide contextuelle dès la landing page, et la sécurité des comptes est assurée de manière centralisée et réactive par l'administrateur.

---

## 5. Sprint 4 — Intégration SoliLMS

**Objectif** : Connecter SoliQuiz à l'écosystème global de Solicode en automatisant la remontée des notes vers SoliLMS.

### Backlog Sprint 4

| Axe | ID | Cas d'Utilisation | Description | Persona |
| :--- | :--- | :--- | :--- | :--- |
| **Intégration** | UC_EXPORT | Export vers SoliLMS | Notes finalisées envoyées via API sans saisie manuelle. | Fouad, Youssef |
| **Intégration** | UC_API_CONF | Config API SoliLMS | L'admin configure les endpoints de synchronisation. | Fouad |

**Résultat Attendu Sprint 4 :** SoliQuiz devient un véritable pont numérique. Le système est interconnecté, éliminant définitivement la double saisie pour les formateurs et l'administration.

