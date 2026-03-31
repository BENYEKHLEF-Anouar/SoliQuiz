# How Alpine.js is Used in SoliQuiz-mobile to Fetch Data from SoliQuiz API

## Overview
The mobile app (`SoliQuiz-mobile`) uses **Alpine.js** as a lightweight reactive framework to fetch data from the backend API (`SoliQuiz`) and display it in real-time without full page reloads. Each mobile view is a Blade template that contains Alpine directives and JavaScript functions.

## Architecture

### 1. **API Base URL Configuration**
In `resources/views/layouts/app.blade.php`, an Alpine store is initialized with the API base URL and hardcoded user IDs:

```javascript
document.addEventListener('alpine:init', () => {
    Alpine.store('config', {
        apiBaseUrl: 'http://localhost:8000/api',
        studentUserId: 4,
        formateurUserId: 2
    });
});
```

This store is accessible in all Alpine components via `Alpine.store('config')`.

### 2. **Component Structure**
Each view (e.g., `student/dashboard.blade.php`) follows this pattern:

```blade
@extends('layouts.app')
@section('content')
<div x-data="dashboard()" x-init="init()">
    <!-- HTML with Alpine directives -->
    <span x-text="profile.prenom"></span>
    <template x-for="item in items" :key="item.id">
        <div x-text="item.title"></div>
    </template>
</div>

<script>
function dashboard() {
    return {
        // Reactive data properties
        profile: {},
        scores: {},
        
        // Initialization
        async init() {
            await this.fetchProfile();
            await Promise.all([
                this.fetchScores(),
                this.fetchEvaluations()
            ]);
        },
        
        // API fetch methods
        async fetchProfile() {
            const response = await fetch(`${Alpine.store('config').apiBaseUrl}/student/profile`);
            this.profile = await response.json();
        },
        
        // Computed properties
        get urgentCount() {
            return this.evaluations.filter(e => e.urgent).length;
        }
    }
}
</script>
@endsection
```

## Key Alpine Features Used

### 1. **`x-data`**
Declares a reactive component with initial data and methods.
```html
<div x-data="dashboard()">
```

### 2. **`x-init`**
Calls the initialization function when the component loads.
```html
<div x-init="init()">
```

### 3. **`x-text`**
Dynamically updates text content.
```html
<span x-text="profile.prenom"></span>
```

### 4. **`x-for`**
Loops over arrays to render lists.
```html
<template x-for="evaluation in evaluations" :key="evaluation.id">
    <div x-text="evaluation.title"></div>
</template>
```

### 5. **`x-show` / `x-if`**
Conditionally shows/hides elements.
```html
<span x-show="urgentCount > 0" x-text="urgentCount + ' Urgent'"></span>
<template x-if="currentQuestion"> ... </template>
```

### 6. **Dynamic Attributes**
Binds HTML attributes to JavaScript expressions.
```html
<a :href="'/student/qcm/' + evaluation.id">
<div :class="evaluation.urgent ? 'bg-warning-500' : 'bg-transparent'">
```

### 7. **Event Handling**
Attaches event listeners.
```html
<button @click="prevQuestion" :disabled="currentIndex === 0">
<input @change="selectOption(option.id)">
```

## Data Fetching Pattern

### API Endpoints Used
| View | API Endpoint | Purpose |
|------|--------------|---------|
| Student Dashboard | `GET /api/student/profile` | User info |
| | `GET /api/student/scores` | Global score & ranking |
| | `GET /api/student/evaluations` | Pending QCMs |
| | `GET /api/student/notifications` | Mock notifications |
| QCM Passation | `GET /api/qcm/{id}` | QCM details |
| | `GET /api/qcm/{id}/questions` | Questions & options |
| QCM Result | `GET /api/qcm/{id}/result` | Score & feedback |
| Formateur QCMs | `GET /api/formateur/qcms` | List of QCMs |
| Class Notes | `GET /api/formateur/cohorts` | Cohort list |
| | `GET /api/formateur/cohorts/{id}/students` | Student performance |

### Example Fetch Method
```javascript
async fetchProfile() {
    try {
        const response = await fetch(
            `${Alpine.store('config').apiBaseUrl}/student/profile`
        );
        this.profile = await response.json();
    } catch (e) {
        console.error('Failed to load profile', e);
    }
}
```

## Loading States & Error Handling
Each component includes:
- **`loading`** boolean to show spinners
- **`try/catch`** blocks with console error logging
- **Empty state** displays when no data

## Benefits of This Approach
1. **No page reloads** – Data is fetched asynchronously
2. **Minimal JavaScript** – Only ~50-100 lines per view
3. **Reactive UI** – Automatic updates when data changes
4. **Easy to maintain** – Clear separation of concerns
5. **Mobile-optimized** – Fast, responsive interactions

## Sample API Response
**Endpoint:** `GET /api/student/profile`
```json
{
    "id": 4,
    "nom": "Rachidi",
    "prenom": "Mehdi",
    "email": "mehdi@solicode.ma",
    "avatarUrl": null,
    "role": "Apprenant",
    "cohort": "DWB101"
}
```

## Testing the Integration
1. Start API server: `cd SoliQuiz && php artisan serve --port=8000`
2. Start mobile app: `cd SoliQuiz-mobile && php artisan serve --port=8001`
3. Visit `http://localhost:8001/student/dashboard`
4. Open browser DevTools → Network tab to see API requests

---

**Note:** This implementation uses **hardcoded user IDs** (student=4, formateur=2) for development. In production, authentication would be added to identify the current user.