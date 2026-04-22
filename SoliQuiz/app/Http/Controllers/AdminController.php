<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Services\UserService;
use App\Services\PedagogieService;
use App\Services\QcmService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    protected $dashboardService;
    protected $userService;
    protected $pedagogieService;
    protected $qcmService;

    public function __construct(
        DashboardService $dashboardService,
        UserService $userService,
        PedagogieService $pedagogieService,
        QcmService $qcmService
    ) {
        $this->dashboardService = $dashboardService;
        $this->userService = $userService;
        $this->pedagogieService = $pedagogieService;
        $this->qcmService = $qcmService;
    }

    /**
     * Affiche le shell principal de l'admin
     */
    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'dashboard');
        $search = $request->query('search');
        $profil = $request->query('profil');
        $qcmSearch = $request->query('qcm_search');

        $kpis = $this->dashboardService->getKpis();
        $users = $this->userService->paginate(10, $search, $profil);
        $structure = $this->pedagogieService->getFullStructure();
        $qcms = $this->qcmService->paginate(10, $qcmSearch);
        $classes = \App\Models\Classe::orderBy('nom')->get();

        return view('admin.index', compact('kpis', 'users', 'structure', 'qcms', 'classes', 'tab', 'search', 'profil', 'qcmSearch'));
    }

    // JSON API for SPA updates could go here or in separate controllers
}
