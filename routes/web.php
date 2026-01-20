<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

function recalculerNoteFinale($stageId) {

    $notes = DB::table('evaluations')
        ->where('stage_id', $stageId)
        ->pluck('note', 'type');

    if ($notes->has('entreprise') && $notes->has('pedagogique')) {

        $noteFinale = ($notes['entreprise'] + $notes['pedagogique']) / 2;

        DB::table('stages')
            ->where('id', $stageId)
            ->update([
                'note_finale' => $noteFinale,
                'updated_at' => now()
            ]);
    }
}

/*
|--------------------------------------------------------------------------
| Redirection principale
|--------------------------------------------------------------------------
*/
Route::get('/', function () {

    $stats = [
        'etudiants'   => DB::table('users')->where('role', 'etudiant')->count(),
        'entreprises' => DB::table('entreprises')->count(),
        'offres'      => DB::table('offres_stage')->count(),
        'stages'      => DB::table('stages')->where('etat', 'en_cours')->count(),
    ];

    return view('landing', compact('stats'));
});



/*
|--------------------------------------------------------------------------
| Pages principales (UI)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $role = auth()->user()->role;

    return match ($role) {
        'etudiant'   => redirect('/dashboard/etudiant'),
        'entreprise' => redirect('/dashboard/entreprise'),
        'encadrant'  => redirect('/dashboard/encadrant'),
        'admin'      => redirect('/dashboard/admin'),
        default      => abort(403),
    };
})->middleware('auth')->name('dashboard');


Route::get('/offres', function () {
    $offres = DB::table('offres_stage')
        ->where('active', true)
        ->get();

    return view('offres.index', compact('offres'));
})->middleware('auth');


/*
|--------------------------------------------------------------------------
| Actions métier (procédures MySQL)
|--------------------------------------------------------------------------
*/
Route::post('/accepter-candidature/{id}', function ($id) {

    try {
        DB::statement('CALL accepter_candidature(?)', [$id]);
        return back()->with('success', 'Le stage a été créé avec succès !');
    } catch (\Illuminate\Database\QueryException $e) {
        if (str_contains($e->getMessage(), 'Double stage interdit')) {
            return back()->with('error', 'Impossible : Cet étudiant a déjà un stage validé.');
        }
        throw $e;
    }

    $candidature = DB::table('candidatures')->where('id', $id)->first();

    if (!$candidature) {
        return back()->with('error', 'Candidature introuvable');
    }

    // trouver la spécialité du stage
    $offre = DB::table('offres_stage')->where('id', $candidature->offre_stage_id)->first();

    // trouver un encadrant de cette spécialité
    $encadrant = DB::table('users')
        ->where('role', 'encadrant')
        ->where('specialite', $offre->specialite)
        ->first();

    if (!$encadrant) {
        abort(500, 'Aucun encadrant disponible pour cette spécialité');
    }

    // accepter la candidature
    DB::table('candidatures')
        ->where('id', $id)
        ->update(['statut' => 'acceptee']);

    // créer le stage MAIS PAS EN COURS
// Modifiez la ligne 106 pour utiliser $id (ou le nom exact dans votre fonction)
DB::statement('CALL accepter_candidature(?)', [$id]);
    return back()->with('success', 'Candidature acceptée. En attente de validation encadrant.');

})->middleware(['auth', 'role:entreprise']);


Route::post('/refuser-candidature/{id}', function ($id) {
    DB::statement('CALL refuser_candidature(?)', [$id]);

    return back()->with('success', 'Candidature refusée');
})->middleware(['auth', 'role:entreprise']);

