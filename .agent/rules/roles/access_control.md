---
trigger: always_on
type: rule
id: access-control
---

# 🔐 SOLIQUIZ ACCESS CONTROL RULES

## Role Hierarchy

| Role | Permissions |
|------|-------------|
| **admin** | Full platform access. User/Classe/Seance management. |
| **formateur** | Manage own QCMs. View assigned classes. View results. |
| **etudiant** | Take QCMs. View own results. Access student dashboard. |

## Spatie Permission Setup

### Roles
```php
Role::create(['name' => 'admin']);
Role::create(['name' => 'formateur']);
Role::create(['name' => 'etudiant']);
```

### Middleware
```php
// routes/web.php
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin routes
});

Route::middleware(['auth', 'role:formateur'])->group(function () {
    // Formateur routes
});

Route::middleware(['auth', 'role:etudiant'])->group(function () {
    // Student routes
});
```

## User Model Helpers
```php
public function isAdmin(): bool
{
    return $this->hasRole('admin');
}

public function isFormateur(): bool
{
    return $this->hasRole('formateur');
}

public function isEtudiant(): bool
{
    return $this->hasRole('etudiant');
}
```

## Dashboard Routing
```php
// After login
if ($user->isAdmin()) {
    return redirect()->route('admin.dashboard');
} elseif ($user->isFormateur()) {
    return redirect()->route('formateur.dashboard');
} else {
    return redirect()->route('student.dashboard');
}
```

## API Authentication
- Web: Session-based (`auth` middleware).
- API: Sanctum token-based (`auth:sanctum` middleware).
