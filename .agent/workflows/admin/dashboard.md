---
description: Workflow for building Admin Dashboard module
trigger: /admin-dashboard
---

# 🛡️ Admin Dashboard Workflow

## Command
`/admin-dashboard`

## Dependencies
- **Skill:** `dashboard-analytics`
- **Rules:** `rules/data/service_layer.md`, `rules/roles/access_control.md`

## Execution Steps

### 1. Dashboard Controller
**File:** `app/Http/Controllers/Web/AdminController.php`

```php
public function dashboard(DashboardService $service): View
{
    $kpis = $service->getAdminKpis();
    $recentActivity = $service->getRecentActivity(10);
    $topQcms = $service->getTopQcms(5);
    
    return view('admin.dashboard', compact('kpis', 'recentActivity', 'topQcms'));
}
```

### 2. Service Methods
**File:** `app/Services/DashboardService.php`

Add `getAdminKpis()`, `getRecentActivity()`, `getTopQcms()`.

### 3. View Components
**File:** `resources/views/admin/dashboard.blade.php`

**Required sections:**
- KPI cards grid (4 cards: Users, Classes, QCMs, Attempts).
- Quick access cards (Pédagogie, Classes, Utilisateurs).
- Recent activity table.
- Top QCMs list.

### 4. Alpine.js State
```javascript
x-data={
    kpis: @json($kpis),
    recentActivity: @json($recentActivity),
    topQcms: @json($topQcms),
    loading: false
}
```

## Validation Checklist
- [ ] All KPIs calculating correctly.
- [ ] Quick access links working.
- [ ] Recent activity showing last 10 attempts.
- [ ] Top QCMs ordered by attempt count.
- [ ] Responsive grid layout.

**Trace:** `Admin Dashboard Workflow executed`
