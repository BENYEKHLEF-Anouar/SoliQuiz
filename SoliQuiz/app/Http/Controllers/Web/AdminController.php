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
        // Pass null as formateurId to get everything
        $qcms = $this->qcmService->paginate(15, $search, null);
        return view('admin.qcms', compact('qcms', 'search'));
    }

    public function searchQcms(Request $request)
    {
        $search = $request->input('search');
        $qcms = $this->qcmService->paginate(15, $search, null);
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
        
        return view('admin.dashboard', compact('kpis', 'topQcms', 'recentTentatives', 'topPerformers'));
    }

    /**
     * Affiche la liste des utilisateurs.
     */
    public function gestionUtilisateurs(Request $request)
    {
        $search = $request->input('search');
        $users = $search
            ? User::where(function ($q) use ($search) {
                  $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('type_profil', 'like', "%{$search}%");
              })->get()
            : User::all();
        return view('admin.gestion-utilisateurs', compact('users', 'search'));
    }

    public function searchUsers(Request $request)
    {
        $search = $request->input('search');
        $users = $search
            ? User::where(function ($q) use ($search) {
                  $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('type_profil', 'like', "%{$search}%");
              })->get()
            : User::all();
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
            'password' => 'required|string|min:8',
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
            'password' => $request->password,
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
    
    public function pedagogie()
    {
        // On charge l'arborescence complète pour l'affichage master-detail
        $seances = Seance::with(['unitesApprentissage.competences'])->orderBy('date', 'desc')->get();
        return view('admin.pedagogie', compact('seances'));
    }

    public function storeSeance(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'date' => 'required|date'
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
            'code' => 'required|string|max:50'
        ]);
        $this->seanceService->addUniteApprentissage($seance, $data);
        return redirect()->route('admin.pedagogie')->with('success', 'Unité d\'apprentissage ajoutée.');
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
        $this->seanceService->addCompetence($ua, $data);
        return redirect()->route('admin.pedagogie')->with('success', 'Compétence ajoutée.');
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
            'date' => 'required|date'
        ]);
        $seance->update($data);
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
            'code' => 'required|string|max:50|unique:unites_apprentissage,code,' . $id,
        ]);
        $ua->update($data);
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
            'code' => 'required|string|max:50|unique:competences,code,' . $id,
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);
        $competence->update($data);
        return redirect()->route('admin.pedagogie')->with('success', 'Compétence mise à jour.');
    }

    /**
     * =============== GESTION DES CLASSES ===============
     */

    public function gestionClasses(Request $request)
    {
        $search = $request->input('search');
        $classesQuery = Classe::with(['formateur', 'etudiants'])->withCount('etudiants');
        
        if ($search) {
            $classesQuery->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('promotion', 'like', "%{$search}%");
            });
        }
        
        $classes = $classesQuery->get();
        // Utilisation du type_profil pour plus de robustesse par rapport aux rôles Spatie
        $formateurs = User::where('type_profil', 'formateur')->orderBy('nom')->get();
        
        return view('admin.classes', compact('classes', 'formateurs', 'search'));
    }

    public function searchClasses(Request $request)
    {
        $search = $request->input('search');
        $classesQuery = Classe::with(['formateur', 'etudiants'])->withCount('etudiants');
        
        if ($search) {
            $classesQuery->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('promotion', 'like', "%{$search}%");
            });
        }
        
        $classes = $classesQuery->get();
        return response()->json($classes);
    }

    public function storeClasse(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'promotion' => 'nullable|string|max:50'
        ]);
        $this->classeService->create($data);
        return redirect()->route('admin.classes')->with('success', 'Classe créée avec succès.');
    }

    public function destroyClasse($id)
    {
        $classe = Classe::findOrFail($id);
        $this->classeService->delete($classe);
        return redirect()->route('admin.classes')->with('success', 'Classe supprimée.');
    }

    public function assignFormateur(Request $request, $id)
    {
        $classe = Classe::findOrFail($id);
        $request->validate([
            'formateur_id' => 'required|exists:users,id'
        ]);
        $this->classeService->assignFormateur($classe, $request->formateur_id);
        return redirect()->route('admin.classes')->with('success', 'Formateur assigné avec succès à la classe.');
    }
    /**
     * Affiche le détail d'une classe et ses membres.
     */
    public function showClasse($id)
    {
        $classe = Classe::with(['formateur', 'etudiants'])->findOrFail($id);
        // On récupère les étudiants qui n'ont pas encore de classe pour pouvoir les ajouter
        // On utilise type_profil 'etudiant' pour correspondre au modèle User
        $etudiantsSansClasse = User::where('type_profil', 'etudiant')
            ->whereNull('classe_id')
            ->orderBy('nom')
            ->get();
        
        return view('admin.classes-show', compact('classe', 'etudiantsSansClasse'));
    }

    /**
     * Ajoute un étudiant à une classe.
     */
    public function addStudentToClasse(Request $request, $id)
    {
        $classe = Classe::findOrFail($id);
        $request->validate(['user_id' => 'required|exists:users,id']);
        
        $this->classeService->addStudent($classe, $request->user_id);
        return redirect()->route('admin.classes.show', $id)->with('success', 'Étudiant ajouté à la classe.');
    }

    /**
     * Retire un étudiant d'une classe.
     */
    public function removeStudentFromClasse($id, $userId)
    {
        $this->classeService->removeStudent($userId);
        return redirect()->route('admin.classes.show', $id)->with('success', 'Étudiant retiré de la classe.');
    }
}