/*
|--------------------------------------------------------------------------
| Profil utilisateur (Breeze)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware('auth')->group(function () {
    
    // Ajoute cette route pour afficher la page de suspension
    Route::get('/compte-suspendu', function() {
        if (auth()->user()->is_active) return redirect('/dashboard');
        return view('errors.suspended');
    })->name('suspended');

    // Pour chaque dashboard, on vérifie le statut
    Route::get('/dashboard/etudiant', function() {
        if (!auth()->user()->is_active) return redirect()->route('suspended');
        return view('dashboard.etudiant');
    });

    Route::get('/dashboard/entreprise', function() {
        if (!auth()->user()->is_active) return redirect()->route('suspended');
        return view('dashboard.entreprise');
    });

    Route::get('/dashboard/encadrant', function() {
        if (!auth()->user()->is_active) return redirect()->route('suspended');
        return view('dashboard.encadrant');
    });

    Route::get('/dashboard/admin', fn() => view('dashboard.admin'));
});

Route::get('/candidatures', function () {

    $user = auth()->user();

    // =====================
    // ÉTUDIANT
    // =====================
    if ($user->role === 'etudiant') {

        $candidatures = DB::table('candidatures')
            ->join('offres_stage', 'candidatures.offre_stage_id', '=', 'offres_stage.id')
            ->join('entreprises', 'offres_stage.entreprise_id', '=', 'entreprises.id')
            ->select(
                'candidatures.id',
                'candidatures.statut',
                'candidatures.cv',
                'candidatures.lettre_motivation',
                'offres_stage.titre as offre',
                'entreprises.nom as entreprise'
            )
            ->where('candidatures.etudiant_id', $user->id)
            ->get();
    }

    // =====================
    // ENTREPRISE
    // =====================
    elseif ($user->role === 'entreprise') {

        $candidatures = DB::table('candidatures')
            ->join('users as etudiants', 'candidatures.etudiant_id', '=', 'etudiants.id')
            ->join('offres_stage', 'candidatures.offre_stage_id', '=', 'offres_stage.id')
            ->join('entreprises', 'offres_stage.entreprise_id', '=', 'entreprises.id')
            ->where('entreprises.user_id', $user->id)
            ->select(
                'candidatures.id',
                'candidatures.statut',
                'candidatures.cv',
                'candidatures.lettre_motivation',
                'etudiants.name as etudiant_nom',
                'etudiants.specialite as etudiant_specialite',
                'offres_stage.titre as offre'
            )
            ->get();
    }

    else {
        abort(403);
    }

    return view('candidatures.index', compact('candidatures'));

})->middleware('auth');
       


Route::post('/postuler/{offre}', function (Request $request, $offre) {

    $request->validate([
        'cv' => 'required|mimes:pdf|max:2048',
        'lettre_motivation' => 'required|mimes:pdf|max:2048',
    ]);

    // 🔒 Vérification anti-duplication
    $existe = DB::table('candidatures')
        ->where('offre_stage_id', $offre)
        ->where('etudiant_id', auth()->id())
        ->exists();

    if ($existe) {
        return back()->with('error', 'Vous avez déjà postulé à cette offre.');
    }

    // 📂 Upload fichiers
    $cvPath = $request->file('cv')->store('cvs', 'public');
    $lettrePath = $request->file('lettre_motivation')->store('lettres', 'public');

    // 🧠 Insertion
    DB::table('candidatures')->insert([
        'offre_stage_id' => $offre,
        'etudiant_id' => auth()->id(),
        'statut' => 'en_attente',
        'cv' => $cvPath,
        'lettre_motivation' => $lettrePath,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('success', 'Candidature envoyée avec succès');

})->middleware('auth');




/*
|--------------------------------------------------------------------------
| ENTREPRISE - OFFRES DE STAGE
|--------------------------------------------------------------------------
*/


Route::get('/entreprise/offres', function (Request $request) {

    $entreprise = DB::table('entreprises')
        ->where('user_id', auth()->id())
        ->first();

    if (!$entreprise) {
        abort(403, 'Entreprise non trouvée');
    }

    $query = DB::table('offres_stage')
        ->where('entreprise_id', $entreprise->id);

    // 🔍 Search by title
    if ($request->filled('search')) {
        $query->where('titre', 'like', '%' . $request->search . '%');
    }

    // 🟢 / ⚪ Status filter
    if ($request->filled('status')) {
        if ($request->status === 'active') {
            $query->where('active', 1);
        } elseif ($request->status === 'inactive') {
            $query->where('active', 0);
        }
    }

    $offres = $query
        ->orderBy('created_at', 'desc')
        ->get();

    return view('entreprise.offres.index', compact('offres'));

})->middleware(['auth', 'role:entreprise']);


Route::get('/entreprise/offres/create', function () {
    return view('entreprise.offres.create');
})->middleware(['auth', 'role:entreprise']);


