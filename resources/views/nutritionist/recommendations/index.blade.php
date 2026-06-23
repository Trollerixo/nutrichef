<x-app-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Recomendaciones</h1>
            <p class="text-muted mb-0">Recetas recomendadas a tus pacientes.</p>
        </div>
        <a href="{{ route('nutritionist.recommendations.create') }}" class="btn btn-outline-secondary">Nueva recomendación</a>
    </div>

    @if ($recommendations->isEmpty())
        <div class="alert alert-secondary">Aún no has enviado recomendaciones.</div>
    @else
        <div class="row g-3">
            @foreach ($recommendations as $recommendation)
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <h5 class="fw-semibold">{{ $recommendation->recipe?->title ?? 'Receta eliminada' }}</h5>
                                <span class="badge bg-secondary text-uppercase">{{ $recommendation->sent_at->format('d M Y') }}</span>
                            </div>
                            <p class="text-muted small mb-2">Paciente: {{ $recommendation->patient?->name ?? 'N/A' }}</p>
                            <p class="mb-0">{{ $recommendation->message }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
