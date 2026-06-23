<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NutriChef{{ isset($title) ? ' — '.$title : '' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-light">
    <x-toast />

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4 text-primary d-flex align-items-center" href="/" style="color: var(--nc-primary) !important;">
                <span class="me-2">🍳</span> NutriChef
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    {{-- Navegación limpia --}}
                </ul>
                <div class="d-flex gap-2 align-items-center">
                    @auth
                        <div class="dropdown">
                            <button class="btn btn-link text-dark text-decoration-none dropdown-toggle p-0" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Mi panel</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>Cerrar sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm px-3">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="btn btn-dark btn-sm px-3">Registrarse</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            {{ $slot }}
        </div>
    </main>

    <footer class="py-4 bg-white border-top mt-5">
        <div class="container text-center text-muted small">
            &copy; {{ date('Y') }} NutriChef. Todos los derechos reservados.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
