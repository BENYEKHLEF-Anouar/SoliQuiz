<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Diagramme: Session { id, nom, date }
// Renommée "seances" pour éviter le conflit avec la table sessions HTTP de Laravel
// Relation: Session "1" -- "0..*" UniteApprentissage : inclut  → UA a seance_id FK
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seances', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->date('date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seances');
    }
};
