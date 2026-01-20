<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Global Background */
        body {
            background: radial-gradient(circle at top, #020617, #00041a); /* Un peu plus sombre pour faire ressortir les cartes */
            color: #e5e7eb;
            margin: 0;
            min-height: 100vh;
        }

        /* Le wrapper qui décale le contenu pour laisser la place à la Sidebar sur PC */
        .main-wrapper {
            margin-left: 280px; /* Largeur exacte de la sidebar */
            padding: 2rem;
            transition: all 0.3s ease;
        }

        /* Cartes dashboard (on garde ton style tech) */
        .card-tech {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(56, 189, 248, 0.2);
            border-radius: 16px;
            color: #e5e7eb;
            backdrop-filter: blur(10px);
        }

        /* RESPONSIVE : On annule la marge sur mobile */
        @media (max-width: 991.98px) {
            .main-wrapper {
                margin-left: 0 !important;
                padding: 1.5rem;
                padding-top: 80px; /* Espace pour le bouton burger */
            }
        }
    </style>
</head>

<body>

    {{-- 
        On n'inclut plus 'partials.menu' ici car il est 
        désormais intégré directement dans 'partials.navbar' 
    --}}
    @include('partials.navbar')

    {{-- CONTENU PRINCIPAL --}}
    <main class="main-wrapper">
        <div class="container-fluid">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>