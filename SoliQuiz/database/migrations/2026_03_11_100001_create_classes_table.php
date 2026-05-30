<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Diagramme: Classe { id, nom, promotion }
// Relation: Formateur "1" -- "1" Classe : gère  → formateur_id FK
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formateur_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nom');
            $table->string('promotion');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
