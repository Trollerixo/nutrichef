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
                        <div class="dropdown" x-data="publicNotificationBell()">
                            <button class="btn btn-link text-secondary p-1 position-relative" type="button" @click="toggle()" aria-label="Notificaciones">
                                <i class="bi bi-bell fs-5"></i>
                                <span x-show="unread > 0" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; min-width: 18px;" x-text="unread"></span>
                            </button>
                            <div x-show="open" @click.outside="open = false" class="dropdown-menu dropdown-menu-end shadow border-0 show" style="width: 360px; max-height: 400px; overflow-y: auto; right: 0; left: auto;">
                                <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                                    <strong class="small">Notificaciones</strong>
                                    <button @click="markAllRead()" x-show="unread > 0" class="btn btn-link p-0 text-decoration-none small" style="color:#6c757d;">Marcar todas leídas</button>
                                </div>
                                <div x-show="loading" class="text-center py-4 text-muted small">Cargando...</div>
                                <template x-for="item in items" :key="item.id">
                                    <div class="dropdown-item border-bottom px-3 py-2" :class="item.read ? '' : 'bg-light'">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div class="min-w-0">
                                                <div class="small fw-semibold text-truncate" x-text="item.title"></div>
                                                <div class="text-muted" style="font-size: 0.7rem;" x-text="item.message"></div>
                                                <div class="text-muted" style="font-size: 0.65rem;" x-text="item.time"></div>
                                            </div>
                                            <button x-show="!item.read" @click="markRead(item.id)" class="btn btn-link p-0 text-primary flex-shrink-0" title="Marcar leída">
                                                <i class="bi bi-check"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                                <div x-show="!loading && items.length === 0" class="text-center py-4 text-muted small">
                                    <i class="bi bi-bell-slash me-1"></i> Sin notificaciones
                                </div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-link text-dark text-decoration-none dropdown-toggle p-0" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
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

    @auth
        @php
            $pubBellUnread = auth()->user()->receivedNotifications()->wherePivot('read', false)->count();
            $pubBellItems = auth()->user()->receivedNotifications()
                ->withPivot(['read', 'read_at'])
                ->wherePivot('read', false)
                ->latest('sent_at')
                ->limit(5)
                ->get();
            $pubBellData = $pubBellItems->map(fn($n) => [
                'id' => $n->id,
                'title' => $n->title,
                'message' => \Illuminate\Support\Str::limit($n->message, 80),
                'time' => $n->sent_at?->diffForHumans(),
                'read' => (bool) $n->pivot?->read,
            ]);
        @endphp
        <script id="pub-bell-data" type="application/json">@json($pubBellData)</script>
    @endauth

    <script>
    function publicNotificationBell() {
        const readBase = '{{ url('/notificaciones') }}/';
        return {
            open: false,
            unread: {{ auth()->check() ? $pubBellUnread : 0 }},
            items: JSON.parse(document.getElementById('pub-bell-data')?.textContent || '[]'),
            toggle() {
                this.open = !this.open;
            },
            markRead(id) {
                fetch(readBase + id + '/leer', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                }).then(r => r.json()).then(() => {
                    this.items = this.items.filter(i => i.id !== id);
                    this.unread = Math.max(0, this.unread - 1);
                });
            },
            markAllRead() {
                fetch(readBase + 'leer-todas', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                }).then(r => r.json()).then(() => {
                    this.items = [];
                    this.unread = 0;
                });
            },
        };
    }
    </script>

    @stack('scripts')
</body>
</html>
