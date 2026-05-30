<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Diagramme: UniteApprentissage { id, nom, code }
// Relation: Session "1" -- "0..*" UniteApprentissage : inclut  → seance_id FK ici
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unites_apprentissage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seance_id')->nullable()->constrained('seances')->nullOnDelete();
            $table->string('nom');
            $table->string('code')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unites_apprentissage');
    }
};
