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
        
        $formateurs = $this->userService->getFormateurs();
        $availableStudents = $this->userService->getAvailableStudents();

        return view('admin.classes', compact('classes', 'search', 'formateurs', 'availableStudents'));
    }

    public function show($id)
    {
        $details = $this->classeService->getDetailsWithStats($id);
        
        $classe = $details['classe'];
        $tauxEngagement = $details['tauxEngagement'];
        $moyenneGlobale = $details['moyenneGlobale'];
        $etudiantsSansClasse = $details['etudiantsSansClasse'];

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

        $this->classeService->bulkAddStudents($classe, $request->user_ids);

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

        $result = $this->classeService->importStudents($classe, $request->import_data);

        $createdCount = $result['createdCount'];
        $addedCount = $result['addedCount'];
        $errors = $result['errors'];

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
