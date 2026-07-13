<x-app-layout>
    <h1 class="fw-bold h2 mb-4">Moderacion de comentarios</h1>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($comentarios->isEmpty())
        <p class="text-muted">No hay comentarios para moderar.</p>
    @else
        <div class="d-flex flex-column gap-3">
            @foreach ($comentarios as $comentario)
                <div class="card {{ $comentario->flagged ? 'border-warning bg-warning-subtle' : '' }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span class="fw-semibold small">
                                {{ $comentario->user?->name ?? 'Anónimo' }}
                                sobre
                                <em>{{ $comentario->recipe?->title ?? '(receta eliminada)' }}</em>
                            </span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted small">
                                    {{ $comentario->created_at->format('Y-m-d') }}
                                </span>
                                @if ($comentario->flagged)
                                    <i class="bi bi-flag-fill text-warning" title="Marcado como spam"></i>
                                @endif
                            </div>
                        </div>

                        <p class="mb-3">{{ $comentario->comment }}</p>

                        <div class="d-flex justify-content-end gap-2">
                            {{-- Marcar / desmarcar spam --}}
                            <form method="POST"
                                  action="{{ route('admin.comentarios.flag', $comentario) }}"
                                  class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="btn btn-sm {{ $comentario->flagged ? 'btn-warning' : 'btn-outline-warning' }}">
                                    <i class="bi bi-flag me-1"></i>
                                    {{ $comentario->flagged ? 'Desmarcar' : 'Spam' }}
                                </button>
                            </form>

                            {{-- Eliminar --}}
                            <form method="POST"
                                  action="{{ route('admin.comentarios.destroy', $comentario) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este comentario?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <img src="{{ asset('images/icons/eliminar_comentario.svg') }}" alt="" class="nc-icon-sm me-1">Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $comentarios->links() }}
        </div>
    @endif
</x-app-layout>
