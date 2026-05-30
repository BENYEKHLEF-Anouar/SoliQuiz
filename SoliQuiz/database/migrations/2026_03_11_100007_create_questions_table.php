<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Diagramme: Question { id, texte, type, points, explication_feedback }
// Relation: QCM "1" -- "1..*" Question : contient  → qcm_id FK
// %% Question.type : 'unique' | 'multiple'
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qcm_id')->constrained('qcms')->cascadeOnDelete();
            $table->text('texte');
            $table->enum('type', ['unique', 'multiple'])->default('unique');
            $table->unsignedTinyInteger('points')->default(1);
            $table->text('explication_feedback')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
