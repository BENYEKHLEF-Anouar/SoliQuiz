# Capacité : Génération de Galerie UI

## Objectif
Générer et maintenir un hub central (`index.html`) dans le dossier `3.maquettage/` permettant de naviguer visuellement entre tous les éléments produits par le workflow de design.

## Template de Référence
Le fichier généré doit se baser sur le fichier template suivant :
- **Fichier** : `templates/galerie-ui.template.html`

### Directives de Remplacement
Lors de la génération, l'IA doit remplacer les placeholders suivants dans le template :
- `[Nom du Projet]` : Le nom du projet extrait du cahier des charges.
- `<!-- Sections injectées dynamiquement ici -->` : Le code HTML de navigation généré.

## Règles de Mise à Jour Dynamique
L'IA doit mettre à jour les sections suivantes de la navigation en fonction de l'existence des dossiers :

1. **Charte Graphique** : Pointer vers `charte-graphique/index.html`.
2. **Atoms** : Créer un bouton pour chaque sous-dossier dans `components-lib/atoms/` pointant vers son `index.html`.
3. **Molecules** : Créer un bouton pour chaque sous-dossier dans `components-lib/molecules/` pointant vers son `index.html`.
4. **Mockups** : Créer un bouton pour chaque fichier `.html` dans `mockups/`.

## Instructions Techniques
- **Réadaptation des chemins** : Assurez-vous que les chemins dans `loadComponent` sont relatifs au fichier `index.html` racine du dossier `3.maquettage/`.
- **Preline & Tailwind** : Toujours inclure les CDN pour garantir que la galerie s'affiche même sans build local.
