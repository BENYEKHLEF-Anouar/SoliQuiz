# Charte Graphique - SoliQuiz UI

**Concept Visuel** : Apaisant, pédagogique, clair, professionnel.
L'objectif est d'éliminer le stress de l'évaluation avec des teintes dominantes bleu cyan (Digital Ocean) et un gris doux (Slate) pour minimiser la fatigue visuelle.

---

## 1. Couleurs

### Primaire : Digital Ocean Blue (#17B0CF)
```
primary-50:  #f0f9ff (hsl(195, 100%, 97%))
primary-100: #e0f2fe (hsl(195, 100%, 93%))
primary-200: #bae6fd (hsl(195, 100%, 86%))
primary-300: #7dd3fc (hsl(195, 100%, 74%))
primary-400: #38bdf8 (hsl(195, 100%, 60%))
primary-500: #17B0CF (hsl(191, 81%, 46%))  // Base - Digital Ocean
primary-600: #0ea5e9 (hsl(195, 90%, 50%))
primary-700: #0284c7 (hsl(195, 95%, 40%))
primary-800: #0369a1 (hsl(195, 95%, 32%))
primary-900: #0c4a6e (hsl(195, 95%, 24%))
```

### Neutre : Slate
```
slate-50:  #f8fafc  // Background principal
slate-100: #f1f5f9  // Bordures légères
slate-200: #e2e8f0  // Bordures
slate-300: #cbd5e1  // Texte désactivé
slate-400: #94a3b8  // Texte secondaire
slate-500: #64748b  // Texte tertiaire
slate-600: #475569  // Texte corps
slate-700: #334155  // Texte important
slate-800: #1e293b  // Texte titres
slate-900: #0f172a  // Texte principal
```

### Sémantique
```
Success:  #10b981 (hsl(160, 65%, 45%)) - emerald-500
Error:    #f43f5e (hsl(348, 75%, 55%)) - rose-500
Warning:  #f59e0b (hsl(38, 95%, 55%))  - amber-500
Info:     #17B0CF (hsl(191, 81%, 46%)) - primary-500
```

---

## 2. Typographie

### Polices
- **Titres (Headings)**: *Plus Jakarta Sans* (Moderne, géométrique, lisible)
- **Corps de texte (Body)**: *Plus Jakarta Sans* (Ergonomie de lecture)

### Échelle Typographique
```
text-[8px]:   Labels micro, tags
text-[9px]:   Labels uppercase tracking-widest
text-[10px]:  Labels, badges, tracking-[0.2em]
text-[11px]:  Navigation, boutons uppercase
text-xs:      Corps secondaire (12px)
text-sm:      Corps standard (14px)
text-base:    Corps principal (16px)
text-lg:      Sous-titres (18px)
text-xl:      Titres section (20px)
text-2xl:     Titres cards (24px)
text-3xl:     Titres pages (30px)
text-4xl:     Hero (36px)
text-5xl:     Hero large (48px)
```

### Styles de Texte
```
// Labels
`text-[10px] font-black uppercase tracking-[0.2em] italic`

// Titres Cards
`text-xl font-bold text-slate-900`

// Valeurs KPI
`text-2xl font-black text-slate-900`
```

---

## 3. Composants

### Bordures & Arrondis
```
rounded-lg:   0.5rem  (8px)  - Boutons small, tags
rounded-xl:   0.75rem (12px) - Inputs, cards elements
rounded-2xl:  1rem    (16px) - Cards, modals
rounded-3xl:  1.5rem  (24px) - Large cards, containers
rounded-[2rem]: 32px - Modals premium
```

### Ombres
```
shadow-sm:    Ombre légère pour cards
shadow-md:    Ombre medium pour hover states
shadow-lg:    Ombre pour dropdowns, modals
shadow-xl:    Ombre premium pour modals
shadow-premium: shadow-2xl avec faible opacité
```

### Espacement
```
Les espacements suivent une échelle cohérente:
- Cards padding: p-4 à p-6
- Section gaps: gap-4 à gap-6
- Component margins: space-y-4 à space-y-8
```

---

## 4. Accessibilité (WCAG)

- Contraste validé sur tous les boutons `bg-primary-500` avec texte `text-white`
- Le texte secondaire utilise `text-slate-500` minimum sur `bg-slate-50`
- Focus states visibles avec `ring-4 ring-primary-500/10`
- Transitions douces de `150ms` ou `200ms`

---

## 5. Patterns UI Récurrents

### Cards
```html
<div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
  <!-- Content -->
</div>
```

### KPI Cards
```html
<div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
  <div class="flex items-center gap-2 mb-3">
    <div class="size-9 bg-primary-100 rounded-lg flex items-center justify-center text-primary-600">
      <!-- Icon -->
    </div>
    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Label</span>
  </div>
  <div class="flex items-baseline gap-2">
    <span class="text-2xl font-black text-slate-900">Value</span>
  </div>
</div>
```

### Boutons Primaires
```html
<button class="px-6 py-3 bg-[#17B0CF] text-white text-[11px] font-black uppercase tracking-widest rounded-2xl hover:bg-[#14a0bd] transition-all shadow-lg shadow-[#17B0CF]/20">
  Action
</button>
```

### Inputs
```html
<input class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:bg-white focus:ring-4 focus:ring-[#17B0CF]/10 focus:border-[#17B0CF] outline-none transition-all">
```

---

## 6. Icônes

Toutes les icônes utilisent des SVG inline avec:
- `stroke="currentColor"` pour la couleur dynamique
- `stroke-width="2"` ou `"2.5"` selon le contexte
- `fill="none"` pour les icônes line-style
