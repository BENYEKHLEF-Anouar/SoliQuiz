---
description: Workflow for building User Management module
trigger: /admin-users
---

# 👤 Gestion Utilisateurs Workflow

## Command
`/admin-users`

## Dependencies
- **Skill:** `classe-manager`
- **Rules:** `rules/roles/access_control.md`, `rules/data/service_layer.md`

## Execution Steps

### 1. Controller Methods
**File:** `app/Http/Controllers/Web/AdminController.php`

```php
public function gestionUtilisateurs(): View
{
    $users = User::with('classe')
        ->orderBy('created_at', 'desc')
        ->paginate(20);
    
    return view('admin.gestion-utilisateurs', compact('users'));
}

public function storeUser(Request $request, UserService $service): RedirectResponse
{
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'type_profil' => 'required|in:admin,formateur,etudiant',
        'classe_id' => 'nullable|exists:classes,id',
        'password' => 'required|min:8',
    ]);
    
    $service->createUser($validated);
    
    return redirect()->route('admin.utilisateurs')
        ->with('success', 'Utilisateur créé avec succès');
}
```

### 2. UserService
**File:** `app/Services/UserService.php`

```php
public function createUser(array $data): User
{
    $user = User::create([
        'nom' => $data['nom'],
        'prenom' => $data['prenom'],
        'email' => $data['email'],
        'type_profil' => $data['type_profil'],
        'classe_id' => $data['classe_id'] ?? null,
        'password' => Hash::make($data['password']),
    ]);
    
    $user->assignRole($data['type_profil']);
    
    return $user;
}
```

### 3. View
**File:** `resources/views/admin/gestion-utilisateurs.blade.php`

**Required elements:**
- Alpine.js search/filter.
- User table with pagination.
- "Ajouter Utilisateur" modal.
- Role badge display.
- Delete with confirmation.

### 4. Alpine.js State
```javascript
x-data={
    users: @json($users),
    search: '',
    filterRole: '',
    showCreateModal: false,
    showDeleteModal: false,
    selectedUser: null,
    
    filteredUsers() {
        return this.users.filter(u => {
            const matchesSearch = (u.nom + ' ' + u.prenom + u.email)
                .toLowerCase()
                .includes(this.search.toLowerCase());
            const matchesRole = !this.filterRole || u.type_profil === this.filterRole;
            return matchesSearch && matchesRole;
        });
    }
}
```

## Validation Checklist
- [ ] Create user with role assignment.
- [ ] Search/filter working.
- [ ] Pagination functioning.
- [ ] Delete with confirmation.
- [ ] Form validation messages.

**Trace:** `Gestion Utilisateurs Workflow executed`
