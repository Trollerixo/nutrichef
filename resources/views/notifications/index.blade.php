<x-app-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Notificaciones</h1>
            <p class="text-muted mb-0">Todas las notificaciones enviadas por la administración.</p>
        </div>
        @php $unreadCount = auth()->user()->receivedNotifications()->wherePivot('read', false)->count(); @endphp
        @if($unreadCount > 0)
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-check2-all me-1"></i> Marcar todas como leídas
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse($notifications as $notification)
        @php
            $pivot = $notification->pivot;
            $isUnread = !$pivot->read;
        @endphp
        <div class="card mb-3 {{ $isUnread ? 'border-start border-start-4 border-primary' : '' }}" style="{{ $isUnread ? 'border-left-width: 4px;' : '' }}">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div class="flex-grow-1 min-w-0">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            @if($isUnread)
                                <span class="badge bg-primary rounded-pill" style="width: 8px; height: 8px; padding: 0;">&nbsp;</span>
                            @endif
                            <strong class="{{ $isUnread ? '' : 'text-muted' }}">{{ $notification->title }}</strong>
                            <span class="text-muted" style="font-size: 0.75rem;">·</span>
                            <span class="text-muted" style="font-size: 0.75rem;">{{ $notification->sent_at?->diffForHumans() }}</span>
                        </div>
                        <p class="mb-0 small {{ $isUnread ? 'text-dark' : 'text-muted' }}">{{ $notification->message }}</p>
                        @if($notification->sender)
                            <small class="text-muted mt-1 d-block" style="font-size: 0.7rem;">Enviada por {{ $notification->sender->name }}</small>
                        @endif
                    </div>
                    @if($isUnread)
                        <form method="POST" action="{{ route('notifications.read', $notification) }}" class="flex-shrink-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary btn-sm" title="Marcar como leída">
                                <i class="bi bi-check"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-secondary">
            <i class="bi bi-bell me-2"></i> No tienes notificaciones.
        </div>
    @endforelse

    <div class="mt-3">
        {{ $notifications->links() }}
    </div>
</x-app-layout>
