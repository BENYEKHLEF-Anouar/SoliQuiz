<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'matricule',        // Formateur uniquement
        'code_etudiant',    // Etudiant uniquement
        'classe_id',        // Etudiant uniquement
        'type_profil',      // 'admin' | 'formateur' | 'etudiant'
        'derniere_connexion',
    ];

    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    public function getRoleAttribute(): string
    {
        return $this->type_profil;
    }

    protected $appends = [
        'nom_complet',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'derniere_connexion' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ─── Helpers de profil ───────────────────────────────
    public function isFormateur(): bool
    {
        return $this->type_profil === 'formateur';
    }
    public function isEtudiant(): bool
    {
        return $this->type_profil === 'etudiant';
    }
    public function isAdmin(): bool
    {
        return $this->type_profil === 'admin';
    }

    // ─── Relations Formateur ─────────────────────────────

    /** La classe que gère ce formateur */
    public function classeGeree(): HasMany
    {
        return $this->hasMany(Classe::class, 'formateur_id');
    }

    /** Les QCM créés par ce formateur */
    public function qcms(): HasMany
    {
        return $this->hasMany(QCM::class, 'formateur_id');
    }

    /** Les sessions créées par ce formateur */
    public function seances(): HasMany
    {
        return $this->hasMany(Seance::class);
    }

    /** Les unités d'apprentissage créées par ce formateur */
    public function unitesApprentissage(): HasMany
    {
        return $this->hasMany(UniteApprentissage::class);
    }

    // ─── Relations Etudiant ──────────────────────────────

    /** La classe de l'étudiant */
    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    /** Les tentatives de l'étudiant */
    public function tentatives(): HasMany
    {
        return $this->hasMany(Tentative::class, 'etudiant_id');
    }
}
