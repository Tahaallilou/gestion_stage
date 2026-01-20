<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme de gestion des stages</title>
    
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>

    <style>
        /* Match the Login Page Background */
        body, html {
            background-color: #020617; /* Solid Midnight Blue */
            margin: 0;
            color: white;
            overflow-x: hidden;
        }

        /* PC Version: 90% Width */
        @media (min-width: 1200px) {
            .custom-wide-container {
                width: 90% !important;
                max-width: 1800px;
                margin: 0 auto;
            }
        }

        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 60px 0;
            position: relative;
            z-index: 2; /* Ensures content is above particles */
        }

        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
        }

        /* Mobile Specific Centering Fix */
        @media (max-width: 991.98px) {
            .hero {
                text-align: center;
            }
            .cta {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }
            .cta .btn {
                margin: 0 !important;
                width: 100%;
                max-width: 320px;
            }
            .badge-tech {
                margin: 0 auto 1rem auto !important;
            }
        }
    </style>
</head>

<body class="landing">

<div id="particles-js"></div>

<div class="container-fluid custom-wide-container hero">
    <div class="row align-items-center justify-content-center w-100 g-5 m-0">
        
        {{-- LEFT COLUMN --}}
        <div class="col-lg-6 col-12 d-flex flex-column align-items-center align-items-lg-start">
            <span class="badge-tech mb-3">
                Projet académique
            </span>

            <h1 class="display-4 fw-bold mt-3 text-white">
                Plateforme de<br>Gestion des Stages
            </h1>

            <p class="lead mt-4 text-secondary">
                Application web permettant la gestion complète du cycle de stage :
                publication des offres, candidatures, affectations, suivi
                pédagogique et évaluation.  
                Le système s’appuie sur une logique métier automatisée.
            </p>

            <div class="mt-4 cta w-100">
                <a href="{{ route('login') }}" class="btn glow-btn btn-lg me-lg-3">
                    Accéder à la plateforme
                </a>
                <a href="{{ route('register') }}" class="btn glow-btn2 btn-lg">
                    Créer un compte
                </a>
            </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="col-lg-6 col-12">
            <div class="dashboard-preview p-4 p-md-5 rounded shadow-lg mx-auto" style="max-width: 600px; background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(56, 189, 248, 0.3);">

                <h3 class="mb-4 text-info fw-bold text-center">
                 Aperçu en temps réel
                </h3>

                <div class="row g-3">
                    <div class="col-6">
                        <div class="stat-card">
                            <span class="stat-value text-white">{{ $stats['etudiants'] }}</span>
                            <span class="stat-label">Étudiants</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <span class="stat-value text-white">{{ $stats['entreprises'] }}</span>
                            <span class="stat-label">Entreprises</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <span class="stat-value text-white">{{ $stats['offres'] }}</span>
                            <span class="stat-label">Offres</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card">
                            <span class="stat-value text-white">{{ $stats['stages'] }}</span>
                            <span class="stat-label">Stages actifs</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    particlesJS('particles-js', {
        particles: {
            number: { value: 100, density: { enable: true, value_area: 800 } },
            color: { value: '#38bdf8' },
            shape: { type: "circle" },
            opacity: { value: 0.4, random: true },
            size: { value: 2.5, random: true },
            line_linked: { 
                enable: true, 
                distance: 150, 
                color: "#38bdf8", 
                opacity: 0.3, 
                width: 1 
            },
            move: { 
                enable: true, 
                speed: 1.2, 
                direction: "none", 
                random: true, 
                out_mode: "out" 
            }
        },
        interactivity: {
            detect_on: "canvas",
            events: {
                onhover: { enable: true, mode: "repulse" },
                onclick: { enable: true, mode: "push" }
            },
            modes: {
                repulse: { distance: 100, duration: 0.4 }
            }
        },
        retina_detect: true
    });
</script>

</body>
</html>