@extends('layouts.tech')

@section('title', 'Registre des Évaluations')

@section('content')

<style>
    .header-section { margin-bottom: 30px; }
    .header-section h2 {
        font-weight: 800;
        background: linear-gradient(to right, #fff, #38bdf8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .eval-list-container {
        background: #0f172a;
        border: 1px solid #1e293b;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }

    .table-eval { margin-bottom: 0; color: #ffffff !important; }
    .table-eval thead { background: #1e293b; }

    .table-eval thead th {
        padding: 20px;
        color: #7dd3fc !important;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: none;
    }

    .table-eval tbody td {
        padding: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        vertical-align: middle;
    }

    /* Style du Badge Note */
    .note-badge {
        font-size: 1.1rem;
        font-weight: 900;
        color: #38bdf8;
        background: rgba(56, 189, 248, 0.1);
        padding: 8px 15px;
        border-radius: 10px;
        border: 1px solid rgba(56, 189, 248, 0.2);
        display: inline-block;
        min-width: 85px;
    }
    .note-high { color: #10b981; background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.2); }
    .note-mid { color: #fbbf24; background: rgba(251, 191, 36, 0.1); border-color: rgba(251, 191, 36, 0.2); }

    .comment-text {
        color: #94a3b8;
        font-size: 0.85rem;
        line-height: 1.5;
        max-width: 350px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .student-name {
        color: #ffffff;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 2px;
    }

    .mission-title {
        color: #38bdf8;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .stage-id-badge {
        font-family: 'Courier New', monospace;
        background: #020617;
        color: #475569;
        padding: 4px 8px;
        border-radius: 5px;
        font-size: 0.7rem;
    }
</style>

<div class="header-section">
    <h2>⭐ Registre des Évaluations</h2>
    <p style="color: #7dd3fc;">Suivi détaillé des performances académiques des stagiaires.</p>
</div>

<div class="eval-list-container">
    <div class="table-responsive">
        <table class="table table-eval align-middle">
            <thead>
                <tr>
                    <th style="width: 100px;">Réf.</th>
                    <th>Étudiant & Mission</th>
                    <th class="text-center">Note Finale</th>
                    <th>Appréciation / Commentaire</th>
                </tr>
            </thead>
            <tbody>
                @forelse($evaluations as $e)
                <tr>
                    {{-- ID STAGE --}}
                    <td>
                        <span class="stage-id-badge">#{{ str_pad($e->stage_id, 4, '0', STR_PAD_LEFT) }}</span>
                    </td>

                    {{-- INFOS ÉTUDIANT & STAGE --}}
                    <td>
                        <div class="student-name"></div>
                        <div class="mission-title">
                            <i class="fas fa-briefcase me-1 small"></i> {{ $e->stage_titre ?? 'Mission de stage' }}
                        </div>
                    </td>

                    {{-- NOTE --}}
                    <td class="text-center">
                        <div class="note-badge {{ $e->note >= 15 ? 'note-high' : ($e->note >= 10 ? 'note-mid' : '') }}">
                            {{ number_format($e->note, 2) }} <small style="font-size: 0.6rem; opacity: 0.7;">/ 20</small>
                        </div>
                    </td>

                    {{-- COMMENTAIRE --}}
                    <td>
                        <div class="comment-text" title="{{ $e->commentaire }}">
                            @if($e->commentaire)
                                {{ $e->commentaire }}
                            @else
                                <span class="text-muted italic small">Aucune observation saisie.</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center p-5">
                        
                        <div class="text-secondary">Aucune évaluation n'a encore été enregistrée.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 d-flex justify-content-between align-items-center">
    <a href="/dashboard/encadrant" class="btn btn-outline-light btn-sm rounded-pill px-4">
        ← Retour au Dashboard
    </a>
    <span class="text-secondary small">Total : <strong>{{ count($evaluations) }}</strong> évaluations</span>
</div>

@endsection