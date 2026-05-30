<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Convert score_reussite from percentage (0-100) to 20-point scale (0-20)
     */
    public function up(): void
    {
        // Convert existing percentages to 20-point scale
        DB::table('qcms')->update([
            'score_reussite' => DB::raw('FLOOR(score_reussite / 5)')
        ]);

        // Update tentatives score_obtenu from percentage to 20-point scale
        DB::table('tentatives')->whereNotNull('score_obtenu')->update([
            'score_obtenu' => DB::raw('ROUND(score_obtenu / 5, 1)')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert back to percentage
        DB::table('qcms')->update([
            'score_reussite' => DB::raw('score_reussite * 5')
        ]);

        DB::table('tentatives')->whereNotNull('score_obtenu')->update([
            'score_obtenu' => DB::raw('score_obtenu * 5')
        ]);
    }
};
