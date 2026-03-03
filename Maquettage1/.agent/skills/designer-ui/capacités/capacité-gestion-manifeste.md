# Capacité : Gestion des Manifestes

## Objectif
Maintenir des registres précis par catégorie pour éviter les doublons et faciliter la réutilisabilité des composants et des pages.

## Principe
Le système utilise **trois manifestes distincts** pour segmenter la bibliothèque :
1.  **Atoms** : `3.maquettage/components-lib/atoms/atoms-manifest.md`
2.  **Molécules** : `3.maquettage/components-lib/molecules/molecules-manifest.md`
3.  **Mockups** : `3.maquettage/mockups/mockups-manifest.md`

## Structure des Manifestes

### 1. Manifeste des Atoms (atoms/atoms-manifest.md)
*Registre des éléments unitaires.*
- **Champs** : Nom, Emplacement, Variants CSS, Pages d'utilisation.

### 2. Manifeste des Molécules (molecules/molecules-manifest.md)
*Registre des groupes de composants.*
- **Champs** : Nom, Emplacement, Atoms composants (dépendances), Pages d'utilisation.

### 3. Manifeste des Mockups (mockups/mockups-manifest.md)
*Registre des pages complètes.*
- **Champs** : Nom de la page, Chemin du fichier, Wireframe de référence, Statut (Finalisé/En cours).

## Règles d'Application (Algorithme)

### Étape 1 : Identification du Type
- Si l'élément est un **Atom** → Utiliser `components-lib/atoms/atoms-manifest.md`
- Si l'élément est une **Molécule** → Utiliser `components-lib/molecules/molecules-manifest.md`
- Si l'élément est un **Mockup** → Utiliser `mockups/mockups-manifest.md`

### Étape 2 : Vérification (Avant Création)
1. **Ouvrir** le manifeste correspondant au type identifié.
   - **Note** : Si le fichier n'existe pas, cela signifie qu'aucun composant n'a été créé.
2. **Rechercher** si le nom ou une fonction similaire existe.
3. **Décider** :
   - Si existe → Stopper et réutiliser.
   - Si absent → Procéder à la création.

### Étape 3 : Documentation (Après Création)
1. **Ajouter** une entrée structurée dans le manifeste concerné.
2. **Vérifier** que les chemins relatifs vers les fichiers sont corrects.

## Interdictions
- **NE JAMAIS** mélanger des molécules dans le manifeste des atoms.
- **NE JAMAIS** créer un mockup (page) sans l'enregistrer dans `mockups/mockups-manifest.md`.
