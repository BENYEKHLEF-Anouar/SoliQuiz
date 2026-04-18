# SoliQuiz API Integration Guide

This document explains the REST API structure exposed by the `SoliQuiz` backend and how it should be consumed by the `SoliQuiz-mobile` frontend application.

## 1. Overview & Current State

The `SoliQuiz` backend provides a set of JSON REST API endpoints to serve data for both Students and Formateurs.

> [!WARNING]
> **Dev/Staging Note:** Currently, authentication is bypassed for development. The backend controllers hardcode the user IDs:
> - **Student Role:** Hardcoded to ID `4` (Mehdi)
> - **Formateur Role:** Hardcoded to ID `2` (Youssef)
> 
> You do not need to pass bearer tokens for these specific endpoints at the moment, but standard Laravel Sanctum authentication will likely be required in production.

## 2. Base URL

Assuming your local Laravel backend is running on port `8000`:
`http://localhost:8000/api`

In your `SoliQuiz-mobile` fetch calls, you can configure a base URL or call the full endpoint.

---

## 3. Available Endpoints

### 3.1. Student Endpoints (`/api/student/...`)

These endpoints provide data for the student dashboard and views.

- `GET /api/student/profile`: Retrieves the student's profile information (name, email, role, cohort).
- `GET /api/student/scores`: Returns the global average score (`globalScore`), cohort ranking (`ranking`), and total students in the cohort.
- `GET /api/student/evaluations`: Returns pending QCMs that the student has not yet completed.
- `GET /api/student/notifications`: Returns a list of notifications (currently mocked data).
- `GET /api/student/history`: Returns the history of completed QCM tentatives (dates, scores, total questions).

### 3.2. Formateur Endpoints (`/api/formateur/...`)

These endpoints provide data for the instructor's (formateur's) dashboard and tracking.

- `GET /api/formateur/profile`: Retrieves the formateur's profile information.
- `GET /api/formateur/qcms`: Lists all QCMs created by the formateur, including their status (Actif/Brouillon) and participation counts.
- `GET /api/formateur/cohorts`: Lists the cohorts (Actif/Brouillon) managed by the formateur.
- `GET /api/formateur/cohorts/{cohortId}/students`: Lists all students in a specific cohort, including their average score and an alert flag if they are underperforming.
- `GET /api/formateur/students/{studentId}/performance`: Provides a high-level performance overview for a specific student.
- `GET /api/formateur/students/{studentId}/history`: Details the history of QCM attempts for a specific student.
- `GET /api/formateur/qcms/{qcmId}/results`: Retrieves the leaderboard/results of all students for a specific QCM.

### 3.3. QCM Execution Endpoints (`/api/qcm/...`)

These endpoints handle the actual mechanics of taking a quiz.

- `GET /api/qcm/{id}`: Retrieves metadata about a specific QCM (duration, total questions, success threshold).
- `GET /api/qcm/{id}/questions`: Retrieves the questions and possible options for the QCM (without the correct answers attached, to prevent cheating).
- `GET /api/qcm/{id}/result`: Retrieves the final result of the student's latest attempt for this QCM, including their chosen answers, the correct answers, explanations, and whether the objective was met.

---

## 4. Fetching Data in Alpine.js (`SoliQuiz-mobile`)

Since `SoliQuiz-mobile` heavily utilizes Alpine.js, you should handle data fetching within your Alpine component logic. 

> [!TIP]
> Use standard `fetch` API combined with Alpine's `init()` method (or `x-init`) to load data asynchronously. Also, ensure you manage loading states to prevent visual flickering.

### Example: Fetching Student Profile in Alpine.js

```html
<div x-data="studentProfile()" x-init="fetchProfile()">
    <!-- Loading State -->
    <div x-show="isLoading">Loading profile...</div>

    <!-- Data State -->
    <div x-show="!isLoading" style="display: none;">
        <h2 x-text="profile.nom + ' ' + profile.prenom"></h2>
        <p x-text="profile.role"></p>
        <p x-text="profile.cohort"></p>
    </div>
</div>

<script>
function studentProfile() {
    return {
        isLoading: true,
        profile: {},
        errorMessage: '',
        
        async fetchProfile() {
            try {
                this.isLoading = true;
                // Use the full URL if your backend and frontend are running on different ports
                const response = await fetch('http://localhost:8000/api/student/profile');
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                
                const data = await response.json();
                this.profile = data;
            } catch (error) {
                console.error("Failed to fetch profile:", error);
                this.errorMessage = "Impossible de charger le profil.";
            } finally {
                // Consider adding a slight timeout if you want a guaranteed minimum loading duration (UX consistency)
                setTimeout(() => {
                    this.isLoading = false;
                }, 600);
            }
        }
    }
}
</script>
```

### Example: Submitting a QCM (Future implementation)

While the API currently focuses on `GET` requests for data retrieval, future iterations will include `POST` routes to submit answers. When implementing them via `fetch`, remember to include appropriate headers:

```javascript
async submitAnswers(payload) {
    const response = await fetch('http://localhost:8000/api/qcm/1/submit', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            // 'Authorization': 'Bearer ' + token // When Sanctum is enabled
        },
        body: JSON.stringify(payload)
    });
    return response.json();
}
```

## 5. Next Steps & Considerations

1. **Wait for dynamic authentication**: As of now, the endpoints rely on `$studentId = 4` and `$formateurId = 2`. When authentication (`Laravel Sanctum` or similar) is integrated, the fetch requests from Alpine will need to pass an Authorization token or rely on cookie-based session auth.
2. **CORS Configuration**: If `SoliQuiz-mobile` runs on a different port (e.g., `8080`) than `SoliQuiz` (`8000`), ensure `cors.php` in the backend is configured to accept requests from the frontend base URL.
