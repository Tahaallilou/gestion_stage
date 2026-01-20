<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    

    $request->validate([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    'password' => ['required', 'confirmed', Rules\Password::defaults()],
    'role' => 'required|in:etudiant,encadrant,entreprise',

    // Étudiant / Encadrant
    'specialite' => 'required_if:role,etudiant|required_if:role,encadrant',

    // Entreprise
    'entreprise_nom' => 'required_if:role,entreprise',
    'secteur' => 'required_if:role,entreprise',
    'ville' => 'required_if:role,entreprise',
]);

    $user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make($request->password),
    'role' => $request->role,
    'specialite' => $request->specialite, // étudiant & encadrant
]);


if ($request->role === 'entreprise') {
    \DB::table('entreprises')->insert([
        'user_id' => $user->id,
        'nom' => $request->entreprise_nom,
        'secteur' => $request->secteur,
        'ville' => $request->ville,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}


   -Auth::login($user);

return match ($user->role) {
    'etudiant'   => redirect('/dashboard/etudiant'),
    'entreprise' => redirect('/dashboard/entreprise'),
    'encadrant'  => redirect('/dashboard/encadrant'),
};  
}}