Route::post('/entreprise/offres', function (Request $request) {

    $request->validate([
    'titre' => 'required|string',
    'description' => 'required|string',
    'specialite' => 'required|string',
    'duree' => 'required|integer|min:1',
    'places' => 'required|integer|min:1',
]);


    $entreprise = DB::table('entreprises')
        ->where('user_id', auth()->id())
        ->first();

    DB::table('offres_stage')->insert([
    'titre' => $request->titre,
    'description' => $request->description,
    'specialite' => $request->specialite,
    'duree' => $request->duree,
    'places' => $request->places,
    'active' => true,
    'entreprise_id' => $entreprise->id,
    'created_at' => now(),
    'updated_at' => now(),
]);


    return redirect('/entreprise/offres')
        ->with('success', 'Offre créée avec succès');

})->middleware(['auth', 'role:entreprise']);

Route::get('/mon-stage', function () {

    $stage = DB::table('stages')
    ->join('candidatures', 'stages.candidature_id', '=', 'candidatures.id')
    ->join('offres_stage', 'candidatures.offre_stage_id', '=', 'offres_stage.id')
    ->join('entreprises', 'offres_stage.entreprise_id', '=', 'entreprises.id')
    ->leftJoin('users as encadrants', 'stages.encadrant_id', '=', 'encadrants.id')
    ->where('candidatures.etudiant_id', auth()->id())
    ->orderBy('stages.updated_at', 'desc') // 🔥 clé ici
    ->select(
        'stages.*',
        'offres_stage.titre as offre_titre',
        'entreprises.nom as entreprise_nom',
        'encadrants.name as encadrant_nom'
    )
    ->first();

    $evaluations = [];

    if ($stage) {
        $evaluations = DB::table('evaluations')
            ->where('stage_id', $stage->id)
            ->get();
    }

    return view('etudiant.mon-stage', compact('stage', 'evaluations'));

})->middleware('auth');


Route::get('/mes-evaluations', function () {

    $evaluations = DB::table('evaluations')
        ->join('stages', 'evaluations.stage_id', '=', 'stages.id')
        ->join('candidatures', 'stages.candidature_id', '=', 'candidatures.id')
        ->where('candidatures.etudiant_id', auth()->id())
        ->get();

    return view('etudiant.evaluations', compact('evaluations'));

})->middleware(['auth', 'role:etudiant']);



Route::get('/entreprise/stagiaires', function (Request $request) {

    $stages = DB::table('stages')
        ->join('candidatures', 'stages.candidature_id', '=', 'candidatures.id')
        ->join('users', 'candidatures.etudiant_id', '=', 'users.id')
        ->join('offres_stage', 'candidatures.offre_stage_id', '=', 'offres_stage.id')
        ->join('entreprises', 'offres_stage.entreprise_id', '=', 'entreprises.id')
        ->where('entreprises.user_id', auth()->id())

        /* 🔍 SEARCH BY STUDENT NAME */
        ->when($request->search, function ($q) use ($request) {
            $q->where('users.name', 'LIKE', '%' . $request->search . '%');
        })

        /* 📄 FILTER BY RAPPORT */
        ->when($request->rapport === 'depose', function ($q) {
            $q->whereNotNull('stages.rapport_path');
        })
        ->when($request->rapport === 'non_depose', function ($q) {
            $q->whereNull('stages.rapport_path');
        })

        ->select(
            'stages.id as stage_id',
            'stages.etat',
            'stages.rapport_path',
            'users.name as etudiant',
            'offres_stage.titre'
        )
        ->orderBy('stages.created_at', 'desc')
        ->get();

    return view('entreprise.stagiaires', compact('stages'));

})->middleware(['auth', 'role:entreprise']);



Route::delete('/entreprise/offres/{id}', function ($id) {
    DB::table('offres_stage')->where('id', $id)->delete();
    return back()->with('success', 'Offre supprimée');
})->middleware(['auth', 'role:entreprise']);


Route::post('/entreprise/evaluer', function (Request $request) {

    $stage = DB::table('stages')->where('id', $stageId)->first();

if (!$stage->rapport_path) {
    return back()->with('error', 'Le rapport de stage doit être déposé avant toute évaluation');
}


    Route::post('/entreprise/evaluer', function (Illuminate\Http\Request $request) {

    DB::table('evaluations')->insert([
        'stage_id' => $request->stage_id,
        'type' => 'entreprise',
        'note' => $request->note,
        'commentaire' => $request->commentaire,
        'created_at' => now(),
        'updated_at' => now(),
    ]);


    terminerStageSiComplet($request->stage_id);

    return redirect('/entreprise/stagiaires')
        ->with('success', 'Évaluation entreprise enregistrée');

})->middleware(['auth', 'role:entreprise']);


    return back()->with('success', 'Évaluation entreprise enregistrée');

})->middleware(['auth','role:entreprise']);


