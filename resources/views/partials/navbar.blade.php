{{-- Bouton Burger (Visible uniquement sur mobile) --}}
<button class="mobile-toggle d-lg-none" id="sidebarToggle">
    <div class="burger-line"></div>
    <div class="burger-line" style="width: 18px;"></div>
    <div class="burger-line"></div>
</button>

{{-- Overlay pour fermer le menu mobile en cliquant à côté --}}
<div class="sidebar-overlay d-lg-none" id="sidebarOverlay"></div>

<aside class="sidebar-tech" id="mainSidebar">
    {{-- 1. LOGO & BRAND --}}
<div class="sidebar-brand">
    <div class="brand-icon overflow-hidden"> {{-- overflow-hidden pour que l'image ne dépasse pas des bords arrondis --}}
        <a class="navbar-brand m-0 p-0 d-block" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/bridge logo small.jpg') }}" alt="Bridge Logo" class="navbar-logo">
        </a>
    </div>
</div>

    {{-- 2. USER PROFILE BOX --}}
    <div class="user-profile-pill">
        <div class="position-relative">
            <div class="user-initials">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <span class="status-indicator"></span>
        </div>
        <div class="user-info">
            <div class="user-name">{{ auth()->user()->name }}</div>
            <div class="user-role">{{ auth()->user()->role }}</div>
        </div>
    </div>

    {{-- 3. NAVIGATION MENU --}}
    <div class="sidebar-content">
        <ul class="sidebar-menu">
            <li class="sidebar-title">Navigation</li>
            <li>
                <a href="{{ route('dashboard') }}" class="nav-link-tech {{ request()->is('dashboard*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-speedometer2"></i></span> Dashboard
                </a>
            </li>

            {{-- ADMIN --}}
            @if(auth()->user()->role === 'admin')
                <li class="sidebar-title">Administration</li>
                <li >
                    <a href="/admin/users" class="nav-link-tech {{ request()->is('admin/users*') ? 'active' : '' }}">
                         Gérer les comptes
                    </a>
                </li>
            @endif

            {{-- ÉTUDIANT --}}
            @if(auth()->user()->role === 'etudiant')
                <li class="sidebar-title">Espace Étudiant</li>
                <li><a href="/offres" class="nav-link-tech {{ request()->is('offres*') ? 'active' : '' }}"><span class="nav-icon"><i class="bi bi-file-earmark-text"></i></span> Offres</a></li>
                <li><a href="/candidatures" class="nav-link-tech {{ request()->is('candidatures*') ? 'active' : '' }}"><span class="nav-icon"><i class="bi bi-inbox"></i></span> Mes candidatures</a></li>
                <li><a href="/mon-stage" class="nav-link-tech {{ request()->is('mon-stage*') ? 'active' : '' }}"><span class="nav-icon"><i class="bi bi-mortarboard"></i></span> Mon stage</a></li>
                <li><a href="/mes-evaluations" class="nav-link-tech {{ request()->is('mes-evaluations*') ? 'active' : '' }}"><span class="nav-icon"><i class="bi bi-star"></i></span> Évaluations</a></li>
            @endif

            {{-- ENTREPRISE --}}
            @if(auth()->user()->role === 'entreprise')
                <li class="sidebar-title">Espace Entreprise</li>
                <li><a href="/entreprise/offres" class="nav-link-tech {{ request()->is('entreprise/offres*') ? 'active' : '' }}"><span class="nav-icon"><i class="bi bi-folder2-open"></i></span> Mes offres</a></li>
                <li><a href="/candidatures" class="nav-link-tech {{ request()->is('candidatures*') ? 'active' : '' }}"><span class="nav-icon"><i class="bi bi-person-lines-fill"></i></span> Candidatures</a></li>
                <li><a href="/entreprise/stagiaires" class="nav-link-tech {{ request()->is('entreprise/stagiaires*') ? 'active' : '' }}"><span class="nav-icon"><i class="bi bi-people"></i></span> Stagiaires</a></li>
            @endif

            {{-- ENCADRANT --}}
            @if(auth()->user()->role === 'encadrant')
                <li class="sidebar-title">Espace Encadrant</li>
                <li><a href="/encadrant/stages" class="nav-link-tech {{ request()->is('encadrant/stages*') ? 'active' : '' }}"><span class="nav-icon"><i class="bi bi-journal-bookmark"></i></span> Stages</a></li>
                <li><a href="/encadrant/evaluations" class="nav-link-tech {{ request()->is('encadrant/evaluations/index*') ? 'active' : '' }}"><span class="nav-icon"><i class="bi bi-pencil-square"></i></span> Évaluations</a></li>
            @endif
        </ul>
    </div>

    {{-- 4. LOGOUT SECTION --}}
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="btn-logout-tech">
                <span class="logout-icon"><i class="bi bi-power"></i></span>
                <span>DÉCONNEXION</span>
            </button>
        </form>
    </div>
</aside>

<style>
    /* BASE SIDEBAR STYLE */
    .sidebar-tech {
        width: 280px;
        height: 100vh;
        background: rgba(2, 6, 23, 0.95);
        backdrop-filter: blur(12px);
        border-right: 1px solid rgba(56, 189, 248, 0.2);
        display: flex;
        flex-direction: column;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 1050;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* BRAND / LOGO */
.sidebar-brand {
    padding: 1.5rem; /* Espace autour du bloc logo */
}

.brand-icon {
    width: 100%;
    height: 80px; /* Force une hauteur fixe pour le container */
    padding: 0 !important; /* SUPPRIME le padding pour que l'image touche les bords */
    border-radius: 15px;
    border: 1px solid rgba(56, 189, 248, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
}

.navbar-logo {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Remplit tout l'espace. Note: 'cover' peut couper un peu les bords, utilise 'fill' si tu veux tout voir quitte à déformer légèrement */
    display: block;
}
    /* PROFILE PILL */
    .user-profile-pill {
        margin: 0 1.5rem 2rem 1.5rem;
        padding: 1rem;
        background: rgba(30, 41, 59, 0.4);
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .user-initials {
        width: 40px;
        height: 40px;
        background: #0f172a;
        border: 1px solid #38bdf8;
        color: #38bdf8;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }
    .status-indicator {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 10px;
        height: 10px;
        background: #10b981;
        border: 2px solid #020617;
        border-radius: 50%;
        box-shadow: 0 0 8px #10b981;
    }
    .user-name { color: white; font-weight: 700; font-size: 0.9rem; }
    .user-role { color: #38bdf8; font-size: 0.65rem; text-transform: uppercase; font-weight: 800; }

    /* MENU ITEMS */
    .sidebar-content { flex-grow: 1; overflow-y: auto; padding: 0 1rem; }
    .sidebar-title {
        color: #475569;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin: 1.5rem 0 0.5rem 0.5rem;
    }
    .nav-link-tech {
        color: #94a3b8;
        text-decoration: none;
        padding: 0.8rem 1rem;
        display: flex;
        align-items: center;
        gap: 12px;
        border-radius: 12px;
        transition: 0.3s;
        font-weight: 500;
    }
    .nav-link-tech:hover, .nav-link-tech.active {
        background: rgba(56, 189, 248, 0.1);
        color: #38bdf8;
    }
    
    .nav-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    /* FOOTER & LOGOUT */
    .sidebar-footer { padding: 1.5rem; border-top: 1px solid rgba(255,255,255,0.05); }
    .btn-logout-tech {
        width: 100%;
        background: transparent;
        border: 1px solid rgba(248, 113, 113, 0.2);
        color: #f87171;
        padding: 0.8rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: 0.3s;
    }
    .btn-logout-tech:hover {
        background: rgba(248, 113, 113, 0.1);
        box-shadow: 0 0 15px rgba(248, 113, 113, 0.1);
    }
    
    .glow-btn {
        background: rgba(56, 189, 248, 0.1);
        border: 1px solid #38bdf8;
        color: #38bdf8;
        border-radius: 12px;
        padding: 10px;
        font-weight: 700;
        font-size: 0.8rem;
        transition: 0.3s;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
    }
    .glow-btn:hover {
        background: #38bdf8;
        color: #020617;
        box-shadow: 0 0 20px rgba(56, 189, 248, 0.4);
    }

    /* RESPONSIVE MOBILE */
    @media (max-width: 991.98px) {
        .sidebar-tech { left: -280px; }
        .sidebar-tech.show { left: 0; box-shadow: 20px 0 50px rgba(0,0,0,0.8); }
        .mobile-toggle {
            position: fixed;
            top: 15px;
            right: 15px;
            z-index: 1100;
            background: #0f172a;
            border: 1px solid #38bdf8;
            border-radius: 10px;
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .burger-line { width: 24px; height: 2px; background: #38bdf8; border-radius: 2px; }
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            z-index: 1040;
        }
        .sidebar-overlay.show { display: block; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('mainSidebar');
        const overlay = document.getElementById('sidebarOverlay');

        if(toggle) {
            toggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            });
        }

        if(overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        }
    });
</script>