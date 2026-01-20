@extends('layouts.tech')

@section('title', 'Mes stages encadrés')

@section('content')

<style>
    .header-section { margin-bottom: 30px; }
    .header-section h2 {
        font-weight: 800;
        background: linear-gradient(to right, #fff, #38bdf8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Conteneur Table Haute Visibilité */
    .table-container-encadrant {
        background: #0f172a;
        border: 1px solid #1e293b;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }

    .table-tech {
        margin-bottom: 0;
        color: #ffffff !important;
    }

    .table-tech thead {
        background: #1e293b;
    }

    .table-tech thead th {
        padding: 20px;
        color: #7dd3fc !important;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: none;
    }

    .table-tech tbody td {
        padding: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        vertical-align: middle;
    }

    /* Badges & Status */
    .status-pill {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 800;
        font-size: 0.7rem;
        text-transform: uppercase;
    }

    .pill-valide { background: rgba(16, 185, 129, 0.2); color: #10b981; border: 1px solid #10b981; }
    .pill-attente { background: rgba(245, 158, 11, 0.2); color: #f59e0b; border: 1px solid #f59e0b; }

    /* Boutons d'action */
    .btn-action {
        font-weight: 700;
        font-size: 0.75rem;
        border-radius: 8px;
        padding: 8px 15px;
        transition: 0.3s;
        text-transform: uppercase;
    }

    .btn-view-report {
        background: rgba(56, 189, 248, 0.1);
        color: #38bdf8;
        border: 1px solid #38bdf8;
    }

    .btn-view-report:hover {
        background: #38bdf8;
        color: #020617;
    }
</style>

<div class="header-section">
    <h2>🎓 Mes stages encadrés</h2>
    <p style="color: #7dd3fc;">Suivi des dépôts de rapports et validations pédagogiques.</p>
</div>

{{-- ALERTES --}}
@if(session('success'))
    <div class="alert" style="background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; border-radius: 12px; font-weight: 600;">
        ✅ {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert" style="background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; color: #ef4444; border-radius: 12px; font-weight: 600;">
        ⚠️ {{ session('error') }}
    </div>
@endif

<div class="table-container-encadrant">
    <div class="table-responsive">
        <table class="table table-tech align-middle">
            <thead>
                <tr>
                    <th>Étudiant</th>
                    <th>Mission / Offre</th>
                    <th>Spécialité</th>
                    <th class="text-center">Livrable</th>
                    <th class="text-center">Statut Encadrant</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
            @forelse($stages as $stage)
                <tr>
                    {{-- ÉTUDIANT --}}
                    <td>
                        <div class="fw-bold e">{{ $stage->etudiant }}</div>
                        <div style="font-size: 0.75rem; color: #94a3b8;">ID: #STG-{{ $stage->stage_id }}</div>
                    </td>

                    {{-- OFFRE --}}
                    <td><span class=" fw-medium" >{{ $stage->titre }}</span></td>

                    {{-- SPÉCIALITÉ --}}
                    <td>
                        <span style="color: #7fabe0ff; font-size: 0.85rem;">{{ $stage->specialite }}</span>
                    </td>

                    {{-- RAPPORT --}}
                    <td class="text-center">
                        @if($stage->rapport_path)
                            <a href="{{ asset('storage/'.$stage->rapport_path) }}"
                               target="_blank"
                               class="btn btn-action btn-view-report">
                                📄 VOIR LE PDF
                            </a>
                        @else
                            <span style="color: #475569; font-style: italic; font-size: 0.8rem;">En attente de dépôt</span>
                        @endif
                    </td>

                    {{-- VALIDATION --}}
                    <td class="text-center">
                        @if($stage->validation_encadrant)
                            <span class="status-pill pill-valide">✔ VALIDÉ</span>
                        @else
                            <span class="status-pill pill-attente">⏳ EN ATTENTE</span>
                        @endif
                    </td>

                    {{-- ACTIONS --}}
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            {{-- VALIDER LE STAGE --}}
                            @if(!$stage->validation_encadrant && $stage->rapport_path)
                                <form method="POST" action="/encadrant/stage/{{ $stage->stage_id }}/valider">
                                    @csrf
                                    <button class="btn btn-action btn-success">✅ Valider</button>
                                </form>
                            @endif

                            {{-- ÉVALUER --}}
                           @if(!$stage->validation_encadrant)
    <form method="POST" action="/encadrant/stage/{{ $stage->stage_id }}/valider">
        @csrf
        <button class="btn btn-action btn-success">
            ▶️ Démarrer le stage
        </button>
    </form>
@endif


                            @if($stage->etat === 'en_cours' && $stage->rapport_path)
    <a href="/encadrant/evaluer/{{ $stage->stage_id }}" class="btn btn-warning">
        ✍️ Évaluer
    </a>
@endif

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center p-5" style="color: #475569;">
                        
                        Aucun stage sous votre supervision pour le moment.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection