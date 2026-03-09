# Branche Technique

## Choix Technologiques
Pour **SoliQuiz**, nous avons sélectionné une pile technologique moderne garantissant performance, sécurité et interopérabilité (Web & APK) :

### Backend
- **PHP 8.2+ & Laravel 12** : Framework MVC robuste pour une logique métier structurée.
- **Spatie Laravel Permission** : Gestion rigoureuse des rôles Administrateur, Formateur et Étudiant.
- **Native PHP** : Technologie utilisée pour porter l'écosystème PHP nativement sur mobile (APK).

### Frontend
- **Blade Templates** : Moteur de rendu natif de Laravel pour l'interface web.
- **Tailwind CSS & Preline** : Pour une interface moderne, accessible et **Mobile-First**.
- **Alpine.JS** : Pour les interactions dynamiques lors des quiz (Timer, auto-sauvegarde).

### Base de données
- **MySQL 8.0** : Stockage fiable des questions, résultats granulaires et utilisateurs.

### Outils Spécifiques
- **Tiptap** : Éditeur de texte riche pour la rédaction des questions complexes.
- **Vite** : Outil de build ultra-rapide utilisé pour compiler les assets front-end.

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

## Architecture Système

### Architecture MVC & 3-Tier
Laravel sépare naturellement les responsabilités :
1. **Couche Présentation** : Vues Blade (Web) et client APK Mobile.
2. **Couche Métier** : Logique de scoring par objectif et validation dans les contrôleurs.
3. **Couche Données** : Modèles Eloquent et persistance MySQL.

### Architecture Orientée API
Le système est conçu pour être "API-centric". L'application web et l'application mobile (APK) consomment les mêmes services REST. Cela garantit que le calcul du score et le feedback pédagogique sont cohérents, quel que soit le support de passation.

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```

## Prototype (Espaces et Classes)

Conformément à l'analyse de sécurité, tous les accès sont privés et protégés par authentification.

### 1. Espace Formateur & Administration
- **Gestion des QCM** : Création, modification et suppression de tests.
- **Granularité Pédagogique** : Liaison de chaque question à un micro-objectif.
- **Suivi Analytique** : Tableaux de bord de progression pour la classe et le centre.

### 2. Espace Étudiant (Web & APK)
- **Passation Sécurisée** : Sauvegarde en temps réel et gestion du temps (Timer).
- **Feedback Immédiat** : Consultation de la correction détaillée après validation.
- **Historique Personnel** : Suivi de sa propre montée en compétences par objectif.

### 3. Les classes principales du modèle
- **Utilisateur (Formateur / Etudiant)** : {id, nom, email, mot_de_passe}
- **QCM** : {id, objectif_id, formateur_id, titre, duree_minutes, score_reussite}
- **Question** : {id, qcm_id, texte, type[unique/multiple], points, explication_feedback}
- **Option (Choix)** : {id, question_id, texte, est_correcte, feedback_specifique}
- **Tentative (Passage)** : {id, etudiant_id, qcm_id, score_obtenu, statut, date_fin}
- **Reponse & ChoixReponse** : Capture exacte des réponses cochées par l'étudiant pour chaque question.

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```