# Conception

## Diagramme de classes
Le diagramme de classes modélise l'architecture relationnelle du système et garantit l'intégrité de la base de données au travers de plusieurs modules :
- **Sécurité et Utilisateurs** : `Utilisateur` (hérité par `Formateur` et `Etudiant`), `Role`, `Permission` (modèle RBAC).
- **Structure Pédagogique** : `UniteApprentissage`, `Session`, `Objectif` (permettant la granularité et les scores par compétence).
- **Moteur d'Évaluation** : `QCM`, `Question`, `Option` (gestion du contenu des questionnaires).
- **Soumissions et Résultats** : `Tentative`, `Reponse`, `ChoixReponse` (capture sécurisée des choix et calcul des scores finaux).

![Diagramme de classes](images/diagramme-classes.png)

## Maquettes (UI/UX)
Les interfaces ont été conçues pour être "mobile-first", offrant une navigation fluide entre la sélection des quiz et le passage des tests.

```{=openxml}
<w:p><w:r><w:br w:type="page"/></w:r></w:p>
```