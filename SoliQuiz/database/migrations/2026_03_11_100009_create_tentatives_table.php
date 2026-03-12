<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Diagramme: Tentative { id, score_obtenu, date_debut, date_fin, statut }
// Relations:
//   Etudiant "1" -- "0..*" Tentative : effectue     → etudiant_id FK
//   QCM "1" -- "0..*" Tentative : fait_objet_de     → qcm_id FK
// %% Tentative.statut : 'en_cours' | 'soumis' | 'expire'
return new class extends Migration {
    public function up(): void
    {
        Schema::create('tentatives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('qcm_id')->constrained('qcms')->cascadeOnDelete();
            $table->unsignedSmallInteger('score_obtenu')->nullable();
            $table->enum('statut', ['en_cours', 'reussi', 'echoue', 'abandonne'])->default('en_cours');
            $table->timestamp('date_debut')->useCurrent();
            $table->timestamp('date_fin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tentatives');
    }
};
