<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Option extends Model
{
    protected $fillable = [
        'question_id',
        'texte',
        'est_correcte',
        'feedback_specifique',
    ];

    protected $casts = [
        'est_correcte' => 'boolean',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function choixReponses(): HasMany
    {
        return $this->hasMany(ChoixReponse::class);
    }
}
