# SoliQuiz — API Integration Documentation

> **Last updated:** May 2026 — Full Sanctum API with Student, Formateur & QCM endpoints.

---

## 📌 Overview

The SoliQuiz backend exposes a **RESTful JSON API** under `/api/` consumed by the `SoliQuiz-mobile` app (NativePHP / React Native / Flutter).

| Detail | Value |
|---|---|
| **Base URL (dev)** | `http://127.0.0.1:8000/api` |
| **Auth mechanism** | Laravel Sanctum — opaque personal access tokens |
| **Content-Type** | `application/json` |
| **Response format** | JSON |

---

## 🔐 Authentication

### `POST /api/login`

Public endpoint. Returns a Sanctum token valid for all subsequent requests.

**Request body:**
```json
{
  "email": "user@solicode.ma",
  "password": "secret",
  "device_name": "SoliQuiz-Mobile"
}
```

**Success response `200`:**
```json
{
  "token": "1|AbCdEfGhIjKlMnOpQrStUvWxYz...",
  "user": {
    "id": 12,
    "nom": "Benyekhlef",
    "prenom": "Anouar",
    "email": "anouar@solicode.ma",
    "role": "formateur",
    "type_profil": "formateur"
  }
}
```

**Error response `422`** (wrong credentials):
```json
{
  "message": "Les identifiants sont incorrects.",
  "errors": { "email": ["Les identifiants sont incorrects."] }
}
```

> [!IMPORTANT]
> All subsequent requests **must** include the token in the `Authorization` header:
> `Authorization: Bearer <token>`

---

### `POST /api/logout`

Revokes the current token. Requires authentication.

**Response `200`:**
```json
{ "message": "Déconnecté avec succès" }
```

---

## 👨‍🎓 Student Endpoints

**Prefix:** `/api/student/`  
**Middleware:** `auth:sanctum` + `role:etudiant,admin`

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/student/profile` | Authenticated student's profile & cohort |
| `GET` | `/api/student/scores` | Global average score, cohort ranking, total students |
| `GET` | `/api/student/evaluations` | Published QCMs the student hasn't attempted yet |
| `GET` | `/api/student/history` | Completed QCMs with scores, ordered newest first |
| `GET` | `/api/student/notifications` | System notifications (mocked) |

### Response Shapes

#### `GET /api/student/profile`
```json
{
  "id": 12,
  "nom": "Alami",
  "prenom": "Sara",
  "email": "sara@solicode.ma",
  "avatarUrl": null,
  "role": "Apprenant",
  "cohort": "Développement Fullstack"
}
```

#### `GET /api/student/scores`
```json
{
  "globalScore": 14.75,
  "ranking": 3,
  "totalStudents": 18
}
```

#### `GET /api/student/evaluations`
```json
[
  {
    "id": 7,
    "title": "PHP — Syntaxe Fondamentaux",
    "subject": "Programmation Web",
    "dueDate": null,
    "urgent": false
  }
]
```

#### `GET /api/student/history`
```json
[
  {
    "id": 34,
    "title": "Laravel — Routes & Contrôleurs",
    "date": "2026-05-10 14:32",
    "score": 16.5,
    "totalQuestions": 10
  }
]
```

#### `GET /api/student/notifications`
```json
[
  {
    "id": 1,
    "type": "urgent",
    "message": "Nouveau QCM disponible: PHP - Syntaxe Fondamentaux",
    "read": false,
    "createdAt": "2026-05-15T17:00:00Z"
  }
]
```

---

## 📝 QCM Endpoints

**Prefix:** `/api/qcm/`  
**Middleware:** `auth:sanctum` + `role:etudiant,admin`

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/qcm/{id}` | Basic QCM metadata |
| `GET` | `/api/qcm/{id}/questions` | Full question list with options (no correct answer flag) |
| `GET` | `/api/qcm/{id}/result` | Student's latest completed attempt result with per-question correction |

### Response Shapes

#### `GET /api/qcm/{id}`
```json
{
  "id": 7,
  "title": "PHP — Syntaxe Fondamentaux",
  "durationMinutes": 30,
  "totalQuestions": 10,
  "successScore": 12.0,
  "isPublished": true
}
```

#### `GET /api/qcm/{id}/questions`
```json
[
  {
    "id": 42,
    "text": "Quelle balise ouvre un bloc PHP ?",
    "type": "single",
    "points": 2,
    "options": [
      { "id": 101, "text": "<?php" },
      { "id": 102, "text": "<php>" },
      { "id": 103, "text": "<?>" }
    ]
  }
]
```

> [!NOTE]
> `isCorrect` is **intentionally omitted** from options in this endpoint to prevent cheating. It is only included in the `/result` response.

#### `GET /api/qcm/{id}/result`
```json
{
  "qcmId": 7,
  "title": "PHP — Syntaxe Fondamentaux",
  "score": 16.0,
  "totalQuestions": 10,
  "percentage": 80,
  "objectiveMet": true,
  "questions": [
    {
      "id": 42,
      "text": "Quelle balise ouvre un bloc PHP ?",
      "userAnswer": [101],
      "correctAnswer": [101],
      "isCorrect": true,
      "explanation": "<?php est la balise d'ouverture standard."
    }
  ]
}
```

