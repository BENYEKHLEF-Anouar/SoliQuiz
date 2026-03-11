<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Diagramme: Session { id, nom, date }
// Renommée Seance pour éviter le conflit avec la facade Laravel Session
class Seance extends Model
{
    protected $table = 'seances';

    protected $fillable = ['nom', 'date'];

    protected $casts = [
        'date' => 'date',
    ];

    public function unitesApprentissage(): HasMany
    {
        return $this->hasMany(UniteApprentissage::class);
    }
}
