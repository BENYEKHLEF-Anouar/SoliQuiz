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
        Schema::create('competence_qcm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competence_id')->constrained('competences')->cascadeOnDelete();
            $table->foreignId('qcm_id')->constrained('qcms')->cascadeOnDelete();
            $table->timestamps();
            
            // Un QCM ne peut être rattaché qu'une seule fois à une compétence donnée
            $table->unique(['competence_id', 'qcm_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competence_qcm');
    }
};
