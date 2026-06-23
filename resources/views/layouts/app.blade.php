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
            <div class="d-flex align-items-center gap-3">
                {{-- Notificaciones --}}
                <div class="dropdown" x-data="notificationBell()">
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


            </div>
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

    @php
        $bellUnread = auth()->user()->receivedNotifications()->wherePivot('read', false)->count();
        $bellItems = auth()->user()->receivedNotifications()
            ->withPivot(['read', 'read_at'])
            ->wherePivot('read', false)
            ->latest('sent_at')
            ->limit(5)
            ->get();
        $bellData = $bellItems->map(fn($n) => [
            'id' => $n->id,
            'title' => $n->title,
            'message' => \Illuminate\Support\Str::limit($n->message, 80),
            'time' => $n->sent_at?->diffForHumans(),
            'read' => (bool) $n->pivot?->read,
        ]);
    @endphp

    <script>
    function notificationBell() {
        const readBase = '{{ url('/notificaciones') }}/';
        return {
            open: false,
            unread: {{ $bellUnread }},
            items: @json($bellData),
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
