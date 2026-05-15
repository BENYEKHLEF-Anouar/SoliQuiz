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

    public function __construct(ClasseService $classeService)
    {
        $this->classeService = $classeService;
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
     * API pour la recherche asynchrone (Alpine.js)
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        $classes = $this->classeService->paginate(9, $search);
        return response()->json($classes);
    }
}
