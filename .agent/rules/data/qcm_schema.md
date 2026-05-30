---
trigger: on_demand
type: rule
id: qcm-schema
---

# 🗄️ SOLIQUIZ DATABASE SCHEMA RULES

## Entity Relationships

```
Seance (1) ───► (N) UniteApprentissage (UA)
UA (1) ───► (N) Competence
Competence (1) ───► (N) QCM
QCM (1) ───► (N) Question
Question (1) ───► (N) Option

Classe (1) ───► (N) User (etudiants)
Classe (N) ───► (1) User (formateur)

User (1) ───► (N) Tentative
Tentative (1) ───► (N) Reponse
Reponse (N) ───► (N) Option (choix)
```

## Key Constraints
- **QCM:** `score_reussite` stored as INTEGER percentage (0-100).
- **Tentative:** `score_obtenu` calculated as INTEGER percentage (0-100).
- **Option:** `est_correcte` BOOLEAN, `feedback` TEXT nullable.
- **Question:** `type` ENUM('unique', 'multiple'), `points` INTEGER default 1.
- **User:** `type_profil` ENUM('admin', 'formateur', 'etudiant').

## Migration Order
1. `users` (modified with type_profil)
2. `classes`
3. `seances`
4. `unite_apprentissages`
5. `competences`
6. `qcms`
7. `questions`
8. `options`
9. `tentatives`
10. `reponses`
11. `option_reponse` (pivot)

## Eloquent Patterns
- Use `BelongsTo`, `HasMany` relationships explicitly.
- Scopes: `scopePublie()`, `scopeBrouillon()`, `scopeEnCours()`.
- Accessors: `getStatutAttribute()`, `getScoreDisplayAttribute()`.
- Cast: `score_reussite` → integer, `est_publie` → boolean.
