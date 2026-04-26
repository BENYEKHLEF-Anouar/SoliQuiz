---
trigger: always_on
type: rule
id: visual-identity
---

# 🎨 SOLIQUIZ VISUAL IDENTITY

## Brand Colors

| Token | Hex | Usage |
|-------|-----|-------|
| `primary-600` | `#4F46E5` | Buttons, links, active states |
| `primary-700` | `#4338CA` | Hover states |
| `success-500` | `#10B981` | Success scores ≥ 70% |
| `warning-500` | `#F59E0B` | Warning scores 50-69% |
| `danger-500` | `#EF4444` | Danger scores < 50% |
| `slate-900` | `#0F172A` | Admin navbar background |
| `white` | `#FFFFFF` | Formateur/Student navbar |

## Typography

- **Font Family:** Plus Jakarta Sans.
- **Headings:** `font-bold tracking-tight`.
- **Body:** `text-slate-600 dark:text-slate-400`.
- **Monospace:** `font-mono` for scores, IDs, dates.

## Score Display Convention

```html
<!-- Success (≥ 70%) -->
<span class="text-emerald-500 font-bold">85%</span>

<!-- Warning (50-69%) -->
<span class="text-amber-500 font-bold">62%</span>

<!-- Danger (< 50%) -->
<span class="text-rose-500 font-bold">35%</span>
```

## Component Patterns

### Cards
```html
<div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
```

### Buttons
```html
<!-- Primary -->
<button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">

<!-- Secondary -->
<button class="bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 px-4 py-2 rounded-lg">

<!-- Danger -->
<button class="bg-rose-500 hover:bg-rose-600 text-white px-4 py-2 rounded-lg">
```

### Status Badges
```html
<span class="px-2 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">Publié</span>
<span class="px-2 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-700">Brouillon</span>
<span class="px-2 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-700">Archivé</span>
```

## Responsive Breakpoints
- Mobile: < 640px (`sm:`)
- Tablet: 640px - 1024px (`md:`, `lg:`)
- Desktop: > 1024px (`xl:`)
