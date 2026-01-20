@extends('layouts.tech')

@section('title', 'Dashboard Encadrant')

@section('content')

<style>
    /* Header avec dégradé et bordure éclatante */
    .encadrant-header {
        border-left: 5px solid #38bdf8;
        padding-left: 25px;
        margin-bottom: 50px;
    }

    .encadrant-header h2 {
        color: #ffffff;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        background: linear-gradient(to right, #fff, #38bdf8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .encadrant-header p {
        color: #7dd3fc; /* Bleu ciel plus clair */
        font-size: 1.1rem;
        font-weight: 500;
    }

    /* Stats Widgets Contrastés */
    .stat-card-encadrant {
        background: #0f172a; /* Fond uni très sombre */
        border: 1px solid #1e293b;
        border-radius: 24px;
        padding: 30px;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    }

    .stat-card-encadrant:hover {
        border-color: #38bdf8;
        transform: translateY(-5px);
        box-shadow: 0 0 20px rgba(56, 189, 248, 0.15);
    }

    .stat-label {
        color: #7dd3fc !important;
        font-weight: 800;
        font-size: 0.8rem;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 15px;
        display: block;
    }

    .stat-value {
        font-size: 3rem;
        font-weight: 900;
        color: #ffffff;
        line-height: 1;
    }

    /* Action Cards Style Cyber */
    .action-card {
        background: #0f172a;
        border: 1px solid #1e293b;
        border-radius: 24px;
        padding: 35px;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .action-card:hover {
        border-color: #38bdf8;
        background: #131c31;
    }

    .action-card h5 {
        color: #ffffff;
        font-weight: 800;
        font-size: 1.3rem;
        margin-bottom: 15px;
    }

    .action-card p {
        color: #94a3b8; /* Texte gris clair lisible */
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .icon-box {
        width: 60px;
        height: 60px;
        background: rgba(56, 189, 248, 0.1);
        border: 1px solid rgba(56, 189, 248, 0.2);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 25px;
    }

    /* Boutons Tech Haute Visibilité */
    .btn-tech-outline {
        border: 2px solid #38bdf8;
        color: #38bdf8;
        border-radius: 12px;
        font-weight: 800;
        padding: 12px;
        transition: 0.3s;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: auto; /* Pousse le bouton en bas */
        text-decoration: none;
        text-align: center;
        display: block;
    }

    .btn-tech-outline:hover {
        background: #38bdf8;
        color: #020617;
        box-shadow: 0 0 20px rgba(56, 189, 248, 0.4);
    }
</style>

<div class="encadrant-header">
    <h2>🎓 Centre de Pilotage</h2>
    <p>Espace de supervision pédagogique et suivi des carrières</p>
</div>

{{-- SECTION STATISTIQUES --}}
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="stat-card-encadrant">
            <span class="stat-label">STAGES ENCADRÉS</span>
            <div class="d-flex align-items-end justify-content-between">
                <div class="stat-value">{{ $stagesActifs }}</div>
<span class="badge"
      style="background: rgba(56, 189, 248, 0.1); color: #38bdf8; border: 1px solid #38bdf8; font-weight: 800;">
    ACTIFS
</span>

            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card-encadrant">
            <span class="stat-label text-warning" style="color: #fbbf24 !important;">VALIDATIONS</span>
            <div class="d-flex align-items-end justify-content-between">
                <div class="stat-value text-warning" style="color: #fbbf24 !important;">
    {{ $validationsEnAttente }}
</div>
<span class="badge bg-warning text-dark fw-bold">
    EN ATTENTE
</span>

            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card-encadrant">
            <span class="stat-label text-success" style="color: #10b981 !important;">RAPPORTS REÇUS</span>
            <div class="d-flex align-items-end justify-content-between">
                <div class="stat-value text-success" style="color: #10b981 !important;">
    {{ $rapportsANoter }}
</div>
<span class="badge bg-success fw-bold">
    À NOTER
</span>

            </div>
        </div>
    </div>
</div>

{{-- SECTION ACTIONS --}}
<h5 class="fw-bold mb-4 text-white" style="letter-spacing: 2px; font-size: 0.9rem;"> NAVIGATION RAPIDE</h5>

<div class="row g-4">
    <div class="col-md-4">
        <div class="action-card">
            <div class="icon-box">📂</div>
            <h5>Mes Stages</h5>
            <p>Pilotez l'avancement des projets et supervisez la relation entre l'étudiant et l'entreprise d'accueil.</p>
            <a href="/encadrant/stages" class="btn btn-tech-outline">Liste des dossiers</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="action-card">
            <div class="icon-box">🧑‍🎓</div>
            <h5>Étudiants</h5>
            <p>Accédez aux fiches de compétences et aux historiques académiques des étudiants sous votre tutorat.</p>
            <a href="/encadrant/stages" class="btn btn-tech-outline" >Fiches Étudiants</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="action-card">
            <div class="icon-box">✍️</div>
            <h5>Évaluations</h5>
            <p>Saisissez les notes de soutenance et validez les rapports de fin de stage pour la certification.</p>
            <a href="/encadrant/evaluations" class="btn btn-tech-outline" >Espace Notation</a>
        </div>
    </div>
</div>

@endsection