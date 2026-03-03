# Wireframe : Espace Administrateur (Supervision Globale)

## ZONE 1 : HEADER (Navigation Admin)
- **Composant** : Logo Admin
- **Contenu** : "SoliQuiz Centre d'Administration"
- **Action** : Vers [Dashboard Admin]
- **Composant** : Menu (Nav Rapide)
- **Contenu** : "Vue Centre | Accès | SoliLMS Sync"
- **Action** : Vers Modules
- **Composant** : Avatar
- **Contenu** : "SuperAdmin Fouad"
- **Action** : Vers [Auth]

## ZONE 2 : HERO (Aperçu Santé du Centre)
- **Composant** : Titre (H1)
- **Contenu** : "Centre de contrôle Solicode"
- **Action** : Aucune
- **Composant** : Cartes KPI Macro (x3)
- **Contenu** : "12 Tests Actifs | 350 Étudiants | SoliLMS API : CONNECTÉ (Pastille Verte)"
- **Action** : Aucune

## ZONE 3 : SECTION (Monitoring API & Systèmes)
- **Composant** : Titre (H2)
- **Contenu** : "État des synchronisations API"
- **Action** : Aucune
- **Composant** : Tableau de log (Derniers exports vers SoliLMS)
- **Contenu** : "Date | Test ID | Étudiants sync | Statut (Succès 100%)"
- **Action** : Vers Détails Log
- **Composant** : Bouton de secours (H3)
- **Contenu** : "Forcer la synchronisation manuelle"
- **Action** : Appel API immédiat "Voir les logs API"

## ZONE 4 : SECTION (Gestion des cohortes et succès)
- **Composant** : Titre (H2)
- **Contenu** : "Performances globales par cohorte"
- **Action** : Aucune
- **Composant** : Graphique d'ensemble (Bar Chart statique)
- **Contenu** : "Comparaison des taux de réussite Web vs Mobile"
- **Action** : Filtrage par mois

## ZONE 5 : SECTION (Outils Rapides)
- **Composant** : Titre (H2)
- **Contenu** : "Gestion des accès utilisateurs"
- **Action** : Aucune
- **Composant** : Formulaire Rapide
- **Contenu** : "Recherche Rapide Utilisateur par Matricule ou Email"
- **Action** : Vers Profil Utilisateur (Édition droits Formateur/Apprenant)
