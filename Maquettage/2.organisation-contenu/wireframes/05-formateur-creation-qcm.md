# Wireframe : Création / Édition QCM (Formateur)

## ZONE 1 : HEADER (Éditeur)
- **Composant** : Fil d'Ariane
- **Contenu** : "Accueil > Mes QCM > Nouveau"
- **Action** : Vers [Dashboard] ou [Liste QCM]
- **Composant** : Titre (H1)
- **Contenu** : "Concevoir une nouvelle évaluation"
- **Action** : Éditable directement
- **Composant** : Bouton (Save)
- **Contenu** : "Enregistrer le QCM en brouillon"
- **Action** : Sauvegarde BDD

## ZONE 2 : PANNEAU LATÉRAL GAUCHE (Paramètres QCM)
- **Composant** : Titre (H2)
- **Contenu** : "Informations générales"
- **Action** : Aucune
- **Composant** : Sélecteur (Dropdown)
- **Contenu** : "Cohorte ciblée : DW_101"
- **Action** : Sélection Multiple
- **Composant** : Interrupteur (Toggle)
- **Contenu** : "Activer le chronomètre : 20 min"
- **Action** : Activation Timer

## ZONE 3 : CORPS PRINCIPAL (Éditeur de Questions)
- **Composant** : Bloc Question N°1 (Carte focus)
- **Contenu** : "Saisie de la consigne"
- **Action** : Saisie Textarea
- **Composant** : Sous-titre (H3)
- **Contenu** : "Paramètres de la question"
- **Action** : Aucune
- **Composant** : Options (Choix Unique / Multiple)
- **Contenu** : "Sélecteur du type de réponse"
- **Action** : Modification typologie input
- **Composant** : Lignes de réponses (Inputs x4)
- **Contenu** : "Texte réponse + Checkbox 'Est la bonne réponse'"
- **Action** : Saisie + Sélection
- **Composant** : Champ Feedback (Info bulle)
- **Contenu** : "Explication affichée à l'étudiant à la fin"
- **Action** : Saisie Textarea
- **Composant** : Sélecteur Objectif Pédagogique
- **Contenu** : "Lier à l'objectif : 'Comprendre l'héritage'"
- **Action** : SaisieAutocomplete

## ZONE 4 : FOOTER ÉDITEUR (Validation Globale)
- **Composant** : Bouton Primaire
- **Contenu** : "Enregistrer et assigner à la classe"
- **Action** : Déploiement du test
- **Composant** : Bouton Secondaire
- **Contenu** : "+ Ajouter une question"
- **Action** : Nouveau bloc Question +1