**Error `404`** if no completed attempt exists:
```json
{ "message": "No result found" }
```

---

## 👨‍🏫 Formateur Endpoints

**Prefix:** `/api/formateur/`  
**Middleware:** `auth:sanctum`

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/formateur/profile` | Authenticated formateur's profile |
| `GET` | `/api/formateur/qcms` | All QCMs created by this formateur |
| `GET` | `/api/formateur/qcms/{id}/results` | All student results for a specific QCM |
| `GET` | `/api/formateur/cohorts` | Classes managed by this formateur |
| `GET` | `/api/formateur/cohorts/{id}/students` | Students in a cohort with average scores |
| `GET` | `/api/formateur/students/{id}/performance` | Individual student performance summary |
| `GET` | `/api/formateur/students/{id}/history` | Attempt history for a specific student |

### Response Shapes

#### `GET /api/formateur/profile`
```json
{
  "id": 5,
  "nom": "Benyekhlef",
  "prenom": "Anouar",
  "email": "anouar@solicode.ma",
  "avatarUrl": null,
  "role": "Formateur Référent"
}
```

#### `GET /api/formateur/qcms`
```json
[
  {
    "id": 7,
    "title": "PHP — Syntaxe Fondamentaux",
    "status": "Actif",
    "questionsCount": 10,
    "assignedCohort": "Développement Fullstack",
    "resultsCount": 14
  }
]
```

> QCM `status` mapping: `public` → `"Actif"` | `termine` → `"Terminé"` | other → `"Brouillon"`

#### `GET /api/formateur/qcms/{id}/results`
```json
{
  "qcmId": 7,
  "title": "PHP — Syntaxe Fondamentaux",
  "results": [
    {
      "id": 34,
      "studentName": "Sara Alami",
      "score": 16.5,
      "totalQuestions": 10,
      "maxScore": 20,
      "date": "2026-05-10 14:32"
    }
  ]
}
```

#### `GET /api/formateur/cohorts`
```json
[
  {
    "id": 3,
    "name": "Développement Fullstack",
    "promotion": "P-2024"
  }
]
```

#### `GET /api/formateur/cohorts/{id}/students`
```json
[
  {
    "id": 12,
    "name": "Sara Alami",
    "avatarUrl": null,
    "averageScore": 14.75,
    "alert": false
  }
]
```

> `alert: true` when `averageScore < 10`

#### `GET /api/formateur/students/{id}/performance`
```json
{
  "studentId": 12,
  "name": "Sara Alami",
  "lastQcmScore": 16.5,
  "participation": "Active",
  "averageScore": 14.75,
  "alert": false
}
```

#### `GET /api/formateur/students/{id}/history`
```json
[
  {
    "id": 34,
    "title": "Laravel — Routes & Contrôleurs",
    "date": "2026-05-10 14:32",
    "score": 16.5,
    "totalQuestions": 10
  }
]
```

---

## ⚠️ Error Handling

| HTTP Code | Meaning |
|---|---|
| `401` | Missing or invalid token — re-authenticate |
| `403` | Authenticated but wrong role for this endpoint |
| `404` | Resource not found |
| `422` | Validation error (wrong credentials, missing fields) |
| `500` | Server error — check Laravel logs |

---

## 💻 Usage Example (JavaScript / Axios)

```javascript
const BASE_URL = 'http://127.0.0.1:8000/api';

// Login and store token
async function login(email, password) {
    const { data } = await axios.post(`${BASE_URL}/login`, {
        email,
        password,
        device_name: 'SoliQuiz-Mobile'
    });
    localStorage.setItem('api_token', data.token);
    return data.user;
}

// Authenticated request helper
function apiClient() {
    return axios.create({
        baseURL: BASE_URL,
        headers: {
            Authorization: `Bearer ${localStorage.getItem('api_token')}`,
            Accept: 'application/json'
        }
    });
}

// Fetch student evaluations
async function getEvaluations() {
    const { data } = await apiClient().get('/student/evaluations');
    return data;
}

// Fetch QCM questions
async function getQcmQuestions(qcmId) {
    const { data } = await apiClient().get(`/qcm/${qcmId}/questions`);
    return data;
}
```

> [!TIP]
> In production, replace `http://127.0.0.1:8000` with your server's public URL and ensure `SANCTUM_STATEFUL_DOMAINS` in `.env` is configured accordingly.

---

## 🗒️ Known Limitations & Notes

| Item | Note |
|---|---|
| **Notifications** | Endpoint returns hardcoded mock data — no `notifications` table in DB |
| **`avatarUrl`** | Always returns `null` — no file upload system implemented yet |
| **Formateur auth guard** | `/api/formateur/*` uses `auth:sanctum` only (no role check at route level) — role check is done manually in the controller |
| **Score scale** | All `score` values in the API are on a `/20` scale (`decimal(5,1)`) |
| **`dueDate`** | Always `null` in evaluations — no deadline field in the `qcms` table |
