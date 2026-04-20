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
        // 1. Update Seances (Sessions)
        Schema::table('seances', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->nullable()->constrained('users')->cascadeOnDelete();
        });

        // 2. Update Unites d'Apprentissage
        Schema::table('unites_apprentissage', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->nullable()->constrained('users')->cascadeOnDelete();
        });

        // 3. Update QCMs
        Schema::table('qcms', function (Blueprint $table) {
            $table->foreignId('classe_id')->after('unite_apprentissage_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->enum('statut', ['brouillon', 'public', 'termine'])->after('score_reussite')->default('brouillon');
        });

        // Data migration: mapping est_publie to statut
        \Illuminate\Support\Facades\DB::table('qcms')->where('est_publie', true)->update(['statut' => 'public']);
        \Illuminate\Support\Facades\DB::table('qcms')->where('est_publie', false)->update(['statut' => 'brouillon']);

        Schema::table('qcms', function (Blueprint $table) {
            $table->dropColumn('est_publie');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qcms', function (Blueprint $table) {
            $table->boolean('est_publie')->after('score_reussite')->default(false);
        });

        \Illuminate\Support\Facades\DB::table('qcms')->where('statut', 'public')->update(['est_publie' => true]);

        Schema::table('qcms', function (Blueprint $table) {
            $table->dropColumn(['classe_id', 'statut']);
        });

        Schema::table('unites_apprentissage', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('seances', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
