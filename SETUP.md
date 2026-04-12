   # SoliQuiz + SoliQuiz-mobile Setup Guide

## Prerequisites

- PHP 8.2+ (for SoliQuiz) / PHP 8.3+ (for SoliQuiz-mobile)
- Composer
- Node.js + npm
- MySQL server (for SoliQuiz)
- Git

---

## Part 1: Setup SoliQuiz (API Server)

**Location**: `D:\WebProjects\SoliQuiz\SoliQuiz\`

```bash
# Navigate to project
cd D:\WebProjects\SoliQuiz\SoliQuiz

# Install PHP dependencies
composer install

# Verify .env has these settings:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=soliquiz
# DB_USERNAME=root
# DB_PASSWORD=Anouar20032005

# Generate application key (if not already done)
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database with test data
php artisan db:seed

# Start the API server (port 8000)
php artisan serve --port=8000
```

**Verify API is working**: Visit `http://localhost:8000/api/student/profile`

---

## Part 2: Setup SoliQuiz-mobile (Mobile Consumer)

**Location**: `D:\WebProjects\SoliQuiz\SoliQuiz-mobile\`

```bash
# Navigate to project
cd D:\WebProjects\SoliQuiz\SoliQuiz-mobile

# Install PHP dependencies
composer install

# Copy .env example and configure
copy .env.example .env

# Generate application key
php artisan key:generate

# Install npm dependencies and build assets
npm install
npm run build

# Start the mobile app server (use a different port, e.g., 8001)
php artisan serve --port=8001
```

---

## Part 3: Project Structure

### SoliQuiz-mobile Component Structure
```
resources/
├── css/
│   └── app.css              (Tailwind v4 with custom CSS variables)
├── js/
│   ├── app.js               (Entry point)
│   └── bootstrap.js
├── views/
│   ├── components/
│   │   ├── layout/
│   │   │   └── app.blade.php      (Base layout with Vite)
│   │   ├── nav/
│   │   │   ├── student-bottom-nav.blade.php
│   │   │   └── formateur-bottom-nav.blade.php
│   │   ├── header/
│   │   │   ├── student-header.blade.php
│   │   │   └── formateur-header.blade.php
│   │   ├── cards/
│   │   │   └── evaluation-card.blade.php
│   │   └── icons/
│   │       └── svg-icons.blade.php
│   ├── partials/
│   │   └── stats-grid.blade.php
│   ├── landing.blade.php
│   ├── auth/login.blade.php
│   ├── student/
│   │   ├── dashboard.blade.php
│   │   ├── qcm-passation.blade.php
│   │   ├── qcm-result.blade.php
│   │   ├── history.blade.php
│   │   └── profile.blade.php
│   └── formateur/
│       ├── qcms.blade.php
│       ├── class-notes.blade.php
│       └── profile.blade.php
```

---

## Part 4: How It Works

### SoliQuiz (API) - Runs on `http://localhost:8000`

API Endpoints:
- `GET /api/student/profile` - Student profile (hardcoded ID 4)
- `GET /api/student/scores` - Student scores
- `GET /api/student/evaluations` - Available QCMs
- `GET /api/student/notifications` - Notifications
- `GET /api/student/history` - QCM history
- `GET /api/qcm/{id}` - QCM details
- `GET /api/qcm/{id}/questions` - Questions (with answers!)
- `GET /api/qcm/{id}/result` - QCM result
- `GET /api/formateur/profile` - Formateur profile (hardcoded ID 2)
- `GET /api/formateur/qcms` - Formateur QCMs
- `GET /api/formateur/cohorts` - Cohorts
- `GET /api/formateur/cohorts/{id}/students` - Students in cohort

### SoliQuiz-mobile - Runs on `http://localhost:8001`

Routes:
- `/` - Landing page
- `/login` - Login page
- `/student/dashboard` - Student dashboard
- `/student/qcm/{id}` - QCM passation
- `/student/qcm/{id}/result` - QCM result
- `/student/history` - History/Stats
- `/student/profile` - Student profile
- `/formateur/qcms` - Formateur QCMs
- `/formateur/class-notes` - Class notes
- `/formateur/profile` - Formateur profile

