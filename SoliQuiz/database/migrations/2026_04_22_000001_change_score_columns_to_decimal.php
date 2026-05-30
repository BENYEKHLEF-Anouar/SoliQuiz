<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Change score columns from integer to decimal(5,1) for proper /20 scale support.
     */
    public function up(): void
    {
        Schema::table('qcms', function (Blueprint $table) {
            $table->decimal('score_reussite', 5, 1)->change();
        });

        Schema::table('tentatives', function (Blueprint $table) {
            $table->decimal('score_obtenu', 5, 1)->nullable()->change();
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->decimal('points', 5, 1)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qcms', function (Blueprint $table) {
            $table->unsignedTinyInteger('score_reussite')->change();
        });

        Schema::table('tentatives', function (Blueprint $table) {
            $table->unsignedSmallInteger('score_obtenu')->change();
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->unsignedTinyInteger('points')->change();
        });
    }
};