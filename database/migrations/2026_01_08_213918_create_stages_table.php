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
    Schema::create('stages', function (Blueprint $table) {
        $table->id();
        $table->foreignId('candidature_id')->constrained('candidatures')->onDelete('cascade');
        $table->foreignId('encadrant_id')->constrained('users')->onDelete('cascade');
        $table->enum('etat', ['en_cours', 'termine', 'cloture'])->default('en_cours');
        $table->date('date_debut');
        $table->date('date_fin');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('stages');
}

};
