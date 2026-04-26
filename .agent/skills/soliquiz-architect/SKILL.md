---
name: soliquiz-architect
description: Database architecture, migrations, models, and Eloquent relationships for SoliQuiz.
---

# 🏗️ SOLIQUIZ ARCHITECT

## Domain
Database schema design, migrations, Eloquent models, and relationships for the SoliQuiz platform.

## Capabilities

### 1. Database Architecture
- **Entity Hierarchy:** Seance → UA → Competence → QCM → Question → Option
- **User Management:** Users, Classes, Role-based access (Spatie)
- **Assessment:** Tentatives, Réponses, Choix
- **Relationships:** All Eloquent relationships defined

### 2. Migrations
Order of creation:
1. `users` — name, email, password, type_profil, matricule, code_etudiant, classe_id
2. `classes` — nom, description, formateur_id (FK users)
3. `seances` — nom, date, user_id (FK users)
4. `unites_apprentissage` — nom, code, seance_id (FK), user_id (FK users)
5. `competences` — libelle, code, unite_apprentissage_id (FK)
6. `qcms` — titre, duree_minutes, score_reussite (decimal 5,1), statut, formateur_id (FK), unite_apprentissage_id (FK), classe_id (FK)
7. `competence_qcm` — pivot (competence_id, qcm_id)
8. `questions` — texte, type (choix_unique|choix_multiple), points, explication_feedback, qcm_id (FK)
9. `options` — texte, est_correcte (boolean), feedback_specifique, question_id (FK)
10. `tentatives` — date_debut, date_fin, score_obtenu (decimal 5,1), statut, etudiant_id (FK), qcm_id (FK)
11. `reponses` — tentative_id (FK), question_id (FK)
12. `choix_reponses` — reponse_id (FK), option_id (FK)
13. Spatie permission tables
14. `personal_access_tokens` (Sanctum)

### 3. Eloquent Models & Relationships

