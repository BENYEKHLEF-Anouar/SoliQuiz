<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Diagramme: Reponse { id, repondu_a }
// Relations:
//   Tentative "1" -- "1..*" Reponse : contient  → tentative_id FK
//   Reponse "0..*" -- "1" Question : concerne   → question_id FK
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reponses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tentative_id')->constrained('tentatives')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->timestamp('repondu_a')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reponses');
    }
};
