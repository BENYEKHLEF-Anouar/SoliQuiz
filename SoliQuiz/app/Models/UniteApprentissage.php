<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Diagramme: UniteApprentissage { id, nom, code }
// Relation: Session "1" -- "0..*" UniteApprentissage : inclut → seance_id FK ici
class UniteApprentissage extends Model
{
    protected $table = 'unites_apprentissage';

    protected $fillable = ['seance_id', 'nom', 'code'];

    public function seance(): BelongsTo
    {
        return $this->belongsTo(Seance::class);
    }

    public function competences(): HasMany
    {
        return $this->hasMany(Competence::class);
    }

    public function qcms(): HasMany
    {
        return $this->hasMany(QCM::class);
    }
}
