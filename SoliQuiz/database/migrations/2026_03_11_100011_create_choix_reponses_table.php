<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Table de liaison pour: Reponse "0..*" -- "1..*" Option : choisit
// Nécessaire pour les questions à choix multiples (type='multiple')
// Sans cette table, un étudiant ne pourrait choisir qu'une seule option par réponse
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('choix_reponses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reponse_id')->constrained('reponses')->cascadeOnDelete();
            $table->foreignId('option_id')->constrained('options')->cascadeOnDelete();
            $table->timestamps();

            // Un étudiant ne peut sélectionner la même option qu'une fois par réponse
            $table->unique(['reponse_id', 'option_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('choix_reponses');
    }
};
