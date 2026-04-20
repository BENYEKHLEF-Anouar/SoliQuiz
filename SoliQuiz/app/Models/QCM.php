<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QCM extends Model
{
    protected $table = 'qcms';

    protected $fillable = [
        'formateur_id',
        'unite_apprentissage_id',
        'classe_id',
        'titre',
        'duree_minutes',
        'score_reussite',
        'statut',
    ];

    protected $casts = [
        'statut' => 'string',
    ];

    public function formateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'formateur_id');
    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function uniteApprentissage(): BelongsTo
    {
        return $this->belongsTo(UniteApprentissage::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'qcm_id');
    }

    public function tentatives(): HasMany
    {
        return $this->hasMany(Tentative::class, 'qcm_id');
    }

    /** Les compétences spécifiques évaluées par ce QCM */
    public function competences(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Competence::class, 'competence_qcm');
    }
}
