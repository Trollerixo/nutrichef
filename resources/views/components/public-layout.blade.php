<!DOCTYPE html>
<html lang="{{ str_replace(''_'', ''-'', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NutriChef{{ isset($title) ? '' — ''. $title : '' }}</title>
    @vite([''resources/css/app.css'', ''resources/js/app.js''])
    @stack(''head'')
</head>
<body class="bg-light">

    {{-- Navbar Pública --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom fixed-top" style="height:64px; z-index:1030;">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">🍳 NutriChef</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs(''recipes.*'') ? ''active fw-bold'' : '''' }}" href="{{ route(''recipes.index'') }}">Recetas</a>
                    </li>
                </ul>
                <div class="d-flex gap-2">
                    @auth
                        <a href="{{ route(''dashboard'') }}" class="btn btn-outline-primary btn-sm px-3">
                            <i class="bi bi-speedometer2 me-1"></i> Mi Panel
                        </a>
                        <form method="POST" action="{{ route(''logout'') }}" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-box-arrow-right"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route(''login'') }}" class="btn btn-outline-secondary btn-sm px-3">Iniciar Sesión</a>
                        <a href="{{ route(''register'') }}" class="btn btn-dark btn-sm px-3">Registrarse</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main style="margin-top: 80px; min-height: calc(100vh - 80px);">
        <div class="container py-4">
            {{ $slot }}
        </div>
    </main>

    <footer class="bg-white border-top py-4 mt-5">
        <div class="container text-center">
            <p class="text-muted mb-0 small">&copy; {{ date(''Y'') }} NutriChef. Todos los derechos reservados.</p>
        </div>
    </footer>

    @stack(''scripts'')
</body>
</html>
