<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\LeaderboardService;
use App\Models\Classe;
use App\Models\QCM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    private LeaderboardService $leaderboardService;

    public function __construct(LeaderboardService $leaderboardService)
    {
        $this->leaderboardService = $leaderboardService;
    }

    /**
     * Affiche le tableau de classement.
     */
    public function show(Request $request)
    {
        $user = Auth::user();
        $classeId = $request->input('classe_id');
        $qcmId = $request->input('qcm_id');

        $classes = collect();
        $qcms = collect();

        if ($user->isAdmin()) {
            $classes = Classe::orderBy('nom')->get();
            $qcms = $this->leaderboardService->getQcmsForFilter($classeId ?: null);
        } elseif ($user->isFormateur()) {
            $classes = Classe::where('formateur_id', $user->id)->orderBy('nom')->get();
            if ($classeId && !$classes->contains('id', $classeId)) {
                $classeId = null;
            }
            $qcms = $this->leaderboardService->getQcmsForFilter($classeId ?: null, $user->id);
        } else {
            // Étudiant : classe fixe
            $classeId = $user->classe_id;
            $qcms = $this->leaderboardService->getQcmsForFilter($classeId);
        }

        // Si un QCM particulier est sélectionné
        if ($qcmId) {
            $rankings = $this->leaderboardService->getQcmRanking((int)$qcmId, 10);
            $selectedQcm = QCM::find($qcmId);
        } else {
            // Classement général
            $rankings = $this->leaderboardService->getOverallRanking(
                $classeId ? (int)$classeId : null,
                ($user->isFormateur() && !$classeId) ? $user->id : null,
                10
            );
            $selectedQcm = null;
        }

        $selectedClasse = $classeId ? Classe::find($classeId) : null;

        // Choix de la vue selon le rôle
        if ($user->isAdmin()) {
            return view('admin.leaderboard', compact('rankings', 'classes', 'qcms', 'classeId', 'qcmId', 'selectedQcm', 'selectedClasse'));
        } elseif ($user->isFormateur()) {
            return view('formateur.leaderboard', compact('rankings', 'classes', 'qcms', 'classeId', 'qcmId', 'selectedQcm', 'selectedClasse'));
        } else {
            return view('etudiant.leaderboard', compact('rankings', 'qcms', 'qcmId', 'selectedQcm', 'selectedClasse'));
        }
    }
}
