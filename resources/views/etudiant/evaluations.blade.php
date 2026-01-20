@extends('layouts.tech')

@section('title', 'Mes évaluations')

@section('content')

<style>
    .page-header-eval {
        margin-bottom: 40px;
    }

    .page-header-eval h2 {
        font-weight: 800;
        background: linear-gradient(to right, #fff, #10b981);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Score Card Design */
    .eval-card {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(16, 185, 129, 0.1);
        border-radius: 24px;
        padding: 25px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
    }

    .eval-card:hover {
        border-color: #10b981;
        transform: scale(1.02);
        background: rgba(15, 23, 42, 0.8);
        box-shadow: 0 15px 30px rgba(16, 185, 129, 0.1);
    }

    /* Note Circle */
    .note-circle {
        width: 70px;
        height: 70px;
        background: rgba(16, 185, 129, 0.1);
        border: 2px solid #10b981;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #10b981;
        font-weight: 900;
        font-size: 1.2rem;
        box-shadow: 0 0 15px rgba(16, 185, 129, 0.2);
    }

    .eval-type {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #94a3b8;
        font-weight: 800;
    }

    .eval-title {
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
        margin-top: 5px;
    }

    .comment-box {
        background: rgba(2, 6, 23, 0.4);
        border-radius: 12px;
        padding: 15px;
        margin-top: 15px;
        font-style: italic;
        color: #cbd5e1;
        font-size: 0.9rem;
        border-left: 3px solid #10b981;
    }

    .empty-state {
        padding: 60px;
        background: rgba(30, 41, 59, 0.2);
        border: 2px dashed rgba(56, 189, 248, 0.1);
        border-radius: 30px;
        text-align: center;
    }
</style>

<div class="page-header-eval">
    <h2>📊 Mes évaluations</h2>
    <p class="text-secondary">Récapitulatif de vos performances et retours pédagogiques.</p>
</div>

@if($evaluations->isEmpty())
    <div class="empty-state">
        <div class="fs-1 mb-3">🧊</div>
        <h4 class="text-white fw-bold">Aucune note pour le moment</h4>
        <p class="text-secondary">Vos évaluations apparaîtront ici dès que vos tuteurs les auront validées.</p>
    </div>
@else

<div class="row g-4">
    @foreach($evaluations as $e)
    <div class="col-md-6 col-xl-4">
        <div class="eval-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <span class="eval-type">{{ $e->type }}</span>
                    <h5 class="eval-title">{{ $e->titre }}</h5>
                </div>
                <div class="note-circle">
                    {{ $e->note }}<span style="font-size: 0.6rem; margin-left: 2px;">/20</span>
                </div>
            </div>

            <div class="comment-box">
                @if($e->commentaire)
                    " {{ $e->commentaire }} "
                @else
                    <span class="opacity-50">Aucun commentaire laissé par l'évaluateur.</span>
                @endif
            </div>

            <div class="mt-3 d-flex align-items-center gap-2">
                <div style="height: 4px; flex-grow: 1; background: rgba(255,255,255,0.1); border-radius: 10px;">
                    <div style="height: 100%; width: {{ ($e->note / 20) * 100 }}%; background: #10b981; border-radius: 10px; box-shadow: 0 0 10px #10b981;"></div>
                </div>
                <span class="small text-secondary fw-bold">{{ ($e->note / 20) * 100 }}%</span>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endif

@endsection