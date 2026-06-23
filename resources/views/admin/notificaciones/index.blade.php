<x-app-layout>
    <h1 class="fw-bold h2 mb-4">Notificaciones</h1>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Formulario de envío --}}
    <div class="card mb-5" style="max-width: 680px;">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.notificaciones.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="title" class="form-label">Titulo</label>
                    <input type="text"
                           id="title"
                           name="title"
                           class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title') }}"
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Mensaje</label>
                    <textarea id="message"
                              name="message"
                              rows="4"
                              class="form-control @error('message') is-invalid @enderror"
                              required>{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-outline-secondary">
                    Enviar a todos
                </button>
            </form>
        </div>
    </div>

    {{-- Enviadas recientemente --}}
    <h4 class="fw-bold mb-3">Enviadas recientemente</h4>

    @if ($recientes->isEmpty())
        <p class="text-muted">Aún no se han enviado notificaciones.</p>
    @else
        <div class="d-flex flex-column gap-3" style="max-width: 680px;">
            @foreach ($recientes as $notif)
                <div class="card">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <span class="fw-semibold">{{ $notif->title }}</span>
                            <span class="text-muted small ms-3 text-nowrap">
                                {{ $notif->sent_at?->format('Y-m-d') }} &mdash;
                                {{ $notif->target === 'all' ? 'Todos' : ucfirst($notif->target) }}
                            </span>
                        </div>
                        <p class="text-muted small mb-0 mt-1">{{ $notif->message }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
