# Charte Graphique Mobile - SoliQuiz

**Concept Visuel Mobile** : Compact, ergonomique, réactif.
L'expérience mobile est conçue pour la passation de QCM en déplacement, avec des cibles tactiles optimisées et une interface épurée pour favoriser la concentration sur de petits écrans.

## 1. Couleurs (Synchronisées Desktop)

### Primaire : Ocean Teal
- Nom Tailwind : `primary`
- Base (500) : `hsl(190, 80%, 45%)`
- Utilisation : Actions principales, barres de progression, indicateurs actifs.

### Sémantique :
- **Success** : `hsl(140, 65%, 45%)` (Validations, scores élevés)
- **Error** : `hsl(0, 75%, 55%)` (Déconnexion, réponses fausses)
- **Warning** : `hsl(35, 95%, 55%)` (Urgence timer, alertes)
- **Info** : `hsl(220, 80%, 55%)` (Notifications)

## 2. Typographie
- **Titres** : *Outfit* (Configuré pour des tailles d'écran réduites)
- **Corps** : *Inter* (Lisibilité maximale à 14px/16px)

## 3. Ergonomie Mobile (Touch First)
- **Cibles Tactiles** : 44x44px minimum pour tous les éléments interactifs.
- **Espacement** : Utilisation de `p-5` et `px-5` pour maintenir des marges de sécurité sur les bords des smartphones.
- **Navigation** : Bottom Navigation Bar pour un accès facile au pouce.
- **Feedbacks** : États `:active` (scale 0.98 ou changement de background) pour confirmer l'interaction tactile.

## 4. Composants Mobile UI
- **Bottom Sheets** : Panneaux glissants du bas pour les notifications et menus secondaires.
- **Safe Areas** : Gestion des encoches (notches) via `pb-safe` et `pt-safe`.
- **Cartes** : Coins arrondis `rounded-2xl` pour une esthétique moderne et douce.
