# SoliQuiz — Rôles & Fonctionnalités

---

## 1. Administrateur

### Identité
- **Rôle Spatie** : `admin`
- **type_profil** : `admin`
- **Accès** : Toutes les pages, tous les droits de lecture/écriture
- **Bypass** : Le middleware `EnsureUserHasRole` laisse l'admin passer partout

### Pages & Actions

| Page | Route | Ce qu'il peut faire |
|------|-------|---------------------|
| **Dashboard** | `/admin/dashboard` | Voir les KPI globaux (total utilisateurs, total QCMs, QCMs actifs, score moyen). Accès rapide aux actions principales. |
| **Gestion Utilisateurs** | `/admin/users` | Lister (recherche par nom/email, filtre par type_profil et classe, pagination). Créer un utilisateur (nom, prénom, email, mot de passe, type_profil, classe si étudiant, matricule si formateur). Modifier un utilisateur. Supprimer un utilisateur (avec confirmation). Affecter une classe à plusieurs étudiants en masse. |
| **Structure Pédagogique** | `/admin/pedagogie` | Créer / modifier / supprimer des Séances. Créer / modifier / supprimer des Unités d'Apprentissage (rattachées à une séance). Créer / modifier / supprimer des Compétences (rattachées à une UA). Toute entité créée porte son `user_id`. |
| **Supervision QCM** | `/admin/qcms` | Voir tous les QCMs en lecture seule avec statistiques. Changer le statut d'un QCM manuellement (fermer un QCM). Voir les résultats détaillés par QCM. |
| **Profil** | `/profile` | Modifier son nom, email, mot de passe. |

### Workflow quotidien
```
Connexion
  → Dashboard (vue globale)
  → Gérer les utilisateurs (CRUD, recherche, filtres, affectation classe)
  → Gérer la structure pédagogique (séances → UAs → compétences)
  → Superviser les QCMs (stats, fermeture manuelle)
  → Mon profil
```

### Règles métier
- L'admin est le seul à pouvoir créer des Séances et UAs.
- Les compétences peuvent être créées/modifiées/supprimées par l'admin **et** le formateur.
- L'admin peut fermer manuellement un QCM (passer en `termine`).
- L'admin ne crée pas de QCM — c'est le rôle du formateur.
- L'admin voit tout, même les QCMs des autres formateurs.

---

## 2. Formateur

### Identité
- **Rôle Spatie** : `formateur`
- **type_profil** : `formateur`
- **Accès** : Pages formateur uniquement (+ profil)

### Pages & Actions

| Page | Route | Ce qu'il peut faire |
|------|-------|---------------------|
| **Dashboard** | `/formateur/dashboard` | Voir ses KPIs (nombre de QCMs, QCMs actifs, score moyen de ses étudiants, taux de réussite). Sélectionner une classe via dropdown → toutes les stats se filtrent. Voir les alertes (QCMs qui expirent bientôt, étudiants en difficulté). Voir la progression par compétence de la classe sélectionnée. |
| **Bibliothèque QCM** | `/formateur/bibliotheque` | Lister ses QCMs en cartes paginées. Rechercher par titre. Filtrer par statut, classe, unité d'apprentissage. Créer un nouveau QCM. Modifier un QCM existant. Changer le statut (brouillon → public → terminé). Dupliquer un QCM. Supprimer un QCM (avec confirmation). |
| **Créer / Éditer QCM** | `/formateur/qcm/create` ou `/formateur/qcm/{id}/edit` | Saisir le titre, le statut, la durée, le seuil de réussite. Choisir l'UA → les compétences se chargent dynamiquement. Choisir la classe cible. Ajouter des questions : choisir le type (choix_unique ou choix_multiple), saisir l'énoncé, attribuer les points. Pour chaque question, ajouter des options : si `choix_unique` → un seul bouton radio pour la réponse correcte ; si `choix_multiple` → des cases à cocher pour les réponses correctes. Saisir le feedback par option et par question. Équilibrer les points automatiquement (/20). Finaliser : validation que le total fait 20, que chaque question unique a exactement 1 correcte, que chaque question multiple a au moins 1 correcte. |
| **Résultats Cohorte** | `/formateur/resultats` | Sélectionner une classe. Voir la liste paginée des étudiants avec leur score moyen, taux de réussite, date de dernière tentative. Voir les résultats par QCM : distribution des scores, nombre de réussites/échecs. Rechercher un étudiant par nom. Filtrer par QCM. |
| **Profil** | `/profile` | Modifier son nom, email, mot de passe. |

### Workflow quotidien
```
Connexion
  → Dashboard (sélectionner une classe, voir alertes)
  → Bibliothèque QCM
      → Créer un QCM (éditeur SPA)
      → Modifier un QCM existant
      → Publier / Fermer un QCM
  → Résultats Cohorte (suivi des étudiants)
  → Mon profil
```

### Règles métier
- Le formateur ne voit que **ses** QCMs (filtrés par `formateur_id = auth()->id()`).
- Un QCM en statut `public` ne peut plus être modifié — seulement cloné ou fermé.
- Le type de question détermine le comportement de sélection :
  - `choix_unique` : exactement **1** option correcte (radio button).
  - `choix_multiple` : au moins **1** option correcte (checkboxes).
