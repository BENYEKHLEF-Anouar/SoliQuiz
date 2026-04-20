<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Competence extends Model
{
    protected $fillable = ['unite_apprentissage_id', 'code', 'libelle', 'description'];

    public function uniteApprentissage(): BelongsTo
    {
        return $this->belongsTo(UniteApprentissage::class);
    }

    /** Les QCM qui évaluent cette compétence */
    public function qcms(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(QCM::class, 'competence_qcm');
    }
}
