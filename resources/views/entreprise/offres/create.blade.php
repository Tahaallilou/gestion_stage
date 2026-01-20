@extends('layouts.tech')

@section('title', 'Créer une offre')

@section('content')

<style>
    .form-header { margin-bottom: 30px; }
    .form-header h2 {
        font-weight: 800;
        background: linear-gradient(to right, #fff, #38bdf8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Carte de formulaire haute visibilité */
    .card-tech-form {
        background: #0f172a; /* Fond sombre uni pour le contraste */
        border: 1px solid #1e293b;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    /* Labels en bleu ciel électrique */
    .form-label {
        color: #7dd3fc !important;
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    /* Inputs personnalisés pour fond sombre */
    .form-control, .form-select {
        background-color: #020617 !important;
        border: 1px solid #334155 !important;
        color: #ffffff !important; /* Texte saisi en blanc pur */
        border-radius: 10px;
        padding: 12px 15px;
    }

    .form-control:focus, .form-select:focus {
        border-color: #38bdf8 !important;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2);
        outline: none;
    }

    /* Placeholder plus visible */
    .form-control::placeholder {
        color: #475569 !important;
    }

    /* Boutons */
    .btn-create {
        background: #10b981;
        color: #fff;
        font-weight: 800;
        border: none;
        padding: 12px 25px;
        border-radius: 10px;
        transition: 0.3s;
    }

    .btn-create:hover {
        background: #059669;
        transform: translateY(-2px);
    }

    .btn-cancel {
        color: #94a3b8;
        border: 1px solid #334155;
        padding: 12px 25px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.3s;
    }

    .btn-cancel:hover {
        background: rgba(255, 255, 255, 0.05);
        color: #fff;
    }
</style>

<div class="form-header">
    <h2>➕ Publier une nouvelle mission</h2>
    <p style="color: #7dd3fc;">Remplissez les détails ci-dessous pour attirer les meilleurs talents.</p>
</div>

<div class="card-tech-form mx-auto" style="max-width:800px">
    <form method="POST" action="/entreprise/offres">
        @csrf

        <div class="mb-4">
            <label class="form-label">Titre de l’offre</label>
            <input type="text"
                   name="titre"
                   class="form-control"
                   placeholder="Ex : Développeur Full-Stack Junior"
                   required>
        </div>

        <div class="mb-4">
            <label class="form-label">Spécialité visée</label>
            <select name="specialite" class="form-select" required>
                <option value="" class="text-secondary">-- Sélectionner le domaine --</option>
                @foreach(config('specialites') as $s)
                    <option value="{{ $s }}">{{ $s }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="form-label">Description de la mission</label>
            <textarea name="description"
                      class="form-control"
                      rows="5"
                      placeholder="Décrivez les objectifs, les technologies utilisées et le profil recherché..."
                      required></textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <label class="form-label">Durée estimée (mois)</label>
                <input type="number"
                       name="duree"
                       min="1"
                       max="12"
                       class="form-control"
                       placeholder="6"
                       required>
            </div>

            <div class="col-md-6 mb-4">
                <label class="form-label">Postes disponibles</label>
                <input type="number"
                       name="places"
                       min="1"
                       class="form-control"
                       placeholder="1"
                       required>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3 mt-4 pt-3 border-top border-secondary border-opacity-10">
            <button type="submit" class="btn-create">
                🚀 PUBLIER L'OFFRE
            </button>

            <a href="/entreprise/offres" class="btn-cancel">
                Annuler
            </a>
        </div>
    </form>
</div>

@endsection