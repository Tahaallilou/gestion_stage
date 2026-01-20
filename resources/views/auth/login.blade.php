<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | Gestion des Stages</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>

    <style>
        /* 1. Pure Dark Background */
        body, html {
            height: 100%;
            margin: 0;
            background-color: #020617; 
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            overflow: hidden;
        }

        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
        }

        /* 2. Bigger Responsive Tech Card */
        .login-card {
            position: relative;
            z-index: 10;
            background: rgba(15, 23, 42, 0.85); 
            border: 1px solid rgba(56, 189, 248, 0.3);
            border-radius: 28px;
            padding: 3.5rem; /* Increased padding */
            width: 100%;
            max-width: 500px; /* Increased width from 420px */
            box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.7);
        }

        /* 3. Inputs & Typography */
        h2 {
            font-size: 2.25rem; /* Larger Title */
            letter-spacing: -1px;
        }

        .form-label {
            color: #94a3b8;
            font-size: 0.9rem; /* Slightly larger label */
            font-weight: 700;
            letter-spacing: 1.2px;
            margin-bottom: 10px;
        }

        .form-control {
            background: #0f172a !important;
            border: 1px solid #1e293b !important;
            color: white !important;
            padding: 15px; /* Thicker inputs */
            border-radius: 12px;
            font-size: 1.1rem;
        }

        .form-control:focus {
            border-color: #38bdf8 !important;
            box-shadow: 0 0 0 5px rgba(56, 189, 248, 0.15) !important;
        }

        /* 4. Bigger Glow Button */
        .glow-btn {
            background: #38bdf8;
            color: #020617;
            border: none;
            border-radius: 12px;
            padding: 16px; /* Bigger button */
            font-weight: 800;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
            margin-top: 10px;
        }

        .glow-btn:hover {
            background: #7dd3fc;
            box-shadow: 0 0 30px rgba(56, 189, 248, 0.5);
            transform: translateY(-2px);
        }

        /* 5. Responsive Adjustment */
       @media (max-width: 576px) {
    .login-card {
        /* Adds more space inside the card */
        padding: 3rem 1.5rem !important; 
        /* Ensures the card doesn't hit the very edges of the screen */
        margin: 0 15px; 
    }
    
    .glow-btn {
        /* Makes the button slightly taller for easier tapping */
        padding: 18px !important; 
        font-size: 1rem !important;
    }

    .form-control {
        /* Slightly larger inputs for better thumb-accuracy */
        padding: 14px !important;
    }
}
    </style>
</head>
<body>

    <div id="particles-js"></div>

    <div class="login-card">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-white mb-2">Connexion</h2>
            <p class="text-secondary fw-medium">Portail de Gestion des Stages</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label class="form-label text-uppercase">Email</label>
                <input type="email" name="email" class="form-control" placeholder="nom@institution.fr" required autofocus>
            </div>

            <div class="mb-5">
                <label class="form-label text-uppercase">Mot de passe</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn glow-btn w-100">Se connecter</button>
        </form>

        <div class="text-center mt-5">
            <p class="text-secondary mb-0">
                Nouveau sur la plateforme ? <br class="d-block d-sm-none">
                <a href="{{ route('register') }}" class="text-info text-decoration-none fw-bold">Créer un compte</a>
            </p>
        </div>
    </div>

    <script>
        particlesJS('particles-js', {
            particles: {
                number: { value: 100, density: { enable: true, value_area: 800 } },
                color: { value: "#38bdf8" },
                opacity: { value: 0.4, random: true },
                size: { value: 2.5, random: true },
                line_linked: { enable: true, distance: 150, color: "#38bdf8", opacity: 0.2, width: 1 },
                move: { speed: 1.2, random: true }
            }
        });
    </script>
</body>
</html>