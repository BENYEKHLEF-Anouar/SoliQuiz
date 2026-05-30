# Technical Documentation: SoliQuiz Mobile

## 📌 Architecture Overview
`SoliQuiz-mobile` is a hybrid/web application frontend crafted using **Tailwind CSS 4** and **Alpine.js**. It is designed to act as the primary student entry point and alternative formateur supervision dashboard on mobile platforms.

## 🎨 UI & Styling
- **Luminous Design System**: Implements custom CSS variables defined in `app.css` (Ocean Teal palette).
- **Tailwind 4**: Utilizes the modern Vite + Tailwind CSS integration. Everything uses utility classes, keeping CSS minimal.
- **Blade Templating**: Views are organized in `resources/views/` (e.g., `student/dashboard.blade.php`, `auth/login.blade.php`) and utilize highly reusable components (`components.layout.app`, `components.header.student-header`).

## 🧠 State Management & Logic (Alpine.js)
Instead of a heavy framework like React or Vue, the app employs Alpine.js for lightweight reactivity:

- **Global Store (`app.js`)**: 
  An `Alpine.store('config')` is initialized to act as the single source of truth for the application state.
  - **Auth Persistence**: The store binds directly to the browser's `localStorage` to retain the `token` and `user` state across page reloads.
  - **Network Helper**: An `authFetch()` wrapper method intercepts all outbound API requests to inject the Sanctum `Authorization: Bearer <token>` header, removing redundancy in individual views.

- **View Components (`x-data`)**:
  Each Blade view (like the student dashboard) wraps its logic in an Alpine component (`x-data="dashboard"`). These components use standard asynchronous JavaScript (`async/await`) to hit the backend API (using `authFetch`) for data hydration without reloading the DOM.

## 🔒 Authentication Flow
1. **Login**: User inputs credentials in `auth/login.blade.php`.
2. **Fetch**: `handleLogin()` posts to `SoliQuiz` backend (`/api/login`).
3. **Persist**: Upon 200 OK, token is committed to `Alpine.store`.
4. **Redirect**: JavaScript strictly directs the user based on their `$user->type_profil`.
5. **Session Expiry**: Any subsequent `authFetch` that yields a `401 Unauthorized` triggers an automatic eviction map (clears `localStorage` and routes back to login).
