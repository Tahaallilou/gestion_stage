@extends('layouts.tech')

@section('title', 'Mon stage')

@section('content')

<style>
    .stage-header { margin-bottom: 30px; }
    .stage-header h2 {
        font-weight: 800;
        background: linear-gradient(to right, #fff, #818cf8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Glass Cards */
    .info-card {
        background: rgba(30, 41, 59, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 24px;
        padding: 25px;
        transition: 0.3s ease;
    }
    .info-card:hover { border-color: #818cf8; background: rgba(30, 41, 59, 0.6); }

    .label-tech {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #64748b;
        font-weight: 800;
        display: block;
        margin-bottom: 4px;
    }

    .value-tech { color: #f1f5f9; font-weight: 600; font-size: 1rem; }

    /* Rapport Section */
    .upload-zone {
        background: rgba(16, 185, 129, 0.05);
        border: 2px dashed rgba(16, 185, 129, 0.2);
        border-radius: 20px;
        padding: 30px;
        text-align: center;
        margin-top: 30px;
    }

    .btn-upload {
        background: #10b981;
        border: none;
        padding: 10px 25px;
        border-radius: 12px;
        font-weight: 700;
        color: #020617;
        transition: 0.3s;
    }
    .btn-upload:hover { background: #34d399; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(16, 185, 129, 0.4); }

    /* Final Grade Orb */
    .grade-orb {
        width: 100px;
        height: 100px;
        background: radial-gradient(circle at center, rgba(16, 185, 129, 0.2), transparent);
        border: 3px solid #10b981;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin: 20px auto;
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.2);
    }
</style>

<div class="stage-header">
    <h2>🎓 Mon suivi de stage</h2>
    <p class="text-secondary">Toutes les informations relatives à votre mission actuelle.</p>
</div>

@if(!$stage)
    <div class="p-5 text-center rounded-4" style="background: rgba(245, 158, 11, 0.05); border: 1px solid rgba(245, 158, 11, 0.2);">
        <span class="fs-1">🧭</span>
        <h4 class="text-warning mt-3">Aucun stage actif</h4>
        <p class="text-secondary mb-0">Dès qu'une de vos candidatures sera acceptée, vous retrouverez ici votre suivi.</p>
    </div>
@else

@php
    $evalEntreprise = $evaluations['entreprise'] ?? null;
    $evalPedagogique = $evaluations['pedagogique'] ?? null;
    $noteFinale = $stage->note_finale;

    if ($noteFinale === null && $evalEntreprise && $evalPedagogique) {
        $noteFinale = round(($evalEntreprise->note + $evalPedagogique->note) / 2, 2);
    }
@endphp

<div class="row g-4">
    {{-- COLONNE GAUCHE : INFOS --}}
    <div class="col-lg-4">
        <div class="info-card h-100">
            <h5 class="text-white fw-bold mb-4 d-flex align-items-center gap-2">
                <span class="text-primary">📌</span> Détails de la mission
            </h5>

            <div class="mb-3">
                <span class="label-tech">Projet</span>
                <span class="value-tech">{{ $stage->offre_titre }}</span>
            </div>

            <div class="mb-3">
                <span class="label-tech">Organisme d'accueil</span>
                <span class="value-tech">{{ $stage->entreprise_nom }}</span>
            </div>

            <div class="mb-3">
                <span class="label-tech">Encadrant Académique</span>
                <span class="value-tech text-info">{{ $stage->encadrant_nom ?? 'En attente d\'affectation' }}</span>
            </div>

            <div class="mt-4 pt-4 border-top border-secondary border-opacity-10">
                <span class="label-tech mb-2">Statut du dossier</span>
                <span class="badge px-3 py-2 rounded-pill {{ $stage->etat === 'termine' ? 'bg-success' : 'bg-info text-dark' }}">
                    {{ strtoupper(str_replace('_', ' ', $stage->etat)) }}
                </span>
            </div>

            @if($noteFinale !== null)
                <div class="grade-orb">
                    <span class="small text-secondary fw-bold" style="font-size: 0.6rem;">SCORE FINAL</span>
                    <span class="fs-3 fw-black text-white">{{ $noteFinale }}</span>
                    <span class="small text-secondary" style="font-size: 0.5rem;">/20</span>
                </div>
            @endif
        </div>
    </div>

    {{-- COLONNE DROITE : ÉVALUATIONS & RAPPORT --}}
    <div class="col-lg-8">
        <div class="row g-4">
            {{-- Eval Entreprise --}}
            <div class="col-md-6">
                <div class="info-card h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h6 class="text-white fw-bold">🏢 Tutorat Entreprise</h6>
                        @if($evalEntreprise)
                            <span class="badge bg-primary">{{ $evalEntreprise->note }}/20</span>
                        @endif
                    </div>
                    <p class="text-secondary small italic">
                        {{ $evalEntreprise ? '"'.$evalEntreprise->commentaire.'"' : 'Évaluation en attente...' }}
                    </p>
                </div>
            </div>

            {{-- Eval Académique --}}
            <div class="col-md-6">
                <div class="info-card h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h6 class="text-white fw-bold">🎓 Suivi Pédagogique</h6>
                        @if($evalPedagogique)
                            <span class="badge bg-warning text-dark">{{ $evalPedagogique->note }}/20</span>
                        @endif
                    </div>
                    <p class="text-secondary small italic">
                        {{ $evalPedagogique ? '"'.$evalPedagogique->commentaire.'"' : 'Évaluation en attente...' }}
                    </p>
                </div>
            </div>

            {{-- Zone Rapport --}}
            <div class="col-12">
                @if($stage->etat === 'en_cours' && !$stage->rapport_path)
<form method="POST" action="/mon-stage/rapport" enctype="multipart/form-data">
    @csrf
    <input type="file" name="rapport" required>
    <button class="btn btn-success">📤 Déposer le rapport</button>
</form>
@endif

            </div>
        </div>
    </div>
</div>

@endif

@endsection