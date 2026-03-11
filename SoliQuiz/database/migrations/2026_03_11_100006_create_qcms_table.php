<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Diagramme: QCM { id, titre, duree_minutes, score_reussite, est_publie }
// Relations:
//   Formateur "1" -- "0..*" QCM : crée                  → formateur_id FK
//   UniteApprentissage "1" -- "0..*" QCM : est_evaluée_par → unite_apprentissage_id FK
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qcms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formateur_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('unite_apprentissage_id')->nullable()->constrained('unites_apprentissage')->nullOnDelete();
            $table->string('titre');
            $table->unsignedSmallInteger('duree_minutes')->default(30);
            $table->unsignedTinyInteger('score_reussite')->default(50); // pourcentage
            $table->boolean('est_publie')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qcms');
    }
};
