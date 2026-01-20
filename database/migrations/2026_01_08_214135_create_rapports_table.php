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
    Schema::create('rapports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('stage_id')->constrained('stages')->onDelete('cascade');
        $table->string('fichier');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('rapports');
}

};
