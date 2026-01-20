<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription | Gestion des Stages</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>

    <style>
        /* 1. Background Ultra-Noir */
        body, html {
            height: 100%;
            margin: 0;
            background-color: #020617; 
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: white;
        }

        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
            pointer-events: all;
        }

        /* 2. Carte Inscription (Plus large que le login) */
        .register-card {
            position: relative;
            z-index: 10;
            background: rgba(15, 23, 42, 0.85); 
            border: 1px solid rgba(56, 189, 248, 0.3);
            border-radius: 28px;
            padding: 3rem;
            width: 100%;
            max-width: 650px; /* Plus large pour les formulaires complexes */
            box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.7);
            margin: 40px 20px;
            max-height: 90vh;
            overflow-y: auto;
        }

        /* Scrollbar stylisée pour la carte si elle déborde */
        .register-card::-webkit-scrollbar { width: 5px; }
        .register-card::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }

        /* 3. Inputs & Selects Tech */
        .form-label {
            color: #94a3b8;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            background: #0f172a !important;
            border: 1px solid #1e293b !important;
            color: white !important;
            padding: 12px 15px;
            border-radius: 12px;
        }

        .form-control:focus, .form-select:focus {
            border-color: #38bdf8 !important;
            box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.15) !important;
        }

        option { background: #0f172a; color: white; }

        /* 4. Bouton Glow */
        .glow-btn {
            background: #38bdf8;
            color: #020617;
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-weight: 800;
            font-size: 1.1rem;
            transition: 0.3s;
        }

        .glow-btn:hover {
            background: #7dd3fc;
            box-shadow: 0 0 30px rgba(56, 189, 248, 0.5);
            transform: translateY(-2px);
        }

        /* 5. Responsive */
        @media (max-width: 768px) {
            .register-card { padding: 2rem 1.5rem; }
            h2 { font-size: 1.8rem; }
        }
    </style>
</head>
<body>

    <div id="particles-js"></div>

    <div class="register-card">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-white mb-2">Créer un compte</h2>
            <p class="text-secondary small">Rejoignez la plateforme de gestion des stages</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="row">
                {{-- Nom --}}
                <div class="mb-3 col-md-6">
                    <label class="form-label">Nom complet</label>
                    <input type="text" name="name" class="form-control" required placeholder="Ex: Jean Dupont">
                </div>

                {{-- Email --}}
                <div class="mb-3 col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="nom@exemple.com">
                </div>
            </div>

            {{-- Rôle --}}
            <div class="mb-3">
                <label class="form-label text-info">Votre profil</label>
                <select name="role" id="roleSelect" class="form-select" required>
                    <option value="" disabled selected>-- Qui êtes-vous ? --</option>
                    <option value="etudiant">Étudiant</option>
                    <option value="encadrant">Encadrant pédagogique</option>
                    <option value="entreprise">Entreprise</option>
                </select>
            </div>

            <hr class="my-4 opacity-10">

            {{-- BLOC ÉTUDIANT / ENCADRANT --}}
<div id="academicBlock" class="d-none">
    <div class="row">
        <div class="mb-3 col-md-6">
            <label class="form-label">Spécialité</label>
            <select name="specialite" class="form-select">
                <option value="">-- Choisir une spécialité --</option>
                <option value="Informatique">Informatique</option>
                <option value="Réseaux">Réseaux</option>
                <option value="Génie logiciel">Génie logiciel</option>
                <option value="Data">Data</option>
                <option value="Cybersécurité">Cybersécurité</option>
                <option value="IA">IA</option> {{-- Ajouté ! --}}
            </select>
        </div>
        <div class="mb-3 col-md-6" id="niveauField">
            <label class="form-label">Niveau d'études</label>
            <input type="text" name="niveau" class="form-control" placeholder="Ex: L3, M1...">
        </div>
    </div>
</div>

            {{-- BLOC ENTREPRISE --}}
            <div id="entrepriseBlock" class="d-none">
                <div class="mb-3">
                    <label class="form-label">Nom de l’entreprise</label>
                    <input type="text" name="entreprise_nom" class="form-control" placeholder="Ex: Tech Solutions">
                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Secteur</label>
                        <input type="text" name="secteur" class="form-control" placeholder="Ex: Développement Web">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Ville</label>
                        <input type="text" name="ville" class="form-control" placeholder="Ex: Paris">
                    </div>
                </div>
            </div>

            {{-- MOT DE PASSE --}}
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" class="form-control" required placeholder="••••••••">
                </div>
                <div class="mb-4 col-md-6">
                    <label class="form-label">Confirmation</label>
                    <input type="password" name="password_confirmation" class="form-control" required placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="btn glow-btn w-100">CRÉER MON COMPTE</button>
        </form>

        <p class="text-center mt-4 text-secondary small">
            Déjà inscrit ? <a href="{{ route('login') }}" class="text-info text-decoration-none fw-bold">Se connecter</a>
        </p>
    </div>

    <script>
        // Gestion des champs dynamiques
        document.getElementById('roleSelect').addEventListener('change', function () {
            const role = this.value;
            const academic = document.getElementById('academicBlock');
            const entreprise = document.getElementById('entrepriseBlock');
            const niveau = document.getElementById('niveauField');

            // Reset
            academic.classList.add('d-none');
            entreprise.classList.add('d-none');

            if (role === 'etudiant' || role === 'encadrant') {
                academic.classList.remove('d-none');
                // Cacher le niveau si c'est un encadrant
                niveau.style.display = (role === 'encadrant') ? 'none' : 'block';
            } else if (role === 'entreprise') {
                entreprise.classList.remove('d-none');
            }
        });

        // Particules interactives
        particlesJS('particles-js', {
            particles: {
                number: { value: 90, density: { enable: true, value_area: 800 } },
                color: { value: "#38bdf8" },
                opacity: { value: 0.4, random: true },
                size: { value: 2.5, random: true },
                line_linked: { enable: true, distance: 150, color: "#38bdf8", opacity: 0.2, width: 1 },
                move: { speed: 1.5, random: true }
            },
            interactivity: {
                detect_on: "window",
                events: { onhover: { enable: true, mode: "repulse" } }
            }
        });
    </script>
</body>
</html>