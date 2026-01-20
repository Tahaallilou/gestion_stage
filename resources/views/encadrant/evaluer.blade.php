@extends('layouts.tech')

@section('title', 'Évaluer le stage')

@section('content')

<style>
    .eval-container {
        max-width: 750px;
        margin: 0 auto;
    }

    .header-eval h2 {
        font-weight: 800;
        background: linear-gradient(to right, #fff, #38bdf8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 10px;
    }

    /* Carte de formulaire haute visibilité */
    .card-eval {
        background: #0f172a;
        border: 1px solid #1e293b;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
    }

    /* Label stylisé */
    .form-label-tech {
        color: #7dd3fc !important;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-size: 0.8rem;
        margin-bottom: 15px;
        display: block;
    }

    /* Input Note géant */
    .input-note-hero {
        font-size: 3rem !important;
        font-weight: 900 !important;
        text-align: center;
        color: #38bdf8 !important; /* Bleu encadrant */
        background: #020617 !important;
        border: 2px solid #334155 !important;
        border-radius: 20px;
        padding: 15px;
        width: 180px;
        margin: 0 auto;
        transition: 0.3s;
    }

    .input-note-hero:focus {
        border-color: #38bdf8 !important;
        box-shadow: 0 0 30px rgba(56, 189, 248, 0.2);
        outline: none;
    }

    /* Textarea pour feedback profond */
    .textarea-tech {
        background: #020617 !important;
        border: 1px solid #334155 !important;
        color: #ffffff !important;
        border-radius: 15px;
        padding: 20px;
        font-size: 1rem;
        line-height: 1.6;
    }

    .textarea-tech:focus {
        border-color: #38bdf8 !important;
    }

    /* Bouton de validation */
    .btn-save-eval {
        background: #10b981;
        color: #fff;
        font-weight: 800;
        padding: 18px;
        border-radius: 15px;
        border: none;
        width: 100%;
        text-transform: uppercase;
        letter-spacing: 2px;
        transition: 0.3s;
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2);
    }

    .btn-save-eval:hover {
        background: #059669;
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(16, 185, 129, 0.4);
    }

    .note-unit {
        font-size: 2rem;
        color: #475569;
        font-weight: 700;
    }
</style>

<div class="eval-container">
    <div class="header-eval text-center mb-5">
        <h2>✍️ Évaluation Pédagogique</h2>
        <p style="color: #94a3b8;">Saisie de la note finale et de l'appréciation du tuteur académique.</p>
    </div>

    <form method="POST" action="/encadrant/evaluer" class="card-eval">
        @csrf
        <input type="hidden" name="stage_id" value="{{ $stage_id }}">

        <div class="mb-5 text-center">
            <label class="form-label-tech">Note de Soutenance / Rapport</label>
            <div class="d-flex align-items-center justify-content-center gap-3 mt-2">
                <input type="number" 
                       name="note" 
                       min="0" 
                       max="20" 
                       step="0.5"
                       class="form-control input-note-hero" 
                       placeholder="0.0"
                       required>
                <span class="note-unit">/ 20</span>
            </div>
            <p class="text-secondary small mt-3">Les demi-points sont acceptés (ex: 14.5)</p>
        </div>

        <div class="mb-4">
            <label class="form-label-tech">Commentaire Final & Feedback</label>
            <textarea name="commentaire" 
                      class="form-control textarea-tech" 
                      rows="5" 
                      placeholder="Qualité du travail, assiduité, compétences techniques acquises..." 
                      required></textarea>
        </div>

        <div class="pt-4 mt-5 border-top border-white border-opacity-10">
            <button type="submit" class="btn-save-eval">
                💾 ENREGISTRER L'ÉVALUATION
            </button>
            
            <div class="text-center mt-4">
                <a href="/encadrant/stages" class="text-secondary text-decoration-none small fw-bold">
                    ← ANNULER ET RETOURNER À LA LISTE
                </a>
            </div>
        </div>
    </form>
</div>

@endsection