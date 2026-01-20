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
    Schema::create('logs_systeme', function (Blueprint $table) {
        $table->id();
        $table->string('action');
        $table->string('table_concernee');
        $table->unsignedBigInteger('user_id')->nullable();
        $table->text('details')->nullable();
        $table->timestamp('created_at')->useCurrent();
    });
}

public function down()
{
    Schema::dropIfExists('logs_systeme');
}

};
