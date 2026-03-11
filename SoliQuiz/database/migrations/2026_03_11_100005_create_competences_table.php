<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Diagramme: Competence { id, code, libelle, description }
// Relation: UniteApprentissage "1" -- "0..*" Competence : vise  → unite_apprentissage_id FK ici
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unite_apprentissage_id')->constrained('unites_apprentissage')->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competences');
    }
};
