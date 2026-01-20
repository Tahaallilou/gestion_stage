@extends('layouts.tech')

@section('title', 'Évaluation Pédagogique')

@section('content')

<style>
    .eval-header { margin-bottom: 30px; }
    .eval-header h2 {
        font-weight: 800;
        background: linear-gradient(to right, #fff, #38bdf8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Carte de formulaire Cyber */
    .card-eval-pédago {
        background: #0f172a;
        border: 1px solid #1e293b;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.6);
    }

    .form-label-tech {
        color: #7dd3fc !important;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-size: 0.8rem;
        margin-bottom: 12px;
        display: block;
    }

    /* Input Note Style "Score" */
    .input-note-pédago {
        font-size: 2.5rem !important;
        font-weight: 900 !important;
        text-align: center;
        color: #38bdf8 !important; /* Bleu encadrant */
        max-width: 160px;
        margin: 0 auto;
        background: #020617 !important;
        border: 2px solid #334155 !important;
        border-radius: 16px;
        padding: 10px;
    }

    .input-note-pédago:focus {
        border-color: #38bdf8 !important;
        box-shadow: 0 0 25px rgba(56, 189, 248, 0.25) !important;
        outline: none;
    }

    .textarea-feedback {
        background-color: #020617 !important;
        border: 1px solid #334155 !important;
        color: #ffffff !important;
        border-radius: 15px;
        padding: 20px;
        font-size: 1rem;
        line-height: 1.6;
    }

    .textarea-feedback:focus {
        border-color: #38bdf8 !important;
        box-shadow: 0 0 15px rgba(56, 189, 248, 0.1);
    }

    .btn-submit-eval {
        background: #38bdf8;
        color: #020617;
        font-weight: 900;
        padding: 16px 32px;
        border-radius: 12px;
        border: none;
        width: 100%;
        text-transform: uppercase;
        letter-spacing: 2px;
        transition: 0.3s;
        margin-top: 20px;
    }

    .btn-submit-eval:hover {
        background: #7dd3fc;
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(56, 189, 248, 0.3);
    }

    .slash-20 {
        font-size: 2rem;
        color: #475569;
        font-weight: 700;
    }
</style>

<div class="eval-header text-center">
    <div class="mb-2" style="font-size: 2rem;">📝</div>
    <h2>Évaluation Pédagogique</h2>
    <p style="color: #7dd3fc; font-weight: 500;">Saisie de la note finale et du rapport de soutenance.</p>
</div>

<div class="card-eval-pédago mx-auto" style="max-width: 750px;">
    <form method="POST" action="/encadrant/evaluer/{{ request('stage') }}">
        @csrf

        <div class="mb-5 text-center">
            <label class="form-label-tech">Note Académique Finale</label>
            <div class="d-flex align-items-center justify-content-center gap-3 mt-3">
                <input type="number" 
                       name="note" 
                       min="0" 
                       max="20" 
                       step="0.5"
                       class="form-control input-note-pédago" 
                       placeholder="00"
                       required>
                <span class="slash-20">/ 20</span>
            </div>
            <p class="text-secondary small mt-3">La note doit refléter la qualité du rapport et de la soutenance.</p>
        </div>

        <div class="mb-4">
            <label class="form-label-tech">Commentaires & Observations</label>
            <textarea name="commentaire" 
                      class="form-control textarea-feedback" 
                      rows="6" 
                      placeholder="Appréciation globale du travail fourni, rigueur méthodologique, et acquis pédagogiques..."></textarea>
        </div>

        <div class="pt-4 border-top border-secondary border-opacity-10">
            <button type="submit" class="btn-submit-eval">
                💾 ENREGISTRER LA NOTE FINALE
            </button>
            
            <div class="text-center mt-4">
                <a href="/encadrant/stages" class="text-secondary text-decoration-none small fw-bold" style="letter-spacing: 1px;">
                    ← ANNULER ET RETOURNER AUX STAGES
                </a>
            </div>
        </div>
    </form>
</div>

@endsection