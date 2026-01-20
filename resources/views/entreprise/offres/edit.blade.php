@extends('layouts.tech')

@section('title', 'Modifier l’offre')

@section('content')

<style>
    .form-header { margin-bottom: 30px; }
    .form-header h2 {
        font-weight: 800;
        background: linear-gradient(to right, #fff, #38bdf8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .card-edit-form {
        background: #0f172a; 
        border: 1px solid #334155;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
    }

    .form-label {
        color: #7dd3fc !important;
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
    }

    .form-control {
        background-color: #020617 !important;
        border: 1px solid #334155 !important;
        color: #ffffff !important;
        border-radius: 12px;
        padding: 12px 18px;
        transition: 0.3s ease;
    }

    .form-control:focus {
        border-color: #38bdf8 !important;
        box-shadow: 0 0 15px rgba(56, 189, 248, 0.2);
    }

    .btn-update {
        background: #38bdf8;
        color: #020617;
        font-weight: 800;
        border: none;
        padding: 14px 30px;
        border-radius: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.3s;
    }

    .btn-update:hover {
        background: #7dd3fc;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(56, 189, 248, 0.4);
    }

    .btn-back {
        color: #94a3b8;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.3s;
    }

    .btn-back:hover {
        color: #fff;
    }
</style>

<div class="form-header">
    <a href="/entreprise/offres" class="btn-back mb-3 small">
        <i class="fas fa-arrow-left"></i> Retour à la liste
    </a>
    <h2>✏️ Modifier la mission</h2>
    <p style="color: #7dd3fc; font-size: 0.95rem;">Mise à jour des informations pour l'offre : <strong>{{ $offre->titre }}</strong></p>
</div>

<div class="card-edit-form mx-auto" style="max-width: 850px;">
    <form method="POST" action="/entreprise/offres/{{ $offre->id }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="form-label">Titre du stage</label>
            <input type="text" 
                   name="titre" 
                   class="form-control"
                   value="{{ $offre->titre }}" 
                   required>
        </div>

        <div class="mb-4">
            <label class="form-label">Description détaillée</label>
            <textarea name="description" 
                      class="form-control"
                      rows="6" 
                      required>{{ $offre->description }}</textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <label class="form-label">Durée (en mois)</label>
                <input type="number" 
                       name="duree" 
                       class="form-control"
                       value="{{ $offre->duree }}" 
                       min="1"
                       required>
            </div>
            <div class="col-md-6 mb-4">
                <label class="form-label">Nombre de places</label>
                <input type="number" 
                       name="places" 
                       class="form-control"
                       value="{{ $offre->places }}" 
                       min="1"
                       required>
            </div>
        </div>

        <div class="mt-4 pt-4 border-top border-white border-opacity-10">
            <button type="submit" class="btn-update w-100 w-md-auto">
                💾 ENREGISTRER LES MODIFICATIONS
            </button>
        </div>
    </form>
</div>

@endsection