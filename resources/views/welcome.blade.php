<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" rel="stylesheet" />


        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased d-flex justify-content-center align-items-center vh-100 bg-light">

        <div class="text-center">
            <h2 className="mb-3">Seja bem vindo ao sistema bibliotecario</h2>
            <div class="d-flex gap-3 justify-content-center">
                <a href="{{ route('login') }}" class="btn btn-success px-4">Login</a>
                <a href="{{ route('register') }}" class="btn btn-outline-success px-4">Register</a>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
