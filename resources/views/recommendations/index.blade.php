<x-app-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Recomendaciones</h1>
            <p class="text-muted mb-0">Recetas que tus nutricionistas te han recomendado.</p>
        </div>
    </div>

    @if ($recommendations->isEmpty())
        <div class="alert alert-secondary">
            Aún no tienes recomendaciones de tus nutricionistas.
        </div>
    @else
        <div class="row g-3">
            @foreach ($recommendations as $rec)
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <h5 class="fw-semibold mb-0">
                                    @if($rec->recipe)
                                        <a href="{{ route('recipes.show', $rec->recipe) }}" class="text-decoration-none text-dark">
                                            {{ $rec->recipe->title }}
                                        </a>
                                    @else
                                        <span class="text-muted">Receta eliminada</span>
                                    @endif
                                </h5>
                                <span class="badge bg-secondary text-uppercase" style="font-size: 0.65rem;">{{ $rec->sent_at->format('d M Y') }}</span>
                            </div>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-person me-1"></i>{{ $rec->nutritionist?->name ?? 'Nutricionista' }}
                            </p>
                            @if($rec->message)
                                <p class="mt-2 mb-0 small">{{ $rec->message }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
