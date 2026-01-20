@extends('layouts.tech')

@section('title', 'Mes offres')

@section('content')

<style>
    /* Titres et en-tête */
    .header-section h2 {
        font-weight: 800;
        background: linear-gradient(to right, #fff, #38bdf8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Carte Offre Haute Visibilité */
    .offer-card {
        background: #0f172a;
        border: 1px solid #1e293b;
        border-radius: 20px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .offer-card:hover {
        transform: translateY(-8px);
        border-color: #38bdf8;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6), 0 0 20px rgba(56, 189, 248, 0.1);
    }

    .offer-title {
        color: #ffffff;
        font-weight: 800;
        font-size: 1.25rem;
    }

    /* Badges de Statut */
    .badge-tech {
        padding: 6px 12px;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        border-radius: 8px;
    }

    /* Texte de contenu */
    .offer-desc {
        color: #94a3b8; /* Gris clair pour la lecture */
        line-height: 1.6;
    }

    .offer-meta {
        background: rgba(30, 41, 59, 0.5);
        border-radius: 12px;
        padding: 10px 15px;
        display: inline-flex;
        gap: 15px;
        color: #7dd3fc; /* Bleu ciel électrique */
        font-weight: 600;
        font-size: 0.85rem;
    }

    /* Boutons personnalisés */
    .btn-action-sm {
        font-weight: 700;
        font-size: 0.75rem;
        padding: 8px 12px;
        border-radius: 8px;
        text-transform: uppercase;
        transition: 0.2s;
    }

    .btn-new {
        background: #10b981;
        color: white;
        font-weight: 800;
        padding: 12px 24px;
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }
    
    .btn-new:hover {
        background: #059669;
        transform: scale(1.05);
        color: white;
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid #10b981;
        color: #10b981;
        border-radius: 12px;
        font-weight: 700;
    }

    /* Style des filtres */
    .form-control-tech, .form-select-tech {
        background: #020617 !important;
        border: 1px solid #334155 !important;
        color: #fff !important;
        padding: 10px 15px;
        border-radius: 10px;
    }

    .form-control-tech:focus, .form-select-tech:focus {
        border-color: #38bdf8 !important;
        box-shadow: 0 0 0 0.25rem rgba(56, 189, 248, 0.1) !important;
        color: #fff;
    }

    .input-group-text {
        border-radius: 10px 0 0 10px !important;
    }

    .hover-white:hover {
        color: #fff !important;
        transition: 0.3s;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-5 header-section">
    <div>
        <h2 class="mb-1">📄 Mes offres de stage</h2>
        <p style="color: #7dd3fc; font-weight: 500;">Propulsez vos projets en recrutant les meilleurs profils.</p>
    </div>

    <a href="/entreprise/offres/create" class="btn btn-new d-flex align-items-center gap-2">
        <span></span> NOUVELLE OFFRE
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
        <span>✅</span> {{ session('success') }}
    </div>
@endif

{{-- BARRE DE FILTRES --}}
<div class="p-4 mb-4 shadow-lg" style="background: #0f172a; border-radius: 20px; border: 1px solid #1e293b;">
    <form action="{{ url()->current() }}" method="GET" class="row g-3 align-items-center">
        {{-- Recherche par titre --}}
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0 border-secondary border-opacity-25 text-secondary">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="form-control form-control-tech border-start-0" 
                       placeholder="Rechercher une offre...">
            </div>
        </div>

        {{-- Filtre par Statut --}}
        <div class="col-md-3">
            <select name="status" class="form-select form-select-tech">
                <option value="">Tous les statuts</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actives</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Désactivées</option>
            </select>
        </div>

        {{-- Bouton Filtrer --}}
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100 fw-bold rounded-3" style="background: #38bdf8; border: none; color: #020617;">
                FILTRER
            </button>
        </div>

        {{-- Réinitialiser --}}
        <div class="col-md-2 text-center">
            <a href="{{ url()->current() }}" class="text-secondary small text-decoration-none hover-white">
                <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser
            </a>
        </div>
    </form>
</div>

@if($offres->isEmpty())
    <div class="p-5 text-center rounded-4" style="background: #0f172a; border: 2px dashed #1e293b;">
        
        <h4 class="text-white fw-bold">Aucun signal d'offre</h4>
        <p class="text-secondary">Votre catalogue est vide. Commencez à publier pour recevoir des candidatures.</p>
    </div>
@else
    <div class="row g-4">
        @foreach($offres as $offre)
        <div class="col-md-6 col-lg-4">
            <div class="offer-card p-4 h-100 d-flex flex-column">

                <div class="d-flex justify-content-between align-items-start mb-3">
                    <span class="offer-title text-truncate" title="{{ $offre->titre }}">{{ $offre->titre }}</span>
                    @if($offre->active)
                        <span class="badge badge-tech bg-success text-white">Active</span>
                    @else
                        <span class="badge badge-tech bg-secondary">Désactivée</span>
                    @endif
                </div>

                <p class="offer-desc small mb-4 flex-grow-1">
                    {{ Str::limit($offre->description, 120) }}
                </p>

                <div class="offer-meta mb-4">
                    <span> {{ $offre->duree }} Mois</span>
                    <span> {{ $offre->places }} Places</span>
                </div>

                <div class="pt-3 border-top border-secondary border-opacity-10 d-flex flex-wrap gap-2">
                    <a href="/entreprise/offres/{{ $offre->id }}/edit" class="btn btn-action-sm btn-outline-info">
                         Éditer
                    </a>

                    @if($offre->active)
                        <form method="POST" action="/entreprise/offres/{{ $offre->id }}/desactiver" class="d-inline">
                            @csrf
                            <button class="btn btn-action-sm btn-warning text-dark">
                                 Desactiver
                            </button>
                        </form>
                    @else
                        <form method="POST" action="/entreprise/offres/{{ $offre->id }}/activer" class="d-inline">
                            @csrf
                            <button class="btn btn-action-sm btn-success">
                                 Activer
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="/entreprise/offres/{{ $offre->id }}" class="d-inline ms-auto" onsubmit="return confirm('Supprimer définitivement cette offre ?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-action-sm btn-outline-danger" title="Supprimer">
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection