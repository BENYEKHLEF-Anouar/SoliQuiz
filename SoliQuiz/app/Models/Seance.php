<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Diagramme: Session { id, nom, date }
// Renommée Seance pour éviter le conflit avec la facade Laravel Session
class Seance extends Model
{
    protected $table = 'seances';

    protected $fillable = ['nom', 'date', 'user_id'];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function unitesApprentissage(): HasMany
    {
        return $this->hasMany(UniteApprentissage::class);
    }
}
