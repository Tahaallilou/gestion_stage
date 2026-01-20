@extends('layouts.tech')

@section('title', 'Mes stagiaires')

@section('content')

<style>
    .intern-header {
        margin-bottom: 30px;
    }
    .intern-header h2 {
        font-weight: 800;
        background: linear-gradient(to right, #fff, #38bdf8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Intern Row Card */
    .intern-card {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        backdrop-filter: blur(10px);
    }

    .intern-card:hover {
        border-color: #38bdf8;
        background: rgba(30, 41, 59, 0.7);
        transform: translateX(10px);
    }

    .intern-avatar {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #1e293b, #0f172a);
        border: 1px solid #38bdf8;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        color: #38bdf8;
        margin-right: 20px;
    }

    .intern-info { flex-grow: 1; }
    .intern-name { color: #fff; font-weight: 700; font-size: 1.1rem; margin-bottom: 2px; }
    .intern-offer { color: #64748b; font-size: 0.85rem; }

    .status-pill {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .btn-action-custom {
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 700;
        transition: 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-report {
        background: rgba(56, 189, 248, 0.1);
        color: #38bdf8;
        border: 1px solid rgba(56, 189, 248, 0.2);
    }
    .btn-report:hover { background: #38bdf8; color: #020617; }

    .btn-eval {
        background: #f59e0b;
        color: #020617;
        border: none;
    }
    .btn-eval:hover { background: #fbbf24; transform: scale(1.05); }

    @media (max-width: 768px) {
        .intern-card { flex-direction: column; text-align: center; gap: 15px; }
        .intern-avatar { margin-right: 0; }
        .intern-actions { width: 100%; display: flex; flex-direction: column; gap: 10px; }
    }
</style>

<div class="intern-header">
    <h2>👥 Gestion des Stagiaires</h2>
    <p class="text-secondary">Suivez la progression de vos recrues et évaluez leurs livrables.</p>
</div>
<form method="GET" class="row g-3 mb-4">

    <div class="col-md-5">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               class="form-control form-control-tech"
               placeholder="Nom de l’étudiant">
    </div>

    <div class="col-md-4">
        <select name="rapport" class="form-select form-select-tech">
            <option value="">Tous les rapports</option>
            <option value="depose" {{ request('rapport')=='depose'?'selected':'' }}>
                Rapport déposé
            </option>
            <option value="non_depose" {{ request('rapport')=='non_depose'?'selected':'' }}>
                En attente
            </option>
        </select>
    </div>

    <div class="col-md-3">
        <button class="btn btn-primary w-100 fw-bold">
            Filtrer
        </button>
    </div>

</form>


@if($stages->isEmpty())
    <div class="p-5 text-center rounded-4" style="background: rgba(30, 41, 59, 0.3); border: 1px dashed rgba(255,255,255,0.1);">

        <h4 class="text-white ">Aucun stagiaire actif</h4>
        <p class="text-secondary mb-0">Les étudiants apparaîtront ici une fois leurs candidatures acceptées.</p>
    </div>
@else
    <div class="intern-list">
        @foreach($stages as $s)
        <div class="intern-card">
            <div class="intern-avatar">
                {{ strtoupper(substr($s->etudiant, 0, 1)) }}
            </div>

            <div class="intern-info">
                <div class="intern-name">{{ $s->etudiant }}</div>
                <div class="intern-offer"> {{ $s->titre }}</div>
            </div>

            <div class="me-4 d-none d-md-block">
                @if($s->rapport_path)
                    <span class="status-pill" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">Rapport déposé</span>
                @else
                    <span class="status-pill" style="background: rgba(248, 113, 113, 0.1); color: #f87171;">Attente rapport</span>
                @endif
            </div>

            <div class="intern-actions d-flex gap-2">
                @if($s->rapport_path)
                    <a href="{{ asset('storage/'.$s->rapport_path) }}" target="_blank" class="btn-action-custom btn-report">
                        <span>📄</span> Voir Rapport
                    </a>
                @endif

                @if($s->rapport_path && $s->etat === 'en_cours')
                    <a href="/entreprise/evaluer/{{ $s->stage_id }}" class="btn-action-custom btn-eval">
                        <span>✍️</span> Évaluer
                    </a>
                @else
                    @if($s->etat === 'termine')
                        <span class="status-pill" style="background: rgba(56, 189, 248, 0.1); color: #38bdf8;">Évalué ✅</span>
                    @endif
                @endif
            </div>
        </div>
        @endforeach
    </div>
@endif

@endsection