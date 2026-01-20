@extends('layouts.tech')

@section('title', 'Détails du Stage')

@section('content')

<style>
    .stage-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .mission-header {
        background: linear-gradient(135deg, rgba(56, 189, 248, 0.1), rgba(129, 140, 248, 0.1));
        border: 1px solid rgba(56, 189, 248, 0.2);
        border-radius: 24px;
        padding: 40px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }

    .mission-header::after {
        content: 'MISSION';
        position: absolute;
        top: -10px;
        right: -10px;
        font-size: 5rem;
        font-weight: 900;
        color: rgba(255, 255, 255, 0.03);
        letter-spacing: 5px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }

    .info-item {
        background: rgba(2, 6, 23, 0.4);
        padding: 20px;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .info-label {
        font-size: 0.7rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 800;
        display: block;
        margin-bottom: 5px;
    }

    .info-value {
        color: #f1f5f9;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .btn-action-tech {
        background: transparent;
        border: 1px solid #38bdf8;
        color: #38bdf8;
        padding: 12px 25px;
        border-radius: 12px;
        font-weight: 700;
        transition: 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-action-tech:hover {
        background: #38bdf8;
        color: #020617;
        box-shadow: 0 0 20px rgba(56, 189, 248, 0.3);
    }

    .date-badge {
        background: rgba(56, 189, 248, 0.15);
        color: #38bdf8;
        padding: 4px 12px;
        border-radius: 8px;
        font-family: monospace;
        font-size: 0.9rem;
    }
</style>

<div class="stage-container">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="p-3 rounded-4 bg-primary bg-opacity-10 text-primary">
            <span class="fs-3">🎓</span>
        </div>
        <h2 class="fw-bold mb-0 text-white">Détails du stage</h2>
    </div>

    @if($stage)
        <div class="mission-header">
            <div class="row align-items-start">
                <div class="col-md-8">
                    <span class="badge mb-2 px-3 py-2" style="background: #38bdf8; color: #020617; font-weight: 800;">
                        {{ strtoupper($stage->etat) }}
                    </span>
                    <h3 class="text-white fw-black mb-1 mt-2" style="font-size: 2rem;">{{ $stage->titre }}</h3>
                    <p class="text-info fw-bold fs-5">🏢 {{ $stage->entreprise }}</p>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Début de mission</span>
                    <span class="info-value"><span class="date-badge">{{ $stage->date_debut }}</span></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Fin de mission</span>
                    <span class="info-value"><span class="date-badge">{{ $stage->date_fin }}</span></span>
                </div>
            </div>

            <div class="mt-4 pt-4 border-top border-white border-opacity-10 d-flex justify-content-between align-items-center">
                <a href="/mes-evaluations" class="btn-action-tech">
                    <span>📊</span> VOIR MES ÉVALUATIONS
                </a>
                <span class="text-secondary small">ID Stage: #ST-00{{ $stage->id ?? '1' }}</span>
            </div>
        </div>
    @else
        <div class="p-5 text-center rounded-5" style="background: rgba(15, 23, 42, 0.4); border: 2px dashed rgba(255,255,255,0.1);">
            <div class="fs-1 mb-3">🛰️</div>
            <h4 class="text-white fw-bold">Aucun stage détecté</h4>
            <p class="text-secondary mb-4">Vous n'avez pas encore de mission active assignée à votre profil.</p>
            <a href="/offres" class="btn btn-primary px-4 py-2 rounded-pill fw-bold">Consulter les offres</a>
        </div>
    @endif
</div>

@endsection