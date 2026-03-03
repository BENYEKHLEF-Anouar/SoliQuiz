# Capacité : Analyse de Demande Design

## Objectif
Analyser la demande utilisateur et l'état actuel du projet pour déterminer les actions de design nécessaires (Charte, Composition, Composants, Mockup).

## Algorithme de Décision

### 1. Extraction des Paramètres
- **Type de demande** : Création de page, Ajout de composant, Modification de style.
- **Cible** : Nom de la page ($PAGE) ou Nom du composant ($COMPOSANT).

### 2. Matrice de Vérification (État du Projet)
| Livrable             | Chemin Relatif                | Condition de Nécessité            |
| :------------------- | :---------------------------- | :-------------------------------- |
| **Charte Graphique** | `charte-graphique/index.html` | Toujours si absente               |
| **Composition**      | `composition/comp-$PAGE.md`   | Si création de page               |
| **Manifeste**        | `components-lib/manifest.md`  | Toujours pour vérifier l'existant |
| **Mockup**           | `mockups/$PAGE.html`          | Si création/modification de page  |

### 3. Logique de Cascade (Workflow Intelligence)

#### CAS A : Création d'une nouvelle page
1. **SI** charte absente → Planifier `Action B` (Créer Charte).
2. **SI** composition absente → Planifier `Action A` (Analyser Wireframe).
3. **LIRE** composition pour identifier les composants manquants.
4. **POUR CHAQUE** composant manquant :
   - Déterminer type (Atom/Molécule).
   - Planifier `Action C` (Atom) ou `Action D` (Molécule).
5. **SI** mockup absent → Planifier `Action F` (Créer Mockup).

#### CAS B : Ajout d'un composant
1. **VÉRIFIER** `manifest.md`.
2. **SI** existe → Proposer la réutilisation.
3. **SI** absent :
   - Déterminer type (Atom/Molécule).
   - Planifier `Action C` ou `Action D`.
   - Mettre à jour `comp-$PAGE.md`.
   - Planifier `Action F` (Regénérer Mockup).

#### CAS C : Modification Design
1. **PLANIFIER** `Action F` (Regénérer Mockup avec nouveaux styles).

## Format de Sortie (Décision)
La capacité doit retourner un plan d'action clair :
- `✅ Skip` : Si tout est déjà présent et à jour.
- `Séquence : [Liste des Actions]` : Liste ordonnée des actions à exécuter.
- `Composants à créer : [Liste]` : Liste des nouveaux composants identifiés.

## Interdictions
- Ne jamais sauter l'étape de vérification du Manifeste.
- Ne pas planifier de Mockup si la Composition est manquante.
- Ne pas planifier d'Atoms/Molécules si la Charte (tokens CSS) n'existe pas.
