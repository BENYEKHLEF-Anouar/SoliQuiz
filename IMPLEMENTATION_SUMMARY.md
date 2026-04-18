# SoliQuiz API & Mobile Implementation Summary

## Overview
This document summarizes the implementation of:
1. **RESTful API** in the main `SoliQuiz/` Laravel application to serve data to the mobile app.
2. **Mobile views** in the separate `SoliQuiz-mobile/` Laravel application (NativePHP‑ready) that display data read‑only, based on the provided UI mockups.

The API endpoints were designed to match the data requirements extracted from the mobile mockups.  
The mobile views use **Tailwind CSS** (with the custom design tokens from the mockups) and **Alpine.js** to fetch data from the API and render it dynamically.

## 1. SoliQuiz API (Backend)

### 1.1 Configuration
- **Route file**: Added `routes/api.php` and registered it in `bootstrap/app.php`.
- **CORS**: Enabled cross‑origin requests via `HandleCors` middleware and a new `config/cors.php` (allow all origins for development).
- **Database**: No new tables were added; the existing schema and seeders were used.

### 1.2 API Endpoints
All endpoints return JSON and are prefixed with `/api`.

#### Student Endpoints (hardcoded student ID = 4)
| Endpoint | Description |
|----------|-------------|
| `GET /api/student/profile` | Student’s name, email, cohort. |
| `GET /api/student/scores` | Global score average and ranking within cohort. |
| `GET /api/student/evaluations` | List of published QCMs not yet attempted by the student. |
| `GET /api/student/notifications` | Hardcoded mock notifications (no notifications table). |
| `GET /api/student/history` | Past attempts with scores and dates. |

#### QCM Endpoints
| Endpoint | Description |
|----------|-------------|
| `GET /api/qcm/{id}` | QCM details (title, duration, question count, etc.). |
| `GET /api/qcm/{id}/questions` | All questions with their options (correctness hidden). |
| `GET /api/qcm/{id}/result` | Result for the student’s latest attempt, with per‑question feedback. |

#### Formateur Endpoints (hardcoded formateur ID = 2)
| Endpoint | Description |
|----------|-------------|
| `GET /api/formateur/profile` | Formateur’s name, email. |
| `GET /api/formateur/qcms` | List of QCMs created by the formateur, with counts. |
| `GET /api/formateur/cohorts` | Cohorts (classes) taught by the formateur. |
| `GET /api/formateur/cohorts/{cohortId}/students` | Students in a cohort with average scores and alert flags. |
| `GET /api/formateur/students/{studentId}/performance` | Detailed performance metrics for a student. |
| `GET /api/formateur/students/{studentId}/history` | Student’s attempt history. |

### 1.3 Controllers
Three controllers were created in `app/Http/Controllers/Api/`:
- `StudentController` – handles student‑related queries.
- `QcmController` – returns QCM details, questions, and results.
- `FormateurController` – provides formateur‑specific data.

Each controller uses the existing Eloquent models and relationships. No new models or migrations were added.

### 1.4 Authentication
For development, authentication is **skipped**. The API uses hardcoded user IDs (student ID 4, formateur ID 2). This can be replaced with proper token‑based authentication later.

## 2. SoliQuiz‑mobile (Frontend)

### 2.1 Project Setup
- The existing `SoliQuiz-mobile/` Laravel project was used.
- The `.env` file was adjusted to use `DB_CONNECTION=null` and `SESSION_DRIVER=array` (no database required).
- The mobile design system (`charte-graphique/style.css`) was copied to `public/css/`.

### 2.2 Layout
A master layout `resources/views/layouts/app.blade.php` includes:
- Tailwind CSS via CDN (configured with the design tokens from the mockups).
- Alpine.js for reactivity.
- Preline UI components.
- Custom CSS variables from the mobile design system.
- An Alpine store `config` holding the API base URL (`http://localhost:8000/api`) and hardcoded user IDs.

### 2.3 Routes
`routes/web.php` defines routes for each mobile view:

| Route | View |
|-------|------|
| `/student/dashboard` | Student dashboard |
| `/student/qcm/{id}` | QCM passation (questions) |
| `/student/qcm/{id}/result` | QCM result |
| `/student/history` | Student history |
| `/student/profile` | Student profile |
| `/formateur/qcms` | Formateur’s QCM list |
| `/formateur/class‑notes` | Class notes & student performance |
| `/formateur/profile` | Formateur profile |

### 2.4 Views
Each view is a Blade file that extends the layout and uses Alpine.js to fetch data from the API.

#### Student Views
- **Dashboard** – Shows profile, global score, ranking, pending evaluations, and notifications.
- **QCM Passation** – Displays one question at a time with options; navigation buttons allow moving between questions (read‑only).
- **QCM Result** – Shows score, percentage, objective‑met status, and per‑question feedback.
- **History** – Lists past attempts with scores.
- **Profile** – Displays student info and logout button.

#### Formateur Views
- **QCM List** – Searchable list of QCMs with status and dropdown actions (edit/duplicate/archive).
- **Class Notes** – Cohort selector, class average, student list with alerts, and a bottom sheet for student details.
- **Profile** – Formateur info and logout.

All views follow the mobile mockups’ structure and styling, using the same Tailwind classes and design tokens.

### 2.5 Data Flow
1. Alpine.js `x‑init` calls `fetch()` to the API endpoint.
2. The response is stored in Alpine data properties.
3. The template renders the data using `x‑text`, `x‑for`, `x‑show`, etc.
4. Loading states and basic error handling are included.

## 3. How to Run

### 3.1 Start the API Server (SoliQuiz)
```bash
cd SoliQuiz
php artisan serve --port=8000
```
The API will be available at `http://localhost:8000/api/`.

### 3.2 Start the Mobile App (SoliQuiz‑mobile)
```bash
cd SoliQuiz-mobile
php artisan serve --port=8001
```
The mobile web interface will be available at `http://localhost:8001/`.

### 3.3 Test the API
You can test any endpoint with a browser or curl, e.g.:
```
http://localhost:8000/api/student/profile
http://localhost:8000/api/qcm/1/questions
```

### 3.4 View the Mobile App
Open `http://localhost:8001/student/dashboard` (or any other route) in a browser.  
**Note**: The mobile layout is constrained to a phone‑like width (max‑width: 430px) for a realistic preview.

## 4. Testing
- All API endpoints return the expected JSON structure.
- All mobile routes load successfully (HTTP 200).
- The mobile views correctly fetch and display data from the API.
- No database is required for the mobile app; it only consumes the API.

## 5. Notes & Future Improvements

### 5.1 Limitations
- **No authentication**: The API uses hardcoded user IDs. Implement Sanctum or JWT for production.
- **Notifications**: Mock data is returned; a notifications table would be needed for real notifications.
- **CORS**: Currently allows all origins; restrict in production.
- **Mobile app**: This is a web‑based prototype; for a true NativePHP app, the same Blade views can be used inside an Electron shell.

### 5.2 Next Steps
1. Add authentication (login endpoint + token storage).
2. Create a notifications table and model.
3. Implement write operations (formateurs can create/edit QCMs, students can submit answers).
4. Add real‑time features (e.g., timer, auto‑save) using Alpine.js or Livewire.
5. Package the mobile app as an APK with NativePHP.
