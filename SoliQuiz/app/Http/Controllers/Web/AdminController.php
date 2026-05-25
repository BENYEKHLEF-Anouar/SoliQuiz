<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use App\Services\DashboardService;
use App\Services\SeanceService;
use App\Services\ClasseService;
use App\Models\Classe;
use App\Models\Seance;
use App\Models\UniteApprentissage;
use App\Models\Competence;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    private UserService $userService;
    private DashboardService $dashboardService;
    private SeanceService $seanceService;
    private ClasseService $classeService;
    private \App\Services\QcmService $qcmService;

    public function __construct(
        UserService $userService, 
        DashboardService $dashboardService, 
        SeanceService $seanceService, 
        ClasseService $classeService,
        \App\Services\QcmService $qcmService
    )
    {
        $this->userService = $userService;
        $this->dashboardService = $dashboardService;
        $this->seanceService = $seanceService;
        $this->classeService = $classeService;
        $this->qcmService = $qcmService;
    }

    /**
     * Affiche la banque de tous les QCMs (Admin only)
     */
    public function indexQcms(Request $request)
    {
        $search = $request->input('search');
        $statut = $request->input('statut');
        $formateurId = $request->input('formateur_id');
        
        $qcms = $this->qcmService->paginate(15, $search, $formateurId ? (int)$formateurId : null, $statut);
        $qcms->appends($request->query());
        
        $formateurs = User::where('type_profil', 'formateur')->orderBy('nom')->get();
        
        return view('admin.qcms', compact('qcms', 'search', 'statut', 'formateurId', 'formateurs'));
    }

    public function searchQcms(Request $request)
    {
        $search = $request->input('search');
        $statut = $request->input('statut');
        $formateurId = $request->input('formateur_id');
        $qcms = $this->qcmService->paginate(15, $search, $formateurId ? (int)$formateurId : null, $statut);
        return response()->json($qcms);
    }

    /**
     * Affiche l'écran d'accueil du tableau de bord Admin
     */
    public function dashboard()
    {
        $kpis = $this->dashboardService->getKpis();
        $topQcms = $this->dashboardService->getTopQcms(5);
        $recentTentatives = $this->dashboardService->getRecentTentatives(10);
        $topPerformers = $this->dashboardService->getTopPerformers(3);
        $systemStatus = $this->dashboardService->getSystemStatus();
        $classes = $this->dashboardService->getAllClassesMetrics();
        
        return view('admin.dashboard', compact('kpis', 'topQcms', 'recentTentatives', 'topPerformers', 'systemStatus', 'classes'));
    }
    
    /**
     * Affiche les résultats des étudiants pour une classe sélectionnée (Admin)
     */
    public function resultats(Request $request)
    {
        $classes = Classe::orderBy('nom')->get();
        $selectedClasseId = $request->input('classe_id') ?: ($classes->first()?->id ?? null);
        
        $selectedClasse = $selectedClasseId ? Classe::with('etudiants')->find($selectedClasseId) : null;
        
        $qcms = $selectedClasseId 
            ? \App\Models\QCM::where('classe_id', $selectedClasseId)
                ->where('statut', '!=', 'brouillon')
                ->with(['tentatives.etudiant', 'uniteApprentissage'])
                ->latest()
                ->get()
            : collect();
            
        $etudiants = $selectedClasse 
            ? $selectedClasse->etudiants->map(fn($e) => [
                'id' => $e->id,
                'name' => $e->prenom . ' ' . $e->nom
            ])->sortBy('name')->values()
            : collect();
            
        return view('admin.resultats', compact('classes', 'selectedClasseId', 'selectedClasse', 'qcms', 'etudiants'));
    }

    /**
     * Affiche la liste des utilisateurs.
     */
    public function gestionUtilisateurs(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');

        $users = $this->userService->searchUsersWithRelations($search, $role)
            ->appends($request->query());

        $totalEtudiants = User::where('type_profil', 'etudiant')->count();
        $totalFormateurs = User::where('type_profil', 'formateur')->count();
        $totalAdmins = User::where('type_profil', 'admin')->count();
        
        return view('admin.gestion-utilisateurs', compact('users', 'search', 'role', 'totalEtudiants', 'totalFormateurs', 'totalAdmins'));
    }

    public function searchUsers(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');

        $users = $this->userService->searchUsersWithRelations($search, $role)
            ->appends($request->query());
        
        return response()->json($users);
    }

    /**
     * Enregistre un nouvel utilisateur.
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:Apprenant,Formateur,Administrateur',
            'password' => 'nullable|string|min:8',
            'classe_id' => 'nullable|exists:classes,id',
        ]);

        $roleMapping = [
            'Administrateur' => 'admin',
            'Formateur' => 'formateur',
            'Apprenant' => 'etudiant'
        ];

        $this->userService->create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => $request->password ?: 'password',
            'type_profil' => $roleMapping[$request->role],
            'classe_id' => $request->classe_id,
        ]);

        return redirect()->route('admin.utilisateurs')->with('success', 'Utilisateur créé avec succès !');
    }

    /**
     * Supprime un utilisateur.
     */
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.utilisateurs')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $this->userService->delete($user);
        
        return redirect()->route('admin.utilisateurs')->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Met à jour un utilisateur existant.
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'role' => 'required|in:Apprenant,Formateur,Administrateur',
            'password' => 'nullable|string|min:8',
            'classe_id' => 'nullable|exists:classes,id',
        ]);

        $roleMapping = [
            'Administrateur' => 'admin',
            'Formateur' => 'formateur',
            'Apprenant' => 'etudiant'
        ];

        $this->userService->update($user, [
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => $request->password,
            'type_profil' => $roleMapping[$request->role],
            'classe_id' => $request->classe_id,
        ]);

        return redirect()->route('admin.utilisateurs')->with('success', 'Utilisateur mis à jour avec succès !');
    }

    /**
     * =============== PEDAGOGIE (Seances, UA, Competences) ===============
     */
    
    public function pedagogie(Request $request)
    {
        $creatorFilter = $request->input('creator');

        $seances = $this->seanceService->getSeancesWithRelations($creatorFilter ? (int)$creatorFilter : null);

        $creatorIds = Seance::whereNotNull('user_id')->distinct()->pluck('user_id');
        $creators = User::whereIn('id', $creatorIds)->orderBy('nom')->get();
        
        // Liste de tous les formateurs pour l'assignation
        $formateurs = User::where('type_profil', 'formateur')->orderBy('nom')->get();

        return view('admin.pedagogie', compact('seances', 'creators', 'creatorFilter', 'formateurs'));
    }

    public function storeSeance(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date',
        ]);
        
        $this->seanceService->create($data);
        return redirect()->route('admin.pedagogie')->with('success', 'Séance créée avec succès.');
    }

    public function destroySeance($id)
    {
        $seance = Seance::findOrFail($id);
        $this->seanceService->delete($seance);
        return redirect()->route('admin.pedagogie')->with('success', 'Séance supprimée.');
    }

    public function storeUA(Request $request, $seanceId)
    {
        $seance = Seance::findOrFail($seanceId);
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'user_id' => 'required|exists:users,id',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date',
        ]);
        
        $codeExists = UniteApprentissage::where('code', $data['code'])->exists();
        
        $this->seanceService->addUniteApprentissage($seance, $data);
        
        $redirect = redirect()->route('admin.pedagogie')->with('success', 'Unité d\'apprentissage ajoutée.');
        if ($codeExists) {
            $redirect->with('code_warning', 'Attention : Le code de l\'UA est déjà utilisé.');
        }
        return $redirect;
    }

    public function destroyUA($id)
    {
        $ua = UniteApprentissage::findOrFail($id);
        $this->seanceService->deleteUniteApprentissage($ua);
        return redirect()->route('admin.pedagogie')->with('success', 'Unité d\'apprentissage supprimée.');
    }

    public function storeCompetence(Request $request, $uaId)
    {
        $ua = UniteApprentissage::findOrFail($uaId);
        $data = $request->validate([
            'code' => 'required|string|max:50',
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);
        
        $codeExists = Competence::where('code', $data['code'])->exists();
        
        $this->seanceService->addCompetence($ua, $data);
        
        $redirect = redirect()->route('admin.pedagogie')->with('success', 'Compétence ajoutée.');
        if ($codeExists) {
            $redirect->with('code_warning', 'Attention : Le code de la compétence est déjà utilisé.');
        }
        return $redirect;
    }

    public function destroyCompetence($id)
    {
        $competence = Competence::findOrFail($id);
        $this->seanceService->deleteCompetence($competence);
        return redirect()->route('admin.pedagogie')->with('success', 'Compétence supprimée.');
    }

    /**
     * Show edit form for Seance (Admin)
     */
    public function editSeance($id)
    {
        $seance = Seance::findOrFail($id);
        return response()->json($seance);
    }

    /**
     * Update Seance (Admin)
     */
    public function updateSeance(Request $request, $id)
    {
        $seance = Seance::findOrFail($id);
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date',
        ]);
        $this->seanceService->update($seance, $data);
        return redirect()->route('admin.pedagogie')->with('success', 'Séance mise à jour.');
    }

    /**
     * Show edit form for UA (Admin)
     */
    public function editUA($id)
    {
        $ua = UniteApprentissage::findOrFail($id);
        return response()->json($ua);
    }

    /**
     * Update UA (Admin)
     */
    public function updateUA(Request $request, $id)
    {
        $ua = UniteApprentissage::findOrFail($id);
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'user_id' => 'required|exists:users,id',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date',
        ]);
        $this->seanceService->updateUniteApprentissage($ua, $data);
        return redirect()->route('admin.pedagogie')->with('success', 'Unité d\'apprentissage mise à jour.');
    }

    /**
     * Show edit form for Competence (Admin)
     */
    public function editCompetence($id)
    {
        $competence = Competence::findOrFail($id);
        return response()->json($competence);
    }

    /**
     * Update Competence (Admin)
     */
    public function updateCompetence(Request $request, $id)
    {
        $competence = Competence::findOrFail($id);
        $data = $request->validate([
            'code' => 'required|string|max:50',
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);
        $this->seanceService->updateCompetence($competence, $data);
        return redirect()->route('admin.pedagogie')->with('success', 'Compétence mise à jour.');
    }

    /**
     * =============== GESTION DES CLASSES (MIGRÉ VERS ClasseController) ===============
     */
}

