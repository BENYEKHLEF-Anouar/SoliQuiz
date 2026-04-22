<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'qcm_id',
        'texte',
        'type',
        'points',
        'explication_feedback',
    ];

    protected $casts = [
        'points' => 'decimal:1',
    ];

    public function qcm(): BelongsTo
    {
        return $this->belongsTo(QCM::class, 'qcm_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    public function reponses(): HasMany
    {
        return $this->hasMany(Reponse::class);
    }
}
