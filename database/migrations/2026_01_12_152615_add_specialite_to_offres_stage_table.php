<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // database/migrations/xxxx_add_specialite_to_offres_stage_table.php
public function up()
{
    Schema::table('offres_stage', function (Blueprint $table) {
        $table->string('specialite')->after('description');
    });
}

public function down()
{
    Schema::table('offres_stage', function (Blueprint $table) {
        $table->dropColumn('specialite');
    });
}

};
