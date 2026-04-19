# Technical Documentation: SoliQuiz (Web Backend)

## 📌 Architecture Overview
SoliQuiz is built on **Laravel 12**, serving dual purposes:
1. **Web Interface (Blade)**: A high-fidelity backend interface for Administrators and Formateurs.
2. **RESTful API (Sanctum)**: A secure data provider for the `SoliQuiz-mobile` application.

## 🗄️ Database Schema & Models
The data layer is highly relational, utilizing Eloquent ORM:

- **`User`**: Enhanced with `Spatie/laravel-permission` (`HasRoles`) and `Laravel\Sanctum\HasApiTokens`. Differentiated by `type_profil` (`admin`, `formateur`, `etudiant`).
- **`Classe` (Cohort)**: Handled by a `formateur` (One-to-Many). Contains multiple `etudiants`.
- **`UniteApprentissage` & `Competence`**: Taxonomy for structuring knowledge bases.
- **`QCM`**: Built by a `formateur`, linked to an `UniteApprentissage`.
- **`Question` & `Option`**: The core of the evaluation system.
- **`Tentative` & `Reponse` & `ChoixReponse`**: Tracks student attempts, their scores, and chosen answers.

## 🔐 Security & Authentication
- **Web Auth**: Relies on session-based Laravel UI scaffolding (`cookies/session`).
- **Mobile API Auth**: Uses **Laravel Sanctum**. The `AuthController@login` issues an opaque personal access token.

## 🧭 Routing Structure
- **`routes/web.php`**: Contains protected groups utilizing the `role:admin|formateur|etudiant` middleware.
- **`routes/api.php`**: Protected entirely by `auth:sanctum`. Endpoints are neatly grouped by context: `/student`, `/formateur`, and `/qcm`.

## ⚙️ Key Controllers (API)
- **`StudentController`**: Aggregates profile data, cohort ranking, and pending evaluations tailored to the authenticated user token.
- **`FormateurController`**: Provides aggregate KPIs for cohort supervision.
- **`QcmController`**: Serves QCM payloads (questions/options) and computes results dynamically based on `Tentative` data.
