<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\User;
use App\Services\ClasseService;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    protected $classeService;
    protected $userService;

    public function __construct(ClasseService $classeService, \App\Services\UserService $userService)
    {
        $this->classeService = $classeService;
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $classes = $this->classeService->paginate(9, $search);
        
        // Pour les formulaires de création/édition
        $formateurs = User::where('type_profil', 'formateur')->orderBy('nom')->get();
        $availableStudents = User::where('type_profil', 'etudiant')
            ->whereNull('classe_id')
            ->orderBy('nom')
            ->get();

        return view('admin.classes', compact('classes', 'search', 'formateurs', 'availableStudents'));
    }

    public function show($id)
    {
        $classe = Classe::with(['formateur', 'etudiants.tentatives'])->findOrFail($id);
        
        $totalEtudiants = $classe->etudiants->count();
        $activeStudents = $classe->etudiants->filter(fn($e) => $e->tentatives->count() > 0)->count();
        $tauxEngagement = $totalEtudiants > 0 ? round(($activeStudents / $totalEtudiants) * 100, 1) : 0;

        $allScores = $classe->etudiants->flatMap->tentatives->whereNotNull('score_obtenu')->pluck('score_obtenu');
        $moyenneGlobale = $allScores->count() > 0 ? round($allScores->avg(), 1) : null;
        
        $etudiantsSansClasse = User::where('type_profil', 'etudiant')
            ->whereNull('classe_id')
            ->orderBy('nom')
            ->get();

        return view('admin.classes-show', compact('classe', 'etudiantsSansClasse', 'tauxEngagement', 'moyenneGlobale'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'promotion' => 'nullable|string|max:255',
            'formateur_id' => 'nullable|exists:users,id',
        ]);

        $this->classeService->create($validated);

        return redirect()->route('admin.classes')
            ->with('success', 'La classe a été créée avec succès.');
    }

    public function update(Request $request, Classe $classe)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'promotion' => 'nullable|string|max:255',
            'formateur_id' => 'nullable|exists:users,id',
        ]);

        $this->classeService->update($classe, $validated);

        return redirect()->route('admin.classes')
            ->with('success', 'La classe a été mise à jour.');
    }

    public function destroy(Classe $classe)
    {
        $this->classeService->delete($classe);
        return redirect()->route('admin.classes')
            ->with('success', 'La classe a été supprimée.');
    }

    /**
     * Assigne un formateur à une classe
     */
    public function assignFormateur(Request $request, $id)
    {
        $classe = Classe::findOrFail($id);
        $request->validate(['formateur_id' => 'required|exists:users,id']);
        
        $this->classeService->assignFormateur($classe, $request->formateur_id);
        
        return back()->with('success', 'Le formateur a été assigné.');
    }

    /**
     * Ajoute un étudiant à une classe
     */
    public function addStudent(Request $request, Classe $classe)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);
        $this->classeService->addStudent($classe, $request->user_id);
        
        return back()->with('success', 'L\'étudiant a été ajouté à la classe.');
    }

    /**
     * Retire un étudiant d'une classe
     */
    public function removeStudent($id, $userId)
    {
        $this->classeService->removeStudent($userId);
        return back()->with('success', 'L\'étudiant a été retiré de la classe.');
    }

    /**
     * Ajoute plusieurs étudiants à une classe
     */
    public function bulkAddStudents(Request $request, Classe $classe)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        foreach ($request->user_ids as $userId) {
            $this->classeService->addStudent($classe, $userId);
        }

        return back()->with('success', count($request->user_ids) . ' étudiants ont été ajoutés à la classe.');
    }

    /**
     * Importation en masse d'étudiants (création / affectation)
     */
    public function importStudents(Request $request, Classe $classe)
    {
        $request->validate([
            'import_data' => 'required|string',
        ]);

        $lines = explode("\n", $request->import_data);
        $addedCount = 0;
        $createdCount = 0;
        $errors = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            // Parse formats: email OR firstname;lastname;email OR email;firstname;lastname
            $parts = preg_split('/[;,]/', $line);
            $parts = array_map('trim', $parts);

            $email = null;
            $nom = 'Étudiant';
            $prenom = 'Nouvel';

            if (count($parts) === 1) {
                $email = $parts[0];
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $prefix = explode('@', $email)[0];
                    $nameParts = explode('.', $prefix);
                    if (count($nameParts) >= 2) {
                        $prenom = ucfirst($nameParts[0]);
                        $nom = ucfirst($nameParts[1]);
                    } else {
                        $nom = ucfirst($prefix);
                        $prenom = 'Apprenant';
                    }
                }
            } elseif (count($parts) >= 3) {
                if (filter_var($parts[2], FILTER_VALIDATE_EMAIL)) {
                    $prenom = $parts[0];
                    $nom = $parts[1];
                    $email = $parts[2];
                } elseif (filter_var($parts[0], FILTER_VALIDATE_EMAIL)) {
                    $email = $parts[0];
                    $prenom = $parts[1];
                    $nom = $parts[2];
                }
            } elseif (count($parts) === 2) {
                if (filter_var($parts[1], FILTER_VALIDATE_EMAIL)) {
                    $email = $parts[1];
                    $nom = $parts[0];
                } elseif (filter_var($parts[0], FILTER_VALIDATE_EMAIL)) {
                    $email = $parts[0];
                    $nom = $parts[1];
                }
            }

            if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Ligne invalide ou email incorrect : " . htmlspecialchars($line);
                continue;
            }

            $user = User::where('email', $email)->first();

            if ($user) {
                if ($user->type_profil === 'etudiant') {
                    $user->update(['classe_id' => $classe->id]);
                    $addedCount++;
                } else {
                    $errors[] = "L'utilisateur avec l'email {$email} existe déjà et n'est pas un étudiant (rôle: {$user->type_profil}).";
                }
            } else {
                $this->userService->create([
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'password' => 'password',
                    'type_profil' => 'etudiant',
                    'classe_id' => $classe->id,
                ]);
                $createdCount++;
            }
        }

        $message = "Importation terminée.";
        if ($createdCount > 0 || $addedCount > 0) {
            $message .= " {$createdCount} nouveaux étudiants créés et {$addedCount} étudiants existants affectés.";
        }

        if (count($errors) > 0) {
            return back()->with('success', $message)->withErrors($errors);
        }

        return back()->with('success', $message);
    }

    /**
     * API pour la recherche asynchrone (Alpine.js)
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        $classes = $this->classeService->paginate(9, $search);
        return response()->json($classes);
    }
}
