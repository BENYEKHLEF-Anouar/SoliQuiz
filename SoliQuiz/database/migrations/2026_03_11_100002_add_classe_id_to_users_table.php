<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Relation: Classe "1" -- "0..*" Etudiant : contient → classe_id sur users
// Séparé pour éviter la dépendance circulaire users ↔ classes
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('classe_id')->nullable()->after('code_etudiant')->constrained('classes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('classe_id');
        });
    }
};
