# Wireframe : Passation du QCM (Apprenant)

## ZONE 1 : HEADER (Test Focus Mode)
- **Composant** : Titre du QCM (H1)
- **Contenu** : "Évaluation : Bases du PHP"
- **Action** : Aucune
- **Composant** : Chronomètre (Timer)
- **Contenu** : "Temps restant : 14:59"
- **Action** : Compte à rebours
- **Composant** : Progression
- **Contenu** : "Question 3 sur 10"
- **Action** : Aucune

## ZONE 2 : BODY (Question Active)
- **Composant** : Texte (Consigne/Question)
- **Contenu** : "Quelle est la fonction pour afficher du texte en PHP ?"
- **Action** : Aucune
- **Composant** : Liste Checkbox / Radio (Choix de réponse)
- **Contenu** : "1) echo() | 2) print() | 3) alert() | 4) display()"
- **Action** : Saisie (Auto-sauvegarde à la sélection)

## ZONE 3 : FOOTER COLLANT (Navigation)
- **Composant** : Indicateur d'état (Texte discret)
- **Contenu** : "Sauvegardé à 10:45 ✓"
- **Action** : Aucune
- **Composant** : Bouton Précédent
- **Contenu** : "Question précédente"
- **Action** : Retour question -1
- **Composant** : Bouton Suivant (Actif quand réponse choisie)
- **Contenu** : "Question suivante / Valider mes réponses (si dernière)"
- **Action** : Passage question +1 / Validation finale
