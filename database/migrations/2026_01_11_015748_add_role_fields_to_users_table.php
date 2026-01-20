<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   public function up(): void
{
    Schema::table('users', function (Blueprint $table) {

        // Étudiant
        $table->string('filiere')->nullable();
        $table->string('niveau')->nullable();

        // Entreprise
        $table->string('entreprise_nom')->nullable();
        $table->string('secteur')->nullable();

        // Encadrant pédagogique
        $table->string('departement')->nullable();
    });
}


    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'filiere',
                'niveau',
                'entreprise_nom',
                'secteur',
                'departement'
            ]);
        });
    }
};
