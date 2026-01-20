@extends('layouts.tech')

@section('title', 'Évaluer le stage')

@section('content')

<style>
    .eval-header { margin-bottom: 30px; }
    .eval-header h2 {
        font-weight: 800;
        background: linear-gradient(to right, #fff, #fbbf24); /* Dégradé or pour l'excellence */
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .card-eval-form {
        background: #0f172a;
        border: 1px solid #1e293b;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.7);
    }

    .form-label {
        color: #7dd3fc !important;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
        margin-bottom: 12px;
    }

    /* Input Note géant pour la visibilité */
    .input-note {
        font-size: 2rem !important;
        font-weight: 800 !important;
        text-align: center;
        color: #fbbf24 !important; /* Couleur Or */
        max-width: 150px;
        margin: 0 auto;
        border: 2px solid #334155 !important;
    }

    .input-note:focus {
        border-color: #fbbf24 !important;
        box-shadow: 0 0 20px rgba(251, 191, 36, 0.2) !important;
    }

    .form-control {
        background-color: #020617 !important;
        border: 1px solid #334155 !important;
        color: #ffffff !important;
        border-radius: 12px;
        padding: 15px;
    }

    .btn-save-eval {
        background: #10b981;
        color: white;
        font-weight: 800;
        padding: 15px 30px;
        border-radius: 12px;
        border: none;
        width: 100%;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.3s;
    }

    .btn-save-eval:hover {
        background: #059669;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
    }
</style>

<div class="eval-header text-center">
    <h2>🏆 Évaluation de fin de mission</h2>
    <p style="color: #7dd3fc;">Attribuez une note et un feedback final au stagiaire pour clore son dossier.</p>
</div>

<div class="card-eval-form mx-auto" style="max-width: 700px;">
    <form method="POST" action="/entreprise/evaluer">
        @csrf

        <input type="hidden" name="stage_id" value="{{ $stage_id }}">
        <input type="hidden" name="type" value="entreprise">

        <div class="mb-5 text-center">
            <label class="form-label d-block">Note Globale (/20)</label>
            <div class="d-flex align-items-center justify-content-center gap-3">
                <input type="number"
                       name="note"
                       class="form-control input-note"
                       min="0"
                       max="20"
                       placeholder="--"
                       required>
                <span class="fs-2 text-secondary fw-bold">/ 20</span>
            </div>
            <small class="text-secondary mt-2 d-block">Utilisez des chiffres entiers.</small>
        </div>

        <div class="mb-4">
            <label class="form-label">Commentaire & Appreciation</label>
            <textarea name="commentaire"
                      class="form-control"
                      rows="6"
                      placeholder="Décrivez les points forts, les axes d'amélioration et le comportement général durant le stage..."
                      required></textarea>
        </div>

        <div class="mt-5">
            <button type="submit" class="btn-save-eval">
                💾 VALIDER ET ARCHIVER LE STAGE
            </button>
            <div class="text-center mt-3">
                <a href="/entreprise/stagiaires" class="text-secondary small text-decoration-none">
                    ← Annuler et revenir à la liste
                </a>
            </div>
        </div>
    </form>
</div>

@endsection