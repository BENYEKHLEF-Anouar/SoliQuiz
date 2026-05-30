# Cahier des Charges — SoliQuiz

> **Projet :** Système de Gestion & d'Auto-évaluation QCM  
> **Client :** Centre de formation Solicode (Bootcamp)

---

## Vision et Objectifs du Projet

**SoliQuiz** est une application web et mobile (Android/APK) conçue pour digitaliser l'évaluation quotidienne au sein de Solicode. L'objectif est de remplacer les solutions génériques disparates par une plateforme unique capable de :
- **Automatiser** le cycle complet des QCM (création, passation, correction).
- **Tracer** l'acquisition des compétences via une granularité par micro-objectif.
- **Éliminer** la double saisie administrative via une intégration avec SoliLMS.
- **Améliorer** l'engagement des apprenants grâce à un feedback immédiat et une interface sans stress.

---

## Contexte et Problématique

L'analyse de terrain (entretiens d'empathie) a révélé une **fragmentation critique des outils** (Google Forms, SoliLMS, Excel). Cette situation génère trois douleurs majeures :

1.  **Opacité Pédagogique (Formateurs/Étudiants) :** Les résultats fournis sous forme de "scores bruts" (ex: 12/20) ne permettent pas de savoir quels micro-objectifs sont échoués. Soufiane (étudiant) souligne que cela empêche toute révision ciblée.
2.  **Lourdeur Administrative (Formateurs/Admin) :** Le report manuel des notes de Google Forms vers SoliLMS est chronophage (Youssef) et source d'erreurs de saisie frustrantes pour les étudiants (Fatine).
3.  **Friction Technique (Étudiants) :** L'absence d'interface mobile fluide et de sauvegarde automatique génère une angoisse de perte de données chez Mehdi, exacerbée par des clôtures de tests brusques sans timer.

> **How Might We (Comment pourrions-nous) :** Offrir aux acteurs de Solicode un outil d'évaluation QCM intégré, qui automatise la correction, structure les résultats par objectif pédagogique et fournit un feedback immédiat aux apprenants — éliminant ainsi la double saisie et l'opacité des résultats ?

---

## Profils Utilisateurs (Personas)

| Profil | Rôle | Besoins Clés |
|---|---|---|
| **L'Étudiant** (Mehdi / Soufiane) | Passer les tests et apprendre | Mobile-first, auto-sauvegarde, timer, feedback explicatif immédiat, tableau de bord de progression. |
| **Le Formateur** (Youssef / Fatine) | Évaluer et ajuster son cours | CRUD QCM rapide, liaison par objectifs, calcul auto des scores, analyse granulaire de la classe. |
| **L'Administrateur** (Fouad) | Superviser et synchroniser | Supervision globale du centre, gestion des accès (Security-first), synchronisation API avec SoliLMS. |

---

## Spécifications Fonctionnelles (Agilité)

### Sprint 1 : MVP (Minimum Viable Product)
*   **Sécurité Basique :** Authentification sécurisée avec redirection selon le rôle (Admin, Formateur, Étudiant).
*   **Moteur QCM (CRUD) :** Création de QCM, gestion des questions (choix unique/multiple) et définition des bonnes réponses.
*   **Cycle de Passation :** Interface de test pour l'étudiant avec correction et calcul automatique du score global.
*   **APK Mobile (V1) :** Consultation des scores et des QCM en lecture seule pour les étudiants et formateurs.

### Sprint 2 : Version Avancée & Analytique
*   **Granularité par Objectif :** Liaison des QCM à des sessions/micro-objectifs. Calcul des scores ventilé par compétence.
*   **Feedback Pédagogique :** Affichage de la correction détaillée avec explications justifiées après validation.
*   **Expérience "Stress-Free" :** Implémentation du Timer (compte à rebours) et de la sauvegarde automatique en temps réel.
*   **Pilotage et Intégration :** Dashboard global de supervision administrative et export automatisé vers SoliLMS via API.

---

## Exigences Non-Fonctionnelles (Qualité)

-   **Performance :** Chargement instantané des pages et faible consommation de données pour l'APK.
-   **Disponibilité :** Mode "dégradé" permettant la poursuite du test en cas de micro-coupure internet (local storage).
-   **Ergonomie :** Différenciation visuelle stricte (Design System) entre boutons radio (choix unique) et checkboxes (choix multiples).
-   **Accessibilité :** Lisibilité irréprochable sur smartphone (Mobile-First).

---

## Critères d'Acceptation (DoD)

1.  Le formateur peut créer un QCM fonctionnel en moins de 3 minutes.
2.  L'étudiant peut achever son test sur smartphone même après une perte de connexion.
3.  L'export des notes vers SoliLMS s'effectue sans aucune saisie manuelle.
4.  Chaque test génère un feedback compréhensible permettant à l'apprenant d'identifier ses lacunes.

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```