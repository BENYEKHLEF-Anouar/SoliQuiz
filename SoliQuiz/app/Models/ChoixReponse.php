<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChoixReponse extends Model
{
    protected $table = 'choix_reponses';

    protected $fillable = ['reponse_id', 'option_id'];

    public function reponse(): BelongsTo
    {
        return $this->belongsTo(Reponse::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }
}
