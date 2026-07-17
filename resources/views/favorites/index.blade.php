<x-app-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Tus favoritas</h1>
            <p class="text-muted mb-0">Recetas guardadas para volver cuando quieras.</p>
        </div>
        <a href="{{ route('recipes.index') }}" class="btn btn-dark btn-sm">
            <img src="{{ asset('images/icons/buscar_receta.svg') }}" alt="" class="nc-icon-sm me-1"> Buscar más
        </a>
    </div>

    <div class="row g-4">
        @forelse ($recipes as $recipe)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('recipes.show', $recipe) }}" class="text-decoration-none text-dark">
                    <div class="card h-100 border-0 shadow-sm">
                        @if($recipe->image_url)
                            <div class="ratio ratio-4x3 rounded-top overflow-hidden">
                                <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="w-100 h-100" style="object-fit: cover;">
                            </div>
                        @else
                            <div class="nc-img-placeholder rounded-top" style="height: 160px;">🍽️</div>
                        @endif
                        <div class="card-body">
                            <span class="nc-badge mb-2 d-inline-block">{{ $recipe->category?->name ?? 'General' }}</span>
                            <h5 class="fw-bold mb-2 h6">{{ $recipe->title }}</h5>
                            <div class="d-flex gap-3 text-muted small">
                                <span><i class="bi bi-clock"></i> {{ $recipe->prep_time_min ?? '-' }} min</span>
                                <span><i class="bi bi-droplet"></i> {{ $recipe->calories ?? '-' }} kcal</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-heart fs-1 text-muted opacity-25" style="font-size: 5rem !important;"></i>
                </div>
                <h4 class="fw-bold text-muted">Aún no tienes favoritas</h4>
                <p class="text-muted mb-4">Explora nuestro catálogo y guarda las recetas que más te gusten para tenerlas siempre a mano.</p>
                <a href="{{ route('recipes.index') }}" class="btn btn-dark px-4">
                    Explorar recetas ahora
                </a>
            </div>
        @endforelse
    </div>
</x-app-layout>