Route::post('/evaluations/{stage}', function (Request $request, $stage) {

    $user = auth()->user();

    // Sécurité : seuls entreprise & encadrant
    if (!in_array($user->role, ['entreprise', 'encadrant'])) {
        abort(403);
    }

    $request->validate([
        'note' => 'required|integer|min:0|max:20',
        'commentaire' => 'nullable|string',
    ]);

    DB::table('evaluations')->insert([
        'stage_id' => $stage,
        'type' => $user->role === 'entreprise'
                    ? 'entreprise'
                    : 'pedagogique',
        'note' => $request->note,
        'commentaire' => $request->commentaire,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    $stageId = $request->stage_id;

// vérifier si les deux évaluations existent
$nbEvaluations = DB::table('evaluations')
    ->where('stage_id', $stageId)
    ->whereIn('type', ['entreprise', 'pedagogique'])
    ->count();

if ($nbEvaluations === 2) {
    DB::table('stages')
        ->where('id', $stageId)
        ->update([
            'etat' => 'termine',
            'date_fin' => now()->toDateString(),
            'updated_at' => now()
        ]);
}

$evals = DB::table('evaluations')
    ->where('stage_id', $stageId)
    ->count();

if ($evals === 2) {
    DB::table('stages')->where('id', $stageId)->update([
        'etat' => 'termine',
        'note_finale' => $noteFinale
    ]);
}


    return back()->with('success', 'Évaluation enregistrée');

})->middleware('auth');


Route::get('/mes-evaluations', function () {

    $user = auth()->user();

    if ($user->role !== 'etudiant') {
        abort(403);
    }

    $evaluations = DB::table('evaluations')
        ->join('stages', 'evaluations.stage_id', '=', 'stages.id')
        ->join('candidatures', 'stages.candidature_id', '=', 'candidatures.id')
        ->join('offres_stage', 'candidatures.offre_stage_id', '=', 'offres_stage.id')
        ->where('candidatures.etudiant_id', $user->id)
        ->select(
            'evaluations.type',
            'evaluations.note',
            'evaluations.commentaire',
            'offres_stage.titre'
        )
        ->get();

    return view('etudiant.evaluations', compact('evaluations'));

})->middleware('auth');



Route::get('/entreprise/stagiaires', function () {

    $entreprise = DB::table('entreprises')
        ->where('user_id', auth()->id())
        ->first();

    if (!$entreprise) {
        abort(403);
    }

    $stages = DB::table('stages')
        ->join('candidatures', 'stages.candidature_id', '=', 'candidatures.id')
        ->join('users', 'candidatures.etudiant_id', '=', 'users.id')
        ->join('offres_stage', 'candidatures.offre_stage_id', '=', 'offres_stage.id')
        ->where('offres_stage.entreprise_id', $entreprise->id)
        ->select(
    'stages.id as stage_id',
    'stages.etat',
    'stages.date_debut',
    'stages.date_fin',
    'stages.rapport_path',   // ✅ AJOUT ICI
    'users.name as etudiant',
    'offres_stage.titre'
)

        ->get();

    return view('entreprise.stagiaires', compact('stages'));

})->middleware(['auth', 'role:entreprise']);

Route::get('/entreprise/evaluer/{stage}', function ($stage) {

    $stageData = DB::table('stages')
        ->where('id', $stage)
        ->first();

    if (!$stageData || !$stageData->rapport_path) {
        return back()->with('error', 'Le rapport n’a pas encore été déposé.');
    }

    return view('entreprise.evaluer', [
        'stage_id' => $stage
    ]);

})->middleware(['auth', 'role:entreprise']);




Route::post('/entreprise/evaluer', function (Request $request) {

    DB::table('evaluations')->insert([
        'stage_id' => $request->stage_id,
        'type' => 'entreprise',
        'note' => $request->note,
        'commentaire' => $request->commentaire,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect('/entreprise/stagiaires')
        ->with('success', 'Évaluation enregistrée');

})->middleware(['auth', 'role:entreprise']);


Route::get('/entreprise/offres/{id}/edit', function ($id) {

    $offre = DB::table('offres_stage')->where('id', $id)->first();

    if (!$offre) abort(404);

    return view('entreprise.offres.edit', compact('offre'));

})->middleware(['auth', 'role:entreprise']);


Route::delete('/entreprise/offres/{id}', function ($id) {

    DB::table('offres_stage')->where('id', $id)->delete();

    return redirect('/entreprise/offres')
        ->with('success', 'Offre supprimée');

})->middleware(['auth', 'role:entreprise']);

Route::put('/entreprise/offres/{id}', function ($id) {

    request()->validate([
        'titre' => 'required',
        'description' => 'required',
        'duree' => 'required|integer',
        'places' => 'required|integer',
    ]);

    DB::table('offres_stage')
        ->where('id', $id)
        ->update([
            'titre' => request('titre'),
            'description' => request('description'),
            'duree' => request('duree'),
            'places' => request('places'),
            'updated_at' => now(),
        ]);

    return redirect('/entreprise/offres')
        ->with('success', 'Offre mise à jour');

})->middleware(['auth', 'role:entreprise']);

Route::post('/entreprise/offres/{id}/desactiver', function ($id) {

    DB::table('offres_stage')
        ->where('id', $id)
        ->update(['active' => false]);

    return back()->with('success', 'Offre désactivée');

})->middleware(['auth', 'role:entreprise']);


Route::post('/entreprise/offres/{id}/activer', function ($id) {

    DB::table('offres_stage')
        ->where('id', $id)
        ->update(['active' => true]);

    return back()->with('success', 'Offre activée');

})->middleware(['auth', 'role:entreprise']);


Route::get('/encadrant/stages', function () {

    $stages = DB::table('stages')
        ->join('candidatures', 'stages.candidature_id', '=', 'candidatures.id')
        ->join('users as etudiants', 'candidatures.etudiant_id', '=', 'etudiants.id')
        ->join('offres_stage', 'candidatures.offre_stage_id', '=', 'offres_stage.id')
        ->where('stages.encadrant_id', auth()->id())
        ->select(
            'stages.id as stage_id',
            'stages.etat',
            'stages.validation_encadrant',
            'stages.rapport_path',   // ✅ IMPORTANT
            'etudiants.name as etudiant',
            'offres_stage.titre',
            'offres_stage.specialite'
        )
        ->get();

    return view('encadrant.stages.index', compact('stages'));

})->middleware(['auth', 'role:encadrant']);



Route::get('/encadrant/evaluations', function () {

    $evaluations = DB::table('evaluations')
        ->join('stages', 'evaluations.stage_id', '=', 'stages.id')
        ->where('stages.encadrant_id', auth()->id())
        ->where('evaluations.type', 'pedagogique')
        ->get();

    return view('encadrant.evaluations.index', compact('evaluations'));

})->middleware(['auth', 'role:encadrant']);


Route::post('/encadrant/evaluer', function (Request $request) {

    $stage = DB::table('stages')->where('id', $stageId)->first();

if (!$stage->rapport_path) {
    return back()->with('error', 'Le rapport de stage doit être déposé avant toute évaluation');
}


    DB::table('evaluations')->insert([
        'stage_id' => $request->stage_id,
        'type' => 'pedagogique',
        'note' => $request->note,
        'commentaire' => $request->commentaire,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('success', 'Évaluation pédagogique enregistrée');

})->middleware(['auth','role:encadrant']);


Route::post('/encadrant/stage/{id}/etat', function ($id) {

    request()->validate([
        'etat' => 'required|in:en_cours,termine,cloture'
    ]);

    DB::table('stages')
        ->where('id', $id)
        ->where('encadrant_id', auth()->id())
        ->update([
            'etat' => request('etat'),
            'updated_at' => now()
        ]);

    return back()->with('success', 'Avancement mis à jour');

})->middleware(['auth', 'role:encadrant']);

Route::post('/encadrant/valider-stage/{stage}', function ($stage) {

    DB::table('stages')
        ->where('id', $stage)
        ->update([
            'validation_encadrant' => 1,
            'etat' => 'en_cours',
        ]);

    return back()->with('success', 'Stage validé et démarré');

})->middleware(['auth','role:encadrant']);


Route::get('/mon-stage', function () {

    $stage = DB::table('stages')
    ->join('candidatures', 'stages.candidature_id', '=', 'candidatures.id')
    ->join('offres_stage', 'candidatures.offre_stage_id', '=', 'offres_stage.id')
    ->join('entreprises', 'offres_stage.entreprise_id', '=', 'entreprises.id')
    ->leftJoin('users as encadrants', 'stages.encadrant_id', '=', 'encadrants.id')
    ->where('candidatures.etudiant_id', auth()->id())
    ->orderByRaw("FIELD(stages.etat, 'en_cours', 'en_attente', 'termine')")
    ->select(
        'stages.*',
        'offres_stage.titre as offre_titre',
        'entreprises.nom as entreprise_nom',
        'encadrants.name as encadrant_nom'
    )
    ->first();


    if (!$stage) {
        $stage = DB::table('stages')
    ->join('candidatures', 'stages.candidature_id', '=', 'candidatures.id')
    ->where('candidatures.etudiant_id', auth()->id())
    ->first();
        return view('etudiant.mon-stage')->with('stage', null);
    }

    $evaluations = DB::table('evaluations')
        ->where('stage_id', $stage->id)
        ->get()
        ->keyBy('type');

    return view('etudiant.mon-stage', compact('stage','evaluations'));

})->middleware(['auth','role:etudiant']);

Route::post('/encadrant/stage/{stage}/valider', function ($stageId) {

    $stage = DB::table('stages')->where('id', $stageId)->first();

    if (!$stage) {
        return back()->with('error', 'Stage introuvable');
    }

    if ($stage->validation_encadrant) {
        return back()->with('error', 'Stage déjà validé');
    }

    DB::table('stages')
        ->where('id', $stageId)
        ->update([
            'validation_encadrant' => 1,
            'etat' => 'en_cours', // ✅ ICI ET SEULEMENT ICI
            'updated_at' => now()
        ]);

    return back()->with('success', 'Stage validé et démarré');

})->middleware(['auth', 'role:encadrant']);


Route::post('/encadrant/stages/{stage}/evaluer', function (Request $request, $stage) {

    $request->validate([
        'note' => 'required|integer|min:0|max:20',
        'commentaire' => 'nullable|string',
    ]);

    DB::table('evaluations')->insert([
        'stage_id' => $stage,
        'type' => 'pedagogique',
        'note' => $request->note,
        'commentaire' => $request->commentaire,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    recalculerNoteFinale($stage);

    return back()->with('success', 'Évaluation pédagogique enregistrée');

})->middleware(['auth', 'role:encadrant']);


Route::post('/encadrant/stages/{stage}/evaluer', function (Request $request, $stage) {

    $request->validate([
        'note' => 'required|integer|min:0|max:20',
        'commentaire' => 'nullable|string',
    ]);

    DB::table('evaluations')->insert([
        'stage_id' => $stage,
        'type' => 'pedagogique',
        'note' => $request->note,
        'commentaire' => $request->commentaire,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    recalculerNoteFinale($stage);

    return back()->with('success', 'Évaluation pédagogique enregistrée');

});

Route::post('/evaluations/{stage}', function (Request $request, $stageId) {

    $user = auth()->user();

    // Sécurité : uniquement entreprise & encadrant
    if (!in_array($user->role, ['entreprise', 'encadrant'])) {
        abort(403);
    }

    $request->validate([
        'note' => 'required|integer|min:0|max:20',
        'commentaire' => 'nullable|string',
    ]);

    // Type d'évaluation
    $type = $user->role === 'entreprise'
        ? 'entreprise'
        : 'pedagogique';

    // ❌ Empêcher double évaluation
    $exists = DB::table('evaluations')
        ->where('stage_id', $stageId)
        ->where('type', $type)
        ->exists();

    if ($exists) {
        return back()->with('error', 'Évaluation déjà effectuée');
    }

    // ✅ Insertion évaluation
    DB::table('evaluations')->insert([
        'stage_id' => $stageId,
        'type' => $type,
        'note' => $request->note,
        'commentaire' => $request->commentaire,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // 🔍 Vérifier si les deux évaluations existent
    $evaluations = DB::table('evaluations')
        ->where('stage_id', $stageId)
        ->whereIn('type', ['entreprise', 'pedagogique'])
        ->get();

    if ($evaluations->count() === 2) {

        // 🧮 Calcul de la note finale (moyenne simple)
        $noteFinale = round($evaluations->avg('note'), 2);

        // 🔚 Clôture du stage
        DB::table('stages')
            ->where('id', $stageId)
            ->update([
                'etat' => 'termine',
                'note_finale' => $noteFinale,
                'date_fin' => now()->toDateString(),
                'updated_at' => now()
            ]);
    }

    return back()->with('success', 'Évaluation enregistrée avec succès');

})->middleware('auth');


Route::post('/etudiant/rapport', function (Request $request) {

    $request->validate([
        'stage_id' => 'required|exists:stages,id',
        'rapport' => 'required|mimes:pdf|max:4096',
    ]);

    $path = $request->file('rapport')->store('rapports', 'public');

    DB::table('stages')
        ->where('id', $request->stage_id)
        ->update([
            'rapport_path' => $path,
            'updated_at' => now(),
        ]);

    return back()->with('success', 'Rapport déposé avec succès');

})->middleware(['auth','role:etudiant']);

Route::post('/mon-stage/rapport', function (Request $request) {

    $request->validate([
        'rapport' => 'required|mimes:pdf|max:4096',
    ]);

    $stage = DB::table('stages')
        ->join('candidatures', 'stages.candidature_id', '=', 'candidatures.id')
        ->where('candidatures.etudiant_id', auth()->id())
        ->where('stages.etat', 'en_cours')
        ->select('stages.id')
        ->first();

    if (!$stage) {
        return back()->with('error', 'Aucun stage en cours');
    }

    $path = $request->file('rapport')->store('rapports', 'public');

    DB::table('stages')
        ->where('id', $stage->id)
        ->update([
            'rapport_path' => $path,
            'updated_at' => now(),
        ]);

    return back()->with('success', 'Rapport déposé avec succès');

})->middleware(['auth', 'role:etudiant']);


Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {

        $stats = [
            'etudiants' => DB::table('users')->where('role', 'etudiant')->count(),
            'entreprises' => DB::table('users')->where('role', 'entreprise')->count(),
            'encadrants' => DB::table('users')->where('role', 'encadrant')->count(),
            'stages' => DB::table('stages')->count(),
            'stages_en_cours' => DB::table('stages')->where('etat', 'en_cours')->count(),
            'stages_termines' => DB::table('stages')->where('etat', 'termine')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    });

    // Gestion comptes
    Route::get('/users', function () {

        $users = DB::table('users')->get();

        return view('admin.users', compact('users'));
    });

    Route::post('/users/{id}/role', function ($id, Request $request) {

        DB::table('users')
            ->where('id', $id)
            ->update(['role' => $request->role]);

        return back()->with('success', 'Rôle mis à jour');
    });

});

/* ===============================
   ÉVALUATION ENCADRANT
================================ */

Route::get('/encadrant/evaluer/{stage}', function ($stage) {

    $stage = DB::table('stages')
        ->where('id', $stage)
        ->where('encadrant_id', auth()->id())
        ->first();

    if (!$stage) {
        abort(404);
    }

    if (!$stage->rapport_path) {
        return back()->with('error', 'Le rapport doit être déposé avant l’évaluation.');
    }

    return view('encadrant.evaluer', [
        'stage_id' => $stage->id
    ]);

})->middleware(['auth', 'role:encadrant']);


Route::post('/encadrant/evaluer', function (Request $request) {

    DB::table('evaluations')->insert([
        'stage_id' => $request->stage_id,
        'type' => 'pedagogique',
        'note' => $request->note,
        'commentaire' => $request->commentaire,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect('/encadrant/stages')
        ->with('success', 'Évaluation pédagogique enregistrée');

})->middleware(['auth', 'role:encadrant']);

 Route::get('/encadrant/evaluer/{stage}', function ($stage) {

    $stage = DB::table('stages')
        ->where('id', $stage)
        ->where('encadrant_id', auth()->id())
        ->first();

    if (!$stage) {
        abort(404);
    }

    if (!$stage->rapport_path) {
        return back()->with('error', 'Rapport requis avant évaluation');
    }

    return view('encadrant.evaluer', [
        'stage_id' => $stage->id
    ]);

})->middleware(['auth','role:encadrant']);

Route::post('/encadrant/evaluer', function (Request $request) {

    DB::table('evaluations')->insert([
        'stage_id' => $request->stage_id,
        'type' => 'pedagogique',
        'note' => $request->note,
        'commentaire' => $request->commentaire,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect('/encadrant/stages')
        ->with('success', 'Évaluation enregistrée');

})->middleware(['auth','role:encadrant']);

function terminerStageSiComplet($stageId)
{
    $stage = DB::table('stages')->where('id', $stageId)->first();

    if (!$stage || !$stage->rapport_path) {
        return;
    }

    $nbEvaluations = DB::table('evaluations')
        ->where('stage_id', $stageId)
        ->count();

    if ($nbEvaluations >= 2 && $stage->etat !== 'termine') {

        $notes = DB::table('evaluations')
            ->where('stage_id', $stageId)
            ->pluck('note');

        $noteFinale = round($notes->avg(), 2);

        DB::table('stages')
            ->where('id', $stageId)
            ->update([
                'etat' => 'termine',
                'note_finale' => $noteFinale,
                'date_fin' => now()->toDateString(),
                'updated_at' => now(),
            ]);
    }
}

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/users', function () {
        $users = DB::table('users')->get();
        return view('admin.users.index', compact('users'));
    });

    Route::post('/admin/users/{id}/role', function ($id, Illuminate\Http\Request $request) {
        DB::table('users')
            ->where('id', $id)
            ->update(['role' => $request->role]);

        return back()->with('success', 'Rôle mis à jour');
    });

    Route::post('/admin/users/{id}/delete', function ($id) {
        DB::table('users')->where('id', $id)->delete();
        return back()->with('success', 'Compte supprimé');
    });

});

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/users', function (\Illuminate\Http\Request $request) {

        $query = DB::table('users');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
                  ->orWhere('id', $request->search);
            });
        }

        if ($request->role_filter) {
            $query->where('role', $request->role_filter);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        $stats = [
            'users' => DB::table('users')->count(),
            'admins' => DB::table('users')->where('role','admin')->count(),
            'etudiants' => DB::table('users')->where('role','etudiant')->count(),
            'entreprises' => DB::table('users')->where('role','entreprise')->count(),
            'encadrants' => DB::table('users')->where('role','encadrant')->count(),
        ];

        return view('admin.users.index', compact('users','stats'));
    });

    Route::post('/admin/users/{id}/role', function ($id, \Illuminate\Http\Request $request) {
        DB::table('users')->where('id',$id)->update(['role'=>$request->role]);
        return back()->with('success','Rôle mis à jour');
    });

    Route::post('/admin/users/{id}/toggle', function ($id) {
        if ($id == auth()->id()) {
            return back()->with('error','Action interdite sur votre propre compte');
        }

        DB::table('users')->where('id',$id)->update([
            'is_active' => DB::raw('NOT is_active')
        ]);

        return back()->with('success','Statut du compte modifié');
    });

    Route::delete('/admin/users/{id}', function ($id) {
        if ($id == auth()->id()) {
            return back()->with('error','Impossible de supprimer votre compte');
        }

        DB::table('users')->where('id',$id)->delete();
        return back()->with('success','Compte supprimé');
    });

});


Route::get('/dashboard/encadrant', function () {

    $encadrantId = auth()->id();

    // 1️⃣ Stages encadrés (en cours)
    $stagesActifs = DB::table('stages')
        ->where('encadrant_id', $encadrantId)
        ->where('etat', 'en_cours')
        ->count();

    // 2️⃣ Validations en attente
    $validationsEnAttente = DB::table('stages')
        ->where('encadrant_id', $encadrantId)
        ->where('validation_encadrant', 0)
        ->count();

    // 3️⃣ Rapports déposés mais PAS encore évalués par l'encadrant
    $rapportsANoter = DB::table('stages')
        ->leftJoin('evaluations', function ($join) {
            $join->on('evaluations.stage_id', '=', 'stages.id')
                 ->where('evaluations.type', 'pedagogique');
        })
        ->where('stages.encadrant_id', $encadrantId)
        ->whereNotNull('stages.rapport_path')
        ->whereNull('evaluations.id')
        ->count();

    return view('dashboard.encadrant', compact(
        'stagesActifs',
        'validationsEnAttente',
        'rapportsANoter'
    ));

})->middleware(['auth', 'role:encadrant']);


require __DIR__.'/auth.php';
