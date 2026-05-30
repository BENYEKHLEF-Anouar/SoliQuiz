# Wireframe : Espace Formateur (Tableau de Bord)

## ZONE 1 : HEADER (Navigation Formateur)
- **Composant** : Logo
- **Contenu** : "SoliQuiz Formateur"
- **Action** : Vers [Accueil Formateur]
- **Composant** : Menu (Nav)
- **Contenu** : "Mes Classes | Mes QCM | Éditeur"
- **Action** : Vers Modules internes
- **Composant** : Bouton (Création Rapide)
- **Contenu** : "+ Nouveau QCM"
- **Action** : Vers [Création QCM]
- **Composant** : Avatar Profil / Déconnexion
- **Contenu** : "Initiales Formateur + Bouton Quitter"
- **Action** : Vers [Auth]

## ZONE 2 : HERO (Vue Globale)
- **Composant** : Titre (H1)
- **Contenu** : "Supervision de vos cohortes : Développeurs Web 101"
- **Action** : Menu déroulant pour changer de cohorte
- **Composant** : KPI Global
- **Contenu** : "Moyenne Cohorte : 14/20 | 85% de participation"
- **Action** : Aucune

## ZONE 3 : SECTION (Évaluations / Alertes)
- **Composant** : Titre (H2)
- **Contenu** : "Évaluations récentes"
- **Action** : Vers "Historique complet"
- **Composant** : Module d'alerte
- **Contenu** : "25% d'échec sur le QCM 'Concepts Orientés Objet'"
- **Action** : Vers l'Analyse détaillée du QCM
- **Composant** : Bouton d'action
- **Contenu** : "Créer une nouvelle évaluation (pour cibler la lacune)"
- **Action** : Vers [Création QCM]

## ZONE 4 : SECTION (Taux de réussite par objectif)
- **Composant** : Titre (H2)
- **Contenu** : "Taux de réussite par objectif pédagogique"
- **Action** : Aucune
- **Composant** : Tableau de bord (Progress Bars par compétence)
- **Contenu** : "Héritage Java: 40% | Polymorphisme: 55% | Syntaxe: 90%"
- **Action** : Cliquer pour voir les élèves en difficulté