```php
// Seance → UA → Competence
class Seance extends Model {
    public function uniteApprentissages(): HasMany {
        return $this->hasMany(UniteApprentissage::class)->orderBy('code');
    }
}

class UniteApprentissage extends Model {
    public function seance(): BelongsTo {
        return $this->belongsTo(Seance::class);
    }
    public function competences(): HasMany {
        return $this->hasMany(Competence::class)->orderBy('code');
    }
}

class Competence extends Model {
    public function uniteApprentissage(): BelongsTo {
        return $this->belongsTo(UniteApprentissage::class);
    }
    public function qcms(): HasMany {
        return $this->hasMany(Qcm::class);
    }
}

// Classe & Users
class Classe extends Model {
    protected $fillable = ['nom', 'promotion', 'formateur_id'];
    
    public function formateur(): BelongsTo {
        return $this->belongsTo(User::class, 'formateur_id');
    }
    public function etudiants(): HasMany {
        return $this->hasMany(User::class, 'classe_id')->where('type_profil', 'etudiant');
    }
    public function qcms(): HasMany {
        return $this->hasMany(Qcm::class);
    }
}

class User extends Authenticatable {
    use HasRoles, HasApiTokens, HasFactory, Notifiable;
    
    protected $fillable = [
        'nom', 'prenom', 'email', 'password',
        'matricule',        // Formateur uniquement
        'code_etudiant',    // Etudiant uniquement
        'classe_id',        // Etudiant uniquement
        'type_profil',
        'derniere_connexion',
    ];
    
    protected $appends = ['nom_complet', 'role'];
    
    public function isAdmin(): bool { return $this->type_profil === 'admin'; }
    public function isFormateur(): bool { return $this->type_profil === 'formateur'; }
    public function isEtudiant(): bool { return $this->type_profil === 'etudiant'; }
    
    /** La classe que gere ce formateur */
    public function classeGeree(): HasMany {
        return $this->hasMany(Classe::class, 'formateur_id');
    }

    /** Les QCM crees par ce formateur */
    public function qcms(): HasMany {
        return $this->hasMany(QCM::class, 'formateur_id');
    }

    /** Les sessions creees par ce formateur */
    public function seances(): HasMany {
        return $this->hasMany(Seance::class);
    }

    /** Les unites d'apprentissage creees par ce formateur */
    public function unitesApprentissage(): HasMany {
        return $this->hasMany(UniteApprentissage::class);
    }

    /** La classe de l'etudiant */
    public function classe(): BelongsTo {
        return $this->belongsTo(Classe::class);
    }

    /** Les tentatives de l'etudiant */
    public function tentatives(): HasMany {
        return $this->hasMany(Tentative::class, 'etudiant_id');
    }
}

// QCM Structure
class QCM extends Model {
    protected $table = 'qcms';
    protected $fillable = [
        'formateur_id', 'unite_apprentissage_id', 'classe_id',
        'titre', 'duree_minutes', 'score_reussite', 'statut'
    ];
    protected $casts = ['score_reussite' => 'decimal:1'];
    
    public function formateur(): BelongsTo { return $this->belongsTo(User::class, 'formateur_id'); }
    public function classe(): BelongsTo { return $this->belongsTo(Classe::class); }
    public function uniteApprentissage(): BelongsTo { return $this->belongsTo(UniteApprentissage::class); }
    public function questions(): HasMany { return $this->hasMany(Question::class, 'qcm_id'); }
    public function tentatives(): HasMany { return $this->hasMany(Tentative::class, 'qcm_id'); }
    public function competences(): BelongsToMany { 
        return $this->belongsToMany(Competence::class, 'competence_qcm', 'qcm_id', 'competence_id');
    }
}

class Question extends Model {
    protected $fillable = ['texte', 'type', 'points', 'explication_feedback', 'qcm_id'];
    
    public function qcm(): BelongsTo { return $this->belongsTo(QCM::class); }
    public function options(): HasMany { return $this->hasMany(Option::class); }
    public function reponses(): HasMany { return $this->hasMany(Reponse::class); }
}

class Option extends Model {
    protected $fillable = ['texte', 'est_correcte', 'feedback_specifique', 'question_id'];
    protected $casts = ['est_correcte' => 'boolean'];
    
    public function question(): BelongsTo { return $this->belongsTo(Question::class); }
    public function choixReponses(): HasMany { return $this->hasMany(ChoixReponse::class); }
}

// Passation
class Tentative extends Model {
    protected $fillable = ['date_debut', 'date_fin', 'score_obtenu', 'statut', 'etudiant_id', 'qcm_id'];
    protected $casts = ['score_obtenu' => 'decimal:1'];
    
    public function etudiant(): BelongsTo { return $this->belongsTo(User::class, 'etudiant_id'); }
    public function qcm(): BelongsTo { return $this->belongsTo(QCM::class, 'qcm_id'); }
    public function reponses(): HasMany { return $this->hasMany(Reponse::class); }
}

class Reponse extends Model {
    protected $fillable = ['tentative_id', 'question_id'];
    
    public function tentative(): BelongsTo { return $this->belongsTo(Tentative::class); }
    public function question(): BelongsTo { return $this->belongsTo(Question::class); }
    public function choixReponses(): HasMany { return $this->hasMany(ChoixReponse::class); }
}

class ChoixReponse extends Model {
    protected $fillable = ['reponse_id', 'option_id'];
    
    public function reponse(): BelongsTo { return $this->belongsTo(Reponse::class); }
    public function option(): BelongsTo { return $this->belongsTo(Option::class); }
}
```

## Schema Constraints
- **QCM:** `score_reussite` DECIMAL(5,1) on /20 scale
- **Tentative:** `score_obtenu` DECIMAL(5,1) on /20 scale
- **Option:** `est_correcte` BOOLEAN
- **Question:** `type` ENUM('choix_unique', 'choix_multiple')
- **User:** `type_profil` ENUM('admin', 'formateur', 'etudiant')
- **QCM:** `statut` ENUM('brouillon', 'publie', 'clos')

## Output
- Complete database migrations
- Eloquent models with relationships
- Foreign key constraints with cascade rules
- Model scopes and accessors
