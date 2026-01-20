@extends('layouts.tech')

@section('title', 'Dashboard Étudiant')

@section('content')

<style>
    /* Header & Welcome */
    .student-header {
        margin-bottom: 40px;
    }
    .student-header h2 {
        font-weight: 800;
        letter-spacing: -0.5px;
        color: #fff;
    }
    .student-header .welcome-text {
        color: #94a3b8;
        font-size: 1.1rem;
    }

    /* Student Action Cards */
    .student-card {
        background: rgba(30, 41, 59, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 24px;
        padding: 40px 20px;
        height: 100%;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .student-card:hover {
        transform: translateY(-8px);
        background: rgba(30, 41, 59, 0.8);
        border-color: #38bdf8;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
    }

    .card-icon-circle {
        width: 70px;
        height: 70px;
        background: rgba(56, 189, 248, 0.1);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 20px;
        transition: 0.3s;
    }

    .student-card:hover .card-icon-circle {
        background: #38bdf8;
        transform: rotate(-10deg);
    }

    .student-card:hover .card-icon-circle span {
        filter: brightness(0); /* L'icône devient noire sur fond bleu */
    }

    .student-card h5 {
        color: #fff;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .student-card p {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    /* Stage Status Box */
    .stage-status-box {
        background: linear-gradient(135deg, rgba(56, 189, 248, 0.1), rgba(129, 140, 248, 0.1));
        border: 1px dashed rgba(56, 189, 248, 0.3);
        border-radius: 24px;
        padding: 30px;
        margin-top: 40px;
    }

    .status-badge-tech {
        background: #38bdf8;
        color: #020617;
        font-weight: 800;
        padding: 8px 20px;
        border-radius: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.75rem;
    }
</style>

<div class="student-header">
    <h2> Dashboard Étudiant</h2>
    <p class="welcome-text">Prêt pour votre prochaine étape professionnelle ?</p>
</div>

{{-- GRILLE D'ACTIONS --}}
<div class="row g-4">

    {{-- OFFRES --}}
    <div class="col-md-4">
        <a href="/offres" class="text-decoration-none">
            <div class="student-card text-center">
                <div class="card-icon-circle">
                    <span>📄</span>
                </div>
                <h5>Explorer les offres</h5>
                <p>Trouvez le stage qui correspond à votre projet.</p>
            </div>
        </a>
    </div>

    {{-- CANDIDATURES --}}
    <div class="col-md-4">
        <a href="/candidatures" class="text-decoration-none">
            <div class="student-card text-center">
                <div class="card-icon-circle">
                    <span>📥</span>
                </div>
                <h5>Mes candidatures</h5>
                <p>Suivez l'état de vos demandes en temps réel.</p>
            </div>
        </a>
    </div>

    {{-- MON STAGE --}}
    <div class="col-md-4">
        <a href="/mon-stage" class="text-decoration-none">
            <div class="student-card text-center">
                <div class="card-icon-circle">
                    <span>🚀</span>
                </div>
                <h5>Mon stage</h5>
                <p>Accédez à votre suivi et vos évaluations.</p>
            </div>
        </a>
    </div>

</div>

{{-- SECTION ÉTAT DU STAGE --}}
@php
$stage = DB::table('stages')
    ->join('candidatures', 'stages.candidature_id', '=', 'candidatures.id')
    ->where('candidatures.etudiant_id', auth()->id())
    ->first();
@endphp

@if($stage)
<div class="stage-status-box shadow-lg">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h5 class="text-white fw-bold mb-2">📊 État actuel du stage</h5>
            <p class="text-secondary mb-md-0">Consultez l'avancement de votre stage et les prochaines étapes de validation.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <span class="status-badge-tech">
                {{ $stage->etat }}
            </span>
        </div>
    </div>
</div>
@else
<div class="mt-5 p-4 text-center rounded-4" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
    <p class="text-secondary mb-0 small italic">Aucun stage actif pour le moment. Postulez à des offres pour commencer !</p>
</div>
@endif

@endsection