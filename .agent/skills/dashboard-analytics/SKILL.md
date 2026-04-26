---
name: dashboard-analytics
description: Specialized skill for building role-based dashboards with KPIs and statistics.
---

# 📊 DASHBOARD ANALYTICS SKILL

## Capabilities

### 1. Admin Dashboard
**KPIs:**
- Total users (by role: admin, formateur, etudiant).
- Total classes.
- Total QCMs (by status: published, draft).
- Total attempts this month.
- Average platform-wide score.

**Widgets:**
- Quick access cards: Pédagogie, Classes, Utilisateurs.
- Recent activity feed (last 10 attempts).
- Top performing QCMs (by attempt count).
- Low performing QCMs (by average score).

### 2. Formateur Dashboard
**KPIs:**
- Classes managed (count + student total).
- QCMs created (total + published).
- Total attempts on own QCMs.
- Average score on own QCMs.

**Widgets:**
- Class list with student counts.
- Recent QCMs with attempt stats.
- Quick action: Create QCM.

### 3. Student Dashboard
**KPIs:**
- QCMs completed.
- Average personal score.
- QCMs in progress.
- Success rate (% of passed QCMs).

**Widgets:**
- Recent attempts (last 5).
- Available QCMs (to do).
- In-progress QCMs (resume).
- Skill progression chart (by compétence).

## Implementation Patterns

### KPI Calculation
```php
// DashboardService::getAdminKpis(): array
public function getAdminKpis(): array
{
    return [
        'total_users' => User::count(),
        'users_by_role' => User::selectRaw('type_profil, COUNT(*) as count')
            ->groupBy('type_profil')
            ->pluck('count', 'type_profil'),
        'total_classes' => Classe::count(),
        'total_qcms' => Qcm::count(),
        'qcms_by_status' => Qcm::selectRaw('est_publie, COUNT(*) as count')
            ->groupBy('est_publie')
            ->pluck('count', 'est_publie'),
        'attempts_this_month' => Tentative::whereMonth('created_at', now()->month)->count(),
        'average_score' => Tentative::where('statut', 'termine')->avg('score_obtenu'),
    ];
}
```

### Chart Integration
- Use Chart.js or Alpine.js charts.
- Score distribution histogram.
- Attempt trend line (7/30 days).
- Competence radar chart for students.

### Alpine.js Widgets
```html
<!-- KPI Card -->
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-slate-500">QCMs Complétés</p>
            <p class="text-2xl font-bold" x-text="kpis.completed_qcms">0</p>
        </div>
        <div class="p-3 bg-indigo-100 rounded-full">
            <svg class="w-6 h-6 text-indigo-600">...</svg>
        </div>
    </div>
    <div class="mt-4 flex items-center text-sm">
        <span class="text-emerald-500 font-medium">+12%</span>
        <span class="text-slate-400 ml-2">vs mois dernier</span>
    </div>
</div>
```

## Output
- Role-specific dashboard controllers.
- KPI calculation services.
- Alpine.js powered widgets.
- Chart visualizations.
