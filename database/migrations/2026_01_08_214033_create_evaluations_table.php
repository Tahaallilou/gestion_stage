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
    Schema::create('evaluations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('stage_id')->constrained('stages')->onDelete('cascade');
        $table->enum('type', ['entreprise', 'pedagogique']);
        $table->integer('note');
        $table->text('commentaire')->nullable();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('evaluations');
}

};
