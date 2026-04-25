# Manifeste des Mockups - SoliQuiz UI

Ce document répertorie l'ensemble des mockups statiques synchronisés avec le projet Laravel SoliQuiz. 
**Toutes les pages utilisent désormais la charte "Luminous" (Plus Jakarta Sans, Ocean Teal HSL, Glassmorphism).**

## Identité Visuelle Standardisée
- **Couleur Primaire**: `hsl(190, 80%, 45%)` (Ocean Teal)
- **Typographie**: Plus Jakarta Sans (800+ pour titres, 500 pour corps)
- **Style**: Premium Luminous (Glassmorphism, Shadow-premium, Mesh Background, Italic Uppercase)

## Liste des Mockups (Finalisés 1:1)

| Module | Page | Fichier HTML | Source Blade |
|--------|------|--------------|--------------|
| **Public** | Landing Page | [14-public-landing.html](14-public-landing.html) | `welcome.blade.php` |
| **Auth** | Connexion | [01-auth-login.html](01-auth-login.html) | `auth/login.blade.php` |
| **Auth** | Inscription | [08-register.html](08-register.html) | `auth/register.blade.php` |
| **Auth** | Récupération MDP | [16-auth-password-email.html](16-auth-password-email.html) | `auth/passwords/email.blade.php` |
| **Student** | Dashboard | [02-student-dashboard.html](02-student-dashboard.html) | `student/dashboard.blade.php` |
| **Student** | Bibliothèque QCM | [bibliotheque.html](bibliotheque.html) | `student/bibliotheque.blade.php` |
| **Student** | Passation QCM | [15-qcm-passation.html](15-qcm-passation.html) | `student/passation.blade.php` |
| **Student** | Résultats | [16-qcm-resultats.html](16-qcm-resultats.html) | `student/resultats.blade.php` |
| **Formateur** | Dashboard | [03-formateur-dashboard.html](03-formateur-dashboard.html) | `formateur/dashboard.blade.php` |
| **Formateur** | Bibliothèque (Vue Tabulaire) | [03-formateur-bibliotheque.html](03-formateur-bibliotheque.html) | `formateur/bibliotheque.blade.php` |
| **Formateur** | Bibliothèque (Vue Grille) | [17-formateur-qcms.html](17-formateur-qcms.html) | `formateur/bibliotheque.blade.php` |
| **Formateur** | Création QCM (Studio) | [09-formateur-creation-qcm.html](09-formateur-creation-qcm.html) | `formateur/creation-qcm.blade.php` |
| **Formateur** | Édition QCM | [18-qcm-edit.html](18-qcm-edit.html) | `formateur/edit-qcm.blade.php` |
| **Formateur** | Résultats Cohorte | [20-formateur-resultats.html](20-formateur-resultats.html) | `formateur/resultats-cohorte.blade.php` |
| **Formateur** | Suivi Pédagogique | [21-formateur-pedagogie.html](21-formateur-pedagogie.html) | `formateur/pedagogie.blade.php` |
| **Admin** | Dashboard | [06-admin-dashboard.html](06-admin-dashboard.html) | `admin/dashboard.blade.php` |
| **Admin** | Gestion Classes | [12-admin-classes.html](12-admin-classes.html) | `admin/classes.blade.php` |
| **Admin** | Détails Cohorte | [15-admin-classes-show.html](15-admin-classes-show.html) | `admin/classes-show.blade.php` |
| **Admin** | Gestion Utilisateurs | [13-admin-gestion-utilisateurs.html](13-admin-gestion-utilisateurs.html) | `admin/gestion-utilisateurs.blade.php` |
| **Admin** | Banque QCM Globale | [17-admin-qcms.html](17-admin-qcms.html) | `admin/qcms.blade.php` |
| **Admin** | Ingénierie Pédagogique | [19-admin-pedagogie.html](19-admin-pedagogie.html) | `admin/pedagogie.blade.php` |
| **All** | Profil / Paramètres | [04-admin-profile.html](04-admin-profile.html) | `profile/edit.blade.php` |
| **Error** | Page 404 | [21-error-404.html](21-error-404.html) | `errors/404.blade.php` |
| **Error** | Page 500 | [22-error-500.html](22-error-500.html) | `errors/500.blade.php` |

## Architecture Technique
- **CSS Principal**: `../charte-graphique/app.css` (Tailwind 4)
- **Logique UI**: Alpine.js (natif dans les fichiers HTML)
- **Typographie**: `Plus Jakarta Sans` (Intégrée via Google Fonts)
- **Couleurs**: Palettes HSL synchronisées avec la production.
