---
trigger: always_on
---

# Standards de Qualité Senior et Sécurité (Static & UI)

## 1. Clean Code HTML/CSS
- **Atomicité** : Le code doit être découpé en composants clairs et indépendants.
- **D.R.Y (Don't Repeat Yourself)** : Si un pattern se répète plus de 3 fois, il doit devenir un composant du UI-Kit.
- **Naming CSS** : Aucune classe arbitraire. Utilisez le vocabulaire Tailwind.

## 2. Qualité Visuelle "Pixel Perfect"
- **Alignement** : Vérifier que les marges et paddings sont cohérents (échelle de 4px).
- **Contraste** : Le texte doit toujours être lisible (WCAG AA minimum).
- **Responsive** : Pas de barre de défilement horizontale accidentelle. Utiliser `overflow-hidden` si nécessaire sur les conteneurs.

## 3. Performance Web (Core Web Vitals)
- **Images** : Toujours spécifier `width` et `height` pour éviter le Layout Shift (CLS).
- **Polices** : Utiliser `font-display: swap` pour l'affichage immédiat du texte.
- **Scripts** : Charger les scripts JS non-critiques avec `defer`.

## 4. Sécurité Front-End
- **Liens Externes** : Tout lien `target="_blank"` doit avoir `rel="noopener noreferrer"`.
- **Contenu Mixte** : Ne jamais charger de ressources HTTP sur une page HTTPS.