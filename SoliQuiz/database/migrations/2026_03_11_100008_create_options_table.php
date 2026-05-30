<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Diagramme: Option { id, texte, est_correcte, feedback_specifique }
// Relation: Question "1" -- "2..*" Option : propose  → question_id FK
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->string('texte');
            $table->boolean('est_correcte')->default(false);
            $table->string('feedback_specifique')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('options');
    }
};
