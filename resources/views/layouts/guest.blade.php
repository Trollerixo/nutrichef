<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NutriChef{{ isset($title) ? ' — '.$title : '' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">

    <nav class="navbar navbar-light bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">🍳 NutriChef</a>
            <div class="d-flex gap-2">
                <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">Iniciar Sesión</a>
                <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-sm">Registrarse</a>
            </div>
        </div>
    </nav>

    <main class="py-5">
        <div class="container">
            {{ $slot }}
        </div>
    </main>

</body>
</html>
