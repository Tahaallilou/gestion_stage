@extends('layouts.tech')

@section('title', 'Dashboard Admin')

@section('content')

<style>
    /* Global Dashboard Style */
    .dashboard-header {
        color: #38bdf8;
        font-weight: 800;
        letter-spacing: -1px;
        margin-bottom: 30px;
        text-transform: uppercase;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Stats Cards - Glassmorphism */
    .stat-widget {
        background: rgba(30, 41, 59, 0.5);
        border: 1px solid rgba(56, 189, 248, 0.2);
        border-radius: 20px;
        padding: 25px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-widget:hover {
        transform: translateY(-5px);
        border-color: #38bdf8;
        box-shadow: 0 10px 30px -10px rgba(56, 189, 248, 0.3);
    }

    .stat-widget h3 {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0;
        color: #fff;
    }

    .stat-widget p {
        color: #94a3b8;
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 1px;
        margin-top: 5px;
    }

    .stat-icon {
        position: absolute;
        right: -10px;
        bottom: -10px;
        font-size: 5rem;
        opacity: 0.05;
        color: #38bdf8;
        transform: rotate(-15deg);
    }

    /* Modern Table Style */
    .table-container {
        background: rgba(15, 23, 42, 0.6);
        border-radius: 20px;
        padding: 20px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        margin-top: 30px;
    }

    .custom-table {
        color: #e2e8f0 !important;
        margin-bottom: 0;
    }

    .custom-table thead th {
        border-bottom: 2px solid rgba(56, 189, 248, 0.2);
        color: #38bdf8;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        padding: 15px;
    }

    .custom-table tbody td {
        padding: 15px;
        vertical-align: middle;
        border-color: rgba(255, 255, 255, 0.05);
    }

    /* Status Badges Custom */
    .badge-status {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-en_attente { background: rgba(234, 179, 8, 0.1); color: #eab308; border: 1px solid rgba(234, 179, 8, 0.2); }
    .status-accepte { background: rgba(34, 197, 94, 0.1); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.2); }
    .status-refuse { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
    
</style>

<div class="dashboard-header">
     Centre de Contrôle Admin
</div>

<div class="row">
    {{-- Offres --}}
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-widget">
            <h3>{{ DB::table('offres_stage')->count() }}</h3>
            <p>Offres Actives</p>
            <div class="stat-icon">📁</div>
        </div>
    </div>

    {{-- Candidatures --}}
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-widget">
            <h3>{{ DB::table('candidatures')->count() }}</h3>
            <p>Candidatures</p>
            <div class="stat-icon">📄</div>
        </div>
    </div>

    {{-- Stages --}}
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-widget">
            <h3>{{ DB::table('stages')->count() }}</h3>
            <p>Stages en cours</p>
            <div class="stat-icon">🎓</div>
        </div>
    </div>

    {{-- Entreprises --}}
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-widget">
            <h3>{{ DB::table('entreprises')->count() }}</h3>
            <p>Partenaires</p>
            <div class="stat-icon">🏢</div>
        </div>
    </div>
</div>


<div class="table-container shadow-lg">
    <div class="d-flex justify-content-between align-items-center mb-4 px-2">
        <h4 class="m-0 fw-bold" style="color: #f8fafc;">Dernières Activités</h4>
        <button class="btn btn-sm btn-outline-info" style="border-radius: 8px; font-size: 0.8rem;">Voir tout</button>
    </div>

    <div class="table-responsive">
        <table class="table custom-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Offre ID</th>
                    <th>Étudiant ID</th>
                    <th>Statut</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach(DB::table('candidatures')->orderByDesc('id')->limit(5)->get() as $c)
                <tr>
                    <td class="fw-bold">#{{ $c->id }}</td>
                    <td><span class="opacity-75">Offre #{{ $c->offre_stage_id }}</span></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-info rounded-circle me-2" style="width: 8px; height: 8px;"></div>
                            ID: {{ $c->etudiant_id }}
                        </div>
                    </td>
                    <td>
                        <span class="badge-status status-{{ $c->statut }}">
                            {{ str_replace('_', ' ', $c->statut) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="#" class="text-info text-decoration-none small">Détails →</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection 