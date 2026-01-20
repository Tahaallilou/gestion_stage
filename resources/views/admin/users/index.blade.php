@extends('layouts.tech')

@section('title', 'Gestion des comptes')

@section('content')

<style>
    /* Stats Cards Custom */
    .stat-card {
        background: #0f172a;
        border: 1px solid #1e293b;
        border-radius: 15px;
        padding: 1.5rem;
        transition: transform 0.3s ease;
    }
    .stat-card:hover { transform: translateY(-5px); border-color: #38bdf8; }
    .stat-number { font-size: 1.8rem; font-weight: 800; color: #fff; display: block; }
    .stat-label { color: #94a3b8; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; }

    /* Table & UI */
    .user-container {
        background: #0f172a;
        border: 1px solid #1e293b;
        border-radius: 20px;
        overflow: hidden;
    }
    .table-tech { color: #fff !important; margin-bottom: 0; }
    .table-tech thead { background: #1e293b; }
    .table-tech th { padding: 1.2rem; font-size: 0.75rem; text-transform: uppercase; color: #7dd3fc !important; }
    .table-tech td { padding: 1.2rem; border-bottom: 1px solid rgba(255,255,255,0.05); }

    /* Inputs & Buttons */
    .form-control-tech, .form-select-tech {
        background: #020617 !important;
        border: 1px solid #334155 !important;
        color: #fff !important;
        border-radius: 10px;
    }
    .status-badge { padding: 5px 10px; border-radius: 6px; font-weight: 700; font-size: 0.65rem; text-transform: uppercase; }
    .bg-active { background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid #10b981; }
    .bg-suspended { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid #ef4444; }
</style>

<div class="mb-4">
    <h2 class="fw-bold" style="background: linear-gradient(to right, #fff, #38bdf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">👥 Gestion des Comptes</h2>
    <p class="text-secondary">Contrôle global des accès et des permissions du système.</p>
</div>

{{-- STATS SECTION --}}
<div class="row g-3 mb-5">
    <div class="col-md-2 col-6">
        <div class="stat-card">
            <span class="stat-label">Total</span>
            <span class="stat-number">{{ $stats['users'] }}</span>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="stat-card" style="border-left: 4px solid #f59e0b;">
            <span class="stat-label"> Étudiants</span>
            <span class="stat-number">{{ $stats['etudiants'] }}</span>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="stat-card" style="border-left: 4px solid #10b981;">
            <span class="stat-label"> Entrep.</span>
            <span class="stat-number">{{ $stats['entreprises'] }}</span>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card" style="border-left: 4px solid #38bdf8;">
            <span class="stat-label"> Encadrants</span>
            <span class="stat-number">{{ $stats['encadrants'] }}</span>
        </div>
    </div>
    <div class="col-md-3 col-12">
        <div class="stat-card" style="border-left: 4px solid #ef4444;">
            <span class="stat-label"> Admins</span>
            <span class="stat-number">{{ $stats['admins'] }}</span>
        </div>
    </div>
</div>

{{-- ALERTES --}}
@if(session('success'))
    <div class="alert alert-success bg-dark border-success text-success mb-4">✅ {{ session('success') }}</div>
@endif

{{-- BARRE DE FILTRES --}}
<div class="p-4 mb-4" style="background: #0f172a; border-radius: 15px; border: 1px solid #1e293b;">
    <form class="row g-3 align-items-center">
        <div class="col-md-5">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control form-control-tech" placeholder="🔍 Rechercher nom, email ou ID...">
        </div>
        <div class="col-md-3">
            <select name="role_filter" class="form-select form-select-tech">
                <option value="">Tous les rôles</option>
                <option value="etudiant" {{ request('role_filter') == 'etudiant' ? 'selected' : '' }}>Étudiant</option>
                <option value="entreprise" {{ request('role_filter') == 'entreprise' ? 'selected' : '' }}>Entreprise</option>
                <option value="encadrant" {{ request('role_filter') == 'encadrant' ? 'selected' : '' }}>Encadrant</option>
                <option value="admin" {{ request('role_filter') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100 fw-bold rounded-3">FILTRER</button>
        </div>
        <div class="col-md-2 text-end">
            <a href="/admin/users" class="text-secondary small text-decoration-none">Réinitialiser</a>
        </div>
    </form>
</div>

{{-- TABLEAU --}}
<div class="user-container shadow-lg">
    <div class="table-responsive">
        <table class="table table-tech align-middle">
            <thead>
                <tr>
                    <th>Identité</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td>
                        <div class="fw-bold ">{{ $user->name }}</div>
                        <code class="text-secondary small">#USR-{{ $user->id }}</code>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge bg-dark border border-secondary">{{ strtoupper($user->role) }}</span>
                    </td>
                    <td>
                        <span class="status-badge {{ $user->is_active ? 'bg-active' : 'bg-suspended' }}">
                            {{ $user->is_active ? 'Actif' : 'Suspendu' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            {{-- CHANGER ROLE --}}
                            <form method="POST" action="/admin/users/{{ $user->id }}/role" class="d-flex gap-1">
                                @csrf
                                <select name="role" class="form-select form-select-sm form-select-tech" style="width: 110px;">
                                    <option value="etudiant" {{ $user->role == 'etudiant' ? 'selected' : '' }}>Étudiant</option>
                                    <option value="entreprise" {{ $user->role == 'entreprise' ? 'selected' : '' }}>Entreprise</option>
                                    <option value="encadrant" {{ $user->role == 'encadrant' ? 'selected' : '' }}>Encadrant</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <button class="btn btn-sm btn-outline-warning">ok</button>
                            </form>

                            {{-- TOGGLE STATUS --}}
                            <form method="POST" action="/admin/users/{{ $user->id }}/toggle">
                                @csrf
                                <button class="btn btn-sm {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" 
                                        title="{{ $user->is_active ? 'Suspendre' : 'Activer' }}">
                                    {{ $user->is_active ? 'Suspendre' : 'Activer' }}
                                </button>
                            </form>

                            {{-- DELETE --}}
                            <form method="POST" action="/admin/users/{{ $user->id }}" 
                                  onsubmit="return confirm('Attention : Action irréversible !')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center p-5 text-secondary">Aucun utilisateur trouvé.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- PAGINATION --}}
<div class="mt-4 d-flex justify-content-center">
    {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>

@endsection