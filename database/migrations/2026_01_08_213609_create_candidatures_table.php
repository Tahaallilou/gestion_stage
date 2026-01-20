<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('candidatures', function (Blueprint $table) {
        $table->id();
        $table->foreignId('offre_stage_id')->constrained('offres_stage')->onDelete('cascade');
        $table->foreignId('etudiant_id')->constrained('users')->onDelete('cascade');
        $table->enum('statut', ['en_attente', 'acceptee', 'refusee'])->default('en_attente');
        $table->timestamps();

        // Empêche double candidature à la même offre
        $table->unique(['offre_stage_id', 'etudiant_id']);
    });
}

public function down()
{
    Schema::dropIfExists('candidatures');
}

};
