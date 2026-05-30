<?php

namespace App\Observers;

use App\Models\Tentative;
use App\Models\QCM;

class TentativeObserver
{
    /**
     * Handle the Tentative "updated" event.
     * Check if all students have completed the QCM and auto-close it.
     */
    public function updated(Tentative $tentative): void
    {
        // Only check when the tentative is completed (has a final status and score)
        if ($tentative->wasChanged('statut') && in_array($tentative->statut, ['reussi', 'echoue', 'abandonne'])) {
            $this->checkAndAutoCloseQcm($tentative->qcm);
        }
    }

    /**
     * Handle the Tentative "created" event.
     */
    public function created(Tentative $tentative): void
    {
        // If created with a final status, check for auto-close
        if (in_array($tentative->statut, ['reussi', 'echoue', 'abandonne'])) {
            $this->checkAndAutoCloseQcm($tentative->qcm);
        }
    }

    /**
     * Check if all students in the target class have completed the QCM,
     * and auto-close it if so.
     */
    private function checkAndAutoCloseQcm(QCM $qcm): void
    {
        // Only check for public QCMs that aren't already closed
        if ($qcm->statut !== 'public' || !$qcm->classe_id) {
            return;
        }

        // Get total students in the class
        $totalStudents = $qcm->classe->etudiants()->count();

        // Get unique students who have completed this QCM
        $completedStudents = $qcm->tentatives()
            ->whereIn('statut', ['reussi', 'echoue', 'abandonne'])
            ->distinct('etudiant_id')
            ->count('etudiant_id');

        // If all students have completed, auto-close the QCM
        if ($completedStudents >= $totalStudents && $totalStudents > 0) {
            $qcm->update(['statut' => 'termine']);
        }
    }
}
