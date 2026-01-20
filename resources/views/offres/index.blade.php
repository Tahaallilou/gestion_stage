@extends('layouts.tech')

@section('title', 'Offres de stage')

@section('content')

<style>
    /* Header styling */
    .page-title {
        font-weight: 800;
        background: linear-gradient(to right, #fff, #38bdf8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 30px;
    }

    /* Card Offer Design */
    .offer-card {
        background: rgba(15, 23, 42, 0.7);
        border: 1px solid rgba(56, 189, 248, 0.1);
        border-radius: 24px;
        padding: 25px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        backdrop-filter: blur(10px);
    }

    .offer-card:hover {
        transform: translateY(-10px);
        border-color: #38bdf8;
        box-shadow: 0 20px 40px rgba(56, 189, 248, 0.15);
    }

    /* Badge Custom */
    .badge-specialite {
        background: rgba(56, 189, 248, 0.1);
        color: #38bdf8;
        border: 1px solid rgba(56, 189, 248, 0.2);
        padding: 6px 12px;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    /* Form Styling */
    .file-input-wrapper {
        position: relative;
        margin-bottom: 15px;
    }

    .form-label-tech {
        font-size: 0.8rem;
        color: #94a3b8;
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
    }

    .form-control-tech {
        background: rgba(2, 6, 23, 0.5) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: #cbd5e1 !important;
        border-radius: 12px !important;
        font-size: 0.85rem;
        padding: 10px;
    }

    .form-control-tech:focus {
        border-color: #38bdf8 !important;
        box-shadow: 0 0 10px rgba(56, 189, 248, 0.2) !important;
    }

    .btn-postuler {
        background: linear-gradient(135deg, #38bdf8, #818cf8);
        border: none;
        color: white;
        font-weight: 700;
        padding: 12px;
        border-radius: 14px;
        transition: 0.3s;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 10px;
    }

    .btn-postuler:hover {
        transform: scale(1.02);
        box-shadow: 0 0 20px rgba(56, 189, 248, 0.4);
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        background: rgba(255, 255, 255, 0.03);
        padding: 10px 15px;
        border-radius: 12px;
        margin-bottom: 20px;
    }
</style>

<h2 class="page-title">📄 Offres de stage disponibles</h2>

@if(session('success'))
    <div class="alert border-0 shadow-lg" style="background: rgba(16, 185, 129, 0.2); color: #34d399; border-radius: 15px;">
        ✅ {{ session('success') }}
    </div>
@endif

<div class="row g-4">
@forelse($offres as $offre)
    <div class="col-xl-4 col-md-6">
        <div class="offer-card h-100 d-flex flex-column">
            
            <div class="d-flex justify-content-between align-items-start mb-3">
                <span class="badge-specialite">
                    {{ $offre->specialite }}
                </span>
                <span class="text-info fw-bold">#{{ $offre->id }}</span>
            </div>

            <h4 class="text-white fw-bold mb-3">{{ $offre->titre }}</h4>

            <p class="text-secondary small flex-grow-1">
                {{ Str::limit($offre->description, 140) }}
            </p>

            <div class="info-row small">
                <span class="text-secondary">⏱ <strong>{{ $offre->duree }}</strong> mois</span>
                <span class="text-secondary">👥 <strong>{{ $offre->places }}</strong> places</span>
            </div>

            {{-- FORMULAIRE --}}
            <form action="/postuler/{{ $offre->id }}" method="POST" enctype="multipart/form-data" class="mt-auto">
                @csrf

                <div class="file-input-wrapper">
                    <label class="form-label-tech">📄 CV (PDF)</label>
                    <input type="file" name="cv" accept="application/pdf" class="form-control form-control-tech" required>
                </div>

                <div class="file-input-wrapper">
                    <label class="form-label-tech">✉️ LETTRE DE MOTIVATION (PDF)</label>
                    <input type="file" name="lettre_motivation" accept="application/pdf" class="form-control form-control-tech" required>
                </div>

                <button type="submit" class="btn-postuler w-100">
                     Envoyer ma candidature
                </button>
            </form>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="p-5 text-center card-tech">
            <h3 class="text-secondary">Aucune mission n'est disponible pour le moment.</h3>
            <p class="mb-0">Revenez plus tard pour voir les nouvelles opportunités.</p>
        </div>
    </div>
@endforelse
</div>

@endsection