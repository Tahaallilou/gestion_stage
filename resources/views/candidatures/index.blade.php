@extends('layouts.tech')

@section('title', 'Gestion des candidatures')

@section('content')

<style>
    .page-header-candi { margin-bottom: 30px; }
    .page-header-candi h2 {
        font-weight: 800;
        background: linear-gradient(to right, #fff, #38bdf8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Conteneur principal avec fond sombre uni pour le contraste */
    .table-container-tech {
        background: #020617; /* Noir profond pour faire ressortir le blanc */
        border: 1px solid #1e293b;
        border-radius: 16px;
        overflow: hidden;
    }

    .table-tech {
        margin-bottom: 0;
        color: #000000ff !important; /* Texte blanc pur */
    }

    .table-tech thead {
        background: #0f172a; /* Gris très sombre pour l'entête */
        border-bottom: 1px solid #1e293b;
    }

    /* Intitulés en bleu ciel électrique pour une lisibilité maximale */
    .table-tech thead th {
        padding: 20px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #7dd3fc !important; 
        border: none;
    }

    .table-tech tbody td {
        padding: 20px;
        border-bottom: 1px solid #1e293b;
        vertical-align: middle;
        color:  #09049fff !important;
    }

    /* Status Badges Haute Visibilité */
    .status-badge {
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        display: inline-block;
    }
    .badge-wait { background: #f59e0b; color: #000; } /* Ambre sur noir */
    .badge-ok { background: #10b981; color: #fff; }   /* Vert émeraude */
    .badge-no { background: #ef4444; color: #fff; }   /* Rouge vif */

    /* Boutons de documents */
    .btn-doc {
        padding: 8px 14px;
        font-size: 0.8rem;
        border-radius: 8px;
        transition: 0.3s;
        text-decoration: none;
        font-weight: 700;
        border: none;
    }
    .btn-cv { background: #38bdf8; color: #ffffffff; } /* Bleu ciel vif */
    .btn-lettre { background: #475569; color: #ffffffff; }

    .btn-decision {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        font-weight: bold;
        transition: 0.2s;
    }
</style>

<div class="page-header-candi">
    <h2>📥 Candidatures</h2>
    <p style="color: #7dd3fc; font-size: 0.9rem;">Gestion centralisée des demandes et suivi des recrutements.</p>
</div>

<div class="table-container-tech shadow-lg">
    <div class="table-responsive">
        <table class="table table-tech align-middle">
            <thead>
                <tr>
                    @if(auth()->user()->role === 'entreprise')
                        <th>Étudiant</th>
                    @endif
                    <th>Offre de stage</th>
                    <th>Entreprise</th>
                    <th class="text-center">État du dossier</th>
                    @if(auth()->user()->role === 'entreprise')
                        <th class="text-center">Documents</th>
                        <th class="text-center">Décision</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($candidatures as $c)
                <tr>
                    @if(auth()->user()->role === 'entreprise')
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div style="width: 35px; height: 35px; background: #1e293b; border: 1px solid #38bdf8; color: #38bdf8; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 800;">
                                    {{ strtoupper(substr($c->etudiant_nom, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $c->etudiant_nom }}</div>
                                    <div style="color: #7dd3fc; font-size: 0.75rem;">{{ $c->etudiant_specialite }}</div>
                                </div>
                            </div>
                        </td>
                    @endif

                    <td class="fw-bold text-white">{{ $c->offre }}</td>

                    <td style="color: #cbd5e1;">{{ $c->entreprise ?? '—' }}</td>

                    <td class="text-center">
                        <span class="status-badge 
                            {{ $c->statut === 'en_attente' ? 'badge-wait' : '' }}
                            {{ $c->statut === 'acceptee' ? 'badge-ok' : '' }}
                            {{ $c->statut === 'refusee' ? 'badge-no' : '' }}">
                            {{ $c->statut === 'en_attente' ? '⌛ En attente' : '' }}
                            {{ $c->statut === 'acceptee' ? '✔ Acceptée' : '' }}
                            {{ $c->statut === 'refusee' ? '✖ Refusée' : '' }}
                        </span>
                    </td>

                    @if(auth()->user()->role === 'entreprise')
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ asset('storage/'.$c->cv) }}" class="btn-doc btn-cv" target="_blank">📄 CV</a>
                                <a href="{{ asset('storage/'.$c->lettre_motivation) }}" class="btn-doc btn-lettre" target="_blank">✉ Lettre</a>
                            </div>
                        </td>

                        <td class="text-center">
                            @if($c->statut === 'en_attente')
                                <div class="d-flex justify-content-center gap-2">
                                    <form action="/accepter-candidature/{{ $c->id }}" method="POST">
                                        @csrf
                                        <button class="btn-decision btn-success shadow-sm" title="Accepter">✔</button>
                                    </form>
                                    <form action="/refuser-candidature/{{ $c->id }}" method="POST">
                                        @csrf
                                        <button class="btn-decision btn-danger shadow-sm" title="Refuser">✖</button>
                                    </form>
                                </div>
                            @else
                                <span style="color: #475569; font-size: 0.8rem; font-style: italic;">Traité</span>
                            @endif
                        </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center p-5" style="color: #475569;">
                        
                        Aucune candidature enregistrée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection