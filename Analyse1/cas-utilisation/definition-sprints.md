# Idéation — Cas d'Utilisation SoliQuiz

> **Phase Design Thinking : Idéation**
> Chaque cas d'utilisation est une réponse directe à un problème ou besoin validé lors de la phase d'empathie.

---

## Traçabilité Empathie → Solution

| Problème validé (Empathie) | Persona source | Solution (Cas d'utilisation) |
|---|---|---|
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

## Sprint 1 — MVP : Éliminer la friction de base

**Objectif** : Permettre aux formateurs de créer un QCM et aux étudiants de le passer avec un score automatique — en remplaçant Google Forms dès le premier sprint.

### Cas d'Utilisation

1. **UC1-1 : Créer la structure d'un QCM**
   - *Répond à* : Fatine et Youssef ne peuvent pas créer de QCM structuré sans passer par des outils externes.
   - Le formateur crée un QCM avec un titre et une description, lié à sa session.

2. **UC1-2 : Ajouter des Questions et des Choix**
   - *Répond à* : Besoin de définir des questions à choix unique ou multiple avec la bonne réponse — fonctionnalité absente de Google Forms par objectif.
   - Ajout, modification et suppression de questions et de choix de réponses.

3. **UC1-3 : Passer le QCM (Étudiant)**
   - *Répond à* : L'étudiant n'a actuellement aucun outil intégré pour passer une évaluation dans l'environnement Solicode.
   - L'étudiant accède à son QCM et coche ses réponses depuis un interface dédiée.

4. **UC1-4 : Calcul automatique du score global**
   - *Répond à* : Fatine et Youssef passent du temps à corriger manuellement et à saisir les notes dans SoliLMS.
   - Le système corrige les réponses et affiche immédiatement le score total.

5. **UC1-5 : Authentification (Rôle Formateur / Étudiant)**
   - *Répond à* : L'éparpillement sur des outils non-officiels sans identification claire génère des failles de sécurité (Fouad).
   - Connexion sécurisée pour identifier le profil de l'utilisateur avant tout accès.

6. **UC1-6 : Gestion des utilisateurs (Administrateur)**
   - *Répond à* : Fouad ne peut pas gérer les accès de façon centralisée actuellement.
   - L'administrateur active et attribue les rôles Formateur et Étudiant depuis un panneau unique.

7. **APK Mobile (V1) : Consultation en lecture seule**
   - *Répond à* : Les formateurs et étudiants ont besoin de consulter les scores rapidement sur le terrain.
   - **UC1_MOB_E** : L'étudiant consulte son score global.
   - **UC1_MOB_F** : Le formateur consulte les scores de sa classe.

---

## Sprint 2 — Avancé : Pédagogie, Analyse et Intégration

**Objectif** : Répondre aux besoins de granularité analytique, de feedback pédagogique, d'ergonomie mobile et d'intégration avec SoliLMS.

### Cas d'Utilisation

1. **UC_LINK_QCM : Lier le QCM à une Session et un Objectif**
   - *Répond à* : Youssef a besoin d'un QCM distinct par session et par objectif — pas d'un test global.
   - Chaque QCM est associé à une session précise et à un micro-objectif pédagogique.

2. **UC_SCORE : Score détaillé par objectif pédagogique**
   - *Répond à* : Youssef fait ses calculs sous Excel faute de granularité dans les outils actuels.
   - Le système affiche un score segmenté par objectif (ex. : Logique 80%, Syntaxe 20%).

3. **UC_FEEDBACK : Feedback détaillé et correction immédiate**
   - *Répond à* : Soufiane reçoit un score brut sans explication, ce qui l'empêche de cibler ses révisions.
   - Dès la soumission (Web ou APK), l'apprenant voit la correction complète avec les explications.

4. **UC_HISTORY & UC_PROGRESS_FORM : Historique et Progression**
   - *Répond à* : Soufiane ne peut pas suivre sa progression. Youssef n'a aucune vue longitudinale.
   - **UC_HISTORY** : L'étudiant suit sa progression personnelle.
   - **UC_PROGRESS_FORM** : Le formateur suit l'évolution de la classe.

5. **UC_TIMER : Timer + sauvegarde automatique (Focus Mehdi)**
   - *Répond à* : Mehdi perd ses réponses en cas de coupure et subit des clôtures sans avertissement.
   - Compte à rebours visible et sauvegarde en temps réel (obligatoire pour l'APK).

6. **UC_EXPORT : Export des notes vers SoliLMS**
   - *Répond à* : Élimination de la double saisie manuelle pour Youssef et Fouad.
   - Les notes finalisées sont envoyées à SoliLMS via l'API.

7. **UC_DASH_ADMIN : Tableau de bord global (Administrateur)**
   - *Répond à* : Fouad n'a aucune vue centralisée sur les performances globales du centre.
   - Supervision des taux de réussite par cohorte et par module.

8. **UC_API_CONFIG : Configuration API SoliLMS**
   - *Répond à* : Nécessité technique pour l'interopérabilité.
   - L'administrateur configure les endpoints de synchronisation.
