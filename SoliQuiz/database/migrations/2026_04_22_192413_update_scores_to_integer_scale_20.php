<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qcms', function (Blueprint $table) {
            // score_reussite is now an integer on /20 scale (e.g. 10 for 50%)
            $table->unsignedTinyInteger('score_reussite')->default(10)->change();
        });

        Schema::table('tentatives', function (Blueprint $table) {
            // score_obtenu is now an integer on /20 scale
            $table->unsignedTinyInteger('score_obtenu')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('qcms', function (Blueprint $table) {
            $table->unsignedTinyInteger('score_reussite')->default(50)->change();
        });

        Schema::table('tentatives', function (Blueprint $table) {
            $table->unsignedSmallInteger('score_obtenu')->nullable()->change();
        });
    }
};
