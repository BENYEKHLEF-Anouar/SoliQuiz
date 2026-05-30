<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reponse extends Model
{
    protected $fillable = [
        'tentative_id',
        'question_id',
        'repondu_a',
    ];

    protected $casts = [
        'repondu_a' => 'datetime',
    ];

    public function tentative(): BelongsTo
    {
        return $this->belongsTo(Tentative::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function choixReponses(): HasMany
    {
        return $this->hasMany(ChoixReponse::class);
    }

    // Récupère les options choisies directement
    public function optionsChoisies()
    {
        return $this->hasManyThrough(Option::class, ChoixReponse::class, 'reponse_id', 'id', 'id', 'option_id');
    }
}
