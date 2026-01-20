<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Authentification' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/theme.css') }}">

</head>

<body class="d-flex align-items-center justify-content-center" style="min-height:100vh">


    @yield('content')
</div>


</body>
</html>
