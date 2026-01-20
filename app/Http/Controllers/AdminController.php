<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // N'oublie pas l'import pour les sessions

class AdminController extends Controller
{
    // ... tes autres fonctions ...

    public function toggleStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        if (!$user->is_active) {
            // Supprime les sessions pour déconnecter l'utilisateur de force
            DB::table('sessions')->where('user_id', $user->id)->delete();
        }

        return back()->with('success', 'Statut du compte modifié');
    }
}