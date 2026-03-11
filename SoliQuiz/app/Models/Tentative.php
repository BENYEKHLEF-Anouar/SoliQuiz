<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tentative extends Model
{
    protected $fillable = [
        'etudiant_id',
        'qcm_id',
        'score_obtenu',
        'statut',
        'date_debut',
        'date_fin',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin'   => 'datetime',
    ];

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'etudiant_id');
    }

    public function qcm(): BelongsTo
    {
        return $this->belongsTo(QCM::class);
    }

    public function reponses(): HasMany
    {
        return $this->hasMany(Reponse::class);
    }
}
