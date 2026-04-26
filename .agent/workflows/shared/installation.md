---
description: Initial project setup and installation workflow
trigger: /install
---

# 🔧 Installation & Setup Workflow

## Command
`/install`

## Prerequisites
- PHP 8.4+
- Composer
- Node.js 20+
- MySQL 8.0+

## Execution Steps

### 1. Laravel Installation
```bash
composer create-project laravel/laravel:^12.0 SoliQuiz
cd SoliQuiz
```

### 2. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

**Edit `.env`:**
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=soliquiz
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Database Setup
```bash
mysql -u root -p -e "CREATE DATABASE soliquiz;"
php artisan migrate
```

### 4. Install Dependencies

**Spatie Laravel Permission:**
```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

**Laravel Sanctum (API):**
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

**Frontend:**
```bash
npm install
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

**Alpine.js:**
```bash
npm install alpinejs
```

### 5. Tailwind Configuration

**`tailwind.config.js`:**
```javascript
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
```

**`resources/css/app.css`:**
```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

**`vite.config.js`:**
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
```

### 6. Create Directory Structure
```bash
mkdir -p app/Http/Controllers/Web
mkdir -p app/Http/Controllers/Api
mkdir -p app/Services
mkdir -p resources/views/admin
mkdir -p resources/views/formateur
mkdir -p resources/views/student
mkdir -p resources/views/components
mkdir -p resources/views/layouts
```

### 7. Seed Roles
**`database/seeders/RoleSeeder.php`:**
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'formateur']);
        Role::create(['name' => 'etudiant']);
    }
}
```

```bash
php artisan db:seed --class=RoleSeeder
```

### 8. Build Assets
```bash
npm run build
```

### 9. Serve Application
```bash
php artisan serve
```

## Verification Checklist
- [ ] Laravel installed and running.
- [ ] Database connected and migrated.
- [ ] Spatie roles created.
- [ ] Tailwind CSS compiling.
- [ ] Alpine.js loaded.
- [ ] Login page accessible.
- [ ] No console errors.

**Trace:** `Installation Workflow executed`
