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
    Schema::create('offres_stage', function (Blueprint $table) {
        $table->id();
        $table->foreignId('entreprise_id')->constrained('entreprises')->onDelete('cascade');
        $table->string('titre');
        $table->text('description');
        $table->integer('duree'); // en semaines
        $table->integer('places');
        $table->boolean('active')->default(true);
        $table->timestamps();
    });
}
/**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::dropIfExists('offres_stage');
}

};