- Quand le type passe de `choix_multiple` → `choix_unique`, seule la première option correcte est conservée.
- Le total des points doit être **20** pour finaliser (avec possibilité d'équilibrer automatiquement).
- Le formateur ne peut pas créer de séances ou UAs — c'est le rôle de l'admin.
- Le formateur **peut** créer, modifier et supprimer des compétences.

---

## 3. Étudiant / Apprenant

### Identité
- **Rôle Spatie** : `etudiant`
- **type_profil** : `etudiant`
- **Accès** : Pages étudiant uniquement (+ profil)
- **Appartenance** : Toujours rattaché à une `classe_id`

### Pages & Actions

| Page | Route | Ce qu'il peut faire |
|------|-------|---------------------|
| **Dashboard** | `/student/dashboard` | Voir son greeting + nom de sa classe. Voir les QCMs en attente (non encore passés, avec date limite). Voir sa progression par compétence (barres de progression basées sur les tentatives passées). Voir son historique récent (5 dernières tentatives avec score + badge réussite/échec). |
| **Bibliothèque** | `/student/bibliotheque` | Lister les QCMs disponibles (statut `public` pour sa classe) en cartes paginées. Rechercher par titre. Filtrer par unité d'apprentissage, compétence. Voir pour chaque QCM : titre, UA, durée, nombre de questions, statut. Lancer un QCM via le bouton "Commencer". |
| **Passation QCM** | `/student/qcm/{id}` | Mode focus plein écran (pas de sidebar, pas de distractions). Minuteur défilant depuis `duree_minutes` : alerte rouge à 2 minutes restantes, soumission automatique à 0. Répondre aux questions : radio pour `choix_unique`, checkbox pour `choix_multiple`. Naviguer entre les questions (précédent / suivant). Voir la progression (ex. "3/10"). Soumettre ses réponses → le score est calculé → redirection vers les résultats. |
| **Mes Résultats** | `/student/resultats` | Lister toutes ses tentatives passées en cartes paginées. Rechercher par titre de QCM. Filtrer par statut (réussite/échec). Voir pour chaque tentative : titre du QCM, score (/20), badge réussite/échec, date. Cliquer sur une tentative → vue détaillée par question (bonne/mauvaise réponse, feedback). |
| **Profil** | `/profile` | Modifier son nom, email, mot de passe. |

### Workflow quotidien
```
Connexion
  → Dashboard (voir QCMs en attente, progression)
  → Bibliothèque (parcourir les QCMs disponibles)
      → Lancer un QCM (passation avec minuteur)
          → Soumettre → voir le score
  → Mes Résultats (historique des tentatives, détails par question)
  → Mon profil
```

### Règles métier
- L'étudiant ne voit que les QCMs `public` assignés à **sa** classe.
- Un étudiant ne peut passer un QCM **qu'une seule fois** (une seule tentative par QCM).
- Une tentative en cours ne peut pas être reprise après fermeture du navigateur (le minuteur continue côté serveur).
- Le score est calculé automatiquement à la soumission : somme des points des questions où la/les bonne(s) réponse(s) ont été sélectionnées.
- Si le minuteur atteint 0, la tentative est soumise automatiquement avec les réponses déjà saisies.
- L'étudiant ne peut pas modifier ses réponses après soumission.

---

## 4. Matrice des Droits

| Action | Admin | Formateur | Étudiant |
|--------|:-----:|:---------:|:--------:|
| Voir le dashboard global | ✅ | — | — |
| Voir son dashboard rôle-spécifique | ✅ | ✅ | ✅ |
| Créer / modifier / supprimer des utilisateurs | ✅ | — | — |
| Créer / modifier / supprimer des séances | ✅ | — | — |
| Créer / modifier / supprimer des UAs | ✅ | — | — |
| Créer / modifier / supprimer des compétences | ✅ | ✅ | — |
| Créer / modifier ses QCMs | — | ✅ | — |
| Voir tous les QCMs (lecture seule) | ✅ | — | — |
| Voir ses QCMs | — | ✅ | — |
| Voir les QCMs de sa classe | — | — | ✅ (publics) |
| Passer un QCM | — | — | ✅ |
| Voir les résultats de sa cohorte | — | ✅ | — |
| Voir ses propres résultats | — | — | ✅ |
| Fermer manuellement un QCM | ✅ | ✅ (ses QCMs) | — |
| Modifier son profil | ✅ | ✅ | ✅ |

---

## 5. Flux Transverses

### Création d'un QCM (Formateur)
```
1. Formateur clique "Nouveau QCM" dans la bibliothèque
2. L'éditeur SPA s'ouvre (pas de rechargement)
3. Il saisit le titre, choisit l'UA → les compétences se chargent
4. Il ajoute des questions, choisit le type, saisit les options
5. Il sélectionne les réponses correctes (radio ou checkbox selon le type)
6. Il équilibre les points pour atteindre 20
7. Il clique "Finaliser" → validation côté serveur
8. Succès → toast verte + redirection bibliothèque
9. Erreur → toast rouge + message d'erreur
```

### Passation d'un QCM (Étudiant)
```
1. L'étudiant voit un QCM en attente sur son dashboard ou dans la bibliothèque
2. Il clique "Commencer" → mode focus activé
3. Le minuteur démarre, les questions s'affichent une par une
4. Il sélectionne ses réponses (radio ou checkbox)
5. Il navigue entre les questions ou soumet directement
6. À la soumission (ou expiration du minuteur) :
   - Le score est calculé par PassationService
   - La tentative est enregistrée avec score_obtenu
   - L'étudiant est redirigé vers ses résultats
7. Le TentativeObserver vérifie si tous les étudiants ont passé le QCM → fermeture automatique
```

### Gestion d'un Utilisateur (Admin)
```
1. L'admin accède à /admin/users
2. Il recherche ou filtre la liste
3. Il clique "Ajouter" → modal de création
4. Il remplit le formulaire (type_profil détermine les champs visibles)
5. Si étudiant → champ classe apparaît
6. Si formateur → champ matricule apparaît
7. Soumission → validation → création → toast de confirmation
8. L'utilisateur reçoit le rôle Spatie correspondant à son type_profil
```
