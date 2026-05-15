<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('unites_apprentissage', function (Blueprint $table) {
            // Supprimer l'ancienne contrainte avec nullOnDelete
            $table->dropForeign(['seance_id']);
            
            // Ajouter la nouvelle contrainte avec cascadeOnDelete
            $table->foreign('seance_id')
                ->references('id')
                ->on('seances')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unites_apprentissage', function (Blueprint $table) {
            $table->dropForeign(['seance_id']);
            
            $table->foreign('seance_id')
                ->references('id')
                ->on('seances')
                ->nullOnDelete();
        });
    }
};
