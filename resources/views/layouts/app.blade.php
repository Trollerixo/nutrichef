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

    {{-- Top bar --}}
    <nav class="navbar navbar-light bg-white border-bottom fixed-top shadow-sm" style="height:64px;z-index:1030;">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold text-primary" href="/" style="color: var(--nc-primary) !important;">
                🍳 NutriChef
            </a>
            <span class="text-secondary small">
                <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
            </span>
        </div>
    </nav>

    <div class="d-flex" style="margin-top:64px;min-height:calc(100vh - 64px);">

        {{-- Sidebar --}}
        <aside class="nc-sidebar bg-white border-end d-flex flex-column"
               style="width:280px;flex-shrink:0;min-height:calc(100vh - 64px);position:relative;">
            <div class="p-3 flex-grow-1">
                @include('layouts.navigation')
            </div>
            {{-- User footer --}}
            <div class="p-3 border-top">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-person-circle fs-4 me-2 text-secondary"></i>
                    <div style="line-height:1.2;min-width:0;">
                        <div class="fw-semibold small text-truncate">{{ Auth::user()->name }}</div>
                        <div class="text-muted text-truncate" style="font-size:.75rem;">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main content --}}
        <main class="flex-grow-1 p-4">
            {{ $slot }}
        </main>

    </div>
    <x-confirm-modal />
    @stack('scripts')
</body>
</html>
