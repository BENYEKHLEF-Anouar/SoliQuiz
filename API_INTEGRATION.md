# SoliQuiz - API Integration Documentation

This document explains how the `SoliQuiz-mobile` application can fetch data from the `SoliQuiz` web backend using the new authentication system.

## 🔐 Authentication (Sanctum)

The API uses **Laravel Sanctum** for token-based authentication.

### Login Endpoint
- **URL**: `/api/login`
- **Method**: `POST`
- **Payload**:
  ```json
  {
    "email": "user@solicode.com",
    "password": "password",
    "device_name": "MobileApp"
  }
  ```
- **Response**:
  ```json
  {
    "token": "1|AbCdefgHijkLmNoPqRsTuVwXyZ...",
    "user": {
      "id": 1,
      "nom": "Doe",
      "prenom": "John",
      "role": "admin",
      "type_profil": "admin"
    }
  }
  ```

> [!IMPORTANT]
> All subsequent requests must include the token in the `Authorization` header:
> `Authorization: Bearer <your_token>`

---

## 👨‍🎓 Student Endpoints (`/api/student/*`)

| Endpoint | Method | Description |
| :--- | :--- | :--- |
| `/profile` | `GET` | Fetches authenticated student's profile & cohort info. |
| `/scores` | `GET` | Fetches global score, ranking, and total students in cohort. |
| `/evaluations` | `GET` | Lists pending QCMs for the student. |
| `/history` | `GET` | Returns list of completed QCMs with scores. |
| `/notifications` | `GET` | Fetches system notifications (mock data). |

---

| 👨‍🏫 Formateur Endpoints (`/api/formateur/*`) | Method | Description |
| :--- | :--- | :--- |
| `/profile` | `GET` | Fetches authenticated formateur's profile. |
| `/qcms` | `GET` | Lists QCMs created by the formateur. |
| `/cohorts` | `GET` | Lists classes managed by the formateur. |
| `/qcms/{id}/results` | `GET` | Detailed results for a specific QCM. |
| `/cohorts/{id}/students`| `GET` | List of students in a specific cohort with avg scores. |

---

## 📝 QCM Data Endpoints (`/api/qcm/*`)

| Endpoint | Method | Description |
| :--- | :--- | :--- |
| `/{id}` | `GET` | Basic QCM info. |
| `/{id}/questions` | `GET` | Full list of questions/options for a specific QCM. |
| `/{id}/result` | `GET` | Authenticated student's result for a specific QCM. |

---

## 🛠 Usage in Mobile App (Alpine.js / Axios)

```javascript
// Example: Authenticating and fetching profile
async function login(email, password) {
    const response = await axios.post('http://soliquiz.test/api/login', {
        email: email,
        password: password,
        device_name: 'MobileApp'
    });
    
    const token = response.data.token;
    localStorage.setItem('auth_token', token);
}

async function fetchProfile() {
    const token = localStorage.getItem('auth_token');
    const response = await axios.get('http://soliquiz.test/api/student/profile', {
        headers: { 'Authorization': `Bearer ${token}` }
    });
    console.log(response.data);
}
```

> [!TIP]
> Ensure your mobile app's `.env` (if applicable) or config uses the correct backend base URL.