---

## Part 5: Running Both Simultaneously

**Terminal 1: SoliQuiz API**
```bash
cd D:\WebProjects\SoliQuiz\SoliQuiz
php artisan serve --port=8000
```

**Terminal 2: SoliQuiz-mobile**
```bash
cd D:\WebProjects\SoliQuiz\SoliQuiz-mobile
npm run dev
php artisan serve --port=8001
```

**Note**: Run `npm run build` after any CSS/JS changes.

---

## Part 6: Testing Flow

1. Start SoliQuiz on port 8000
2. Start SoliQuiz-mobile on port 8001
3. Open mobile in browser: `http://localhost:8001`
4. Click "Se Connecter maintenant" → Login page
5. Enter any email (e.g., `test@solicode.co`) → Student dashboard
6. Enter email with "formateur" (e.g., `formateur@solicode.co`) → Formateur QCMs

---

## Important Notes

1. **API URL**: SoliQuiz-mobile is configured to call `http://localhost:8000/api`. Ensure SoliQuiz is running first.

2. **CORS**: Already configured in SoliQuiz (`config/cors.php`) to allow all origins.

3. **Database**: SoliQuiz-mobile uses SQLite (empty) - it only reads from SoliQuiz API, doesn't need its own database.

4. **Hardcoded Users**: The API uses hardcoded IDs:
   - Student ID: 4 (Mehdi)
   - Formateur ID: 2 (Youssef)

5. **Vite + Tailwind v4**: Assets are built with Vite. Run `npm run build` after any changes to CSS/JS.

6. **Active Navigation**: Bottom nav highlights active route using `request()->routeIs()`.

---

## Troubleshooting

| Issue | Solution |
|-------|----------|
| API calls fail | Ensure SoliQuiz is running on port 8000 |
| CORS error | Check SoliQuiz `config/cors.php` |
| No data showing | Check browser console for fetch errors |
| Port already in use | Use different port: `php artisan serve --port=8080` |
| Tailwind not loading | Run `npm run build` to compile assets |
| Vite errors | Ensure Node.js is installed and run `npm install` |

---

## Part 7: Package as Mobile App with NativePHP

### Prerequisites for Android Build
- Android SDK installed (e.g., `C:\Users\<YourUser>\AppData\Local\Android\Sdk`)
- PHP 8.3+ (matching composer.json requirement)
- Java/JDK configured
- Android device or emulator connected

### Build Commands

```bash
# Navigate to mobile project
cd D:\WebProjects\SoliQuiz\SoliQuiz-mobile

# Option 1: Using the custom setup script (recommended)
# This fixes common NativePHP Android issues
powershell -ExecutionPolicy Bypass -File setup-android.ps1

# Option 2: Standard NativePHP install
php artisan native:install android

# Build and run on connected device/emulator
php artisan native:run

# Build release APK
php artisan native:build android --release

# Build debug APK
php artisan native:build android --debug
```

### Important Configuration Files

| File | Purpose |
|------|---------|
| `nativephp.json` | Pins PHP version (create if not exists) |
| `nativephp/android/local.properties` | Android SDK path |
| `setup-android.ps1` | Custom setup script (fixes bin.nativephp.com failures) |

### Troubleshooting NativePHP

| Issue | Solution |
|-------|----------|
| "PHP binaries not available" | Run `.\setup-android.ps1` instead of `native:install` |
| "libphp.a not found" | Check `nativephp/android/app/src/main/staticLibs/arm64-v8a/libphp.a` |
| PHP version mismatch | Create `nativephp.json` with `"php": { "version": "8.3" }` |
| SDK not found | Edit `nativephp/android/local.properties` with `sdk.dir=C\:\\...\\Android\\Sdk` |

### APK Output Location
- Debug: `nativephp/android/app/build/outputs/apk/debug/app-debug.apk`
- Release: `nativephp/android/app/build/outputs/apk/release/app-release.apk`