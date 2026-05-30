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

    // Statuts : 'en_cours' | 'reussi' | 'echoue' | 'abandonne'
    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'score_obtenu' => 'decimal:1',
    ];

    public function isReussi(): bool
    {
        return $this->statut === 'reussi';
    }
    public function isEchoue(): bool
    {
        return $this->statut === 'echoue';
    }
    public function isEnCours(): bool
    {
        return $this->statut === 'en_cours';
    }
    public function isAbandonne(): bool
    {
        return $this->statut === 'abandonne';
    }

    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'etudiant_id');
    }

    public function qcm(): BelongsTo
    {
        return $this->belongsTo(QCM::class, 'qcm_id');
    }

    public function reponses(): HasMany
    {
        return $this->hasMany(Reponse::class);
    }
}
