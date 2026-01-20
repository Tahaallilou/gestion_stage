@extends('layouts.tech')

@section('title', 'Dashboard Entreprise')

@section('content')

@php
    $entreprise = DB::table('entreprises')
        ->where('user_id', auth()->id())
        ->first();
@endphp

@if(!$entreprise)
    <div class="alert alert-danger border-0 shadow-lg d-flex align-items-center" style="background: rgba(220, 38, 38, 0.2); color: #f87171; border-radius: 15px;">
        <span class="fs-4 me-3">⚠️</span>
        <div>
            <h5 class="alert-heading fw-bold mb-0">Accès restreint</h5>
            <p class="mb-0 small opacity-75">Profil entreprise introuvable. Veuillez contacter l'administration.</p>
        </div>
    </div>
@else

<style>
    /* Header Animation */
    .biz-header {
        border-bottom: 1px solid rgba(56, 189, 248, 0.15);
        padding-bottom: 20px;
        margin-bottom: 40px;
    }
    
    .biz-header h2 {
        font-weight: 800;
        background: linear-gradient(to right, #fff, #38bdf8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Stats Cards */
    .stat-box {
        background: rgba(30, 41, 59, 0.4);
        border: 1px solid rgba(56, 189, 248, 0.1);
        border-radius: 24px;
        padding: 30px;
        text-align: center;
        transition: 0.3s;
    }
    
    .stat-box:hover {
        border-color: #38bdf8;
        background: rgba(30, 41, 59, 0.6);
        box-shadow: 0 10px 30px -10px rgba(56, 189, 248, 0.2);
    }

    .stat-box h3 {
        font-size: 3rem;
        font-weight: 900;
        color: #fff;
        margin-bottom: 5px;
    }

    .stat-box p {
        color: #94a3b8;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.8rem;
        margin-bottom: 0;
    }

    /* Action Grid */
    .biz-card {
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 22px;
        padding: 35px 20px;
        height: 100%;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .biz-card:hover {
        transform: translateY(-10px);
        border-color: #38bdf8;
        background: rgba(15, 23, 42, 0.95);
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
    }

    .biz-icon {
        font-size: 2.5rem;
        margin-bottom: 20px;
        display: block;
        transition: 0.3s;
    }

    .biz-card:hover .biz-icon {
        transform: scale(1.2);
        filter: drop-shadow(0 0 10px #38bdf8);
    }

    .biz-card h5 {
        color: #fff;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .biz-card p {
        color: #64748b;
        font-size: 0.85rem;
        margin-bottom: 0;
    }
</style>

<div class="biz-header d-flex align-items-center justify-content-between">
    <div>
        <h2>🏢 Dashboard Entreprise</h2>
        <p class="text-secondary mb-0">Bienvenue, gérez vos recrutements et vos stagiaires en un clin d'œil.</p>
    </div>
    <div class="d-none d-md-block text-end">
        <span class="badge rounded-pill px-3 py-2" style="background: rgba(56, 189, 248, 0.1); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.2);">
            {{ $entreprise->nom }}
        </span>
    </div>
</div>

{{-- SECTION STATISTIQUES --}}
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="stat-box">
            <h3>{{ DB::table('offres_stage')->where('entreprise_id', $entreprise->id)->count() }}</h3>
            <p>Offres publiées</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-box">
            <h3>
                {{ DB::table('candidatures')
                    ->join('offres_stage','candidatures.offre_stage_id','=','offres_stage.id')
                    ->where('offres_stage.entreprise_id', $entreprise->id)
                    ->count() }}
            </h3>
            <p>Candidatures reçues</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-box">
            <h3>
                {{ DB::table('stages')
                    ->join('candidatures','stages.candidature_id','=','candidatures.id')
                    ->join('offres_stage','candidatures.offre_stage_id','=','offres_stage.id')
                    ->where('offres_stage.entreprise_id', $entreprise->id)
                    ->count() }}
            </h3>
            <p>Stagiaires actifs</p>
        </div>
    </div>
</div>

{{-- SECTION ACTIONS --}}
<div class="row g-4 mb-5">
    {{-- OFFRES --}}
    <div class="col-6 col-md-3">
        <a href="/entreprise/offres" class="text-decoration-none">
            <div class="biz-card text-center">
                <span class="biz-icon">📄</span>
                <h5>Mes offres</h5>
                <p class="d-none d-sm-block">Gérez vos annonces</p>
            </div>
        </a>
    </div>

    {{-- CANDIDATURES --}}
    <div class="col-6 col-md-3">
        <a href="/candidatures" class="text-decoration-none">
            <div class="biz-card text-center">
                <span class="biz-icon">📥</span>
                <h5>Candidatures</h5>
                <p class="d-none d-sm-block">Recruter vos talents</p>
            </div>
        </a>
    </div>

    {{-- STAGIAIRES --}}
    <div class="col-6 col-md-3">
        <a href="/entreprise/stagiaires" class="text-decoration-none">
            <div class="biz-card text-center">
                <span class="biz-icon">👥</span>
                <h5>Stagiaires</h5>
                <p class="d-none d-sm-block">Suivi et encadrement</p>
            </div>
        </a>
    </div>

    {{-- ÉVALUATIONS --}}
    <div class="col-6 col-md-3">
        <a href="/entreprise/stagiaires" class="text-decoration-none">
            <div class="biz-card text-center">
                <span class="biz-icon">📝</span>
                <h5>Évaluations</h5>
                <p class="d-none d-sm-block">Noter les performances</p>
            </div>
        </a>
    </div>
</div>

@endif

@endsection